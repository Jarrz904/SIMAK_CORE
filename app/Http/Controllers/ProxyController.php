<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proxy;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProxyController extends Controller
{
    /**
     * Menyimpan laporan proxy/IP dari penduduk.
     */
    public function store(Request $request)
    {
        // 1. Validasi Input 
        // Diubah menjadi 'nullable' agar deskripsi/detail tidak wajib diisi
        $request->validate([
            'ip_detail' => 'nullable|string|max:500', 
            'foto_proxy' => 'required|image|mimes:jpeg,png,jpg|max:2048', // Max 2MB
        ], [
            'foto_proxy.required' => 'Bukti screenshot wajib diunggah.',
            'foto_proxy.image' => 'File harus berupa gambar (JPG, PNG, JPEG).',
        ]);

        try {
            // 2. Proses Upload Foto
            if ($request->hasFile('foto_proxy')) {
                // Simpan ke storage/app/public/uploads/proxy
                $path = $request->file('foto_proxy')->store('uploads/proxy', 'public');

                // 3. Simpan data ke Database
                // Note: 'deskripsi' adalah nama kolom di database Anda
                // '$request->ip_detail' adalah nama input di Blade/Form Anda
                Proxy::create([
                    'user_id' => Auth::id(),
                    'deskripsi' => $request->ip_detail, // Memasukkan input ip_detail ke kolom deskripsi
                    'foto_proxy' => $path,
                    'status' => 'pending',
                ]);

                return back()->with('success', 'Laporan proxy berhasil terkirim ke sistem!');
            }

            return back()->with('error', 'Gagal mengupload bukti. File tidak ditemukan.');

        } catch (\Exception $e) {
            // Menangkap error database
            return back()->with('error', 'Gagal menyimpan ke database: ' . $e->getMessage());
        }
    }
}