<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password | SIMAK Maintenance</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-[#0f172a] flex items-center justify-center min-h-screen p-6">

    <div class="max-w-md w-full bg-[#1e293b] rounded-2xl shadow-2xl border border-slate-700 overflow-hidden">
        <div class="bg-orange-600 p-8 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-white/20 rounded-full mb-4">
                <i class="fas fa-key text-3xl text-white"></i>
            </div>
            <h2 class="text-2xl font-bold text-white uppercase tracking-wider">Pemulihan Akses</h2>
            <p class="text-orange-100 text-sm mt-1">Sistem Maintenance Kependudukan</p>
        </div>

        <div class="p-8">
            @if (session('status'))
                <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/50 rounded-lg text-emerald-400 text-sm flex items-center">
                    <i class="fas fa-check-circle mr-3"></i>
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 p-4 bg-rose-500/10 border border-rose-500/50 rounded-lg text-rose-400 text-sm">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <p class="text-slate-400 text-sm mb-6 leading-relaxed">
                Masukkan alamat email yang terdaftar. Kami akan mengirimkan instruksi untuk mengatur ulang password Anda melalui Gmail.
            </p>

            <form action="{{ route('password.email') }}" method="POST">
                @csrf
                <div class="mb-6">
                    <label class="block text-slate-300 text-xs font-semibold uppercase tracking-wider mb-2">Alamat Gmail</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-500">
                            <i class="fas fa-envelope"></i>
                        </span>
                        <input type="email" name="email" required
                            class="w-full pl-10 pr-4 py-3 bg-[#0f172a] border border-slate-600 rounded-xl text-slate-200 focus:outline-none focus:border-orange-500 transition-all placeholder:text-slate-600"
                            placeholder="nama@gmail.com">
                    </div>
                </div>

                <button type="submit" 
                    class="w-full bg-orange-600 hover:bg-orange-700 text-white font-bold py-3 rounded-xl shadow-lg shadow-orange-900/20 transition-all flex items-center justify-center">
                    <i class="fas fa-paper-plane mr-2"></i>
                    KIRIM LINK RESET
                </button>
            </form>

            <div class="mt-8 pt-6 border-t border-slate-700 text-center">
                <a href="{{ route('login') }}" class="text-slate-400 hover:text-orange-500 text-sm transition-all">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali ke Login
                </a>
            </div>
        </div>

        <div class="bg-slate-900/50 p-4 text-center border-t border-slate-700">
            <span class="text-[10px] text-slate-500 uppercase tracking-widest">
                <i class="fas fa-shield-alt mr-1"></i> Secure Encryption Active
            </span>
        </div>
    </div>

</body>
</html>