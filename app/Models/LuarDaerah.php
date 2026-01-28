<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LuarDaerah extends Model
{
    use HasFactory;

    protected $table = 'luar_daerahs';

    protected $fillable = [
        'user_id',
        'nik',
        'nik_luar_daerah',
        'jenis_dokumen',
        'tanggapan_admin',
        'status',
        'processed_by',
        'is_rejected',
    ];

    protected $casts = [
        'is_rejected' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}