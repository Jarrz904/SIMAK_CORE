<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kecamatan extends Model
{
    use HasFactory;

    // Pastikan nama tabel sesuai dengan yang ada di database Anda
    protected $table = 'kecamatans';

    // Kolom yang boleh diisi secara massal
    protected $fillable = [
        'nama_kecamatan',
        'kode_kecamatan', // Jika ada
    ];

    /**
     * Relasi ke model Laporan (Satu kecamatan memiliki banyak laporan)
     * Sesuaikan nama model Laporan Anda (misal: Trouble, Proxy, dll)
     */
    public function laporans(): HasMany
    {
        // Ganti 'Laporan' dengan nama model utama kendala Anda
        // Pastikan di tabel laporans ada kolom 'kecamatan_id'
        return $this->hasMany(Laporan::class, 'kecamatan_id');
    }
}