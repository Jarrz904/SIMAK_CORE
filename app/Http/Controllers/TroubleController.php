<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Trouble; // Tambahkan import model ini
use Illuminate\Support\Facades\Storage; // Tambahkan ini untuk manajemen file

class TroubleController extends Controller
{
    public function store(Request $request)
    {
        // Validasi data
        $request->validate([
            'kategori' => 'required',
            'deskripsi' => 'required|string|min:5',
            'foto_trouble' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('foto_trouble')) {
            // Proses simpan gambar
            $file = $request->file('foto_trouble');

            // Menggunakan store() akan menghasilkan nama file acak yang aman di folder storage/app/public/trouble_reports
            $path = $file->store('trouble_reports', 'public');

            // --- LOGIKA SIMPAN KE DATABASE ---
            // Kode ini akan menyimpan data ke tabel 'troubles'
            Trouble::create([
                'user_id' => Auth::id(), // Mengambil ID user yang sedang login
                'kategori' => $request->kategori,
                'deskripsi' => $request->deskripsi,
                'foto_trouble' => $path, // Menyimpan path file hasil upload
                'status' => 'pending', // Status default saat laporan dibuat
            ]);

            return back()->with('success', 'Laporan trouble berhasil dikirim ke teknisi!');
        }

        return back()->with('error', 'Gagal mengirim laporan.');
    }
}