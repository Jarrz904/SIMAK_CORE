<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setel Ulang Password | SIMAK</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-[#0f172a] flex items-center justify-center min-h-screen p-6">

    <div class="max-w-md w-full bg-[#1e293b] rounded-2xl shadow-2xl border border-slate-700 overflow-hidden">
        <div class="bg-orange-600 p-6 text-center">
            <h2 class="text-xl font-bold text-white uppercase tracking-wider">Password Baru</h2>
            <p class="text-orange-100 text-xs">Silakan buat password yang kuat</p>
        </div>

        <form action="{{ route('password.update') }}" method="POST" class="p-8">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="mb-6 p-4 bg-[#0f172a] border border-orange-500/30 rounded-xl text-center">
                <p class="text-slate-400 text-[10px] uppercase tracking-widest mb-1 text-orange-200">Sesi Reset Berakhir Dalam</p>
                <div id="timer" class="text-3xl font-mono font-bold text-orange-500">
                    60s
                </div>
            </div>

            @if ($errors->any())
                <div class="mb-4 p-3 bg-red-500/10 border border-red-500/50 rounded-xl">
                    @foreach ($errors->all() as $error)
                        <p class="text-red-500 text-xs mt-1"><i class="fas fa-exclamation-circle mr-1"></i> {{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <div class="mb-5">
                <label class="block text-slate-300 text-xs font-semibold mb-2 uppercase">Konfirmasi Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                    class="w-full px-4 py-3 bg-[#0f172a] border border-slate-600 rounded-xl text-slate-200 focus:outline-none focus:border-orange-500 transition-all @error('email') border-red-500 @enderror"
                    placeholder="Masukkan kembali email Anda">
            </div>

            <div class="mb-5">
                <label class="block text-slate-300 text-xs font-semibold mb-2 uppercase">Password Baru</label>
                <input type="password" name="password" required
                    class="w-full px-4 py-3 bg-[#0f172a] border border-slate-600 rounded-xl text-slate-200 focus:outline-none focus:border-orange-500 transition-all @error('password') border-red-500 @enderror"
                    placeholder="Minimal 8 karakter">
            </div>

            <div class="mb-8">
                <label class="block text-slate-300 text-xs font-semibold mb-2 uppercase">Ulangi Password Baru</label>
                <input type="password" name="password_confirmation" required
                    class="w-full px-4 py-3 bg-[#0f172a] border border-slate-600 rounded-xl text-slate-200 focus:outline-none focus:border-orange-500 transition-all"
                    placeholder="Ulangi password baru">
            </div>

            <button type="submit" id="submitBtn"
                class="w-full bg-orange-600 hover:bg-orange-700 text-white font-bold py-3 rounded-xl shadow-lg transition-all active:scale-95">
                SIMPAN PASSWORD BARU
            </button>
        </form>
    </div>

    <script>
        function startCountdown(durationInSeconds) {
            let timer = durationInSeconds;
            const display = document.querySelector('#timer');
            const submitBtn = document.querySelector('#submitBtn');

            const countdown = setInterval(function () {
                // Tampilan format detik murni (contoh: 59s)
                display.textContent = timer + "s";

                // Efek visual jika waktu kritis (di bawah 15 detik)
                if (timer <= 15) {
                    display.classList.remove('text-orange-500');
                    display.classList.add('text-red-500', 'animate-pulse');
                }

                if (--timer < 0) {
                    clearInterval(countdown);
                    display.textContent = "EXPIRED";
                    
                    // Menonaktifkan form
                    submitBtn.disabled = true;
                    submitBtn.classList.replace('bg-orange-600', 'bg-slate-600');
                    submitBtn.classList.add('cursor-not-allowed');
                    
                    alert('Waktu reset password (60 detik) telah habis. Silakan minta link baru.');
                    window.location.href = "{{ route('password.request') }}";
                }
            }, 1000);
        }

        // Mulai countdown 60 detik saat halaman dimuat
        window.onload = function () {
            startCountdown(60); 
        };
    </script>
</body>
</html>