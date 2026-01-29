<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Aktivasi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class AktivasiController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validasi diperketat dengan logika kondisional
        $request->validate([
            'nama_lengkap'  => 'required|string|max:255',
            'nik_aktivasi'  => 'required|numeric|digits:16',
            'jenis_layanan' => 'required|in:restore,aktivasi', // Memastikan pilihan hanya 2 ini
            'alasan'        => 'nullable|string|max:1000',
            
            // LOGIKA UTAMA: 
            // Jika jenis_layanan == restore, maka lampiran WAJIB (required)
            // Jika jenis_layanan == aktivasi, maka lampiran BEBAS/OPSIONAL (nullable)
            'lampiran'      => ($request->jenis_layanan === 'restore' ? 'required' : 'nullable') . '|image|mimes:jpeg,png,jpg|max:5120',
        ], [
            'nik_aktivasi.digits'   => 'NIK harus berjumlah 16 digit.',
            'nama_lengkap.required' => 'Nama lengkap wajib diisi sesuai KTP.',
            'lampiran.required'     => 'Untuk layanan Restore Data, Anda wajib mengunggah lampiran foto/dokumen.',
            'lampiran.image'        => 'File yang diunggah harus berupa gambar.',
        ]);

        try {
            // 2. Proses Upload Lampiran (jika ada)
            $path = null;
            if ($request->hasFile('lampiran')) {
                $path = $request->file('lampiran')->store('aktivasi', 'public');
            }

            // 3. Menyiapkan data untuk disimpan
            $dataToSave = [
                'user_id'      => Auth::id(),
                'nama_lengkap' => $request->nama_lengkap,
                'nik_aktivasi' => $request->nik_aktivasi,
                'alasan'       => $request->alasan ?? 'Permintaan Aktivasi NIK',
                'foto_ktp'     => $path, // Berisi path file atau null jika tidak upload
                'status'       => 'Pending',
            ];

            // 4. Pengaman: Hanya simpan jenis_layanan jika kolomnya sudah ada di database
            // Ini mencegah error "Column not found" jika migrasi belum dijalankan
            if (Schema::hasColumn('aktivasis', 'jenis_layanan')) {
                $dataToSave['jenis_layanan'] = strtolower($request->jenis_layanan);
            }

            // 5. Eksekusi simpan ke database
            Aktivasi::create($dataToSave);

            $pesanSukses = ($request->jenis_layanan === 'restore') 
                ? 'Permintaan Restore Data berhasil dikirim!' 
                : 'Permintaan Aktivasi NIK berhasil dikirim!';

            return back()->with('success', $pesanSukses);

        } catch (\Exception $e) {
            // Menangkap error jika ada kegagalan sistem
            return back()->withErrors(['loginError' => 'Gagal menyimpan data: ' . $e->getMessage()])->withInput();
        }
    }
}