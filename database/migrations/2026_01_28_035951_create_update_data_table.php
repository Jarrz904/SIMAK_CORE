<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('update_datas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nik_pemohon');
            $table->string('jenis_layanan'); // Perubahan Nama, Agama, dll
            $table->text('deskripsi');
            $table->text('lampiran'); // Akan menyimpan array JSON path foto
            $table->string('status')->default('pending');
            $table->text('tanggapan_admin')->nullable();
            $table->string('processed_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('update_data');
    }
};
