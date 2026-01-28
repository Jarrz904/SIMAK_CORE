<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // 1. Mengubah kolom email agar boleh kosong (Nullable)
            // Ini penting karena form registrasi kita tidak menggunakan email
            $table->string('email')->nullable()->change();

            // 2. Menambah kolom 'location' jika belum ada
            if (!Schema::hasColumn('users', 'location')) {
                $table->string('location')->after('name')->nullable();
            }
            
            // 3. Menambah kolom 'nik' jika belum ada
            if (!Schema::hasColumn('users', 'nik')) {
                $table->string('nik', 16)->unique()->after('id')->nullable();
            }

            // 4. Menambah kolom 'role' jika belum ada
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('penduduk')->after('password');
            }

            // 5. Menambah kolom 'pin' jika belum ada
            if (!Schema::hasColumn('users', 'pin')) {
                $table->string('pin')->unique()->after('nik')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Mengembalikan email menjadi wajib (NOT NULL)
            $table->string('email')->nullable(false)->change();
            
            // Menghapus kolom yang ditambahkan
            $table->dropColumn(['location', 'nik', 'role', 'pin']);
        });
    }
};