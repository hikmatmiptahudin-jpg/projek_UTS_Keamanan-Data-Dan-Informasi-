<?php

namespace App\Services;

class HybridEncryptionService
{
    // Lokasi kunci RSA
    private $privateKeyPath;
    private $publicKeyPath;

    public function __construct()
    {
        $this->privateKeyPath = storage_path('app/keys/private.pem');
        $this->publicKeyPath = storage_path('app/keys/public.pem');
    }

    // ENKRIPSI DATA
    public function encrypt($plainText)
    {
        // Membuat kunci AES 256-bit secara acak
        $aesKey = random_bytes(32);

        // Membuat IV untuk AES-GCM
        $iv = random_bytes(12);

        // Mengenkripsi data menggunakan AES-256-GCM
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

        // Mengenkripsi kunci AES menggunakan RSA
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

        // Mengembalikan hasil dalam format Base64
        return [
            'encrypted_data' => base64_encode($encryptedData),
            'encrypted_key' => base64_encode($encryptedKey),
            'iv' => base64_encode($iv),
            'auth_tag' => base64_encode($tag),
        ];
    }

    // DEKRIPSI DATA
    public function decrypt($encryptedData, $encryptedKey, $iv, $authTag)
    {
        // Membaca RSA Private Key
        $privateKey = file_get_contents($this->privateKeyPath);

        if ($privateKey === false) {
            throw new \Exception('Private key tidak ditemukan.');
        }

        // Mengembalikan data Base64
        $encryptedData = base64_decode($encryptedData, true);
        $encryptedKey = base64_decode($encryptedKey, true);
        $iv = base64_decode($iv, true);
        $authTag = base64_decode($authTag, true);

        if (
            $encryptedData === false ||
            $encryptedKey === false ||
            $iv === false ||
            $authTag === false
        ) {
            throw new \Exception('Format data enkripsi tidak valid.');
        }

        // Mendekripsi kunci AES menggunakan RSA
        $aesKey = '';

        $success = openssl_private_decrypt(
            $encryptedKey,
            $aesKey,
            $privateKey,
            OPENSSL_PKCS1_OAEP_PADDING
        );

        if (!$success) {
            throw new \Exception('Gagal mendekripsi kunci AES.');
        }

        // Mendekripsi data menggunakan AES-256-GCM
        $plainText = openssl_decrypt(
            $encryptedData,
            'aes-256-gcm',
            $aesKey,
            OPENSSL_RAW_DATA,
            $iv,
            $authTag
        );

        if ($plainText === false) {
            throw new \Exception('Gagal mendekripsi data. Data mungkin telah berubah.');
        }

        return $plainText;
    }
}
