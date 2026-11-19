
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Hapus foreign key lama
        DB::statement("
            ALTER TABLE admin_data
            DROP FOREIGN KEY admin_data_user_id_foreign
        ");

        // Izinkan user_id bernilai NULL
        DB::statement("
            ALTER TABLE admin_data
            MODIFY user_id BIGINT UNSIGNED NULL
        ");

        // Pertahankan relasi, tetapi jangan hapus data ketika user dihapus
        DB::statement("
            ALTER TABLE admin_data
            ADD CONSTRAINT admin_data_user_id_foreign
            FOREIGN KEY (user_id)
            REFERENCES users(id)
            ON DELETE SET NULL
        ");
    }

    public function down(): void
    {
        // Kembalikan ke struktur sebelumnya
        DB::statement("
            ALTER TABLE admin_data
            DROP FOREIGN KEY admin_data_user_id_foreign
        ");

        DB::statement("
            ALTER TABLE admin_data
            MODIFY user_id BIGINT UNSIGNED NOT NULL
        ");

        DB::statement("
            ALTER TABLE admin_data
            ADD CONSTRAINT admin_data_user_id_foreign
            FOREIGN KEY (user_id)
            REFERENCES users(id)
            ON DELETE CASCADE
        ");
    }
};
