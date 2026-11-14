
@extends('layouts.admin')

@section('title', 'Enkripsi Data')

@section('content')

<div class="container-fluid">

    <div class="mb-4">
        <h2>Enkripsi Data</h2>
        <p class="text-muted">
            Sistem keamanan data menggunakan metode Hybrid Encryption.
        </p>
    </div>

    {{-- Informasi metode enkripsi --}}
    <div class="row g-4">

        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title text-primary">
                        AES-256-GCM
                    </h5>

                    <p>
                        Advanced Encryption Standard (AES) digunakan
                        untuk mengenkripsi isi data rahasia menggunakan
                        kunci simetris sepanjang 256 bit.
                    </p>

                    <ul>
                        <li>Algoritma simetris.</li>
                        <li>Menggunakan kunci 256 bit.</li>
                        <li>Menggunakan mode GCM.</li>
                        <li>Memberikan perlindungan integritas data.</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title text-success">
                        RSA-2048
                    </h5>

                    <p>
                        RSA digunakan untuk mengenkripsi kunci AES
                        menggunakan pasangan kunci publik dan privat.
                    </p>

                    <ul>
                        <li>Algoritma asimetris.</li>
                        <li>Menggunakan ukuran kunci 2048 bit.</li>
                        <li>Public key untuk mengenkripsi kunci AES.</li>
                        <li>Private key untuk mendekripsi kunci AES.</li>
                    </ul>
                </div>
            </div>
        </div>

    </div>

    {{-- Alur proses --}}
    <div class="card shadow-sm mt-4">
        <div class="card-body">

            <h5 class="mb-3">Alur Proses Hybrid Encryption</h5>

            <div class="alert alert-primary">
                <strong>1. Input Data</strong>
                <p class="mb-0">
                    Administrator memasukkan judul dan isi data rahasia.
                </p>
            </div>

            <div class="alert alert-info">
                <strong>2. Enkripsi AES-256-GCM</strong>
                <p class="mb-0">
                    Sistem membuat kunci AES secara acak dan
                    mengenkripsi isi data.
                </p>
            </div>

            <div class="alert alert-warning">
                <strong>3. Enkripsi Kunci dengan RSA-2048</strong>
                <p class="mb-0">
                    Kunci AES dienkripsi menggunakan RSA Public Key.
                </p>
            </div>

            <div class="alert alert-success">
                <strong>4. Penyimpanan Data</strong>
                <p class="mb-0">
                    Sistem menyimpan ciphertext, encrypted key,
                    IV, dan authentication tag ke database.
                </p>
            </div>

            <div class="alert alert-secondary">
                <strong>5. Proses Dekripsi</strong>
                <p class="mb-0">
                    RSA Private Key membuka kunci AES, kemudian
                    AES-256-GCM mengembalikan isi data asli.
                </p>
            </div>

        </div>
    </div>

    {{-- Tombol navigasi --}}
    <div class="card shadow-sm mt-4">
        <div class="card-body">

            <h5>Mulai Pengujian</h5>

            <p>
                Silakan tambahkan data rahasia untuk menjalankan
                proses enkripsi atau buka data yang sudah tersimpan
                untuk menjalankan proses dekripsi.
            </p>

            <a href="{{ route('data.create') }}"
               class="btn btn-primary">
                + Tambah Data dan Enkripsi
            </a>

            <a href="{{ route('data.index') }}"
               class="btn btn-outline-secondary">
                Lihat Data Terenkripsi
            </a>

            <a href="{{ route('encryption.demo') }}"
                class="btn btn-success">
                <i class="bi bi-shield-lock"></i>
                Demonstrasi Enkripsi
            </a>

        </div>
    </div>

</div>

@endsection
