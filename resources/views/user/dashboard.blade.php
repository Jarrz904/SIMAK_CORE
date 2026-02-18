<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Portal - SIMAK SYSTEM</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap');

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            overflow-x: hidden;
        }

        [x-cloak] {
            display: none !important;
        }

        ::-webkit-scrollbar {
            width: 5px;
        }

        ::-webkit-scrollbar-track {
            background: #0f172a;
        }

        ::-webkit-scrollbar-thumb {
            background: #ea580c;
            border-radius: 10px;
        }
    </style>
</head>

<body class="bg-slate-50 min-h-screen flex flex-col" x-data="{ tab: 'dashboard', mobileMenu: false }">

    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
            class="fixed bottom-5 right-5 left-5 md:left-auto z-[70] bg-green-600 text-white px-6 py-4 rounded-2xl shadow-2xl flex items-center justify-between space-x-3 border-b-4 border-green-800">
            <div class="flex items-center space-x-3">
                <i class="fas fa-check-circle text-xl"></i>
                <div>
                    <p class="text-[10px] font-black uppercase tracking-widest">Sistem Berhasil</p>
                    <p class="text-xs font-bold">{{ session('success') }}</p>
                </div>
            </div>
            <button @click="show = false" class="opacity-50 hover:opacity-100"><i class="fas fa-times"></i></button>
        </div>
    @endif

    <nav class="bg-slate-900 text-white p-4 shadow-lg sticky top-0 z-50 border-b-2 border-orange-600">
        <div class="w-full px-2 md:px-6 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <button @click="mobileMenu = !mobileMenu" class="md:hidden text-orange-500 text-xl focus:outline-none">
                    <i class="fas" :class="mobileMenu ? 'fa-times' : 'fa-bars'"></i>
                </button>
                <i class="fas fa-fingerprint text-orange-500 text-xl"></i>
                <span class="font-black tracking-tighter uppercase text-lg">SIMAK <span
                        class="text-orange-500">USER</span></span>
            </div>

            <div class="flex items-center space-x-4">
                <div class="hidden sm:flex flex-col items-end mr-4">
                    <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest text-right">User
                        Status</span>
                    <span class="text-[10px] text-green-400 font-black">● ONLINE</span>
                </div>

                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit"
                        class="text-slate-500 hover:text-red-600 text-[10px] md:text-xs font-black tracking-[0.2em] uppercase transition-all duration-300 active:scale-95">
                        Keluar
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <div class="flex flex-col md:flex-row gap-0 flex-grow w-full relative">

        <aside :class="mobileMenu ? 'translate-x-0' : '-translate-x-full md:translate-x-0'"
            class="fixed md:relative z-40 w-72 md:w-80 bg-slate-900 h-screen md:h-auto overflow-y-auto p-6 text-slate-300 transition-transform duration-300 ease-in-out border-r border-slate-800">

            <div class="mb-8 p-4 bg-slate-800/50 rounded-2xl border border-slate-700">
                <div class="flex items-center space-x-3">
                    <div
                        class="w-10 h-10 bg-orange-600 rounded-xl flex items-center justify-center text-white font-black shrink-0">
                        {{ substr($user->name ?? 'A', 0, 1) }}
                    </div>
                    <div class="overflow-hidden">
                        <p class="text-xs font-black text-white truncate italic">{{ $user->name ?? 'Ahmad Fauzi' }}</p>
                        <p class="text-[9px] text-slate-500 font-mono uppercase tracking-tighter">ID:
                            {{ substr($user->nik ?? '0000000000000000', 0, 8) }}...
                        </p>
                    </div>
                </div>
            </div>

            <div class="space-y-1">
                <p class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-4 ml-2">Main Control</p>

                <button @click="tab = 'dashboard'; mobileMenu = false"
                    :class="tab === 'dashboard' ? 'bg-orange-600 text-white shadow-lg shadow-orange-600/20' : 'hover:bg-slate-800'"
                    class="w-full flex items-center space-x-3 p-4 rounded-2xl transition-all text-sm font-bold text-left">
                    <i class="fas fa-th-large w-5 text-center"></i> <span>Dashboard</span>
                </button>

                <button @click="tab = 'edit_profil'; mobileMenu = false"
                    :class="tab === 'edit_profil' ? 'bg-orange-600 text-white shadow-lg' : 'hover:bg-slate-800'"
                    class="w-full flex items-center space-x-3 p-4 rounded-2xl transition-all text-sm font-bold text-left">
                    <i class="fas fa-user-edit w-5 text-center"></i> <span>Edit Profil</span>
                </button>

                <div class="pt-6">
                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-4 ml-2">Layanan SIAK
                    </p>
                    <button @click="tab = 'registrasi'; mobileMenu = false"
                        :class="tab === 'registrasi' ? 'bg-orange-600 text-white shadow-lg' : 'hover:bg-slate-800'"
                        class="w-full flex items-center space-x-3 p-4 rounded-2xl transition-all text-sm font-bold text-left">
                        <i class="fas fa-plus-circle w-5 text-center"></i> <span>Kendala SIAK</span>
                    </button>
                    <button @click="tab = 'pembubuhan'; mobileMenu = false"
                        :class="tab === 'pembubuhan' ? 'bg-orange-600 text-white shadow-lg' : 'hover:bg-slate-800'"
                        class="w-full flex items-center space-x-3 p-4 rounded-2xl transition-all text-sm font-bold text-left">
                        <i class="fas fa-file-signature w-5 text-center"></i> <span>Pembubuhan TTE</span>
                    </button>
                    <button @click="tab = 'luardaerah'; mobileMenu = false"
                        :class="tab === 'luardaerah' ? 'bg-orange-600 text-white shadow-lg' : 'hover:bg-slate-800'"
                        class="w-full flex items-center space-x-3 p-4 rounded-2xl transition-all text-sm font-bold text-left">
                        <i class="fas fa-map-marked-alt w-5 text-center"></i>
                        <span>Luar Daerah</span>
                    </button>
                    <button @click="tab = 'aktivasi'; mobileMenu = false"
                        :class="tab === 'aktivasi' ? 'bg-orange-600 text-white shadow-lg' : 'hover:bg-slate-800'"
                        class="w-full flex items-center space-x-3 p-4 rounded-2xl transition-all text-sm font-bold text-left">
                        <i class="fas fa-id-card w-5 text-center"></i> <span>Aktivasi NIK</span>
                    </button>
                    <button @click="tab = 'update_data'; mobileMenu = false"
                        :class="tab === 'update_data' ? 'bg-orange-600 text-white shadow-lg' : 'hover:bg-slate-800'"
                        class="w-full flex items-center space-x-3 p-4 rounded-2xl transition-all text-sm font-bold text-left">
                        <i class="fas fa-user-edit w-5 text-center"></i>
                        <span>Update Data</span>
                    </button>
                </div>

                <div class="pt-6">
                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-4 ml-2">Pusat Bantuan
                    </p>
                    <button @click="tab = 'proxy'; mobileMenu = false"
                        :class="tab === 'proxy' ? 'bg-indigo-600 text-white shadow-lg' : 'hover:bg-slate-800'"
                        class="w-full flex items-center space-x-3 p-4 rounded-2xl transition-all text-sm font-bold text-left">
                        <i class="fas fa-shield-alt w-5 text-center"></i> <span>Lapor Proxy</span>
                    </button>

                    <button @click="tab = 'trouble'; mobileMenu = false"
                        :class="tab === 'trouble' ? 'bg-red-600 text-white shadow-lg' : 'hover:bg-slate-800'"
                        class="w-full flex items-center space-x-3 p-4 rounded-2xl transition-all text-sm font-bold text-left">
                        <i class="fas fa-bug w-5 text-center"></i> <span>Sistem Trouble</span>
                    </button>
                </div>
            </div>
        </aside>

        <div x-show="mobileMenu" @click="mobileMenu = false" x-transition:enter="transition opacity-0"
            x-transition:enter-end="opacity-100" class="fixed inset-0 bg-black/50 z-30 md:hidden"></div>

        <main class="flex-1 p-4 md:p-8 w-full overflow-x-hidden">

            <div x-data="{ 
                    showDetail: false, 
                    selectedNotif: { id: null, kategori: '', pesan: '', tanggapan: '', full_date: '', update_date: '', icon: '', color: '', has_response: false },
                    readNotifications: JSON.parse(localStorage.getItem('read_notifs') || '[]'),
                    
                    get unreadCount() {
                        let count = 0;
                        @foreach($allNotifications as $n)
                            @php 
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    $nId = $n->id ?? ($n->_id ?? null);
                                $tanggapanAdmin = $n->tanggapan_admin ?? ($n->tanggapan ?? null);
                            @endphp
                            @if(!empty($tanggapanAdmin) && $nId)
                                if (!this.readNotifications.includes('{{ $nId }}')) {
                                    count++;
                                }
                            @endif
                        @endforeach
                        return count;
                    },

                    markAsRead(id) {
                        if (id && id !== 'null' && !this.readNotifications.includes(id.toString())) {
                            this.readNotifications.push(id.toString());
                            this.readNotifications = [...new Set(this.readNotifications)];
                            localStorage.setItem('read_notifs', JSON.stringify(this.readNotifications));
                        }
                    }
                }" class="relative">



                {{-- Fitur Dashboard User --}}
                <div x-show="tab === 'dashboard'" x-transition x-cloak
                    class="max-w-7xl mx-auto p-4 md:p-6 space-y-6 -mt-4">

                    <div
                        class="bg-slate-800 rounded-[2rem] p-6 text-white relative overflow-hidden shadow-xl border-b-4 border-orange-600">
                        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <div class="flex items-center gap-4">
                                <div
                                    class="hidden md:flex w-12 h-12 bg-orange-600 rounded-2xl items-center justify-center text-xl shadow-lg shadow-orange-600/20">
                                    <i class="fas fa-user-shield"></i>
                                </div>
                                <div>
                                    <h1 class="text-xl md:text-2xl font-black italic tracking-tight">Halo,
                                        {{ $user->name ?? 'User' }}!
                                    </h1>
                                    <p class="text-slate-400 text-[10px] uppercase tracking-widest mt-1">Status: <span
                                            class="text-green-400 font-black italic">Pengguna Aktif</span></p>
                                </div>
                            </div>
                            <div class="flex gap-4 border-l border-white/10 pl-4">
                                <div class="bg-white/5 p-3 rounded-2xl backdrop-blur-sm">
                                    <p class="text-[8px] text-slate-400 font-black uppercase tracking-tighter">NIK
                                        Terdaftar</p>
                                    <p class="text-xs font-bold tracking-widest">{{ $user->nik ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="absolute -right-6 -bottom-6 text-white/5 text-8xl rotate-12 pointer-events-none">
                            <i class="fas fa-id-card"></i>
                        </div>
                    </div>

                    @php
                        // Hitung notifikasi yang belum ditanggapi
                        $pendingCount = $allNotifications->filter(function ($item) {
                            return empty($item->tanggapan_admin) && empty($item->tanggapan);
                        })->count();
                    @endphp
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-indigo-900 rounded-[2rem] p-8 text-white relative overflow-hidden group">
                            @php
                                // Mengambil data pengumuman terbaru dari database
                                $latestAnnouncement = \App\Models\Announcement::latest()->first();
                            @endphp

                            <div class="relative z-10">
                                @if($latestAnnouncement)
                                    {{-- TAMPILAN SAAT ADMIN SUDAH MENGIRIM PENGUMUMAN --}}
                                    <h4
                                        class="text-lg font-black uppercase tracking-widest mb-3 flex items-center gap-3 text-orange-400">
                                        <i class="fas fa-bullhorn animate-bounce"></i> {{ $latestAnnouncement->title }}
                                    </h4>

                                    <p class="text-sm md:text-base leading-relaxed opacity-95 font-semibold">
                                        {{ $latestAnnouncement->message }}
                                    </p>

                                    <div class="mt-4 flex items-center gap-2">
                                        <div class="h-[1px] flex-1 bg-white/20"></div>
                                        <span class="text-[10px] font-mono text-indigo-300 uppercase font-bold">
                                            Update: {{ $latestAnnouncement->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                @else
                                    {{-- TAMPILAN KOSONGAN (DEFAULT) --}}
                                    <h4
                                        class="text-lg font-black uppercase tracking-widest mb-3 flex items-center gap-3 text-indigo-300">
                                        <i class="fas fa-info-circle"></i> Info Layanan
                                    </h4>

                                    <p class="text-sm md:text-base leading-relaxed opacity-60 font-medium italic">
                                        Belum ada informasi atau pengumuman terbaru dari admin untuk saat ini.
                                    </p>

                                    <div class="mt-4 flex items-center gap-2">
                                        <div class="h-[1px] flex-1 bg-white/10"></div>
                                        <span class="text-[10px] font-mono text-indigo-300 uppercase font-bold">Sistem
                                            Aktif</span>
                                    </div>
                                @endif
                            </div>

                            {{-- Ikon Dekorasi --}}
                            <i
                                class="fas fa-lightbulb absolute -right-4 -top-4 text-indigo-800 text-8xl rotate-12 group-hover:scale-110 transition-transform duration-500 opacity-50"></i>
                        </div>
                        <div x-data="{ 
                                        currentTime: '', 
                                        currentDate: '', 
                                        updateClock() {
                                            const now = new Date();
                                            const hours = String(now.getHours()).padStart(2, '0');
                                            const minutes = String(now.getMinutes()).padStart(2, '0');
                                            this.currentTime = `${hours}:${minutes} WIB`;
                                            
                                            this.currentDate = now.toLocaleDateString('id-ID', { 
                                                weekday: 'long', 
                                                day: 'numeric', 
                                                month: 'short', 
                                                year: 'numeric' 
                                            });
                                        }
                                    }" x-init="updateClock(); setInterval(() => updateClock(), 1000)"
                            class="bg-gradient-to-br from-orange-50 to-white border-2 border-orange-100 rounded-[1.5rem] md:rounded-[2rem] p-4 md:p-6 group transition-all duration-300 hover:shadow-lg hover:border-orange-200 relative overflow-hidden w-full">
                            <div
                                class="absolute -right-2 -top-2 w-16 h-16 bg-orange-100/50 rounded-full blur-2xl group-hover:bg-orange-200/50 transition-colors">
                            </div>

                            <div class="relative z-10">
                                <div class="flex items-center justify-between mb-4">
                                    <h4
                                        class="text-[10px] md:text-sm font-black uppercase tracking-widest flex items-center gap-2 text-orange-600">
                                        <i class="fas fa-calendar-alt"></i> Info Waktu
                                    </h4>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-4">
                                    <div
                                        class="bg-white/80 backdrop-blur-sm p-3 rounded-xl md:rounded-2xl border border-orange-100 shadow-sm transition-transform group-hover:scale-[1.02] flex sm:flex-col items-center sm:items-start justify-between sm:justify-start">
                                        <p
                                            class="text-[7px] font-black text-orange-400 uppercase tracking-tighter sm:mb-1">
                                            Jam Sekarang</p>
                                        <div class="flex items-center gap-2">
                                            <i class="fas fa-clock text-orange-600 text-xs"></i>
                                            <p class="text-sm md:text-base font-black text-slate-800 tracking-tighter"
                                                x-text="currentTime"></p>
                                        </div>
                                    </div>

                                    <div
                                        class="bg-slate-800 p-3 rounded-xl md:rounded-2xl border border-slate-700 shadow-sm text-white transition-transform group-hover:scale-[1.02] flex sm:flex-col items-center sm:items-start justify-between sm:justify-start">
                                        <p
                                            class="text-[7px] font-black text-slate-400 uppercase tracking-tighter sm:mb-1">
                                            Kalender</p>
                                        <div class="flex items-center gap-2">
                                            <i class="fas fa-calendar-day text-orange-400 text-xs"></i>
                                            <p class="text-[10px] md:text-xs font-bold tracking-tight truncate"
                                                x-text="currentDate"></p>
                                        </div>
                                    </div>
                                </div>

                                <div
                                    class="p-3 bg-orange-100/30 rounded-xl border border-dashed border-orange-200 flex flex-col sm:flex-row justify-between items-center text-[8px] md:text-[9px] font-black uppercase tracking-tighter text-orange-600 gap-1 sm:gap-0 text-center sm:text-left">
                                    <span class="flex items-center gap-1">
                                        <i class="fas fa-history"></i> Jadwal Operasional
                                    </span>
                                    <span class="bg-orange-100 sm:bg-transparent px-2 py-0.5 rounded-full">
                                        Sen - Jum | 08:00 - 16:00
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <h2 class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] ml-2">
                                Monitor Layanan & Riwayat
                            </h2>
                            <div class="h-px flex-1 bg-slate-200 mx-4"></div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-10">
                            @php
                                // Pastikan $allNotifications tersedia
                                $notifications = collect($allNotifications ?? []);

                                $targetKategori = [
                                    'KENDALA SIAK' => [
                                        'label' => 'Kendala SIAK',
                                        'icon' => 'fa-database',
                                        'color' => 'text-purple-600',
                                        'bg' => 'bg-purple-50',
                                        'keywords' => ['SIAK', 'REGISTRASI SIAK', 'APLIKASI EROR SIAK', ' APLIKASI SIAK', 'GAGAL LOGIN SIAK']
                                    ],
                                    'PEMBUBUHAN' => [
                                        'label' => 'Pembubuhan TTE',
                                        'icon' => 'fa-file-signature',
                                        'color' => 'text-rose-600',
                                        'bg' => 'bg-rose-50',
                                        'keywords' => ['PEMBUBUHAN', 'AKTA KELAHIRAN', 'AKTA KEMATIAN', 'KARTU KELUARGA', 'SURAT KETERANGAN PINDAH', 'BIODATA WNI']
                                    ],
                                    'LUAR_CARD' => [
                                        'label' => 'Luar Daerah',
                                        'icon' => 'fa-map-marked-alt',
                                        'color' => 'text-blue-600',
                                        'bg' => 'bg-blue-50',
                                        'keywords' => ['PINDAH DATANG', 'PEMBARUAN DATA LUAR', 'KONSOLIDASI MANUAL', 'REKAM KTP', 'CETAK KTP']
                                    ],
                                    'AKTIVASI' => [
                                        'label' => 'Aktivasi NIK',
                                        'icon' => 'fa-id-card',
                                        'color' => 'text-blue-600',
                                        'bg' => 'bg-blue-50',
                                        'keywords' => ['AKTIVASI', 'RESTORE']
                                    ],
                                    'UPDATE DATA' => [
                                        'label' => 'Update Data',
                                        'icon' => 'fa-user-edit',
                                        'color' => 'text-cyan-600',
                                        'bg' => 'bg-cyan-50',
                                        'keywords' => ['PERUBAHAN NAMA', 'PERUBAHAN TANGGAL LAHIR', 'PERUBAHAN AGAMA', 'PERUBAHAN JENIS KELAMIN', 'PERUBAHAN GOLONGAN DARAH', 'PERUBAHAN ALAMAT']
                                    ],
                                    'PROXY' => [
                                        'label' => 'Laporan Proxy',
                                        'icon' => 'fa-server',
                                        'color' => 'text-indigo-600',
                                        'bg' => 'bg-indigo-50',
                                        'keywords' => ['PROXY']
                                    ],
                                    'TROUBLE' => [
                                        'label' => 'Aktivitas Trouble',
                                        'icon' => 'fa-exclamation-triangle',
                                        'color' => 'text-amber-600',
                                        'bg' => 'bg-amber-50',
                                        'keywords' => ['TROUBLE', 'KOMPUTER/PC', 'PERANGKAT PENDUKUNG', 'JARINGAN']
                                    ],
                                ];

                                if (!function_exists('cleanForJs')) {
                                    function cleanForJs($text)
                                    {
                                        if (empty($text))
                                            return '';
                                        $text = str_replace(["\r", "\n"], ' ', $text);
                                        return htmlspecialchars(addslashes(trim($text)), ENT_QUOTES, 'UTF-8');
                                    }
                                }
                            @endphp
                            @foreach($targetKategori as $key => $style)
                                @php
                                    // 1. Filter dan Urutkan: Prioritaskan status 'Proses', lalu berdasarkan waktu TERLAMA (Ascending) untuk Proses
                                    $riwayatKategori = $notifications->filter(function ($item) use ($key, $style) {
                                        $itemObj = (object) $item;
                                        $searchableText = strtoupper(
                                            ($itemObj->jenis_registrasi ?? '') . ' ' .
                                            ($itemObj->kategori ?? '') . ' ' .
                                            ($itemObj->jenis_permasalahan ?? '') . ' ' .
                                            ($itemObj->jenis_layanan ?? '') . ' ' .
                                            ($itemObj->jenis_dokumen ?? '') . ' ' .
                                            ($itemObj->deskripsi ?? '') . ' ' .
                                            ($itemObj->pesan ?? '') . ' ' .
                                            ($itemObj->keterangan ?? '')
                                        );

                                        foreach ($style['keywords'] as $word) {
                                            if (str_contains($searchableText, strtoupper($word))) {
                                                if ($key === 'DOKUMEN' && str_contains($searchableText, 'UPDATE DATA')) {
                                                    return false;
                                                }
                                                return true;
                                            }
                                        }
                                        return false;
                                    })->sort(function ($a, $b) {
                                        $a = (object) $a;
                                        $b = (object) $b;

                                        // Cek apakah sudah ada tanggapan
                                        $aHasResp = !empty(trim($a->tanggapan_admin ?? $a->tanggapan ?? ''));
                                        $bHasResp = !empty(trim($b->tanggapan_admin ?? $b->tanggapan ?? ''));

                                        // LOGIKA 1: Status Proses (Tanpa Tanggapan) selalu di atas Selesai
                                        if (!$aHasResp && $bHasResp)
                                            return -1;
                                        if ($aHasResp && !$bHasResp)
                                            return 1;

                                        // LOGIKA 2: Jika status sama
                                        $dateA = \Carbon\Carbon::parse($a->created_at ?? $a->tanggal ?? now())->timestamp;
                                        $dateB = \Carbon\Carbon::parse($b->created_at ?? $b->tanggal ?? now())->timestamp;

                                        if (!$aHasResp) {
                                            // Jika SESAMA PROSES: Urutkan dari yang TERLAMA (Ascending) agar antrian lama jadi Prioritas Utama
                                            return $dateA <=> $dateB;
                                        } else {
                                            // Jika SESAMA SELESAI: Urutkan dari yang TERBARU (Descending) agar hasil terakhir terlihat
                                            return $dateB <=> $dateA;
                                        }
                                    });

                                    // Ambil yang paling atas (Hasil sort: Proses Terlama atau Selesai Terbaru)
                                    $notif = $riwayatKategori->first() ? (object) $riwayatKategori->first() : null;

                                    $dokumenDisplay = '-';
                                    $tanggapanRaw = '';
                                    $hasResponse = false;
                                    $isRejected = false;
                                    $formattedKirim = '-';
                                    $formattedBalas = '';
                                    $diffForHumans = '';

                                    if ($notif) {
                                        $dokumenDisplay = $notif->jenis_dokumen ?? $notif->jenis_permasalahan ?? $notif->keterangan ?? $notif->jenis_layanan ?? '-';
                                        $tanggapanRaw = $notif->tanggapan_admin ?? ($notif->tanggapan ?? '');
                                        $hasResponse = !empty(trim($tanggapanRaw));

                                        if ($hasResponse) {
                                            $lowTanggapan = strtolower($tanggapanRaw);
                                            $isRejected = str_contains($lowTanggapan, 'tolak') ||
                                                str_contains($lowTanggapan, 'gagal') ||
                                                str_contains($lowTanggapan, 'salah') ||
                                                str_contains($lowTanggapan, 'tidak ditemukan') ||
                                                str_contains($lowTanggapan, 'tidak sinkron');
                                        }

                                        $dtKirim = \Carbon\Carbon::parse($notif->created_at ?? $notif->tanggal ?? now())->setTimezone('Asia/Jakarta');
                                        $formattedKirim = $dtKirim->translatedFormat('d F Y • H:i') . ' WIB';
                                        $diffForHumans = $dtKirim->diffForHumans();

                                        if ($hasResponse) {
                                            $dtBalas = \Carbon\Carbon::parse($notif->updated_at ?? $notif->created_at ?? now())->setTimezone('Asia/Jakarta');
                                            $formattedBalas = $dtBalas->translatedFormat('d F Y • H:i') . ' WIB';
                                        }
                                    }

                                    $statusLabel = $isRejected ? 'Ditolak' : ($hasResponse ? 'Selesai' : 'Proses');
                                    $cardBg = $notif ? ($hasResponse ? ($isRejected ? 'bg-red-50/50' : 'bg-emerald-50/50') : 'bg-white') : 'bg-slate-50/50';
                                    $borderColor = $notif ? ($hasResponse ? ($isRejected ? 'border-red-200' : 'border-emerald-200') : 'border-slate-200') : 'border-slate-100';
                                @endphp

                                <div class="flex flex-col gap-4">
                                    {{-- CARD UTAMA --}}
                                    <div @if($notif) @click="selectedNotif = { 
                                        id: '{{ $notif->id ?? rand(1, 999) }}',
                                        kategori: '{{ $style['label'] }}', 
                                        kategori_asli: '{{ cleanForJs($notif->kategori ?? $notif->jenis_registrasi ?? $key) }}',
                                        jenis_layanan: '{{ cleanForJs($notif->jenis_permasalahan ?? $notif->jenis_layanan ?? $style['label']) }}',
                                        jenis_dokumen: '{{ cleanForJs($dokumenDisplay) }}',
                                        pesan: '{{ cleanForJs($notif->deskripsi ?? $notif->pesan ?? 'Tidak ada pesan') }}', 
                                        tanggapan: '{{ $hasResponse ? cleanForJs($tanggapanRaw) : 'Sedang dalam verifikasi.' }}',
                                        full_date: '{{ $formattedKirim }}',
                                        update_date: '{{ $formattedBalas }}',
                                        has_response: {{ $hasResponse ? 'true' : 'false' }},
                                        icon: '{{ $style['icon'] }}',
                                        status: '{{ $statusLabel }}',
                                        color: '{{ $isRejected ? 'text-red-600' : ($hasResponse ? 'text-emerald-600' : $style['color']) }}'
                                    }; showDetail = true;" @endif
                                        class="group relative {{ $cardBg }} p-5 md:p-8 rounded-[1.8rem] md:rounded-[2.5rem] border-2 {{ $borderColor }} shadow-md transition-all duration-300 {{ $notif ? 'hover:shadow-2xl hover:-translate-y-2 cursor-pointer active:scale-95' : 'opacity-60 grayscale' }}">

                                        <div class="relative z-10">
                                            <div class="flex items-center justify-between mb-4 md:mb-6">
                                                <div
                                                    class="w-12 h-12 md:w-16 md:h-16 {{ $isRejected ? 'bg-red-100 text-red-600' : ($hasResponse ? 'bg-emerald-100 text-emerald-600' : $style['bg'] . ' ' . $style['color']) }} rounded-2xl md:rounded-[1.5rem] flex items-center justify-center text-2xl md:text-3xl shadow-sm transition-transform group-hover:scale-110">
                                                    <i
                                                        class="fas {{ $style['icon'] }} {{ $notif && !$hasResponse ? 'animate-bounce' : '' }}"></i>
                                                </div>
                                                @if($diffForHumans)
                                                    <span
                                                        class="text-[9px] md:text-[11px] font-extrabold text-slate-500 bg-white shadow-sm border border-slate-100 px-2 md:px-3 py-1 rounded-full uppercase tracking-wider">{{ $diffForHumans }}</span>
                                                @endif
                                            </div>

                                            <h3
                                                class="text-[11px] md:text-[13px] font-black text-slate-600 uppercase tracking-[0.15em] mb-2 md:mb-3">
                                                {{ $style['label'] }}
                                            </h3>

                                            <div class="min-h-[50px] md:min-h-[60px]">
                                                @if($hasResponse)
                                                    <p
                                                        class="{{ $isRejected ? 'text-red-800' : 'text-emerald-800' }} font-bold text-sm md:text-[15px] line-clamp-2 leading-relaxed">
                                                        {{ $tanggapanRaw }}
                                                    </p>
                                                @elseif($notif)
                                                    <p
                                                        class="text-slate-700 font-bold text-xs md:text-[14px] italic flex items-center gap-2 md:gap-3">
                                                        <span
                                                            class="flex h-2 w-2 md:h-3 md:w-3 rounded-full bg-amber-500 animate-pulse"></span>
                                                        Menunggu balasan...
                                                    </p>
                                                @else
                                                    <p
                                                        class="text-slate-400 font-semibold text-[11px] md:text-[13px] italic text-center py-2">
                                                        Belum ada aktivitas baru</p>
                                                @endif
                                            </div>

                                            <div
                                                class="mt-4 md:mt-6 flex items-center justify-between border-t-2 border-slate-50 pt-4 md:pt-5">
                                                @if($notif)
                                                    <div
                                                        class="px-3 py-1.5 md:px-4 md:py-2 {{ $isRejected ? 'bg-red-100 text-red-700' : ($hasResponse ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700') }} rounded-lg md:rounded-xl text-[9px] md:text-[10px] font-black uppercase flex items-center gap-2 shadow-sm">
                                                        <span
                                                            class="w-1.5 h-1.5 md:w-2 md:h-2 {{ $isRejected ? 'bg-red-500' : ($hasResponse ? 'bg-emerald-500' : 'bg-amber-500 animate-ping') }} rounded-full"></span>
                                                        {{ $statusLabel }}
                                                    </div>
                                                @endif
                                                <span class="text-[10px] md:text-[12px] font-black text-slate-400">
                                                    {{ $riwayatKategori->count() }} Laporan
                                                </span>
                                            </div>
                                        </div>

                                        <i
                                            class="fas {{ $style['icon'] }} absolute -right-2 -bottom-2 md:-right-4 md:-bottom-4 text-slate-900/[0.03] text-6xl md:text-8xl pointer-events-none group-hover:scale-125 group-hover:rotate-12 transition-all duration-500"></i>
                                    </div>

                                    {{-- RIWAYAT LIST --}}
                                    @if($riwayatKategori->count() > 1)
                                        <div
                                            class="bg-slate-100/50 rounded-[1.5rem] md:rounded-[2rem] p-2 md:p-3 border border-slate-200 space-y-2 max-h-[150px] md:max-h-[180px] overflow-y-auto scrollbar-hide shadow-inner mx-1 md:mx-2">
                                            @foreach($riwayatKategori->skip(1)->take(5) as $hist)
                                                            @php
                                                                $hist = (object) $hist;
                                                                $dtHistKirim = \Carbon\Carbon::parse($hist->created_at ?? $hist->tanggal ?? now())->setTimezone('Asia/Jakarta');
                                                                $histTanggapan = $hist->tanggapan_admin ?? $hist->tanggapan ?? '';
                                                                $histHasResp = !empty(trim($histTanggapan));
                                                                $histIsRejected = $histHasResp && (str_contains(strtolower($histTanggapan), 'tolak') || str_contains(strtolower($histTanggapan), 'gagal') || str_contains(strtolower($histTanggapan), 'tidak sinkron'));
                                                                $histDokumen = $hist->jenis_dokumen ?? $hist->jenis_permasalahan ?? $hist->jenis_layanan ?? '-';
                                                                $dtHistBalas = \Carbon\Carbon::parse($hist->updated_at ?? $hist->created_at ?? now())->setTimezone('Asia/Jakarta');
                                                                $formattedHistBalas = $dtHistBalas->translatedFormat('d F Y • H:i') . ' WIB';
                                                            @endphp
                                                            <div @click="selectedNotif = { 
                                                    id: '{{ $hist->id ?? rand(1000, 9999) }}',
                                                    kategori: '{{ $style['label'] }}', 
                                                    kategori_asli: '{{ cleanForJs($hist->kategori ?? $key) }}',
                                                    jenis_layanan: '{{ cleanForJs($hist->jenis_permasalahan ?? $hist->jenis_layanan ?? $style['label']) }}',
                                                    jenis_dokumen: '{{ cleanForJs($histDokumen) }}',
                                                    pesan: '{{ cleanForJs($hist->deskripsi ?? $hist->pesan ?? '') }}', 
                                                    tanggapan: '{{ $histHasResp ? cleanForJs($histTanggapan) : 'Dalam proses.' }}',
                                                    full_date: '{{ $dtHistKirim->translatedFormat('d F Y • H:i') }} WIB',
                                                    update_date: '{{ $formattedHistBalas }}',
                                                    has_response: {{ $histHasResp ? 'true' : 'false' }},
                                                    status: '{{ $histIsRejected ? 'Ditolak' : ($histHasResp ? 'Selesai' : 'Proses') }}',
                                                    icon: '{{ $style['icon'] }}',
                                                    color: '{{ $histIsRejected ? 'text-red-600' : ($histHasResp ? 'text-emerald-600' : 'text-slate-600') }}'
                                                }; showDetail = true;"
                                                                class="group/item bg-white p-2.5 md:p-3 rounded-xl md:rounded-2xl text-[10px] md:text-[11px] border border-slate-200 flex justify-between items-center cursor-pointer hover:border-slate-400 hover:shadow-sm transition-all">
                                                                <div class="flex items-center gap-2 md:gap-3">
                                                                    <span
                                                                        class="w-2 h-2 rounded-full {{ $histHasResp ? ($histIsRejected ? 'bg-red-400' : 'bg-emerald-400') : 'bg-amber-400' }}"></span>
                                                                    <span
                                                                        class="font-black text-slate-600">{{ $dtHistKirim->format('d/m/Y') }}</span>
                                                                </div>
                                                                <span
                                                                    class="uppercase font-black px-2 py-0.5 rounded-md text-[8px] md:text-[9px] {{ $histHasResp ? ($histIsRejected ? 'text-red-600 bg-red-50' : 'text-emerald-600 bg-emerald-50') : 'text-amber-600 bg-amber-50' }}">
                                                                    {{ $histHasResp ? ($histIsRejected ? 'Rejected' : 'Done') : 'Process' }}
                                                                </span>
                                                            </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endforeach

                            {{-- MODAL DETAIL --}}
                            <template x-teleport="body">
                                <div x-show="showDetail"
                                    class="fixed inset-0 z-[9999] flex items-end sm:items-center justify-center p-0 sm:p-4"
                                    x-cloak>
                                    <div class="absolute inset-0 bg-slate-900/90 backdrop-blur-sm"
                                        @click="showDetail = false"></div>

                                    <div x-show="showDetail" x-transition:enter="transition ease-out duration-300"
                                        x-transition:enter-start="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95"
                                        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                                        class="bg-white rounded-t-[2rem] sm:rounded-[2.5rem] w-full max-w-lg overflow-hidden shadow-2xl relative z-10 border border-white/20 max-h-[90vh] overflow-y-auto">

                                        <div class="p-6 md:p-8">
                                            <div class="flex justify-between items-center mb-6">
                                                <div class="flex items-center gap-3 md:gap-4">
                                                    <div :class="selectedNotif.color ? selectedNotif.color.replace('text', 'bg').replace('600', '100') + ' ' + selectedNotif.color : 'bg-slate-100 text-slate-600'"
                                                        class="w-10 h-10 md:w-12 md:h-12 rounded-xl flex items-center justify-center text-lg md:text-xl">
                                                        <i class="fas" :class="selectedNotif.icon"></i>
                                                    </div>
                                                    <div>
                                                        <h4
                                                            class="text-slate-800 font-black text-lg md:text-xl leading-tight">
                                                            Detail Laporan</h4>
                                                        <span
                                                            class="text-[10px] font-bold text-slate-400 uppercase tracking-widest"
                                                            x-text="selectedNotif.kategori"></span>
                                                    </div>
                                                </div>
                                                <button @click="showDetail = false"
                                                    class="w-10 h-10 flex items-center justify-center bg-slate-50 text-slate-400 rounded-full hover:bg-red-50 hover:text-red-500 transition-all">
                                                    <i class="fas fa-times text-base"></i>
                                                </button>
                                            </div>

                                            <div class="space-y-4 md:space-y-5">
                                                <div class="grid grid-cols-1 gap-3">
                                                    <template x-if="selectedNotif.kategori_asli">
                                                        <div
                                                            class="px-4 py-3 bg-blue-50 border border-blue-100 rounded-2xl">
                                                            <p
                                                                class="text-[10px] font-black text-blue-400 uppercase leading-none mb-1.5">
                                                                Kategori Masalah:</p>
                                                            <p class="text-blue-900 text-sm font-bold break-words"
                                                                x-text="selectedNotif.kategori_asli"></p>
                                                        </div>
                                                    </template>

                                                    <template
                                                        x-if="selectedNotif.jenis_layanan || selectedNotif.jenis_dokumen">
                                                        <div
                                                            class="px-4 py-3 bg-indigo-50 border border-indigo-100 rounded-2xl">
                                                            <p
                                                                class="text-[10px] font-black text-indigo-400 uppercase leading-none mb-1.5">
                                                                Jenis Layanan:</p>
                                                            <p class="text-indigo-900 text-sm font-bold break-words"
                                                                x-text="selectedNotif.jenis_layanan || selectedNotif.jenis_dokumen">
                                                            </p>
                                                        </div>
                                                    </template>
                                                </div>

                                                <div class="p-4 md:p-6 bg-slate-50 rounded-2xl border border-slate-100">
                                                    <div
                                                        class="flex flex-col sm:flex-row justify-between items-start gap-1 mb-3">
                                                        <p
                                                            class="text-[10px] font-black text-slate-400 uppercase tracking-wider">
                                                            Deskripsi / Pesan:</p>
                                                        <span
                                                            class="text-[10px] text-slate-500 font-bold bg-slate-200/50 px-2 py-0.5 rounded"
                                                            x-text="selectedNotif.full_date"></span>
                                                    </div>
                                                    <p class="text-slate-700 text-sm font-semibold italic leading-relaxed whitespace-pre-line break-words w-full"
                                                        x-text="selectedNotif.pesan"></p>
                                                </div>

                                                <div :class="selectedNotif.status === 'Ditolak' ? 'bg-red-900 border-red-500' : 'bg-slate-900 border-emerald-500'"
                                                    class="p-5 md:p-7 rounded-[1.8rem] relative overflow-hidden shadow-xl border-b-4 text-white">
                                                    <div class="relative z-10">
                                                        <p class="text-[10px] font-black uppercase mb-2 opacity-70"
                                                            :class="selectedNotif.status === 'Ditolak' ? 'text-red-200' : 'text-emerald-400'">
                                                            Balasan Petugas:</p>
                                                        <p class="text-sm md:text-base leading-relaxed font-medium break-words"
                                                            x-text="selectedNotif.tanggapan"></p>
                                                        <div
                                                            class="mt-4 pt-4 border-t border-white/10 flex justify-between items-center">
                                                            <template x-if="selectedNotif.has_response">
                                                                <span class="text-[10px] text-white font-bold uppercase"
                                                                    x-text="selectedNotif.update_date"></span>
                                                            </template>
                                                            <span
                                                                class="px-2.5 py-1 bg-white/10 rounded-lg text-[9px] font-black uppercase"
                                                                x-text="selectedNotif.status"></span>
                                                        </div>
                                                    </div>
                                                    <i class="fas absolute -right-6 -bottom-6 text-white/5 text-7xl md:text-8xl rotate-12"
                                                        :class="selectedNotif.icon"></i>
                                                </div>
                                            </div>

                                            <button @click="showDetail = false"
                                                class="w-full mt-6 md:mt-8 py-4 md:py-5 bg-slate-900 text-white rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-orange-600 transition-all active:scale-95">
                                                Tutup
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>



                {{-- Fitur KENDALA SIAK --}}
                <div x-show="tab === 'registrasi'" x-transition x-cloak>
                    <div
                        class="bg-white p-5 md:p-8 rounded-[1.5rem] md:rounded-[2.5rem] border border-slate-200 shadow-sm">
                        <div class="flex items-center space-x-3 mb-6 md:mb-8">
                            <div
                                class="w-9 h-9 md:w-10 md:h-10 bg-orange-100 text-orange-600 rounded-xl flex items-center justify-center shrink-0">
                                <i class="fas fa-exclamation-circle text-sm md:text-base"></i>
                            </div>
                            <h2 class="text-base md:text-xl font-black uppercase italic leading-tight">
                                Laporkan Kendala SIAK
                            </h2>
                        </div>

                        <form action="{{ route('pengajuan.store') }}" method="POST" enctype="multipart/form-data"
                            class="space-y-5 md:space-y-6" x-data="{ isLoading: false }" @submit="isLoading = true">

                            @csrf
                            <input type="hidden" name="jenis_registrasi" value="DOKUMEN">

                            <div>
                                <label
                                    class="text-[9px] md:text-[10px] font-black text-slate-400 uppercase ml-2 block mb-2 tracking-widest">
                                    Jenis Kendala SIAK
                                </label>
                                <div class="relative">
                                    <select name="kategori_siak" required
                                        class="w-full bg-slate-50 border border-slate-200 rounded-xl md:rounded-2xl p-3.5 md:p-4 text-xs md:text-sm focus:border-orange-500 focus:ring-2 focus:ring-orange-100 focus:outline-none font-semibold transition-all duration-300 appearance-none">
                                        <option value="" disabled selected>Pilih jenis kendala...</option>
                                        <option value="REGISTRASI SIAK">Registrasi SIAK</option>
                                        <option value="GAGAL LOGIN SIAK">Gagal Login Aplikasi SIAK</option>
                                        <option value="UPDATE DATA SIAK">Update SIAK</option>
                                        <option value="APLIKASI EROR">Aplikasi Eror</option>
                                    </select>
                                    <div
                                        class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400">
                                        <i class="fas fa-chevron-down text-[10px]"></i>
                                    </div>
                                </div>
                                @error('kategori_siak')
                                    <p class="text-red-500 text-[9px] md:text-[10px] font-bold mt-1 ml-2 uppercase italic">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div>
                                <label
                                    class="text-[9px] md:text-[10px] font-black text-slate-400 uppercase ml-2 block mb-2 tracking-widest">
                                    Deskripsi Detail Kendala
                                </label>
                                <textarea name="deskripsi_siak" rows="4" required
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl md:rounded-2xl p-3.5 md:p-4 text-xs md:text-sm focus:border-orange-500 focus:ring-2 focus:ring-orange-100 focus:outline-none font-semibold transition-all duration-300"
                                    placeholder="Jelaskan secara detail kendala yang dialami, termasuk pesan error yang muncul jika ada...">{{ old('deskripsi_siak') }}</textarea>
                                @error('deskripsi_siak')
                                    <p class="text-red-500 text-[9px] md:text-[10px] font-bold mt-1 ml-2 uppercase italic">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div x-data="{ 
                previews: [], 
                handleFiles(event) {
                    const files = Array.from(event.target.files);
                    this.previews = []; 
                    files.forEach(file => {
                        if (file.type.startsWith('image/')) {
                            const reader = new FileReader();
                            reader.onload = (e) => { 
                                this.previews.push({ url: e.target.result, name: file.name }); 
                            };
                            reader.readAsDataURL(file);
                        }
                    });
                }
            }">
                                <label
                                    class="text-[9px] md:text-[10px] font-black text-slate-400 uppercase ml-2 block mb-2 tracking-widest">
                                    Tangkapan Layar Error <span class="text-orange-600 block md:inline">(Upload foto
                                        kendala)</span>
                                </label>

                                <div class="relative group">
                                    <input type="file" name="foto_dokumen_siak[]" class="hidden" x-ref="photo"
                                        accept="image/jpeg,image/png,image/jpg" required multiple
                                        @change="handleFiles($event)">

                                    <div class="border-2 border-dashed border-slate-200 rounded-[1.5rem] md:rounded-[2rem] p-6 md:p-10 text-center hover:bg-slate-50 hover:border-orange-400 cursor-pointer transition-all duration-300"
                                        @click="$refs.photo.click()">

                                        <div x-show="previews.length === 0" class="space-y-3">
                                            <div
                                                class="w-12 h-12 md:w-14 md:h-14 bg-slate-100 text-slate-400 rounded-xl md:rounded-2xl flex items-center justify-center mx-auto mb-2 group-hover:scale-110 group-hover:bg-orange-100 group-hover:text-orange-600 transition-all duration-500">
                                                <i class="fas fa-camera text-xl md:text-2xl"></i>
                                            </div>
                                            <p
                                                class="text-[8px] md:text-[9px] text-slate-400 font-black uppercase tracking-[0.1em] leading-tight px-4">
                                                Klik untuk unggah foto bukti kendala
                                            </p>
                                        </div>

                                        <div x-show="previews.length > 0" x-cloak class="space-y-6">
                                            <div class="flex flex-wrap gap-3 justify-center">
                                                <template x-for="(img, index) in previews" :key="index">
                                                    <div class="relative group/img">
                                                        <img :src="img.url"
                                                            class="h-16 w-16 md:h-24 md:w-24 object-cover rounded-xl md:rounded-2xl shadow-lg border-2 md:border-4 border-white transform transition hover:scale-105">
                                                        <div
                                                            class="absolute -top-1.5 -right-1.5 bg-orange-600 text-white text-[8px] md:text-[9px] font-black w-4 h-4 md:w-5 md:h-5 rounded-full flex items-center justify-center shadow-md">
                                                            <span x-text="index + 1"></span>
                                                        </div>
                                                    </div>
                                                </template>
                                            </div>

                                            <div class="pt-4 border-t border-slate-100">
                                                <p class="text-[9px] md:text-[10px] text-orange-600 font-black uppercase italic mb-2"
                                                    x-text="previews.length + ' Foto terpilih'"></p>
                                                <span
                                                    class="text-[8px] bg-slate-900 text-white px-4 py-2 rounded-full font-black uppercase tracking-widest inline-block hover:bg-orange-600 transition-colors">
                                                    <i class="fas fa-sync-alt mr-1 text-[7px]"></i> Ganti Foto
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @error('foto_dokumen_siak')
                                    <p class="text-red-500 text-[9px] md:text-[10px] font-bold mt-2 ml-2 uppercase italic">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div class="pt-4">
                                <button type="submit" :disabled="isLoading"
                                    :class="isLoading ? 'bg-slate-400 cursor-not-allowed' : 'bg-slate-900 hover:bg-orange-600 shadow-xl active:scale-95'"
                                    class="w-full text-white font-black py-4 md:py-5 rounded-2xl md:rounded-[1.8rem] transition-all duration-300 uppercase tracking-[0.1em] md:tracking-[0.2em] text-[10px] md:text-[11px] flex items-center justify-center gap-3">

                                    <template x-if="!isLoading">
                                        <span class="flex items-center gap-2">
                                            <i class="fas fa-paper-plane"></i> KIRIM LAPORAN KENDALA
                                        </span>
                                    </template>

                                    <template x-if="isLoading">
                                        <span class="flex items-center gap-2">
                                            <i class="fas fa-circle-notch animate-spin"></i> SEDANG MENGIRIM...
                                        </span>
                                    </template>
                                </button>

                                <p
                                    class="text-[8px] text-slate-400 font-bold uppercase text-center mt-4 tracking-widest leading-relaxed">
                                    Laporan akan langsung diteruskan ke tim teknis SIAK.
                                </p>
                            </div>
                        </form>
                    </div>
                </div>


                {{-- Fitur Pembubuhan --}}
                <div x-show="tab === 'pembubuhan'" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform scale-95"
                    x-transition:enter-end="opacity-100 transform scale-100" x-cloak>

                    <div x-data="{ isProcessingTTE: false }"
                        class="bg-white p-6 md:p-10 rounded-[2rem] md:rounded-[2.5rem] text-slate-900 shadow-sm border border-slate-100 relative overflow-hidden">

                        <div class="relative z-10">
                            <h2 class="text-xl md:text-2xl font-black mb-2 uppercase italic text-slate-800">
                                Pembubuhan TTE
                            </h2>
                            <p
                                class="text-[10px] text-slate-500 mb-8 max-w-lg leading-relaxed font-bold uppercase tracking-wide">
                                Lengkapi data di bawah ini untuk mengajukan pembubuhan Tanda Tangan Elektronik
                                pada
                                dokumen kependudukan Anda.
                            </p>

                            <form action="{{ route('pembubuhan.store') }}" method="POST" enctype="multipart/form-data"
                                id="form-pembubuhan-tte" class="space-y-4" @submit="isProcessingTTE = true">
                                @csrf

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    {{-- NIK USER: Otomatis dari akun login --}}
                                    <div>
                                        <label class="text-[10px] font-black text-slate-400 uppercase ml-2 block mb-2">
                                            NIK Akun (Pelapor)
                                        </label>
                                        <input type="text" name="nik" value="{{ auth()->user()->nik }}" readonly
                                            class="w-full bg-slate-50 border border-slate-200 rounded-2xl p-4 font-bold text-sm text-slate-500 outline-none cursor-not-allowed">
                                    </div>

                                    {{-- NIK PEMOHON: Diisi manual --}}
                                    <div>
                                        <label class="text-[10px] font-black text-slate-800 uppercase ml-2 block mb-2">
                                            NIK Pemohon (Sesuai Dokumen)
                                        </label>
                                        <input type="text" name="nik_pemohon" required maxlength="16"
                                            value="{{ old('nik_pemohon') }}"
                                            class="w-full bg-slate-50 border border-slate-200 rounded-2xl p-4 focus:bg-white focus:border-slate-800 outline-none font-bold text-sm text-slate-800 transition-all @error('nik_pemohon') border-red-500 @enderror"
                                            placeholder="Masukkan 16 digit NIK"
                                            onkeypress="return event.charCode >= 48 && event.charCode <= 57">
                                        @error('nik_pemohon')
                                            <p class="text-red-500 text-[10px] font-bold mt-1 ml-2 uppercase italic">
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>
                                </div>

                                <div>
                                    <label class="text-[10px] font-black text-slate-800 uppercase ml-2 block mb-2">
                                        Jenis Dokumen (Pengajuan TTE)
                                    </label>
                                    <div class="relative">
                                        <select name="jenis_dokumen" required
                                            class="w-full bg-slate-50 border border-slate-200 rounded-2xl p-4 focus:bg-white focus:border-slate-800 outline-none font-bold text-sm text-slate-800 appearance-none transition-all @error('jenis_dokumen') border-red-500 @enderror">
                                            <option value="" disabled selected>-- Pilih Jenis Dokumen --
                                            </option>
                                            <option value="AKTA KELAHIRAN" {{ old('jenis_dokumen') == 'AKTA KELAHIRAN' ? 'selected' : '' }}>AKTA KELAHIRAN</option>
                                            <option value="AKTA KEMATIAN" {{ old('jenis_dokumen') == 'AKTA KEMATIAN' ? 'selected' : '' }}>AKTA KEMATIAN</option>
                                            <option value="KARTU KELUARGA" {{ old('jenis_dokumen') == 'KARTU KELUARGA' ? 'selected' : '' }}>KARTU KELUARGA</option>
                                            <option value="SURAT KETERANGAN PINDAH" {{ old('jenis_dokumen') == 'SURAT KETERANGAN PINDAH' ? 'selected' : '' }}>SURAT KETERANGAN PINDAH</option>
                                            <option value="BIODATA WNI" {{ old('jenis_dokumen') == 'BIODATA WNI' ? 'selected' : '' }}>
                                                BIODATA WNI</option>
                                        </select>
                                        <div
                                            class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-slate-400">
                                            <i class="fas fa-chevron-down text-xs"></i>
                                        </div>
                                    </div>
                                    @error('jenis_dokumen')
                                        <p class="text-red-500 text-[10px] font-bold mt-1 ml-2 uppercase italic">
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <button type="submit" :disabled="isProcessingTTE"
                                    :class="isProcessingTTE ? 'bg-slate-400' : 'bg-slate-900 hover:bg-rose-600 shadow-slate-200'"
                                    class="w-full text-white font-black py-4 rounded-xl text-[10px] uppercase tracking-widest shadow-lg active:scale-[0.98] transition-all mt-4 flex items-center justify-center gap-3">

                                    <template x-if="!isProcessingTTE">
                                        <span class="flex items-center gap-2">
                                            <i class="fas fa-paper-plane"></i> Kirim Permohonan Pembubuhan TTE
                                        </span>
                                    </template>

                                    <template x-if="isProcessingTTE">
                                        <span class="flex items-center gap-2">
                                            <i class="fas fa-circle-notch animate-spin"></i> Sedang Memproses...
                                        </span>
                                    </template>
                                </button>
                            </form>
                        </div>

                        <i
                            class="fas fa-file-signature absolute -right-4 bottom-0 text-[120px] md:text-[200px] text-slate-50/50 -z-0"></i>
                    </div>
                </div>


                {{-- Fitur Luar Daerah --}}
                <div x-show="tab === 'luardaerah'" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform scale-95"
                    x-transition:enter-end="opacity-100 transform scale-100" x-cloak>

                    <div x-data="{ isSubmittingLuar: false }"
                        class="bg-white p-6 md:p-10 rounded-[2rem] md:rounded-[2.5rem] text-slate-900 shadow-sm border border-slate-100 relative overflow-hidden">

                        <div class="relative z-10">
                            <h2 class="text-xl md:text-2xl font-black mb-2 uppercase italic text-slate-800">
                                Layanan Luar Daerah
                            </h2>
                            <p
                                class="text-[10px] text-slate-500 mb-8 max-w-lg leading-relaxed font-bold uppercase tracking-wide">
                                Gunakan formulir ini untuk melaporkan atau mengajukan dokumen kependudukan yang
                                berasal
                                dari luar daerah. Data akan diproses dengan kategori khusus Luar Daerah.
                            </p>

                            <form action="{{ route('user.luardaerah.store') }}" method="POST" class="space-y-4"
                                id="formLuarDaerah" @submit="isSubmittingLuar = true">
                                @csrf

                                <input type="hidden" name="sumber_layanan" value="LUAR_DAERAH">

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="text-[10px] font-black text-slate-400 uppercase ml-2 block mb-2">
                                            NIK User (Otomatis)
                                        </label>
                                        <input type="text" name="nik" value="{{ auth()->user()->nik }}" readonly
                                            class="w-full bg-slate-50 border border-slate-200 rounded-2xl p-4 font-bold text-sm text-slate-500 outline-none cursor-not-allowed">
                                        @error('nik')
                                            <p class="text-red-500 text-[9px] font-bold mt-1 ml-2 uppercase italic">
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="text-[10px] font-black text-slate-400 uppercase ml-2 block mb-2">
                                            NIK Pemohon Luar Daerah
                                        </label>
                                        <input type="text" name="nik_luar_daerah" required maxlength="16"
                                            value="{{ old('nik_luar_daerah') }}"
                                            class="w-full bg-slate-50 border border-slate-200 rounded-2xl p-4 focus:bg-white focus:border-slate-800 outline-none font-bold text-sm text-slate-800 transition-all"
                                            placeholder="Masukkan 16 digit NIK luar daerah"
                                            onkeypress="return event.charCode >= 48 && event.charCode <= 57">
                                        @error('nik_luar_daerah')
                                            <p class="text-red-500 text-[9px] font-bold mt-1 ml-2 uppercase italic">
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>
                                </div>

                                <div>
                                    <label class="text-[10px] font-black text-slate-400 uppercase ml-2 block mb-2">
                                        Pilih Jenis Layanan Luar Daerah
                                    </label>
                                    <div class="relative">
                                        <select name="jenis_dokumen_luar" required
                                            class="w-full bg-slate-50 border border-slate-200 rounded-2xl p-4 focus:bg-white focus:border-slate-800 outline-none font-bold text-sm text-slate-800 appearance-none transition-all">
                                            <option value="" disabled selected>-- Pilih Layanan Luar Daerah --
                                            </option>
                                            <option value="PINDAH DATANG" {{ old('jenis_dokumen_luar') == 'PINDAH DATANG' ? 'selected' : '' }}>Pindah Datang (Luar Daerah)
                                            </option>
                                            <option value="KONSOLIDASI MANUAL" {{ old('jenis_dokumen_luar') == 'KONSOLIDASI MANUAL' ? 'selected' : '' }}>
                                                Konsolidasi Manual</option>
                                            <option value="PEMBARUAN DATA LUAR" {{ old('jenis_dokumen_luar') == 'PEMBARUAN DATA LUAR' ? 'selected' : '' }}>Update/Pembaruan Data Luar</option>
                                            <option value="REKAM KTP" {{ old('jenis_dokumen_luar') == 'REKAM KTP' ? 'selected' : '' }}>Rekam KTP</option>
                                            <option value="CETAK KTP" {{ old('jenis_dokumen_luar') == 'CETAK KTP' ? 'selected' : '' }}>Cetak KTP</option>
                                        </select>
                                        <div
                                            class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-slate-400">
                                            <i class="fas fa-chevron-down text-xs"></i>
                                        </div>
                                    </div>
                                    @error('jenis_dokumen_luar')
                                        <p class="text-red-500 text-[9px] font-bold mt-1 ml-2 uppercase italic">
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <button type="submit" :disabled="isSubmittingLuar"
                                    :class="isSubmittingLuar ? 'bg-slate-400 cursor-not-allowed' : 'bg-blue-600 hover:bg-blue-700 shadow-blue-200 shadow-lg active:scale-[0.98]'"
                                    class="w-full text-white font-black py-4 rounded-xl text-[10px] uppercase tracking-widest transition-all mt-4 flex items-center justify-center gap-2">

                                    <template x-if="!isSubmittingLuar">
                                        <span class="flex items-center gap-2">
                                            <i class="fas fa-paper-plane"></i> Kirim Data Luar Daerah
                                        </span>
                                    </template>

                                    <template x-if="isSubmittingLuar">
                                        <span class="flex items-center gap-2">
                                            <i class="fas fa-circle-notch animate-spin"></i> Sedang Memproses...
                                        </span>
                                    </template>
                                </button>
                            </form>
                        </div>

                        <i
                            class="fas fa-map-marked-alt absolute -right-4 bottom-0 text-[120px] md:text-[200px] text-slate-50/50 -z-0"></i>
                    </div>
                </div>


                 {{-- Fitur Edit Profile --}}
                <div x-show="tab === 'edit_profil'" x-transition x-cloak>
                    <div
                        class="bg-white p-6 md:p-8 rounded-[2rem] md:rounded-[2.5rem] border border-slate-200 shadow-sm">
                        <h2 class="text-xl font-black mb-8 uppercase italic">Pengaturan Profil</h2>

                        <form action="{{ route('user.profile.update') }}" method="POST">
                            @csrf
                            @method('PUT') {{-- Pastikan menggunakan PUT atau POST sesuai route Anda --}}

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6 mb-6">
                                {{-- Nama Lengkap --}}
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-slate-400 uppercase ml-2">Nama
                                        Lengkap</label>
                                    <input type="text" name="name" value="{{ auth()->user()->name }}" required
                                        class="w-full bg-slate-50 border border-slate-200 rounded-xl p-4 text-sm font-bold focus:border-orange-500 outline-none">
                                </div>

                                {{-- NIK (Readonly) --}}
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-slate-400 uppercase ml-2">NIK
                                        (Nomor Induk
                                        Kependudukan)</label>
                                    <input type="text" name="nik" value="{{ auth()->user()->nik }}" readonly
                                        class="w-full bg-slate-100 border border-slate-200 rounded-xl p-4 text-sm font-bold text-slate-500 outline-none cursor-not-allowed"
                                        title="NIK tidak dapat diubah secara mandiri">
                                </div>

                                {{-- Password Baru --}}
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-slate-400 uppercase ml-2">Password
                                        Baru
                                        (Opsional)</label>
                                    <input type="password" name="password"
                                        class="w-full bg-slate-50 border border-slate-200 rounded-xl p-4 text-sm font-bold focus:border-orange-500 outline-none"
                                        placeholder="••••••••">
                                </div>

                                {{-- Konfirmasi Password --}}
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-slate-400 uppercase ml-2">Konfirmasi
                                        Password</label>
                                    <input type="password" name="password_confirmation"
                                        class="w-full bg-slate-50 border border-slate-200 rounded-xl p-4 text-sm font-bold focus:border-orange-500 outline-none"
                                        placeholder="••••••••">
                                </div>

                                {{-- Alamat / Kecamatan --}}
                                <div class="md:col-span-2 space-y-2">
                                    <label class="text-[10px] font-black text-slate-400 uppercase ml-2">
                                        Lokasi / Wilayah (Kecamatan)
                                    </label>

                                    <div class="relative max-w-sm">
                                        <select name="location" required {{-- Trik Atribut Size: Saat diklik, munculkan
                                            5 opsi saja (scrollable) --}} onfocus='this.size=5;' onblur='this.size=1;'
                                            onchange='this.size=1; this.blur();'
                                            class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-xs font-bold focus:border-orange-500 outline-none appearance-none cursor-pointer absolute z-10">

                                            <option value="" disabled {{ !auth()->user()->location ? 'selected' : '' }}>
                                                Pilih Kecamatan</option>

                                            @foreach($kecamatans as $kec)
                                                <option value="{{ strtoupper($kec->nama_kecamatan) }}" {{ (old('location') ?? auth()->user()->location) == strtoupper($kec->nama_kecamatan) ? 'selected' : '' }} class="py-2">
                                                    {{ strtoupper($kec->nama_kecamatan) }}
                                                </option>
                                            @endforeach
                                        </select>

                                        {{-- Ikon panah (Hidden saat select aktif agar tidak menutupi teks) --}}
                                        <div
                                            class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-slate-400">
                                            <i class="fas fa-chevron-down text-[10px]"></i>
                                        </div>
                                    </div>

                                    {{-- Spacer agar konten di bawah tidak tertutup saat dropdown terbuka --}}
                                    <div class="h-10"></div>
                                </div>

                            </div>

                            <button type="submit"
                                class="w-full sm:w-auto bg-orange-600 text-white px-8 py-4 rounded-xl font-black text-[10px] uppercase tracking-widest shadow-lg shadow-orange-600/20 active:scale-95 transition-all">
                                Simpan Perubahan
                            </button>
                        </form>
                    </div>
                </div>


                 {{-- Fitur Aktivasi --}}
                <div x-show="tab === 'aktivasi'" x-transition x-cloak
                    x-data="{ jenisLayanan: '', fileCount: 0, isProcessingAktivasi: false }">
                    <div
                        class="bg-white p-4 md:p-8 rounded-[1.5rem] md:rounded-[2.5rem] border border-slate-200 shadow-sm">
                        <div class="flex items-center space-x-3 mb-6">
                            <div
                                class="w-8 h-8 md:w-10 md:h-10 bg-blue-100 text-blue-600 rounded-lg md:rounded-xl flex items-center justify-center shrink-0">
                                <i class="fas fa-id-card text-sm md:text-base"></i>
                            </div>
                            <h2 class="text-base md:text-xl font-black uppercase italic">Permintaan Aktivasi / Restore
                                NIK
                            </h2>
                        </div>

                        <div class="bg-blue-50 border-l-4 border-blue-500 p-3 mb-6">
                            <p class="text-[8px] md:text-[10px] text-blue-700 font-black leading-tight uppercase">
                                <i class="fas fa-info-circle mr-1"></i> Kirimkan permintaan jika NIK Anda tidak
                                terdeteksi di portal kependudukan atau membutuhkan pemulihan data.
                            </p>
                        </div>

                        <form action="{{ route('aktivasi.store') }}" method="POST" enctype="multipart/form-data"
                            class="space-y-4 md:space-y-6" @submit="isProcessingAktivasi = true">
                            @csrf

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-6">
                                <div class="space-y-1">
                                    <label class="text-[9px] font-black text-slate-400 uppercase ml-2 block">Nama
                                        Pelapor</label>
                                    <input type="text" name="nama_lengkap" required
                                        value="{{ auth()->user()->name ?? '' }}" readonly
                                        class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 md:p-4 text-xs md:text-sm focus:border-blue-500 focus:outline-none font-semibold transition-all text-slate-500 cursor-not-allowed">
                                </div>

                                <div class="space-y-1">
                                    <label class="text-[9px] font-black text-slate-400 uppercase ml-2 block">NIK
                                        Pemohon
                                        (16 Digit)</label>
                                    <input type="text" name="nik_aktivasi" required maxlength="16"
                                        onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                                        placeholder="16 digit NIK"
                                        class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 md:p-4 text-xs md:text-sm focus:border-blue-500 focus:outline-none font-semibold transition-all">
                                </div>
                            </div>

                            <div class="space-y-1">
                                <label class="text-[9px] font-black text-slate-400 uppercase ml-2 block">Pilih
                                    Jenis
                                    Layanan</label>
                                <div class="relative">
                                    <select name="jenis_layanan" required x-model="jenisLayanan"
                                        class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 md:p-4 text-xs md:text-sm focus:border-blue-500 focus:outline-none font-semibold transition-all appearance-none cursor-pointer">
                                        <option value="" disabled selected>-- Pilih Layanan --</option>
                                        <option value="RESTORE">RESTORE DATA (Wajib Unggah Foto)</option>
                                        <option value="AKTIVASI">AKTIVASI DATA (Unggah Foto Opsional)</option>
                                    </select>
                                    <div
                                        class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-slate-400">
                                        <i class="fas fa-chevron-down text-[10px]"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-3">
                                <label class="text-[9px] font-black text-slate-400 uppercase ml-2 block">
                                    Tangkapan Layar / Foto Dokumen
                                    <template x-if="jenisLayanan === 'RESTORE'">
                                        <span class="text-red-500 font-black">(WAJIB - MINIMAL 1 FOTO)</span>
                                    </template>
                                    <template x-if="jenisLayanan === 'AKTIVASI'">
                                        <span class="text-blue-500 font-black">(OPSIONAL - BOLEH KIRIM FOTO)</span>
                                    </template>
                                </label>

                                <div class="relative group">
                                    <input type="file" name="lampiran[]" id="lampiran_aktivasi"
                                        :required="jenisLayanan === 'RESTORE'" multiple
                                        @change="fileCount = $event.target.files.length"
                                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20">

                                    <div class="w-full border-2 border-dashed rounded-[1.5rem] p-4 md:p-8 transition-all flex flex-col items-center justify-center"
                                        :class="fileCount > 0 ? 'border-orange-400 bg-orange-50/30' : 'border-slate-200 group-hover:border-blue-400 group-hover:bg-blue-50/50'">

                                        <div x-show="fileCount === 0" class="flex flex-col items-center space-y-2">
                                            <div
                                                class="w-10 h-10 md:w-16 md:h-16 bg-slate-100 rounded-xl md:rounded-2xl flex items-center justify-center text-slate-400 transition-colors">
                                                <i class="fas fa-camera text-xl md:text-2xl"></i>
                                            </div>
                                            <p
                                                class="text-[8px] md:text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">
                                                Klik Untuk Unggah Foto (Bisa Pilih Banyak)
                                            </p>
                                        </div>

                                        <div x-show="fileCount > 0"
                                            class="flex flex-col items-center space-y-2 w-full text-center">
                                            <div
                                                class="w-10 h-10 md:w-16 md:h-16 bg-orange-100 rounded-xl md:rounded-2xl flex items-center justify-center text-orange-600">
                                                <i class="fas fa-images text-xl md:text-2xl"></i>
                                            </div>
                                            <p class="text-[10px] font-black text-orange-600 uppercase">
                                                <span x-text="fileCount"></span> File Berhasil Dipilih
                                            </p>
                                            <div
                                                class="bg-slate-900 text-white px-4 py-1.5 rounded-lg text-[8px] font-black uppercase tracking-widest">
                                                <i class="fas fa-sync-alt mr-1"></i> Ganti Foto
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <p class="text-[7px] md:text-[8px] text-slate-400 font-bold uppercase italic">
                                    * Gunakan tombol Ctrl (Windows) atau Command (Mac) untuk memilih beberapa
                                    foto sekaligus.
                                </p>
                            </div>

                            <div class="space-y-1">
                                <label class="text-[9px] font-black text-slate-400 uppercase ml-2 block">Pesan
                                    Tambahan</label>
                                <textarea name="alasan" rows="2"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 md:p-4 text-xs md:text-sm focus:border-blue-500 focus:outline-none font-semibold transition-all"
                                    placeholder="Contoh: NIK tidak ditemukan saat daftar BPJS atau butuh restore data lama"></textarea>
                            </div>

                            <button type="submit" :disabled="isProcessingAktivasi"
                                :class="isProcessingAktivasi ? 'bg-slate-400 cursor-not-allowed' : 'bg-blue-600 hover:bg-blue-700 active:scale-95'"
                                class="w-full text-white font-black py-3.5 md:py-4 rounded-xl md:rounded-2xl shadow-lg uppercase tracking-widest text-[9px] md:text-[10px] transition-all flex items-center justify-center gap-2">

                                <template x-if="!isProcessingAktivasi">
                                    <span>KIRIM PERMINTAAN <span x-text="jenisLayanan.toUpperCase()"></span></span>
                                </template>

                                <template x-if="isProcessingAktivasi">
                                    <span class="flex items-center gap-2">
                                        <i class="fas fa-circle-notch animate-spin"></i> SEDANG MEMPROSES...
                                    </span>
                                </template>
                            </button>
                        </form>
                    </div>
                </div>


                {{-- Fitur Update Data --}}
                <div x-show="tab === 'update_data'" x-transition x-cloak
                    x-data="{ jenisLayanan: '', fileCount: 0, files: [] }">
                    <div
                        class="bg-white p-4 md:p-8 rounded-[1.5rem] md:rounded-[2.5rem] border border-slate-200 shadow-sm">

                        <div class="flex items-center space-x-3 mb-6">
                            <div
                                class="w-8 h-8 md:w-10 md:h-10 bg-blue-100 text-blue-600 rounded-lg md:rounded-xl flex items-center justify-center shrink-0">
                                <i class="fas fa-user-edit text-sm md:text-base"></i>
                            </div>
                            <h2 class="text-base md:text-xl font-black uppercase italic">Permohonan Update Data
                            </h2>
                        </div>

                        <div class="bg-blue-50 border-l-4 border-blue-500 p-3 mb-6">
                            <p class="text-[8px] md:text-[10px] text-blue-700 font-black leading-tight uppercase">
                                <i class="fas fa-info-circle mr-1"></i> Ajukan perubahan elemen data
                                kependudukan jika
                                terdapat kesalahan.
                            </p>
                        </div>

                        {{-- Tampilkan Error Validasi Jika Ada --}}
                        @if ($errors->any())
                            <div
                                class="mb-4 p-3 bg-red-100 border-l-4 border-red-500 rounded text-red-700 text-[10px] font-bold uppercase">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li><i class="fas fa-exclamation-triangle mr-1"></i> {{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('update-data.store') }}" method="POST" enctype="multipart/form-data"
                            class="space-y-4 md:space-y-6">
                            @csrf

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-6">
                                <div class="space-y-1">
                                    <label class="text-[9px] font-black text-slate-400 uppercase ml-2 block">Nama
                                        Pelapor</label>
                                    <input type="text" value="{{ Auth::user()->name ?? '' }}" readonly
                                        class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 md:p-4 text-xs md:text-sm font-semibold transition-all text-slate-500">
                                </div>

                                <div class="space-y-1">
                                    <label class="text-[9px] font-black text-slate-400 uppercase ml-2 block">NIK
                                        Pemohon
                                        (16 Digit)</label>
                                    <input type="number" name="nik_pemohon" required value="{{ old('nik_pemohon') }}"
                                        {{-- Mencegah input lebih dari 16 digit --}}
                                        onkeypress="if(this.value.length==16) return false;"
                                        oninput="if (this.value.length > 16) this.value = this.value.slice(0, 16)"
                                        placeholder="Masukkan 16 digit NIK"
                                        class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 md:p-4 text-xs md:text-sm focus:border-blue-500 focus:outline-none font-semibold transition-all">
                                </div>
                            </div>

                            <div class="space-y-1">
                                <label class="text-[9px] font-black text-slate-400 uppercase ml-2 block">Jenis
                                    Perubahan</label>
                                <select name="kategori_update" required x-model="jenisLayanan"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 md:p-4 text-xs md:text-sm focus:border-blue-500 focus:outline-none font-semibold transition-all appearance-none cursor-pointer">
                                    <option value="" disabled selected>-- Pilih Perubahan Data --</option>
                                    <option value="PERUBAHAN NAMA">PERUBAHAN NAMA</option>
                                    <option value="PERUBAHAN TANGGAL LAHIR">PERUBAHAN TANGGAL LAHIR</option>
                                    <option value="PERUBAHAN AGAMA">PERUBAHAN AGAMA</option>
                                    <option value="PERUBAHAN JENIS KELAMIN">PERUBAHAN JENIS KELAMIN</option>
                                    <option value="PERUBAHAN GOLONGAN DARAH">PERUBAHAN GOLONGAN DARAH</option>
                                </select>
                            </div>

                            <div class="space-y-3">
                                <label class="text-[9px] font-black text-slate-400 uppercase ml-2 block">
                                    Unggah Lampiran <span class="text-red-500">(WAJIB - Gambar)</span>
                                </label>

                                <div class="relative group">
                                    <input type="file" name="lampiran_update[]" multiple required accept="image/*"
                                        @change="fileCount = $event.target.files.length; files = Array.from($event.target.files).map(f => URL.createObjectURL(f))"
                                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20">

                                    <div class="w-full border-2 border-dashed rounded-[1.5rem] p-4 md:p-6 transition-all flex flex-col items-center justify-center"
                                        :class="fileCount > 0 ? 'border-orange-400 bg-orange-50/30' : 'border-slate-200 group-hover:border-blue-400 group-hover:bg-blue-50/50'">

                                        <div x-show="fileCount === 0" class="flex flex-col items-center space-y-2">
                                            <div
                                                class="w-10 h-10 md:w-16 md:h-16 bg-slate-100 rounded-xl md:rounded-2xl flex items-center justify-center text-slate-400 transition-colors">
                                                <i class="fas fa-camera text-xl md:text-2xl"></i>
                                            </div>
                                            <p
                                                class="text-[8px] md:text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">
                                                Klik Unggah Dokumen (Bisa lebih dari 1)
                                            </p>
                                        </div>

                                        <div x-show="fileCount > 0" class="w-full flex flex-col items-center space-y-4">
                                            <div class="flex flex-wrap justify-center gap-2 md:gap-3">
                                                <template x-for="(file, index) in files" :key="index">
                                                    <div class="relative">
                                                        <div
                                                            class="w-10 h-10 md:w-14 md:h-14 rounded-lg md:rounded-xl border border-orange-200 overflow-hidden bg-white shadow-sm">
                                                            <img :src="file" class="w-full h-full object-cover">
                                                        </div>
                                                    </div>
                                                </template>
                                            </div>
                                            <p class="text-[10px] font-black text-orange-600 uppercase italic"
                                                x-text="fileCount + ' File Terpilih'"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-1">
                                <label class="text-[9px] font-black text-slate-400 uppercase ml-2 block">Deskripsi
                                    /
                                    Alasan <span class="text-red-500">*</span></label>
                                <textarea name="alasan_update" required rows="2"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 md:p-4 text-xs md:text-sm focus:border-blue-500 focus:outline-none font-semibold transition-all"
                                    placeholder="Contoh: Perubahan nama sesuai akta kelahiran...">{{ old('alasan_update') }}</textarea>
                            </div>

                            <button type="submit"
                                class="w-full bg-blue-600 text-white font-black py-3.5 md:py-4 rounded-xl md:rounded-2xl shadow-lg uppercase tracking-widest text-[9px] md:text-[10px] hover:bg-blue-700 active:scale-95 transition-all">
                                KIRIM PERMINTAAN <span x-text="jenisLayanan.replace('UPDATE ', '')"></span>
                            </button>
                        </form>
                    </div>
                </div>


                 {{-- Fitur Proxy --}}
                <div x-show="tab === 'proxy'" x-transition x-cloak>
                    <div
                        class="bg-indigo-600 p-6 md:p-10 rounded-[2rem] md:rounded-[2.5rem] text-white shadow-xl relative overflow-hidden">
                        <div class="relative z-10">
                            <h2 class="text-xl md:text-2xl font-black mb-4 uppercase italic">Pelaporan Proxy</h2>
                            <p
                                class="text-[10px] text-indigo-100 mb-8 max-w-lg leading-relaxed font-bold uppercase tracking-wide">
                                Untuk Laporkan Proxy Jika Laporan Terblokir.
                            </p>

                            <form action="{{ route('proxy.store') }}" method="POST" enctype="multipart/form-data"
                                class="space-y-4">
                                @csrf
                                <div>
                                    <label class="text-[10px] font-black text-indigo-200 uppercase ml-2 block mb-2">
                                        Deskripsi (Opsional)
                                    </label>
                                    <input type="text" name="deskripsi"
                                        class="w-full bg-white/10 border border-white/20 rounded-2xl p-4 focus:bg-white/20 outline-none font-bold text-sm text-white"
                                        placeholder="Tambahkan detail jika ada...">
                                </div>

                                <div x-data="{ 
                    proxyPreviews: [], 
                    handleFiles(event) {
                        this.proxyPreviews = [];
                        const files = event.target.files;
                        for (let i = 0; i < files.length; i++) {
                            const reader = new FileReader();
                            reader.onload = (e) => {
                                this.proxyPreviews.push(e.target.result);
                            };
                            reader.readAsDataURL(files[i]);
                        }
                    }
                }">
                                    <label class="text-[10px] font-black text-indigo-200 uppercase ml-2 block mb-2">
                                        Bukti Screenshot (Maks. 10 File)
                                    </label>

                                    <input type="file" name="foto_proxy[]" class="hidden" x-ref="proxyFile"
                                        accept="image/*" required multiple @change="handleFiles($event)">

                                    <div @click="$refs.proxyFile.click()"
                                        class="border-2 border-dashed border-white/30 rounded-2xl p-6 text-center hover:bg-white/10 cursor-pointer">

                                        <div x-show="proxyPreviews.length === 0" class="space-y-2 text-white/50">
                                            <i class="fas fa-camera text-2xl"></i>
                                            <p class="text-[9px] font-bold uppercase tracking-widest leading-tight">
                                                Pilih Gambar (Bisa pilih banyak)
                                            </p>
                                        </div>

                                        <div x-show="proxyPreviews.length > 0" x-cloak>
                                            <div class="grid grid-cols-3 md:grid-cols-5 gap-2 mb-3">
                                                <template x-for="(src, index) in proxyPreviews" :key="index">
                                                    <img :src="src"
                                                        class="h-20 w-full object-cover rounded-lg border border-white/20">
                                                </template>
                                            </div>
                                            <span
                                                class="text-[8px] bg-white text-indigo-600 px-2 py-1 rounded font-black uppercase">
                                                Ganti Semua Gambar
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <button type="submit"
                                    class="w-full bg-white text-indigo-600 font-black py-4 rounded-xl text-[10px] uppercase tracking-widest shadow-xl active:scale-95">
                                    Kirim Laporan Proxy
                                </button>
                            </form>
                        </div>
                        <i
                            class="fas fa-network-wired absolute -right-4 bottom-0 text-[120px] md:text-[200px] text-white/5"></i>
                    </div>
                </div>


                 {{-- Fitur Trouble Sistem --}}
                <div x-show="tab === 'trouble'" x-transition x-cloak>
                    <div
                        class="bg-red-50 p-6 md:p-10 rounded-[2rem] md:rounded-[2.5rem] border border-red-200 shadow-sm">
                        <div class="text-center mb-8">
                            <div
                                class="w-16 h-16 bg-red-100 text-red-600 rounded-2xl flex items-center justify-center text-2xl mx-auto mb-4 shadow-lg shadow-red-200">
                                <i class="fas fa-bug rotate-12"></i>
                            </div>
                            <h2 class="text-xl md:text-2xl font-black text-red-600 mb-1 uppercase italic">
                                Laporkan
                                Trouble</h2>
                            <p class="text-[9px] text-red-400 font-black uppercase tracking-widest">Pusat
                                Kendala Teknis
                                SIAK</p>
                        </div>

                        <form action="{{ route('trouble.store') }}" method="POST" enctype="multipart/form-data"
                            class="space-y-5">
                            @csrf
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <select name="kategori" required
                                    class="w-full bg-white border border-red-100 rounded-2xl p-4 text-xs font-bold focus:border-red-500 outline-none appearance-none">
                                    <option value="" disabled selected>Pilih...</option>

                                    {{-- Tambahkan kata kunci yang dikenali oleh filter Dashboard --}}
                                    <option value="Jaringan">🌐 Jaringan</option>
                                    <option value="Komputer/PC">💻 Komputer/PC</option>
                                    <option value="Perangkat Pendukung">🖨️ Perangkat Pendukung</option>
                                </select>

                                <div x-data="{ 
                                        previews: [], 
                                        handleTroubleFiles(event) {
                                            const files = Array.from(event.target.files);
                                            this.previews = []; 
                                            files.forEach(file => {
                                                const reader = new FileReader();
                                                reader.onload = (e) => { this.previews.push(e.target.result); };
                                                reader.readAsDataURL(file);
                                            });
                                        }
                                    }" class="space-y-2">
                                    <label class="text-[10px] font-black text-red-400 uppercase ml-2 block">Foto
                                        Bukti
                                        (Bisa > 1)</label>

                                    <input type="file" name="foto_trouble[]" class="hidden" x-ref="troubleFile"
                                        accept="image/*" required multiple @change="handleTroubleFiles($event)">

                                    <div @click="$refs.troubleFile.click()"
                                        class="bg-white border-2 border-dashed border-red-100 rounded-2xl p-4 text-center cursor-pointer min-h-[56px] flex items-center justify-center">

                                        <div x-show="previews.length === 0"
                                            class="text-[10px] font-black text-red-300 uppercase">
                                            <i class="fas fa-camera mr-2"></i>Pilih Foto
                                        </div>

                                        <div x-show="previews.length > 0" x-cloak
                                            class="flex flex-wrap items-center justify-center gap-2">
                                            <template x-for="(img, index) in previews" :key="index">
                                                <img :src="img"
                                                    class="h-8 w-8 rounded object-cover border border-red-200">
                                            </template>
                                            <span class="text-[8px] font-black text-red-600 uppercase ml-1">Ganti</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-red-400 uppercase ml-2 block">Detail
                                    Deskripsi</label>
                                <textarea name="deskripsi" rows="4" required
                                    class="w-full bg-white border border-red-100 rounded-2xl p-4 text-sm focus:border-red-500 outline-none font-semibold shadow-inner"
                                    placeholder="Ceritakan detail kendala..."></textarea>
                            </div>

                            <button type="submit"
                                class="w-full bg-red-600 text-white font-black py-4 rounded-2xl shadow-lg uppercase tracking-widest text-[10px] active:scale-95 transition-all hover:bg-red-700">
                                KIRIM LOG ERROR KE ADMIN
                            </button>
                        </form>
                    </div>
                </div>
        </main>
    </div>

    @if($errors->any())
        <div class="fixed top-20 right-5 left-5 md:left-auto z-[70] max-w-sm">
            <div class="bg-rose-500 text-white p-4 rounded-2xl shadow-2xl border-b-4 border-rose-800">
                <div class="flex items-center mb-2">
                    <i class="fas fa-exclamation-triangle mr-3"></i>
                    <span class="text-xs font-black uppercase tracking-widest">Terjadi Kesalahan:</span>
                </div>
                <ul class="text-[10px] font-bold uppercase list-disc ml-8">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

</body>

</html>