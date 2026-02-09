<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - SIMAK Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap');

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="bg-slate-50 min-h-screen" x-data="{ tab: 'dashboard' }">

    <nav class="bg-slate-900 text-white p-3 md:p-4 shadow-lg sticky top-0 z-50 border-b-2 border-orange-600">
        <div class="max-w-[1600px] mx-auto flex justify-between items-center">
            <div class="flex items-center space-x-2 md:space-x-3">
                <div class="bg-orange-500/10 p-2 rounded-xl">
                    <i class="fas fa-layer-group text-orange-500 text-base md:text-xl"></i>
                </div>
                <span class="flex flex-col">
                    <span class="font-black tracking-tighter uppercase text-sm md:text-lg leading-none">
                        SIMAK <span class="text-orange-500">Admin</span>
                    </span>
                    <span class="text-[8px] font-bold text-slate-400 tracking-[0.2em] uppercase">Management
                        System</span>
                </span>
            </div>
            <div class="flex items-center space-x-3 md:space-x-6">
                <div class="hidden sm:flex flex-col items-end">
                    <span
                        class="text-[8px] md:text-[10px] text-slate-400 font-bold uppercase tracking-[0.2em]">Status</span>
                    <span class="text-[9px] md:text-[10px] text-green-400 font-black flex items-center">
                        <span class="animate-pulse mr-1">●</span> OPERATIONAL
                    </span>
                </div>

                <div class="hidden md:block h-8 border-l border-slate-700 mx-2"></div>

                <form action="{{ route('logout') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit"
                        class="group relative flex flex-col items-center justify-center transition-all duration-300 active:scale-95 py-1">
                        <span
                            class="text-[10px] md:text-xs font-black tracking-[0.2em] uppercase italic text-slate-400 group-hover:text-red-500 transition-colors">
                            Keluar
                        </span>
                        <span
                            class="h-[1.5px] w-0 group-hover:w-full bg-red-500 transition-all duration-300 shadow-[0_0_8px_rgba(239,68,68,0.5)]"></span>
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <div class="max-w-[1600px] mx-auto flex flex-col md:flex-row gap-0">

        <aside
            class="w-full md:w-20 lg:w-72 bg-slate-900 md:min-h-screen p-2 md:p-4 text-slate-300 border-b md:border-b-0 md:border-r border-slate-800 transition-all duration-300">
            <p class="hidden lg:block text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-4 px-4">
                Control
            </p>

            <div class="flex flex-row md:flex-col justify-around md:justify-start gap-1 md:space-y-2">

                <button @click="tab ='dashboard'"
                    :class="tab === 'dashboard' ? 'bg-orange-600 text-white shadow-lg' : 'hover:bg-slate-800 text-slate-400'"
                    class="flex-1 md:flex-none flex flex-col md:flex-row items-center md:space-x-3 p-2 md:p-3 lg:p-4 rounded-xl transition-all group">
                    <i class="fas fa-chart-line text-lg md:text-base lg:w-5"></i>
                    <span
                        class="text-[10px] lg:text-sm font-bold mt-1 md:mt-0 lg:block hidden md:group-hover:block lg:group-hover:inline">Dashboard</span>
                </button>

                <button @click="tab = 'tambah_akun'"
                    :class="tab === 'tambah_akun' ? 'bg-orange-600 text-white shadow-lg' : 'hover:bg-slate-800 text-slate-400'"
                    class="flex-1 md:flex-none flex flex-col md:flex-row items-center md:space-x-3 p-2 md:p-3 lg:p-4 rounded-xl transition-all group">
                    <i class="fas fa-user-plus text-lg md:text-base lg:w-5"></i>
                    <span class="text-[10px] lg:text-sm font-bold mt-1 md:mt-0 lg:block hidden">Kelola Akun</span>
                </button>

                <button @click="tab = 'laporan_aktivasi'"
                    :class="tab === 'laporan_aktivasi' ? 'bg-orange-600 text-white shadow-lg' : 'hover:bg-slate-800 text-slate-400'"
                    class="flex-1 md:flex-none flex flex-col md:flex-row items-center md:space-x-3 p-2 md:p-3 lg:p-4 rounded-xl transition-all group">
                    <i class="fas fa-id-card text-lg"></i>
                    <span class="text-[10px] lg:text-sm font-bold mt-1 md:mt-0 lg:block hidden">Laporan Aktivasi
                        NIK</span>
                </button>

                <button @click="tab = 'laporan_sistem'"
                    :class="tab === 'laporan_sistem' ? 'bg-orange-600 text-white shadow-lg' : 'hover:bg-slate-800 text-slate-400'"
                    class="flex-1 md:flex-none flex flex-col md:flex-row items-center md:space-x-3 p-2 md:p-3 lg:p-4 rounded-xl transition-all group">
                    <i class="fas fa-tools text-lg"></i>
                    <span class="text-[10px] lg:text-sm font-bold mt-1 md:mt-0 lg:block hidden">Laporan Trouble</span>
                </button>

                <button @click="tab = 'laporan_update_data'"
                    :class="tab === 'laporan_update_data' ? 'bg-orange-600 text-white shadow-lg' : 'hover:bg-slate-800 text-slate-400'"
                    class="flex-1 md:flex-none flex flex-col md:flex-row items-center md:space-x-3 p-2 md:p-3 lg:p-4 rounded-xl transition-all group">
                    <i class="fas fa-user-edit text-lg"></i>
                    <span class="text-[10px] lg:text-sm font-bold mt-1 md:mt-0 lg:block hidden">Laporan Update
                        Data</span>
                </button>

                <div id="badge-notif-container">
                    @php $countPending = $laporans->whereNull('tanggapan_admin')->count(); @endphp
                    @if($countPending > 0)
                        <span
                            class="absolute top-1 right-1 md:right-2 lg:right-4 bg-red-500 text-[8px] px-1.5 py-0.5 rounded-full font-black text-white animate-pulse">
                            {{ $countPending }}
                        </span>
                    @endif
                </div>
                </button>
                <script>

                    function updateAdminNotif() {
                        // Mengambil data dari URL dashboard saat ini
                        fetch(window.location.href)
                            .then(response => response.text())
                            .then(html => {
                                const parser = new DOMParser();
                                const doc = parser.parseFromString(html, 'text/html');

                                // 1. Update Badge Angka pada Tombol
                                const newBadge = doc.getElementById('badge-notif-container').innerHTML;
                                document.getElementById('badge-notif-container').innerHTML = newBadge;

                                // 2. Update List Notifikasi (Pusat Notifikasi Masuk)
                                const newContent = doc.getElementById('notification-items-wrapper');
                                if (newContent) {
                                    document.getElementById('notification-items-wrapper').innerHTML = newContent.innerHTML;
                                }

                                // 3. Update Text Total Pesan
                                const newTotal = doc.getElementById('notification-count');
                                if (newTotal) {
                                    document.getElementById('notification-count').innerHTML = newTotal.innerHTML;
                                }
                            })
                            .catch(error => console.log('Realtime check failed:', error));
                    }
                    // Jalankan pengecekan setiap 5 detik
                    setInterval(updateAdminNotif, 5000);
                </script>
            </div>
        </aside>

        {{-- Fitur Dashboard Admin --}}
        <main class="flex-1 p-8">

            <div x-show="tab === 'dashboard'" x-transition x-cloak class="max-w-6xl mx-auto">
                {{-- HEADER --}}
                <header class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div class="flex items-center gap-4">
                        <div
                            class="hidden md:flex w-14 h-14 bg-orange-500 rounded-2xl items-center justify-center shadow-lg shadow-orange-200">
                            <i class="fas fa-user-shield text-white text-2xl"></i>
                        </div>
                        <div>
                            <h1
                                class="text-2xl md:text-3xl font-black text-slate-800 uppercase italic tracking-tighter leading-none">
                                Welcome to <span class="text-orange-500">Dashboard Admin</span>
                            </h1>
                            <div class="flex items-center gap-2 mt-2">
                                <span class="text-slate-500 text-xs md:text-sm font-medium tracking-tight">
                                    @php
                                        $waktu = \Carbon\Carbon::now('Asia/Jakarta');
                                        $hour = $waktu->hour;
                                        $sapaan = ($hour >= 5 && $hour < 11) ? 'Selamat Pagi' : (($hour >= 11 && $hour < 15) ? 'Selamat Siang' : (($hour >= 15 && $hour < 18) ? 'Selamat Sore' : 'Selamat Malam'));

                                        $totalPending = $sortedLaporans->where('status', 'Pending')->count();

                                        $countSiak = $pengajuans->count();
                                        $countAktivasi = $aktivasis->count();
                                        $countTte = $pembubuhans->count();
                                        $countProxy = $proxies->count();
                                        $countTrouble = $troubles->count();
                                        $countLuarDaerah = $luarDaerahs->count();

                                        // Perbaikan: Pastikan variabel tersedia, jika tidak set sebagai collection kosong
                                        $update_datas = $update_datas ?? collect();
                                        $countUpdateData = $update_datas->count();

                                        $siakCategories = $pengajuans->groupBy('kategori')->map->count();
                                        $tteCategories = $pembubuhans->groupBy('kategori')->map->count();
                                        $troubleCategories = $troubles->groupBy('kategori')->map->count();
                                        $luarDaerahCategories = $luarDaerahs->groupBy('kategori')->map->count();

                                        // KATEGORI UNTUK UPDATE DATA
                                        $updateCategories = $update_datas->groupBy('kategori')->map->count();
                                    @endphp
                                    {{ $sapaan }},
                                </span>
                                <span
                                    class="text-orange-600 font-black text-xs md:text-sm uppercase tracking-tighter bg-orange-50 px-3 py-1 rounded-lg border border-orange-100 shadow-sm">
                                    {{ auth()->user()->name }} 👋
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 sm:gap-3">
                        @php
                            $pendingNotifications = collect($sortedLaporans)->filter(function ($item) {
                                return empty($item->tanggapan_admin);
                            });
                            $unreadCount = $pendingNotifications->count();
                        @endphp

                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open"
                                class="relative w-10 h-10 sm:w-12 sm:h-12 bg-white rounded-xl sm:rounded-2xl shadow-md border border-slate-100 flex items-center justify-center transition-all hover:bg-slate-50 group">
                                <i
                                    class="fas fa-bell text-slate-400 group-hover:text-orange-500 transition-colors text-sm sm:text-base"></i>
                                @if($unreadCount > 0)
                                    <span
                                        class="absolute -top-1 -right-1 w-4 h-4 sm:w-5 sm:h-5 bg-red-500 text-white text-[9px] sm:text-[10px] font-black rounded-full flex items-center justify-center border-2 border-white animate-bounce shadow-sm">
                                        {{ $unreadCount }}
                                    </span>
                                @endif
                            </button>

                            <div x-show="open" x-transition:opacity
                                class="fixed inset-0 bg-slate-900/20 backdrop-blur-sm z-[998] sm:hidden"
                                @click="open = false"></div>

                            <div x-show="open" @click.away="open = false"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                                x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-cloak
                                class="fixed inset-x-4 top-20 sm:absolute sm:inset-auto sm:right-0 sm:top-full sm:mt-3 w-auto sm:w-80 md:w-96 bg-white rounded-[1.5rem] sm:rounded-[2rem] shadow-2xl border border-slate-100 z-[999] overflow-hidden">

                                <div
                                    class="p-4 sm:p-5 border-b border-slate-50 bg-slate-50/50 flex justify-between items-center">
                                    <h3 class="font-black text-slate-800 uppercase italic text-xs sm:text-sm">Notifikasi
                                        Masuk</h3>
                                    <span
                                        class="bg-slate-900 text-white text-[8px] sm:text-[9px] px-2 sm:px-3 py-1 rounded-full font-black tracking-widest">
                                        {{ $unreadCount }} BARU
                                    </span>
                                </div>

                                <div
                                    class="max-h-[60vh] sm:max-h-[400px] overflow-y-auto p-3 sm:p-4 space-y-2 sm:space-y-3 custom-scrollbar">
                                    @forelse($pendingNotifications as $notif)
                                        @php
                                            $kat = strtoupper($notif->kategori);

                                            // 1. LOGIKA LAPORAN SISTEM / TROUBLE (Prioritas: SIAK, TROUBLE, PROXY, PEMBUBUHAN)
                                            if (Str::contains($kat, 'SIAK') || Str::contains($kat, 'TROUBLE') || Str::contains($kat, 'PROXY') || Str::contains($kat, 'PEMBUBUHAN')) {
                                                $targetTab = 'laporan_sistem';
                                                $icon = 'fa-cogs';
                                                $color = 'bg-orange-100 text-orange-600';
                                            }
                                            // 2. LOGIKA LAPORAN NIK (Aktivasi NIK, Luar Daerah)
                                            elseif (Str::contains($kat, 'NIK') || Str::contains($kat, 'DAERAH')) {
                                                $targetTab = 'laporan_aktivasi';
                                                $icon = 'fa-fingerprint';
                                                $color = 'bg-blue-100 text-blue-600';
                                            }
                                            // 3. LOGIKA LAPORAN UPDATE DATA (Hanya Perubahan Data User)
                                            elseif (Str::contains($kat, 'PERUBAHAN') || Str::contains($kat, 'UPDATE')) {
                                                $targetTab = 'laporan_update_data';
                                                $icon = 'fa-user-edit';
                                                $color = 'bg-emerald-100 text-emerald-600';
                                            }
                                            // FALLBACK
                                            else {
                                                $targetTab = 'laporan_sistem';
                                                $icon = 'fa-database';
                                                $color = 'bg-slate-100 text-slate-600';
                                            }
                                        @endphp

                                        <div @click="tab = '{{ $targetTab }}'; open = false; window.scrollTo({top: 0, behavior: 'smooth'})"
                                            class="group p-3 sm:p-4 bg-white border border-slate-50 rounded-xl sm:rounded-2xl flex items-start gap-3 sm:gap-4 hover:bg-slate-50 transition-all cursor-pointer border-l-4 {{ str_replace('bg-', 'border-', explode(' ', $color)[0]) }}">

                                            <div
                                                class="{{ $color }} w-8 h-8 sm:w-10 sm:h-10 shrink-0 rounded-lg sm:rounded-xl flex items-center justify-center text-xs sm:text-sm shadow-inner group-hover:scale-110 transition-transform">
                                                <i class="fas {{ $icon }}"></i>
                                            </div>

                                            <div class="flex-1 min-w-0">
                                                <div class="flex flex-col">
                                                    <div class="flex justify-between items-start">
                                                        <span
                                                            class="text-[9px] sm:text-[10px] font-black text-slate-800 uppercase leading-none truncate">{{ $notif->user_name }}</span>
                                                        <span
                                                            class="text-[7px] bg-slate-100 px-1.5 py-0.5 rounded text-slate-500 font-black uppercase italic">{{ $notif->kategori }}</span>
                                                    </div>
                                                    <span
                                                        class="text-[7px] sm:text-[8px] text-slate-400 font-bold uppercase mt-1 tracking-tighter italic">
                                                        📂 Menu: {{ str_replace('_', ' ', $targetTab) }}
                                                    </span>
                                                </div>
                                                <p
                                                    class="text-[10px] sm:text-[11px] text-slate-500 mt-1 line-clamp-2 italic leading-relaxed">
                                                    "{{ $notif->pesan }}"
                                                </p>
                                                <span
                                                    class="text-[7px] sm:text-[8px] text-orange-400 font-mono mt-2 block uppercase tracking-widest">
                                                    {{ $notif->created_at->diffForHumans() }}
                                                </span>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="text-center py-10 sm:py-12">
                                            <i class="fas fa-check-double text-slate-200 text-xl sm:text-2xl mb-3"></i>
                                            <p
                                                class="text-slate-400 font-bold text-[9px] sm:text-[10px] uppercase tracking-widest">
                                                Tidak ada laporan baru</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <div
                            class="bg-white px-3 sm:px-6 py-2 sm:py-3 rounded-xl sm:rounded-2xl shadow-md border border-slate-100 flex items-center gap-2 sm:gap-4">
                            <div class="text-right">
                                <p
                                    class="text-[8px] sm:text-[10px] font-black text-slate-400 uppercase leading-none tracking-[0.1em] sm:tracking-widest mb-1">
                                    Waktu Sistem</p>
                                <p class="text-xs sm:text-sm font-black text-slate-800 whitespace-nowrap">
                                    {{ date('d M Y') }}
                                </p>
                            </div>
                            <div
                                class="w-8 h-8 sm:w-10 sm:h-10 bg-slate-50 rounded-lg sm:rounded-xl flex items-center justify-center text-orange-500">
                                <i class="fas fa-calendar-alt text-sm sm:text-lg"></i>
                            </div>
                        </div>
                    </div>
                </header>

                {{-- STATS CARDS --}}
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-8 gap-4 mb-8">
                    @php
                        /** * SINKRONISASI DATA REAL-TIME
                         * Menghitung data berdasarkan kolom spesifik di database
                         */

                        // 1. Data Utama (Count)
                        $countSiak = $pengajuans->count();
                        $countAktivasi = $aktivasis->count();
                        $countUpdateData = ($update_datas ?? collect())->count();
                        $countProxy = ($proxies ?? collect())->count();
                        $countTte = ($pembubuhans ?? collect())->count();
                        $countLuarDaerah = ($luarDaerahs ?? collect())->count();
                        $countTrouble = ($troubles ?? collect())->count();
                        $totalUserCount = $totalUser ?? 0;

                        // 2. Pengelompokan Kategori/Jenis untuk Progress Bars
                        // Kendala SIAK & Trouble menggunakan kolom 'kategori'
                        $siakCategories = $pengajuans->groupBy('kategori')->map->count();
                        $troubleCategories = ($troubles ?? collect())->groupBy('kategori')->map->count();

                        // Aktivasi NIK & Update Data menggunakan kolom 'jenis_layanan'
                        $aktivasiCategories = ($aktivasis ?? collect())->groupBy('jenis_layanan')->map->count();
                        $updateCategories = ($update_datas ?? collect())->groupBy('jenis_layanan')->map->count();

                        // Pembubuhan & Luar Daerah menggunakan kolom 'jenis_dokumen'
                        $tteCategories = ($pembubuhans ?? collect())->groupBy('jenis_dokumen')->map->count();
                        $luarDaerahCategories = ($luarDaerahs ?? collect())->groupBy('jenis_dokumen')->map->count();

                        $cards = [
                            [
                                'label' => 'User',
                                'val' => $totalUserCount,
                                'color' => 'slate-900',
                                'icon' => 'fa-users',
                                'txt' => 'white'
                            ],
                            [
                                'label' => 'Kendala SIAK',
                                'val' => $countSiak,
                                'color' => 'white',
                                'icon' => 'fa-database',
                                'txt' => 'orange-500'
                            ],
                            [
                                'label' => 'Aktivasi NIK',
                                'val' => $countAktivasi,
                                'color' => 'white',
                                'icon' => 'fa-fingerprint',
                                'txt' => 'blue-500'
                            ],
                            [
                                'label' => 'Update Data',
                                'val' => $countUpdateData,
                                'color' => 'white',
                                'icon' => 'fa-sync-alt',
                                'txt' => 'amber-500'
                            ],
                            [
                                'label' => 'Proxy',
                                'val' => $countProxy,
                                'color' => 'white',
                                'icon' => 'fa-network-wired',
                                'txt' => 'emerald-500'
                            ],
                            [
                                'label' => 'Pembubuhan',
                                'val' => $countTte,
                                'color' => 'white',
                                'icon' => 'fa-file-signature',
                                'txt' => 'purple-500'
                            ],
                            [
                                'label' => 'Luar Daerah',
                                'val' => $countLuarDaerah,
                                'color' => 'white',
                                'icon' => 'fa-map-marked-alt',
                                'txt' => 'cyan-500'
                            ],
                            [
                                'label' => 'Trouble',
                                'val' => $countTrouble,
                                'color' => 'white',
                                'icon' => 'fa-bug',
                                'txt' => 'rose-500'
                            ],
                        ];
                    @endphp

                    @foreach($cards as $c)
                        <div
                            class="bg-{{ $c['color'] }} p-4 rounded-[1.5rem] shadow-sm border border-slate-100 relative overflow-hidden transition-all duration-300 hover:shadow-lg hover:-translate-y-1 group">
                            @if($c['color'] == 'white')
                                <div class="absolute top-0 left-0 w-1 h-full bg-{{ $c['txt'] }}"></div>
                            @endif

                            <p
                                class="text-{{ $c['txt'] }} text-[8px] font-black uppercase tracking-widest relative z-10 opacity-80 group-hover:opacity-100">
                                {{ $c['label'] }}
                            </p>

                            <h2
                                class="text-2xl font-black {{ $c['color'] == 'white' ? 'text-slate-800' : 'text-white' }} leading-tight relative z-10 mt-1">
                                {{ $c['val'] }}
                            </h2>

                            <div
                                class="absolute -right-2 -bottom-2 opacity-10 z-0 transition-transform group-hover:scale-110 duration-500">
                                <i
                                    class="fas {{ $c['icon'] }} text-5xl text-{{ $c['txt'] === 'white' ? 'slate-400' : $c['txt'] }}"></i>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- MAIN SECTION: FORM PENGUMUMAN & DONUT CHART --}}
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                    {{-- FORM BUAT PENGUMUMAN --}}
                    <div
                        class="lg:col-span-2 bg-[#1a1f2e] p-8 rounded-[2.5rem] shadow-2xl border border-slate-800 relative overflow-hidden group">
                        <div class="relative z-10">
                            <div class="flex items-center justify-between mb-8">
                                <div>
                                    <h3
                                        class="font-black text-white text-xl uppercase italic tracking-tighter leading-none">
                                        Buat Pengumuman
                                    </h3>
                                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-1">
                                        Kirim Info ke Dashboard User
                                    </p>
                                </div>
                                <i class="fas fa-bullhorn text-orange-500 text-2xl animate-pulse"></i>
                            </div>

                            @if(session('success'))
                                <div
                                    class="mb-4 p-4 bg-emerald-500/10 border border-emerald-500/30 rounded-2xl text-emerald-400 text-[10px] font-bold uppercase text-center animate-bounce">
                                    <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                                </div>
                            @endif

                            <form action="{{ route('admin.announcements.store') }}" method="POST" class="space-y-4">
                                @csrf
                                <div class="grid grid-cols-1 gap-4">
                                    <input type="text" name="title" placeholder="Judul Pengumuman..." required
                                        class="w-full bg-[#242b3d] border border-slate-700 rounded-2xl px-6 py-4 text-white text-sm focus:outline-none focus:ring-2 focus:ring-orange-500/50 focus:border-orange-500 transition-all placeholder:text-slate-500 font-medium">

                                    <textarea name="message" rows="4" placeholder="Isi pesan pengumuman..." required
                                        class="w-full bg-[#242b3d] border border-slate-700 rounded-2xl px-6 py-4 text-white text-sm focus:outline-none focus:ring-2 focus:ring-orange-500/50 focus:border-orange-500 transition-all placeholder:text-slate-500 font-medium resize-none"></textarea>
                                </div>
                                <input type="hidden" name="type" value="info">
                                <button type="submit"
                                    class="w-full bg-orange-500 hover:bg-orange-600 text-white font-black uppercase italic py-5 rounded-2xl shadow-lg shadow-orange-900/40 transition-all transform hover:scale-[1.01] active:scale-[0.98] tracking-widest text-sm flex items-center justify-center gap-3">
                                    <i class="fas fa-paper-plane"></i> Publikasikan Sekarang
                                </button>
                            </form>
                        </div>
                        <i
                            class="fas fa-bullhorn absolute -right-12 -bottom-12 text-white/5 text-[18rem] -rotate-12 pointer-events-none group-hover:text-white/[0.07] transition-all duration-700"></i>
                    </div>

                    {{-- SEBARAN LAYANAN (DONUT CHART) --}}
                    <div
                        class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 flex flex-col justify-between">
                        <div class="text-center">
                            <h3 class="font-black text-slate-800 text-sm uppercase italic tracking-tighter">Sebaran
                                Layanan</h3>
                            <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-1">Total
                                Komposisi Masuk</p>
                        </div>

                        <div class="relative h-[220px] w-full my-4">
                            <canvas id="categoryChart"></canvas>
                        </div>

                        <div class="bg-slate-50 rounded-3xl p-4 mt-2">
                            <div class="text-center">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total
                                    Keseluruhan</p>
                                <p class="text-3xl font-black text-slate-800 tracking-tighter">
                                    {{ $countSiak + $countAktivasi + $countTte + $countProxy + $countTrouble + $countLuarDaerah + $countUpdateData }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- CATEGORY PROGRESS BARS --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-6">
                    @php
                        $catLists = [
                            ['title' => 'Kendala SIAK (Kategori)', 'icon' => 'fa-database', 'color' => 'orange', 'data' => $siakCategories, 'total' => $countSiak],
                            ['title' => 'Update Data (Jenis)', 'icon' => 'fa-sync-alt', 'color' => 'amber', 'data' => $updateCategories, 'total' => $countUpdateData],
                            ['title' => 'Aktivasi NIK (Jenis)', 'icon' => 'fa-fingerprint', 'color' => 'blue', 'data' => $aktivasiCategories, 'total' => $countAktivasi],
                            ['title' => 'Pembubuhan (Dokumen)', 'icon' => 'fa-file-signature', 'color' => 'purple', 'data' => $tteCategories, 'total' => $countTte],
                            ['title' => 'Luar Daerah (Dokumen)', 'icon' => 'fa-map-marked-alt', 'color' => 'cyan', 'data' => $luarDaerahCategories, 'total' => $countLuarDaerah],
                            ['title' => 'Sistem Trouble (Kategori)', 'icon' => 'fa-bug', 'color' => 'rose', 'data' => $troubleCategories, 'total' => $countTrouble]
                        ];
                    @endphp

                    @foreach($catLists as $list)
                        <div
                            class="bg-white p-6 rounded-[2.2rem] shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
                            <h4
                                class="text-[10px] font-black text-slate-800 uppercase mb-5 flex items-center gap-2 border-b border-slate-50 pb-3">
                                <i class="fas {{ $list['icon'] }} text-{{ $list['color'] }}-500"></i>
                                {{ $list['title'] }}
                            </h4>

                            <div class="space-y-5">
                                @forelse($list['data'] as $name => $count)
                                    <div>
                                        <div
                                            class="flex justify-between text-[9px] font-bold mb-2 uppercase text-slate-600 tracking-tight">
                                            <span>{{ $name ?: 'Lainnya' }}</span>
                                            <span class="text-slate-900">{{ $count }}</span>
                                        </div>
                                        <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden">
                                            <div class="bg-{{ $list['color'] }}-500 h-full rounded-full transition-all duration-700"
                                                style="width: {{ ($count / max($list['total'], 1)) * 100 }}%"></div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="flex flex-col items-center py-4 opacity-40">
                                        <i class="fas fa-folder-open text-slate-300 mb-2"></i>
                                        <p class="text-[9px] text-slate-400 italic uppercase font-bold tracking-widest">Belum
                                            ada data</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    @endforeach
                </div>

                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        const ctxDonut = document.getElementById('categoryChart').getContext('2d');
                        new Chart(ctxDonut, {
                            type: 'doughnut',
                            data: {
                                labels: ['SIAK', 'NIK', 'Update', 'TTE', 'Proxy', 'Trouble', 'Luar Daerah'],
                                datasets: [{
                                    data: [
                        {{ $countSiak }},
                        {{ $countAktivasi }},
                        {{ $countUpdateData }},
                        {{ $countTte }},
                        {{ $countProxy }},
                        {{ $countTrouble }},
                                        {{ $countLuarDaerah }}
                                    ],
                                    backgroundColor: ['#f97316', '#3b82f6', '#f59e0b', '#8b5cf6', '#10b981', '#f43f5e', '#06b6d4'],
                                    borderWidth: 0,
                                    hoverOffset: 15
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                cutout: '75%',
                                plugins: {
                                    legend: {
                                        display: true,
                                        position: 'bottom',
                                        labels: {
                                            usePointStyle: true,
                                            padding: 12,
                                            font: { size: 9, weight: '700' },
                                            color: '#64748b'
                                        }
                                    }
                                }
                            }
                        });
                    });
                </script>
            </div>


            {{-- Fitur Kelola Akun --}}
            <div x-show="tab === 'tambah_akun'" x-data="{ 
    editModal: false, 
    editData: { id: '', name: '', email: '', location: '', role: '', pin: '' } 
}" x-transition x-cloak class="space-y-6 p-1 md:p-0">

                {{-- Notifikasi Error --}}
                @if ($errors->any())
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-4 rounded-xl shadow-sm">
                        <div class="flex items-center mb-1">
                            <i class="fas fa-exclamation-circle text-red-500 mr-2"></i>
                            <p class="text-red-700 font-black text-[10px] uppercase">Gagal Menyimpan Data</p>
                        </div>
                        <ul class="list-disc ml-5 text-red-600 text-[10px] font-bold">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Notifikasi Sukses --}}
                @if(session('success'))
                    <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-4 rounded-xl shadow-sm flex items-center">
                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                        <p class="text-green-700 font-black text-[10px] uppercase">{{ session('success') }}</p>
                    </div>
                @endif

                {{-- Form Registrasi --}}
                <div
                    class="bg-white p-5 md:p-8 rounded-[1.5rem] md:rounded-[2.5rem] shadow-sm border border-slate-200 max-w-2xl mx-auto md:mx-0">
                    <h2
                        class="text-lg md:text-xl font-black mb-6 uppercase tracking-tighter italic text-slate-800 flex items-center">
                        <i class="fas fa-user-plus mr-2 text-orange-500"></i> Registrasi Akun Baru
                    </h2>

                    <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase ml-2 block mb-1">Nama
                                    Lengkap</label>
                                <input type="text" name="name" value="{{ old('name') }}" required
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl md:rounded-2xl p-3 md:p-4 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-all outline-none"
                                    placeholder="Contoh: Budi Santoso">
                            </div>
                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase ml-2 block mb-1">NIK
                                    (16
                                    Digit)</label>
                                <input type="text" name="nik" value="{{ old('nik') }}" required maxlength="16"
                                    minlength="16"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl md:rounded-2xl p-3 md:p-4 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-all outline-none"
                                    placeholder="16 Digit NIK">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase ml-2 block mb-1">
                                    Lokasi Kecamatan
                                </label>
                                <div class="relative group">
                                    <input list="list_kecamatan" name="location" required value="{{ old('location') }}"
                                        placeholder="Pilih atau cari kecamatan..."
                                        class="w-full bg-slate-50 border border-slate-200 rounded-xl md:rounded-2xl p-3 md:p-4 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-all outline-none cursor-pointer"
                                        autocomplete="off">

                                    <datalist id="list_kecamatan" class="max-h-40 overflow-y-auto">
                                        @foreach($kecamatans as $kec)
                                            <option value="{{ strtoupper($kec->nama_kecamatan) }}">
                                        @endforeach
                                    </datalist>
                                </div>
                                <p class="text-[9px] text-slate-400 mt-1 ml-2 italic">
                                    *Ketik untuk mencari, atau klik pada kolom untuk melihat daftar.
                                </p>
                            </div>
                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase ml-2 block mb-1">PIN
                                    Keamanan</label>
                                <input type="text" name="pin" value="{{ old('pin') }}" required maxlength="10"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl md:rounded-2xl p-3 md:p-4 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-all outline-none"
                                    placeholder="Maks 10 karakter">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase ml-2 block mb-1">Hak
                                    Akses</label>
                                <select name="role" required
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl md:rounded-2xl p-3 md:p-4 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-all outline-none cursor-pointer">
                                    <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>👤 USER
                                    </option>
                                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>🛠️ ADMIN
                                    </option>
                                </select>
                            </div>
                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase ml-2 block mb-1">Kata
                                    Sandi
                                    (Opsional)</label>
                                <input type="password" name="password"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl md:rounded-2xl p-3 md:p-4 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-all outline-none"
                                    placeholder="Minimal 8 Karakter">
                            </div>
                        </div>
                        <p class="text-[9px] text-slate-400 mt-1 ml-2 italic font-medium">*Jika Kata Sandi kosong,
                            sistem akan
                            menggunakan NIK sebagai password default.</p>

                        <button type="submit"
                            class="w-full bg-slate-900 text-white font-black py-4 rounded-xl md:rounded-2xl shadow-xl hover:bg-orange-600 active:scale-95 transition-all uppercase tracking-widest text-xs">
                            <i class="fas fa-save mr-2"></i> Simpan Pengguna
                        </button>
                    </form>
                </div>

                {{-- Tabel Daftar Pengguna --}}
                <div
                    class="bg-white rounded-[1.5rem] md:rounded-[2.5rem] shadow-sm border border-slate-200 overflow-hidden">
                    <div
                        class="p-5 md:p-6 border-b border-slate-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 bg-slate-50/50">
                        <h3 class="font-black uppercase tracking-tighter text-slate-700 text-sm italic">Daftar
                            Pengguna
                            Sistem</h3>
                        <span
                            class="text-[10px] bg-orange-100 px-3 py-1 rounded-full font-bold text-orange-600 border border-orange-200">
                            Total: {{ $totalUser }} User
                        </span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse min-w-[600px] md:min-w-full">
                            <thead>
                                <tr
                                    class="text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 bg-slate-50/30">
                                    <th class="p-4 md:p-5">Pengguna</th>
                                    <th class="p-4 md:p-5">Akses & Lokasi</th>
                                    <th class="p-4 md:p-5">Status</th>
                                    <th class="p-4 md:p-5 text-center">Tindakan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @foreach($allUsers as $user)
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        <td class="p-4 md:p-5">
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="w-8 h-8 md:w-10 md:h-10 rounded-full bg-orange-100 flex items-center justify-center text-orange-600 font-bold text-[10px] md:text-xs uppercase shrink-0">
                                                    {{ substr($user->name, 0, 2) }}
                                                </div>
                                                <div class="min-w-0">
                                                    <p class="font-bold text-xs md:text-sm text-slate-700 truncate">
                                                        {{ $user->name }}
                                                    </p>
                                                    <p class="text-[9px] md:text-[10px] text-slate-400 font-medium italic">
                                                        PIN:
                                                        {{ $user->pin }} | NIK: {{ $user->nik }}
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-4 md:p-5">
                                            <span
                                                class="text-[9px] md:text-[10px] font-black {{ $user->role == 'admin' ? 'text-purple-600' : 'text-blue-600' }} uppercase italic">
                                                {{ $user->role == 'admin' ? 'ADMIN' : 'USER' }}
                                            </span>
                                            <p class="text-[10px] md:text-[11px] text-slate-500 font-bold truncate">
                                                {{ $user->location }}
                                            </p>
                                        </td>
                                        <td class="p-4 md:p-5">
                                            <form action="{{ route('admin.users.toggle', $user->id) }}" method="POST">
                                                @csrf
                                                <button type="submit"
                                                    class="flex items-center gap-2 px-2 md:px-3 py-1 rounded-full border transition-all hover:scale-105 {{ $user->is_active ? 'bg-green-50 border-green-200 text-green-600' : 'bg-red-50 border-red-200 text-red-600' }}">
                                                    <div
                                                        class="w-1.5 h-1.5 rounded-full {{ $user->is_active ? 'bg-green-500' : 'bg-red-500' }}">
                                                    </div>
                                                    <span
                                                        class="text-[9px] md:text-[10px] font-bold uppercase">{{ $user->is_active ? 'Aktif' : 'Off' }}</span>
                                                </button>
                                            </form>
                                        </td>
                                        <td class="p-4 md:p-5">
                                            <div class="flex items-center justify-center gap-2">
                                                <button
                                                    @click="editModal = true; editData = { id: '{{ $user->id }}', name: '{{ $user->name }}', email: '{{ $user->email }}', location: '{{ $user->location }}', role: '{{ $user->role }}', pin: '{{ $user->pin }}' }"
                                                    class="w-7 h-7 md:w-8 md:h-8 rounded-lg md:rounded-xl bg-slate-100 text-slate-500 hover:bg-orange-500 hover:text-white transition-all flex items-center justify-center text-[10px] md:text-xs">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                                                    onsubmit="return confirm('Hapus akun {{ $user->name }} secara permanen?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit"
                                                        class="w-7 h-7 md:w-8 md:h-8 rounded-lg md:rounded-xl bg-slate-100 text-red-400 hover:bg-red-500 hover:text-white transition-all flex items-center justify-center text-[10px] md:text-xs">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Modal Edit Pengguna --}}
                <div x-show="editModal"
                    class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
                    x-cloak x-transition>
                    <div @click.away="editModal = false"
                        class="bg-white w-full max-w-md rounded-[1.5rem] p-5 md:p-6 shadow-2xl border border-slate-200 max-h-[90vh] overflow-y-auto transform transition-all">

                        {{-- Header --}}
                        <div class="flex justify-between items-center mb-4 border-b border-slate-100 pb-3">
                            <h2 class="text-base md:text-lg font-black uppercase italic text-slate-800">Edit Pengguna
                            </h2>
                            <button @click="editModal = false" class="text-slate-400 hover:text-red-500">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>

                        {{-- Form --}}
                        <form :action="'/admin/users/' + editData.id + '/update'" method="POST" class="space-y-2.5">
                            @csrf
                            @method('PUT')

                            {{-- Input Nama --}}
                            <div>
                                <label class="text-[9px] font-black text-slate-400 uppercase block mb-0.5 ml-1">Nama
                                    Lengkap</label>
                                <input type="text" name="name" x-model="editData.name" required
                                    class="w-full bg-slate-50 border border-slate-200 rounded-lg py-2 px-3 text-sm font-bold text-slate-700 outline-none focus:border-orange-500 transition-all">
                            </div>

                            {{-- Input PIN --}}
                            <div>
                                <label class="text-[9px] font-black text-slate-400 uppercase block mb-0.5 ml-1">PIN
                                    Keamanan</label>
                                <input type="text" name="pin" x-model="editData.pin" required maxlength="10"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-lg py-2 px-3 text-sm font-bold text-slate-700 outline-none focus:border-orange-500 transition-all"
                                    placeholder="PIN 6-10 Digit">
                            </div>

                            {{-- Grid untuk Dropdown (Kecamatan & Role) --}}
                            <div class="grid grid-cols-2 gap-3">
                                {{-- Dropdown Kecamatan dengan Scroll --}}
                                <div class="relative">
                                    <label
                                        class="text-[9px] font-black text-slate-400 uppercase block mb-0.5 ml-1">Kecamatan</label>
                                    <div class="relative" x-data="{ open: false }">
                                        <button type="button" @click="open = !open"
                                            class="w-full bg-slate-50 border border-slate-200 rounded-lg py-1.5 px-2 text-xs font-bold text-slate-700 flex justify-between items-center focus:border-orange-500 outline-none">
                                            <span x-text="editData.location || 'Pilih'"></span>
                                            <i class="fas fa-chevron-down text-[8px] transition-transform"
                                                :class="open ? 'rotate-180' : ''"></i>
                                        </button>

                                        {{-- Hidden Input untuk Form --}}
                                        <input type="hidden" name="location" x-model="editData.location">

                                        {{-- Dropdown Menu (Dipotong Setengah & Scroll) --}}
                                        <div x-show="open" @click.away="open = false"
                                            class="absolute z-[110] mt-1 w-full bg-white border border-slate-200 rounded-lg shadow-xl max-h-32 overflow-y-auto scrollbar-thin scrollbar-thumb-slate-300">
                                            @foreach($kecamatans as $kec)
                                                <div @click="editData.location = '{{ $kec->nama_kecamatan }}'; open = false"
                                                    class="px-3 py-2 text-[11px] font-bold text-slate-600 hover:bg-orange-50 hover:text-orange-600 cursor-pointer border-b border-slate-50 last:border-0 uppercase">
                                                    {{ $kec->nama_kecamatan }}
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label class="text-[9px] font-black text-slate-400 uppercase block mb-0.5 ml-1">Hak
                                        Akses</label>
                                    <select name="role" x-model="editData.role"
                                        class="w-full bg-slate-50 border border-slate-200 rounded-lg py-1.5 px-2 text-xs font-bold text-slate-700 outline-none focus:border-orange-500 transition-all cursor-pointer">
                                        <option value="user">USER</option>
                                        <option value="admin">ADMIN</option>
                                    </select>
                                </div>
                            </div>

                            {{-- Input Password --}}
                            <div>
                                <label class="text-[9px] font-black text-slate-400 uppercase block mb-0.5 ml-1">Password
                                    Baru (Opsional)</label>
                                <input type="password" name="password"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-lg py-2 px-3 text-sm font-bold text-slate-700 outline-none focus:border-orange-500 transition-all"
                                    placeholder="Minimal 6 karakter">
                            </div>

                            {{-- Action Buttons --}}
                            <div class="flex gap-2 pt-3">
                                <button type="button" @click="editModal = false"
                                    class="flex-1 bg-slate-100 text-slate-500 font-black py-2.5 rounded-xl uppercase text-[10px] transition-all hover:bg-slate-200">
                                    Batal
                                </button>
                                <button type="submit"
                                    class="flex-1 bg-slate-900 text-white font-black py-2.5 rounded-xl shadow-lg uppercase text-[10px] hover:bg-orange-600 transition-all transform active:scale-95">
                                    Update Data
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>


            {{-- TAB LAPORAN AKTIVASI & LUAR DAERAH --}}
            @php
                // 1. Penggabungan Data Aktivasi & Luar Daerah
                $combined = collect();

                if (isset($aktivasis)) {
                    foreach ($aktivasis as $item) {
                        $item->row_type = 'AKTIVASI';
                        $item->display_category = strtoupper($item->jenis_layanan ?? 'AKTIVASI NIK');
                        $combined->push($item);
                    }
                }

                if (isset($luarDaerahs)) {
                    foreach ($luarDaerahs as $item) {
                        $item->row_type = 'LUARDAERAH';
                        $item->display_category = strtoupper($item->jenis_dokumen ?? 'LUAR DAERAH');
                        $combined->push($item);
                    }
                }

                // 2. Sorting Awal (FIFO & Status)
                $sortedItems = $combined->sort(function ($a, $b) {
                    $statusA = ($a->is_rejected) ? 'ditolak' : (!empty($a->tanggapan_admin) ? 'selesai' : 'pending');
                    $statusB = ($b->is_rejected) ? 'ditolak' : (!empty($b->tanggapan_admin) ? 'selesai' : 'pending');
                    $priorityA = ($statusA === 'pending') ? 0 : 1;
                    $priorityB = ($statusB === 'pending') ? 0 : 1;

                    if ($priorityA !== $priorityB)
                        return $priorityA <=> $priorityB;

                    return \Carbon\Carbon::parse($a->created_at)->timezone('Asia/Jakarta')->timestamp <=>
                        \Carbon\Carbon::parse($b->created_at)->timezone('Asia/Jakarta')->timestamp;
                })->values();

                // Mapping data ke array sederhana untuk JavaScript
                $jsData = $sortedItems->map(function ($a) {
                    $dt = \Carbon\Carbon::parse($a->created_at)->timezone('Asia/Jakarta');

                    // PERBAIKAN: Menangani Foto KTP yang sekarang berbentuk JSON/Array
                    $fotoList = [];
                    if ($a->foto_ktp) {
                        // Decode jika string JSON, atau gunakan langsung jika sudah array (karena $casts)
                        $decoded = is_array($a->foto_ktp) ? $a->foto_ktp : json_decode($a->foto_ktp, true);
                        if (is_array($decoded)) {
                            foreach ($decoded as $path) {
                                $fotoList[] = asset('storage/' . $path);
                            }
                        } else {
                            // Fallback jika isinya masih string path tunggal (data lama)
                            $fotoList[] = asset('storage/' . $a->foto_ktp);
                        }
                    }

                    return [
                        'id' => $a->id,
                        'name' => strtoupper($a->user->name ?? 'User Tidak Dikenal'),
                        'location' => strtoupper($a->user->location ?? 'LUAR WILAYAH'),
                        'date' => $dt->format('Y-m-d'),
                        'month' => $dt->format('m'),
                        'time' => $dt->format('H:i'),
                        'date_human' => $dt->translatedFormat('d M Y'),
                        'status' => ($a->is_rejected) ? 'ditolak' : (!empty($a->tanggapan_admin) ? 'selesai' : 'pending'),
                        'kat_row' => $a->row_type,
                        'display_kat' => $a->display_category,
                        'is_restore' => (bool) ($a->is_restore ?? false),
                        // Simpan sebagai array agar bisa di-loop di Alpine
                        'foto_ktp' => $fotoList,
                        'alasan' => $a->alasan ?? 'Tidak ada alasan',
                        'tanggapan' => $a->tanggapan_admin ?? '',
                        'url_respon' => route('admin.laporan.respon'),
                        'url_tolak' => route('admin.laporan.tolak', $a->id),
                        'url_hapus' => route('admin.laporan.hapus', $a->id),
                        'csrf' => csrf_token()
                    ];
                });

                $listKecamatan = $jsData->pluck('location')->unique()->sort()->values();
            @endphp

            {{-- Fitur Laporan Aktivasi --}}
            <div x-show="tab === 'laporan_aktivasi'" x-data="{ 
        allData: [...{{ json_encode($jsData) }}],
        listKecamatan: {{ json_encode($listKecamatan) }},
        filterMonth: '{{ now()->timezone('Asia/Jakarta')->format('m') }}', 
        filterKecamatan: '',
        filterStatus: '',
        filterKategori: '',
        currentPage: 1,
        perPage: 10,
        showModal: false,
        modalImage: '',
        modalType: '',

        exportToExcel() {
            const baseUrl = '{{ route('export.laporan.aktivasi') }}';
            const params = new URLSearchParams({
                month: this.filterMonth,
                kecamatan: this.filterKecamatan,
                status: this.filterStatus,
                kategori: this.filterKategori
            });
            window.location.href = `${baseUrl}?${params.toString()}`;
        },

        get filteredData() {
            let filtered = this.allData.filter(row => {
                const kecMatch = this.filterKecamatan === '' || row.location === this.filterKecamatan;
                const monthMatch = this.filterMonth === '' || row.month === this.filterMonth;
                const statusMatch = this.filterStatus === '' || row.status === this.filterStatus;
                const kategoriMatch = this.filterKategori === '' || row.kat_row === this.filterKategori;
                return kecMatch && monthMatch && statusMatch && kategoriMatch;
            });

            return filtered.sort((a, b) => {
                const statusOrder = { 'pending': 0, 'selesai': 1, 'ditolak': 2 };
                const orderA = statusOrder[a.status] ?? 99;
                const orderB = statusOrder[b.status] ?? 99;
                if (orderA !== orderB) return orderA - orderB;
                const dateA = new Date((a.date || '1970-01-01') + ' ' + (a.time || '00:00:00'));
                const dateB = new Date((b.date || '1970-01-01') + ' ' + (b.time || '00:00:00'));
                return dateA - dateB;
            });
        },

        get pagedData() {
            let start = (this.currentPage - 1) * this.perPage;
            let end = start + this.perPage;
            return this.filteredData.slice(start, end);
        },

        get totalPages() {
            return Math.max(1, Math.ceil(this.filteredData.length / this.perPage));
        }
     }" x-transition x-cloak class="w-full px-4 md:px-0">

                {{-- MODAL PREVIEW --}}
                <div x-show="showModal"
                    class="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-slate-900/95 backdrop-blur-md"
                    x-transition @click.away="showModal = false" style="display: none;">
                    <div class="relative max-w-5xl w-full" @click.stop>
                        <div class="absolute -top-12 left-0 right-0 flex justify-between items-center px-2">
                            <span
                                class="bg-blue-600 text-white text-[10px] md:text-xs font-black px-4 py-2 rounded-lg uppercase tracking-widest shadow-lg"
                                x-text="modalType"></span>
                            <button @click="showModal = false"
                                class="bg-white/10 hover:bg-red-500 text-white w-10 h-10 rounded-full flex items-center justify-center transition-all">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <div
                            class="bg-white p-2 rounded-[1.5rem] md:rounded-[2rem] shadow-2xl overflow-hidden text-center">
                            <img :src="modalImage"
                                class="w-full h-auto max-h-[70vh] md:max-h-[75vh] object-contain rounded-[1rem] md:rounded-[1.5rem]">
                            <div class="mt-4 pb-2">
                                <a :href="modalImage" download target="_blank"
                                    class="inline-block bg-slate-800 text-white px-6 py-2 rounded-full text-[10px] md:text-xs font-bold uppercase tracking-widest hover:bg-blue-600 transition-all">
                                    <i class="fas fa-download mr-2"></i> Buka Ukuran Asli
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- HEADER TITLE --}}
                <div class="mb-6 md:mb-8">
                    <h2 class="text-xl md:text-2xl font-black text-slate-800 tracking-tighter uppercase italic">
                        Monitoring Kendala Sistem</h2>
                    <p class="text-[9px] md:text-[11px] text-slate-500 font-bold uppercase tracking-widest mt-1">Urutan:
                        Prioritas Antrean Terlama (Pending) Di Atas</p>
                </div>

                {{-- FILTER BAR --}}
                <div class="flex flex-col md:flex-row flex-wrap items-stretch md:items-center gap-3 md:gap-4 mb-6">
                    <div class="grid grid-cols-2 md:contents gap-3">
                        <div class="flex-1">
                            <select x-model="filterMonth" @change="currentPage = 1"
                                class="w-full bg-white border border-slate-200 rounded-xl md:rounded-2xl px-4 md:px-6 py-3 md:py-4 text-[10px] md:text-xs font-black uppercase text-slate-700 shadow-sm outline-none transition-all">
                                <option value="">Semua Bulan</option>
                                <option value="01">Januari</option>
                                <option value="02">Februari</option>
                                <option value="03">Maret</option>
                                <option value="04">April</option>
                                <option value="05">Mei</option>
                                <option value="06">Juni</option>
                                <option value="07">Juli</option>
                                <option value="08">Agustus</option>
                                <option value="09">September</option>
                                <option value="10">Oktober</option>
                                <option value="11">November</option>
                                <option value="12">Desember</option>
                            </select>
                        </div>
                        <div class="flex-1">
                            <select x-model="filterKecamatan" @change="currentPage = 1"
                                class="w-full bg-white border border-slate-200 rounded-xl md:rounded-2xl px-4 md:px-6 py-3 md:py-4 text-[10px] md:text-xs font-black uppercase text-slate-700 shadow-sm outline-none transition-all">
                                <option value="">Semua Wilayah</option>
                                <template x-for="kec in listKecamatan" :key="kec">
                                    <option :value="kec" x-text="kec"></option>
                                </template>
                            </select>
                        </div>
                        <div class="flex-1">
                            <select x-model="filterKategori" @change="currentPage = 1"
                                class="w-full bg-white border border-slate-200 rounded-xl md:rounded-2xl px-4 md:px-6 py-3 md:py-4 text-[10px] md:text-xs font-black uppercase text-slate-700 shadow-sm outline-none transition-all">
                                <option value="">Semua Kategori</option>
                                <option value="AKTIVASI">AKTIVASI NIK/AKTE</option>
                                <option value="LUARDAERAH">LUAR DAERAH</option>
                            </select>
                        </div>
                        <div class="flex-1">
                            <select x-model="filterStatus" @change="currentPage = 1"
                                class="w-full bg-white border border-slate-200 rounded-xl md:rounded-2xl px-4 md:px-6 py-3 md:py-4 text-[10px] md:text-xs font-black uppercase text-slate-700 shadow-sm outline-none transition-all">
                                <option value="">Semua Status</option>
                                <option value="pending">⏳ Pending</option>
                                <option value="selesai">✅ Selesai</option>
                                <option value="ditolak">❌ Ditolak</option>
                            </select>
                        </div>
                    </div>

                    <button @click="exportToExcel()"
                        class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-4 rounded-xl md:rounded-2xl text-[10px] md:text-xs font-black uppercase flex items-center justify-center gap-2 shadow-lg shadow-emerald-200 transition-all active:scale-95">
                        <i class="fas fa-file-excel text-sm"></i> Ekspor Excel
                    </button>
                </div>

                {{-- TABLE CONTAINER --}}
                <div class="bg-white rounded-2xl md:rounded-[2.5rem] shadow-xl border border-slate-100 overflow-hidden">
                    {{-- PAGINATION TOP --}}
                    <div
                        class="px-4 md:px-8 py-4 md:py-5 bg-slate-50/50 border-b border-slate-100 flex flex-col md:flex-row justify-between items-center gap-4">
                        <div class="text-[9px] md:text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">
                            Halaman <span x-text="currentPage"></span> Dari <span x-text="totalPages"></span>
                        </div>
                        <div class="flex flex-col md:flex-row items-center gap-4 md:gap-6 w-full md:w-auto">
                            <div class="text-[10px] md:text-[11px] font-bold text-slate-500">
                                Showing <span
                                    x-text="filteredData.length > 0 ? ((currentPage - 1) * perPage) + 1 : 0"></span> to
                                <span x-text="Math.min(currentPage * perPage, filteredData.length)"></span> of <span
                                    x-text="filteredData.length"></span> results
                            </div>
                            <div class="flex border border-slate-200 rounded-xl overflow-hidden bg-white shadow-sm">
                                <button
                                    @click="if(currentPage > 1) { currentPage--; window.scrollTo({top: 0, behavior: 'smooth'}); }"
                                    :disabled="currentPage === 1"
                                    class="px-3 md:px-4 py-2 hover:bg-slate-50 disabled:opacity-30 border-r border-slate-200 transition-all">
                                    <i class="fas fa-chevron-left text-[10px] text-slate-600"></i>
                                </button>
                                <template x-for="p in totalPages" :key="p">
                                    <button @click="currentPage = p; window.scrollTo({top: 0, behavior: 'smooth'});"
                                        :class="currentPage === p ? 'bg-slate-200 text-slate-800' : 'text-slate-500 hover:bg-slate-50'"
                                        class="px-3 md:px-5 py-2 text-[10px] md:text-[11px] font-black border-r border-slate-200 transition-all"
                                        x-text="p"></button>
                                </template>
                                <button
                                    @click="if(currentPage < totalPages) { currentPage++; window.scrollTo({top: 0, behavior: 'smooth'}); }"
                                    :disabled="currentPage === totalPages || filteredData.length === 0"
                                    class="px-3 md:px-4 py-2 hover:bg-slate-50 disabled:opacity-30 transition-all">
                                    <i class="fas fa-chevron-right text-[10px] text-slate-600"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="overflow-x-auto w-full">
                        <table class="w-full text-left border-collapse table-fixed min-w-[1000px]">
                            <thead
                                class="text-slate-400 text-[10px] uppercase font-black tracking-widest border-b border-slate-100 bg-slate-50/30">
                                <tr>
                                    <th class="p-4 md:p-8 w-1/4">Pengguna & Wilayah</th>
                                    <th class="p-4 md:p-8 w-44">Layanan & Jenis</th>
                                    <th class="p-4 md:p-8 w-36">Bukti Foto</th>
                                    <th class="p-4 md:p-8 w-1/3">Detail Kendala</th>
                                    <th class="p-4 md:p-8 w-1/4 text-orange-600">Respon Admin</th>
                                    <th class="p-4 md:p-8 w-36 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm font-bold text-slate-700 divide-y divide-slate-50">
                                <template x-for="(row, index) in pagedData" :key="row.kat_row + '-' + row.id">
                                    <tr class="hover:bg-slate-50/50 transition-all"
                                        :class="row.status !== 'pending' ? 'opacity-60 bg-slate-50/30' : ''">
                                        <td class="p-4 md:p-8">
                                            <div class="flex flex-col gap-1.5">
                                                <div class="text-slate-900 font-black text-[13px] md:text-[15px] leading-tight"
                                                    x-text="row.name"></div>
                                                <div
                                                    class="text-[9px] md:text-[10px] text-blue-600 uppercase font-black tracking-widest flex items-center">
                                                    <i class="fas fa-map-marker-alt mr-2 opacity-50"></i>
                                                    <span x-text="row.location"></span>
                                                </div>
                                                <div
                                                    class="mt-2 text-[8px] md:text-[9px] font-black flex flex-wrap gap-2">
                                                    <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-500"
                                                        x-text="row.kat_row"></span>
                                                    <template x-if="row.status === 'selesai'">
                                                        <span
                                                            class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 border border-emerald-200">SELESAI</span>
                                                    </template>
                                                    <template x-if="row.status === 'ditolak'">
                                                        <span
                                                            class="px-3 py-1 rounded-full bg-red-100 text-red-700 border border-red-200">DITOLAK</span>
                                                    </template>
                                                    <template x-if="row.status === 'pending'">
                                                        <span
                                                            class="px-3 py-1 rounded-full bg-orange-100 text-orange-700 border border-orange-200 animate-pulse">MENUNGGU</span>
                                                    </template>
                                                </div>
                                            </div>
                                        </td>

                                        <td class="p-4 md:p-8">
                                            <span
                                                class="inline-block px-3 md:px-4 py-2 rounded-xl md:rounded-2xl text-[9px] md:text-[10px] font-black uppercase tracking-widest border-2"
                                                :class="row.kat_row === 'AKTIVASI' ? 'border-blue-100 bg-blue-50 text-blue-700' : 'border-indigo-100 bg-indigo-50 text-indigo-700'"
                                                x-text="row.display_kat">
                                            </span>
                                        </td>

                                        <td class="p-4 md:p-8">
                                            <div class="grid grid-cols-2 gap-2">
                                                <template x-if="row.foto_ktp && row.foto_ktp.length > 0">
                                                    <template x-for="(img, idx) in row.foto_ktp" :key="idx">
                                                        <div class="relative group w-10 h-10 md:w-12 md:h-12 overflow-hidden rounded-xl border-2 border-slate-200 cursor-pointer shadow-sm hover:border-blue-500 transition-all"
                                                            @click="modalImage = img; modalType = row.display_kat; showModal = true">
                                                            <img :src="img" class="w-full h-full object-cover">
                                                            <div
                                                                class="absolute inset-0 bg-blue-600/20 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                                                <i
                                                                    class="fas fa-search-plus text-white text-[10px]"></i>
                                                            </div>
                                                        </div>
                                                    </template>
                                                </template>
                                                <template x-if="!row.foto_ktp || row.foto_ktp.length === 0">
                                                    <span
                                                        class="text-slate-300 text-[9px] md:text-[10px] font-black tracking-widest italic uppercase">KOSONG</span>
                                                </template>
                                            </div>
                                        </td>

                                        <td class="p-4 md:p-8">
                                            <div
                                                class="text-[12px] md:text-[13px] text-slate-700 font-bold mb-3 leading-relaxed bg-slate-50 border border-slate-200 p-4 md:p-5 rounded-xl md:rounded-2xl shadow-inner min-h-[60px] break-words">
                                                <i class="fas fa-quote-left text-blue-200 mb-1 block"></i>
                                                <span x-text="row.alasan || '-'" class="whitespace-pre-line"></span>
                                            </div>
                                            <div
                                                class="text-[9px] md:text-[10px] text-slate-400 font-black uppercase flex items-center tracking-widest">
                                                <i class="far fa-clock mr-2 text-blue-500"></i>
                                                <span x-text="row.date_human"></span> • <span x-text="row.time"></span>
                                                <span class="ml-1 text-[8px] md:text-[8px] opacity-70">(WIB)</span>
                                            </div>
                                        </td>

                                        <td class="p-4 md:p-8">
                                            <template x-if="row.status === 'ditolak'">
                                                <div class="flex flex-col gap-2">
                                                    <div
                                                        class="bg-red-50 border border-red-100 rounded-xl md:rounded-2xl p-4">
                                                        <p class="text-[10px] md:text-[11px] text-red-800 font-black leading-snug"
                                                            x-text="row.tanggapan"></p>
                                                    </div>
                                                    <button @click="$el.nextElementSibling.classList.toggle('hidden')"
                                                        class="text-left text-[9px] text-red-600 font-black underline uppercase tracking-widest">Edit
                                                        Alasan</button>
                                                    <div
                                                        class="hidden mt-1 p-4 bg-white rounded-xl shadow-xl border border-slate-200">
                                                        <form :action="row.url_respon" method="POST">
                                                            <input type="hidden" name="_token" :value="row.csrf">
                                                            <input type="hidden" name="laporan_id" :value="row.id">
                                                            <input type="hidden" name="type"
                                                                :value="row.kat_row.toLowerCase()">
                                                            <textarea name="admin_note" rows="2"
                                                                class="w-full bg-slate-50 border border-slate-200 rounded-lg py-2 px-3 text-[11px] font-bold outline-none resize-none"
                                                                x-text="row.tanggapan"></textarea>
                                                            <button type="submit"
                                                                class="w-full mt-2 bg-red-600 text-white py-2 rounded-lg text-[9px] font-black uppercase">Simpan</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </template>
                                            <template x-if="row.status !== 'ditolak'">
                                                <form :action="row.url_respon" method="POST"
                                                    class="flex flex-col gap-2">
                                                    <input type="hidden" name="_token" :value="row.csrf">
                                                    <input type="hidden" name="laporan_id" :value="row.id">
                                                    <input type="hidden" name="type" :value="row.kat_row.toLowerCase()">
                                                    <textarea name="admin_note" rows="2"
                                                        placeholder="Tulis catatan verifikasi..."
                                                        class="w-full bg-white border border-slate-200 rounded-xl py-3 px-4 text-[10px] md:text-[11px] font-bold focus:ring-2 focus:ring-orange-500 outline-none resize-none transition-all shadow-sm"
                                                        required x-text="row.tanggapan"></textarea>
                                                    <button type="submit"
                                                        class="w-full text-white py-3 rounded-xl md:rounded-2xl text-[9px] md:text-[10px] font-black uppercase shadow-lg transition-all"
                                                        :class="row.tanggapan ? 'bg-slate-800 hover:bg-black' : 'bg-orange-500 hover:bg-orange-600'">
                                                        <span
                                                            x-text="row.tanggapan ? 'Update Tanggapan' : 'Kirim Verifikasi'"></span>
                                                    </button>
                                                </form>
                                            </template>
                                        </td>

                                        <td class="p-4 md:p-8 text-right">
                                            <div class="flex flex-col items-center justify-center gap-2 md:gap-3">
                                                <template x-if="row.status === 'pending'">
                                                    <form :action="row.url_tolak" method="POST"
                                                        onsubmit="return confirm('Apakah Anda yakin ingin MENOLAK?')"
                                                        class="w-full">
                                                        <input type="hidden" name="_token" :value="row.csrf">
                                                        <input type="hidden" name="type"
                                                            :value="row.kat_row.toLowerCase()">
                                                        <input type="hidden" name="admin_note"
                                                            value="Data NIK tidak sinkron atau dokumen pendukung tidak jelas.">
                                                        <button type="submit"
                                                            class="group flex items-center justify-center w-full bg-red-50 hover:bg-red-600 text-red-600 hover:text-white px-3 py-2 rounded-xl border border-red-200 transition-all shadow-sm">
                                                            <i class="fas fa-times-circle mr-2 text-[10px]"></i>
                                                            <span
                                                                class="text-[9px] md:text-[10px] font-black uppercase tracking-wider">Tolak</span>
                                                        </button>
                                                    </form>
                                                </template>

                                                <form :action="row.url_hapus" method="POST"
                                                    onsubmit="return confirm('PERINGATAN: Hapus data secara permanen?')"
                                                    class="w-full">
                                                    <input type="hidden" name="_token" :value="row.csrf">
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <input type="hidden" name="type" :value="row.kat_row.toLowerCase()">
                                                    <button type="submit"
                                                        class="group flex items-center justify-center w-full bg-slate-50 hover:bg-slate-900 text-slate-500 hover:text-white px-3 py-2 rounded-xl border border-slate-200 transition-all">
                                                        <i class="fas fa-trash-alt mr-2 text-[10px]"></i>
                                                        <span
                                                            class="text-[9px] md:text-[10px] font-black uppercase tracking-wider">Hapus</span>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                </template>

                                <tr x-show="filteredData.length === 0">
                                    <td colspan="6" class="p-20 md:p-32 text-center">
                                        <div class="flex flex-col items-center justify-center opacity-20">
                                            <i class="fas fa-folder-open text-5xl md:text-7xl mb-6"></i>
                                            <span class="text-xl md:text-2xl font-black uppercase tracking-[0.4em]">Data
                                                Kosong</span>
                                            <p class="mt-2 font-bold text-slate-500 uppercase text-[10px] md:text-xs">
                                                Tidak ada data yang sesuai filter</p>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


            {{-- FITUR LAPORAN KENDALA / SISTEM --}}
            <div x-show="tab === 'laporan_sistem'" x-data="{ 
    filterType: '{{ request('type') }}', 
    filterMonth: '{{ request('month', now()->format('m')) }}',
    filterStatus: '{{ request('status') }}',
    filterKecamatan: '{{ request('kecamatan') }}',
    imgModal: false,
    imgModalSrc: '',
    imgModalKategori: '',
    isLoading: false,

    async updateContent(url) {
        this.isLoading = true;
        try {
            const currentUrl = new URL(url);
            if (this.filterStatus) currentUrl.searchParams.set('status', this.filterStatus);
            else currentUrl.searchParams.delete('status');
            
            if (this.filterType) currentUrl.searchParams.set('type', this.filterType);
            else currentUrl.searchParams.delete('type');
            
            if (this.filterKecamatan) currentUrl.searchParams.set('kecamatan', this.filterKecamatan);
            else currentUrl.searchParams.delete('kecamatan');
            
            if (this.filterMonth) currentUrl.searchParams.set('month', this.filterMonth);
            else currentUrl.searchParams.delete('month');
            
            currentUrl.searchParams.set('tab', 'laporan_sistem');

            const response = await fetch(currentUrl.href);
            const text = await response.text();
            const parser = new DOMParser();
            const html = parser.parseFromString(text, 'text/html');
            const newTable = html.querySelector('#ajax-laporan-wrapper').innerHTML;
            
            document.querySelector('#ajax-laporan-wrapper').innerHTML = newTable;
            
            window.history.pushState({}, '', currentUrl.href);
        } catch (e) {
            console.error('Gagal memuat data:', e);
        }
        this.isLoading = false;
    },

    init() {
        this.$watch('filterStatus', () => this.triggerUpdate());
        this.$watch('filterType', () => this.triggerUpdate());
        this.$watch('filterMonth', () => this.triggerUpdate());
        this.$watch('filterKecamatan', () => this.triggerUpdate());

        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('page') && urlParams.get('tab') === 'laporan_sistem') {
            this.$nextTick(() => {
                this.$el.scrollIntoView({ behavior: 'smooth', block: 'start' });
            });
        }

        document.addEventListener('click', (e) => {
            const link = e.target.closest('.pagination-wrapper a');
            if (link && document.querySelector('#ajax-laporan-wrapper').contains(link)) {
                e.preventDefault();
                this.updateContent(link.href);
            }
        });
    },

    triggerUpdate() {
        const baseUrl = window.location.protocol + '//' + window.location.host + window.location.pathname;
        this.updateContent(baseUrl);
    },

    shouldShow(type, date, status, kecamatanName) {
        const monthMatch = this.filterMonth === '' || date.split('-')[1] === this.filterMonth;
        const typeMatch = this.filterType === '' || type === this.filterType;
        const statusMatch = this.filterStatus === '' || status === this.filterStatus;
        const kecMatch = this.filterKecamatan === '' || kecamatanName === this.filterKecamatan;
        
        return monthMatch && typeMatch && statusMatch && kecMatch;
    }
}" x-transition x-cloak>

                {{-- WRAPPER ID UNTUK AJAX --}}
                <div id="ajax-laporan-wrapper"
                    class="relative bg-white rounded-2xl md:rounded-[2.5rem] shadow-md border border-slate-200 overflow-hidden">

                    {{-- OVERLAY LOADING --}}
                    <div x-show="isLoading"
                        class="absolute inset-0 z-50 bg-white/60 backdrop-blur-[2px] flex items-center justify-center">
                        <div class="flex flex-col items-center">
                            <div
                                class="w-12 h-12 border-4 border-slate-200 border-t-orange-500 rounded-full animate-spin">
                            </div>
                            <p class="mt-4 text-[10px] font-black text-slate-500 uppercase tracking-widest">Memproses
                                Data...</p>
                        </div>
                    </div>

                    @php
                        \Carbon\Carbon::setLocale('id');

                        $laporanSistem = $sortedLaporans->whereIn('type', ['trouble', 'proxy', 'pembubuhan', 'pengajuan'])
                            ->filter(function ($item) {
                                $wilayah = strtoupper($item->wilayah ?? '');
                                return !str_contains($wilayah, 'LUAR');
                            });

                        if (request('status')) {
                            $laporanSistem = $laporanSistem->filter(function ($item) {
                                $currStatus = $item->is_rejected ? 'ditolak' : (!empty($item->tanggapan_admin) ? 'selesai' : 'pending');
                                return $currStatus == request('status');
                            });
                        }

                        if (request('type')) {
                            $laporanSistem = $laporanSistem->where('type', request('type'));
                        }

                        if (request('kecamatan')) {
                            $laporanSistem = $laporanSistem->filter(function ($item) {
                                return strtoupper($item->wilayah) == strtoupper(request('kecamatan'));
                            });
                        }

                        if (request('month')) {
                            $laporanSistem = $laporanSistem->filter(function ($item) {
                                return \Carbon\Carbon::parse($item->created_at)->format('m') == request('month');
                            });
                        }

                        $sortedLaporanSistem = $laporanSistem->sort(function ($a, $b) {
                            $statusA = !empty($a->tanggapan_admin) || $a->is_rejected ? 1 : 0;
                            $statusB = !empty($b->tanggapan_admin) || $b->is_rejected ? 1 : 0;

                            if ($statusA === $statusB) {
                                return strtotime($a->created_at) - strtotime($b->created_at);
                            }
                            return $statusA - $statusB;
                        });

                        $currentPage = request()->get('page', 1);
                        $perPage = 10;
                        $displayLaporans = new \Illuminate\Pagination\LengthAwarePaginator(
                            $sortedLaporanSistem->forPage($currentPage, $perPage),
                            $sortedLaporanSistem->count(),
                            $perPage,
                            $currentPage,
                            [
                                'path' => request()->url(),
                                'query' => array_merge(request()->query(), ['tab' => 'laporan_sistem'])
                            ]
                        );
                    @endphp

                    {{-- HEADER --}}
                    <div class="p-4 md:p-6 border-b border-slate-100 bg-slate-50/50">
                        <div class="flex flex-col gap-4 md:gap-6">
                            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-2">
                                <div>
                                    <h3
                                        class="font-black text-slate-800 uppercase text-base md:text-lg italic tracking-tight">
                                        Monitoring Kendala Sistem</h3>
                                    <p
                                        class="text-[9px] md:text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">
                                        Urutan: Prioritas Terlama & Belum Diproses</p>
                                </div>
                            </div>

                            {{-- Baris Filter --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:flex lg:flex-wrap items-center gap-3 w-full">
                                <div class="relative w-full lg:flex-1 lg:min-w-[140px]">
                                    <select x-model="filterMonth"
                                        class="w-full bg-white border-2 border-slate-200 rounded-xl px-3 py-2.5 text-[10px] font-black uppercase text-slate-600 focus:border-orange-500 appearance-none cursor-pointer shadow-sm">
                                        <option value="">Semua Bulan</option>
                                        @foreach(['01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'] as $val => $label)
                                            <option value="{{ $val }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="relative w-full lg:flex-1 lg:min-w-[150px]">
                                    <select x-model="filterKecamatan"
                                        class="w-full bg-white border-2 border-slate-200 rounded-xl px-3 py-2.5 text-[10px] font-black uppercase text-slate-600 focus:border-orange-500 appearance-none cursor-pointer shadow-sm">
                                        <option value="">Semua Wilayah</option>
                                        @foreach($kecamatans as $kec)
                                            <option value="{{ strtoupper($kec->nama_kecamatan) }}">
                                                {{ strtoupper($kec->nama_kecamatan) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="relative w-full lg:flex-1 lg:min-w-[150px]">
                                    <select x-model="filterType"
                                        class="w-full bg-white border-2 border-slate-200 rounded-xl px-3 py-2.5 text-[10px] font-black uppercase text-slate-600 focus:border-orange-500 appearance-none cursor-pointer shadow-sm">
                                        <option value="">Semua Kategori</option>
                                        <option value="trouble">🔴 Trouble (Sistem)</option>
                                        <option value="pengajuan">🟢 Kendala SIAK</option>
                                        <option value="pembubuhan">🔵 Pembubuhan TTE</option>
                                        <option value="proxy">🟣 Masalah Proxy</option>
                                    </select>
                                </div>

                                <div class="relative w-full lg:flex-1 lg:min-w-[130px]">
                                    <select x-model="filterStatus"
                                        class="w-full bg-white border-2 border-slate-200 rounded-xl px-3 py-2.5 text-[10px] font-black uppercase text-slate-600 focus:border-orange-500 appearance-none cursor-pointer shadow-sm">
                                        <option value="">Semua Status</option>
                                        <option value="pending">⏳ Menunggu</option>
                                        <option value="selesai">✅ Selesai</option>
                                        <option value="ditolak">❌ Ditolak</option>
                                    </select>
                                </div>

                                <a :href="'{{ route('admin.laporan.export') }}?category=sistem_terpadu' + 
                        '&export_time={{ now()->timezone('Asia/Jakarta')->format('Y-m-d_H-i-s') }}' +
                        (filterType ? '&type=' + filterType : '') + 
                        (filterMonth ? '&month=' + filterMonth : '') + 
                        (filterStatus ? '&status=' + filterStatus : '') + 
                        (filterKecamatan ? '&kecamatan=' + filterKecamatan : '')"
                                    class="w-full lg:w-auto bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2.5 rounded-xl text-[10px] font-black shadow-md transition-all flex items-center justify-center hover:scale-105 active:scale-95">
                                    <i class="fas fa-file-excel mr-2 text-sm"></i> EKSPOR EXCEL
                                </a>
                            </div>

                            <div
                                class="flex flex-col sm:flex-row justify-between items-center bg-slate-100/50 p-2 rounded-xl border border-slate-200 gap-2">
                                <span
                                    class="text-[9px] font-black text-slate-500 sm:ml-2 uppercase tracking-tighter text-center">
                                    Halaman {{ $displayLaporans->currentPage() }} dari
                                    {{ $displayLaporans->lastPage() }}
                                </span>
                                <div class="pagination-wrapper scale-75 md:scale-90 origin-center">
                                    {{ $displayLaporans->links() }}
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- AREA TABEL --}}
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead
                                class="hidden md:table-header-group bg-slate-50 text-slate-400 text-[10px] uppercase font-black tracking-widest border-b border-slate-100">
                                <tr>
                                    <th class="p-6">Pengguna & Wilayah</th>
                                    <th class="p-6">Layanan & Kategori</th>
                                    <th class="p-6 text-center">Bukti Foto</th>
                                    <th class="p-6">Detail Kendala</th>
                                    <th class="p-6 text-orange-500">Respon Admin</th>
                                    <th class="p-6 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody
                                class="text-sm font-bold text-slate-600 divide-y divide-slate-100 block md:table-row-group">
                                @forelse($displayLaporans as $l)
                                    @php
                                        $status = $l->is_rejected ? 'ditolak' : (!empty($l->tanggapan_admin) ? 'selesai' : 'pending');
                                        $namaKecamatan = strtoupper($l->wilayah ?? 'WILAYAH TIDAK DIKENAL');
                                        $createdAt = \Carbon\Carbon::parse($l->created_at);

                                        $original = match ($l->type) {
                                            'trouble' => \App\Models\Trouble::find($l->id),
                                            'proxy' => \App\Models\Proxy::find($l->id),
                                            'pembubuhan' => \App\Models\Pembubuhan::find($l->id),
                                            'pengajuan' => \App\Models\Pengajuan::find($l->id),
                                            default => null
                                        };

                                        $labelLayanan = 'Kategori';
                                        $isiLayanan = '-';
                                        if ($original) {
                                            if ($l->type == 'trouble') {
                                                $isiLayanan = $original->kategori ?? $original->jenis_layanan ?? 'Sistem/SIAK';
                                            } elseif ($l->type == 'pengajuan') {
                                                $labelLayanan = 'Kategori SIAK';
                                                $isiLayanan = $original->kategori ?? '-';
                                            } elseif ($l->type == 'pembubuhan') {
                                                $labelLayanan = 'Jenis Dokumen';
                                                $isiLayanan = $original->jenis_dokumen ?? $original->nama_dokumen ?? '-';
                                            } elseif ($l->type == 'proxy') {
                                                $labelLayanan = 'Masalah Proxy';
                                                $isiLayanan = $original->kategori ?? 'Akses Jaringan';
                                            }
                                        }

                                        $rawImages = $original ? ($original->foto_trouble ?? $original->foto_bukti ?? $original->foto_dokumen ?? $original->foto_pembubuhan ?? $original->foto_proxy) : null;
                                        $images = !empty($rawImages) ? (is_array(json_decode($rawImages, true)) ? json_decode($rawImages, true) : [$rawImages]) : [];
                                    @endphp

                                    <tr
                                        class="hover:bg-slate-50/80 transition-all border-b border-slate-100 {{ $status !== 'pending' ? 'opacity-80 grayscale-[0.3]' : '' }} block md:table-row mb-4 md:mb-0 bg-white">

                                        {{-- Pengguna & Wilayah --}}
                                        <td class="p-4 md:p-6 block md:table-cell">
                                            <div class="flex flex-col gap-2">
                                                <div class="flex flex-wrap gap-1.5 items-center">
                                                    @php
                                                        $config = [
                                                            'trouble' => ['bg' => 'bg-red-600', 'l' => 'TROUBLE'],
                                                            'proxy' => ['bg' => 'bg-indigo-600', 'l' => 'PROXY'],
                                                            'pembubuhan' => ['bg' => 'bg-blue-600', 'l' => 'Pembubuhan'],
                                                            'pengajuan' => ['bg' => 'bg-emerald-600', 'l' => 'Kendala SIAK']
                                                        ];
                                                        $curr = $config[$l->type] ?? ['bg' => 'bg-slate-600', 'l' => 'SISTEM'];
                                                    @endphp
                                                    <span
                                                        class="px-2 py-0.5 rounded text-[9px] font-black text-white {{ $curr['bg'] }} uppercase">{{ $curr['l'] }}</span>

                                                    @if($status === 'selesai')
                                                        <span
                                                            class="px-2 py-0.5 rounded text-[9px] font-black bg-emerald-500 text-white">✅
                                                            SELESAI</span>
                                                    @elseif($status === 'ditolak')
                                                        <span
                                                            class="px-2 py-0.5 rounded text-[9px] font-black bg-red-500 text-white">❌
                                                            DITOLAK</span>
                                                    @else
                                                        <span
                                                            class="px-2 py-0.5 rounded text-[9px] font-black bg-orange-400 text-white animate-pulse">⏳
                                                            MENUNGGU</span>
                                                    @endif
                                                </div>
                                                <div>
                                                    <div
                                                        class="text-slate-900 font-black text-base md:text-base italic leading-tight">
                                                        {{ $l->user_name }}
                                                    </div>
                                                    <div
                                                        class="text-[10px] text-blue-600 uppercase font-black flex items-center mt-1">
                                                        <i class="fas fa-map-marker-alt mr-1.5"></i> {{ $namaKecamatan }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                        {{-- Layanan & Kategori --}}
                                        <td class="p-4 md:p-6 block md:table-cell border-t md:border-none">
                                            <div class="flex flex-col">
                                                <span
                                                    class="text-[9px] text-slate-400 uppercase font-bold tracking-widest mb-1 md:hidden">Informasi
                                                    Kategori:</span>
                                                <span
                                                    class="text-[9px] text-slate-400 uppercase font-bold tracking-widest mb-1 hidden md:block">{{ $labelLayanan }}:</span>
                                                <span
                                                    class="text-xs font-black text-slate-700 uppercase break-words leading-tight">{{ $isiLayanan }}</span>
                                            </div>
                                        </td>

                                        {{-- Bukti Foto --}}
                                        <td
                                            class="p-4 md:p-6 text-left md:text-center block md:table-cell border-t md:border-none">
                                            <span
                                                class="text-[9px] text-slate-400 uppercase font-bold tracking-widest mb-2 block md:hidden">Bukti
                                                Foto:</span>
                                            <div class="flex md:justify-center -space-x-3">
                                                @forelse($images as $img)
                                                    <img src="{{ asset('storage/' . $img) }}"
                                                        @click="imgModal = true; imgModalSrc = '{{ asset('storage/' . $img) }}'; imgModalKategori = '{{ strtoupper($l->type) }}'"
                                                        class="w-12 h-12 md:w-14 md:h-14 object-cover rounded-xl cursor-zoom-in border-4 border-white shadow-md hover:z-20 hover:scale-110 transition-all">
                                                @empty
                                                    <div
                                                        class="w-12 h-12 md:w-14 md:h-14 rounded-xl bg-slate-50 border-2 border-dashed border-slate-200 flex items-center justify-center text-slate-300">
                                                        <i class="fas fa-image text-xs"></i>
                                                    </div>
                                                @endforelse
                                            </div>
                                        </td>

                                        {{-- Detail Kendala --}}
                                        <td class="p-4 md:p-6 block md:table-cell border-t md:border-none">
                                            <div class="flex flex-col max-w-full md:max-w-[300px]">
                                                <div
                                                    class="text-[11px] text-slate-600 font-bold italic mb-3 leading-relaxed bg-slate-50 p-3 rounded-2xl border-l-4 border-slate-200 break-words whitespace-normal">
                                                    "{{ $l->pesan }}"
                                                </div>
                                                <div
                                                    class="text-[10px] text-slate-500 font-black uppercase flex items-center gap-2">
                                                    <i class="far fa-clock text-blue-500"></i>
                                                    <span>{{ $createdAt->translatedFormat('d F Y, H:i') }} WIB</span>
                                                </div>
                                            </div>
                                        </td>

                                        {{-- Respon Admin --}}
                                        <td class="p-4 md:p-6 block md:table-cell border-t md:border-none">
                                            <span
                                                class="text-[9px] text-orange-500 uppercase font-black tracking-widest mb-2 block md:hidden">Tanggapan
                                                Admin:</span>
                                            @if($status !== 'ditolak')
                                                <form action="{{ route('admin.laporan.respon') }}" method="POST"
                                                    class="flex flex-col gap-2.5">
                                                    @csrf
                                                    <input type="hidden" name="laporan_id" value="{{ $l->id }}">
                                                    <input type="hidden" name="type" value="{{ $l->type }}">
                                                    <textarea name="admin_note" rows="2"
                                                        class="w-full bg-white border border-slate-200 rounded-2xl py-2.5 px-4 text-[11px] font-bold focus:border-emerald-500 focus:ring-0 resize-none transition-all shadow-sm"
                                                        required
                                                        placeholder="Tulis solusi...">{{ $l->tanggapan_admin }}</textarea>
                                                    <button type="submit"
                                                        class="w-full {{ empty($l->tanggapan_admin) ? 'bg-orange-500' : 'bg-slate-800' }} text-white py-2.5 rounded-xl text-[10px] font-black uppercase transition-all shadow-sm hover:opacity-90 active:scale-95">
                                                        {{ empty($l->tanggapan_admin) ? '🚀 Kirim Respon' : '🔄 Perbarui' }}
                                                    </button>
                                                </form>
                                            @else
                                                <div class="bg-red-50 border border-red-100 rounded-2xl p-4">
                                                    <p class="text-[11px] text-red-700 font-black italic">
                                                        {{ $l->tanggapan_admin ?? 'Laporan ini ditolak oleh sistem.' }}
                                                    </p>
                                                </div>
                                            @endif
                                        </td>

                                        {{-- Aksi --}}
                                        <td
                                            class="p-4 md:p-6 text-center block md:table-cell border-t md:border-none bg-slate-50/30 md:bg-transparent">
                                            <div class="flex items-center justify-center gap-2">
                                                @if($status === 'pending')
                                                    <form action="{{ route('admin.laporan.tolak', $l->id) }}" method="POST"
                                                        onsubmit="return confirm('Tolak laporan ini?')">
                                                        @csrf
                                                        <input type="hidden" name="type" value="{{ $l->type }}">
                                                        <button type="submit"
                                                            class="text-[9px] font-black text-red-500 border-2 border-red-500 px-3 py-2 rounded-xl hover:bg-red-500 hover:text-white transition-all uppercase">Tolak</button>
                                                    </form>
                                                @endif
                                                <form action="{{ route('admin.laporan.hapus', $l->id) }}" method="POST"
                                                    onsubmit="return confirm('Hapus permanen data ini?')">
                                                    @csrf @method('DELETE')
                                                    <input type="hidden" name="type" value="{{ $l->type }}">
                                                    <button type="submit"
                                                        class="text-[9px] font-black text-red-500 border-2 border-red-500 px-3 py-2 rounded-xl hover:bg-red-600 hover:text-white transition-all uppercase">Hapus</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="block md:table-row">
                                        <td colspan="6" class="p-20 text-center block md:table-cell">
                                            <div class="flex flex-col items-center justify-center opacity-20">
                                                <i class="fas fa-shield-alt text-6xl mb-4"></i>
                                                <span class="text-sm font-black uppercase tracking-widest">Tidak ada data
                                                    ditemukan</span>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- MODAL PRATINJAU GAMBAR --}}
                <div x-show="imgModal"
                    class="fixed inset-0 z-[999] flex items-center justify-center p-4 bg-slate-900/90 backdrop-blur-sm"
                    x-transition x-cloak @click.away="imgModal = false">
                    <div class="relative w-full max-w-4xl mx-auto" @click.stop>
                        <div class="absolute -top-10 left-0 right-0 flex justify-between items-center text-white px-1">
                            <span
                                class="bg-emerald-500 text-[10px] font-black px-3 py-1 rounded-lg uppercase tracking-wider"
                                x-text="imgModalKategori"></span>
                            <button @click="imgModal = false"
                                class="bg-white/10 hover:bg-red-500 w-8 h-8 rounded-full transition-all">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <div class="bg-white p-1 rounded-xl md:rounded-2xl shadow-2xl">
                            <img :src="imgModalSrc"
                                class="w-full h-auto max-h-[75vh] object-contain rounded-lg bg-slate-50">
                        </div>
                        <div class="mt-4 flex justify-center">
                            <a :href="imgModalSrc" download
                                class="bg-white hover:bg-slate-100 text-slate-900 px-8 py-3 rounded-xl text-[11px] font-black uppercase flex items-center gap-2 transition-all shadow-lg">
                                <i class="fas fa-download text-emerald-500"></i> Simpan Gambar
                            </a>
                        </div>
                    </div>
                </div>
            </div>


            {{-- FItur LAPORAN UPDATE DATA --}}
            <div x-show="tab === 'laporan_update_data'" x-data="{ 
    allData: [...{{ json_encode($updateDatasJs) }}],
    listKecamatan: {{ json_encode($kecamatans->pluck('nama_kecamatan')) }},
    filterMonth: '{{ now()->timezone('Asia/Jakarta')->format('m') }}', 
    filterKecamatan: '',
    filterStatus: '',
    currentPage: 1,
    perPage: 10,
    
    // Logic Modal Detail
    showEditModal: false,
    selectedRow: {},

    // Logic Modal Pratinjau Gambar (Baru)
    imgModal: false,
    imgModalSrc: '',
    imgModalKategori: 'Lampiran Dokumen',

    openEdit(row) {
        this.selectedRow = {...row};
        this.showEditModal = true;
    },

    openImgModal(src, kategori = 'Lampiran Dokumen') {
        this.imgModalSrc = src;
        this.imgModalKategori = kategori;
        this.imgModal = true;
    },

    get filteredData() {
        let filtered = this.allData.filter(row => {
            const kecMatch = this.filterKecamatan === '' || row.location === this.filterKecamatan;
            const monthMatch = this.filterMonth === '' || row.month === this.filterMonth;
            const statusMatch = this.filterStatus === '' || row.status === this.filterStatus;
            return kecMatch && monthMatch && statusMatch;
        });

        return filtered.sort((a, b) => {
            const statusOrder = { 'pending': 0, 'selesai': 1, 'ditolak': 2 };
            return (statusOrder[a.status] ?? 99) - (statusOrder[b.status] ?? 99);
        });
    },

    get pagedData() {
        let start = (this.currentPage - 1) * this.perPage;
        return this.filteredData.slice(start, start + this.perPage);
    },

    get totalPages() {
        return Math.max(1, Math.ceil(this.filteredData.length / this.perPage));
    }
}" x-transition x-cloak class="relative space-y-4 md:space-y-6">

                {{-- BAGIAN FILTER --}}
                <div
                    class="flex flex-col lg:flex-row lg:flex-wrap items-center gap-3 bg-white/50 p-3 md:p-2 rounded-2xl md:rounded-3xl">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 w-full lg:w-auto flex-grow">
                        <select x-model="filterMonth" @change="currentPage = 1"
                            class="bg-white border border-slate-200 rounded-xl md:rounded-2xl px-4 py-3 md:px-6 md:py-4 text-[10px] md:text-xs font-black uppercase outline-none focus:ring-2 focus:ring-blue-500/20 transition-all shadow-sm w-full">
                            <option value="">Semua Bulan</option>
                            @foreach(range(1, 12) as $m)
                                <option value="{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}">
                                    {{ Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                                </option>
                            @endforeach
                        </select>

                        <select x-model="filterKecamatan" @change="currentPage = 1"
                            class="bg-white border border-slate-200 rounded-xl md:rounded-2xl px-4 py-3 md:px-6 md:py-4 text-[10px] md:text-xs font-black uppercase outline-none focus:ring-2 focus:ring-blue-500/20 transition-all shadow-sm w-full">
                            <option value="">Semua Wilayah</option>
                            <template x-for="kec in listKecamatan" :key="kec">
                                <option :value="kec" x-text="kec"></option>
                            </template>
                        </select>

                        <select x-model="filterStatus" @change="currentPage = 1"
                            class="bg-white border border-slate-200 rounded-xl md:rounded-2xl px-4 py-3 md:px-6 md:py-4 text-[10px] md:text-xs font-black uppercase outline-none focus:ring-2 focus:ring-blue-500/20 transition-all shadow-sm w-full">
                            <option value="">Semua Status</option>
                            <option value="pending">Pending</option>
                            <option value="selesai">Selesai</option>
                            <option value="ditolak">Ditolak</option>
                        </select>
                    </div>

                    <div class="w-full lg:w-auto lg:ml-auto">
                        <a :href="'{{ route('admin.laporan.export') }}?type=updatedata&month=' + filterMonth + '&kecamatan=' + filterKecamatan + '&status=' + filterStatus"
                            class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-3 md:px-8 md:py-4 rounded-xl md:rounded-2xl text-[10px] md:text-xs font-black uppercase flex items-center justify-center gap-2 shadow-lg shadow-emerald-200/50 transition-all w-full">
                            <i class="fas fa-file-excel"></i> Ekspor Excel
                        </a>
                    </div>
                </div>

                {{-- INFO HALAMAN & PAGINATION --}}
                <div
                    class="bg-white rounded-2xl md:rounded-[2.5rem] p-4 md:p-6 shadow-sm border border-slate-100 flex flex-col md:flex-row justify-between items-center gap-4">
                    <div
                        class="text-[9px] md:text-[10px] font-black text-slate-400 uppercase tracking-widest text-center md:text-left">
                        Halaman <span class="text-slate-900" x-text="currentPage"></span> dari <span
                            class="text-slate-900" x-text="totalPages"></span>
                    </div>
                    <div class="flex flex-col sm:flex-row items-center gap-3 md:gap-4 w-full md:w-auto">
                        <span class="text-[10px] md:text-[11px] font-bold text-slate-500">
                            Showing <span x-text="filteredData.length > 0 ? ((currentPage-1)*perPage)+1 : 0"></span> to
                            <span x-text="Math.min(currentPage*perPage, filteredData.length)"></span> of
                            <span x-text="filteredData.length"></span> results
                        </span>
                        <div
                            class="flex border border-slate-200 rounded-xl overflow-hidden shadow-sm scale-90 md:scale-100">
                            <button @click="currentPage--" :disabled="currentPage === 1"
                                class="px-4 py-2 bg-white hover:bg-slate-50 disabled:opacity-50 transition-colors border-r border-slate-200">
                                <i class="fas fa-chevron-left text-xs text-slate-400"></i>
                            </button>
                            <div class="px-5 py-2 bg-slate-100 font-black text-xs flex items-center justify-center min-w-[40px]"
                                x-text="currentPage"></div>
                            <button @click="currentPage++" :disabled="currentPage === totalPages"
                                class="px-4 py-2 bg-white hover:bg-slate-50 disabled:opacity-50 transition-colors">
                                <i class="fas fa-chevron-right text-xs text-slate-400"></i>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- KONTEN DATA --}}
                <div
                    class="bg-transparent md:bg-white md:rounded-[2.5rem] md:shadow-xl md:border md:border-slate-100 overflow-hidden">
                    {{-- Header Table (Hidden on Mobile) --}}
                    <div
                        class="hidden lg:grid grid-cols-12 gap-4 p-8 border-b border-slate-50 text-[10px] font-black text-slate-400 uppercase tracking-widest italic">
                        <div class="col-span-3">Pelapor & Wilayah</div>
                        <div class="col-span-2 text-center">Layanan & NIK</div>
                        <div class="col-span-1 text-center">Lampiran</div>
                        <div class="col-span-2 text-center">Detail Perubahan</div>
                        <div class="col-span-3 text-center text-orange-500">Respon Admin</div>
                        <div class="col-span-1 text-right text-slate-400">Aksi</div>
                    </div>

                    <div class="flex flex-col gap-4 md:gap-0 md:divide-y md:divide-slate-100">
                        <template x-for="row in pagedData" :key="row.id">
                            <div
                                class="bg-white md:bg-transparent p-5 md:p-8 rounded-3xl md:rounded-none shadow-sm md:shadow-none grid grid-cols-1 lg:grid-cols-12 gap-4 items-start hover:bg-slate-50/50 transition-all group border border-slate-100 md:border-none">

                                {{-- Nama & Wilayah --}}
                                <div class="col-span-1 lg:col-span-2 space-y-2">
                                    <div class="text-base md:text-lg font-black text-slate-900 leading-tight"
                                        x-text="row.name"></div>
                                    <div class="flex items-center text-[10px] font-black text-blue-700 uppercase">
                                        <i class="fas fa-map-marker-alt mr-2 text-blue-500"></i>
                                        <span x-text="row.location"></span>
                                    </div>
                                    <div class="pt-1">
                                        <span :class="{
                                'bg-orange-100 text-orange-800 border-orange-300': row.status === 'pending',
                                'bg-emerald-100 text-emerald-800 border-emerald-300': row.status === 'selesai',
                                'bg-red-100 text-red-800 border-red-300': row.status === 'ditolak'
                            }" class="px-3 py-1 md:px-4 md:py-1.5 rounded-full text-[9px] md:text-[10px] font-black uppercase tracking-widest border block w-fit"
                                            x-text="row.status === 'pending' ? 'MENUNGGU' : row.status"></span>
                                    </div>
                                </div>

                                {{-- Layanan & NIK --}}
                                <div
                                    class="col-span-1 lg:col-span-2 lg:text-center flex lg:flex-col justify-between items-center lg:justify-start gap-2 bg-slate-50 md:bg-transparent p-3 md:p-0 rounded-xl">
                                    <div class="text-[9px] md:text-[10px] font-black text-slate-700 uppercase"
                                        x-text="row.jenis_layanan"></div>
                                    <div class="font-mono text-xs md:text-sm font-black text-slate-900 px-3 py-1.5 md:p-2 bg-white md:bg-slate-200 rounded-lg md:rounded-xl border border-slate-200 md:border-slate-300 shadow-sm md:shadow-none"
                                        x-text="row.nik_pemohon || '-'"></div>
                                </div>

                                {{-- Lampiran --}}
                                <div class="col-span-1 lg:col-span-1 flex flex-wrap lg:justify-center gap-2 py-2">
                                    <template x-if="row.lampiran">
                                        <div class="flex flex-wrap gap-2">
                                            <template x-if="Array.isArray(row.lampiran)">
                                                <template x-for="(img, index) in row.lampiran" :key="index">
                                                    <div class="w-10 h-12 md:w-8 md:h-10 rounded-lg border border-slate-300 overflow-hidden cursor-pointer hover:scale-110 transition-transform shadow-sm"
                                                        @click="openImgModal(img, row.name)">
                                                        <img :src="img" class="w-full h-full object-cover">
                                                    </div>
                                                </template>
                                            </template>
                                            <template x-if="!Array.isArray(row.lampiran)">
                                                <div class="w-10 h-12 rounded-lg border border-slate-300 overflow-hidden cursor-pointer hover:scale-110 transition-transform shadow-sm"
                                                    @click="openImgModal(row.lampiran, row.name)">
                                                    <img :src="row.lampiran" class="w-full h-full object-cover">
                                                </div>
                                            </template>
                                        </div>
                                    </template>
                                    <template x-if="!row.lampiran">
                                        <div
                                            class="w-10 h-10 bg-slate-50 flex items-center justify-center text-slate-400 border border-dashed border-slate-300 rounded-lg">
                                            <i class="fas fa-image"></i>
                                        </div>
                                    </template>
                                </div>

                                {{-- Detail Perubahan (FIXED: Auto-Height & No Truncate) --}}
                                <div class="col-span-1 lg:col-span-3">
                                    <div class="bg-white p-4 md:p-5 rounded-2xl border border-slate-200 md:border-slate-300 relative h-auto flex flex-col justify-center cursor-pointer hover:bg-slate-50 transition-colors shadow-sm"
                                        @click="openEdit(row)">
                                        <i
                                            class="fas fa-quote-left absolute top-3 left-3 text-[10px] text-blue-500"></i>
                                        <div class="w-full pt-2 pb-1 px-3">
                                            <div class="text-[10px] md:text-[11px] font-bold text-slate-900 italic leading-relaxed text-left break-words whitespace-pre-line"
                                                x-text="row.deskripsi || '-'">
                                            </div>
                                        </div>
                                        <div
                                            class="mt-2 text-[8px] md:text-[9px] font-black text-blue-700 flex items-center justify-end px-2 uppercase">
                                            <i class="far fa-clock mr-1"></i> <span x-text="row.created_at"></span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Respon Admin --}}
                                <div class="col-span-1 lg:col-span-3">
                                    <form :action="row.url_respon" method="POST" class="space-y-3">
                                        <input type="hidden" name="_token" :value="row.csrf">
                                        <input type="hidden" name="laporan_id" :value="row.id">
                                        <input type="hidden" name="type" value="updatedata">

                                        <textarea name="admin_note" rows="2"
                                            class="w-full bg-slate-50 md:bg-white border-2 border-slate-200 rounded-xl md:rounded-2xl p-3 text-[10px] md:text-[11px] font-bold text-slate-900 outline-none focus:border-orange-500 transition-all resize-none placeholder:text-slate-400"
                                            placeholder="Tulis catatan verifikasi..."
                                            x-model="row.tanggapan_admin"></textarea>

                                        <button type="submit"
                                            class="w-full bg-orange-600 text-white py-3 rounded-xl md:rounded-2xl text-[10px] font-black uppercase hover:bg-orange-700 shadow-lg shadow-orange-100 active:scale-95 transition-all">
                                            Kirim Respon
                                        </button>
                                    </form>
                                </div>

                                {{-- Aksi --}}
                                <div
                                    class="col-span-1 lg:col-span-1 grid grid-cols-2 lg:flex lg:flex-col gap-2 pt-2 lg:pt-0">
                                    <form :action="'{{ url('admin/laporan/tolak') }}/' + row.id" method="POST"
                                        onsubmit="return confirm('Tolak laporan ini?')">
                                        <input type="hidden" name="_token" :value="row.csrf">
                                        <input type="hidden" name="laporan_id" :value="row.id">
                                        <input type="hidden" name="type" value="updatedata">
                                        <button type="submit"
                                            class="w-full bg-red-600 text-white py-2.5 rounded-xl text-[9px] font-black uppercase shadow-md hover:bg-red-700 transition-all border-b-4 border-red-800">
                                            Tolak
                                        </button>
                                    </form>

                                    <form :action="row.url_hapus" method="POST"
                                        onsubmit="return confirm('Hapus permanen data ini?')">
                                        <input type="hidden" name="_token" :value="row.csrf">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button type="submit"
                                            class="w-full bg-slate-800 text-white py-2.5 rounded-xl text-[9px] font-black uppercase shadow-md hover:bg-black transition-all border-b-4 border-slate-950">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </template>
                    </div>

                    {{-- MODAL PRATINJAU GAMBAR (LIGHTBOX) --}}
                    <div x-show="imgModal"
                        class="fixed inset-0 z-[10000] flex items-center justify-center p-4 bg-slate-900/90 backdrop-blur-sm"
                        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-cloak
                        @click="imgModal = false">

                        <div class="relative w-full max-w-4xl mx-auto" @click.stop>
                            <div
                                class="absolute -top-12 left-0 right-0 flex justify-between items-center text-white px-1">
                                <div class="flex items-center gap-2 md:gap-3">
                                    <span
                                        class="bg-emerald-500 text-[8px] md:text-[10px] font-black px-2 md:py-1 rounded-lg uppercase tracking-wider"
                                        x-text="imgModalKategori"></span>
                                    <span
                                        class="text-[8px] md:text-[10px] font-medium text-slate-300 uppercase tracking-widest truncate max-w-[150px]"
                                        x-text="imgModalSrc.split('/').pop()"></span>
                                </div>
                                <button @click="imgModal = false"
                                    class="bg-white/10 hover:bg-red-500 w-8 h-8 md:w-10 md:h-10 rounded-full transition-all flex items-center justify-center">
                                    <i class="fas fa-times text-white text-xs"></i>
                                </button>
                            </div>

                            <div class="bg-white p-1 md:p-2 rounded-2xl md:rounded-[2rem] shadow-2xl">
                                <div
                                    class="relative overflow-hidden rounded-xl md:rounded-[1.5rem] bg-slate-50 flex items-center justify-center min-h-[150px]">
                                    <img :src="imgModalSrc"
                                        class="w-full h-auto max-h-[70vh] md:max-h-[75vh] object-contain">
                                </div>
                            </div>

                            <div class="mt-6 md:mt-8 flex justify-center px-4">
                                <a :href="imgModalSrc" download target="_blank"
                                    class="bg-white hover:bg-emerald-500 hover:text-white text-slate-900 px-6 py-3 md:px-10 md:py-4 rounded-xl md:rounded-2xl text-[10px] md:text-[11px] font-black uppercase flex items-center gap-3 transition-all shadow-xl active:scale-95 group w-full md:w-auto justify-center">
                                    <i class="fas fa-download text-emerald-500 group-hover:text-white"></i>
                                    Simpan Gambar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <style>
                /* Mencegah teks meluap dan memastikan break-word pada pesan pelapor */
                .break-words {
                    word-wrap: break-word;
                    overflow-wrap: break-word;
                    word-break: break-word;
                }

                @media (max-width: 768px) {
                    select {
                        max-height: 50px;
                    }
                }
            </style>

</html>