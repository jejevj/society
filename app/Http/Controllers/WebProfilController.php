<?php

namespace App\Http\Controllers;

use App\Services\DataService;
use Illuminate\Support\Facades\Redirect;
use App\Models\ReffOrganisasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class webProfilController extends Controller
{
    protected $dataService;

    public function __construct(DataService $dataService, Request $request)
    {
        $this->dataService = $dataService;
    }

    public function index(Request $request)
    {
        if (!$request->session()->has('id_user')) {
            return Redirect::to('/login');
        }
        $data = [
            'menu'       => 'Profil Saya',
            'menu_aktif' => 'profil||akun',
            'navbar'     => '',
            'breadcrumb' => '',
            'organisasi' => ReffOrganisasi::latest()->get(),
            'detail'     => DB::table('app_user')
                ->leftJoin('reff_role', 'reff_role.id_role', '=', 'app_user.role_id')
                ->where('id_user', session('id_user'))
                ->first(),
        ];
        return view('web.profil-user', $data);
    }

    public function updateProfilUserAction(Request $request)
    {
        $id = session('id_user');
        $validator = Validator::make($request->all(), [
            'nama'     => 'required|string|max:200',
            'username' => 'required|email|max:200|unique:app_user,username_user,' . $id . ',id_user',
            'foto'     => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ], [
            'username.required' => 'Email wajib diisi.',
            'username.email'    => 'Format email tidak valid.',
            'username.unique'   => 'Email sudah digunakan.',
            'foto.image'        => 'File harus berupa gambar.',
            'foto.mimes'        => 'Format gambar harus jpg, jpeg, png, atau gif.',
            'foto.max'          => 'Ukuran gambar maksimal 2MB.',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        $updateData = [
            'nama_user'      => $request->nama,
            'username_user'  => $request->username,
            'identitas_user' => $request->identitas,
            'telepon_user'   => $request->telepon,
            'pekerjaan_user' => $request->pekerjaan,
            'alamat_user'    => $request->alamat,
            'updated_at'     => now(),
        ];

        if ($request->hasFile('foto')) {
            $scan = $this->dataService->scanAntivirus($request->file('foto'));
            if (!$scan['success']) {
                return response()->json([
                    'success' => false,
                    'message' => $scan['message'],
                    'virus'   => $scan['virus'] ?? null,
                    'error'   => $scan['error'] ?? null,
                ], $scan['code']);
            }
            $filename = time() . '_' . $request->file('foto')->getClientOriginalName();
            $path     = $request->file('foto')->storeAs('organisasi', $filename, 'public');
            $updateData['file_identitas_user'] = $path;
        }

        $dt_exist = DB::table('app_user')->where('id_user', $id)->first();
        $update   = DB::table('app_user')->where('id_user', $id)->update($updateData);

        if ($update) {
            $this->dataService->createLogWeb($request, 'updateProfilUserAction', 'Berhasil ubah profil', json_encode($updateData), json_encode($dt_exist));
            return response()->json(['success' => true, 'message' => 'Profil berhasil diperbarui']);
        }
        $this->dataService->createLogWeb($request, 'updateProfilUserAction', 'Gagal ubah profil', json_encode($updateData), json_encode($dt_exist));
        return response()->json(['success' => false, 'message' => 'Profil gagal diperbarui']);
    }

    public function gantiPasswordUser(Request $request)
    {
        if (!$request->session()->has('id_user')) {
            return Redirect::to('/login');
        }
        $data = [
            'menu'       => 'Ganti Password',
            'menu_aktif' => 'password||akun',
            'navbar'     => '',
            'breadcrumb' => '',
            'detail'     => DB::table('app_user')
                ->leftJoin('reff_role', 'reff_role.id_role', '=', 'app_user.role_id')
                ->where('id_user', session('id_user'))
                ->first(),
        ];
        return view('web.ganti-password', $data);
    }

    public function updatePasswordUserAction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password'     => 'required|string|max:200',
            'new_password'     => [
                'required', 'string', 'min:8',
                'regex:/[A-Z]/', 'regex:/[a-z]/',
                'regex:/[0-9]/', 'regex:/[@$!%*#?&._-]/'
            ],
            'confirm_password' => 'required|same:new_password',
        ], [
            'new_password.min'        => 'Password minimal 8 karakter.',
            'new_password.regex'      => 'Password harus mengandung huruf besar, huruf kecil, angka, dan karakter khusus.',
            'confirm_password.same'   => 'Konfirmasi password tidak sesuai.',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        $user = DB::table('app_user')->where('id_user', session('id_user'))->first();

        if (!Hash::check($request->old_password, $user->password_user)) {
            return response()->json(['success' => false, 'message' => 'Password lama salah'], 422);
        }
        if (Hash::check($request->new_password, $user->password_user)) {
            return response()->json(['success' => false, 'message' => 'Password baru tidak boleh sama dengan password lama'], 422);
        }

        $id       = session('id_user');
        $dt_exist = DB::table('app_user')->where('id_user', $id)->first();
        $update   = DB::table('app_user')->where('id_user', $id)->update([
            'password_user' => Hash::make($request->new_password),
            'updated_at'    => now(),
        ]);

        if ($update) {
            $this->dataService->createLog($request, 'updatePasswordUserAction', 'Berhasil ubah password');
            return response()->json(['success' => true, 'message' => 'Password berhasil diperbarui']);
        }
        $this->dataService->createLog($request, 'updatePasswordUserAction', 'Gagal ubah password');
        return response()->json(['success' => false, 'message' => 'Password gagal diperbarui']);
    }

    // ─────────────────────────────────────────────────────────────────
    // RIWAYAT USER — riwayat event registrasi
    // ─────────────────────────────────────────────────────────────────
    public function riwayatUser(Request $request)
    {
        if (!$request->session()->has('id_user')) {
            return Redirect::to('/login');
        }

        $userId = session('id_user');

        $eventRegistrasi = DB::table('t_event_registrasi as r')
            ->join('t_event as e', 'e.kode_event', '=', 'r.kode_event')
            ->select(
                'r.id_registrasi',
                'r.kode_registrasi',
                'r.kode_event',
                'r.kode_cart',
                'r.status_registrasi',
                'r.payment_status',
                'r.midtrans_order_id',
                'r.total_bayar',
                'r.role_peserta',
                'r.confirmed_at',
                'r.created_at',
                'e.judul_event',
                'e.sub_judul_event',
                'e.lokasi_event',
                'e.tanggal_awal_event',
                'e.tanggal_akhir_event',
                DB::raw('COALESCE(e.harga_event, 0) as harga_event'),
                'e.background_event'  // kolom foto event yang benar
            )
            ->where('r.id_user', $userId)
            ->orderByDesc('r.created_at')
            ->get();

        foreach ($eventRegistrasi as $reg) {
            $reg->addons = DB::table('t_event_addon as a')
                ->join('t_event_paket as p', 'p.kode_paket', '=', 'a.kode_paket')
                ->select('p.judul_paket', 'p.harga_paket')
                ->where('a.id_user', $userId)
                ->where('a.kode_event', $reg->kode_event)
                ->get();
        }

        $midtransConfig = DB::table('app_midtrans_config')
            ->where('id_midtrans', 1)->where('is_active', 'Y')->first();

        $data = [
            'menu'            => 'Riwayat',
            'menu_aktif'      => 'riwayat||akun',
            'navbar'          => '',
            'breadcrumb'      => '',
            'set'             => DB::table('app_setting')->where('kode', 'SETT')->first(),
            'organisasi'      => ReffOrganisasi::latest()->get(),
            'eventRegistrasi' => $eventRegistrasi,
            'midtransConfig'  => $midtransConfig,
        ];

        return view('web.riwayat-user', $data);
    }
}
