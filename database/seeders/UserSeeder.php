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
            'name'      => 'Admin PIAK',
            'email'     => 'admin@gmail.com', // Identitas Admin
            'pin'       => '332800',   // PIN Admin (opsional)
            'nik'       => '3201010000000000', // Identitas Admin (NIK)
            'password'  => Hash::make('admin3328'), // Password: admin123
            'role'      => 'admin',
        ]);

        // 2. Buat Data User (Penduduk)
        User::create([
            'name'      => 'Operator',
            'email'     => 'user@gmail.com',
            'nik'       => '3201012301010001', // Identitas User (NIK)
            'pin'       => '332811',          // Identitas Login User (PIN)
            'password'  => Hash::make('user332811'),  // Password: user123
            'role'      => 'user',
        ]);
    }
}