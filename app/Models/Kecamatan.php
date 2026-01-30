<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kecamatan extends Model
{
    use HasFactory;

    // Nama tabel sudah benar
    protected $table = 'kecamatans';

    /**
     * Menonaktifkan timestamps (created_at, updated_at) otomatis Laravel 
     * jika di migration Anda hanya menggunakan 'created_at' manual.
     * Jika Anda ingin Laravel mengelola updated_at juga, biarkan true.
     */
    public $timestamps = false; 

    // Kolom yang disesuaikan dengan migration tadi
    protected $fillable = [
        'nama_kecamatan',
        'kode_wilayah', // Tadi di migration namanya 'kode_wilayah', bukan 'kode_kecamatan'
        'status',       // Penting untuk filter 'aktif'/'nonaktif'
    ];

    /**
     * Relasi ke model User
     * Karena di UserController Anda menyimpan nama kecamatan ke kolom 'location' pada tabel users
     */
    public function users(): HasMany
    {
        // Sesuaikan jika Anda menggunakan 'location' (string) atau 'kecamatan_id' (integer)
        return $this->hasMany(User::class, 'location', 'nama_kecamatan');
    }

    /**
     * Relasi ke model lain (Contoh: Trouble)
     */
    public function troubles(): HasMany
    {
        // Jika nanti Anda menambahkan kolom 'kecamatan_id' di tabel troubles
        return $this->hasMany(Trouble::class, 'kecamatan_id');
    }
}