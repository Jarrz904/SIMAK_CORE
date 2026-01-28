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
        // Pastikan nama tabel adalah 'pengajuans' sesuai error log dan Controller
        Schema::create('pengajuans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Kolom untuk fitur Kendala SIAK
            $table->string('nama_lengkap')->nullable();
            $table->string('nik_aktivasi', 16)->nullable();
            $table->string('jenis_registrasi')->nullable(); // Menampung 'DOKUMEN'
            
            /** * KOLOM KATEGORI
             * Ini adalah kolom yang menyebabkan error 'Unknown column' 
             * karena Controller memanggilnya di baris 182
             */
            $table->string('kategori')->nullable(); 
            
            $table->text('deskripsi')->nullable();
            $table->text('foto_dokumen')->nullable(); // Menampung JSON paths foto
            
            /**
             * KOLOM TANGGAPAN ADMIN
             * Dibutuhkan agar Dashboard User bisa menampilkan balasan petugas
             */
            $table->text('tanggapan_admin')->nullable(); 

            $table->string('status')->default('Pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuans');
    }
};