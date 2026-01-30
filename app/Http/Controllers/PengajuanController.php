<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengajuan;
use Illuminate\Support\Facades\Auth;

class PengajuanController extends Controller
{
    public function store(Request $request)
    {
        // 1. Tambahkan validasi jika field ini dikirim dari form
        $request->validate([
            'jenis_registrasi' => 'required|string',
            'deskripsi' => 'required|string',
            'foto_dokumen' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('foto_dokumen')) {
            $file = $request->file('foto_dokumen');
            $nama_file = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('uploads/pengajuan', $nama_file, 'public');

            // Ambil data user yang sedang login
            $user = Auth::user();

            // 2. Masukkan nama_lengkap dan nik_aktivasi ke dalam create
            Pengajuan::create([
                'user_id' => $user->id,
                'nama_lengkap' => $user->name, // Mengambil nama dari tabel users
                'nik_aktivasi' => $user->nik,  // Mengambil NIK dari tabel users (pastikan kolomnya bernama 'nik')
                'jenis_registrasi' => $request->jenis_registrasi,
                'deskripsi' => $request->deskripsi,
                'foto_dokumen' => $path,
                'status' => 'pending'
            ]);

            return back()->with('success', 'Pengajuan berhasil dikirim!');
        }
    }
}