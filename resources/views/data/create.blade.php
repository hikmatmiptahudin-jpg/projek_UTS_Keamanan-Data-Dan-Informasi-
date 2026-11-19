
@extends('layouts.admin')

@section('title', 'Tambah Data Rahasia')

@section('content')
<div class="container-fluid">

    <h3 class="mb-2">Tambah Data Rahasia</h3>
    <p class="text-muted mb-4">
        Masukkan data yang akan dienkripsi sebelum disimpan ke database.
    </p>

    <div class="alert alert-info">
        <strong>Metode Keamanan:</strong>
        Isi data dienkripsi menggunakan AES-256-GCM.
        Kunci AES kemudian dilindungi menggunakan RSA-OAEP.
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white">
            <h5 class="mb-0">Formulir Data Rahasia</h5>
        </div>

        <div class="card-body">

            <form action="{{ route('data.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Judul Data</label>

                    <input type="text"
                           name="judul"
                           class="form-control"
                           value="{{ old('judul') }}"
                           placeholder="Masukkan judul data"
                           maxlength="255"
                           required>

                    @error('judul')
                        <div class="text-danger small mt-1">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Isi Data Rahasia</label>

                    <textarea name="isi"
                              class="form-control"
                              rows="7"
                              placeholder="Masukkan isi data rahasia..."
                              required>{{ old('isi') }}</textarea>

                    @error('isi')
                        <div class="text-danger small mt-1">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <hr>

                <button type="submit" class="btn btn-primary">
                    Enkripsi dan Simpan
                </button>

                <a href="{{ route('data.index') }}"
                   class="btn btn-secondary">
                    Kembali
                </a>

            </form>

        </div>
    </div>

</div>
@endsection
