<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Aktivasi;
use Illuminate\Support\Facades\Auth;

class AktivasiController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validasi diperketat
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nik_aktivasi' => 'required|numeric|digits:16',
            'alasan' => 'nullable|string|max:1000',
        ], [
            'nik_aktivasi.digits' => 'NIK harus berjumlah 16 digit.',
            'nama_lengkap.required' => 'Nama lengkap wajib diisi sesuai KTP.',
        ]);

        try {
            // 2. Proses simpan ke database
            Aktivasi::create([
                'user_id' => Auth::id(),
                'nama_lengkap' => $request->nama_lengkap,
                'nik_aktivasi' => $request->nik_aktivasi,
                'alasan' => $request->alasan ?? 'Tidak ada alasan tambahan',
                'status' => 'pending',
            ]);

            return back()->with('success', 'Permintaan Aktivasi SIAK berhasil dikirim!');

        } catch (\Exception $e) {
            // Jika masih error (misal karena database belum dimigrasi)
            return back()->withErrors(['loginError' => 'Gagal menyimpan data: ' . $e->getMessage()]);
        }
    }
}