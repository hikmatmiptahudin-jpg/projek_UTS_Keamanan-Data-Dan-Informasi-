<?php

namespace App\Http\Controllers;

use App\Services\HybridEncryptionService;
use Illuminate\Http\Request;

class EncryptionDemoController extends Controller
{
    public function index()
    {
        return view('encryption.demo');
    }

    public function process(Request $request, HybridEncryptionService $encryption)
    {
        $request->validate([
            'plain_text' => ['required', 'string', 'max:5000'],
        ]);

        // Mengambil data asli dari input
        $plainText = $request->plain_text;

        // Menjalankan enkripsi sebenarnya
        $encrypted = $encryption->encrypt($plainText);

        // Menjalankan dekripsi sebenarnya
        $decrypted = $encryption->decrypt(
            $encrypted['encrypted_data'],
            $encrypted['encrypted_key'],
            $encrypted['iv'],
            $encrypted['auth_tag']
        );

        return view('encryption.demo', [
            'plainText' => $plainText,
            'encrypted' => $encrypted,
            'decrypted' => $decrypted,
        ]);
    }
}
