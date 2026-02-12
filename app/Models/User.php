<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * KONFIGURASI PRIMARY KEY
     * Menjadikan NIK sebagai PK utama menggantikan ID
     */
    protected $primaryKey = 'nik';      // Set PK ke kolom NIK
    public $incrementing = false;       // Matikan auto-increment karena NIK adalah string
    protected $keyType = 'string';      // Beritahu Eloquent bahwa PK bertipe string

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'nik',          // Pastikan NIK masuk dalam fillable
        'name',
        'email',
        'password',
        'pin',
        'location',     // Tetap ada untuk backup data teks lama
        'kecamatan_id', // Agar terhubung ke tabel kecamatans
        'role',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     */
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