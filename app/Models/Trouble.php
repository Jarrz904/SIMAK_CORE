<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Trouble extends Model
{
    use HasFactory;

    // Menentukan nama tabel secara eksplisit
    protected $table = 'troubles';

    // Kolom yang diizinkan untuk diisi (Mass Assignment)
    protected $fillable = [
        'user_id',
        'kategori',
        'deskripsi',
        'foto_trouble',
        'status',
        'tanggapan_admin',
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
     * Relasi ke Admin yang memproses pengajuan
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
}