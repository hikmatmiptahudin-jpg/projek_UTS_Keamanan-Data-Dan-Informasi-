<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('admin_data', function (Blueprint $table) {
        $table->id();

        $table->string('judul');

        // Data yang telah dienkripsi AES
        $table->longText('encrypted_data');

        // Kunci AES yang dienkripsi RSA
        $table->longText('encrypted_key');

        // IV untuk proses AES
        $table->string('iv');

        // Authentication tag
        $table->string('auth_tag');

        $table->foreignId('user_id')
              ->constrained()
              ->cascadeOnDelete();

        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('admin_data');
}
};
