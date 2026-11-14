
@extends('layouts.admin')

@section('title', 'Edit Data')

@section('content')

<div class="card shadow-sm border-0">
    <div class="card-body">

        <h4 class="mb-4">Edit Data Rahasia</h4>

        <form action="{{ route('data.update', $item->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Judul Data</label>

                <input
                    type="text"
                    name="judul"
                    class="form-control"
                    value="{{ old('judul', $item->judul) }}"
                    required
                >

                @error('judul')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Isi Data Baru</label>

                <textarea
                    name="isi"
                    class="form-control"
                    rows="5"
                    placeholder="Kosongkan jika tidak ingin mengubah isi data"
                >{{ old('isi') }}</textarea>

                @error('isi')
                    <div class="text-danger">{{ $message }}</div>
                @enderror

                <small class="text-muted">
                    Isi data akan dienkripsi ulang menggunakan AES-256-GCM
                    jika kamu memasukkan isi baru.
                </small>
            </div>

            <button type="submit" class="btn btn-primary">
                Simpan Perubahan
            </button>

            <a href="{{ route('data.index') }}" class="btn btn-secondary">
                Kembali
            </a>

        </form>

    </div>
</div>

@endsection
