<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembubuhan;
use Illuminate\Support\Facades\Auth;

class PembubuhanController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validasi Input (Tetap seperti aslinya)
        $request->validate([
            'nik' => 'required|string',
            'jenis_dokumen' => 'required|string',
            'keterangan' => 'nullable|string',
        ], [
            'jenis_dokumen.required' => 'Silakan pilih jenis dokumen.',
        ]);

        try {
            // 2. Simpan ke Database
            Pembubuhan::create([
                'user_id' => Auth::id(),
                'nik' => $request->nik,
                'jenis_dokumen' => $request->jenis_dokumen,
                'keterangan' => $request->keterangan, // Ini tetap masuk ke kolom keterangan
                'status' => 'pending',
            ]);

            return redirect()->back()->with('success', 'Permohonan TTE berhasil dikirim!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}