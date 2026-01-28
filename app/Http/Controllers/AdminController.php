<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Trouble;
use App\Models\Aktivasi;
use App\Models\Pengajuan;
use App\Models\Proxy;
use App\Models\Pembubuhan;
use App\Models\Kecamatan;
use App\Models\Announcement;
use App\Models\LuarDaerah; // Model Luar Daerah
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class AdminController extends Controller
{
    /**
     * Menampilkan halaman Dashboard Admin Utama.
     * Menggabungkan statistik, grafik, laporan, data user, dan pengumuman.
     */
    public function index(Request $request)
    {
        // Set locale Carbon ke Indonesia agar "time ago" menjadi Bahasa Indonesia
        Carbon::setLocale('id');

        // Set Timezone default Asia/Jakarta
        $timezone = 'Asia/Jakarta';

        // 1. Data Wilayah (Kecamatan)
        $kecamatans = Kecamatan::orderBy('nama_kecamatan', 'asc')->get();

        // 2. Statistik Dasar & Statistik Kartu
        $totalPenduduk = User::where('role', 'user')->count();
        $totalUser = User::count();
        $countSiak = Pengajuan::count();
        $countAktivasi = Aktivasi::count();
        $countProxy = Proxy::count();
        $countPembubuhan = Pembubuhan::count();
        $countTrouble = Trouble::count();
        $countLuarDaerah = LuarDaerah::count();

        // --- Statistik Kategori TTE ---
        $countTte = $countPembubuhan;
        $tteCategories = Pembubuhan::select(
            DB::raw("CASE 
                WHEN jenis_dokumen IS NULL OR TRIM(jenis_dokumen) = '' THEN 'LAINNYA' 
                ELSE UPPER(TRIM(jenis_dokumen)) 
            END as label"),
            DB::raw('count(*) as total')
        )
            ->groupBy('label')
            ->pluck('total', 'label')
            ->toArray();

        // --- Statistik Kategori Luar Daerah ---
        $luarDaerahCategories = LuarDaerah::select(
            DB::raw("CASE 
                WHEN jenis_dokumen IS NULL OR TRIM(jenis_dokumen) = '' THEN 'UMUM' 
                ELSE UPPER(TRIM(jenis_dokumen)) 
            END as label"),
            DB::raw('count(*) as total')
        )
            ->groupBy('label')
            ->pluck('total', 'label')
            ->toArray();

        // --- Statistik Kategori SIAK ---
        $siakCategories = Pengajuan::select(
            DB::raw("CASE 
                WHEN kategori IS NULL OR TRIM(kategori) = '' THEN 'LAINNYA' 
                ELSE UPPER(TRIM(kategori)) 
            END as label"),
            DB::raw('count(*) as total')
        )
            ->groupBy('label')
            ->pluck('total', 'label')
            ->toArray();

        // --- Statistik Kategori Trouble ---
        $troubleCategories = Trouble::select(
            DB::raw("CASE 
                WHEN kategori IS NULL OR TRIM(kategori) = '' THEN 'GANGGUAN' 
                ELSE UPPER(TRIM(kategori)) 
            END as label"),
            DB::raw('count(*) as total')
        )
            ->groupBy('label')
            ->pluck('total', 'label')
            ->toArray();

        $pendudukTerbaru = User::where('role', 'user')->latest()->take(5)->get();

        // MODIFIKASI: Menggunakan paginate agar bisa berpindah halaman
        $allUsers = User::latest()->paginate(10);

        // 3. Load Data Laporan dengan Eager Loading (Optimasi Query)
        $troubles = Trouble::with('user')->get();
        $aktivasis = Aktivasi::with('user')->get();
        $pengajuans = Pengajuan::with('user')->get();
        $proxies = Proxy::with('user')->get();
        $pembubuhans = Pembubuhan::with('user')->get();
        $luarDaerahs = LuarDaerah::with('user')->get();

        // Load Data Pengumuman
        $announcements = Announcement::latest()->get();

        // 4. Logika Grafik Pendaftaran User (7 Hari Terakhir)
        $days = collect(range(6, 0))->map(function ($i) {
            return now()->subDays($i)->format('Y-m-d');
        });

        $counts = $days->map(function ($date) {
            return User::whereDate('created_at', $date)->count();
        });

        $chartLabels = $days->map(fn($date) => date('d M', strtotime($date)));
        $chartData = $counts;

        // 5. Penggabungan Semua Data Laporan (Combined Collection)
        $combinedLaporans = collect();

        // Mapping helper untuk mengurangi repetisi kode
        $this->mapLaporan($combinedLaporans, $troubles, 'trouble', 'PC - ', $timezone);
        $this->mapLaporan($combinedLaporans, $aktivasis, 'aktivasi', 'NIK - ', $timezone);
        $this->mapLaporan($combinedLaporans, $luarDaerahs, 'luardaerah', 'LUAR DAERAH - ', $timezone);
        $this->mapLaporan($combinedLaporans, $pengajuans, 'pengajuan', 'SIAK - ', $timezone);
        $this->mapLaporan($combinedLaporans, $proxies, 'proxy', 'PROXY - JARINGAN', $timezone);
        $this->mapLaporan($combinedLaporans, $pembubuhans, 'pembubuhan', 'TTE - ', $timezone);

        // Urutkan semua laporan berdasarkan tanggal terbaru
        $sortedLaporans = $combinedLaporans->sortByDesc('created_at')->values();

        // Tangkap tab saat ini untuk dikirim kembali ke View
        $currentTab = $request->query('tab', 'dashboard');

        // MODIFIKASI: Logika jika request datang dari AJAX (Klik Page)
        if ($request->ajax()) {
            return view('admin.partials.user_table', compact('allUsers'))->render();
        }

        return view('admin.dashboard', compact(
            'totalPenduduk',
            'totalUser',
            'pendudukTerbaru',
            'allUsers',
            'troubles',
            'sortedLaporans',
            'aktivasis',
            'pengajuans',
            'proxies',
            'pembubuhans',
            'luarDaerahs',
            'chartLabels',
            'chartData',
            'kecamatans',
            'countSiak',
            'countAktivasi',
            'countProxy',
            'countPembubuhan',
            'countTrouble',
            'countLuarDaerah',
            'countTte',
            'tteCategories',
            'luarDaerahCategories',
            'announcements',
            'siakCategories',
            'troubleCategories',
            'currentTab'
        ))->with('laporans', $sortedLaporans);
    }

    /**
     * Private helper to map different models to a unified structure
     */
    private function mapLaporan(&$collection, $data, $type, $prefix, $timezone)
    {
        foreach ($data as $item) {
            $kategori_label = $prefix;
            $kategori_asli = null;
            $jenis_layanan = null;
            $jenis_dokumen = null;
            $pesan = '';

            switch ($type) {
                case 'trouble':
                    $kategori_asli = strtoupper($item->kategori ?? 'TROUBLE');
                    $kategori_label .= $kategori_asli;
                    $pesan = $item->deskripsi;
                    break;
                case 'aktivasi':
                    $jenis_layanan = strtoupper($item->jenis_layanan ?? 'AKTIVASI');
                    $kategori_label .= $jenis_layanan;
                    $pesan = "NIK: " . $item->nik_aktivasi . " | Alasan: " . ($item->alasan ?? '-');
                    break;
                case 'luardaerah':
                    $jenis_dokumen = strtoupper($item->jenis_dokumen ?? 'UMUM');
                    $kategori_label .= $jenis_dokumen;
                    $pesan = "NIK Target: " . ($item->nik_luar_daerah ?? '-') . " | Dokumen: " . ($item->jenis_dokumen ?? '-');
                    break;
                case 'pengajuan':
                    $kategori_asli = strtoupper($item->kategori ?? $item->jenis_registrasi ?? 'REGISTRASI');
                    $kategori_label .= $kategori_asli;
                    $pesan = $item->deskripsi;
                    break;
                case 'proxy':
                    $kategori_asli = 'JARINGAN';
                    $pesan = "Deskripsi: " . ($item->deskripsi ?? $item->ip_detail);
                    break;
                case 'pembubuhan':
                    $jenis_dokumen = strtoupper($item->jenis_dokumen ?? 'PEMBUBUHAN');
                    $kategori_label .= $jenis_dokumen;
                    $pesan = "Permohonan TTE Dokumen: " . ($item->jenis_dokumen ?? '-');
                    break;
            }

            $collection->push((object) [
                'id' => $item->id,
                'user_name' => $item->user->name ?? 'User Tak Dikenal',
                'wilayah' => $item->user->location ?? 'LUAR WILAYAH',
                'nama_kecamatan' => $item->user->location ?? 'LUAR WILAYAH',
                'kategori' => $kategori_label,
                'kategori_asli' => $kategori_asli,
                'jenis_layanan' => $jenis_layanan,
                'jenis_dokumen' => $jenis_dokumen,
                'pesan' => $pesan ?? 'Tidak ada detail',
                'created_at' => Carbon::parse($item->created_at)->timezone($timezone),
                'updated_at' => Carbon::parse($item->updated_at)->timezone($timezone),
                'tanggapan_admin' => $item->tanggapan_admin,
                'processed_by' => $item->processed_by ?? 'ADMIN',
                'is_rejected' => (bool) ($item->is_rejected ?? false),
                'status' => $item->status ?? ($item->is_rejected ? 'ditolak' : (!empty($item->tanggapan_admin) ? 'selesai' : 'pending')),
                'type' => $type
            ]);
        }
    }

    private function getModelByType($type, $id)
    {
        return match ($type) {
            'aktivasi' => Aktivasi::findOrFail($id),
            'luardaerah' => LuarDaerah::findOrFail($id),
            'pengajuan' => Pengajuan::findOrFail($id),
            'proxy' => Proxy::findOrFail($id),
            'trouble' => Trouble::findOrFail($id),
            'pembubuhan' => Pembubuhan::findOrFail($id),
            default => throw new \Exception("Tipe laporan tidak valid."),
        };
    }

    public function kirimRespon(Request $request)
    {
        $request->validate([
            'laporan_id' => 'required',
            'admin_note' => 'required|string',
            'type' => 'required'
        ]);

        try {
            DB::beginTransaction();
            $model = $this->getModelByType($request->type, $request->laporan_id);
            $tableName = $model->getTable();

            $model->tanggapan_admin = $request->admin_note;

            // Set Admin yang memproses
            if (Schema::hasColumn($tableName, 'processed_by')) {
                $model->processed_by = Auth::user()->name;
            }

            // Jika admin merespon, maka otomatis dianggap tidak ditolak
            if (Schema::hasColumn($tableName, 'is_rejected')) {
                $model->is_rejected = false;
            }

            // Ubah status menjadi selesai
            if (Schema::hasColumn($tableName, 'status')) {
                $model->status = 'selesai';
            }

            $model->save();

            DB::commit();
            return redirect()->route('admin.index', ['tab' => 'laporan_sistem'])->with('success', 'Respon ' . ucfirst($request->type) . ' berhasil dikirim!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.index', ['tab' => 'laporan_sistem'])->withErrors(['error' => 'Gagal mengirim tanggapan: ' . $e->getMessage()]);
        }
    }

    public function tolakLaporan(Request $request, $id)
    {
        $request->validate([
            'type' => 'required',
            'admin_note' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();
            $model = $this->getModelByType($request->type, $id);
            $tableName = $model->getTable();

            // Set Pesan Penolakan
            $model->tanggapan_admin = $request->admin_note ?? 'Laporan ditolak karena data tidak sesuai kriteria.';

            // Set Admin yang memproses
            if (Schema::hasColumn($tableName, 'processed_by')) {
                $model->processed_by = Auth::user()->name;
            }

            // Set Flag is_rejected menjadi true
            if (Schema::hasColumn($tableName, 'is_rejected')) {
                $model->is_rejected = true;
            }

            // Set status menjadi ditolak
            if (Schema::hasColumn($tableName, 'status')) {
                $model->status = 'ditolak';
            }

            $model->save();
            DB::commit();

            return redirect()->route('admin.index', ['tab' => 'laporan_sistem'])->with('success', 'Laporan ' . ucfirst($request->type) . ' telah ditolak.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.index', ['tab' => 'laporan_sistem'])->withErrors(['error' => 'Gagal menolak laporan: ' . $e->getMessage()]);
        }
    }

    public function hapusLaporan(Request $request, $id)
    {
        $request->validate(['type' => 'required']);

        try {
            $model = $this->getModelByType($request->type, $id);
            $imageFields = ['foto_trouble', 'foto_ktp', 'foto_dokumen', 'foto_proxy', 'foto_bukti', 'foto_pembubuhan', 'image'];

            foreach ($imageFields as $field) {
                if (isset($model->$field) && !empty($model->$field)) {
                    $data = $model->$field;
                    $decoded = json_decode($data, true);

                    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                        foreach ($decoded as $path) {
                            if (Storage::disk('public')->exists($path)) {
                                Storage::disk('public')->delete($path);
                            }
                        }
                    } else {
                        if (Storage::disk('public')->exists($data)) {
                            Storage::disk('public')->delete($data);
                        }
                    }
                }
            }

            $model->delete();
            return redirect()->route('admin.index', ['tab' => 'laporan_sistem'])->with('success', 'Laporan berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('admin.index', ['tab' => 'laporan_sistem'])->withErrors(['error' => 'Gagal menghapus laporan: ' . $e->getMessage()]);
        }
    }

    /**
     * Fitur Registrasi / Tambah User Baru oleh Admin
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nik' => 'required|string|size:16|unique:users,nik',
            'pin' => 'required|numeric|digits:6',
            'location' => 'required|string|max:255',
            'role' => 'required|in:admin,user', // Validasi Role
            'email' => 'nullable|email|unique:users,email',
        ]);

        try {
            User::create([
                'name' => $validated['name'],
                'nik' => $validated['nik'],
                'pin' => $validated['pin'],
                'email' => $request->email ?? $validated['nik'] . '@sistem.com',
                'location' => $validated['location'],
                'role' => $validated['role'], // Menyimpan Role Pilihan
                'password' => Hash::make($validated['nik']),
                'is_active' => true,
            ]);

            return redirect()->route('admin.index', ['tab' => 'data_user'])->with('success', 'Akun berhasil disimpan!');
        } catch (\Exception $e) {
            return redirect()->route('admin.index', ['tab' => 'data_user'])->withErrors(['error' => 'Gagal: ' . $e->getMessage()]);
        }
    }

    /**
     * Fitur Update / Edit User (Termasuk Ganti Role)
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'pin' => 'required|string|max:10',
            'location' => 'required|string|max:255',
            'role' => 'required|in:admin,user', // Tambahan Validasi Role saat Update
            'email' => 'nullable|email|unique:users,email,' . $id,
        ]);

        try {
            // Cek jika admin mencoba mengubah role-nya sendiri menjadi user (keamanan)
            if ($user->id === auth()->id() && $validated['role'] === 'user') {
                return redirect()->route('admin.index', ['tab' => 'data_user'])->withErrors(['error' => 'Anda tidak bisa menurunkan role Anda sendiri menjadi User!']);
            }

            $user->update([
                'name' => $validated['name'],
                'pin' => $validated['pin'],
                'location' => $validated['location'],
                'role' => $validated['role'], // Memperbarui Role
                'email' => $validated['email'] ?? $user->email,
            ]);

            return redirect()->route('admin.index', ['tab' => 'data_user'])->with('success', 'Data user berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->route('admin.index', ['tab' => 'data_user'])->withErrors(['error' => 'Gagal memperbarui data user.']);
        }
    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            if ($user->id === auth()->id()) {
                return redirect()->route('admin.index', ['tab' => 'data_user'])->withErrors(['error' => 'Tidak bisa menghapus akun sendiri!']);
            }
            $user->delete();
            return redirect()->route('admin.index', ['tab' => 'data_user'])->with('success', 'Akun berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('admin.index', ['tab' => 'data_user'])->withErrors(['error' => 'Gagal menghapus akun.']);
        }
    }

    public function toggleStatus($id)
    {
        try {
            $user = User::findOrFail($id);
            if ($user->id === auth()->id()) {
                return redirect()->route('admin.index', ['tab' => 'data_user'])->withErrors(['error' => 'Tidak bisa menonaktifkan akun sendiri!']);
            }
            $user->is_active = !$user->is_active;
            $user->save();
            return redirect()->route('admin.index', ['tab' => 'data_user'])->with('success', 'Status akun diperbarui.');
        } catch (\Exception $e) {
            return redirect()->route('admin.index', ['tab' => 'data_user'])->withErrors(['error' => 'Gagal merubah status.']);
        }
    }

    /**
     * PRIVATE HELPER UNTUK EKSPORT
     * Menyeragamkan format baris data agar filter berfungsi di tingkat collection.
     */
    private function formatExportRow($item, $defaultType, $timezone)
    {
        $status = 'PENDING';
        if (isset($item->is_rejected) && $item->is_rejected) {
            $status = 'DITOLAK';
        } elseif (!empty($item->tanggapan_admin)) {
            $status = 'SELESAI';
        }

        return [
            'tipe' => strtoupper($item->jenis_layanan ?? $defaultType),
            'pelapor' => $item->user->name ?? 'N/A',
            'wilayah' => strtoupper($item->user->location ?? 'LUAR WILAYAH'),
            'nik_target' => $item->nik_aktivasi ?? ($item->nik_luar_daerah ?? '-'),
            'alasan' => $item->alasan ?? ($item->jenis_dokumen ?? ($item->deskripsi ?? ($item->kategori ?? '-'))),
            'status' => $status,
            'tanggapan' => $item->tanggapan_admin ?? '-',
            'admin' => $item->processed_by ?? '-',
            'tanggal' => Carbon::parse($item->created_at)->timezone($timezone)
        ];
    }

    /**
     * EKSPOR KHUSUS AKTIVASI NIK
     * Mencakup data Aktivasi dan Luar Daerah.
     */
    public function exportAktivasi(Request $request)
    {
        $timezone = 'Asia/Jakarta';
        $nowJakarta = Carbon::now($timezone);

        $monthFilter = $request->query('month');
        $statusFilter = $request->query('status');
        $kecamatanFilter = $request->query('kecamatan');
        $kategoriFilter = $request->query('kategori');

        $combined = collect();

        // Ambil dan format data Aktivasi
        foreach (Aktivasi::with('user')->get() as $item) {
            $combined->push($this->formatExportRow($item, 'AKTIVASI', $timezone));
        }

        // Ambil dan format data Luar Daerah
        foreach (LuarDaerah::with('user')->get() as $item) {
            $combined->push($this->formatExportRow($item, 'LUAR DAERAH', $timezone));
        }

        // --- FILTERING ---
        if ($monthFilter) {
            $combined = $combined->filter(fn($i) => $i['tanggal']->format('m') == $monthFilter);
        }
        if ($statusFilter) {
            $combined = $combined->filter(fn($i) => strtolower($i['status']) == strtolower($statusFilter));
        }
        if ($kecamatanFilter) {
            $combined = $combined->filter(fn($i) => $i['wilayah'] === strtoupper($kecamatanFilter));
        }
        if ($kategoriFilter) {
            $combined = $combined->filter(fn($i) => $i['tipe'] === strtoupper($kategoriFilter));
        }

        $sorted = $combined->sortBy('tanggal')->values();
        $filename = "rekap_aktivasi_lengkap_" . $nowJakarta->format('Ymd_His') . ".csv";
        $header = ['NO', 'JENIS LAYANAN', 'PELAPOR', 'WILAYAH', 'NIK TARGET', 'ALASAN/DOKUMEN', 'STATUS', 'TANGGAPAN', 'ADMIN', 'TANGGAL'];

        return response()->stream(function () use ($sorted, $header) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($file, $header);

            foreach ($sorted as $index => $row) {
                fputcsv($file, [
                    $index + 1,
                    $row['tipe'],
                    $row['pelapor'],
                    $row['wilayah'],
                    $row['nik_target'],
                    $row['alasan'],
                    $row['status'],
                    $row['tanggapan'],
                    $row['admin'],
                    $row['tanggal']->format('d/m/Y H:i')
                ]);
            }
            fclose($file);
        }, 200, [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Expires" => "0",
        ]);
    }

    /**
     * EKSPOR UMUM / EXCEL CSV
     * Digunakan untuk kategori-kategori laporan sistem.
     */
    public function exportExcel(Request $request)
    {
        $timezone = 'Asia/Jakarta';
        $nowJakarta = Carbon::now($timezone);

        $mode = $request->query('category', 'sistem');
        $monthFilter = $request->query('month');
        $statusFilter = $request->query('status');
        $kecamatanFilter = $request->query('kecamatan_id');

        // PERBAIKAN: Hapus 'LUAR DAERAH' dari array default agar tidak campur dengan laporan sistem
        $queries = match ($mode) {
            'aktivasi' => ['AKTIVASI' => Aktivasi::with('user'), 'LUAR DAERAH' => LuarDaerah::with('user')],
            'luardaerah' => ['LUAR DAERAH' => LuarDaerah::with('user')],
            default => [
                'TROUBLE' => Trouble::with('user'),
                'SIAK' => Pengajuan::with('user'),
                'PROXY' => Proxy::with('user'),
                'TTE' => Pembubuhan::with('user'),
                // 'LUAR DAERAH' => LuarDaerah::with('user'), // BARIS INI DIHAPUS AGAR TIDAK DOUBLE
            ],
        };

        $allLaporans = collect();
        foreach ($queries as $label => $query) {
            foreach ($query->get() as $item) {
                $allLaporans->push($this->formatExportRow($item, $label, $timezone));
            }
        }

        // --- FILTERING ---
        if ($monthFilter) {
            $allLaporans = $allLaporans->filter(fn($i) => $i['tanggal']->format('m') == $monthFilter);
        }
        if ($statusFilter) {
            $allLaporans = $allLaporans->filter(fn($i) => strtolower($i['status']) == strtolower($statusFilter));
        }
        if ($kecamatanFilter) {
            $allLaporans = $allLaporans->filter(fn($i) => $i['wilayah'] === strtoupper($kecamatanFilter));
        }

        $sortedExport = $allLaporans->sortBy('tanggal')->values();
        $filename = "rekap_" . $mode . "_" . $nowJakarta->format('Ymd_His') . ".csv";
        
        // Tentukan Header berdasarkan Mode
        $header = ['NO', 'TIPE', 'PELAPOR', 'WILAYAH', 'DETAIL/NIK', 'STATUS', 'TANGGAPAN', 'ADMIN', 'TANGGAL'];
        if ($mode === 'luardaerah') {
            $header = ['NO', 'TIPE', 'PELAPOR', 'WILAYAH', 'NIK TARGET', 'DOKUMEN', 'STATUS', 'TANGGAPAN', 'ADMIN', 'TANGGAL'];
        }

        return response()->stream(function () use ($sortedExport, $header, $mode) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($file, $header);

            foreach ($sortedExport as $index => $row) {
                if ($mode === 'luardaerah') {
                    $line = [$index + 1, $row['tipe'], $row['pelapor'], $row['wilayah'], $row['nik_target'], $row['alasan'], $row['status'], $row['tanggapan'], $row['admin'], $row['tanggal']->format('d/m/Y H:i')];
                } else {
                    $detail = ($row['nik_target'] !== '-') ? $row['nik_target'] : $row['alasan'];
                    $line = [$index + 1, $row['tipe'], $row['pelapor'], $row['wilayah'], $detail, $row['status'], $row['tanggapan'], $row['admin'], $row['tanggal']->format('d/m/Y H:i')];
                }
                fputcsv($file, $line);
            }
            fclose($file);
        }, 200, [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Expires" => "0",
        ]);
    }
}