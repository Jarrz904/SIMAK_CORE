<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Daftar tabel yang perlu diperbarui
        $tables = ['aktivasis', 'pengajuans', 'proxies', 'troubles', 'pembubuhans'];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                    // Cek satu per satu agar tidak error jika kolom sudah ada
                    if (!Schema::hasColumn($tableName, 'tanggapan_admin')) {
                        $table->text('tanggapan_admin')->nullable()->after('status');
                    }
                    if (!Schema::hasColumn($tableName, 'processed_by')) {
                        $table->string('processed_by')->nullable()->after('tanggapan_admin');
                    }
                    if (!Schema::hasColumn($tableName, 'is_rejected')) {
                        $table->boolean('is_rejected')->default(false)->after('processed_by');
                    }
                });
            }
        }
    }

    public function down(): void
    {
        $tables = ['aktivasis', 'pengajuans', 'proxies', 'troubles', 'pembubuhans'];
        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropColumn(['tanggapan_admin', 'processed_by', 'is_rejected']);
            });
        }
    }
};