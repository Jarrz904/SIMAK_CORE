<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UpdateData;
use Illuminate\Support\Facades\Auth;

class UpdateDataController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nik_pemohon' => 'required|numeric|digits:16',
            'jenis_layanan' => 'required',
            'alasan' => 'required|string', // 'alasan' adalah nama input di Blade kamu
            'lampiran' => 'required',
            'lampiran.*' => 'image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $paths = [];
        if ($request->hasFile('lampiran')) {
            foreach ($request->file('lampiran') as $file) {
                $paths[] = $file->store('update_data', 'public');
            }
        }

        \App\Models\UpdateData::create([
            'user_id' => \Auth::id(),
            'nik_pemohon' => $request->nik_pemohon,
            'jenis_layanan' => $request->jenis_layanan,
            'deskripsi' => $request->alasan, // Masuk ke kolom deskripsi
            'lampiran' => $paths,
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Permohonan berhasil dikirim!');
    }
}