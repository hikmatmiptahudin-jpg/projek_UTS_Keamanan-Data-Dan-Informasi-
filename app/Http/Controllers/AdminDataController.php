<?php

namespace App\Http\Controllers;

use App\Models\AdminData;
use App\Models\ActivityLog;
use App\Services\HybridEncryptionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminDataController extends Controller
{
    protected $encryption;

    public function __construct(HybridEncryptionService $encryption)
    {
        $this->encryption = $encryption;
    }

    // Memastikan hanya admin yang dapat mengelola data
    private function pastikanAdmin()
    {
        abort_unless(
            Auth::user() && Auth::user()->role === 'admin',
            403,
            'Anda tidak memiliki izin untuk melakukan tindakan ini.'
        );
    }

    // Mencatat aktivitas pengguna
    private function catatAktivitas($aktivitas, $deskripsi)
    {
        ActivityLog::create([
            'user_id' => Auth::id(),
            'aktivitas' => $aktivitas,
            'deskripsi' => $deskripsi,
        ]);
    }

    // Admin dan user dapat melihat seluruh daftar ciphertext
    public function index()
    {
        $data = AdminData::latest()->get();

        $isAdmin = Auth::user()->role === 'admin';

        return view('data.index', compact('data', 'isAdmin'));
    }

    // Form tambah data hanya untuk admin
    public function create()
    {
        $this->pastikanAdmin();

        return view('data.create');
    }

    // Menyimpan data dengan enkripsi, hanya untuk admin
    public function store(Request $request)
    {
        $this->pastikanAdmin();

        $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
        ]);

        $encrypted = $this->encryption->encrypt($request->isi);

        AdminData::create([
            'judul' => $request->judul,
            'encrypted_data' => $encrypted['encrypted_data'],
            'encrypted_key' => $encrypted['encrypted_key'],
            'iv' => $encrypted['iv'],
            'auth_tag' => $encrypted['auth_tag'],
            'user_id' => Auth::id(),
        ]);

        $this->catatAktivitas(
            'Tambah Data',
            'Menambahkan dan mengenkripsi data: ' . $request->judul
        );

        return redirect()->route('data.index')
            ->with('success', 'Data berhasil dienkripsi dan disimpan.');
    }

    // Admin melihat hasil dekripsi, user hanya melihat ciphertext
    public function show($id)
    {
        $item = AdminData::findOrFail($id);

        $isAdmin = Auth::user()->role === 'admin';

        $isi = null;

        if ($isAdmin) {
            $isi = $this->encryption->decrypt(
                $item->encrypted_data,
                $item->encrypted_key,
                $item->iv,
                $item->auth_tag
            );

            $this->catatAktivitas(
                'Dekripsi Data',
                'Membuka dan mendekripsi data: ' . $item->judul
            );
        }

        return view('data.show', compact('item', 'isi', 'isAdmin'));
    }

    // Form edit hanya untuk admin
    public function edit($id)
    {
        $this->pastikanAdmin();

        $item = AdminData::findOrFail($id);

        return view('data.edit', compact('item'));
    }

    // Memperbarui data hanya untuk admin
    public function update(Request $request, $id)
    {
        $this->pastikanAdmin();

        $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'nullable|string',
        ]);

        $item = AdminData::findOrFail($id);

        $item->judul = $request->judul;

        if ($request->filled('isi')) {
            $encrypted = $this->encryption->encrypt($request->isi);

            $item->encrypted_data = $encrypted['encrypted_data'];
            $item->encrypted_key = $encrypted['encrypted_key'];
            $item->iv = $encrypted['iv'];
            $item->auth_tag = $encrypted['auth_tag'];
        }

        $item->save();

        $this->catatAktivitas(
            'Ubah Data',
            'Mengubah data: ' . $item->judul
        );

        return redirect()->route('data.index')
            ->with('success', 'Data berhasil diperbarui.');
    }

    // Menghapus data hanya untuk admin
    public function destroy($id)
    {
        $this->pastikanAdmin();

        $item = AdminData::findOrFail($id);

        $this->catatAktivitas(
            'Hapus Data',
            'Menghapus data: ' . $item->judul
        );

        $item->delete();

        return redirect()->route('data.index')
            ->with('success', 'Data berhasil dihapus.');
    }
}
