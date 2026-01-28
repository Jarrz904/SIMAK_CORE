<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AnnouncementController; 
use App\Http\Controllers\PembubuhanController; // Tambahkan ini
use App\Http\Controllers\LuarDaerahController; // Tambahkan ini

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Seluruh rute aplikasi didefinisikan di sini.
*/

// --- HALAMAN LOGIN / PORTAL UTAMA ---
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::get('/login', [AuthController::class, 'showLogin']);

// --- FITUR REGISTRASI ---
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.process');

// --- FITUR LUPA PASSWORD (GMAIL SMTP) ---
Route::middleware('guest')->group(function () {
    // 1. Menampilkan form input email
    Route::get('/forgot-password', function () {
        return view('auth.forgot-password');
    })->name('password.request');

    // 2. Memproses pengiriman link ke Gmail
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');

    // 3. Menampilkan form input password baru
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');

    // 4. Memproses update password baru
    Route::post('/reset-password', [AuthController::class, 'updatePassword'])->name('password.update');
});

// --- PROSES AUTENTIKASI ---
Route::post('/login', [AuthController::class, 'login'])->name('login.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// --- GRUP MIDDLEWARE AUTH (HARUS LOGIN) ---
Route::middleware(['auth', 'checkStatus'])->group(function () {

    // ==========================================
    // AREA ADMIN (Hanya Role Admin)
    // ==========================================
    Route::middleware('role:admin')->group(function () {
        
        /**
         * PERBAIKAN UTAMA: 
         * Menggunakan 'admin.index' sebagai nama utama. 
         */
        Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.index');
        
        // Alias untuk menghindari error di AuthController jika masih pakai nama lama
        Route::get('/admin/panel', function() {
            return redirect()->route('admin.index');
        })->name('admin.dashboard');

        // --- ROUTE MANAJEMEN USER ---
        Route::prefix('admin/users')->group(function () {
            Route::post('/store', [AdminController::class, 'store'])->name('admin.users.store');
            Route::delete("/{id}", [AdminController::class, 'destroy'])->name('admin.users.destroy');
            Route::put("/{id}/update", [AdminController::class, 'update'])->name('admin.users.update');
            Route::post("/{id}/toggle-status", [AdminController::class, 'toggleStatus'])->name('admin.users.toggle');
        });

        // --- ROUTE MANAJEMEN LAPORAN ---
        Route::prefix('admin/laporan')->group(function () {
            Route::post('/kirim-respon', [AdminController::class, 'kirimRespon'])->name('admin.laporan.respon');
            Route::post('/tolak/{id}', [AdminController::class, 'tolakLaporan'])->name('admin.laporan.tolak');
            Route::delete('/hapus/{id}', [AdminController::class, 'hapusLaporan'])->name('admin.laporan.hapus');
            
            // Menambahkan rute pengelolaan Luar Daerah untuk Admin
            Route::post('/luar-daerah/respon', [AdminController::class, 'responLuarDaerah'])->name('admin.luardaerah.respon');
            
            // --- ROUTE EXPORT ---
            Route::get('/export', [AdminController::class, 'exportExcel'])->name('admin.laporan.export');
            
            /** * PERBAIKAN DI SINI:
             * Menggunakan Route::match untuk mendukung GET dan POST sekaligus.
             * Ini mencegah error 'Method Not Allowed' saat form dikirim via POST.
             */
            Route::match(['get', 'post'], '/export-aktivasi', [AdminController::class, 'exportAktivasi'])->name('export.laporan.aktivasi');
        });

        // --- ROUTE MANAJEMEN PENGUMUMAN ---
        Route::prefix('admin/announcements')->group(function () {
            Route::post('/store', [AnnouncementController::class, 'store'])->name('admin.announcements.store');
            Route::delete('/{announcement}', [AnnouncementController::class, 'destroy'])->name('admin.announcements.destroy');
        });
    });

    // ==========================================
    // AREA USER (Role User/Penduduk)
    // ==========================================
    Route::middleware('role:user')->group(function () {
        Route::get('/user/dashboard', [UserController::class, 'index'])->name('user.dashboard');
        Route::put('/profile/update', [UserController::class, 'updateProfil'])->name('user.profile.update');
        Route::post('/user/notifikasi/{id}/read', [UserController::class, 'markAsRead'])->name('user.notif.read');

        // --- FITUR PEMBUBUHAN TTE ---
        Route::post('/pembubuhan/store', [PembubuhanController::class, 'store'])->name('pembubuhan.store');

        // --- FITUR LUAR DAERAH ---
        Route::post('/user/luar-daerah/store', [LuarDaerahController::class, 'store'])->name('user.luardaerah.store');
    });

    // ==========================================
    // FITUR LAYANAN & BANTUAN (UMUM / MULTIROLE)
    // ==========================================
    Route::post('/pengajuan/store', [UserController::class, 'storePengajuan'])->name('pengajuan.store');
    Route::post('/aktivasi-nik', [UserController::class, 'storeAktivasi'])->name('aktivasi.store');
    Route::post('/proxy-report', [UserController::class, 'storeProxy'])->name('proxy.store');
    Route::post('/trouble-report', [UserController::class, 'storeTrouble'])->name('trouble.store');

});

// --- ROUTE DIAGNOSTIK ---
Route::get('/debug-email-view', function () {
    $path = resource_path('views/emails/reset-password.blade.php');
    if (file_exists($path)) {
        return "✅ File ditemukan di: " . $path;
    } else {
        return "❌ File TIDAK ditemukan! Laravel mencari di: " . $path;
    }
});