<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Aktivasi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class AktivasiController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validasi diperbaiki untuk mendukung Multiple Files (Array)
        $request->validate([
            'nama_lengkap'  => 'required|string|max:255',
            'nik_aktivasi'  => 'required|numeric|digits:16',
            'jenis_layanan' => 'required|in:RESTORE,AKTIVASI,restore,aktivasi',
            'alasan'        => 'nullable|string|max:1000',
            // Validasi lampiran sebagai array
            'lampiran'      => [
                'nullable',
                'required_if:jenis_layanan,RESTORE,restore',
                'array', 
            ],
            // Validasi tiap file di dalam array
            'lampiran.*'    => 'image|mimes:jpeg,png,jpg|max:5120', // Max 5MB per file
        ], [
            'nik_aktivasi.digits'   => 'NIK harus berjumlah 16 digit.',
            'nik_aktivasi.numeric'  => 'NIK harus berupa angka.',
            'lampiran.required_if'  => 'Untuk layanan Restore Data, Anda wajib mengunggah dokumen.',
            'lampiran.*.image'      => 'File harus berupa gambar (JPG/PNG).',
            'lampiran.*.max'        => 'Ukuran tiap gambar maksimal adalah 5MB.',
        ]);

        DB::beginTransaction();
        $storedPaths = []; // Array untuk menampung banyak path file

        try {
            // 2. Proses Upload Banyak Lampiran
            if ($request->hasFile('lampiran')) {
                foreach ($request->file('lampiran') as $index => $file) {
                    // Nama file unik: timestamp_nik_index.ext
                    $fileName = time() . '_' . $request->nik_aktivasi . '_' . $index . '.' . $file->getClientOriginalExtension();
                    
                    // Simpan file asli (Tanpa resize agar tidak pecah)
                    $path = $file->storeAs('aktivasi', $fileName, 'public');
                    $storedPaths[] = $path;
                }
            }

            // 3. Eksekusi Simpan ke kolom FOTO_KTP
            Aktivasi::create([
                'user_id'       => Auth::id(),
                'nama_lengkap'  => $request->nama_lengkap,
                'nik_aktivasi'  => $request->nik_aktivasi,
                'jenis_layanan' => strtoupper($request->jenis_layanan),
                'alasan'        => $request->alasan ?? ($request->jenis_layanan === 'RESTORE' ? 'Permintaan Restore Data' : 'Permintaan Aktivasi NIK'),
                
                // Jika banyak foto, simpan sebagai JSON. Jika satu, simpan string biasa.
                // Sesuaikan dengan kebutuhan casting di Model Anda
                'foto_ktp'      => count($storedPaths) > 0 ? json_encode($storedPaths) : null,
                
                'status'        => 'pending',
            ]);

            DB::commit();

            $pesan = (strtoupper($request->jenis_layanan) === 'RESTORE') 
                ? 'Permintaan Restore Data berhasil terkirim!' 
                : 'Permintaan Aktivasi NIK berhasil terkirim!';

            return back()->with('success', $pesan);

        } catch (\Exception $e) {
            DB::rollBack();

            // Hapus semua file yang sempat terupload jika database gagal
            foreach ($storedPaths as $path) {
                Storage::disk('public')->delete($path);
            }

            return back()
                ->withErrors(['error' => 'Gagal mengirim permintaan: ' . $e->getMessage()])
                ->withInput();
        }
    }
}