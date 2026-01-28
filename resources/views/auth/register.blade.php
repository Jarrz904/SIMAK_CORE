<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMAK - Registrasi Penduduk</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Optimasi scroll halus dan input focus */
        input:focus {
            transform: scale(1.01);
            transition: all 0.2s ease;
        }

        .req-met {
            color: #10b981 !important;
        }

        /* Green-500 */
        .req-unmet {
            color: #94a3b8 !important;
        }

        /* Slate-400 */
        .strength-bar {
            transition: width 0.5s ease, background-color 0.5s ease;
        }
    </style>
</head>

<body class="bg-slate-100 min-h-screen flex flex-col font-sans">

    <nav class="bg-white p-4 shadow-sm border-b-2 border-green-500 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center">
                <i class="fas fa-user-plus text-green-600 text-xl md:text-2xl mr-2 md:mr-3"></i>
                <span class="text-lg md:text-xl font-black text-slate-800 tracking-tighter uppercase">SIMAK <span
                        class="text-green-600">REGISTER</span></span>
            </div>
            <a href="{{ route('login') }}"
                class="text-[10px] md:text-xs font-bold text-slate-500 hover:text-green-600 transition-colors flex items-center">
                <i class="fas fa-arrow-left mr-2"></i> <span class="hidden md:inline">KEMBALI KE</span> LOGIN
            </a>
        </div>
    </nav>

    <main class="flex-grow flex items-center justify-center p-4 md:p-6">
        <div
            class="max-w-md w-full bg-white shadow-2xl rounded-[2rem] overflow-hidden border border-slate-200 my-6 md:my-10">
            <div class="p-6 md:p-10">
                <div class="mb-8 text-center">
                    <h2 class="text-2xl md:text-3xl font-black text-slate-800 uppercase tracking-tight">Buat Akun</h2>
                    <p class="text-slate-500 mt-2 text-xs md:text-sm">Lengkapi data identitas kependudukan Anda.</p>
                </div>

                <form action="{{ url('/register') }}" method="POST" class="space-y-4" id="regForm">
                    @csrf

                    <div>
                        <label
                            class="block text-[10px] font-bold text-slate-500 uppercase mb-2 tracking-widest ml-1">Nomor
                            Induk Kependudukan (NIK)</label>
                        <div class="relative group">
                            <span
                                class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 group-focus-within:text-green-500 transition-colors">
                                <i class="fas fa-address-card"></i>
                            </span>
                            <input type="number" name="nik"
                                class="w-full pl-11 pr-4 py-3 bg-slate-50 border-2 border-slate-200 rounded-xl focus:ring-4 focus:ring-green-500/10 focus:border-green-500 outline-none font-semibold text-slate-700 transition-all placeholder:font-normal"
                                placeholder="Contoh: 3201xxxxxxxxxxxx" required>
                        </div>
                        @error('nik') <p class="text-red-500 text-[10px] mt-1 font-bold ml-1 italic">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label
                            class="block text-[10px] font-bold text-slate-500 uppercase mb-2 tracking-widest ml-1">Nama
                            Lengkap</label>
                        <div class="relative group">
                            <span
                                class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 group-focus-within:text-green-500 transition-colors">
                                <i class="fas fa-user"></i>
                            </span>
                            <input type="text" name="name"
                                class="w-full pl-11 pr-4 py-3 bg-slate-50 border-2 border-slate-200 rounded-xl focus:ring-4 focus:ring-green-500/10 focus:border-green-500 outline-none font-semibold text-slate-700 transition-all placeholder:font-normal"
                                placeholder="Sesuai KTP (Tanpa Gelar)" required>
                        </div>
                        @error('name') <p class="text-red-500 text-[10px] mt-1 font-bold ml-1 italic">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label
                            class="block text-[10px] font-bold text-slate-500 uppercase mb-2 tracking-widest ml-1">Alamat
                            Email (Opsional)</label>
                        <div class="relative group">
                            <span
                                class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 group-focus-within:text-green-500 transition-colors">
                                <i class="fas fa-envelope"></i>
                            </span>
                            <input type="email" name="email"
                                class="w-full pl-11 pr-4 py-3 bg-slate-50 border-2 border-slate-200 rounded-xl focus:ring-4 focus:ring-green-500/10 focus:border-green-500 outline-none font-semibold text-slate-700 transition-all placeholder:font-normal"
                                placeholder="nama@email.com">
                        </div>
                        @error('email') <p class="text-red-500 text-[10px] mt-1 font-bold ml-1 italic">{{ $message }}
                        </p> @enderror
                    </div>

                    <div>
                        <label
                            class="block text-[10px] font-bold text-slate-500 uppercase mb-2 tracking-widest ml-1">Alamat
                            Domisili</label>
                        <div class="relative group">
                            <span
                                class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 group-focus-within:text-green-500 transition-colors">
                                <i class="fas fa-map-marker-alt"></i>
                            </span>
                            <input type="text" name="location"
                                class="w-full pl-11 pr-4 py-3 bg-slate-50 border-2 border-slate-200 rounded-xl focus:ring-4 focus:ring-green-500/10 focus:border-green-500 outline-none font-semibold text-slate-700 transition-all placeholder:font-normal"
                                placeholder="Dusun / RT / RW / Desa" required>
                        </div>
                        @error('location') <p class="text-red-500 text-[10px] mt-1 font-bold ml-1 italic">{{ $message }}
                        </p> @enderror
                    </div>

                    <div>
                        <label
                            class="block text-[10px] font-bold text-slate-500 uppercase mb-2 tracking-widest ml-1">Sistem
                            PIN (Min. 4 Digit)</label>
                        <div class="relative group">
                            <span
                                class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 group-focus-within:text-green-500 transition-colors">
                                <i class="fas fa-key"></i>
                            </span>
                            <input type="password" name="pin"
                                class="w-full pl-11 pr-4 py-3 bg-slate-50 border-2 border-slate-200 rounded-xl focus:ring-4 focus:ring-green-500/10 focus:border-green-500 outline-none font-semibold text-slate-700 transition-all placeholder:font-normal"
                                placeholder="Buat PIN Rahasia" required>
                        </div>
                        @error('pin') <p class="text-red-500 text-[10px] mt-1 font-bold ml-1 italic">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label
                                class="block text-[10px] font-bold text-slate-500 uppercase mb-2 tracking-widest ml-1">Password</label>
                            <div class="relative group">
                                <span
                                    class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 group-focus-within:text-green-500 transition-colors">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <input type="password" name="password" id="passwordMain"
                                    class="w-full pl-11 pr-4 py-3 bg-slate-50 border-2 border-slate-200 rounded-xl focus:ring-2 focus:ring-green-500 outline-none font-semibold transition-all placeholder:font-normal"
                                    placeholder="••••••••" required>
                            </div>
                        </div>
                        <div>
                            <label
                                class="block text-[10px] font-bold text-slate-500 uppercase mb-2 tracking-widest ml-1">Konfirmasi</label>
                            <div class="relative group">
                                <span
                                    class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 group-focus-within:text-green-500 transition-colors">
                                    <i class="fas fa-check-double"></i>
                                </span>
                                <input type="password" name="password_confirmation"
                                    class="w-full pl-11 pr-4 py-3 bg-slate-50 border-2 border-slate-200 rounded-xl focus:ring-2 focus:ring-green-500 outline-none font-semibold transition-all placeholder:font-normal"
                                    placeholder="••••••••" required>
                            </div>
                        </div>
                    </div>

                    <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200 space-y-3">
                        <div class="flex justify-between items-center">
                            <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest italic">Kekuatan:
                            </p>
                            <span id="strength-text" class="text-[10px] font-black uppercase text-slate-400">Belum
                                Diisi</span>
                        </div>

                        <div class="w-full bg-slate-200 h-1.5 rounded-full overflow-hidden">
                            <div id="strength-bar" class="strength-bar h-full w-0 bg-slate-400"></div>
                        </div>

                        <div class="grid grid-cols-2 gap-2 mt-2">
                            <div id="req-cap"
                                class="req-unmet text-[9px] font-bold flex items-center transition-colors">
                                <i class="fas fa-circle text-[5px] mr-2"></i> Huruf Kapital
                            </div>
                            <div id="req-num"
                                class="req-unmet text-[9px] font-bold flex items-center transition-colors">
                                <i class="fas fa-circle text-[5px] mr-2"></i> Angka (0-9)
                            </div>
                            <div id="req-sym"
                                class="req-unmet text-[9px] font-bold flex items-center transition-colors">
                                <i class="fas fa-circle text-[5px] mr-2"></i> Simbol (!@#$)
                            </div>
                            <div id="req-len"
                                class="req-unmet text-[9px] font-bold flex items-center transition-colors">
                                <i class="fas fa-circle text-[5px] mr-2"></i> Min. 8 Karakter
                            </div>
                        </div>
                    </div>

                    @error('password') <p class="text-red-500 text-[10px] mt-1 font-bold ml-1 italic">{{ $message }}</p>
                    @enderror

                    <div class="pt-6">
                        <button type="submit"
                            class="w-full bg-green-600 text-white font-black py-4 rounded-2xl hover:bg-green-700 active:scale-95 transform transition-all shadow-xl shadow-green-200 flex items-center justify-center space-x-3 group">
                            <span class="uppercase tracking-widest text-xs">DAFTAR SEKARANG</span>
                            <i class="fas fa-user-check text-xs group-hover:rotate-12 transition-transform"></i>
                        </button>
                    </div>
                </form>

                <div class="mt-8 pt-6 border-t border-slate-100 text-center">
                    <p class="text-[10px] text-slate-400 uppercase tracking-widest leading-relaxed">
                        Data identitas Anda akan diverifikasi oleh sistem <br>
                        <span class="font-bold text-slate-500">SIMAK CORE SECURITY</span>.
                    </p>
                </div>
            </div>
        </div>
    </main>

    <footer class="py-6 text-center text-slate-400 text-[10px] uppercase tracking-[0.2em] font-bold">
        &copy; 2026 SIMAK CORE - Maintenance Kependudukan
    </footer>

    <script>
        const passInput = document.getElementById('passwordMain');
        const reqCap = document.getElementById('req-cap');
        const reqNum = document.getElementById('req-num');
        const reqSym = document.getElementById('req-sym');
        const reqLen = document.getElementById('req-len');

        const strengthBar = document.getElementById('strength-bar');
        const strengthText = document.getElementById('strength-text');

        passInput.addEventListener('input', function () {
            const val = this.value;
            let score = 0;

            // Kriteria 1: Kapital
            const hasCap = /[A-Z]/.test(val);
            toggleReq(reqCap, hasCap);
            if (hasCap) score++;

            // Kriteria 2: Angka
            const hasNum = /[0-9]/.test(val);
            toggleReq(reqNum, hasNum);
            if (hasNum) score++;

            // Kriteria 3: Simbol
            const hasSym = /[^A-Za-z0-9]/.test(val);
            toggleReq(reqSym, hasSym);
            if (hasSym) score++;

            // Kriteria 4: Panjang
            const hasLen = val.length >= 8;
            toggleReq(reqLen, hasLen);
            if (hasLen) score++;

            // Update UI Strength Meter
            updateStrengthMeter(score, val.length);
        });

        function toggleReq(el, isMet) {
            if (isMet) {
                el.classList.remove('req-unmet');
                el.classList.add('req-met');
                el.querySelector('i').className = 'fas fa-check-circle text-[7px] mr-2';
            } else {
                el.classList.remove('req-met');
                el.classList.add('req-unmet');
                el.querySelector('i').className = 'fas fa-circle text-[5px] mr-2';
            }
        }

        function updateStrengthMeter(score, length) {
            if (length === 0) {
                strengthBar.style.width = '0%';
                strengthBar.className = 'strength-bar h-full bg-slate-400';
                strengthText.innerText = 'Belum Diisi';
                strengthText.className = 'text-[10px] font-black uppercase text-slate-400';
                return;
            }

            // Logika Penentuan Level
            if (score <= 1) {
                strengthBar.style.width = '25%';
                strengthBar.className = 'strength-bar h-full bg-red-500';
                strengthText.innerText = 'Sangat Lemah';
                strengthText.className = 'text-[10px] font-black uppercase text-red-500';
            } else if (score === 2) {
                strengthBar.style.width = '50%';
                strengthBar.className = 'strength-bar h-full bg-orange-400';
                strengthText.innerText = 'Lemah';
                strengthText.className = 'text-[10px] font-black uppercase text-orange-400';
            } else if (score === 3) {
                strengthBar.style.width = '75%';
                strengthBar.className = 'strength-bar h-full bg-yellow-400';
                strengthText.innerText = 'Sedang';
                strengthText.className = 'text-[10px] font-black uppercase text-yellow-500';
            } else if (score === 4) {
                strengthBar.style.width = '100%';
                strengthBar.className = 'strength-bar h-full bg-green-500';
                strengthText.innerText = 'Sangat Kuat';
                strengthText.className = 'text-[10px] font-black uppercase text-green-500';
            }
        }
    </script>
</body>

</html>