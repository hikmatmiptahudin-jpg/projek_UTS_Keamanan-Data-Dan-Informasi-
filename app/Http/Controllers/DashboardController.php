<?php

namespace App\Http\Controllers;

use App\Models\AdminData;
use App\Services\HybridEncryptionService;

class DashboardController extends Controller
{
    protected $encryption;

    public function __construct(HybridEncryptionService $encryption)
    {
        $this->encryption = $encryption;
    }

    public function index()
    {
        // Menghitung seluruh data yang tersimpan
        $totalData = AdminData::count();

        // Memeriksa status autentikasi administrator
        $isAdmin = session('rsa_admin_authenticated') === true;

        // Menyiapkan seluruh teks dashboard
        $dashboardContent = [
            'page_title' => 'Dashboard',

            'sidebar_brand' => 'SecureAdmin',
            'nav_dashboard' => 'Dashboard',
            'nav_data' => 'Manajemen Data',
            'nav_add' => 'Tambah Data',
            'nav_encryption' => 'Enkripsi Data',
            'nav_history' => 'Riwayat Aktivitas',

            'access_status' => 'Status akses',
            'admin_status' => 'Administrator RSA Terverifikasi',
            'ciphertext_status' => 'Mode Ciphertext',
            'logout' => 'Logout RSA',
            'login' => 'Login dengan Kunci RSA',

            'total_label' => 'Total Data Tersimpan',
            'total_value' => (string) $totalData,
            'total_description' => 'Data terenkripsi',

            'symmetric_label' => 'Algoritma Simetris',
            'symmetric_value' => 'AES-256-GCM',
            'symmetric_description' => 'Enkripsi isi data',

            'asymmetric_label' => 'Algoritma Asimetris',
            'asymmetric_value' => 'RSA-2048',
            'asymmetric_description' => 'Perlindungan kunci AES',

            'welcome_title' => 'Selamat Datang di SecureAdmin',
            'welcome_description' =>
                'Sistem pengelolaan data administrator dengan penerapan kriptografi AES-256-GCM dan RSA-2048.',
            'admin_description' =>
                'Admin dapat mengelola data, melakukan enkripsi, dan mendekripsi data yang tersimpan.',
            'manage_button' => 'Kelola Data',
        ];

        // Mengubah kumpulan teks menjadi JSON
        $plainText = json_encode(
            $dashboardContent,
            JSON_UNESCAPED_UNICODE
        );

        // Mengenkripsi seluruh teks dashboard
        $encryptedDashboard = $this->encryption->encrypt($plainText);

        // Mengirim hasil enkripsi ke halaman dashboard
        return view('dashboard', [
            'isAdmin' => $isAdmin,
            'encryptedDashboard' => $encryptedDashboard,
        ]);
    }
}
