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
        $tables = ['troubles', 'pengajuans', 'pembubuhans', 'proxies', 'aktivasis'];
        
        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                
                // 1. Tambah kolom tanggapan_admin jika belum ada (antisipasi)
                if (!Schema::hasColumn($tableName, 'tanggapan_admin')) {
                    $table->text('tanggapan_admin')->nullable();
                }

                // 2. Tambah kolom processed_by
                if (!Schema::hasColumn($tableName, 'processed_by')) {
                    $table->string('processed_by')->nullable()->after('tanggapan_admin');
                }

                // 3. Tambah kolom status
                if (!Schema::hasColumn($tableName, 'status')) {
                    $table->string('status')->default('pending')->after('processed_by');
                }

                // 4. Tambah kolom is_rejected (SANGAT PENTING untuk logika Controller Anda)
                if (!Schema::hasColumn($tableName, 'is_rejected')) {
                    $table->boolean('is_rejected')->default(false)->after('status');
                }
            });

            // 5. Sinkronisasi Data Lama
            // Set 'completed' jika sudah ada tanggapan
            DB::table($tableName)->whereNotNull('tanggapan_admin')->update(['status' => 'completed']);
            
            // Set 'rejected' dan is_rejected = true jika tanggapan mengandung kata 'DITOLAK'
            DB::table($tableName)->where('tanggapan_admin', 'like', '%DITOLAK%')->update([
                'status' => 'rejected',
                'is_rejected' => true
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = ['troubles', 'pengajuans', 'pembubuhans', 'proxies', 'aktivasis'];
        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                // Hapus kolom secara hati-hati
                $columns = [];
                if (Schema::hasColumn($tableName, 'processed_by')) $columns[] = 'processed_by';
                if (Schema::hasColumn($tableName, 'status')) $columns[] = 'status';
                if (Schema::hasColumn($tableName, 'is_rejected')) $columns[] = 'is_rejected';
                
                if (!empty($columns)) {
                    $table->dropColumn($columns);
                }
            });
        }
    }
};