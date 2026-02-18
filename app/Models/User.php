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
     * DIKEMBALIKAN KE 'id' agar relasi database (Foreign Key) tidak pecah.
     * Laravel akan tetap bisa login menggunakan NIK melalui controller Auth.
     */
    protected $primaryKey = 'id';       // Gunakan id (bigint) sebagai PK utama
    public $incrementing = true;        // Aktifkan auto-increment
    protected $keyType = 'int';         // Tipe data ID adalah integer

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'id',           // Izinkan ID jika diperlukan
        'nik',          // NIK tetap disimpan sebagai identitas unik
        'name',
        'email',
        'password',
        'pin',
        'location',     
        'kecamatan_id', 
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
     */
    public function getFullLocationAttribute()
    {
        return $this->kecamatan ? $this->kecamatan->nama_kecamatan : ($this->location ?? 'WILAYAH LUAR');
    }
}