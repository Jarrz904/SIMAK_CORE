<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
// Import semua model yang Anda gunakan
use App\Models\Trouble;
use App\Models\Aktivasi;
use App\Models\Proxy;
use App\Models\Pengajuan;
use App\Models\Pembubuhan;

class LaporanController extends Controller
{
    public function kirimRespon(Request $request)
    {
        // 1. Validasi: 'type' sangat penting untuk menentukan tabel
        $request->validate([
            'laporan_id' => 'required',
            'type'       => 'required', // aktivasi, trouble, proxy, dll
            'admin_note' => 'required|string|max:1000',
        ]);

        try {
            // 2. Tentukan Model berdasarkan type
            $model = match($request->type) {
                'aktivasi'   => Aktivasi::class,
                'trouble'    => Trouble::class,
                'proxy'      => Proxy::class,
                'pengajuan'  => Pengajuan::class,
                'pembubuhan' => Pembubuhan::class,
                default      => null,
            };

            if (!$model) {
                return back()->withErrors(['error' => 'Tipe laporan tidak dikenali.']);
            }

            // 3. Cari data di tabel yang sesuai
            $laporan = $model::findOrFail($request->laporan_id);

            // 4. Update tanggapan admin & pastikan is_rejected FALSE (karena ini merespon/selesai)
            $laporan->update([
                'tanggapan_admin' => $request->admin_note,
                'is_rejected'     => false,
            ]);

            // 5. Update ke profil User (opsional, seperti kode Anda sebelumnya)
            if ($laporan->user_id) {
                User::where('id', $laporan->user_id)->update([
                    'admin_note' => $request->admin_note
                ]);
            }

            return back()->with('success', 'Respon berhasil disimpan!');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal: ' . $e->getMessage()]);
        }
    }

    /**
     * Tambahkan fungsi Tolak agar tombol tolak berfungsi
     */
    public function tolakLaporan(Request $request, $id)
    {
        $request->validate(['type' => 'required']);

        $model = match($request->type) {
            'aktivasi'   => Aktivasi::class,
            'trouble'    => Trouble::class,
            'proxy'      => Proxy::class,
            'pengajuan'  => Pengajuan::class,
            'pembubuhan' => Pembubuhan::class,
            default      => null,
        };

        if ($model) {
            $laporan = $model::findOrFail($id);
            $laporan->update([
                'is_rejected' => true,
                'tanggapan_admin' => 'Ditolak oleh admin'
            ]);
            return back()->with('success', 'Laporan berhasil ditolak.');
        }

        return back()->withErrors(['error' => 'Gagal menolak laporan.']);
    }
}