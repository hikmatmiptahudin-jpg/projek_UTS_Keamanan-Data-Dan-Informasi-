<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RsaAuthController extends Controller
{
    // Menampilkan halaman login RSA
    public function showLogin()
    {
        return view('auth.rsa-login');
    }

    // Membuat challenge acak
    public function challenge(Request $request)
    {
        // Menghapus akses administrator sebelumnya
        $request->session()->forget('rsa_admin_authenticated');

        $challenge = bin2hex(random_bytes(32));

        $request->session()->put('rsa_challenge', $challenge);
        $request->session()->put('rsa_challenge_created_at', time());

        return response()->json([
            'challenge' => $challenge
        ]);
    }

    // Memverifikasi tanda tangan RSA
    public function verify(Request $request)
    {
        $request->validate([
            'signature' => ['required', 'string', 'max:2048'],
        ]);

        $challenge = $request->session()->get('rsa_challenge');

        $createdAt = $request->session()->get(
            'rsa_challenge_created_at'
        );

        // Memeriksa challenge
        if (
            !$challenge ||
            !$createdAt ||
            time() - $createdAt > 120
        ) {
            $request->session()->forget([
                'rsa_challenge',
                'rsa_challenge_created_at',
                'rsa_admin_authenticated',
            ]);

            return response()->json([
                'authenticated' => false,
                'message' => 'Challenge tidak tersedia atau sudah kedaluwarsa. Silakan coba kembali.'
            ], 401);
        }

        // Challenge hanya boleh digunakan satu kali
        $request->session()->forget([
            'rsa_challenge',
            'rsa_challenge_created_at',
        ]);

        // Mengubah tanda tangan Base64 menjadi binary
        $signature = base64_decode(
            $request->signature,
            true
        );

        if ($signature === false || strlen($signature) !== 256) {
            $request->session()->forget('rsa_admin_authenticated');

            return response()->json([
                'authenticated' => false,
                'message' => 'Format tanda tangan RSA tidak valid.'
            ], 422);
        }

        // Membaca public key aktif
        $publicKeyPath = storage_path('app/keys/public.pem');

        $publicKeyContent = file_get_contents($publicKeyPath);

        if ($publicKeyContent === false) {
            Log::error('RSA public key tidak dapat dibaca.');

            return response()->json([
                'authenticated' => false,
                'message' => 'Konfigurasi kunci server bermasalah.'
            ], 500);
        }

        $publicKey = openssl_pkey_get_public($publicKeyContent);

        if ($publicKey === false) {
            Log::error('RSA public key tidak valid.');

            return response()->json([
                'authenticated' => false,
                'message' => 'Public key server tidak valid.'
            ], 500);
        }

        // Memverifikasi tanda tangan menggunakan SHA-256
        $result = openssl_verify(
            $challenge,
            $signature,
            $publicKey,
            OPENSSL_ALGO_SHA256
        );

        // Kesalahan internal OpenSSL
        if ($result === -1) {
            Log::error('Terjadi kesalahan saat verifikasi tanda tangan RSA.');

            return response()->json([
                'authenticated' => false,
                'message' => 'Terjadi kesalahan pada proses verifikasi RSA.'
            ], 500);
        }

        // Private key salah: masuk dashboard ciphertext
        if ($result !== 1) {
            $request->session()->forget('rsa_admin_authenticated');

            return response()->json([
                'authenticated' => false,
                'mode' => 'ciphertext',
                'message' => 'Private key tidak cocok. Dashboard dibuka dalam mode ciphertext.',
                'redirect' => route('dashboard')
            ]);
        }

        // Autentikasi RSA berhasil
        $request->session()->regenerate();

        $request->session()->put(
            'rsa_admin_authenticated',
            true
        );

        return response()->json([
            'authenticated' => true,
            'mode' => 'admin',
            'message' => 'Autentikasi RSA berhasil.',
            'redirect' => route('dashboard')
        ]);
    }

    // Membuka dashboard dalam mode ciphertext
    public function ciphertextMode(Request $request)
    {
        // Menghapus seluruh status autentikasi RSA
        $request->session()->forget([
            'rsa_admin_authenticated',
            'rsa_challenge',
            'rsa_challenge_created_at',
        ]);

        return response()->json([
            'success' => true,
            'mode' => 'ciphertext',
            'redirect' => route('dashboard'),
        ]);
    }

    // Logout administrator RSA
    public function logout(Request $request)
    {
        $request->session()->forget([
            'rsa_admin_authenticated',
            'rsa_challenge',
            'rsa_challenge_created_at',
        ]);

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('landing');
    }
}
