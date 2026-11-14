
@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

<div class="row">

    <!-- TOTAL DATA -->
    <div class="col-md-4 mb-3">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6 class="text-muted">Total Data Tersimpan</h6>
                <h2 class="text-primary">{{ $totalData }}</h2>
                <p class="mb-0">Data terenkripsi</p>
            </div>
        </div>
    </div>

    <!-- AES -->
    <div class="col-md-4 mb-3">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6 class="text-muted">Algoritma Simetris</h6>
                <h2 class="text-success">AES-256-GCM</h2>
                <p class="mb-0">Enkripsi isi data</p>
            </div>
        </div>
    </div>

    <!-- RSA -->
    <div class="col-md-4 mb-3">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6 class="text-muted">Algoritma Asimetris</h6>
                <h2 class="text-danger">RSA-2048</h2>
                <p class="mb-0">Perlindungan kunci AES</p>
            </div>
        </div>
    </div>

</div>

<div class="card shadow-sm border-0 mt-3">
    <div class="card-body">
        <h5>Selamat Datang di SecureAdmin</h5>

        <p>
            Sistem pengelolaan data administrator dengan
            penerapan kriptografi AES-256-GCM dan RSA-2048.
        </p>

        <p>
            Admin dapat mengelola data, melakukan enkripsi,
            dan mendekripsi data yang tersimpan.
        </p>

        <a href="{{ route('data.index') }}" class="btn btn-primary">
            Kelola Data
        </a>
    </div>
</div>

@endsection
