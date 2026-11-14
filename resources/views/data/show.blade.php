
@extends('layouts.admin')

@section('title', 'Detail Data Rahasia')

@section('content')
<div class="container-fluid">
    <h3 class="mb-4">Detail Data Rahasia</h3>

    <div class="card">
        <div class="card-body">

            <div class="mb-3">
                <label class="form-label">Judul</label>
                <input type="text"
                       class="form-control"
                       value="{{ $item->judul }}"
                       readonly>
            </div>

            @if($isAdmin)

                <div class="mb-3">
                    <label class="form-label">Isi Data (Hasil Dekripsi)</label>
                    <textarea class="form-control"
                              rows="6"
                              readonly>{{ $isi }}</textarea>
                </div>

                <div class="alert alert-success">
                    Data berhasil didekripsi menggunakan RSA dan AES-256-GCM.
                </div>

            @else

                <div class="alert alert-info">
                    Anda masuk sebagai User. Data ditampilkan dalam bentuk ciphertext.
                </div>

                <div class="mb-3">
                    <label class="form-label">Ciphertext (AES-256-GCM)</label>
                    <textarea class="form-control"
                              rows="6"
                              readonly>{{ $item->encrypted_data }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Encrypted AES Key (RSA)</label>
                    <textarea class="form-control"
                              rows="3"
                              readonly>{{ $item->encrypted_key }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Initialization Vector (IV)</label>
                    <input type="text"
                           class="form-control"
                           value="{{ $item->iv }}"
                           readonly>
                </div>

                <div class="mb-3">
                    <label class="form-label">Authentication Tag</label>
                    <input type="text"
                           class="form-control"
                           value="{{ $item->auth_tag }}"
                           readonly>
                </div>

            @endif

            <a href="{{ route('data.index') }}"
               class="btn btn-secondary">
                Kembali
            </a>

        </div>
    </div>
</div>
@endsection
