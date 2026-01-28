<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up(): void
{
    Schema::create('announcements', function (Illuminate\Database\Schema\Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->text('message');
        $table->enum('type', ['info', 'warning', 'urgent'])->default('info');
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};
