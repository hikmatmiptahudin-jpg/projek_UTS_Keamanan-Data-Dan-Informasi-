
@extends('layouts.admin')

@section('title', '')

@section('content')

<div id="ciphertext-view">
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <pre id="ciphertext-data" class="mb-0" style="white-space: pre-wrap; overflow-wrap: anywhere;"></pre>
        </div>
    </div>
</div>

<div id="dashboard-content" style="display: none;">

    <div class="row">

        <div class="col-md-4 mb-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="text-muted" id="total-label"></h6>
                    <h2 class="text-primary" id="total-value"></h2>
                    <p class="mb-0" id="total-description"></p>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="text-muted" id="symmetric-label"></h6>
                    <h2 class="text-success" id="symmetric-value"></h2>
                    <p class="mb-0" id="symmetric-description"></p>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="text-muted" id="asymmetric-label"></h6>
                    <h2 class="text-danger" id="asymmetric-value"></h2>
                    <p class="mb-0" id="asymmetric-description"></p>
                </div>
            </div>
        </div>

    </div>

    <div class="card shadow-sm border-0 mt-3">
        <div class="card-body">

            <h5 id="welcome-title"></h5>

            <p id="welcome-description"></p>

            <p id="admin-description"></p>

            <a href="{{ route('data.index') }}"
               class="btn btn-primary"
               id="manage-button">
            </a>

        </div>
    </div>

</div>

<script>
    // Paket dashboard terenkripsi dari server
    const encryptedDashboard = @json($encryptedDashboard);

    // Status autentikasi dari session Laravel
    const isAdminSession = @json($isAdmin);

    // Mengubah Base64 menjadi Uint8Array
    function base64ToBytes(base64) {

        const binary = atob(base64);
        const bytes = new Uint8Array(binary.length);

        for (let i = 0; i < binary.length; i++) {
            bytes[i] = binary.charCodeAt(i);
        }

        return bytes;
    }

    // Mengambil private key dari IndexedDB
    function getPrivateKey() {

        return new Promise((resolve, reject) => {

            const request = indexedDB.open(
                'secureadmin_keys',
                1
            );

            request.onsuccess = function () {

                const db = request.result;

                if (!db.objectStoreNames.contains('keys')) {
                    db.close();
                    resolve(null);
                    return;
                }

                const transaction = db.transaction(
                    'keys',
                    'readonly'
                );

                const getRequest = transaction
                    .objectStore('keys')
                    .get('rsa_decryption_key');

                getRequest.onsuccess = function () {

                    const key = getRequest.result || null;

                    db.close();

                    resolve(key);
                };

                getRequest.onerror = function () {

                    db.close();

                    reject(getRequest.error);
                };
            };

            request.onerror = function () {
                reject(request.error);
            };
        });
    }

    // Mendekripsi dashboard menggunakan RSA dan AES
    async function decryptDashboard() {

        const privateKey = await getPrivateKey();

        if (!privateKey) {
            throw new Error('Private key tidak tersedia.');
        }

        // Mengambil komponen ciphertext
        const encryptedData = base64ToBytes(
            encryptedDashboard.encrypted_data
        );

        const encryptedKey = base64ToBytes(
            encryptedDashboard.encrypted_key
        );

        const iv = base64ToBytes(
            encryptedDashboard.iv
        );

        const authTag = base64ToBytes(
            encryptedDashboard.auth_tag
        );

        // Membuka kunci AES menggunakan RSA-OAEP
        const aesKeyBuffer = await crypto.subtle.decrypt(
            {
                name: 'RSA-OAEP'
            },
            privateKey,
            encryptedKey
        );

        // Mengimpor kunci AES
        const aesKey = await crypto.subtle.importKey(
            'raw',
            aesKeyBuffer,
            {
                name: 'AES-GCM'
            },
            false,
            ['decrypt']
        );

        // Menggabungkan ciphertext dan authentication tag
        const combinedCiphertext = new Uint8Array(
            encryptedData.length + authTag.length
        );

        combinedCiphertext.set(encryptedData, 0);

        combinedCiphertext.set(
            authTag,
            encryptedData.length
        );

        // Dekripsi AES-256-GCM
        const decryptedBuffer = await crypto.subtle.decrypt(
            {
                name: 'AES-GCM',
                iv: iv,
                tagLength: 128
            },
            aesKey,
            combinedCiphertext
        );

        // Mengubah hasil dekripsi menjadi JSON
        const plainText = new TextDecoder().decode(
            decryptedBuffer
        );

        return JSON.parse(plainText);
    }

    // Menampilkan sidebar setelah berhasil didekripsi
    function displaySidebar(data) {

        document.getElementById('sidebar-brand').textContent =
            data.sidebar_brand;

        document.getElementById('sidebar-dashboard').textContent =
            data.nav_dashboard;

        document.getElementById('sidebar-data').textContent =
            data.nav_data;

        document.getElementById('sidebar-access-status').textContent =
            data.access_status;

        if (isAdminSession) {

            document.getElementById('sidebar-add').textContent =
                data.nav_add;

            document.getElementById('sidebar-encryption').textContent =
                data.nav_encryption;

            document.getElementById('sidebar-history').textContent =
                data.nav_history;

            document.getElementById('sidebar-add').style.display =
                'block';

            document.getElementById('sidebar-encryption').style.display =
                'block';

            document.getElementById('sidebar-history').style.display =
                'block';

            document.getElementById('sidebar-admin-status').textContent =
                data.admin_status;

            document.getElementById('sidebar-admin-status').style.display =
                'inline-block';

            document.getElementById('sidebar-logout').textContent =
                data.logout;

            document.getElementById('sidebar-logout-form').style.display =
                'block';

        } else {

            document.getElementById('sidebar-ciphertext-status').textContent =
                data.ciphertext_status;

            document.getElementById('sidebar-login').textContent =
                data.login;
        }
    }

    // Menampilkan dashboard setelah berhasil didekripsi
    function displayDashboard(data) {

        // Mengisi judul halaman
        document.title = data.page_title;

        // Mengisi sidebar
        displaySidebar(data);

        // Kartu total data
        document.getElementById('total-label').textContent =
            data.total_label;

        document.getElementById('total-value').textContent =
            data.total_value;

        document.getElementById('total-description').textContent =
            data.total_description;

        // Kartu algoritma simetris
        document.getElementById('symmetric-label').textContent =
            data.symmetric_label;

        document.getElementById('symmetric-value').textContent =
            data.symmetric_value;

        document.getElementById('symmetric-description').textContent =
            data.symmetric_description;

        // Kartu algoritma asimetris
        document.getElementById('asymmetric-label').textContent =
            data.asymmetric_label;

        document.getElementById('asymmetric-value').textContent =
            data.asymmetric_value;

        document.getElementById('asymmetric-description').textContent =
            data.asymmetric_description;

        // Informasi sambutan
        document.getElementById('welcome-title').textContent =
            data.welcome_title;

        document.getElementById('welcome-description').textContent =
            data.welcome_description;

        document.getElementById('admin-description').textContent =
            data.admin_description;

        document.getElementById('manage-button').textContent =
            data.manage_button;

        // Menampilkan dashboard normal
        document.getElementById('ciphertext-view').style.display =
            'none';

        document.getElementById('dashboard-content').style.display =
            'block';
    }

    // Menampilkan ciphertext jika dekripsi gagal
    function displayCiphertext() {

        const cipherText = encryptedDashboard.encrypted_data;

        // Ciphertext pada konten utama
        document.getElementById('ciphertext-data').textContent =
            JSON.stringify(encryptedDashboard, null, 2);

        // Ciphertext pada sidebar
        document.getElementById('sidebar-brand').textContent =
            cipherText;

        document.getElementById('sidebar-dashboard').textContent =
            cipherText;

        document.getElementById('sidebar-data').textContent =
            cipherText;

        document.getElementById('sidebar-access-status').textContent =
            cipherText;

        // Status ciphertext
        const ciphertextStatus = document.getElementById(
            'sidebar-ciphertext-status'
        );

        if (ciphertextStatus) {
            ciphertextStatus.textContent = cipherText;
        }

        // Tombol login
        const loginButton = document.getElementById(
            'sidebar-login'
        );

        if (loginButton) {
            loginButton.textContent = cipherText;
        }

        // Sembunyikan navigasi admin sampai dekripsi berhasil
        [
            'sidebar-add',
            'sidebar-encryption',
            'sidebar-history',
            'sidebar-admin-status'
        ].forEach(function (id) {

            const element = document.getElementById(id);

            if (element) {
                element.style.display = 'none';
            }
        });

        // Jika sesi admin aktif, logout tetap tersedia
        const logoutForm = document.getElementById(
            'sidebar-logout-form'
        );

        if (logoutForm) {

            logoutForm.style.display = 'block';

            document.getElementById('sidebar-logout').textContent =
                cipherText;
        }

        // Menyembunyikan dashboard normal
        document.getElementById('dashboard-content').style.display =
            'none';

        document.getElementById('ciphertext-view').style.display =
            'block';
    }

    // Proses utama dashboard
    async function initializeDashboard() {

        // Kondisi awal: tampilkan ciphertext
        displayCiphertext();

        try {

            // Periksa session admin dari server
            if (!isAdminSession) {
                throw new Error('Sesi administrator tidak aktif.');
            }

            // Dekripsi dashboard
            const dashboardData = await decryptDashboard();

            // Tampilkan hasil dekripsi
            displayDashboard(dashboardData);

        } catch (error) {

            // Jika gagal, tampilkan ciphertext
            displayCiphertext();

            console.log(
                'Dashboard berada dalam mode ciphertext.'
            );
        }
    }

    // Menjalankan fungsi utama
    initializeDashboard();
</script>

@endsection
