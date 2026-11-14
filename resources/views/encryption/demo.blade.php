
@extends('layouts.admin')

@section('title', 'Demonstrasi Enkripsi')

@section('content')

<div class="container-fluid">

    <h2 class="mb-2">Demonstrasi Hybrid Encryption</h2>

    <p class="text-muted">
        Uji proses enkripsi AES-256-GCM dan RSA-2048 secara langsung.
    </p>

    {{-- FORM INPUT --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">

            <h5>Masukkan Data Rahasia</h5>

            <form method="POST" action="{{ route('encryption.demo.process') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Isi Data</label>

                    <textarea
                        name="plain_text"
                        class="form-control"
                        rows="4"
                        placeholder="Masukkan teks rahasia untuk diuji..."
                        required>{{ old('plain_text', $plainText ?? '') }}</textarea>

                    @error('plain_text')
                        <div class="text-danger mt-1">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">
                    Proses Enkripsi dan Dekripsi
                </button>

                <a href="{{ route('encryption.index') }}"
                   class="btn btn-outline-secondary">
                    Kembali
                </a>
            </form>

        </div>
    </div>

    @isset($encrypted)

        {{-- DATA ASLI --}}
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-primary text-white">
                1. Plaintext (Data Asli)
            </div>

            <div class="card-body">
                <pre class="mb-0 text-wrap">{{ $plainText }}</pre>
            </div>
        </div>

        {{-- CIPHERTEXT --}}
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-dark text-white">
                2. Ciphertext AES-256-GCM
            </div>

            <div class="card-body">
                <label class="fw-bold">Encrypted Data</label>
                <textarea class="form-control mb-3" rows="3" readonly>{{ $encrypted['encrypted_data'] }}</textarea>

                <label class="fw-bold">Encrypted AES Key (RSA-2048)</label>
                <textarea class="form-control mb-3" rows="3" readonly>{{ $encrypted['encrypted_key'] }}</textarea>

                <label class="fw-bold">Initialization Vector (IV)</label>
                <textarea class="form-control mb-3" rows="2" readonly>{{ $encrypted['iv'] }}</textarea>

                <label class="fw-bold">Authentication Tag</label>
                <textarea class="form-control" rows="2" readonly>{{ $encrypted['auth_tag'] }}</textarea>
            </div>
        </div>

        {{-- HASIL DEKRIPSI --}}
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-success text-white">
                3. Hasil Dekripsi
            </div>

            <div class="card-body">
                <div class="alert alert-success">
                    <strong>Berhasil!</strong>
                    Data berhasil dienkripsi dan dikembalikan melalui proses dekripsi.
                </div>

                <pre class="text-wrap mb-0">{{ $decrypted }}</pre>

                @if($plainText === $decrypted)
                    <div class="badge bg-success mt-3">
                        Verifikasi berhasil: Data asli dan hasil dekripsi sama.
                    </div>
                @else
                    <div class="badge bg-danger mt-3">
                        Verifikasi gagal.
                    </div>
                @endif
            </div>
        </div>

    @endisset

</div>

@endsection
