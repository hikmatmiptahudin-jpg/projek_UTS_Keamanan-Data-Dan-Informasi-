
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @if(request()->routeIs('dashboard'))
        <title></title>
    @else
        <title>@yield('title') - SecureAdmin</title>
    @endif

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

    <div class="sidebar">

        @if(request()->routeIs('dashboard'))

            {{-- Teks sidebar dashboard diisi setelah dekripsi --}}

            <h3 id="sidebar-brand"></h3>

            <a href="{{ route('dashboard') }}"
               id="sidebar-dashboard"
               class="active"></a>

            <a href="{{ route('data.index') }}"
               id="sidebar-data"></a>

            @if(session('rsa_admin_authenticated') === true)

                <a href="{{ route('data.create') }}"
                   id="sidebar-add"
                   style="display: none;"></a>

                <a href="{{ route('encryption.index') }}"
                   id="sidebar-encryption"
                   style="display: none;"></a>

                <a href="{{ route('history.index') }}"
                   id="sidebar-history"
                   style="display: none;"></a>

            @endif

            <div class="user-info">

                <p class="mb-2" id="sidebar-access-status"></p>

                @if(session('rsa_admin_authenticated') === true)

                    <span id="sidebar-admin-status"
                          class="badge bg-success mb-3"
                          style="display: none;"></span>

                    <form method="POST"
                          action="{{ route('rsa.logout') }}"
                          id="sidebar-logout-form"
                          style="display: none;">

                        @csrf

                        <button type="submit"
                                class="btn btn-danger w-100"
                                id="sidebar-logout">
                        </button>

                    </form>

                @else

                    <span id="sidebar-ciphertext-status"
                          class="badge bg-warning text-dark mb-3"></span>

                    <a href="{{ route('rsa.login') }}"
                       class="btn btn-light text-dark w-100"
                       id="sidebar-login">
                    </a>

                @endif

            </div>

        @else

            {{-- Sidebar normal untuk halaman selain dashboard --}}

            <h3>SecureAdmin</h3>

            <a href="{{ route('dashboard') }}"
               class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                Dashboard
            </a>

            <a href="{{ route('data.index') }}"
               class="{{ request()->routeIs('data.*') ? 'active' : '' }}">
                Manajemen Data
            </a>

            @if(session('rsa_admin_authenticated') === true)

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

            <div class="user-info">

                <p class="mb-2">Status akses:</p>

                @if(session('rsa_admin_authenticated') === true)

                    <span class="badge bg-success mb-3">
                        Administrator RSA Terverifikasi
                    </span>

                    <form method="POST" action="{{ route('rsa.logout') }}">
                        @csrf

                        <button type="submit" class="btn btn-danger w-100">
                            Logout RSA
                        </button>
                    </form>

                @else

                    <span class="badge bg-warning text-dark mb-3">
                        Mode Ciphertext
                    </span>

                    <a href="{{ route('rsa.login') }}"
                       class="btn btn-light text-dark w-100">
                        Login dengan Kunci RSA
                    </a>

                @endif

            </div>

        @endif

    </div>

    <main class="main-content">

        @if(!request()->routeIs('dashboard'))

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

        @endif

        @yield('content')

    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
    </script>

</body>
</html>
