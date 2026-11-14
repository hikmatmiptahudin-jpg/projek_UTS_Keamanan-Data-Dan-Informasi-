<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminData extends Model
{
    use HasFactory;

    // Nama tabel database
    protected $table = 'admin_data';

    // Kolom yang boleh diisi melalui proses create()
    protected $fillable = [
        'judul',
        'encrypted_data',
        'encrypted_key',
        'iv',
        'auth_tag',
        'user_id',
    ];

    // Relasi dengan tabel users
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
