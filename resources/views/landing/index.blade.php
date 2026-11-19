
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>SecureAdmin - Sistem Pengelolaan Data Aman</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --navy: #142650;
            --blue: #2563eb;
            --light: #f3f6fc;
            --text: #24324b;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: Arial, sans-serif;
            color: var(--text);
            background: white;
        }

        .navbar {
            background: white;
            padding: 16px 0;
        }

        .navbar-brand {
            font-size: 23px;
            font-weight: 800;
            color: var(--navy);
        }

        .navbar-brand span {
            color: var(--blue);
        }

        .nav-link {
            color: #34415a;
            font-weight: 600;
            margin-left: 15px;
        }

        .nav-link:hover {
            color: var(--blue);
        }

        .btn-login {
            background: var(--blue);
            color: white;
            padding: 10px 22px;
            border-radius: 8px;
            font-weight: 600;
        }

        .btn-login:hover {
            background: #1d4ed8;
            color: white;
        }

        .hero {
            background: linear-gradient(120deg, #101f42, #1e3a70);
            color: white;
            padding: 100px 0;
            overflow: hidden;
        }

        .hero-label {
            display: inline-block;
            background: rgba(255,255,255,.12);
            border: 1px solid rgba(255,255,255,.2);
            padding: 8px 15px;
            border-radius: 30px;
            font-size: 13px;
            margin-bottom: 22px;
        }

        .hero h1 {
            font-size: 48px;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 22px;
        }

        .hero p {
            color: #d5def0;
            font-size: 17px;
            line-height: 1.8;
        }

        .hero-visual {
            background: rgba(255,255,255,.08);
            border: 1px solid rgba(255,255,255,.15);
            border-radius: 24px;
            padding: 35px;
            text-align: center;
        }

        .hero-icon {
            font-size: 125px;
            color: #8db7ff;
        }

        .section-padding {
            padding: 85px 0;
        }

        .section-title {
            color: var(--navy);
            font-size: 34px;
            font-weight: 800;
            margin-bottom: 15px;
        }

        .section-subtitle {
            color: #718096;
            max-width: 650px;
            margin: auto;
            line-height: 1.8;
        }

        .about-box {
            background: var(--light);
            border-radius: 18px;
            padding: 35px;
            height: 100%;
        }

        .feature-card {
            background: white;
            border: 1px solid #e6ebf3;
            border-radius: 16px;
            padding: 30px;
            height: 100%;
            transition: .3s;
        }

        .feature-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 30px rgba(20,38,80,.09);
        }

        .feature-icon {
            width: 60px;
            height: 60px;
            background: #eaf1ff;
            color: var(--blue);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 27px;
            margin-bottom: 20px;
        }

        .feature-card h5 {
            color: var(--navy);
            font-weight: 700;
        }

        .feature-card p {
            color: #718096;
            line-height: 1.7;
            margin-bottom: 0;
        }

        .security-section {
            background: var(--light);
        }

        .security-box {
            background: white;
            border-radius: 16px;
            padding: 25px;
            height: 100%;
            border: 1px solid #e7edf5;
        }

        .security-box h5 {
            color: var(--navy);
            font-weight: 700;
        }

        .security-number {
            font-size: 28px;
            font-weight: 800;
            color: var(--blue);
        }

        .step-number {
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--blue);
            color: white;
            border-radius: 50%;
            font-weight: 800;
            margin-bottom: 18px;
        }

        .step-card {
            padding: 25px;
            border: 1px solid #e6ebf3;
            border-radius: 15px;
            height: 100%;
        }

        .cta {
            background: linear-gradient(120deg, #142650, #2452a0);
            color: white;
            padding: 65px 20px;
            text-align: center;
        }

        .cta h2 {
            font-weight: 800;
        }

        footer {
            background: #0d1934;
            color: #cbd5e1;
            padding: 30px 0;
        }

        footer strong {
            color: white;
        }

        @media (max-width: 768px) {
            .hero {
                padding: 70px 0;
            }

            .hero h1 {
                font-size: 35px;
            }

            .hero-visual {
                margin-top: 35px;
            }

            .section-title {
                font-size: 28px;
            }
        }
    </style>
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg sticky-top shadow-sm">
    <div class="container">

        <a class="navbar-brand" href="#beranda">
            <i class="bi bi-shield-lock-fill"></i>
            Secure<span>Admin</span>
        </a>

        <button class="navbar-toggler" type="button"
            data-bs-toggle="collapse" data-bs-target="#navbarMenu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarMenu">

            <ul class="navbar-nav ms-auto align-items-lg-center">

                <li class="nav-item">
                    <a class="nav-link" href="#beranda">Beranda</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#tentang">Tentang</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#fitur">Fitur</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#keamanan">Keamanan</a>
                </li>

                <li class="nav-item ms-lg-3 mt-3 mt-lg-0">
                    <a href="{{ route('rsa.login') }}" class="btn btn-login">
                        Login Administrator
                        <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </li>

            </ul>
        </div>
    </div>
</nav>


<!-- HERO -->
<section class="hero" id="beranda">
    <div class="container">

        <div class="row align-items-center">

            <div class="col-lg-7">

                <div class="hero-label">
                    <i class="bi bi-shield-check me-2"></i>
                    Sistem Pengelolaan Data Berbasis Kriptografi
                </div>

                <h1>
                    Keamanan Data Dimulai dari
                    <span style="color:#83b5ff;">Kriptografi</span>
                </h1>

                <p>
                    SecureAdmin merupakan sistem pengelolaan data administrator
                    yang menerapkan teknologi kriptografi untuk melindungi
                    informasi melalui proses enkripsi dan autentikasi digital.
                </p>

                <div class="mt-4">

                    <a href="{{ route('rsa.login') }}" class="btn btn-login btn-lg">
                        <i class="bi bi-key-fill me-2"></i>
                        Masuk Administrator
                    </a>

                    <a href="#tentang" class="btn btn-outline-light btn-lg ms-2">
                        Pelajari Lebih Lanjut
                    </a>

                </div>

            </div>

            <div class="col-lg-5">

                <div class="hero-visual">

                    <i class="bi bi-shield-lock-fill hero-icon"></i>

                    <h4 class="fw-bold mt-3">SecureAdmin Protection</h4>

                    <p class="mb-0">
                        Melindungi data dengan kombinasi
                        enkripsi simetris dan asimetris.
                    </p>

                    <div class="row mt-4">

                        <div class="col-6">
                            <h4 class="fw-bold">AES-256</h4>
                            <small>Data Encryption</small>
                        </div>

                        <div class="col-6">
                            <h4 class="fw-bold">RSA-2048</h4>
                            <small>Key Protection</small>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>
</section>


<!-- TENTANG -->
<section class="section-padding" id="tentang">
    <div class="container">

        <div class="text-center mb-5">

            <h2 class="section-title">Tentang SecureAdmin</h2>

            <p class="section-subtitle">
                Platform pengelolaan data yang dirancang dengan
                pendekatan keamanan informasi menggunakan teknologi kriptografi.
            </p>

        </div>

        <div class="row g-4">

            <div class="col-lg-6">

                <div class="about-box">

                    <h4 class="fw-bold mb-3">
                        <i class="bi bi-info-circle-fill text-primary me-2"></i>
                        Apa Itu SecureAdmin?
                    </h4>

                    <p class="text-secondary" style="line-height:1.9;">
                        SecureAdmin adalah aplikasi berbasis web yang digunakan
                        untuk mengelola data administrator dengan menerapkan
                        mekanisme enkripsi dan autentikasi menggunakan kunci RSA.
                    </p>

                    <p class="text-secondary mb-0" style="line-height:1.9;">
                        Sistem ini dirancang untuk membantu menjaga kerahasiaan
                        informasi sehingga data tidak langsung ditampilkan
                        dalam bentuk teks biasa kepada pengguna yang belum
                        terautentikasi.
                    </p>

                </div>

            </div>

            <div class="col-lg-6">

                <div class="about-box">

                    <h4 class="fw-bold mb-3">
                        <i class="bi bi-bullseye text-primary me-2"></i>
                        Tujuan Sistem
                    </h4>

                    <ul class="text-secondary" style="line-height:2.2;">

                        <li>Menjaga kerahasiaan data administrator.</li>

                        <li>Menerapkan enkripsi dan dekripsi data.</li>

                        <li>Menggunakan autentikasi berbasis private key RSA.</li>

                        <li>Membatasi akses terhadap fitur administrator.</li>

                        <li>Memberikan gambaran penerapan kriptografi pada aplikasi web.</li>

                    </ul>

                </div>

            </div>

        </div>

    </div>
</section>


<!-- FITUR -->
<section class="section-padding security-section" id="fitur">
    <div class="container">

        <div class="text-center mb-5">

            <h2 class="section-title">Fitur Utama</h2>

            <p class="section-subtitle">
                Beberapa fitur utama yang mendukung pengelolaan
                dan perlindungan informasi dalam SecureAdmin.
            </p>

        </div>

        <div class="row g-4">

            <div class="col-md-6 col-lg-4">

                <div class="feature-card">

                    <div class="feature-icon">
                        <i class="bi bi-database-lock"></i>
                    </div>

                    <h5>Manajemen Data</h5>

                    <p>
                        Mengelola informasi administrator melalui
                        fitur penyimpanan, pembaruan, dan pengelolaan data.
                    </p>

                </div>

            </div>

            <div class="col-md-6 col-lg-4">

                <div class="feature-card">

                    <div class="feature-icon">
                        <i class="bi bi-lock-fill"></i>
                    </div>

                    <h5>Enkripsi Data</h5>

                    <p>
                        Menggunakan AES-256-GCM untuk menjaga
                        kerahasiaan isi data yang disimpan.
                    </p>

                </div>

            </div>

            <div class="col-md-6 col-lg-4">

                <div class="feature-card">

                    <div class="feature-icon">
                        <i class="bi bi-key-fill"></i>
                    </div>

                    <h5>Autentikasi RSA</h5>

                    <p>
                        Memverifikasi identitas administrator
                        menggunakan mekanisme tanda tangan digital RSA.
                    </p>

                </div>

            </div>

            <div class="col-md-6 col-lg-4">

                <div class="feature-card">

                    <div class="feature-icon">
                        <i class="bi bi-clock-history"></i>
                    </div>

                    <h5>Riwayat Aktivitas</h5>

                    <p>
                        Menyediakan informasi riwayat aktivitas
                        untuk membantu pemantauan kegiatan administrator.
                    </p>

                </div>

            </div>

            <div class="col-md-6 col-lg-4">

                <div class="feature-card">

                    <div class="feature-icon">
                        <i class="bi bi-file-earmark-lock"></i>
                    </div>

                    <h5>Data Ciphertext</h5>

                    <p>
                        Menampilkan informasi dalam bentuk ciphertext
                        ketika akses administrator belum terverifikasi.
                    </p>

                </div>

            </div>

            <div class="col-md-6 col-lg-4">

                <div class="feature-card">

                    <div class="feature-icon">
                        <i class="bi bi-shield-check"></i>
                    </div>

                    <h5>Kontrol Akses</h5>

                    <p>
                        Membatasi penggunaan fitur khusus berdasarkan
                        status autentikasi administrator RSA.
                    </p>

                </div>

            </div>

        </div>

    </div>
</section>


<!-- KEAMANAN -->
<section class="section-padding" id="keamanan">

    <div class="container">

        <div class="text-center mb-5">

            <h2 class="section-title">Teknologi Keamanan</h2>

            <p class="section-subtitle">
                SecureAdmin mengombinasikan algoritma kriptografi
                simetris dan asimetris dalam mekanisme perlindungan data.
            </p>

        </div>

        <div class="row g-4">

            <div class="col-md-4">

                <div class="security-box text-center">

                    <div class="security-number">AES-256-GCM</div>

                    <h5 class="mt-3">Kriptografi Simetris</h5>

                    <p class="text-secondary mb-0">
                        Digunakan untuk mengenkripsi dan mendekripsi
                        isi data dengan kunci simetris.
                    </p>

                </div>

            </div>

            <div class="col-md-4">

                <div class="security-box text-center">

                    <div class="security-number">RSA-2048</div>

                    <h5 class="mt-3">Kriptografi Asimetris</h5>

                    <p class="text-secondary mb-0">
                        Digunakan dalam perlindungan kunci AES
                        dan proses autentikasi administrator.
                    </p>

                </div>

            </div>

            <div class="col-md-4">

                <div class="security-box text-center">

                    <div class="security-number">SHA-256</div>

                    <h5 class="mt-3">Tanda Tangan Digital</h5>

                    <p class="text-secondary mb-0">
                        Digunakan pada proses tanda tangan digital
                        untuk memverifikasi kepemilikan private key.
                    </p>

                </div>

            </div>

        </div>

    </div>

</section>


<!-- ALUR KERJA -->
<section class="section-padding security-section">

    <div class="container">

        <div class="text-center mb-5">

            <h2 class="section-title">Bagaimana Sistem Bekerja?</h2>

            <p class="section-subtitle">
                Proses autentikasi administrator dilakukan melalui
                beberapa tahapan verifikasi.
            </p>

        </div>

        <div class="row g-4">

            <div class="col-md-3">

                <div class="step-card">

                    <div class="step-number">1</div>

                    <h5 class="fw-bold">Pilih Private Key</h5>

                    <p class="text-secondary">
                        Administrator memilih file private key RSA
                        melalui halaman login.
                    </p>

                </div>

            </div>

            <div class="col-md-3">

                <div class="step-card">

                    <div class="step-number">2</div>

                    <h5 class="fw-bold">Challenge</h5>

                    <p class="text-secondary">
                        Server menghasilkan challenge acak
                        untuk proses autentikasi.
                    </p>

                </div>

            </div>

            <div class="col-md-3">

                <div class="step-card">

                    <div class="step-number">3</div>

                    <h5 class="fw-bold">Verifikasi RSA</h5>

                    <p class="text-secondary">
                        Server memverifikasi tanda tangan digital
                        menggunakan public key.
                    </p>

                </div>

            </div>

            <div class="col-md-3">

                <div class="step-number">4</div>

                <h5 class="fw-bold">Akses Dashboard</h5>

                <p class="text-secondary">
                    Jika berhasil, administrator memperoleh akses
                    untuk melihat informasi yang dapat didekripsi.
                </p>

                </div>

            </div>

        </div>

    </div>

</section>


<!-- CTA -->
<section class="cta">

    <div class="container">

        <h2>Mulai Menggunakan SecureAdmin</h2>

        <p class="mt-3 mb-4">
            Masuk sebagai administrator untuk mengakses
            fitur pengelolaan data dan keamanan sistem.
        </p>

        <a href="{{ route('rsa.login') }}" class="btn btn-light btn-lg fw-bold">
            <i class="bi bi-shield-lock-fill me-2"></i>
            Login Administrator
        </a>

    </div>

</section>


<!-- FOOTER -->
<footer>

    <div class="container">

        <div class="row align-items-center">

            <div class="col-md-6">

                <h5 class="text-white fw-bold">
                    <i class="bi bi-shield-lock-fill me-2"></i>
                    SecureAdmin
                </h5>

                <p class="mb-md-0">
                    Sistem Pengelolaan Data Berbasis Kriptografi.
                </p>

            </div>

            <div class="col-md-6 text-md-end mt-3 mt-md-0">

                <small>
                    &copy; {{ date('Y') }} SecureAdmin.
                    All Rights Reserved.
                </small>

            </div>

        </div>

    </div>

</footer>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
