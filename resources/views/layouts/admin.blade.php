
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title') - SecureAdmin</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <style>
        body {
            background: #f4f6f9;
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .sidebar {
            width: 275px;
            min-height: 100vh;
            background: #192b5c;
            color: white;
            position: fixed;
            top: 0;
            left: 0;
            padding: 20px;
        }

        .sidebar h3 {
            font-weight: bold;
            padding-bottom: 18px;
            border-bottom: 1px solid #526184;
        }

        .sidebar a {
            display: block;
            color: white;
            text-decoration: none;
            padding: 14px 15px;
            margin-top: 8px;
            border-radius: 6px;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background: #30477f;
        }

        .main-content {
            margin-left: 275px;
            padding: 30px;
            min-height: 100vh;
        }

        .user-info {
            border-top: 1px solid #526184;
            margin-top: 30px;
            padding-top: 20px;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                min-height: auto;
                position: relative;
            }

            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>

<body>

    <!-- SIDEBAR -->
    <div class="sidebar">

        <h3>SecureAdmin</h3>

        <!-- MENU UNTUK SEMUA PENGGUNA -->
        <a href="{{ route('dashboard') }}"
           class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
            Dashboard
        </a>

        <a href="{{ route('data.index') }}"
           class="{{ request()->routeIs('data.*') ? 'active' : '' }}">
            Manajemen Data
        </a>

        <!-- MENU KHUSUS ADMIN -->
        @if(Auth::user()->role === 'admin')

            <a href="{{ route('data.create') }}"
               class="{{ request()->routeIs('data.create') ? 'active' : '' }}">
                Tambah Data
            </a>

            <a href="{{ route('encryption.index') }}"
               class="{{ request()->routeIs('encryption.*') ? 'active' : '' }}">
                Enkripsi Data
            </a>

            <a href="{{ route('history.index') }}"
               class="{{ request()->routeIs('history.*') ? 'active' : '' }}">
                Riwayat Aktivitas
            </a>

        @endif

        <!-- PROFIL UNTUK SEMUA PENGGUNA -->
        <a href="{{ route('profile.edit') }}"
           class="{{ request()->routeIs('profile.*') ? 'active' : '' }}">
            Profil
        </a>

        <!-- INFORMASI PENGGUNA -->
        <div class="user-info">

            <p class="mb-1">Login sebagai:</p>

            <strong>{{ Auth::user()->name }}</strong>

            <p class="mt-2">
                <span class="badge bg-{{ Auth::user()->role === 'admin' ? 'success' : 'info' }}">
                    {{ Auth::user()->role === 'admin' ? 'Administrator' : 'Pengguna' }}
                </span>
            </p>

            <!-- LOGOUT -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button type="submit" class="btn btn-danger w-100">
                    Logout
                </button>
            </form>

        </div>

    </div>

    <!-- KONTEN UTAMA -->
    <main class="main-content">

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')

    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
    </script>

</body>
</html>
