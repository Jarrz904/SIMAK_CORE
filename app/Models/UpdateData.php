<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UpdateData extends Model
{
    use HasFactory;

    protected $table = 'update_datas';

    protected $fillable = [
        'user_id', 
        'nik_pemohon', 
        'jenis_layanan', 
        'deskripsi', 
        'lampiran', 
        'status',
        'tanggapan_admin',
        'processed_by'
    ];

    /**
     * Cast attributes to native types.
     * Penting: 'array' memastikan data tersimpan sebagai JSON di DB 
     * dan otomatis menjadi Array saat dipanggil di Controller/Blade.
     */
    protected $casts = [
        'lampiran' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relasi ke User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Helper Methods (Opsional tapi sangat berguna di Blade)
     */
    public function isPending() 
    { 
        return strtolower($this->status) === 'pending'; 
    }

    public function isApproved() 
    { 
        return strtolower($this->status) === 'approved'; 
    }

    public function isRejected() 
    { 
        return strtolower($this->status) === 'rejected'; 
    }
}