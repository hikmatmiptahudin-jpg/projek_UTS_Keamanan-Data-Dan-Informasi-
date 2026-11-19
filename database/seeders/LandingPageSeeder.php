<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LandingPage;

class LandingPageSeeder extends Seeder
{
    public function run(): void
    {
        $sections = [
            [
                'section' => 'hero',
                'judul' => 'Keamanan Data dengan SecureAdmin',
                'deskripsi' => 'Sistem pengelolaan dan perlindungan data menggunakan teknologi kriptografi AES-256-GCM dan RSA-2048.',
                'tombol_teks' => 'Pelajari Lebih Lanjut',
                'tombol_url' => '#tentang',
                'aktif' => true,
            ],
            [
                'section' => 'about',
                'judul' => 'Tentang SecureAdmin',
                'deskripsi' => 'SecureAdmin merupakan sistem pengelolaan data administrator yang dirancang untuk menjaga kerahasiaan, integritas, dan keamanan informasi menggunakan teknologi kriptografi.',
                'aktif' => true,
            ],
            [
                'section' => 'features',
                'judul' => 'Teknologi Keamanan',
                'deskripsi' => 'Perlindungan informasi menggunakan kombinasi algoritma kriptografi modern dan autentikasi berbasis kunci RSA.',
                'aktif' => true,
            ],
        ];

        foreach ($sections as $section) {
            LandingPage::updateOrCreate(
                ['section' => $section['section']],
                $section
            );
        }
    }
}
