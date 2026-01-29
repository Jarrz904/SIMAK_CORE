<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Aktivasi extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama_lengkap',
        'nik_aktivasi',
        'jenis_layanan',
        'alasan',
        'foto_ktp' => 'array',
        'status',
        'tanggapan_admin', 
        'processed_by',
        'is_rejected'
    ];

    /**
     * Relasi ke User (Pemohon)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke Admin yang memproses
     * Menghilangkan RelationNotFoundException di Dashboard
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
}