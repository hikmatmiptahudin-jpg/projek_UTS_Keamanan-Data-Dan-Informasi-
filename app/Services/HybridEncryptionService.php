<?php

namespace App\Services;

class HybridEncryptionService
{
    // Lokasi RSA Public Key
    private $publicKeyPath;

    public function __construct()
    {
        $this->publicKeyPath = storage_path('app/keys/public.pem');
    }

    // ENKRIPSI DATA MENGGUNAKAN AES-256-GCM DAN RSA-OAEP
    public function encrypt($plainText)
    {
        // Membuat kunci AES 256-bit secara acak
        $aesKey = random_bytes(32);

        // Membuat IV 12 byte untuk AES-GCM
        $iv = random_bytes(12);

        // Mengenkripsi plaintext menggunakan AES-256-GCM
        $encryptedData = openssl_encrypt(
            $plainText,
            'aes-256-gcm',
            $aesKey,
            OPENSSL_RAW_DATA,
            $iv,
            $tag
        );

        if ($encryptedData === false) {
            throw new \Exception('Gagal mengenkripsi data AES.');
        }

        // Membaca RSA Public Key
        $publicKey = file_get_contents($this->publicKeyPath);

        if ($publicKey === false) {
            throw new \Exception('Public key tidak ditemukan.');
        }

        // Membungkus kunci AES menggunakan RSA-OAEP
        $encryptedKey = '';

        $success = openssl_public_encrypt(
            $aesKey,
            $encryptedKey,
            $publicKey,
            OPENSSL_PKCS1_OAEP_PADDING
        );

        if (!$success) {
            throw new \Exception('Gagal mengenkripsi kunci AES dengan RSA.');
        }

        // Mengembalikan hasil enkripsi dalam format Base64
        return [
            'encrypted_data' => base64_encode($encryptedData),
            'encrypted_key' => base64_encode($encryptedKey),
            'iv' => base64_encode($iv),
            'auth_tag' => base64_encode($tag),
        ];
    }
}
