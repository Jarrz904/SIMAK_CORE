<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('troubles', function (Blueprint $table) {
            $table->id();
            // Menghubungkan ke ID User yang melapor
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('kategori');
            $table->text('deskripsi');
            $table->string('foto_trouble'); // Menyimpan nama file gambar
            $table->enum('status', ['pending', 'proses', 'selesai'])->default('pending');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('troubles');
    }
};