<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Import ini

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'nik',
        'pin',
        'location',     // Tetap ada untuk backup data teks lama
        'kecamatan_id', // TAMBAHKAN INI: Agar terhubung ke tabel kecamatans
        'role',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /**
     * RELASI: User dimiliki oleh satu Kecamatan
     */
    public function kecamatan(): BelongsTo
    {
        return $this->belongsTo(Kecamatan::class, 'kecamatan_id');
    }

    /**
     * Helper untuk mendapatkan nama lokasi
     * Jika kecamatan_id kosong, akan mengambil dari field location (teks)
     */
    public function getFullLocationAttribute()
    {
        return $this->kecamatan ? $this->kecamatan->nama_kecamatan : ($this->location ?? 'WILAYAH LUAR');
    }
}