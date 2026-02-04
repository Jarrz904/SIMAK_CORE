<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>SIMAK - Sistem Maintenance Kependudukan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        [x-cloak] {
            display: none !important;
        }

        .transition-all {
            transition: all 0.3s ease-in-out;
        }

        /* Animasi Slide Up untuk Mobile */
        @media (max-width: 767px) {
            body {
                overflow: hidden;
                /* Mencegah scroll double pada body saat mobile */
            }

            .mobile-content-wrapper {
                position: relative;
                height: calc(100vh - 64px);
                /* Tinggi layar dikurangi navbar */
                overflow: hidden;
            }

            .info-panel {
                height: 100%;
                width: 100%;
                z-index: 20;
                transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
            }

            .login-panel {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                z-index: 30;
                transform: translateY(100%);
                transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
                overflow-y: auto;
                /* Memungkinkan scroll di dalam form login */
                padding-bottom: 40px;
            }

            /* Class saat panel login aktif */
            .show-login .info-panel {
                transform: translateY(-100%);
            }

            .show-login .login-panel {
                transform: translateY(0);
            }

            /* Gaya Handle untuk Slide */
            .slide-handle {
                width: 50px;
                height: 6px;
                background: #e2e8f0;
                border-radius: 10px;
                margin: 0 auto 24px;
                cursor: pointer;
            }
        }

        /* Smooth Hover Effects */
        .btn-hover-effect:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body class="bg-slate-50 min-h-screen font-sans">

    <nav class="bg-white p-4 shadow-sm border-b-2 border-orange-500 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center">
                <i class="fas fa-tools text-orange-600 text-xl md:text-2xl mr-3"></i>
                <span class="text-lg md:text-xl font-black text-slate-800 tracking-tighter uppercase">SIMAK <span
                        class="text-orange-600">SYSTEM</span></span>
            </div>
        </div>
    </nav>

    <main id="main-container" class="mobile-content-wrapper flex items-center justify-center md:p-8">
        <div
            class="max-w-6xl w-full grid grid-cols-1 md:grid-cols-2 gap-0 bg-white md:shadow-2xl md:rounded-3xl overflow-hidden border-slate-200 relative h-full md:h-auto border">

            <div id="info-section"
                class="info-panel bg-slate-800 p-6 md:p-12 text-white flex flex-col justify-center relative overflow-hidden min-h-[500px]">

                <div class="absolute -bottom-10 -right-10 opacity-10 z-0 pointer-events-none">
                    <i class="fas fa-cogs text-[150px] md:text-[200px]"></i>
                </div>

                <div class="mb-6 md:mb-8 group w-fit relative z-10">
                    <div
                        class="absolute -inset-2 bg-orange-600 rounded-2xl blur-xl opacity-20 group-hover:opacity-50 transition duration-700">
                    </div>
                    <div class="relative">
                        <div
                            class="w-14 h-14 md:w-16 md:h-16 bg-gradient-to-br from-slate-700 to-slate-900 rounded-2xl flex items-center justify-center shadow-2xl border border-slate-600 transform -rotate-6 group-hover:rotate-0 group-hover:scale-110 transition-all duration-500 ease-out overflow-hidden">
                            <i
                                class="fas fa-tools text-xl md:text-2xl text-orange-500 drop-shadow-[0_0_12px_rgba(249,115,22,0.9)] group-hover:text-white transition-colors"></i>
                        </div>
                        <div class="absolute -top-2 -right-2 w-7 h-7 md:w-8 md:h-8 bg-orange-600 rounded-lg flex items-center justify-center shadow-lg transform rotate-12 animate-spin"
                            style="animation-duration: 4s;">
                            <i class="fas fa-cog text-white text-[9px] md:text-[10px]"></i>
                        </div>
                    </div>
                </div>

                <h1
                    class="text-2xl md:text-4xl font-black mb-4 md:mb-6 leading-tight uppercase italic tracking-tighter relative z-10">
                    Sistem Maintenance <br><span class="text-orange-500 not-italic">Kependudukan</span>
                </h1>

                <p class="text-slate-400 text-sm md:text-lg mb-6 md:mb-8 italic relative z-10 leading-relaxed max-w-md">
                    Platform integrasi untuk percepatan perbaikan data, aktivasi identitas, dan pelaporan kendala
                    infrastruktur digital desa.
                </p>

                <div class="space-y-5 md:space-y-7 relative z-10">
                    <div class="flex items-center space-x-5 group/item cursor-default">
                        <div
                            class="bg-orange-600 w-11 h-11 md:w-14 md:h-14 flex items-center justify-center rounded-2xl text-white shadow-[0_10px_20px_-5px_rgba(234,88,12,0.4)] shrink-0 transition-transform group-hover/item:scale-110 duration-300">
                            <i class="fas fa-sync-alt text-base md:text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-black text-[11px] md:text-sm uppercase tracking-wider text-slate-100">
                                Konsolidasi Data</h4>
                            <p class="text-[10px] md:text-xs text-slate-400 leading-tight mt-1">Sinkronisasi NIK tidak
                                aktif dengan database pusat.</p>
                        </div>
                    </div>

                    <div class="flex items-center space-x-5 group/item cursor-default">
                        <div
                            class="bg-blue-600 w-11 h-11 md:w-14 md:h-14 flex items-center justify-center rounded-2xl text-white shadow-[0_10px_20px_-5px_rgba(37,99,235,0.4)] shrink-0 transition-transform group-hover/item:scale-110 duration-300">
                            <i class="fas fa-fingerprint text-base md:text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-black text-[11px] md:text-sm uppercase tracking-wider text-slate-100">
                                Aktivasi IKD</h4>
                            <p class="text-[10px] md:text-xs text-slate-400 leading-tight mt-1">Aktivasi dan verifikasi
                                Identitas Kependudukan Digital.</p>
                        </div>
                    </div>

                    <div class="flex items-center space-x-5 group/item cursor-default">
                        <div
                            class="bg-green-600 w-11 h-11 md:w-14 md:h-14 flex items-center justify-center rounded-2xl text-white shadow-[0_10px_20px_-5px_rgba(22,163,74,0.4)] shrink-0 transition-transform group-hover/item:scale-110 duration-300">
                            <i class="fas fa-server text-base md:text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-black text-[11px] md:text-sm uppercase tracking-wider text-slate-100">
                                Maintenance VPN</h4>
                            <p class="text-[10px] md:text-xs text-slate-400 leading-tight mt-1">Pemeliharaan jalur
                                komunikasi data SIAK terpusat.</p>
                        </div>
                    </div>
                </div>

                <div class="md:hidden mt-10 flex flex-col items-center animate-bounce cursor-pointer relative z-10"
                    onclick="openLogin()">
                    <span class="text-[9px] font-black tracking-[0.3em] mb-2 uppercase text-orange-500">Slide Up to
                        Login</span>
                    <i class="fas fa-chevron-up text-orange-500"></i>
                </div>
            </div>

            <div id="login-section" class="login-panel p-6 md:p-12 bg-white flex flex-col justify-center">
                <div class="md:hidden slide-handle" onclick="closeLogin()"></div>

                <div class="mb-6 md:mb-8 flex justify-between items-start">
                    <div>
                        <h2 class="text-xl md:text-3xl font-black text-slate-800 uppercase tracking-tighter italic">
                            Login Portal</h2>
                        <p class="text-slate-500 mt-1 text-xs md:text-sm">Masuk berdasarkan otoritas peran Anda.</p>
                    </div>
                    <button onclick="closeLogin()"
                        class="md:hidden text-slate-400 hover:text-slate-600 transition-colors p-2">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>

                <div class="flex p-1 bg-slate-100 rounded-xl md:rounded-2xl mb-6 md:mb-8 border border-slate-200">
                    <button type="button" onclick="switchLogin('user')" id="btn-user"
                        class="flex-1 flex items-center justify-center space-x-2 py-2.5 md:py-3 rounded-lg md:rounded-xl text-[9px] md:text-[10px] font-black tracking-widest transition-all bg-white shadow-sm text-slate-800 uppercase">
                        <i class="fas fa-user"></i>
                        <span>USER ACCESS</span>
                    </button>
                    <button type="button" onclick="switchLogin('admin')" id="btn-admin"
                        class="flex-1 flex items-center justify-center space-x-2 py-2.5 md:py-3 rounded-lg md:rounded-xl text-[9px] md:text-[10px] font-black tracking-widest transition-all text-slate-500 hover:text-slate-700 uppercase">
                        <i class="fas fa-user-shield"></i>
                        <span>ADMIN ACCESS</span>
                    </button>
                </div>

                @if($errors->any())
                    <div class="bg-red-50 border-l-4 border-red-600 text-red-700 p-3 md:p-4 mb-6 rounded-xl animate-pulse">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-triangle mr-2 text-xs"></i>
                            <span class="font-bold text-[10px] md:text-xs uppercase italic">
                                @if($errors->has('loginError'))
                                    {{ $errors->first('loginError') }}
                                @else
                                    Kredensial tidak sesuai.
                                @endif
                            </span>
                        </div>
                    </div>
                @endif

                <form action="{{ route('login.process') }}" method="POST" class="space-y-4 md:space-y-5">
                    @csrf
                    <input type="hidden" name="role" id="input-role" value="user">

                    <div class="space-y-2">
                        <label id="label-login"
                            class="block text-[9px] md:text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">
                            Personal Identification Number (PIN)
                        </label>
                        <div class="relative group">
                            <span
                                class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 group-focus-within:text-orange-500 transition-colors">
                                <i class="fas fa-key text-xs md:text-sm" id="icon-login"></i>
                            </span>
                            <input type="text" name="login_identity" id="input-login"
                                value="{{ old('login_identity') }}"
                                class="w-full pl-11 pr-4 py-3.5 md:py-4 bg-slate-50 border-2 border-slate-100 rounded-xl md:rounded-2xl focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 outline-none transition-all font-bold text-sm md:text-base text-slate-700"
                                placeholder="Masukkan PIN" required>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label
                            class="block text-[9px] md:text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">
                            System Password
                        </label>
                        <div class="relative group">
                            <span
                                class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 group-focus-within:text-orange-500 transition-colors">
                                <i class="fas fa-lock text-xs md:text-sm"></i>
                            </span>
                            <input type="password" name="password"
                                class="w-full pl-11 pr-4 py-3.5 md:py-4 bg-slate-50 border-2 border-slate-100 rounded-xl md:rounded-2xl focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 outline-none transition-all font-bold text-sm md:text-base text-slate-700"
                                placeholder="••••••••" required>
                        </div>
                    </div>

                    <div class="pt-2 md:pt-4">
                        <button id="btn-submit" type="submit"
                            class="w-full bg-slate-800 text-white font-black py-4 rounded-xl md:rounded-2xl hover:bg-orange-600 transform transition-all duration-300 shadow-xl flex items-center justify-center space-x-3 group btn-hover-effect">
                            <span id="text-submit" class="uppercase tracking-[0.2em] text-[10px] md:text-xs">LOGIN
                                USER</span>
                            <i
                                class="fas fa-sign-in-alt text-[10px] md:text-xs group-hover:translate-x-1 transition-transform"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <footer class="py-6 text-center text-slate-400 text-[10px] uppercase tracking-[0.3em] font-bold hidden md:block">
        &copy; 2026 SIMAK • Maintenance Kependudukan Desa Digital
    </footer>

    <script>
        // Logika Slide Mobile
        let startY = 0;
        const mainContainer = document.getElementById('main-container');

        // Deteksi Touch Start
        mainContainer.addEventListener('touchstart', e => {
            startY = e.touches[0].pageY;
        }, { passive: true });

        // Deteksi Touch End (Swipe)
        mainContainer.addEventListener('touchend', e => {
            let endY = e.changedTouches[0].pageY;
            let diff = startY - endY;

            if (diff > 50) { // Swipe Up
                openLogin();
            } else if (diff < -50) { // Swipe Down
                closeLogin();
            }
        }, { passive: true });

        function openLogin() {
            if (window.innerWidth < 768) {
                mainContainer.classList.add('show-login');
            }
        }

        function closeLogin() {
            mainContainer.classList.remove('show-login');
        }

        // Fungsi Switch Role
        function switchLogin(role) {
            const btnUser = document.getElementById('btn-user');
            const btnAdmin = document.getElementById('btn-admin');
            const labelLogin = document.getElementById('label-login');
            const inputLogin = document.getElementById('input-login');
            const inputRole = document.getElementById('input-role');
            const textSubmit = document.getElementById('text-submit');
            const btnSubmit = document.getElementById('btn-submit');
            const iconLogin = document.getElementById('icon-login');

            // Reset classes
            [btnUser, btnAdmin].forEach(btn => {
                btn.classList.remove('bg-white', 'shadow-sm', 'text-slate-800');
                btn.classList.add('text-slate-500');
            });

            if (role === 'admin') {
                btnAdmin.classList.add('bg-white', 'shadow-sm', 'text-slate-800');
                btnAdmin.classList.remove('text-slate-500');

                labelLogin.innerText = 'Administrator Identification Number (PIN)';
                inputLogin.placeholder = 'Masukkan PIN Admin';
                inputRole.value = 'admin';
                textSubmit.innerText = 'LOGIN ADMIN';
                iconLogin.className = 'fas fa-user-shield';

                btnSubmit.classList.replace('bg-slate-800', 'bg-orange-600');
            } else {
                btnUser.classList.add('bg-white', 'shadow-sm', 'text-slate-800');
                btnUser.classList.remove('text-slate-500');

                labelLogin.innerText = 'Personal Identification Number (PIN)';
                inputLogin.placeholder = 'Masukkan PIN Anda';
                inputRole.value = 'user';
                textSubmit.innerText = 'LOGIN USER';
                iconLogin.className = 'fas fa-key';

                btnSubmit.classList.replace('bg-orange-600', 'bg-slate-800');
            }
        }
    </script>
</body>

</html>