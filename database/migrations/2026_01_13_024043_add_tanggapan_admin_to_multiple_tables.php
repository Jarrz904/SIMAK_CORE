<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Daftar tabel yang akan dikelola
     */
    private array $tables = ['aktivasis', 'pengajuans', 'proxies', 'troubles', 'pembubuhans'];

    public function up(): void
    {
        foreach ($this->tables as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                    // Cek 'tanggapan_admin'
                    if (!Schema::hasColumn($tableName, 'tanggapan_admin')) {
                        // Cek kolom 'status' ada atau tidak sebelum menggunakan after()
                        if (Schema::hasColumn($tableName, 'status')) {
                            $table->text('tanggapan_admin')->nullable()->after('status');
                        } else {
                            $table->text('tanggapan_admin')->nullable();
                        }
                    }

                    // Cek 'processed_by'
                    if (!Schema::hasColumn($tableName, 'processed_by')) {
                        $table->string('processed_by')->nullable()->after('tanggapan_admin');
                    }

                    // Cek 'is_rejected'
                    if (!Schema::hasColumn($tableName, 'is_rejected')) {
                        $table->boolean('is_rejected')->default(false)->after('processed_by');
                    }
                });
            }
        }
    }

    public function down(): void
    {
        foreach ($this->tables as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                    // List kolom yang ingin dihapus
                    $columns = ['tanggapan_admin', 'processed_by', 'is_rejected'];
                    
                    foreach ($columns as $column) {
                        // Hanya hapus jika kolom memang ada di database
                        if (Schema::hasColumn($tableName, $column)) {
                            $table->dropColumn($column);
                        }
                    }
                });
            }
        }
    }
};