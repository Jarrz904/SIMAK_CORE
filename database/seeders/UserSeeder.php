<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // 1. Buat Data Admin
        User::create([
            'name'      => 'Super Admin SIMAK',
            'email'     => 'admin@simak.com', // Identitas Admin
            'pin'       => '112233',          // PIN Admin (opsional)
            'password'  => Hash::make('admin123'), // Password: admin123
            'role'      => 'admin',
        ]);

        // 2. Buat Data User (Penduduk)
        User::create([
            'name'      => 'Ahmad Fauzi',
            'email'     => 'ahmad@gmail.com',
            'pin'       => '123456',          // Identitas Login User (PIN)
            'password'  => Hash::make('user123'),  // Password: user123
            'role'      => 'penduduk',
        ]);
    }
}