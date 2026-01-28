<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UpdateData; 
use Illuminate\Support\Facades\Auth;

class UpdateDataController extends Controller
{
    public function store(Request $request)
    {
        // PERBAIKAN: Nama field disesuaikan dengan atribut 'name' di form HTML (Blade)
        $request->validate([
            'nik_pemohon'     => 'required|numeric|digits:16',
            'kategori_update' => 'required|string',             // Sesuai name="kategori_update"
            'alasan_update'   => 'required|string',             // Sesuai name="alasan_update"
            'lampiran_update' => 'required|array|min:1',         // Sesuai name="lampiran_update[]"
            'lampiran_update.*' => 'image|mimes:jpeg,png,jpg|max:5120'
        ], [
            // Pesan error kustom agar lebih user-friendly
            'nik_pemohon.digits'      => 'NIK harus tepat 16 digit.',
            'kategori_update.required' => 'Silakan pilih jenis perubahan data.',
            'alasan_update.required'   => 'Alasan perubahan data wajib diisi.',
            'lampiran_update.required' => 'Lampiran dokumen wajib diunggah.',
        ]);

        $paths = [];
        // PERBAIKAN: Mengambil file berdasarkan name="lampiran_update"
        if ($request->hasFile('lampiran_update')) {
            foreach ($request->file('lampiran_update') as $file) {
                if ($file->isValid()) {
                    $paths[] = $file->store('update_data', 'public');
                }
            }
        }

        // Simpan ke Database
        UpdateData::create([
            'user_id'       => Auth::id(),
            'nik_pemohon'   => $request->nik_pemohon,
            'jenis_layanan' => $request->kategori_update, // Mapping input ke kolom database
            'deskripsi'     => $request->alasan_update,   // Mapping input ke kolom database
            'lampiran'      => $paths,                    // Array akan otomatis jadi JSON jika model sudah diset cast 'array'
            'status'        => 'pending',
        ]);

        // Gunakan 'status' agar konsisten dengan UserController Anda sebelumnya
        return redirect()->back()->with('status', 'Permohonan update data berhasil dikirim!');
    }
}