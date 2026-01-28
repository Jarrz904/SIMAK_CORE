<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        $user = auth()->user();

        // 1. Validasi
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'nik' => ['required', 'string', 'max:16', Rule::unique('users')->ignore($user->id)],
            'location' => ['nullable', 'string', 'max:255'],
            'password' => ['nullable', 'confirmed', 'min:8'], // Password boleh kosong jika tidak diubah
        ]);

        // 2. Update data profil
        $user->name = $request->name;
        $user->email = $request->email;
        $user->nik = $request->nik;
        $user->location = $request->location;

        // 3. Update password jika diisi
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return back()->with('success', 'Profil Anda berhasil diperbarui!');
    }
}