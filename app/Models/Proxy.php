<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Proxy extends Model
{
    use HasFactory;

    protected $table = 'proxies';

    protected $fillable = [
        'user_id', 
        'deskripsi', 
        'foto_proxy', 
        'status',
        'tanggapan_admin',
        'processed_by',
        'is_rejected'
    ];

    /**
     * Relasi ke model User (Pemohon)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke model User (Admin yang memproses)
     * Tambahkan ini untuk memperbaiki RelationNotFoundException
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
}