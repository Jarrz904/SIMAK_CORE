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
use App\Models\LuarDaerah;
use App\Models\UpdateData; // Import Model UpdateData
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
     */
    public function index(Request $request)
    {
        // Set locale Carbon ke Indonesia agar "time ago" menjadi Bahasa Indonesia
        Carbon::setLocale('id');

        // Set Timezone default Asia/Jakarta
        $timezone = 'Asia/Jakarta';

        // 1. Data Wilayah (Kecamatan)
        $kecamatans = Kecamatan::orderBy('nama_kecamatan', 'asc')->get();

        // 2. Statistik Dasar
        $totalPenduduk = User::where('role', 'user')->count();
        $totalUser = User::count();
        
        // 3. Hitung Count secara real-time langsung dari Query (Lebih Cepat)
        $countSiak = Pengajuan::count();
        $countAktivasi = Aktivasi::count();
        $countProxy = Proxy::count();
        $countPembubuhan = Pembubuhan::count();
        $countTrouble = Trouble::count();
        $countLuarDaerah = LuarDaerah::count();
        $countUpdateData = UpdateData::count(); 

        // Load Data untuk Collection Mapping (Eager Loading User untuk performa)
        $troubles = Trouble::with('user')->get();
        $aktivasis = Aktivasi::with('user')->get();
        $pengajuans = Pengajuan::with('user')->get();
        $proxies = Proxy::with('user')->get();
        $pembubuhans = Pembubuhan::with('user')->get();
        $luarDaerahs = LuarDaerah::with('user')->get();
        $updateDatas = UpdateData::with('user')->get(); 

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

        // Pagination User
        $allUsers = User::latest()->paginate(10);

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

        // Mapping helper (Sinkronisasi dengan database masing-masing)
        $this->mapLaporan($combinedLaporans, $troubles, 'trouble', 'PC - ', $timezone);
        $this->mapLaporan($combinedLaporans, $aktivasis, 'aktivasi', 'NIK - ', $timezone);
        $this->mapLaporan($combinedLaporans, $luarDaerahs, 'luardaerah', 'LUAR DAERAH - ', $timezone);
        $this->mapLaporan($combinedLaporans, $updateDatas, 'updatedata', 'UPDATE - ', $timezone); 
        $this->mapLaporan($combinedLaporans, $pengajuans, 'pengajuan', 'SIAK - ', $timezone);
        $this->mapLaporan($combinedLaporans, $proxies, 'proxy', 'PROXY - JARINGAN', $timezone);
        $this->mapLaporan($combinedLaporans, $pembubuhans, 'pembubuhan', 'TTE - ', $timezone);

        /**
         * LOGIC SORTING PERBAIKAN:
         * 1. Status 'pending' diprioritaskan di atas.
         * 2. Untuk status yang sama, urutkan berdasarkan created_at ASC (Terlama di atas).
         * 3. Status 'selesai' dan 'ditolak' akan berada di bawah.
         */
        $sortedLaporans = $combinedLaporans->sort(function ($a, $b) {
            // Prioritas Status: pending = 0, lainnya = 1
            $priorityA = ($a->status === 'pending') ? 0 : 1;
            $priorityB = ($b->status === 'pending') ? 0 : 1;

            if ($priorityA === $priorityB) {
                // Jika status sama-sama pending atau sama-sama selesai, 
                // urutkan berdasarkan waktu created_at secara ASC (Oldest First)
                return $a->created_at->timestamp <=> $b->created_at->timestamp;
            }

            return $priorityA <=> $priorityB;
        })->values();

        // --- DATA UNTUK ALPINE.JS DASHBOARD TABLE (Khusus Update Data) ---
        $jsUpdateData = $updateDatas->map(function($item) {
            $status = $item->status ?? ($item->is_rejected ? 'ditolak' : (!empty($item->tanggapan_admin) ? 'selesai' : 'pending'));
            return [
                'id'           => $item->id,
                'name'         => $item->user->name ?? 'User Tak Dikenal',
                'location'     => $item->user->location ?? 'LUAR WILAYAH',
                'month'        => Carbon::parse($item->created_at)->format('m'),
                'status'       => $status,
                'kat_row'      => 'updatedata', // Disamakan dengan key di getModelByType
                'display_kat'  => strtoupper($item->jenis_layanan ?? 'UPDATE DATA'),
                'foto_ktp'     => $item->foto_ktp ? asset('storage/' . $item->foto_ktp) : null,
                'alasan'       => "NIK: ".($item->nik_pemohon ?? '-')." | ".($item->deskripsi ?? $item->alasan ?? '-'),
                'tanggapan'    => $item->tanggapan_admin,
                'date_human'   => Carbon::parse($item->created_at)->diffForHumans(),
                'time'         => Carbon::parse($item->created_at)->format('H:i'),
                'csrf'         => csrf_token(),
                'url_respon'   => route('admin.laporan.respon'),
                'url_tolak'    => route('admin.laporan.tolak', $item->id),
                'url_hapus'    => route('admin.laporan.hapus', $item->id),
            ];
        });

        $currentTab = $request->query('tab', 'dashboard');

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
            'updateDatas', 
            'jsUpdateData', 
            'chartLabels',
            'chartData',
            'kecamatans',
            'countSiak',
            'countAktivasi',
            'countProxy',
            'countPembubuhan',
            'countTrouble',
            'countLuarDaerah',
            'countUpdateData', 
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
                case 'updatedata': 
                    $kategori_asli = 'UPDATE DATA';
                    $jenis_layanan = strtoupper($item->jenis_layanan ?? 'BIODATA');
                    $kategori_label .= $jenis_layanan;
                    $pesan = "NIK: " . ($item->nik_pemohon ?? '-') . " | Detail: " . ($item->deskripsi ?? $item->alasan);
                    break;
                case 'pengajuan':
                    $kategori_asli = strtoupper($item->kategori ?? $item->jenis_registrasi ?? 'REGISTRASI');
                    $kategori_label .= $kategori_asli;
                    $pesan = $item->deskripsi;
                    break;
                case 'proxy':
                    $kategori_asli = 'JARINGAN';
                    $pesan = "Deskripsi: " . ($item->deskripsi ?? $item->ip_detail ?? 'Akses Jaringan');
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
                'pesan' => $pesan ?: 'Tidak ada detail',
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
        // Normalisasi input type agar tidak sensitif huruf besar/kecil dan spasi
        $type = strtolower(trim($type));
        
        return match ($type) {
            'aktivasi' => Aktivasi::findOrFail($id),
            'luardaerah', 'luar daerah' => LuarDaerah::findOrFail($id),
            'updatedata', 'update data', 'update' => UpdateData::findOrFail($id), // Menambahkan 'update' sebagai alias
            'pengajuan', 'siak' => Pengajuan::findOrFail($id),
            'proxy' => Proxy::findOrFail($id),
            'trouble' => Trouble::findOrFail($id),
            'pembubuhan', 'tte' => Pembubuhan::findOrFail($id),
            default => throw new \Exception("Tipe laporan '$type' tidak dikenal oleh sistem."),
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

            if (Schema::hasColumn($tableName, 'processed_by')) {
                $model->processed_by = Auth::user()->name;
            }

            if (Schema::hasColumn($tableName, 'is_rejected')) {
                $model->is_rejected = false;
            }

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

            $model->tanggapan_admin = $request->admin_note ?? 'Laporan ditolak karena data tidak sesuai kriteria.';

            if (Schema::hasColumn($tableName, 'processed_by')) {
                $model->processed_by = Auth::user()->name;
            }

            if (Schema::hasColumn($tableName, 'is_rejected')) {
                $model->is_rejected = true;
            }

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
            $imageFields = ['foto_trouble', 'foto_ktp', 'foto_dokumen', 'foto_proxy', 'foto_bukti', 'foto_pembubuhan', 'image', 'lampiran'];

            foreach ($imageFields as $field) {
                if (isset($model->$field) && !empty($model->$field)) {
                    $data = $model->$field;
                    
                    // PERBAIKAN: Cek apakah data berupa array (sudah otomatis didecode oleh model cast)
                    // atau string yang perlu didecode manual.
                    $decoded = null;
                    if (is_array($data)) {
                        $decoded = $data;
                    } elseif (is_string($data)) {
                        $attempt = json_decode($data, true);
                        if (json_last_error() === JSON_ERROR_NONE && is_array($attempt)) {
                            $decoded = $attempt;
                        }
                    }

                    if (is_array($decoded)) {
                        // Jika data adalah multiple images (Array)
                        foreach ($decoded as $path) {
                            if (is_string($path) && Storage::disk('public')->exists($path)) {
                                Storage::disk('public')->delete($path);
                            }
                        }
                    } else {
                        // Jika data adalah string path tunggal
                        if (is_string($data) && Storage::disk('public')->exists($data)) {
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

    public function store(Request $request)
    {
        // 1. Validasi Input (NIK DIHAPUS)
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'pin' => 'required|string|max:10', 
            'location' => 'required|string|max:255',
            'role' => 'required|in:admin,user',
            'password' => 'required|min:6', // Diwajibkan sesuai permintaan
        ]);

        try {
            User::create([
                'name' => $validated['name'],
                'pin' => $validated['pin'],
                'location' => $validated['location'],
                'role' => $validated['role'],
                'email' => $request->email ?? ($validated['name'] . rand(10,99) . '@sistem.com'), // Email fallback unik
                'password' => Hash::make($validated['password']), 
                'is_active' => true,
            ]);

            return redirect()->route('admin.index', ['tab' => 'data_user'])->with('success', 'Akun ' . $validated['name'] . ' berhasil disimpan!');
        } catch (\Exception $e) {
            return redirect()->route('admin.index', ['tab' => 'data_user'])->withErrors(['error' => 'Gagal menyimpan user: ' . $e->getMessage()]);
        }
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Validasi Update (NIK DIHAPUS)
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'pin' => 'required|string|max:10',
            'location' => 'required|string|max:255',
            'role' => 'required|in:admin,user',
            'email' => 'nullable|email|unique:users,email,' . $id,
            'password' => 'required|min:6', // Diwajibkan sesuai permintaan
        ]);

        try {
            if ($user->id === auth()->id() && $validated['role'] === 'user') {
                return redirect()->route('admin.index', ['tab' => 'data_user'])->withErrors(['error' => 'Anda tidak bisa menurunkan role Anda sendiri menjadi User!']);
            }

            $user->update([
                'name' => $validated['name'],
                'pin' => $validated['pin'],
                'location' => $validated['location'],
                'role' => $validated['role'],
                'email' => $validated['email'] ?? $user->email,
                'password' => Hash::make($validated['password']),
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

    private function formatExportRow($item, $defaultType, $timezone)
    {
        $status = 'PENDING';
        if (isset($item->is_rejected) && $item->is_rejected) {
            $status = 'DITOLAK';
        } elseif (!empty($item->tanggapan_admin)) {
            $status = 'SELESAI';
        }

        $tipeExport = strtoupper($item->jenis_layanan ?? $defaultType);

        return [
            'tipe' => $tipeExport,
            'pelapor' => $item->user->name ?? 'N/A',
            'wilayah' => strtoupper($item->user->location ?? 'LUAR WILAYAH'),
            'nik_target' => $item->nik_aktivasi ?? ($item->nik_luar_daerah ?? ($item->nik_pemohon ?? '-')),
            'alasan' => $item->alasan ?? ($item->jenis_dokumen ?? ($item->deskripsi ?? ($item->kategori ?? '-'))),
            'status' => $status,
            'tanggapan' => $item->tanggapan_admin ?? '-',
            'admin' => $item->processed_by ?? '-',
            'tanggal' => Carbon::parse($item->created_at)->timezone($timezone)
        ];
    }

    public function exportAktivasi(Request $request)
    {
        $timezone = 'Asia/Jakarta';
        $nowJakarta = Carbon::now($timezone);

        $monthFilter = $request->query('month');
        $statusFilter = $request->query('status');
        $kecamatanFilter = $request->query('kecamatan');
        $kategoriFilter = $request->query('kategori');

        $combined = collect();

        foreach (Aktivasi::with('user')->get() as $item) {
            $combined->push($this->formatExportRow($item, 'AKTIVASI', $timezone));
        }

        foreach (LuarDaerah::with('user')->get() as $item) {
            $combined->push($this->formatExportRow($item, 'LUAR DAERAH', $timezone));
        }
        
        foreach (UpdateData::with('user')->get() as $item) {
            $combined->push($this->formatExportRow($item, 'UPDATE DATA', $timezone));
        }

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

        // Untuk Export, tetap urutkan dari yang paling lama ke terbaru
        $sorted = $combined->sortBy('tanggal')->values();
        $filename = "rekap_layanan_kependudukan_" . $nowJakarta->format('Ymd_His') . ".csv";
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

    public function exportExcel(Request $request)
    {
        $timezone = 'Asia/Jakarta';
        $nowJakarta = Carbon::now($timezone);

        $mode = $request->query('category', 'sistem');
        $monthFilter = $request->query('month');
        $statusFilter = $request->query('status');
        $kecamatanFilter = $request->query('kecamatan_id');

        $queries = match ($mode) {
            'aktivasi' => [
                'AKTIVASI' => Aktivasi::with('user'), 
                'LUAR DAERAH' => LuarDaerah::with('user'),
                'UPDATE DATA' => UpdateData::with('user')
            ],
            'luardaerah' => ['LUAR DAERAH' => LuarDaerah::with('user')],
            default => [
                'TROUBLE' => Trouble::with('user'),
                'SIAK' => Pengajuan::with('user'),
                'PROXY' => Proxy::with('user'),
                'TTE' => Pembubuhan::with('user'),
                'UPDATE DATA' => UpdateData::with('user'),
            ],
        };

        $allLaporans = collect();
        foreach ($queries as $label => $query) {
            foreach ($query->get() as $item) {
                $allLaporans->push($this->formatExportRow($item, $label, $timezone));
            }
        }

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