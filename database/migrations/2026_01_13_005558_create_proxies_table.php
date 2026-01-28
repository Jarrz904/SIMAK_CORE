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
        Schema::create('proxies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // PERBAIKAN 1: Nama kolom diubah ke 'deskripsi' agar sinkron dengan Admin & Excel
            // Dibuat nullable() karena Anda ingin deskripsi ini bersifat opsional
            $table->text('deskripsi')->nullable(); 

            $table->string('foto_proxy'); 
            $table->string('status')->default('pending');

            // PERBAIKAN 2: Tambahkan kolom tanggapan agar admin bisa membalas laporan
            $table->text('tanggapan_admin')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proxies');
    }
};