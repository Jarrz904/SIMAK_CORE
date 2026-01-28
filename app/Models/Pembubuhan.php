<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pembubuhan extends Model
{
    use HasFactory;

    protected $table = 'pembubuhans';

    protected $fillable = [
        'user_id',
        'nik',
        'jenis_dokumen',
        'tanggapan_admin',
        'status',
        'processed_by',
        'is_rejected',
    ];

    protected $casts = [
        'is_rejected' => 'boolean',
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
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
}