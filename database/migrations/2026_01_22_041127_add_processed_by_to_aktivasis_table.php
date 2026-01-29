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
        if (Schema::hasTable('aktivasis')) {
            Schema::table('aktivasis', function (Blueprint $table) {
                // Pastikan kolom belum ada agar tidak error "duplicate column"
                if (!Schema::hasColumn('aktivasis', 'processed_by')) {
                    
                    // Cek apakah kolom referensi after() ada
                    if (Schema::hasColumn('aktivasis', 'tanggapan_admin')) {
                        $table->string('processed_by')->nullable()->after('tanggapan_admin');
                    } else {
                        // Jika tanggapan_admin tidak ada, taruh di paling belakang
                        $table->string('processed_by')->nullable();
                    }
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('aktivasis')) {
            Schema::table('aktivasis', function (Blueprint $table) {
                // Pastikan kolom ada sebelum dihapus agar tidak error "check that it exists"
                if (Schema::hasColumn('aktivasis', 'processed_by')) {
                    $table->dropColumn('processed_by');
                }
            });
        }
    }
};