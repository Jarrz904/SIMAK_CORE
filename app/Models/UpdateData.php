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
     * Cast attributes.
     */
    protected $casts = [
        // 'array' cast otomatis melakukan json_encode/decode
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
     * Helper Methods
     */
    public function isPending() 
    { 
        // Menggunakan trim() untuk memastikan string kosong tetap dianggap pending
        return empty(trim($this->tanggapan_admin)) && ($this->status === 'pending' || is_null($this->status)); 
    }

    public function isApproved() 
    { 
        return $this->status === 'selesai' || !empty(trim($this->tanggapan_admin)); 
    }

    public function isRejected() 
    { 
        return $this->status === 'ditolak'; 
    }

    /**
     * Aksesor Lampiran
     * Penting: Di Controller Anda memanggil $item->foto_ktp.
     * Kita tambahkan aksesor agar Controller tidak error saat mencari 'foto_ktp'.
     */
    public function getFotoKtpAttribute()
    {
        return $this->getFirstLampiranAttribute();
    }

    public function getFirstLampiranAttribute()
    {
        // Pastikan lampiran didecode menjadi array
        $files = $this->lampiran;
        if (is_array($files) && count($files) > 0) {
            return $files[0];
        }
        return null;
    }
}