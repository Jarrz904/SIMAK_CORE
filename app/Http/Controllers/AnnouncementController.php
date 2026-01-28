<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AnnouncementController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validasi data dengan pesan error kustom (opsional)
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        try {
            // 2. Simpan ke database menggunakan data yang sudah tervalidasi
            Announcement::create([
                'title'   => $validated['title'],
                'message' => $validated['message'],
                'type'    => $request->type ?? 'info', // Mengambil dari input jika ada, jika tidak 'info'
            ]);

            // 3. Redirect balik dengan pesan sukses
            return redirect()->back()->with('success', 'Pengumuman berhasil diterbitkan!');

        } catch (\Exception $e) {
            // Jika ada error database, catat di log dan beri tahu admin
            Log::error("Gagal buat pengumuman: " . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem saat menyimpan pengumuman.');
        }
    }
}