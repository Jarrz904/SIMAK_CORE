<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UpdateData extends Model
{
    protected $table = 'update_datas';

    protected $fillable = [
        'user_id', 
        'nik_pemohon', 
        'jenis_layanan', 
        'deskripsi',      // Sesuai Database
        'lampiran', 
        'status',
        'tanggapan_admin', // Sesuai Database (Gambar 3)
        'processed_by'    // Sesuai Database (Gambar 3)
    ];

    protected $casts = [
        'lampiran' => 'array', 
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Helper untuk mempermudah pengecekan status
    public function isPending() { return $this->status === 'pending'; }
    public function isApproved() { return $this->status === 'approved'; }
    public function isRejected() { return $this->status === 'rejected'; }
}