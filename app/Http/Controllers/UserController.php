<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Trouble;
use App\Models\Aktivasi;
use App\Models\Pengajuan;
use App\Models\Proxy;
use App\Models\Pembubuhan;
use App\Models\LuarDaerah;
use App\Models\UpdateData;
use App\Models\Kecamatan;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Constructor untuk mengatur zona waktu secara global di level Controller.
     */
    public function __construct()
    {
        date_default_timezone_set('Asia/Jakarta');
    }

    /**
     * Menampilkan halaman Dashboard User/Penduduk.
     */
    public function index()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $userId = $user->id;
        // Ambil hanya kecamatan yang aktif untuk ditampilkan di form dashboard
        $kecamatans = Kecamatan::where('status', 'aktif')->get();

        // Ambil data spesifik user
        $troubles = Trouble::where('user_id', $userId)->latest()->get();
        $aktivasis = Aktivasi::where('user_id', $userId)->latest()->get();
        $pengajuans = Pengajuan::where('user_id', $userId)->latest()->get();
        $proxies = Proxy::where('user_id', $userId)->latest()->get();
        $pembubuhans = Pembubuhan::where('user_id', $userId)->latest()->get();
        $luardaerahs = LuarDaerah::where('user_id', $userId)->latest()->get();
        $updatedatas = UpdateData::where('user_id', $userId)->latest()->get();

        // --- LOGIKA GROUPING UNTUK DASHBOARD ---

        // Grouping Kendala SIAK
        $siakCategories = Pengajuan::where('user_id', $userId)
            ->select('kategori as label', DB::raw('count(*) as total'))
            ->groupBy('kategori')
            ->get();

        // Grouping Aktivasi NIK/Akte
        $aktivasiCategories = Aktivasi::where('user_id', $userId)
            ->select('jenis_layanan as label', DB::raw('count(*) as total'))
            ->groupBy('jenis_layanan')
            ->get();

        // Grouping Luar Daerah
        $luarDaerahCategories = LuarDaerah::where('user_id', $userId)
            ->select(DB::raw('UPPER(jenis_dokumen) as label'), DB::raw('count(*) as total'))
            ->groupBy('jenis_dokumen')
            ->get();

        // Grouping Update Data
        $updateDataCategories = UpdateData::where('user_id', $userId)
            ->select('jenis_layanan as label', DB::raw('count(*) as total'))
            ->groupBy('jenis_layanan')
            ->get();

        $counts = [
            'trouble' => $troubles->count(),
            'aktivasi' => $aktivasis->count(),
            'pengajuan' => $pengajuans->count(),
            'proxy' => $proxies->count(),
            'pembubuhan' => $pembubuhans->count(),
            'luardaerah' => $luardaerahs->count(),
            'updatedata' => $updatedatas->count(),
        ];

        $allNotifications = collect();

        foreach ($troubles as $t) {
            $allNotifications->push((object) [
                'id' => $t->id,
                'group_key' => 'PC_CARD',
                'kategori' => 'PC - ' . strtoupper($t->kategori ?? 'GANGGUAN SISTEM'),
                'kategori_asli' => $t->kategori,
                'jenis_layanan' => 'KENDALA PC',
                'jenis_dokumen' => null,
                'pesan' => $t->deskripsi,
                'tanggapan_admin' => $t->tanggapan_admin,
                'is_rejected' => (bool) ($t->is_rejected ?? false),
                'created_at' => $t->created_at,
                'updated_at' => $t->updated_at,
                'type' => 'trouble',
                'status' => $t->status
            ]);
        }

        foreach ($aktivasis as $a) {
            $prefix = strtoupper($a->jenis_layanan ?? 'AKTIVASI');
            $allNotifications->push((object) [
                'id' => $a->id,
                'group_key' => 'NIK_CARD',
                'kategori' => 'NIK - ' . $prefix,
                'kategori_asli' => null,
                'jenis_layanan' => strtoupper($a->jenis_layanan),
                'jenis_dokumen' => null,
                'pesan' => ($a->alasan ?? "Permintaan " . $prefix . " NIK: " . $a->nik_aktivasi),
                'tanggapan_admin' => $a->tanggapan_admin,
                'is_rejected' => (bool) ($a->is_rejected ?? false),
                'created_at' => $a->created_at,
                'updated_at' => $a->updated_at,
                'type' => 'aktivasi',
                'status' => $a->status
            ]);
        }

        foreach ($pengajuans as $p) {
            $kategoriTampil = !empty($p->kategori) ? $p->kategori : $p->jenis_registrasi;
            $allNotifications->push((object) [
                'id' => $p->id,
                'group_key' => 'SIAK_CARD',
                'kategori' => 'SIAK - ' . strtoupper($kategoriTampil),
                'kategori_asli' => strtoupper($kategoriTampil),
                'jenis_layanan' => 'SIAK',
                'jenis_dokumen' => null,
                'pesan' => $p->deskripsi ?? "Laporan Kendala SIAK",
                'tanggapan_admin' => $p->tanggapan_admin,
                'is_rejected' => (bool) ($p->is_rejected ?? false),
                'created_at' => $p->created_at,
                'updated_at' => $p->updated_at,
                'type' => 'pengajuan',
                'status' => $p->status
            ]);
        }

        foreach ($proxies as $pr) {
            $allNotifications->push((object) [
                'id' => $pr->id,
                'group_key' => 'PROXY_CARD',
                'kategori' => 'PROXY - KENDALA IP',
                'kategori_asli' => 'KENDALA JARINGAN',
                'jenis_layanan' => 'PROXY',
                'jenis_dokumen' => null,
                'pesan' => $pr->deskripsi ?? $pr->ip_detail,
                'tanggapan_admin' => $pr->tanggapan_admin,
                'is_rejected' => (bool) ($pr->is_rejected ?? false),
                'created_at' => $pr->created_at,
                'updated_at' => $pr->updated_at,
                'type' => 'proxy',
                'status' => $pr->status
            ]);
        }

        foreach ($pembubuhans as $pb) {
            $jenisDok = strtoupper($pb->jenis_dokumen ?? 'PEMBUBUHAN');
            $nikTarget = $pb->nik_pemohon ?? ($pb->nik ?? '-');

            $allNotifications->push((object) [
                'id' => $pb->id,
                'group_key' => 'TTE_CARD',
                'kategori' => 'PEMBUBUHAN TTE',
                'kategori_asli' => $jenisDok,
                'jenis_layanan' => $jenisDok,
                'jenis_dokumen' => $jenisDok,
                'pesan' => "Layanan TTE: " . $jenisDok . " (NIK Pemohon: " . $nikTarget . ")",
                'tanggapan_admin' => $pb->tanggapan_admin,
                'is_rejected' => (bool) ($pb->is_rejected ?? ($pb->status === 'Rejected')),
                'created_at' => $pb->created_at,
                'updated_at' => $pb->updated_at,
                'type' => 'pembubuhan',
                'status' => $pb->status ?? 'Pending'
            ]);
        }

        foreach ($luardaerahs as $ld) {
            $allNotifications->push((object) [
                'id' => $ld->id,
                'group_key' => 'LUAR_CARD',
                'kategori' => 'LUAR DAERAH',
                'kategori_asli' => strtoupper($ld->jenis_dokumen),
                'jenis_layanan' => strtoupper($ld->jenis_dokumen),
                'jenis_dokumen' => strtoupper($ld->jenis_dokumen),
                'pesan' => "Layanan Luar Daerah: " . strtoupper($ld->jenis_dokumen) . " (Target: " . $ld->nik_luar_daerah . ")",
                'tanggapan_admin' => $ld->tanggapan_admin,
                'is_rejected' => (bool) ($ld->is_rejected ?? false),
                'created_at' => $ld->created_at,
                'updated_at' => $ld->updated_at,
                'type' => 'luardaerah',
                'status' => $ld->status ?? 'pending'
            ]);
        }

        foreach ($updatedatas as $ud) {
            $allNotifications->push((object) [
                'id' => $ud->id,
                'group_key' => 'UPDATE_CARD',
                'kategori' => 'UPDATE - ' . strtoupper($ud->jenis_layanan),
                'kategori_asli' => strtoupper($ud->jenis_layanan),
                'jenis_layanan' => 'UPDATE DATA',
                'jenis_dokumen' => null,
                'pesan' => $ud->deskripsi,
                'tanggapan_admin' => $ud->tanggapan_admin,
                'is_rejected' => (bool) ($ud->status === 'rejected'),
                'created_at' => $ud->created_at,
                'updated_at' => $ud->updated_at,
                'type' => 'updatedata',
                'status' => $ud->status
            ]);
        }

        $sortedNotifs = $allNotifications->sortByDesc('created_at')->values();

        return view('user.dashboard', [
            'user' => $user,
            'kecamatans' => $kecamatans,
            'allNotifications' => $sortedNotifs,
            'counts' => $counts,
            'troubles' => $troubles,
            'aktivasis' => $aktivasis,
            'pengajuans' => $pengajuans,
            'proxies' => $proxies,
            'pembubuhans' => $pembubuhans,
            'luardaerahs' => $luardaerahs,
            'updatedatas' => $updatedatas,
            'siakCategories' => $siakCategories,
            'aktivasiCategories' => $aktivasiCategories,
            'luarDaerahCategories' => $luarDaerahCategories,
            'updateDataCategories' => $updateDataCategories
        ]);
    }

    /**
     * 1. FITUR TROUBLE (PC)
     */
    public function storeTrouble(Request $request)
    {
        $request->validate([
            'kategori' => 'required|string',
            'deskripsi' => 'required|string',
            'foto_trouble' => 'required|array|min:1|max:10',
            'foto_trouble.*' => 'image|mimes:jpeg,png,jpg|max:5120',
        ], [
            'foto_trouble.required' => 'Wajib melampirkan minimal satu foto bukti gangguan.',
            'foto_trouble.max' => 'Maksimal lampiran yang diperbolehkan adalah 10 file.',
            'foto_trouble.*.image' => 'File harus berupa gambar (JPEG, PNG, JPG).',
            'foto_trouble.*.max' => 'Ukuran satu file foto tidak boleh lebih dari 1MB.'
        ]);

        $filePaths = [];
        if ($request->hasFile('foto_trouble')) {
            foreach ($request->file('foto_trouble') as $file) {
                if ($file->isValid()) {
                    $path = $file->store('troubles', 'public');
                    $filePaths[] = $path;
                }
            }
        }

        Trouble::create([
            'user_id' => Auth::id(),
            'nama_lengkap' => Auth::user()->name,
            'kategori' => strtoupper(trim($request->kategori)),
            'deskripsi' => $request->deskripsi,
            'foto_trouble' => json_encode($filePaths),
            'status' => 'Pending'
        ]);

        return back()->with('status', 'Laporan gangguan (PC/Trouble) berhasil dikirim!');
    }

    /**
     * 2. FITUR KENDALA SIAK
     */
    public function storePengajuan(Request $request)
    {
        $request->validate([
            'kategori_siak' => 'required|string',
            'deskripsi_siak' => 'required|string',
            'foto_dokumen_siak' => 'required|array|min:1|max:5',
            'foto_dokumen_siak.*' => 'image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $filePaths = [];
        if ($request->hasFile('foto_dokumen_siak')) {
            foreach ($request->file('foto_dokumen_siak') as $file) {
                if ($file->isValid()) {
                    $path = $file->store('pengajuan', 'public');
                    $filePaths[] = $path;
                }
            }
        }

        Pengajuan::create([
            'user_id' => Auth::id(),
            'nama_lengkap' => Auth::user()->name,
            'nik_aktivasi' => Auth::user()->nik,
            'jenis_registrasi' => 'SIAK',
            'kategori' => strtoupper(trim($request->kategori_siak)),
            'deskripsi' => $request->deskripsi_siak,
            'foto_dokumen' => json_encode($filePaths),
            'status' => 'Pending'
        ]);

        return back()->with('status', 'Laporan kendala SIAK berhasil dikirim!');
    }

    /**
     * 3. FITUR AKTIVASI NIK
     */
    public function storeAktivasi(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nik_aktivasi' => 'required|numeric|digits:16',
            'jenis_layanan' => 'required|in:RESTORE,AKTIVASI,restore,aktivasi',
            'alasan' => 'nullable|string',
            'lampiran' => (strtoupper($request->jenis_layanan) === 'RESTORE' ? 'required' : 'nullable') . '|array|max:5',
            'lampiran.*' => 'image|mimes:jpeg,png,jpg|max:5120'
        ], [
            'lampiran.required' => 'Untuk layanan Restore Data, Anda wajib mengunggah minimal satu lampiran gambar.',
        ]);

        $filePaths = [];
        if ($request->hasFile('lampiran')) {
            foreach ($request->file('lampiran') as $file) {
                if ($file->isValid()) {
                    $path = $file->store('aktivasi', 'public');
                    $filePaths[] = $path;
                }
            }
        }

        Aktivasi::create([
            'user_id' => Auth::id(),
            'nama_lengkap' => $request->nama_lengkap,
            'nik_aktivasi' => $request->nik_aktivasi,
            'jenis_layanan' => strtoupper(trim($request->jenis_layanan)),
            'alasan' => $request->alasan,
            'foto_ktp' => !empty($filePaths) ? json_encode($filePaths) : null,
            'status' => 'Pending'
        ]);

        $pesan = strtoupper($request->jenis_layanan) === 'RESTORE' ? 'Permintaan Restore Data' : 'Permintaan Aktivasi NIK';
        return back()->with('status', $pesan . ' berhasil dikirim!');
    }

    /**
     * 4. FITUR PROXY
     */
    public function storeProxy(Request $request)
    {
        $request->validate([
            'deskripsi' => 'nullable|string',
            'foto_proxy' => 'required|array|min:1|max:10',
            'foto_proxy.*' => 'image|mimes:jpeg,png,jpg|max:5120'
        ], [
            'foto_proxy.required' => 'Wajib mengunggah minimal satu foto.',
            'foto_proxy.max' => 'Maksimal lampiran adalah 10 file foto.',
            'foto_proxy.*.image' => 'File harus berupa gambar.',
            'foto_proxy.*.max' => 'Ukuran satu foto maksimal 5MB.'
        ]);

        $filePaths = [];
        if ($request->hasFile('foto_proxy')) {
            foreach ($request->file('foto_proxy') as $file) {
                if ($file->isValid()) {
                    $path = $file->store('proxy', 'public');
                    $filePaths[] = $path;
                }
            }
        }

        Proxy::create([
            'user_id' => Auth::id(),
            'nama_lengkap' => Auth::user()->name,
            'deskripsi' => $request->deskripsi,
            'foto_proxy' => json_encode($filePaths),
            'status' => 'Pending'
        ]);

        return back()->with('status', 'Laporan kendala Proxy/Jaringan berhasil dikirim!');
    }

    /**
     * 5. FITUR PEMBUBUHAN TTE
     */
    public function storePembubuhan(Request $request)
    {
        $request->validate([
            'nik' => 'required',
            'nik_pemohon' => 'required|numeric|digits:16',
            'jenis_dokumen' => 'required|string',
        ]);

        Pembubuhan::create([
            'user_id' => Auth::id(),
            'nama_lengkap' => Auth::user()->name,
            'nik' => $request->nik,
            'nik_pemohon' => $request->nik_pemohon,
            'jenis_dokumen' => strtoupper(trim($request->jenis_dokumen)),
            'status' => 'Pending'
        ]);

        return back()->with('status', 'Permohonan TTE berhasil dikirim!');
    }

    /**
     * 6. FITUR LUAR DAERAH
     */
    public function storeLuarDaerah(Request $request)
    {
        $request->validate([
            'nik' => 'required|string',
            'nik_luar_daerah' => 'required|numeric|digits:16',
            'jenis_dokumen_luar' => 'required|string',
        ]);

        LuarDaerah::create([
            'user_id' => Auth::id(),
            'nama_lengkap' => Auth::user()->name,
            'nik' => $request->nik,
            'nik_luar_daerah' => $request->nik_luar_daerah,
            'jenis_dokumen' => strtoupper(trim($request->jenis_dokumen_luar)),
            'status' => 'pending',
            'is_rejected' => false
        ]);

        return back()->with('status', 'Laporan Luar Daerah berhasil dikirim!');
    }

    /**
     * 7. FITUR UPDATE DATA
     */
    public function storeUpdateData(Request $request)
    {
        $request->validate([
            'nik_pemohon' => 'required|numeric|digits:16',
            'kategori_update' => 'required|string',
            'alasan_update' => 'required|string',
            'lampiran_update' => 'required|array|min:1|max:5',
            'lampiran_update.*' => 'image|mimes:jpeg,png,jpg|max:5120'
        ]);

        $filePaths = [];
        if ($request->hasFile('lampiran_update')) {
            foreach ($request->file('lampiran_update') as $file) {
                if ($file->isValid()) {
                    $path = $file->store('update_data', 'public');
                    $filePaths[] = $path;
                }
            }
        }

        UpdateData::create([
            'user_id' => Auth::id(),
            'nama_lengkap' => Auth::user()->name,
            'nik_pemohon' => $request->nik_pemohon,
            'jenis_layanan' => strtoupper(trim($request->kategori_update)),
            'deskripsi' => $request->alasan_update,
            'lampiran' => json_encode($filePaths),
            'status' => 'pending',
        ]);

        return back()->with('status', 'Permohonan update data kependudukan berhasil dikirim!');
    }

    /**
     * Fungsi Profile diarahkan kembali ke Dashboard jika file profile tidak ada.
     */
    public function profile()
    {
        return redirect()->route('user.dashboard');
    }

    /**
     * Fungsi Update Profil diarahkan kembali ke Dashboard.
     */
    public function updateProfil(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->name = $request->name;
        $user->location = $request->location;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        return redirect()->route('user.dashboard')->with('status', 'Profil dan lokasi kecamatan berhasil diperbarui!');
    }

    public function markAsRead($id)
    {
        return back()->with('status', 'Notifikasi ditandai sebagai dibaca.');
    }
}