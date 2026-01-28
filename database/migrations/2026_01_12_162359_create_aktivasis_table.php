<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('aktivasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nama_lengkap');
            $table->string('nik_aktivasi');
            $table->text('alasan')->nullable();
            
            // Kolom Tambahan untuk Fitur Admin
            $table->text('tanggapan_admin')->nullable(); // Untuk menyimpan pesan dari admin
            $table->string('processed_by')->nullable();  // Untuk mencatat nama admin yang memproses
            $table->boolean('is_rejected')->default(false); // Logika filter: true jika ditolak
            
            $table->string('status')->default('pending'); // Tetap dipertahankan untuk backup status
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('aktivasis');
    }
};