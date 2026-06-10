<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PesertaRegisterController extends Controller
{
    private function setting(): ?object
    {
        return DB::table('app_setting')->where('kode', 'SETT')->first();
    }

    /** Tampilkan form registrasi peserta berdasarkan token */
    public function show(string $token)
    {
        $invite = DB::table('t_peserta_invite')
            ->where('token', $token)
            ->where('status', 'PENDING')
            ->where('expired_at', '>', now())
            ->first();

        if (!$invite) {
            abort(404, 'Link registrasi tidak valid atau sudah kadaluarsa.');
        }

        return view('web.home.peserta-register', [
            'token'        => $token,
            'emailPeserta' => $invite->email_peserta,
            'namaPeserta'  => $invite->nama_peserta,
            'nohp'         => $invite->no_hp_peserta,
            'instansi'     => $invite->instansi_peserta,
            'namaEvent'    => $invite->nama_event,
            'set'          => $this->setting(),
        ]);
    }

    /** Proses submit form registrasi peserta */
    public function submit(Request $request, string $token)
    {
        $invite = DB::table('t_peserta_invite')
            ->where('token', $token)
            ->where('status', 'PENDING')
            ->where('expired_at', '>', now())
            ->first();

        if (!$invite) {
            abort(404, 'Link registrasi tidak valid atau sudah kadaluarsa.');
        }

        $request->validate([
            'nama'                  => 'required|string|max:255',
            'telepon'               => 'required|string|max:50',
            'password'              => 'required|string|min:8|confirmed',
        ]);

        DB::beginTransaction();
        try {
            // Buat akun user baru
            $idUser = DB::table('app_user')->insertGetId([
                'nama_user'      => $request->nama,
                'username_user'  => $invite->email_peserta,
                'password_user'  => Hash::make($request->password),
                'telepon_user'   => $request->telepon,
                'organisasi_user'=> $request->instansi ?? $invite->instansi_peserta,
                'role_id'        => 2, // role user biasa
                'status_user'    => 1,
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);

            // Assign event ke user baru
            $this->assignEventToUser($idUser, $invite);

            // Tandai invite sebagai USED
            DB::table('t_peserta_invite')
                ->where('token', $token)
                ->update(['status' => 'USED', 'updated_at' => now()]);

            // Set session login otomatis
            session([
                'id_user'       => $idUser,
                'nama_user'     => $request->nama,
                'username_user' => $invite->email_peserta,
                'role_id'       => 2,
            ]);

            DB::commit();

            return redirect()->route('home')
                ->with('success', 'Akun berhasil dibuat! Anda telah terdaftar di ' . $invite->nama_event);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('PesertaRegister submit error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Terjadi kesalahan, silakan coba lagi.']);
        }
    }

    private function assignEventToUser(int $idUser, object $invite): void
    {
        // Cek sudah terdaftar belum
        $exists = DB::table('t_event_registrasi')
            ->where('kode_event', $invite->kode_event)
            ->where('id_user', $idUser)
            ->exists();

        if ($exists) return;

        // Ambil data registrasi peserta (dari kode_registrasi di invite)
        $reg = DB::table('t_event_registrasi')
            ->where('kode_registrasi', $invite->kode_registrasi)
            ->first();

        if ($reg) {
            // Update registrasi yang ada dengan id_user baru
            DB::table('t_event_registrasi')
                ->where('kode_registrasi', $invite->kode_registrasi)
                ->update([
                    'id_user'    => $idUser,
                    'updated_at' => now(),
                ]);
        } else {
            // Buat registrasi baru
            DB::table('t_event_registrasi')->insert([
                'kode_registrasi'   => 'REG' . date('ymdHis') . strtoupper(Str::random(4)),
                'kode_event'        => $invite->kode_event,
                'kode_cart'         => $invite->kode_cart,
                'id_user'           => $idUser,
                'nama_peserta'      => $invite->nama_peserta,
                'email_peserta'     => $invite->email_peserta,
                'no_hp_peserta'     => $invite->no_hp_peserta,
                'instansi_peserta'  => $invite->instansi_peserta,
                'total_bayar'       => $invite->total_bayar,
                'payment_status'    => 'PAID',
                'status_registrasi' => 'A',
                'confirmed_at'      => now(),
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);
        }
    }
}
