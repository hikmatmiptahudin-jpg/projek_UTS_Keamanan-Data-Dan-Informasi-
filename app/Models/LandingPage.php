<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LandingPage extends Model
{
    use HasFactory;

    protected $table = 'landing_pages';

    protected $fillable = [
        'section',
        'judul',
        'deskripsi',
        'gambar',
        'tombol_teks',
        'tombol_url',
        'aktif',
    ];

    protected $casts = [
        'aktif' => 'boolean',
    ];
}
