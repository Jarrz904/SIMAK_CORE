<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pengajuan extends Model
{
    use HasFactory;

    protected $table = 'pengajuans';

    protected $fillable = [
        'user_id',
        'nama_lengkap',
        'nik_aktivasi',
        'kategori',      
        'deskripsi',
        'foto_dokumen',
        'tanggapan_admin',
        'status',
        'processed_by', 
        'is_rejected'   
    ];

    /**
     * Relasi ke User (Pemohon/Warga)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke User (Admin yang memproses)
     * Ini yang menyebabkan error sebelumnya karena belum ada.
     */
    public function admin(): BelongsTo
    {
        // Menghubungkan processed_by ke id di table users
        return $this->belongsTo(User::class, 'processed_by');
    }
}