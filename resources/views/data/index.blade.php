@extends('layouts.admin')

@section('title', 'Data Rahasia')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3>Data Rahasia</h3>
            <p class="text-muted mb-0">
                Manajemen data dengan enkripsi AES-256-GCM dan RSA-2048.
            </p>
        </div>

        @if($isAdmin)
            <a href="{{ route('data.create') }}" class="btn btn-primary">
                + Tambah Data
            </a>
        @endif
    </div>

    <div class="alert {{ $isAdmin ? 'alert-success' : 'alert-warning' }}">
        @if($isAdmin)
            <strong>Administrator RSA Terverifikasi.</strong>
            Anda memiliki akses untuk mengelola data.
            Dekripsi dilakukan di browser menggunakan private key.
        @else
            <strong>Mode Ciphertext.</strong>
            Anda hanya dapat melihat informasi data terenkripsi.
            Autentikasi RSA diperlukan untuk mengelola data.
        @endif
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white">
            <h5 class="mb-0">Daftar Data Terenkripsi</h5>
        </div>

        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-light">
                        <tr>
                            <th width="70">No.</th>
                            <th>ID Data</th>
                            <th>Status</th>
                            <th>Tanggal Dibuat</th>
                            <th width="250">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($data as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>

                                <td>
                                    <strong>Data #{{ $item->id }}</strong>
                                </td>

                                <td>
                                    <span class="badge bg-success">
                                        Terenkripsi
                                    </span>
                                </td>

                                <td>
                                    {{ $item->created_at?->format('d-m-Y H:i') ?? '-' }}
                                </td>

                                <td>
                                    <a href="{{ route('data.show', $item->id) }}"
                                       class="btn btn-info btn-sm">
                                        Lihat Ciphertext
                                    </a>

                                    @if($isAdmin)
                                        <a href="{{ route('data.edit', $item->id) }}"
                                           class="btn btn-warning btn-sm">
                                            Edit
                                        </a>

                                        <form action="{{ route('data.destroy', $item->id) }}"
                                              method="POST"
                                              class="d-inline"
                                              onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="btn btn-danger btn-sm">
                                                Hapus
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    Belum ada data terenkripsi.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

        </div>
    </div>

</div>
@endsection
