<?php

namespace App\Http\Controllers;

use App\Models\AdminData;
use App\Models\ActivityLog;
use App\Services\HybridEncryptionService;
use Illuminate\Http\Request;

class AdminDataController extends Controller
{
    protected $encryption;

    public function __construct(HybridEncryptionService $encryption)
    {
        $this->encryption = $encryption;
    }

    // Memastikan administrator sudah terverifikasi menggunakan RSA
    private function pastikanAdmin()
    {
        abort_unless(
            session('rsa_admin_authenticated') === true,
            403,
            'Akses ditolak. Autentikasi RSA diperlukan.'
        );
    }

    // Mencatat aktivitas tanpa menyimpan informasi rahasia
    private function catatAktivitas($aktivitas, $id)
    {
        ActivityLog::create([
            'user_id' => null,
            'aktivitas' => $aktivitas,
            'deskripsi' => 'ID data: ' . $id,
        ]);
    }

    // Menampilkan daftar data terenkripsi
    public function index()
    {
        $data = AdminData::latest()->get();

        $isAdmin = session('rsa_admin_authenticated') === true;

        return view('data.index', compact('data', 'isAdmin'));
    }

    // Form tambah data
    public function create()
    {
        $this->pastikanAdmin();

        return view('data.create');
    }

    // Menambahkan data
    // Catatan: proses store masih menggunakan enkripsi server-side.
    public function store(Request $request)
    {
        $this->pastikanAdmin();

        $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
        ]);

        $plainText = json_encode([
            'judul' => $request->judul,
            'isi' => $request->isi,
        ], JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);

        $encrypted = $this->encryption->encrypt($plainText);

        $item = AdminData::create([
            'judul' => 'Data terenkripsi',
            'encrypted_data' => $encrypted['encrypted_data'],
            'encrypted_key' => $encrypted['encrypted_key'],
            'iv' => $encrypted['iv'],
            'auth_tag' => $encrypted['auth_tag'],
            'user_id' => null,
        ]);

        $this->catatAktivitas('Tambah Data', $item->id);

        return redirect()->route('data.index')
            ->with('success', 'Data berhasil dienkripsi dan disimpan.');
    }

    // Menampilkan ciphertext dan halaman dekripsi browser
    public function show($id)
    {
        $item = AdminData::findOrFail($id);

        $isAdmin = session('rsa_admin_authenticated') === true;

        return view('data.show', compact('item', 'isAdmin'));
    }

    // Form edit data
    public function edit($id)
    {
        $this->pastikanAdmin();

        $item = AdminData::findOrFail($id);

        $isAdmin = session('rsa_admin_authenticated') === true;

        // Membaca public key RSA
        $publicKeyPath = storage_path('app/keys/public.pem');

        if (!file_exists($publicKeyPath)) {
            abort(500, 'Public key RSA tidak ditemukan.');
        }

        $publicKeyPem = file_get_contents($publicKeyPath);

        return view('data.edit', compact(
            'item',
            'isAdmin',
            'publicKeyPem'
        ));
    }

    // Memperbarui data menggunakan ciphertext dari browser
    public function update(Request $request, $id)
    {
        $this->pastikanAdmin();

        // Validasi empat komponen enkripsi
        $validated = $request->validate([
            'encrypted_data' => 'required|string',
            'encrypted_key' => 'required|string',
            'iv' => 'required|string',
            'auth_tag' => 'required|string',
        ]);

        // Memastikan data yang diedit tersedia
        $item = AdminData::findOrFail($id);

        // Memeriksa format Base64 setiap komponen
        foreach ([
            'encrypted_data',
            'encrypted_key',
            'iv',
            'auth_tag'
        ] as $field) {

            if (base64_decode($validated[$field], true) === false) {
                return back()->withErrors([
                    $field => 'Format data terenkripsi tidak valid.'
                ]);
            }
        }

        // Memeriksa panjang IV dan authentication tag
        $iv = base64_decode($validated['iv'], true);
        $tag = base64_decode($validated['auth_tag'], true);

        if (strlen($iv) !== 12 || strlen($tag) !== 16) {
            return back()->withErrors([
                'encrypted_data' => 'Format IV atau authentication tag tidak valid.'
            ]);
        }

        // Memperbarui ciphertext tanpa menerima plaintext
        $item->update([
            'judul' => 'Data terenkripsi',
            'encrypted_data' => $validated['encrypted_data'],
            'encrypted_key' => $validated['encrypted_key'],
            'iv' => $validated['iv'],
            'auth_tag' => $validated['auth_tag'],
            'user_id' => null,
        ]);

        // Mencatat aktivitas
        $this->catatAktivitas('Ubah Data', $item->id);

        return redirect()->route('data.index')
            ->with('success', 'Data berhasil diperbarui dan dienkripsi ulang di browser.');
    }

    // Menghapus data
    public function destroy($id)
    {
        $this->pastikanAdmin();

        $item = AdminData::findOrFail($id);

        $this->catatAktivitas('Hapus Data', $item->id);

        $item->delete();

        return redirect()->route('data.index')
            ->with('success', 'Data berhasil dihapus.');
    }
}
