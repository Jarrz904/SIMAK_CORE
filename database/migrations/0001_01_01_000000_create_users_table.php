<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
   public function up(): void
{
    Schema::create('users', function (Blueprint $table) {
        // 1. Ganti $table->id() menjadi NIK sebagai Primary Key
        $table->string('nik', 16)->primary(); 
        
        // 2. Pertahankan codingan sebelumnya
        $table->string('pin')->unique()->nullable(); 
        $table->string('name');
        $table->timestamp('email_verified_at')->nullable();
        $table->string('password');
        
        // Role tetap sesuai keinginan Anda
        $table->enum('role', ['admin', 'user'])->default('user');

        // Tambahkan kolom tambahan yang ada di form Blade Anda agar tidak error
        $table->string('location')->nullable(); 
        $table->boolean('is_active')->default(true);
        
        $table->rememberToken();
        $table->timestamps();
    });

    Schema::create('password_reset_tokens', function (Blueprint $table) {
        $table->string('email')->primary();
        $table->string('token');
        $table->timestamp('created_at')->nullable();
    });

    Schema::create('sessions', function (Blueprint $table) {
        $table->string('id')->primary();
        // 3. PENTING: user_id harus String karena NIK adalah String
        $table->string('user_id', 16)->nullable()->index(); 
        $table->string('ip_address', 45)->nullable();
        $table->text('user_agent')->nullable();
        $table->longText('payload');
        $table->integer('last_activity')->index();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};