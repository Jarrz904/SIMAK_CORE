<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail; // Tambahan untuk fitur Gmail
use Illuminate\Support\Str; // Tambahan untuk generate token
use Illuminate\Validation\Rules\Password; // Penting untuk validasi keamanan tingkat tinggi
use Carbon\Carbon; // Tambahan untuk logika waktu hangus

class AuthController extends Controller
{
    /**
     * Menampilkan halaman login
     */
    public function showLogin()
    {
        // Jika sudah login, langsung arahkan ke dashboard masing-masing
        if (Auth::check()) {
            return $this->redirectBasedOnRole();
        }
        return view('welcome');
    }

    /**
     * Menampilkan halaman registrasi
     */
    public function showRegister()
    {
        // Jika sudah login, tidak perlu daftar lagi
        if (Auth::check()) {
            return $this->redirectBasedOnRole();
        }
        return view('auth.register');
    }

    /**
     * Proses Registrasi Penduduk Baru
     * Perbaikan: Menambahkan validasi Huruf Kapital, Angka, dan Simbol
     */
    public function register(Request $request)
    {
        // Validasi Input sesuai dengan form (NIK, Nama, Email, Lokasi, PIN, Password)
        $request->validate([
            'nik' => 'required|numeric|digits:16|unique:users,nik',
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:users,email',
            'location' => 'required|string|max:500',
            'pin' => 'required|numeric|min:4|unique:users,pin',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                Password::min(8)
                    ->mixedCase()   // Wajib Huruf Besar & Kecil
                    ->numbers()     // Wajib Angka
                    ->symbols(),     // Wajib Simbol (@#$% dll)
            ],
        ], [
            'nik.unique' => 'NIK sudah terdaftar dalam sistem.',
            'nik.digits' => 'NIK harus berjumlah 16 digit.',
            'email.unique' => 'Email sudah digunakan oleh akun lain.',
            'email.email' => 'Format email tidak valid.',
            'pin.unique' => 'PIN sudah digunakan, silakan pilih PIN lain.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.mixed' => 'Password harus mengandung huruf besar dan huruf kecil.',
            'password.numbers' => 'Password harus mengandung setidaknya satu angka.',
            'password.symbols' => 'Password harus mengandung setidaknya satu simbol/karakter khusus.',
        ]);

        // Simpan Data ke Database
        User::create([
            'nik' => $request->nik,
            'name' => $request->name,
            'location' => $request->location,
            'pin' => $request->pin,
            // LOGIKA EMAIL: Gunakan input email jika ada, jika tidak gunakan NIK sebagai fallback
            'email' => $request->email ?? ($request->nik . '@siak.local'),
            'password' => Hash::make($request->password),
            'role' => 'user',
        ]);

        // Redirect ke login dengan pesan sukses
        return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan login menggunakan PIN atau Email Anda.');
    }

    /**
     * Proses Login Multi-Identitas (Email atau PIN) dengan Batasan Role
     */
    public function login(Request $request)
    {
        $request->validate([
            'login_identity' => 'required',
            'password' => 'required',
            'role' => 'required' // Menangkap role dari input hidden di form login
        ]);

        $identity = $request->login_identity;
        $password = $request->password;
        $requestedRole = $request->role; // 'admin' atau 'user'

        // 1. Cek apakah input adalah email atau PIN
        $fieldType = filter_var($identity, FILTER_VALIDATE_EMAIL) ? 'email' : 'pin';

        // 2. Cari user terlebih dahulu untuk pengecekan keamanan tambahan
        $user = User::where($fieldType, $identity)->first();

        // 3. Jika user tidak ditemukan
        if (!$user) {
            return back()->withErrors([
                'loginError' => 'Identitas (PIN/Email) tidak terdaftar dalam sistem.',
            ])->withInput($request->only('login_identity'));
        }

        // 4. Validasi Role: Pastikan user login di pintu yang benar (Limited Access)
        if ($user->role !== $requestedRole) {
            return back()->withErrors([
                'loginError' => 'Akses Ditolak! Akun Anda tidak terdaftar sebagai ' . strtoupper($requestedRole) . '.',
            ])->withInput($request->only('login_identity'));
        }

        // 5. Cek Password secara manual agar pesan error lebih spesifik (Salah Password)
        if (!Hash::check($password, $user->password)) {
            return back()->withErrors([
                'loginError' => 'Password yang Anda masukkan salah. Silakan coba lagi.',
            ])->withInput($request->only('login_identity'));
        }

        // 6. Lakukan Attempt Login jika semua validasi di atas lolos
        if (Auth::attempt([$fieldType => $identity, 'password' => $password])) {
            $request->session()->regenerate();
            return $this->redirectBasedOnRole();
        }

        // Backup Error jika attempt gagal karena alasan teknis lainnya
        return back()->withErrors([
            'loginError' => 'Gagal masuk ke sistem. Silakan periksa kredensial Anda.',
        ])->withInput($request->only('login_identity'));
    }

    /**
     * Logika Pengalihan Berdasarkan Role
     */
    private function redirectBasedOnRole()
    {
        $role = Auth::user()->role;

        if ($role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        if ($role === 'user' || $role === 'penduduk') {
            return redirect()->route('user.dashboard');
        }

        Auth::logout();
        return redirect('/')->withErrors(['loginError' => 'Akses ditolak. Periksa role Anda di database.']);
    }

    /**
     * Proses Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    /**
     * --- TAMBAHAN BARU: FITUR RESET PASSWORD VIA GMAIL ---
     */
    public function sendResetLink(Request $request)
    {
        // 1. Validasi input email
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.exists' => 'Alamat email tersebut tidak terdaftar di sistem kami.'
        ]);

        // 2. Ambil data user
        $user = User::where('email', $request->email)->first();

        // 3. Generate token dan simpan waktu pembuatan (Untuk expiry time)
        $token = Str::random(60);
        $user->update([
            'reset_token' => $token,
            'token_created_at' => now()
        ]);

        // 4. Siapkan data untuk template email
        $data = [
            'name' => $user->name,
            'url'  => url('/reset-password/' . $token), 
        ];

        try {
            // 5. Proses kirim email menggunakan template yang kita buat tadi
            Mail::send('emails.reset-password', $data, function($message) use ($request) {
                $message->to($request->email);
                $message->subject('🔐 Pemulihan Akses - Sistem Maintenance Kependudukan');
            });

            return back()->with('status', 'Instruksi pemulihan telah dikirim ke email ' . $request->email);

        } catch (\Exception $e) {
            // Jika terjadi kesalahan (View missing atau SMTP Error)
            return back()->withErrors(['email' => 'Gagal mengirim email: ' . $e->getMessage()]);
        }
    }

    /**
     * Menampilkan form untuk menginput password baru (Setelah klik link email)
     */
    public function showResetForm($token)
    {
        return view('auth.reset-password-form', ['token' => $token]);
    }

    /**
     * Memproses penggantian password di database
     * UPDATE: Menggunakan batas waktu 60 DETIK (High Security)
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ],
        ], [
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.symbols' => 'Password baru harus mengandung simbol (@#$% dll).',
        ]);

        // 1. Cari user berdasarkan email dan token yang valid
        $user = User::where('email', $request->email)
                    ->where('reset_token', $request->token)
                    ->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Link reset tidak valid atau token salah.']);
        }

        // 2. LOGIKA HANGUS: Cek apakah token sudah lebih dari 60 DETIK
        // Menggunakan diffInSeconds untuk akurasi tinggi sesuai tampilan 60s di frontend
        $createdAt = Carbon::parse($user->token_created_at);
        if ($createdAt->diffInSeconds(now()) > 60) {
            // Hapus token yang sudah hangus agar tidak bisa dicoba lagi
            $user->update(['reset_token' => null, 'token_created_at' => null]);
            return redirect()->route('password.request')->withErrors(['email' => 'Link reset sudah hangus (melebihi 60 detik). Silakan minta link baru.']);
        }

        // 3. Update password baru (di-hash) dan bersihkan token
        $user->update([
            'password' => Hash::make($request->password),
            'reset_token' => null,
            'token_created_at' => null
        ]);

        return redirect()->route('login')->with('success', 'Password berhasil diperbarui! Silakan login dengan password baru Anda.');
    }
}