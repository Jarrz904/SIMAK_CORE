<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Panggil UserSeeder untuk membuat akun Admin dan Penduduk
        $this->call([
            UserSeeder::class,
        ]);

        // Opsional: Jika ingin membuat User tambahan lewat factory
        // User::factory(10)->create();
    }
}