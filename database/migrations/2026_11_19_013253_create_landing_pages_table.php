
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('landing_pages', function (Blueprint $table) {
            $table->id();

            // Identitas bagian landing page
            $table->string('section')->unique();

            // Konten yang ditampilkan
            $table->string('judul');
            $table->text('deskripsi')->nullable();

            // Gambar atau ilustrasi opsional
            $table->string('gambar')->nullable();

            // Tombol pada bagian tertentu
            $table->string('tombol_teks')->nullable();
            $table->string('tombol_url')->nullable();

            // Status tampil atau disembunyikan
            $table->boolean('aktif')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('landing_pages');
    }
};
