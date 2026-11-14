
@extends('layouts.admin')

@section('title', 'Tambah Data Rahasia')

@section('content')
<div class="container-fluid">
    <h3 class="mb-4">Tambah Data Rahasia</h3>

    <div class="card">
        <div class="card-body">

            <form action="{{ route('data.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Judul Data</label>
                    <input type="text"
                           name="judul"
                           class="form-control"
                           value="{{ old('judul') }}"
                           required>
                    @error('judul')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Isi Data Rahasia</label>
                    <textarea name="isi"
                              class="form-control"
                              rows="6"
                              required>{{ old('isi') }}</textarea>
                    @error('isi')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

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
