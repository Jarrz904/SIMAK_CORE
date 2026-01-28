<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('proxies', function (Blueprint $table) {
            // Mengubah kolom menjadi nullable
            $table->string('ip_detail')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('proxies', function (Blueprint $table) {
            // Kembalikan ke tidak boleh null jika diperlukan rollback
            $table->string('ip_detail')->nullable(false)->change();
        });
    }
};
