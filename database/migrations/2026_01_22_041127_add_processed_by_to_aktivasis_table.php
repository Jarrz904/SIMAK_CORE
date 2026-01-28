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
    Schema::table('aktivasis', function (Blueprint $table) {
        // Menambahkan kolom processed_by setelah kolom tanggapan_admin
        $table->string('processed_by')->nullable()->after('tanggapan_admin');
    });
}

public function down(): void
{
    Schema::table('aktivasis', function (Blueprint $table) {
        $table->dropColumn('processed_by');
    });
}
};
