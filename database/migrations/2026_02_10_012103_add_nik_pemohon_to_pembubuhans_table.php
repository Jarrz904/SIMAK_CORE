<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up(): void
{
    Schema::table('pembubuhans', function (Blueprint $table) {
        // Menambahkan kolom nik_pemohon setelah kolom nik
        $table->string('nik_pemohon', 16)->after('nik')->nullable();
    });
}

public function down(): void
{
    Schema::table('pembubuhans', function (Blueprint $table) {
        $table->dropColumn('nik_pemohon');
    });
}
};
