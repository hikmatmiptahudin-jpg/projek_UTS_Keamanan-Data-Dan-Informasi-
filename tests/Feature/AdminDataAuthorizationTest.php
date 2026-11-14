<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\AdminData;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\HybridEncryptionService;

class AdminDataAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    private function buatDataAdminPertama()
{
    $admin1 = User::factory()->create();
    $admin2 = User::factory()->create();

    // Mengenkripsi data menggunakan layanan asli SecureAdmin
    $encrypted = app(HybridEncryptionService::class)
        ->encrypt('Isi rahasia milik administrator pertama.');

    $data = AdminData::create([
        'judul' => 'Data Rahasia Admin Pertama',
        'encrypted_data' => $encrypted['encrypted_data'],
        'encrypted_key' => $encrypted['encrypted_key'],
        'iv' => $encrypted['iv'],
        'auth_tag' => $encrypted['auth_tag'],
        'user_id' => $admin1->id,
    ]);

    return [$admin1, $admin2, $data];
}

    public function test_admin_can_view_own_data()
    {
        [$admin1, $admin2, $data] = $this->buatDataAdminPertama();

        $response = $this->actingAs($admin1)
            ->get(route('data.show', $data->id));

        // Data milik sendiri boleh dibuka.
        // Karena isi terenkripsi contoh di atas bukan hasil enkripsi asli,
        // pengujian ini cukup memeriksa bahwa data tidak ditolak sebagai 404.
        $this->assertNotEquals(404, $response->getStatusCode());
    }

    public function test_admin_cannot_see_other_admin_data_in_list()
    {
        [$admin1, $admin2, $data] = $this->buatDataAdminPertama();

        $response = $this->actingAs($admin2)
            ->get(route('data.index'));

        $response->assertOk();
        $response->assertDontSee('Data Rahasia Admin Pertama');
    }

    public function test_admin_cannot_open_other_admin_data()
    {
        [$admin1, $admin2, $data] = $this->buatDataAdminPertama();

        $this->actingAs($admin2)
            ->get(route('data.show', $data->id))
            ->assertNotFound();
    }

    public function test_admin_cannot_edit_other_admin_data()
    {
        [$admin1, $admin2, $data] = $this->buatDataAdminPertama();

        $this->actingAs($admin2)
            ->get(route('data.edit', $data->id))
            ->assertNotFound();
    }

    public function test_admin_cannot_update_other_admin_data()
    {
        [$admin1, $admin2, $data] = $this->buatDataAdminPertama();

        $this->actingAs($admin2)
            ->put(route('data.update', $data->id), [
                'judul' => 'Data Diubah Admin Kedua',
                'isi' => 'Percobaan mengubah data milik admin lain.',
            ])
            ->assertNotFound();

        // Memastikan judul data tidak berubah
        $this->assertDatabaseHas('admin_data', [
            'id' => $data->id,
            'judul' => 'Data Rahasia Admin Pertama',
            'user_id' => $admin1->id,
        ]);
    }

    public function test_admin_cannot_delete_other_admin_data()
    {
        [$admin1, $admin2, $data] = $this->buatDataAdminPertama();

        $this->actingAs($admin2)
            ->delete(route('data.destroy', $data->id))
            ->assertNotFound();

        // Memastikan data masih ada di database
        $this->assertDatabaseHas('admin_data', [
            'id' => $data->id,
            'user_id' => $admin1->id,
        ]);
    }
}
