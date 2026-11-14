<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Services\HybridEncryptionService;

class EncryptionTest extends TestCase
{
    
public function test_modified_ciphertext_cannot_be_decrypted()
{
    $service = new HybridEncryptionService();

    $originalText = 'Data rahasia SecureAdmin';

    $encrypted = $service->encrypt($originalText);

    // Ubah ciphertext agar datanya tidak lagi sama
    $ciphertext = base64_decode(
        $encrypted['encrypted_data']
    );

    $ciphertext[0] = chr(ord($ciphertext[0]) ^ 1);

    $modifiedCiphertext = base64_encode($ciphertext);

    // Pastikan dekripsi gagal karena integritas berubah
    $this->expectException(\Exception::class);

    $service->decrypt(
        $modifiedCiphertext,
        $encrypted['encrypted_key'],
        $encrypted['iv'],
        $encrypted['auth_tag']
    );
}

public function test_encryption_and_decryption()
{
    $service = new \App\Services\HybridEncryptionService();

    $originalText = 'Ini adalah data rahasia SecureAdmin.';

    // Enkripsi data
    $encrypted = $service->encrypt($originalText);

    // Dekripsi data
    $decrypted = $service->decrypt(
        $encrypted['encrypted_data'],
        $encrypted['encrypted_key'],
        $encrypted['iv'],
        $encrypted['auth_tag']
    );

    // Memastikan hasil dekripsi sama dengan data asli
    $this->assertEquals($originalText, $decrypted);
}

}
