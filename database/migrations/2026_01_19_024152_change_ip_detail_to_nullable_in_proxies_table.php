<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Cek apakah tabel proxies ada
        if (Schema::hasTable('proxies')) {
            Schema::table('proxies', function (Blueprint $table) {
                // Cek apakah kolom ip_detail benar-benar ada sebelum diubah
                if (Schema::hasColumn('proxies', 'ip_detail')) {
                    $table->string('ip_detail')->nullable()->change();
                } else {
                    // Jika kolom tidak ada, buat baru saja agar sistem tidak error
                    $table->string('ip_detail')->nullable();
                }
            });
        }
    }

    public function down(): void
    {
        Schema::table('proxies', function (Blueprint $table) {
            if (Schema::hasColumn('proxies', 'ip_detail')) {
                $table->string('ip_detail')->nullable(false)->change();
            }
        });
    }
};