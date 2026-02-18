<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AnnouncementController;

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
        Route::get('/admin/panel', function () {
            return redirect()->route('admin.index');
        })->name('admin.dashboard');

        // --- ROUTE MANAJEMEN USER ---
        Route::prefix('admin/users')->group(function () {
            Route::post('/store', [AdminController::class, 'store'])->name('admin.users.store');
            Route::delete("/{id}", [AdminController::class, 'destroy'])->name('admin.users.destroy');
            Route::put("/{id}/update", [AdminController::class, 'update'])->name('admin.users.update');
            Route::post("/{id}/toggle-status", [AdminController::class, 'toggleStatus'])->name('admin.users.toggle');
        });

        Route::prefix('admin/laporan')->group(function () {
            // --- ROUTE REAL-TIME FETCH ---
            // SINKRONISASI: Route ini sekarang dipastikan hanya mengembalikan JSON di Controller
            Route::get('/fetch-json', [AdminController::class, 'fetchJson'])->name('admin.laporan.fetch-json');
            
            // Tambahan Route Alias untuk menangani Error: Route [admin.get-laporan-json] not defined
            Route::get('/get-data', [AdminController::class, 'fetchJson'])->name('admin.get-laporan-json');

            // CUKUP TULIS NAMA AKHIRNYA SAJA
            Route::post('/respon', [AdminController::class, 'kirimRespon'])->name('admin.laporan.respon');
            Route::post('/tolak/{id}', [AdminController::class, 'tolakLaporan'])->name('admin.laporan.tolak');
            Route::delete('/hapus/{id}', [AdminController::class, 'hapusLaporan'])->name('admin.laporan.hapus');
            
            // Menambahkan rute pengelolaan Luar Daerah untuk Admin
            Route::post('/luar-daerah/respon', [AdminController::class, 'responLuarDaerah'])->name('admin.luardaerah.respon');

            // --- ROUTE EXPORT ---
            Route::get('/export', [AdminController::class, 'exportExcel'])->name('admin.laporan.export');
            Route::get('/export-excel', [AdminController::class, 'exportExcel'])->name('export.excel');

            // 1. Export Laporan Aktivasi (NIK & Luar Daerah)
            Route::match(['get', 'post'], '/export-aktivasi', [AdminController::class, 'exportAktivasi'])->name('export.laporan.aktivasi');

            // 2. Export Laporan Sistem (SIAK, Trouble, Proxy, TTE)
            Route::match(['get', 'post'], '/export-sistem', [AdminController::class, 'exportSistem'])->name('export.laporan.sistem');

            // 3. Export Laporan Update Data (Biodata)
            Route::match(['get', 'post'], '/export-updatedata', [AdminController::class, 'exportUpdateData'])->name('export.laporan.updatedata');
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
        // Halaman utama user
        Route::get('/user/dashboard', [UserController::class, 'index'])->name('user.dashboard');
Route::put('/user/profile/update', [UserController::class, 'updateProfil'])->name('user.updateProfil');
        /**
         * PERBAIKAN UNTUK DASHBOARD TUNGGAL:
         * 1. Route GET profile diarahkan kembali ke dashboard agar tidak error 404.
         * 2. Route update mendukung PUT dan POST (mengatasi MethodNotAllowed).
         */
        Route::get('/user/profile', function () {
            return redirect()->route('user.dashboard');
        })->name('user.profile');

        // Match digunakan agar jika form di dashboard pakai @method('PUT') atau POST tetap jalan
        Route::match(['put', 'post'], '/user/profile/update', [UserController::class, 'updateProfil'])->name('user.profile.update');

        Route::post('/user/notifikasi/{id}/read', [UserController::class, 'markAsRead'])->name('user.notif.read');

        // --- FITUR PEMBUBUHAN TTE ---
        Route::post('/pembubuhan/store', [UserController::class, 'storePembubuhan'])->name('pembubuhan.store');

        // --- FITUR LUAR DAERAH ---
        Route::post('/user/luar-daerah/store', [UserController::class, 'storeLuarDaerah'])->name('user.luardaerah.store');

        // --- FITUR UPDATE DATA (PENGAJUAN BARU) ---
        Route::post('/user/update-data/store', [UserController::class, 'storeUpdateData'])->name('update-data.store');
    });

    // ==========================================
    // FITUR LAYANAN & BANTUAN (UMUM / MULTIROLE)
    // ==========================================
    Route::post('/pengajuan/store', [UserController::class, 'storePengajuan'])->name('pengajuan.store');
    // Rute aktivasi, proxy, dan trouble tetap bisa diakses admin/user selama login
    Route::post('/aktivasi-nik', [UserController::class, 'storeAktivasi'])->name('aktivasi.store');
    Route::post('/proxy-report', [UserController::class, 'storeProxy'])->name('proxy.store');
    Route::post('/trouble-report', [UserController::class, 'storeTrouble'])->name('trouble.store');

});