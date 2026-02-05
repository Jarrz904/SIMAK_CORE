<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('kecamatans', function (Blueprint $table) {
            $table->bigIncrements('id'); 
            $table->string('nama_kecamatan', 100);
            // Tetap nullable jika suatu saat ada data instansi non-wilayah (seperti MPP)
            $table->string('kode_wilayah', 10)->nullable(); 
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamp('created_at')->useCurrent();
        });

        // Memasukkan data awal dengan kode wilayah lengkap
        DB::table('kecamatans')->insert([
            ['id' => 1, 'nama_kecamatan' => 'ADIWERNA', 'kode_wilayah' => '33.28.11', 'status' => 'aktif'],
            ['id' => 2, 'nama_kecamatan' => 'BALAPULANG', 'kode_wilayah' => '33.28.03', 'status' => 'aktif'],
            ['id' => 3, 'nama_kecamatan' => 'BOJONG', 'kode_wilayah' => '33.28.02', 'status' => 'aktif'],
            ['id' => 4, 'nama_kecamatan' => 'BUMIJAWA', 'kode_wilayah' => '33.28.01', 'status' => 'aktif'],
            ['id' => 5, 'nama_kecamatan' => 'DUKUHTURI', 'kode_wilayah' => '33.28.13', 'status' => 'aktif'],
            ['id' => 6, 'nama_kecamatan' => 'DUKUHWARU', 'kode_wilayah' => '33.28.12', 'status' => 'aktif'],
            ['id' => 7, 'nama_kecamatan' => 'JATINEGARA', 'kode_wilayah' => '33.28.06', 'status' => 'aktif'],
            ['id' => 8, 'nama_kecamatan' => 'KEDUNGBANTENG', 'kode_wilayah' => '33.28.07', 'status' => 'aktif'],
            ['id' => 9, 'nama_kecamatan' => 'MPP', 'kode_wilayah' => '33.28', 'status' => 'aktif'],
            ['id' => 10, 'nama_kecamatan' => 'KRAMAT', 'kode_wilayah' => '33.28.15', 'status' => 'aktif'],
            ['id' => 11, 'nama_kecamatan' => 'LEBAKSIU', 'kode_wilayah' => '33.28.05', 'status' => 'aktif'],
            ['id' => 12, 'nama_kecamatan' => 'MARGASARI', 'kode_wilayah' => '33.28.04', 'status' => 'aktif'],
            ['id' => 13, 'nama_kecamatan' => 'PAGUYANGAN', 'kode_wilayah' => '33.29.02', 'status' => 'aktif'], // Brebes (Sering terintegrasi)
            ['id' => 14, 'nama_kecamatan' => 'PANGKAH', 'kode_wilayah' => '33.28.08', 'status' => 'aktif'],
            ['id' => 15, 'nama_kecamatan' => 'SLAWI', 'kode_wilayah' => '33.28.09', 'status' => 'aktif'],
            ['id' => 16, 'nama_kecamatan' => 'SURADADI', 'kode_wilayah' => '33.28.17', 'status' => 'aktif'],
            ['id' => 17, 'nama_kecamatan' => 'TALANG', 'kode_wilayah' => '33.28.14', 'status' => 'aktif'],
            ['id' => 18, 'nama_kecamatan' => 'TARUB', 'kode_wilayah' => '33.28.16', 'status' => 'aktif'],
            ['id' => 19, 'nama_kecamatan' => 'WARUREJA', 'kode_wilayah' => '33.28.18', 'status' => 'aktif'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kecamatans');
    }
};