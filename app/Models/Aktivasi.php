<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class Aktivasi extends Model
{
    use HasFactory;

    protected $table = 'aktivasis';

    protected $fillable = [
        'user_id',
        'nama_lengkap',
        'nik_aktivasi',
        'jenis_layanan',
        'alasan',
        'foto_ktp',
        'status',
        'tanggapan_admin', 
        'processed_by',
        'is_rejected'
    ];

    /**
     * PENTING: Cast kolom foto_ktp menjadi array agar Laravel otomatis
     * melakukan json_decode saat data diambil.
     */
    protected $casts = [
        'foto_ktp' => 'array',
        'is_rejected' => 'boolean',
    ];

    /**
     * Tambahkan atribut virtual agar bisa diakses langsung di Blade/Alpine.js
     */
    protected $appends = ['foto_ktp_urls'];

    /**
     * Accessor untuk mendapatkan semua URL foto dalam bentuk array.
     */
    public function getFotoKtpUrlsAttribute()
    {
        // Jika tidak ada foto, kembalikan array kosong
        if (!$this->foto_ktp || !is_array($this->foto_ktp)) {
            return [];
        }

        // Ubah setiap path menjadi URL lengkap
        return array_map(function ($path) {
            return asset('storage/' . $path);
        }, $this->foto_ktp);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
}