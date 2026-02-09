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
use App\Models\UpdateData;
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
        // Memastikan PHP dan Carbon menggunakan Asia/Jakarta secara konsisten
        date_default_timezone_set('Asia/Jakarta');
        Carbon::setLocale('id');
        $timezone = 'Asia/Jakarta';

        // 1. Data Wilayah (Kecamatan)
        $kecamatans = Kecamatan::orderBy('nama_kecamatan', 'asc')->get();

        // 2. Statistik Dasar
        $totalPenduduk = User::where('role', 'user')->count();
        $totalUser = User::count();

        // 3. Hitung Count secara real-time langsung dari Query
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

        // PERBAIKAN: Gunakan Paginate dan Sorting (Pending Terlama di Atas)
        $updateDatas = UpdateData::with('user')
            ->orderByRaw("CASE WHEN tanggapan_admin IS NULL OR tanggapan_admin = '' THEN 0 ELSE 1 END ASC")
            ->orderBy('created_at', 'asc')
            ->paginate(10, ['*'], 'update_page')
            ->appends(['tab' => 'laporan_update_data']);

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

        // --- Statistik Kategori Update Data ---
        $updateDataCategories = UpdateData::select(
            DB::raw("CASE 
                WHEN jenis_layanan IS NULL OR TRIM(jenis_layanan) = '' THEN 'BIODATA' 
                ELSE UPPER(TRIM(jenis_layanan)) 
            END as label"),
            DB::raw('count(*) as total')
        )
            ->groupBy('label')
            ->pluck('total', 'label')
            ->toArray();

        $pendudukTerbaru = User::where('role', 'user')->latest()->take(5)->get();

        // Pagination User
        $allUsers = User::latest()->paginate(10, ['*'], 'user_page');

        // Load Data Pengumuman
        $announcements = Announcement::latest()->get();

        // 4. Logika Grafik Pendaftaran User (7 Hari Terakhir)
        $days = collect(range(6, 0))->map(function ($i) use ($timezone) {
            return Carbon::now($timezone)->subDays($i)->format('Y-m-d');
        });

        $counts = $days->map(function ($date) {
            return User::whereDate('created_at', $date)->count();
        });

        $chartLabels = $days->map(fn($date) => date('d M', strtotime($date)));
        $chartData = $counts;

        // 5. Penggabungan Semua Data Laporan (Combined Collection)
        $combinedLaporans = collect();

        // Mapping helper
        $this->mapLaporan($combinedLaporans, $troubles, 'trouble', 'PC - ', $timezone);
        $this->mapLaporan($combinedLaporans, $aktivasis, 'aktivasi', 'NIK - ', $timezone);
        $this->mapLaporan($combinedLaporans, $luarDaerahs, 'luardaerah', 'LUAR DAERAH - ', $timezone);
        $this->mapLaporan($combinedLaporans, UpdateData::with('user')->get(), 'updatedata', 'UPDATE - ', $timezone);
        $this->mapLaporan($combinedLaporans, $pengajuans, 'pengajuan', 'SIAK - ', $timezone);
        $this->mapLaporan($combinedLaporans, $proxies, 'proxy', 'PROXY - JARINGAN', $timezone);
        $this->mapLaporan($combinedLaporans, $pembubuhans, 'pembubuhan', 'TTE - ', $timezone);

        /**
         * LOGIC SORTING: Status 'pending' diprioritaskan di atas.
         */
        $sortedLaporans = $combinedLaporans->sort(function ($a, $b) {
            $priorityA = ($a->status === 'pending') ? 0 : 1;
            $priorityB = ($b->status === 'pending') ? 0 : 1;

            if ($priorityA === $priorityB) {
                return $b->created_at->timestamp <=> $a->created_at->timestamp;
            }
            return $priorityA <=> $priorityB;
        })->values();

        // --- SINKRONISASI UNTUK ALPINE.JS (VERSI MULTI-FOTO) ---
        $updateDatasJs = $updateDatas->map(function ($item) use ($timezone) {
            $status = $item->status ?? ($item->is_rejected ? 'ditolak' : (!empty($item->tanggapan_admin) ? 'selesai' : 'pending'));
            $createdAt = Carbon::parse($item->created_at)->timezone($timezone);

            // LOGIC FIX: Memastikan lampiran menjadi Array URL
            $lampiranRaw = $item->lampiran;
            $allLampirans = [];

            if (!empty($lampiranRaw)) {
                $decoded = json_decode($lampiranRaw, true);
                if (is_array($decoded)) {
                    foreach ($decoded as $path) {
                        $cleanPath = str_replace('\\', '', $path);
                        $allLampirans[] = asset('storage/' . $cleanPath);
                    }
                } else {
                    $cleanPath = str_replace('\\', '', $lampiranRaw);
                    $allLampirans[] = asset('storage/' . $cleanPath);
                }
            }

            return [
                'id' => $item->id,
                'name' => $item->user->name ?? 'User Tak Dikenal',
                'location' => $item->user->location ?? 'LUAR WILAYAH',
                'month' => $createdAt->format('m'),
                'status' => $status,
                'kat_row' => 'updatedata',
                'jenis_layanan' => strtoupper($item->jenis_layanan ?? 'BIODATA'),
                'lampiran' => count($allLampirans) > 0 ? $allLampirans : null,
                'nik_pemohon' => $item->nik_pemohon ?? '-',
                'deskripsi' => $item->deskripsi ?? $item->alasan ?? '-',
                'tanggapan_admin' => $item->tanggapan_admin,
                'created_at' => $createdAt->format('d/m/Y H:i'),
                'csrf' => csrf_token(),
                'url_respon' => route('admin.laporan.respon'),
                'url_hapus' => route('admin.laporan.hapus', ['id' => $item->id, 'type' => 'updatedata']),
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
            'updateDatasJs',
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
            'updateDataCategories',
            'announcements',
            'siakCategories',
            'troubleCategories',
            'currentTab'
        ))->with([
                    'laporans' => $sortedLaporans,
                    'update_datas' => $updateDatas
                ]);
    }

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
                    $kategori_asli = 'LUAR DAERAH';
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
                    $pesan = "Kendala: " . $kategori_asli . " | Deskripsi: " . ($item->deskripsi ?? '-');
                    break;
                case 'proxy':
                    $kategori_asli = 'JARINGAN';
                    $kategori_label = $prefix;
                    $pesan = "Deskripsi: " . ($item->deskripsi ?? $item->ip_detail ?? 'Akses Jaringan');
                    break;
                case 'pembubuhan':
                    $kategori_asli = 'TTE';
                    $jenis_dokumen = strtoupper($item->jenis_dokumen ?? 'PEMBUBUHAN');
                    $kategori_label .= $jenis_dokumen;
                    $pesan = "Permohonan TTE Dokumen: " . ($item->jenis_dokumen ?? '-');
                    break;
            }

            // Memastikan parse waktu selalu ke Jakarta
            $createdAt = Carbon::parse($item->created_at)->timezone($timezone);
            $updatedAt = Carbon::parse($item->updated_at)->timezone($timezone);

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
                'created_at' => $createdAt,
                'updated_at' => $updatedAt,
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
        $type = strtolower(trim($type));
        return match ($type) {
            'aktivasi' => Aktivasi::findOrFail($id),
            'luardaerah', 'luar daerah' => LuarDaerah::findOrFail($id),
            'updatedata', 'update data', 'update' => UpdateData::findOrFail($id),
            'pengajuan', 'siak' => Pengajuan::findOrFail($id),
            'proxy' => Proxy::findOrFail($id),
            'trouble' => Trouble::findOrFail($id),
            'pembubuhan', 'tte' => Pembubuhan::findOrFail($id),
            default => throw new \Exception("Tipe laporan '$type' tidak dikenal."),
        };
    }

    public function kirimRespon(Request $request)
    {
        // Set timezone di awal proses simpan
        date_default_timezone_set('Asia/Jakarta');

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

            // Memastikan kolom updated_at manual atau otomatis menggunakan waktu Jakarta
            if (Schema::hasColumn($tableName, 'updated_at')) {
                $model->updated_at = Carbon::now('Asia/Jakarta');
            }

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

            $tabRedirect = ($request->type == 'updatedata') ? 'laporan_update_data' : 'laporan_sistem';
            return redirect()->route('admin.index', ['tab' => $tabRedirect])->with('success', 'Respon ' . ucfirst($request->type) . ' berhasil dikirim!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Gagal mengirim tanggapan: ' . $e->getMessage()]);
        }
    }

    public function tolakLaporan(Request $request, $id)
    {
        // Set timezone di awal proses simpan
        date_default_timezone_set('Asia/Jakarta');

        $request->validate([
            'type' => 'required',
            'admin_note' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();
            $model = $this->getModelByType($request->type, $id);
            $tableName = $model->getTable();

            $model->tanggapan_admin = $request->admin_note ?? 'Laporan ditolak karena data tidak sesuai kriteria.';

            if (Schema::hasColumn($tableName, 'updated_at')) {
                $model->updated_at = Carbon::now('Asia/Jakarta');
            }

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

            $tabRedirect = ($request->type == 'updatedata') ? 'laporan_update_data' : 'laporan_sistem';
            return redirect()->route('admin.index', ['tab' => $tabRedirect])->with('success', 'Laporan ' . ucfirst($request->type) . ' telah ditolak.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Gagal menolak laporan: ' . $e->getMessage()]);
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
                    $decoded = is_array($data) ? $data : json_decode($data, true);

                    if (is_array($decoded)) {
                        foreach ($decoded as $path) {
                            if (is_string($path) && Storage::disk('public')->exists($path)) {
                                Storage::disk('public')->delete($path);
                            }
                        }
                    } else {
                        if (is_string($data) && Storage::disk('public')->exists($data)) {
                            Storage::disk('public')->delete($data);
                        }
                    }
                }
            }

            $model->delete();
            $tabRedirect = ($request->type == 'updatedata') ? 'laporan_update_data' : 'laporan_sistem';
            return redirect()->route('admin.index', ['tab' => $tabRedirect])->with('success', 'Laporan berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Gagal menghapus laporan: ' . $e->getMessage()]);
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'pin' => 'required|string|max:10',
            'nik'      => 'required|string|size:16|unique:users,nik',
            'location' => 'required|string|max:255',
            'role' => 'required|in:admin,user',
            'password' => 'required|min:6',
            'email' => 'nullable|email|unique:users,email'
        ]);

        try {
            User::create([
                'name' => $validated['name'],
                'pin' => $validated['pin'],
                'nik'       => $validated['nik'],
                'location' => $validated['location'],
                'role' => $validated['role'],
                'email' => $validated['email'] ?? ($validated['name'] . rand(10, 99) . '@sistem.com'),
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
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'pin' => 'required|string|max:10',
            'location' => 'required|string|max:255',
            'role' => 'required|in:admin,user',
            'email' => 'nullable|email|unique:users,email,' . $id,
            'password' => 'nullable|min:6',
        ]);

        try {
            if ($user->id === auth()->id() && $validated['role'] === 'user') {
                return redirect()->route('admin.index', ['tab' => 'data_user'])->withErrors(['error' => 'Anda tidak bisa menurunkan role Anda sendiri menjadi User!']);
            }

            $updateData = [
                'name' => $validated['name'],
                'pin' => $validated['pin'],
                'location' => $validated['location'],
                'role' => $validated['role'],
                'email' => $validated['email'] ?? $user->email,
            ];

            if (!empty($validated['password'])) {
                $updateData['password'] = Hash::make($validated['password']);
            }

            $user->update($updateData);

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

        $fitur = strtoupper($defaultType);
        $subKategori = '-';
        if (isset($item->jenis_layanan)) {
            $subKategori = strtoupper($item->jenis_layanan);
        } elseif (isset($item->jenis_dokumen)) {
            $subKategori = strtoupper($item->jenis_dokumen);
        } elseif (isset($item->kategori)) {
            $subKategori = strtoupper($item->kategori);
        }

        return [
            'fitur' => $fitur,
            'sub_kategori' => $subKategori,
            'pelapor' => $item->user->name ?? 'N/A',
            'wilayah' => strtoupper($item->user->location ?? 'LUAR WILAYAH'),
            'nik_target' => $item->nik_aktivasi ?? ($item->nik_luar_daerah ?? ($item->nik_pemohon ?? '-')),
            'alasan' => $item->alasan ?? ($item->deskripsi ?? ($item->deskripsi_masalah ?? '-')),
            'status' => $status,
            'tanggapan' => $item->tanggapan_admin ?? '-',
            'admin' => $item->processed_by ?? '-',
            'tanggal' => Carbon::parse($item->created_at)->timezone($timezone)
        ];
    }

    /**
     * FUNGSI PERBAIKAN: Routing Export Berdasarkan Fitur
     */
    public function exportAktivasi(Request $request)
    {
        $request->merge(['type' => 'aktivasi']);
        return $this->exportExcel($request);
    }

    public function exportUpdateData(Request $request)
    {
        $request->merge(['type' => 'updatedata']);
        return $this->exportExcel($request);
    }

    public function exportSistem(Request $request)
    {
        $request->merge(['type' => 'sistem']);
        return $this->exportExcel($request);
    }

    public function exportExcel(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        $timezone = 'Asia/Jakarta';
        $nowJakarta = Carbon::now($timezone);

        $mode = $request->query('type');
        $monthFilter = $request->query('month');
        $statusFilter = $request->query('status');
        $kecamatanFilter = $request->query('kecamatan');
        $kategoriFilter = $request->query('kategori');

        // Tentukan Query dasar berdasarkan mode
        $queries = match ($mode) {
            'updatedata' => ['UPDATE DATA' => UpdateData::with('user')],
            'aktivasi' => [
                'AKTIVASI' => Aktivasi::with('user'),
                'LUAR DAERAH' => LuarDaerah::with('user')
            ],
            'trouble' => ['TROUBLE' => Trouble::with('user')],
            'pengajuan' => ['SIAK' => Pengajuan::with('user')],
            'proxy' => ['PROXY' => Proxy::with('user')],
            'pembubuhan' => ['TTE' => Pembubuhan::with('user')],
            default => [
                'TROUBLE' => Trouble::with('user'),
                'SIAK' => Pengajuan::with('user'),
                'PROXY' => Proxy::with('user'),
                'TTE' => Pembubuhan::with('user'),
                'AKTIVASI' => Aktivasi::with('user'),
                'LUAR DAERAH' => LuarDaerah::with('user'),
                'UPDATE DATA' => UpdateData::with('user'),
            ],
        };

        $allLaporans = collect();
        foreach ($queries as $label => $query) {
            foreach ($query->get() as $item) {
                // Gunakan label asli dari switch match sebagai 'fitur'
                $allLaporans->push($this->formatExportRow($item, $label, $timezone));
            }
        }

        // --- FILTERING COLLECTION ---

        if ($monthFilter && $monthFilter !== '') {
            $allLaporans = $allLaporans->filter(fn($i) => $i['tanggal']->format('m') == $monthFilter);
        }

        if ($statusFilter && $statusFilter !== '') {
            $allLaporans = $allLaporans->filter(fn($i) => strtolower($i['status']) == strtolower($statusFilter));
        }

        if ($kecamatanFilter && $kecamatanFilter !== '') {
            $allLaporans = $allLaporans->filter(fn($i) => strtolower($i['wilayah']) === strtolower($kecamatanFilter));
        }

        // Perbaikan filter kategori agar mendeteksi 'LUAR DAERAH' (dengan spasi)
        if ($kategoriFilter && $kategoriFilter !== '') {
            $allLaporans = $allLaporans->filter(function ($i) use ($kategoriFilter) {
                // Menghapus spasi dan membandingkan secara case-insensitive
                $cleanFitur = str_replace(' ', '', strtolower($i['fitur']));
                $cleanKategori = str_replace([' ', '_'], '', strtolower($kategoriFilter));
                return $cleanFitur === $cleanKategori;
            });
        }

        $sortedExport = $allLaporans->sortBy('tanggal')->values();

        $fileLabel = $mode ?: 'sistem_terpadu';
        $filename = "rekap_" . $fileLabel . "_" . $nowJakarta->format('Ymd_His') . ".csv";

        $header = ['NO', 'FITUR UTAMA', 'JENIS/KATEGORI', 'PELAPOR', 'WILAYAH', 'NIK/TARGET', 'DESKRIPSI/ALASAN', 'STATUS', 'TANGGAPAN ADMIN', 'PROSES OLEH', 'TANGGAL'];

        return response()->stream(function () use ($sortedExport, $header) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($file, $header);

            foreach ($sortedExport as $index => $row) {
                fputcsv($file, [
                    $index + 1,
                    $row['fitur'],
                    $row['sub_kategori'],
                    $row['pelapor'],
                    $row['wilayah'],
                    "'" . $row['nik_target'],
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
}