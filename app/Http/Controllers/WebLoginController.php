<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\OtpRegistrasiMail;

class WebLoginController extends Controller
{
    // ────────────────────────
    // Halaman Login
    // ────────────────────────
    public function index(Request $request)
    {
        $set = DB::table('t_setting')->first();
        return view('web.login', compact('set'));
    }

    // ────────────────────────
    // Halaman Register
    // ────────────────────────
    public function register(Request $request)
    {
        $set   = DB::table('t_setting')->first();
        $event = null;

        if ($request->has('event')) {
            $event = DB::table('t_event as e')
                ->where('e.kode_event', $request->event)
                ->where('e.status_event', 'Y')
                ->first();

            if ($event) {
                $event->kolaborasi = DB::table('t_kolaborasi_event')
                    ->where('event_kode_kolaborasi', $event->kode_event)
                    ->get();

                $event->paket = DB::table('t_paket_event')
                    ->where('event_kode_paket', $event->kode_event)
                    ->where('status_paket', 'Y')
                    ->get();
            }
        }

        return view('web.register', compact('set', 'event'));
    }

    // ────────────────────────
    // Aksi Registrasi (Step 1 → Step 2)
    // ────────────────────────
    public function registrasiAction(Request $request)
    {
        $request->validate([
            'nama_user'     => 'required|string|max:200',
            'email_user'    => 'required|email|unique:t_user,email_user',
            'password_user' => 'required|min:6|confirmed',
        ]);

        // Cek jika sudah ada event aktif dengan kode ini
        $kodeEvent = $request->input('kode_event');
        if ($kodeEvent) {
            $event = DB::table('t_event as e')
                ->where('e.kode_event', $kodeEvent)
                ->where('e.status_event', 'Y')
                ->first();
            if (!$event) $kodeEvent = null;
        }

        // Buat OTP 6 digit
        $otp     = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $expires = now()->addMinutes(10);

        // Simpan semua data di sesi (belum insert ke DB)
        session(['reg_pending' => [
            'nama'           => $request->nama_user,
            'email'          => $request->email_user,
            'no_hp'          => $request->no_hp_user,
            'organisasi'     => $request->organisasi_user,
            'jabatan'        => $request->jabatan_user,
            'password'       => $request->password_user,
            'kode_event'     => $kodeEvent,
            'otp'            => $otp,
            'otp_expires_at' => $expires,
            'otp_verified'   => false,
        ]]);

        // Kirim OTP via email
        try {
            Mail::to($request->email_user)->send(new OtpRegistrasiMail($otp, $request->nama_user));
        } catch (\Exception $e) {
            // Jangan gagalkan registrasi jika email error; log saja
            \Log::error('Gagal kirim OTP registrasi: ' . $e->getMessage());
        }

        return redirect()->route('register-event.otp');
    }

    // ────────────────────────
    // Verifikasi Token Akun (link email lama)
    // ────────────────────────
    public function verifikasiAkun(Request $request, $token)
    {
        $user = DB::table('t_user')->where('token_user', $token)->first();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Token tidak valid.');
        }
        DB::table('t_user')->where('token_user', $token)->update(['status_user' => 'Y', 'token_user' => null]);
        return redirect()->route('login')->with('success', 'Akun berhasil diverifikasi. Silakan masuk.');
    }

    // ────────────────────────
    // Aksi Login
    // ────────────────────────
    public function loginAction(Request $request)
    {
        $request->validate([
            'email_user'    => 'required|email',
            'password_user' => 'required',
        ]);

        $user = DB::table('t_user')
            ->where('email_user', $request->email_user)
            ->where('status_user', 'Y')
            ->first();

        if (!$user || !Hash::check($request->password_user, $user->password_user)) {
            return back()->with('error', 'Email atau password salah.');
        }

        // OTP login
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        DB::table('t_user')->where('id_user', $user->id_user)->update(['otp_user' => $otp]);

        try {
            Mail::to($user->email_user)->send(new OtpRegistrasiMail($otp, $user->nama_user));
        } catch (\Exception $e) {
            \Log::error('Gagal kirim OTP login: ' . $e->getMessage());
        }

        return redirect()->route('otpLogin', ['otp' => $otp]);
    }

    // ────────────────────────
    // Halaman OTP Login
    // ────────────────────────
    public function otpLogin(Request $request, $otp)
    {
        $set = DB::table('t_setting')->first();
        return view('web.otp-login', compact('set', 'otp'));
    }

    // ────────────────────────
    // Verifikasi OTP Login
    // ────────────────────────
    public function verifyOtpAction(Request $request)
    {
        $request->validate(['otp' => 'required']);

        $user = DB::table('t_user')->where('otp_user', $request->otp)->first();
        if (!$user) {
            return back()->with('error', 'Kode OTP tidak valid.');
        }

        DB::table('t_user')->where('id_user', $user->id_user)->update(['otp_user' => null]);
        session(['user' => $user]);

        return redirect()->route('home')->with('success', 'Login berhasil.');
    }

    // ────────────────────────
    // Lupa Password
    // ────────────────────────
    public function lupaPassword(Request $request)
    {
        $set = DB::table('t_setting')->first();
        return view('web.lupa-password', compact('set'));
    }

    public function lupaPasswordAction(Request $request)
    {
        $request->validate(['email_user' => 'required|email']);

        $user = DB::table('t_user')->where('email_user', $request->email_user)->first();
        if (!$user) {
            return back()->with('error', 'Email tidak ditemukan.');
        }

        $token = Str::random(64);
        DB::table('t_user')->where('email_user', $request->email_user)->update(['token_user' => $token]);

        // Kirim link reset (implementasi email di sini)
        return back()->with('success', 'Link reset password telah dikirim ke email Anda.');
    }

    public function passwordBaru(Request $request, $token)
    {
        $set = DB::table('t_setting')->first();
        return view('web.password-baru', compact('set', 'token'));
    }

    public function ganitPasswordAction(Request $request)
    {
        $request->validate([
            'token'            => 'required',
            'password_user'    => 'required|min:6|confirmed',
        ]);

        $user = DB::table('t_user')->where('token_user', $request->token)->first();
        if (!$user) {
            return back()->with('error', 'Token tidak valid atau sudah digunakan.');
        }

        DB::table('t_user')->where('token_user', $request->token)->update([
            'password_user' => Hash::make($request->password_user),
            'token_user'    => null,
        ]);

        return redirect()->route('login')->with('success', 'Password berhasil diperbarui.');
    }
}
