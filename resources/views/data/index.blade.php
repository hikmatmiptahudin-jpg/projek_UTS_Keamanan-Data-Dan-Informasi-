
@extends('layouts.admin')

@section('title', 'Data Rahasia')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between mb-4">
        <h3>Data Rahasia</h3>

        @if($isAdmin)
            <a href="{{ route('data.create') }}" class="btn btn-primary">
                + Tambah Data
            </a>
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="alert alert-info">
        @if($isAdmin)
            Anda masuk sebagai Admin. Anda dapat mengelola dan mendekripsi data.
        @else
            Anda masuk sebagai User. Anda dapat melihat seluruh data ciphertext.
        @endif
    </div>

    <div class="card">
        <div class="card-body">

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Judul</th>
                        <th>Pembuat</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($data as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->judul }}</td>
                        <td>{{ $item->user->name ?? 'Tidak diketahui' }}</td>

                        <td>
                            <span class="badge bg-success">
                                Terenkripsi
                            </span>
                        </td>

                        <td>
                            <a href="{{ route('data.show', $item->id) }}"
                               class="btn btn-info btn-sm">
                                Lihat
                            </a>

                            @if($isAdmin)
                                <a href="{{ route('data.edit', $item->id) }}"
                                   class="btn btn-warning btn-sm">
                                    Edit
                                </a>

                                <form action="{{ route('data.destroy', $item->id) }}"
                                      method="POST"
                                      class="d-inline">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="btn btn-danger btn-sm"
                                            onclick="return confirm('Hapus data ini?')">
                                        Hapus
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>

                    @empty
                    <tr>
                        <td colspan="5" class="text-center">
                            Belum ada data.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
    </div>
</div>
@endsection
