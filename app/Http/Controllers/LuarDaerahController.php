<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LuarDaerah;
use Illuminate\Support\Facades\Auth;

class LuarDaerahController extends Controller
{
    /**
     * Menyimpan data pengajuan layanan luar daerah
     */
    public function store(Request $request)
    {
        // 1. Validasi Input sesuai dengan form dan model
        $request->validate([
            'nik'               => 'required|string', // NIK User
            'nik_luar_daerah'   => 'required|string|size:16', // NIK Pemohon
            'jenis_dokumen'     => 'required|string',
        ], [
            // Pesan Error Custom (Bahasa Indonesia)
            'nik_luar_daerah.required' => 'NIK pemohon luar daerah wajib diisi.',
            'nik_luar_daerah.size'     => 'NIK pemohon harus berjumlah 16 digit.',
            'jenis_dokumen.required'   => 'Silakan pilih jenis dokumen layanan.',
        ]);

        try {
            // 2. Simpan ke Database menggunakan Model LuarDaerah
            LuarDaerah::create([
                'user_id'           => Auth::id(),
                'nik'               => $request->nik,
                'nik_luar_daerah'   => $request->nik_luar_daerah,
                'jenis_dokumen'     => $request->jenis_dokumen,
                'status'            => 'pending', // Status awal default
                'is_rejected'       => false,    // Default awal tidak ditolak
            ]);

            // Kembali ke halaman sebelumnya dengan pesan sukses
            return redirect()->back()->with('success', 'Layanan Luar Daerah berhasil diajukan!');

        } catch (\Exception $e) {
            // Jika terjadi error pada sistem
            return redirect()->back()->with('error', 'Gagal mengirim pengajuan: ' . $e->getMessage());
        }
    }
}