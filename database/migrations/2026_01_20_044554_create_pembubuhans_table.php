<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi untuk membuat tabel.
     */
   public function up(): void
{
    Schema::create('pembubuhans', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->string('nik', 16);
        
        // PERBAIKAN: Tambahkan nullable() di sini
        $table->string('no_akte')->nullable(); 
        $table->string('jenis_dokumen');
        $table->string('foto_dokumen')->nullable(); // Boleh kosong
        
        $table->text('tanggapan_admin')->nullable();
        $table->string('processed_by')->nullable(); // Untuk mencatat admin pembantu
        $table->string('status')->default('Pending'); 
        $table->timestamps();
    });
}

    /**
     * Batalkan migrasi (Hapus tabel).
     */
    public function down(): void
    {
        Schema::dropIfExists('pembubuhans');
    }
};