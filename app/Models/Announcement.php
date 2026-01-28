<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    // Tambahkan baris ini agar Controller bisa menyimpan data
    protected $fillable = [
        'title',
        'message',
        'type',
    ];
}