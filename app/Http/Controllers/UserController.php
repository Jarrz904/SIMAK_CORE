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
use App\Models\UpdateData; // Tambahkan Model Baru
use App\Models\Kecamatan;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
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
        $kecamatans = Kecamatan::all();

        // Ambil data spesifik user
        $troubles = Trouble::where('user_id', $userId)->latest()->get();
        $aktivasis = Aktivasi::where('user_id', $userId)->latest()->get();
        $pengajuans = Pengajuan::where('user_id', $userId)->latest()->get();
        $proxies = Proxy::where('user_id', $userId)->latest()->get();
        $pembubuhans = Pembubuhan::where('user_id', $userId)->latest()->get();
        $luardaerahs = LuarDaerah::where('user_id', $userId)->latest()->get(); 
        $updatedatas = UpdateData::where('user_id', $userId)->latest()->get(); // Ambil data Update Data

        $counts = [
            'trouble' => $troubles->count(),
            'aktivasi' => $aktivasis->count(),
            'pengajuan' => $pengajuans->count(),
            'proxy' => $proxies->count(),
            'pembubuhan' => $pembubuhans->count(),
            'luardaerah' => $luardaerahs->count(),
            'updatedata' => $updatedatas->count(), // Tambahkan hitungan
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
                'is_rejected' => (bool)($t->is_rejected ?? false),
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
                'is_rejected' => (bool)($a->is_rejected ?? false),
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
                'is_rejected' => (bool)($p->is_rejected ?? false),
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
                'is_rejected' => (bool)($pr->is_rejected ?? false),
                'created_at' => $pr->created_at,
                'updated_at' => $pr->updated_at,
                'type' => 'proxy',
                'status' => $pr->status
            ]);
        }

        foreach ($pembubuhans as $pb) {
            $allNotifications->push((object) [
                'id' => $pb->id,
                'group_key' => 'TTE_CARD',
                'kategori' => 'PEMBUBUHAN TTE',
                'kategori_asli' => 'TTE',
                'jenis_layanan' => 'TTE', 
                'jenis_dokumen' => strtoupper($pb->jenis_dokumen ?? 'PENGAJUAN TTE'),
                'pesan' => "TTE: No. Dokumen: " . ($pb->no_akte ?? '-') . " | Jenis: " . strtoupper($pb->jenis_dokumen), 
                'tanggapan_admin' => $pb->tanggapan_admin,
                'is_rejected' => (bool)($pb->is_rejected ?? false),
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
                'kategori_asli' => 'LUAR DAERAH',
                'jenis_layanan' => 'LUAR DAERAH', 
                'jenis_dokumen' => strtoupper($ld->jenis_dokumen),
                'pesan' => "LUAR DAERAH: NIK Target: " . $ld->nik_luar_daerah . " | Layanan: " . strtoupper($ld->jenis_dokumen),
                'tanggapan_admin' => $ld->tanggapan_admin,
                'is_rejected' => (bool)($ld->is_rejected ?? false),
                'created_at' => $ld->created_at,
                'updated_at' => $ld->updated_at,
                'type' => 'luardaerah',
                'status' => $ld->status ?? 'pending'
            ]);
        }

        // TAMBAHAN: Loop Notifikasi untuk Update Data
        foreach ($updatedatas as $ud) {
            $allNotifications->push((object) [
                'id' => $ud->id,
                'group_key' => 'UPDATE_CARD', // Key untuk membedakan kartu di Blade
                'kategori' => 'UPDATE - ' . strtoupper($ud->jenis_layanan),
                'kategori_asli' => strtoupper($ud->jenis_layanan),
                'jenis_layanan' => 'UPDATE DATA', 
                'jenis_dokumen' => null,
                'pesan' => $ud->deskripsi,
                'tanggapan_admin' => $ud->tanggapan_admin,
                'is_rejected' => (bool)($ud->status === 'rejected'),
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
            'updatedatas' => $updatedatas // Tambahkan variabel ini
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
            'foto_trouble' => 'required|array|min:1',
            'foto_trouble.*' => 'image|mimes:jpeg,png,jpg|max:5120',
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
            'foto_dokumen_siak' => 'required|array|min:1',
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
            'jenis_registrasi' => 'SIAK', 
            'kategori' => strtoupper(trim($request->kategori_siak)), 
            'deskripsi' => $request->deskripsi_siak,
            'foto_dokumen' => json_encode($filePaths),
            'status' => 'Pending'
        ]);

        return back()->with('status', 'Laporan kendala SIAK berhasil dikirim!');
    }

    /**
     * 3. FITUR AKTIVASI NIK (DIPERBARUI UNTUK MULTIPLE FOTO)
     */
    public function storeAktivasi(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nik_aktivasi' => 'required|numeric|digits:16',
            'jenis_layanan' => 'required|in:restore,aktivasi', 
            'alasan'       => 'nullable|string', 
            // Validasi: Jika restore wajib ada lampiran array min 1, jika aktivasi boleh kosong
            'lampiran'     => ($request->jenis_layanan === 'restore' ? 'required' : 'nullable') . '|array',
            'lampiran.*'   => 'image|mimes:jpeg,png,jpg|max:5120' 
        ], [
            'lampiran.required' => 'Untuk layanan Restore Data, Anda wajib mengunggah minimal satu lampiran gambar.',
            'lampiran.array'    => 'Format lampiran tidak valid.',
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
            'user_id'      => Auth::id(),
            'nama_lengkap' => $request->nama_lengkap, 
            'nik_aktivasi' => $request->nik_aktivasi,
            'jenis_layanan'=> strtolower(trim($request->jenis_layanan)),
            'alasan'       => $request->alasan,
            // Simpan sebagai JSON agar bisa menampung banyak foto seperti fitur Trouble
            'foto_ktp'     => !empty($filePaths) ? json_encode($filePaths) : null, 
            'status'       => 'Pending'
        ]);

        $pesan = $request->jenis_layanan === 'restore' ? 'Permintaan Restore Data' : 'Permintaan Aktivasi NIK';
        return back()->with('status', $pesan . ' berhasil dikirim!');
    }

    /**
     * 4. FITUR PROXY
     */
    public function storeProxy(Request $request)
    {
        $request->validate([
            'deskripsi' => 'nullable|string', 
            'foto_proxy' => 'required|image|max:2048'
        ]);

        $path = null;
        if ($request->hasFile('foto_proxy')) {
            $path = $request->file('foto_proxy')->store('proxy', 'public');
        }

        Proxy::create([
            'user_id' => Auth::id(),
            'deskripsi' => $request->deskripsi,
            'foto_proxy' => $path,
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
            'nik' => 'required|string',
            'no_akte' => 'nullable|string',
            'jenis_dokumen' => 'required|string',
        ]);

        Pembubuhan::create([
            'user_id' => Auth::id(),
            'nik' => $request->nik, 
            'no_akte' => $request->no_akte ?? '-',
            'jenis_dokumen' => strtoupper(trim($request->jenis_dokumen)),
            'status' => 'Pending',
            'is_rejected' => false
        ]);

        return back()->with('status', 'Permohonan pembubuhan TTE berhasil dikirim!');
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
     * Menangani permohonan perubahan elemen data kependudukan.
     */
    public function storeUpdateData(Request $request)
    {
        // Gunakan nama input sesuai yang dikirim dari FORM (kategori_update, alasan_update, lampiran_update)
        $request->validate([
            'nik_pemohon'     => 'required|numeric|digits:16',
            'kategori_update' => 'required|string',
            'alasan_update'   => 'required|string',
            'lampiran_update' => 'required|array|min:1',
            'lampiran_update.*' => 'image|mimes:jpeg,png,jpg|max:5120'
        ], [
            // Pesan error kustom untuk memastikan kita tahu field mana yang bermasalah
            'kategori_update.required' => 'Jenis layanan update data harus diisi.',
            'alasan_update.required'   => 'Deskripsi/Alasan harus diisi.',
            'lampiran_update.required' => 'Lampiran dokumen harus diunggah.'
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
            'user_id'       => Auth::id(),
            'nik_pemohon'   => $request->nik_pemohon,
            'jenis_layanan' => $request->kategori_update, 
            'deskripsi'     => $request->alasan_update,   
            'lampiran'      => $filePaths, 
            'status'        => 'pending',
        ]);

        return back()->with('status', 'Permohonan update data kependudukan berhasil dikirim!');
    }

    /**
     * Profil & Keamanan
     */
    public function profile()
    {
        $data = Auth::user();
        $kecamatans = Kecamatan::all();
        return view('user.profile', compact('data', 'kecamatans'));
    }

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

        return redirect()->route('user.profile')->with('status', 'Profil dan keamanan berhasil diperbarui!');
    }

    public function markAsRead($id)
    {
        return back()->with('status', 'Notifikasi ditandai sebagai dibaca.');
    }
}