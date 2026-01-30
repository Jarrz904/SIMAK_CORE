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

                    <div class="flex items-center gap-3">
                        @php
                            $pendingNotifications = collect($sortedLaporans)->filter(function ($item) {
                                return empty($item->tanggapan_admin);
                            });
                            $unreadCount = $pendingNotifications->count();
                        @endphp

                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open"
                                class="relative w-12 h-12 bg-white rounded-2xl shadow-md border border-slate-100 flex items-center justify-center transition-all hover:bg-slate-50 group">
                                <i class="fas fa-bell text-slate-400 group-hover:text-orange-500 transition-colors"></i>
                                @if($unreadCount > 0)
                                    <span
                                        class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-[10px] font-black rounded-full flex items-center justify-center border-2 border-white animate-bounce shadow-sm">
                                        {{ $unreadCount }}
                                    </span>
                                @endif
                            </button>

                            <div x-show="open" @click.away="open = false"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                                x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-cloak
                                class="absolute right-0 mt-3 w-[calc(100vw-2rem)] sm:w-80 md:w-96 bg-white rounded-[2rem] shadow-2xl border border-slate-100 z-[999] overflow-hidden">

                                <div
                                    class="p-5 border-b border-slate-50 bg-slate-50/50 flex justify-between items-center">
                                    <h3 class="font-black text-slate-800 uppercase italic text-sm">Notifikasi Masuk</h3>
                                    <span
                                        class="bg-slate-900 text-white text-[9px] px-3 py-1 rounded-full font-black tracking-widest">
                                        {{ $unreadCount }} BARU
                                    </span>
                                </div>

                                <div class="max-h-[400px] overflow-y-auto p-4 space-y-3 custom-scrollbar">
                                    @forelse($pendingNotifications as $notif)
                                        @php
                                            $kategori = strtoupper($notif->kategori);
                                            $targetTab = (Str::contains($kategori, 'NIK') || Str::contains($kategori, 'AKTIVASI') || Str::contains($kategori, 'UPDATE')) ? 'laporan_aktivasi' : 'laporan_sistem';
                                        @endphp
                                        <div @click="tab = '{{ $targetTab }}'; open = false; window.scrollTo({top: 0, behavior: 'smooth'})"
                                            class="group p-4 bg-white border border-slate-50 rounded-2xl flex items-start gap-4 hover:bg-orange-50 hover:border-orange-100 transition-all cursor-pointer">
                                            <div
                                                class="bg-orange-100 text-orange-600 w-10 h-10 shrink-0 rounded-xl flex items-center justify-center text-sm shadow-inner group-hover:scale-110 transition-transform">
                                                <i
                                                    class="fas {{ (Str::contains($kategori, 'NIK') || Str::contains($kategori, 'AKTIVASI') || Str::contains($kategori, 'UPDATE')) ? 'fa-fingerprint' : 'fa-database' }}"></i>
                                            </div>
                                            <div class="flex-1">
                                                <div class="flex flex-col">
                                                    <span
                                                        class="text-[10px] font-black text-slate-800 uppercase leading-none">{{ $notif->user_name }}</span>
                                                    <span
                                                        class="text-[8px] text-slate-400 font-bold uppercase mt-1 tracking-tighter">{{ $notif->kategori }}</span>
                                                </div>
                                                <p
                                                    class="text-[11px] text-slate-500 mt-1 line-clamp-2 italic leading-relaxed">
                                                    "{{ $notif->pesan }}"</p>
                                                <span
                                                    class="text-[8px] text-orange-400 font-mono mt-2 block uppercase tracking-widest">{{ $notif->created_at->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="text-center py-12">
                                            <i class="fas fa-check-double text-slate-200 text-2xl mb-3"></i>
                                            <p class="text-slate-400 font-bold text-[10px] uppercase tracking-widest">Tidak
                                                ada laporan baru</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <div
                            class="bg-white px-6 py-3 rounded-2xl shadow-md border border-slate-100 flex items-center gap-4">
                            <div class="text-right">
                                <p
                                    class="text-[10px] font-black text-slate-400 uppercase leading-none tracking-widest mb-1">
                                    Waktu Sistem</p>
                                <p class="text-sm font-black text-slate-800">{{ date('d M Y') }}</p>
                            </div>
                            <div
                                class="w-10 h-10 bg-slate-50 rounded-xl flex items-center justify-center text-orange-500">
                                <i class="fas fa-calendar-alt text-lg"></i>
                            </div>
                        </div>
                    </div>
                </header>

                {{-- STATS CARDS --}}
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-8 gap-4 mb-8">
                    @php
                        /** * SINKRONISASI DATA
                         * Menghitung ulang langsung dari source data agar akurat
                         */
                        $update_datas = $update_datas ?? collect();

                        $cards = [
                            [
                                'label' => 'User',
                                'val' => $totalUser ?? 0,
                                'color' => 'slate-900',
                                'icon' => 'fa-users',
                                'txt' => 'white'
                            ],
                            [
                                'label' => 'Kendala SIAK',
                                'val' => $pengajuans->count(),
                                'color' => 'white',
                                'icon' => 'fa-database',
                                'txt' => 'orange-500'
                            ],
                            [
                                'label' => 'Aktivasi NIK',
                                'val' => $aktivasis->count(),
                                'color' => 'white',
                                'icon' => 'fa-fingerprint',
                                'txt' => 'blue-500'
                            ],
                            [
                                'label' => 'Update Data',
                                'val' => $update_datas->count(),
                                'color' => 'white',
                                'icon' => 'fa-sync-alt',
                                'txt' => 'amber-500'
                            ],
                            [
                                'label' => 'Proxy',
                                'val' => $proxies->count(),
                                'color' => 'white',
                                'icon' => 'fa-network-wired',
                                'txt' => 'emerald-500'
                            ],
                            [
                                'label' => 'Pembubuhan',
                                'val' => $pembubuhans->count(),
                                'color' => 'white',
                                'icon' => 'fa-file-signature',
                                'txt' => 'purple-500'
                            ],
                            [
                                'label' => 'Luar Daerah',
                                'val' => $luarDaerahs->count(),
                                'color' => 'white',
                                'icon' => 'fa-map-marked-alt',
                                'txt' => 'cyan-500'
                            ],
                            [
                                'label' => 'Trouble',
                                'val' => $troubles->count(),
                                'color' => 'white',
                                'icon' => 'fa-bug',
                                'txt' => 'rose-500'
                            ],
                        ];
                    @endphp

                    @foreach($cards as $c)
                        <div
                            class="bg-{{ $c['color'] }} p-4 rounded-[1.5rem] shadow-sm border border-slate-100 relative overflow-hidden transition-all duration-300 hover:shadow-lg hover:-translate-y-1 group">
                            {{-- Glow Indicator for White Cards --}}
                            @if($c['color'] == 'white')
                                <div class="absolute top-0 left-0 w-1 h-full bg-{{ $c['txt'] }}"></div>
                            @endif

                            {{-- Label --}}
                            <p
                                class="text-{{ $c['txt'] }} text-[8px] font-black uppercase tracking-widest relative z-10 opacity-80 group-hover:opacity-100">
                                {{ $c['label'] }}
                            </p>

                            {{-- Angka --}}
                            <h2
                                class="text-2xl font-black {{ $c['color'] == 'white' ? 'text-slate-800' : 'text-white' }} leading-tight relative z-10 mt-1">
                                {{ $c['val'] }}
                            </h2>

                            {{-- Background Icon --}}
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

                            @if($errors->any())
                                <div
                                    class="mb-4 p-4 bg-red-500/10 border border-red-500/30 rounded-2xl text-red-400 text-[10px] font-bold uppercase text-center">
                                    <i class="fas fa-exclamation-triangle mr-2"></i> Semua field wajib diisi!
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
                        {{-- Watermark Icon --}}
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
                            ['title' => 'Kategori Kendala SIAK', 'icon' => 'fa-database', 'color' => 'orange', 'data' => $siakCategories, 'total' => $countSiak],
                            ['title' => 'Kategori Update Data', 'icon' => 'fa-sync-alt', 'color' => 'amber', 'data' => $updateCategories, 'total' => $countUpdateData],
                            ['title' => 'Kategori TTE (Lokal)', 'icon' => 'fa-file-signature', 'color' => 'purple', 'data' => $tteCategories, 'total' => $countTte],
                            ['title' => 'Kategori Luar Daerah', 'icon' => 'fa-map-marked-alt', 'color' => 'cyan', 'data' => $luarDaerahCategories, 'total' => $countLuarDaerah],
                            ['title' => 'Kategori Trouble', 'icon' => 'fa-exclamation-triangle', 'color' => 'rose', 'data' => $troubleCategories, 'total' => $countTrouble]
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
                                            <span>{{ $name ?: 'Umum' }}</span>
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
                                labels: ['SIAK', 'NIK', 'Update Data', 'TTE', 'Proxy', 'Trouble', 'Luar Daerah'],
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
                                    backgroundColor: ['#f97316', '#3b82f6', '#f59e0b', '#8b5cf6', '#6366f1', '#f43f5e', '#06b6d4'],
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
                                    },
                                    tooltip: {
                                        backgroundColor: '#1e293b',
                                        padding: 12,
                                        titleFont: { size: 12, weight: 'bold' },
                                        bodyFont: { size: 11 },
                                        cornerRadius: 8,
                                        displayColors: true
                                    }
                                }
                            }
                        });
                    });
                </script>
            </div>



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
                                <label class="text-[10px] font-black text-slate-400 uppercase ml-2 block mb-1">Lokasi
                                    Kecamatan</label>
                                <div class="relative group">
                                    <input list="list_kecamatan" name="location" required value="{{ old('location') }}"
                                        placeholder="Pilih atau cari kecamatan..."
                                        class="w-full bg-slate-50 border border-slate-200 rounded-xl md:rounded-2xl p-3 md:p-4 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-all outline-none cursor-pointer appearance-none"
                                        autocomplete="off">

                                    <datalist id="list_kecamatan" class="max-h-40 overflow-y-auto">
                                        @foreach($kecamatans as $kec)
                                            <option value="{{ strtoupper($kec->nama_kecamatan) }}">
                                        @endforeach
                                    </datalist>

                                    {{-- Icon indikator agar user tahu ini dropdown --}}
                                    <div
                                        class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-slate-400">
                                        <i class="fas fa-search text-[10px]"></i>
                                    </div>
                                </div>
                                <p class="text-[9px] text-slate-400 mt-1 ml-2 italic">*Ketik untuk mencari, klik
                                    untuk
                                    melihat daftar.</p>
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
                        class="bg-white w-full max-w-md rounded-[1.5rem] md:rounded-[2.5rem] p-6 md:p-8 shadow-2xl border border-slate-200 max-h-[95vh] overflow-y-auto transform transition-all">
                        <div class="flex justify-between items-center mb-6 border-b border-slate-100 pb-4">
                            <h2 class="text-lg md:text-xl font-black uppercase italic text-slate-800">Edit Pengguna</h2>
                            <button @click="editModal = false" class="text-slate-400 hover:text-red-500">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>

                        {{-- Perbaikan: Action diarahkan ke ID yang sesuai dan pastikan method POST dengan spoofing PUT
                        --}}
                        <form :action="'/admin/users/' + editData.id + '/update'" method="POST" class="space-y-4">
                            @csrf
                            @method('PUT')

                            {{-- Input Nama --}}
                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase block mb-1 ml-2">Nama
                                    Lengkap</label>
                                <input type="text" name="name" x-model="editData.name" required
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 md:p-4 text-sm font-bold text-slate-700 outline-none focus:border-orange-500 transition-all">
                            </div>
    
                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase block mb-1 ml-2">PIN
                                    Keamanan</label>
                                <input type="text" name="pin" x-model="editData.pin" required maxlength="10"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 md:p-4 text-sm font-bold text-slate-700 outline-none focus:border-orange-500 transition-all"
                                    placeholder="PIN 6-10 Digit">
                            </div>

                            {{-- Input Lokasi --}}
                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase block mb-1 ml-2">Lokasi
                                    Kecamatan</label>
                                <select name="location" x-model="editData.location" required
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 md:p-4 text-sm font-bold text-slate-700 outline-none focus:border-orange-500 transition-all cursor-pointer">
                                    <option value="" disabled>Pilih Kecamatan</option>
                                    @foreach($kecamatans as $kec)
                                        <option value="{{ $kec->nama_kecamatan }}">
                                            {{ strtoupper($kec->nama_kecamatan) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Input Role --}}
                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase block mb-1 ml-2">Hak
                                    Akses</label>
                                <select name="role" x-model="editData.role"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 md:p-4 text-sm font-bold text-slate-700 outline-none focus:border-orange-500 transition-all cursor-pointer">
                                    <option value="user">USER</option>
                                    <option value="admin">ADMIN</option>
                                </select>
                            </div>

                            {{-- Input Password (Opsional) --}}
                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase block mb-1 ml-2">Password
                                    Baru (Kosongkan jika tidak ganti)</label>
                                <input type="password" name="password"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 md:p-4 text-sm font-bold text-slate-700 outline-none focus:border-orange-500 transition-all"
                                    placeholder="Minimal 6 karakter">
                            </div>

                            <div class="flex gap-3 pt-4">
                                <button type="button" @click="editModal = false"
                                    class="flex-1 bg-slate-100 text-slate-500 font-black py-3 md:py-4 rounded-xl md:rounded-2xl uppercase text-[10px] md:text-xs transition-all hover:bg-slate-200">
                                    Batal
                                </button>
                                <button type="submit"
                                    class="flex-1 bg-slate-900 text-white font-black py-3 md:py-4 rounded-xl md:rounded-2xl shadow-lg uppercase text-[10px] md:text-xs hover:bg-orange-600 transition-all transform active:scale-95">
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
                        // Mengambil jenis_layanan (Akte Kelahiran/Kematian) jika ada, jika tidak default ke AKTIVASI NIK
                        $item->display_category = strtoupper($item->jenis_layanan ?? 'AKTIVASI NIK');
                        $combined->push($item);
                    }
                }

                if (isset($luarDaerahs)) {
                    foreach ($luarDaerahs as $item) {
                        $item->row_type = 'LUARDAERAH';
                        // PERBAIKAN: Mengambil jenis_dokumen (Cetak KTP/Update) dari database
                        $item->display_category = strtoupper($item->jenis_dokumen ?? 'LUAR DAERAH');
                        $combined->push($item);
                    }
                }
                // 3. Ambil Data Update (Sinkronisasi)
                if (isset($updateDatas)) {
                    foreach ($updateDatas as $item) {
                        $item->row_type = 'UPDATE';
                        $item->display_category = strtoupper($item->jenis_layanan ?? 'UPDATE DATA');
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

                    // Gunakan Asia/Jakarta untuk perbandingan timestamp yang konsisten
                    return \Carbon\Carbon::parse($a->created_at)->timezone('Asia/Jakarta')->timestamp <=>
                        \Carbon\Carbon::parse($b->created_at)->timezone('Asia/Jakarta')->timestamp;
                })->values();

                // Mapping data ke array sederhana untuk JavaScript dengan Waktu Asia/Jakarta
                $jsData = $sortedItems->map(function ($a) {
                    $dt = \Carbon\Carbon::parse($a->created_at)->timezone('Asia/Jakarta');
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
                        'foto_ktp' => $a->foto_ktp ? asset('storage/' . $a->foto_ktp) : null,
                        'alasan' => $a->alasan ?? 'Tidak ada alasan',
                        'tanggapan' => $a->tanggapan_admin ?? '',
                        'url_respon' => route('admin.laporan.respon'),
                        'url_tolak' => route('admin.laporan.tolak', $a->id),
                        'url_hapus' => route('admin.laporan.hapus', $a->id),
                        'csrf' => csrf_token()
                    ];
                });

                // Ambil daftar kecamatan unik untuk dropdown
                $listKecamatan = $jsData->pluck('location')->unique()->sort()->values();
            @endphp

            <div x-show="tab === 'laporan_aktivasi'" x-data="{ 
        /* Menggabungkan data aktivasi dan data update ke dalam satu array */
        allData: [...{{ json_encode($jsData) }}, ...{{ json_encode($jsUpdateData ?? []) }}],
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

        get filteredData() {
            // 1. Lakukan Filtering
            let filtered = this.allData.filter(row => {
                const kecMatch = this.filterKecamatan === '' || row.location === this.filterKecamatan;
                const monthMatch = this.filterMonth === '' || row.month === this.filterMonth;
                const statusMatch = this.filterStatus === '' || row.status === this.filterStatus;
                const kategoriMatch = this.filterKategori === '' || row.kat_row === this.filterKategori;
                return kecMatch && monthMatch && statusMatch && kategoriMatch;
            });

            // 2. Lakukan Sorting: PENDING di atas (terlama dulu), SELESAI/DITOLAK di bawah
            return filtered.sort((a, b) => {
                const statusOrder = { 'pending': 0, 'selesai': 1, 'ditolak': 2 };
                const orderA = statusOrder[a.status] ?? 99;
                const orderB = statusOrder[b.status] ?? 99;

                if (orderA !== orderB) {
                    return orderA - orderB;
                }

                // Jika status sama, urutkan berdasarkan waktu (Ascending: Terlama ke Terbaru)
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
    }" x-transition x-cloak>

                {{-- MODAL PREVIEW --}}
                <div x-show="showModal"
                    class="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-slate-900/95 backdrop-blur-md"
                    x-transition @click.away="showModal = false" style="display: none;">
                    <div class="relative max-w-5xl w-full" @click.stop>
                        <div class="absolute -top-12 left-0 right-0 flex justify-between items-center px-2">
                            <span
                                class="bg-blue-600 text-white text-xs font-black px-4 py-2 rounded-lg uppercase tracking-widest shadow-lg"
                                x-text="modalType"></span>
                            <button @click="showModal = false"
                                class="bg-white/10 hover:bg-red-500 text-white w-10 h-10 rounded-full flex items-center justify-center transition-all">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <div class="bg-white p-2 rounded-[2rem] shadow-2xl overflow-hidden">
                            <img :src="modalImage" class="w-full h-auto max-h-[75vh] object-contain rounded-[1.5rem]">
                        </div>
                    </div>
                </div>

                {{-- HEADER TITLE --}}
                <div class="mb-8">
                    <h2 class="text-2xl font-black text-slate-800 tracking-tighter uppercase italic">Monitoring
                        Kendala
                        Sistem</h2>
                    <p class="text-[11px] text-slate-500 font-bold uppercase tracking-widest mt-1">Urutan: Prioritas
                        Antrean Terlama (Pending) Di Atas</p>
                </div>

                {{-- FILTER BAR --}}
                <div class="flex flex-wrap items-center gap-4 mb-6">
                    <div class="flex-1 min-w-[150px]">
                        <select x-model="filterMonth" @change="currentPage = 1"
                            class="w-full bg-white border border-slate-200 rounded-2xl px-6 py-4 text-xs font-black uppercase text-slate-700 shadow-sm focus:ring-2 focus:ring-blue-500 outline-none transition-all">
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
                    <div class="flex-1 min-w-[150px]">
                        <select x-model="filterKecamatan" @change="currentPage = 1"
                            class="w-full bg-white border border-slate-200 rounded-2xl px-6 py-4 text-xs font-black uppercase text-slate-700 shadow-sm focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                            <option value="">Semua Wilayah</option>
                            <template x-for="kec in listKecamatan" :key="kec">
                                <option :value="kec" x-text="kec"></option>
                            </template>
                        </select>
                    </div>
                    <div class="flex-1 min-w-[150px]">
                        <select x-model="filterKategori" @change="currentPage = 1"
                            class="w-full bg-white border border-slate-200 rounded-2xl px-6 py-4 text-xs font-black uppercase text-slate-700 shadow-sm focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                            <option value="">Semua Kategori</option>
                            <option value="AKTIVASI">AKTIVASI NIK/AKTE</option>
                            <option value="LUARDAERAH">LUAR DAERAH</option>
                            <option value="UPDATE">UPDATE DATA</option>
                        </select>
                    </div>
                    <div class="flex-1 min-w-[150px]">
                        <select x-model="filterStatus" @change="currentPage = 1"
                            class="w-full bg-white border border-slate-200 rounded-2xl px-6 py-4 text-xs font-black uppercase text-slate-700 shadow-sm focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                            <option value="">Semua Status</option>
                            <option value="pending">⏳ Pending</option>
                            <option value="selesai">✅ Selesai</option>
                        </select>
                    </div>

                    <a :href="'{{ route('export.laporan.aktivasi') }}?' + 
            'month=' + filterMonth + 
            '&kecamatan=' + filterKecamatan + 
            '&status=' + filterStatus + 
            '&kategori=' + filterKategori +
            '&export_time={{ now()->timezone('Asia/Jakarta')->format('Y-m-d_H-i-s') }}'"
                        class="bg-emerald-600 hover:bg-emerald-700 text-white px-8 py-4 rounded-2xl text-xs font-black uppercase flex items-center gap-2 shadow-lg shadow-emerald-200 transition-all hover:scale-105 active:scale-95">
                        <i class="fas fa-file-excel text-sm"></i> Ekspor Excel
                    </a>
                </div>

                {{-- TABLE CONTAINER --}}
                <div class="bg-white rounded-[2.5rem] shadow-xl border border-slate-100 overflow-hidden">
                    {{-- PAGINATION TOP --}}
                    <div class="px-8 py-5 bg-slate-50/50 border-b border-slate-100 flex justify-between items-center">
                        <div class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">
                            Halaman <span x-text="currentPage"></span> Dari <span x-text="totalPages"></span>
                        </div>
                        <div class="flex items-center gap-6">
                            <div class="text-[11px] font-bold text-slate-500">
                                Showing <span
                                    x-text="filteredData.length > 0 ? ((currentPage - 1) * perPage) + 1 : 0"></span>
                                to
                                <span x-text="Math.min(currentPage * perPage, filteredData.length)"></span> of <span
                                    x-text="filteredData.length"></span> results
                            </div>
                            <div class="flex border border-slate-200 rounded-xl overflow-hidden bg-white shadow-sm">
                                <button
                                    @click="if(currentPage > 1) { currentPage--; window.scrollTo({top: 0, behavior: 'smooth'}); }"
                                    :disabled="currentPage === 1"
                                    class="px-4 py-2 hover:bg-slate-50 disabled:opacity-30 border-r border-slate-200 transition-all">
                                    <i class="fas fa-chevron-left text-[10px] text-slate-600"></i>
                                </button>
                                <template x-for="p in totalPages" :key="p">
                                    <button @click="currentPage = p; window.scrollTo({top: 0, behavior: 'smooth'});"
                                        :class="currentPage === p ? 'bg-slate-200 text-slate-800' : 'text-slate-500 hover:bg-slate-50'"
                                        class="px-5 py-2 text-[11px] font-black border-r border-slate-200 transition-all"
                                        x-text="p"></button>
                                </template>
                                <button
                                    @click="if(currentPage < totalPages) { currentPage++; window.scrollTo({top: 0, behavior: 'smooth'}); }"
                                    :disabled="currentPage === totalPages || filteredData.length === 0"
                                    class="px-4 py-2 hover:bg-slate-50 disabled:opacity-30 transition-all">
                                    <i class="fas fa-chevron-right text-[10px] text-slate-600"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse table-fixed min-w-[1100px]">
                            <thead
                                class="text-slate-400 text-[10px] uppercase font-black tracking-widest border-b border-slate-100">
                                <tr>
                                    <th class="p-8 w-1/4">Pengguna & Wilayah</th>
                                    <th class="p-8 w-52">Layanan & Jenis</th>
                                    <th class="p-8 w-32">Bukti Foto</th>
                                    <th class="p-8 w-1/3">Detail Kendala</th>
                                    <th class="p-8 w-1/4 text-orange-600">Respon Admin</th>
                                    <th class="p-8 w-40 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm font-bold text-slate-700 divide-y divide-slate-50">
                                <template x-for="(row, index) in pagedData" :key="row.kat_row + '-' + row.id">
                                    <tr class="hover:bg-slate-50/50 transition-all"
                                        :class="row.status !== 'pending' ? 'opacity-60 bg-slate-50/30' : ''">
                                        <td class="p-8">
                                            <div class="flex flex-col gap-1.5">
                                                <div class="text-slate-900 font-black text-[15px] leading-tight"
                                                    x-text="row.name"></div>
                                                <div
                                                    class="text-[10px] text-blue-600 uppercase font-black tracking-widest flex items-center">
                                                    <i class="fas fa-map-marker-alt mr-2 opacity-50"></i> <span
                                                        x-text="row.location"></span>
                                                </div>
                                                <div class="mt-2 text-[9px] font-black flex items-center gap-2">
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

                                        <td class="p-8">
                                            <span
                                                class="inline-block px-4 py-2 rounded-2xl text-[10px] font-black uppercase tracking-widest border-2"
                                                :class="row.kat_row === 'AKTIVASI' ? 'border-blue-100 bg-blue-50 text-blue-700' : (row.kat_row === 'UPDATE' ? 'border-emerald-100 bg-emerald-50 text-emerald-700' : 'border-indigo-100 bg-indigo-50 text-indigo-700')"
                                                x-text="row.display_kat">
                                            </span>
                                        </td>

                                        <td class="p-8">
                                            <template x-if="row.foto_ktp">
                                                <div class="relative group w-20 h-12 overflow-hidden rounded-2xl border-2 border-slate-200 cursor-pointer shadow-sm hover:border-blue-500 transition-all"
                                                    @click="modalImage = row.foto_ktp; modalType = row.display_kat; showModal = true">
                                                    <img :src="row.foto_ktp" class="w-full h-full object-cover">
                                                    <div
                                                        class="absolute inset-0 bg-blue-600/20 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                                        <i class="fas fa-expand text-white"></i>
                                                    </div>
                                                </div>
                                            </template>
                                            <template x-if="!row.foto_ktp">
                                                <span
                                                    class="text-slate-300 text-[10px] font-black tracking-widest italic">KOSONG</span>
                                            </template>
                                        </td>

                                        <td class="p-8">
                                            <div
                                                class="text-[13px] text-slate-700 font-bold mb-3 leading-relaxed bg-slate-50 border border-slate-200 p-5 rounded-2xl shadow-inner min-h-[60px]">
                                                <i class="fas fa-quote-left text-blue-200 mb-1 block"></i>
                                                <span x-text="row.alasan || row.keterangan || row.deskripsi || '-'"
                                                    class="whitespace-pre-line"></span>
                                            </div>
                                            <div
                                                class="text-[10px] text-slate-400 font-black uppercase flex items-center tracking-widest">
                                                <i class="far fa-clock mr-2 text-blue-500"></i>
                                                <span x-text="row.date_human"></span> • <span x-text="row.time"></span>
                                                <span class="ml-1 text-[8px] opacity-70">(WIB)</span>
                                            </div>
                                        </td>

                                        <td class="p-8">
                                            <template x-if="row.status === 'ditolak'">
                                                <div class="flex flex-col gap-2">
                                                    <div class="bg-red-50 border border-red-100 rounded-2xl p-4">
                                                        <p class="text-[11px] text-red-800 font-black leading-snug"
                                                            x-text="row.tanggapan"></p>
                                                    </div>
                                                    <button @click="$el.nextElementSibling.classList.toggle('hidden')"
                                                        class="text-left text-[9px] text-red-600 font-black underline uppercase tracking-widest">Edit
                                                        Alasan</button>
                                                    <div
                                                        class="hidden mt-1 p-4 bg-white rounded-2xl shadow-xl border border-slate-200">
                                                        <form :action="row.url_respon" method="POST">
                                                            <input type="hidden" name="_token" :value="row.csrf">
                                                            <input type="hidden" name="laporan_id" :value="row.id">
                                                            <input type="hidden" name="type"
                                                                :value="row.kat_row.toLowerCase()">
                                                            <textarea name="admin_note" rows="2"
                                                                class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2 px-3 text-xs font-bold focus:ring-1 focus:ring-red-500 outline-none resize-none"
                                                                x-text="row.tanggapan"></textarea>
                                                            <button type="submit"
                                                                class="w-full mt-2 bg-red-600 text-white py-2 rounded-xl text-[10px] font-black uppercase">Simpan</button>
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
                                                        class="w-full bg-white border border-slate-200 rounded-2xl py-3 px-4 text-[11px] font-bold focus:ring-2 focus:ring-orange-500 outline-none resize-none transition-all shadow-sm"
                                                        required x-text="row.tanggapan"></textarea>
                                                    <button type="submit"
                                                        class="w-full text-white py-3 rounded-2xl text-[10px] font-black uppercase shadow-lg transition-all"
                                                        :class="row.tanggapan ? 'bg-slate-800 shadow-slate-200 hover:bg-black' : 'bg-orange-500 shadow-orange-200 hover:bg-orange-600'">
                                                        <span
                                                            x-text="row.tanggapan ? 'Update Tanggapan' : 'Kirim Verifikasi'"></span>
                                                    </button>
                                                </form>
                                            </template>
                                        </td>

                                        <td class="p-8 text-right">
                                            <div class="flex flex-col items-center justify-center gap-3">
                                                <template x-if="row.status === 'pending'">
                                                    <form :action="row.url_tolak" method="POST"
                                                        onsubmit="return confirm('Apakah Anda yakin ingin MENOLAK permohonan ini?')"
                                                        class="w-full">
                                                        <input type="hidden" name="_token" :value="row.csrf">
                                                        <input type="hidden" name="type"
                                                            :value="row.kat_row.toLowerCase()">
                                                        <input type="hidden" name="admin_note"
                                                            value="Data NIK tidak sinkron atau dokumen pendukung tidak jelas.">
                                                        <button type="submit"
                                                            class="group flex items-center justify-center w-full bg-red-50 hover:bg-red-600 text-red-600 hover:text-white px-4 py-2.5 rounded-xl border border-red-200 hover:border-red-600 transition-all duration-300 shadow-sm">
                                                            <i
                                                                class="fas fa-times-circle mr-2 text-[10px] group-hover:scale-110 transition-transform"></i>
                                                            <span
                                                                class="text-[10px] font-black uppercase tracking-wider">Tolak
                                                                Data</span>
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
                                                        class="group flex items-center justify-center w-full bg-slate-50 hover:bg-slate-900 text-slate-500 hover:text-white px-4 py-2.5 rounded-xl border border-slate-200 hover:border-slate-900 transition-all duration-300">
                                                        <i
                                                            class="fas fa-trash-alt mr-2 text-[10px] group-hover:shake transition-transform"></i>
                                                        <span
                                                            class="text-[10px] font-black uppercase tracking-wider">Hapus</span>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                </template>

                                <tr x-show="filteredData.length === 0">
                                    <td colspan="6" class="p-32 text-center">
                                        <div class="flex flex-col items-center justify-center opacity-20">
                                            <i class="fas fa-folder-open text-7xl mb-6"></i>
                                            <span class="text-2xl font-black uppercase tracking-[0.4em]">Data
                                                Kosong</span>
                                            <p class="mt-2 font-bold text-slate-500 uppercase text-xs">Tidak ada
                                                data
                                                yang sesuai filter</p>
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
    filterType: '', 
    filterMonth: '{{ now()->format('m') }}',
    filterStatus: '',
    filterKecamatan: '',
    imgModal: false,
    imgModalSrc: '',
    imgModalKategori: '',
    isLoading: false,

    {{-- FUNGSI UNTUK UPDATE KONTEN TANPA RELOAD --}}
    async updateContent(url) {
        this.isLoading = true;
        try {
            {{-- Tambahkan state filter saat ini ke URL request --}}
            const currentUrl = new URL(url);
            if (this.filterStatus) currentUrl.searchParams.set('status', this.filterStatus);
            if (this.filterType) currentUrl.searchParams.set('type', this.filterType);
            if (this.filterKecamatan) currentUrl.searchParams.set('kecamatan', this.filterKecamatan);
            if (this.filterMonth) currentUrl.searchParams.set('month', this.filterMonth);
            currentUrl.searchParams.set('tab', 'laporan_sistem');

            const response = await fetch(currentUrl.href);
            const text = await response.text();
            const parser = new DOMParser();
            const html = parser.parseFromString(text, 'text/html');
            const newTable = html.querySelector('#ajax-laporan-wrapper').innerHTML;
            
            document.querySelector('#ajax-laporan-wrapper').innerHTML = newTable;
            
            {{-- Update URL tanpa refresh untuk mempertahankan state --}}
            window.history.pushState({}, '', currentUrl.href);
        } catch (e) {
            console.error('Gagal memuat data:', e);
        }
        this.isLoading = false;
    },

    init() {
        {{-- Ambil status awal dari URL jika ada --}}
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('status')) this.filterStatus = urlParams.get('status');

        {{-- Scroll ke tabel jika URL mengandung page --}}
        if (urlParams.has('page') && urlParams.get('tab') === 'laporan_sistem') {
            this.$nextTick(() => {
                this.$el.scrollIntoView({ behavior: 'smooth', block: 'start' });
            });
        }

        {{-- WATCHER: Triger update saat dropdown status berubah --}}
        this.$watch('filterStatus', () => {
            const baseUrl = window.location.protocol + '//' + window.location.host + window.location.pathname;
            this.updateContent(baseUrl);
        });

        {{-- Intercept klik pada paginasi --}}
        document.addEventListener('click', (e) => {
            const link = e.target.closest('.pagination-wrapper a');
            if (link && document.querySelector('#ajax-laporan-wrapper').contains(link)) {
                e.preventDefault();
                this.updateContent(link.href);
            }
        });
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
                    class="relative bg-white rounded-[2.5rem] shadow-md border border-slate-200 overflow-hidden">

                    {{-- OVERLAY LOADING --}}
                    <div x-show="isLoading"
                        class="absolute inset-0 z-50 bg-white/60 backdrop-blur-[2px] flex items-center justify-center">
                        <div class="flex flex-col items-center">
                            <div
                                class="w-12 h-12 border-4 border-slate-200 border-t-orange-500 rounded-full animate-spin">
                            </div>
                            <p class="mt-4 text-[10px] font-black text-slate-500 uppercase tracking-widest">
                                Memproses
                                Data...</p>
                        </div>
                    </div>

                    {{-- LOGIKA PAGINASI & PENGURUTAN --}}
                    @php
                        \Carbon\Carbon::setLocale('id');

                        // PERBAIKAN: Memastikan hanya mengambil tipe yang ada di dropdown kategori
                        // dan mengecualikan data yang wilayahnya LUAR DAERAH/LUAR WILAYAH jika itu syaratnya
                        $laporanSistem = $sortedLaporans->whereIn('type', ['trouble', 'proxy', 'pembubuhan', 'pengajuan'])
                            ->filter(function ($item) {
                                $wilayah = strtoupper($item->wilayah ?? '');
                                // Saring agar data 'LUAR DAERAH' atau 'LUAR WILAYAH' tidak masuk ke list sistem ini
                                return !str_contains($wilayah, 'LUAR');
                            });

                        // Filter status di sisi Server (PHP) untuk memastikan paginasi akurat
                        if (request('status')) {
                            $laporanSistem = $laporanSistem->filter(function ($item) {
                                $currStatus = $item->is_rejected ? 'ditolak' : (!empty($item->tanggapan_admin) ? 'selesai' : 'pending');
                                return $currStatus == request('status');
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
                    <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                        <div class="flex flex-col gap-6">
                            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                                <div>
                                    <h3 class="font-black text-slate-800 uppercase text-lg italic tracking-tight">
                                        Monitoring Kendala Sistem</h3>
                                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">
                                        Urutan: Prioritas Terlama & Belum Diproses</p>
                                </div>
                            </div>

                            {{-- Baris Filter --}}
                            <div class="flex flex-wrap items-center gap-3 w-full">
                                <div class="relative flex-1 min-w-[140px]">
                                    <select x-model="filterMonth"
                                        class="w-full bg-white border-2 border-slate-200 rounded-xl px-3 py-2.5 text-[10px] font-black uppercase text-slate-600 focus:border-orange-500 appearance-none cursor-pointer shadow-sm">
                                        <option value="">Semua Bulan</option>
                                        @foreach(['01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'] as $val => $label)
                                            <option value="{{ $val }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="relative flex-1 min-w-[150px]">
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

                                <div class="relative flex-1 min-w-[150px]">
                                    <select x-model="filterType"
                                        class="w-full bg-white border-2 border-slate-200 rounded-xl px-3 py-2.5 text-[10px] font-black uppercase text-slate-600 focus:border-orange-500 appearance-none cursor-pointer shadow-sm">
                                        <option value="">Semua Kategori</option>
                                        <option value="trouble">🔴 Trouble (Sistem)</option>
                                        <option value="pengajuan">🟢 Kendala SIAK</option>
                                        <option value="pembubuhan">🔵 Pembubuhan TTE</option>
                                        <option value="proxy">🟣 Masalah Proxy</option>
                                    </select>
                                </div>

                                <div class="relative flex-1 lg:flex-none">
                                    <select x-model="filterStatus"
                                        class="w-full bg-white border-2 border-slate-200 rounded-2xl px-4 py-3 text-[10px] font-black uppercase text-slate-600 focus:border-orange-500 focus:ring-0 appearance-none cursor-pointer min-w-[130px] shadow-sm">
                                        <option value="">Semua Status</option>
                                        <option value="pending">⏳ Menunggu</option>
                                        <option value="selesai">✅ Selesai</option>
                                    </select>
                                </div>

                                {{-- PERBAIKAN LINK EKSPOR EXCEL --}}
                                <a :href="'{{ route('admin.laporan.export') }}?category=sistem_terpadu' + 
                        '&export_time={{ now()->timezone('Asia/Jakarta')->format('Y-m-d_H-i-s') }}' +
                        (filterType ? '&type=' + filterType : '') + 
                        (filterMonth ? '&month=' + filterMonth : '') + 
                        (filterStatus ? '&status=' + filterStatus : '') + 
                        (filterKecamatan ? '&kecamatan=' + filterKecamatan : '')"
                                    class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2.5 rounded-xl text-[10px] font-black shadow-md transition-all flex items-center justify-center hover:scale-105 active:scale-95">
                                    <i class="fas fa-file-excel mr-2 text-sm"></i> EKSPOR EXCEL
                                </a>
                            </div>

                            <div
                                class="flex justify-between items-center bg-slate-100/50 p-2 rounded-xl border border-slate-200">
                                <span
                                    class="text-[9px] font-black text-slate-500 ml-2 uppercase tracking-tighter">Halaman
                                    {{ $displayLaporans->currentPage() }} dari
                                    {{ $displayLaporans->lastPage() }}</span>
                                <div class="pagination-wrapper scale-90">
                                    {{ $displayLaporans->links() }}
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- AREA TABEL --}}
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse min-w-[1000px]">
                            <thead
                                class="bg-slate-50 text-slate-400 text-[10px] uppercase font-black tracking-widest border-b border-slate-100">
                                <tr>
                                    <th class="p-6">Pengguna & Wilayah</th>
                                    <th class="p-6">Layanan & Kategori</th>
                                    <th class="p-6 text-center">Bukti Foto</th>
                                    <th class="p-6">Detail Kendala</th>
                                    <th class="p-6 text-orange-500">Respon Admin</th>
                                    <th class="p-6 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm font-bold text-slate-600 divide-y divide-slate-100">
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
                                                $labelLayanan = 'Kategori';
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

                                    <tr x-show="shouldShow('{{ $l->type }}', '{{ $createdAt->format('Y-m-d') }}', '{{ $status }}', '{{ $namaKecamatan }}')"
                                        x-transition:enter="transition ease-out duration-300"
                                        class="hover:bg-slate-50/80 transition-all border-b border-slate-100 {{ $status !== 'pending' ? 'opacity-75 grayscale-[0.5]' : '' }}">

                                        <td class="p-6">
                                            <div class="flex flex-col gap-2">
                                                <div class="flex flex-wrap gap-1.5">
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
                                                    @if($status === 'selesai') <span
                                                        class="px-2 py-0.5 rounded text-[9px] font-black bg-emerald-500 text-white">✅
                                                        SELESAI</span>
                                                    @elseif($status === 'ditolak') <span
                                                        class="px-2 py-0.5 rounded text-[9px] font-black bg-red-500 text-white">❌
                                                        DITOLAK</span>
                                                    @else <span
                                                        class="px-2 py-0.5 rounded text-[9px] font-black bg-orange-400 text-white animate-pulse">⏳
                                                        MENUNGGU</span>
                                                    @endif
                                                </div>
                                                <div>
                                                    <div class="text-slate-900 font-black text-base italic leading-tight">
                                                        {{ $l->user_name }}
                                                    </div>
                                                    <div
                                                        class="text-[10px] text-blue-600 uppercase font-black flex items-center mt-1">
                                                        <i class="fas fa-map-marker-alt mr-1.5"></i>
                                                        {{ $namaKecamatan }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                        <td class="p-6">
                                            <div class="flex flex-col">
                                                <span
                                                    class="text-[9px] text-slate-400 uppercase font-bold tracking-widest mb-1">{{ $labelLayanan }}:</span>
                                                <span
                                                    class="text-xs font-black text-slate-700 uppercase break-words leading-tight">{{ $isiLayanan }}</span>
                                            </div>
                                        </td>

                                        <td class="p-6 text-center">
                                            <div class="flex justify-center -space-x-3">
                                                @forelse($images as $img)
                                                    <img src="{{ asset('storage/' . $img) }}"
                                                        @click="imgModal = true; imgModalSrc = '{{ asset('storage/' . $img) }}'; imgModalKategori = '{{ strtoupper($l->type) }}'"
                                                        class="w-14 h-14 object-cover rounded-xl cursor-zoom-in border-4 border-white shadow-md hover:z-20 hover:scale-125 transition-all">
                                                @empty
                                                    <div
                                                        class="w-14 h-14 mx-auto rounded-xl bg-slate-50 border-2 border-dashed border-slate-200 flex items-center justify-center text-slate-300">
                                                        <i class="fas fa-image text-xs"></i>
                                                    </div>
                                                @endforelse
                                            </div>
                                        </td>

                                        <td class="p-6">
                                            <div
                                                class="text-[11px] text-slate-600 font-bold italic mb-3 leading-relaxed bg-slate-50 p-3 rounded-2xl border-l-4 border-slate-200">
                                                "{{ $l->pesan }}"
                                            </div>
                                            <div
                                                class="text-[10px] text-slate-500 font-black uppercase flex items-center gap-2">
                                                <i class="far fa-clock text-blue-500"></i>
                                                <span>{{ $createdAt->translatedFormat('d F Y, H:i') }} WIB</span>
                                            </div>
                                        </td>

                                        <td class="p-6 min-w-[220px]">
                                            @if($status !== 'ditolak')
                                                <form action="{{ route('admin.laporan.respon') }}" method="POST"
                                                    class="flex flex-col gap-2.5">
                                                    @csrf
                                                    <input type="hidden" name="laporan_id" value="{{ $l->id }}">
                                                    <input type="hidden" name="type" value="{{ $l->type }}">
                                                    <textarea name="admin_note" rows="2"
                                                        class="w-full bg-white border border-slate-200 rounded-2xl py-2.5 px-4 text-[11px] font-bold focus:border-emerald-500 focus:ring-0 resize-none transition-all shadow-sm"
                                                        required>{{ $l->tanggapan_admin }}</textarea>
                                                    <button type="submit"
                                                        class="w-full {{ empty($l->tanggapan_admin) ? 'bg-orange-500' : 'bg-slate-800' }} text-white py-2.5 rounded-xl text-[10px] font-black uppercase transition-all shadow-sm hover:opacity-90 active:scale-95">
                                                        {{ empty($l->tanggapan_admin) ? '🚀 Kirim Respon' : '🔄 Perbarui Solusi' }}
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

                                        <td class="p-6 text-center">
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
                                    <tr>
                                        <td colspan="6" class="p-20 text-center">
                                            <div class="flex flex-col items-center justify-center opacity-20">
                                                <i class="fas fa-shield-alt text-6xl mb-4"></i>
                                                <span class="text-sm font-black uppercase tracking-widest">Tidak ada
                                                    data
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
                        <div class="bg-white p-1.5 rounded-2xl shadow-2xl">
                            <img :src="imgModalSrc"
                                class="w-full h-auto max-h-[80vh] object-contain rounded-xl shadow-inner bg-slate-50">
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
    </div>
    </main>
    </div>

</body>

</html>