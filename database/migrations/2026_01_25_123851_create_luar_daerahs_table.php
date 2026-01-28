<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('luar_daerahs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nik', 16); 
            $table->string('nik_luar_daerah', 16);
            $table->string('jenis_dokumen');
            $table->text('tanggapan_admin')->nullable();
            $table->string('processed_by')->nullable();
            $table->boolean('is_rejected')->default(false);
            $table->enum('status', ['pending', 'selesai', 'ditolak'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('luar_daerahs');
    }
};