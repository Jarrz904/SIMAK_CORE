<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengajuan;
use Illuminate\Support\Facades\Auth;

class PengajuanController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'jenis_registrasi' => 'required|string',
            'deskripsi' => 'required|string',
            'foto_dokumen' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('foto_dokumen')) {
            $file = $request->file('foto_dokumen');
            $nama_file = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('uploads/pengajuan', $nama_file, 'public');

            Pengajuan::create([
                'user_id' => auth()->id(),
                'jenis_registrasi' => $request->jenis_registrasi,
                'deskripsi' => $request->deskripsi,
                'foto_dokumen' => $path, // Sesuai nama kolom di database Anda
                'status' => 'pending'
            ]);

            return back()->with('success', 'Pengajuan berhasil dikirim!');
        }
    }
}