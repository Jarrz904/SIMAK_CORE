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
        Schema::table('aktivasis', function (Blueprint $table) {
            // Tambahkan kolom jenis_layanan (untuk simpan 'restore' atau 'aktivasi')
            // diletakkan setelah kolom nik_aktivasi
            if (!Schema::hasColumn('aktivasis', 'jenis_layanan')) {
                $table->string('jenis_layanan')->nullable()->after('nik_aktivasi');
            }

            // Tambahkan kolom foto_ktp (untuk simpan path lampiran gambar)
            // diletakkan setelah kolom alasan
            if (!Schema::hasColumn('aktivasis', 'foto_ktp')) {
                $table->string('foto_ktp')->nullable()->after('alasan');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('aktivasis', function (Blueprint $table) {
            $table->dropColumn(['jenis_layanan', 'foto_ktp']);
        });
    }
};