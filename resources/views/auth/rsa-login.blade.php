
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Login Admin RSA - SecureAdmin</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f1f4f9;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            width: 100%;
            max-width: 450px;
            border: none;
            border-radius: 15px;
        }

        .login-header {
            background: #192b5c;
            color: white;
            padding: 25px;
            border-radius: 15px 15px 0 0;
            text-align: center;
        }

        .login-body {
            padding: 30px;
        }
    </style>
</head>

<body>

<div class="card login-card shadow">

    <div class="login-header">
        <h3>SecureAdmin</h3>
        <p class="mb-0">Autentikasi Administrator RSA</p>
    </div>

    <div class="login-body">

        <!-- LOGIN ADMINISTRATOR RSA -->
        <div class="alert alert-info">
            Pilih private key RSA milik administrator untuk melakukan autentikasi.
            File private key diproses langsung di browser dan tidak diunggah ke server.
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">
                Private Key Administrator
            </label>

            <input
                type="file"
                id="privateKey"
                class="form-control"
                accept=".pem"
            >

            <small class="text-muted">
                Pilih file private.pem yang baru.
            </small>
        </div>

        <button
            type="button"
            id="loginButton"
            class="btn btn-primary w-100"
        >
            Autentikasi RSA
        </button>

        <div id="status" class="mt-3"></div>

        <!-- PEMISAH LOGIN -->
        <div class="text-center my-4">
            <div class="d-flex align-items-center gap-3">
                <hr class="flex-grow-1">
                <span class="text-muted small">ATAU</span>
                <hr class="flex-grow-1">
            </div>
        </div>

        <!-- LOGIN PENGUNJUNG -->
        <div class="mb-3">

            <label class="form-label fw-bold">
                Masuk sebagai Pengunjung
            </label>

            <input
                type="text"
                id="visitorText"
                class="form-control"
                placeholder="Masukkan teks bebas atau acak..."
                maxlength="255"
            >

            <small class="text-muted">
                Masukkan teks apa saja untuk membuka dashboard dalam mode ciphertext.
            </small>

        </div>

        <button
            type="button"
            id="visitorButton"
            class="btn btn-outline-primary w-100"
        >
            <i class="bi bi-eye"></i>
            Masuk ke Dashboard Ciphertext
        </button>

        <!-- KEMBALI -->
        <div class="text-center mt-4">
            <a href="{{ route('landing') }}" class="text-decoration-none">
                Kembali ke halaman utama
            </a>
        </div>

    </div>
</div>

<script>
    const loginButton = document.getElementById('loginButton');
    const visitorButton = document.getElementById('visitorButton');

    const privateKeyInput = document.getElementById('privateKey');
    const visitorText = document.getElementById('visitorText');

    const statusBox = document.getElementById('status');

    const csrfToken = document.querySelector(
        'meta[name="csrf-token"]'
    ).content;

    const DB_NAME = 'secureadmin_keys';
    const STORE_NAME = 'keys';

    // Menampilkan pesan status
    function showStatus(message, type = 'info') {

        statusBox.innerHTML = '';

        const alert = document.createElement('div');

        alert.className = 'alert alert-' + type;
        alert.textContent = message;

        statusBox.appendChild(alert);
    }

    // Membuka database IndexedDB
    function openKeyDB() {

        return new Promise((resolve, reject) => {

            const request = indexedDB.open(DB_NAME, 1);

            request.onupgradeneeded = function () {

                const db = request.result;

                if (!db.objectStoreNames.contains(STORE_NAME)) {
                    db.createObjectStore(STORE_NAME);
                }
            };

            request.onsuccess = () => resolve(request.result);

            request.onerror = () => reject(request.error);

        });
    }

    // Menyimpan CryptoKey di browser
    async function savePrivateKey(key) {

        const db = await openKeyDB();

        return new Promise((resolve, reject) => {

            const transaction = db.transaction(
                STORE_NAME,
                'readwrite'
            );

            transaction.objectStore(STORE_NAME).put(
                key,
                'rsa_decryption_key'
            );

            transaction.oncomplete = () => {
                db.close();
                resolve();
            };

            transaction.onerror = () => {
                db.close();
                reject(transaction.error);
            };

        });
    }

    // Menghapus CryptoKey lama
    async function deletePrivateKey() {

        const db = await openKeyDB();

        return new Promise((resolve, reject) => {

            const transaction = db.transaction(
                STORE_NAME,
                'readwrite'
            );

            transaction.objectStore(STORE_NAME).delete(
                'rsa_decryption_key'
            );

            transaction.oncomplete = () => {
                db.close();
                resolve();
            };

            transaction.onerror = () => {
                db.close();
                reject(transaction.error);
            };

        });
    }

    // Mengubah PEM PKCS#8 menjadi ArrayBuffer
    function pemToArrayBuffer(pem) {

        pem = pem.replace(/^\uFEFF/, '');

        const header = '-----BEGIN PRIVATE KEY-----';
        const footer = '-----END PRIVATE KEY-----';

        if (
            !pem.includes(header) ||
            !pem.includes(footer)
        ) {
            throw new Error('File bukan private key PKCS#8.');
        }

        const base64 = pem
            .split(header)[1]
            .split(footer)[0]
            .replace(/\s/g, '');

        if (
            !base64 ||
            !/^[A-Za-z0-9+/]+={0,2}$/.test(base64) ||
            base64.length % 4 !== 0
        ) {
            throw new Error('Isi private key tidak valid.');
        }

        let binary;

        try {

            binary = atob(base64);

        } catch (error) {

            throw new Error('File PEM tidak dapat dibaca.');

        }

        const bytes = new Uint8Array(binary.length);

        for (let i = 0; i < binary.length; i++) {
            bytes[i] = binary.charCodeAt(i);
        }

        return bytes.buffer;
    }

    // Mengubah tanda tangan menjadi Base64
    function arrayBufferToBase64(buffer) {

        const bytes = new Uint8Array(buffer);

        let binary = '';

        bytes.forEach(byte => {
            binary += String.fromCharCode(byte);
        });

        return btoa(binary);
    }

    /*
    |--------------------------------------------------------------------------
    | MASUK DASHBOARD MODE CIPHERTEXT
    |--------------------------------------------------------------------------
    */

    async function enterCiphertextMode(
        message = 'Membuka dashboard dalam mode ciphertext...'
    ) {

        // Menghapus kunci browser sebelumnya
        await deletePrivateKey();

        showStatus(message, 'warning');

        // Meminta server membersihkan sesi administrator
        const response = await fetch(
            '{{ route("rsa.ciphertext") }}',
            {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            }
        );

        const result = await response.json();

        if (
            !response.ok ||
            !result.success ||
            !result.redirect
        ) {

            throw new Error(
                result.message ||
                'Gagal membuka dashboard ciphertext.'
            );

        }

        // Berpindah ke dashboard
        window.location.href = result.redirect;
    }

    /*
    |--------------------------------------------------------------------------
    | LOGIN ADMINISTRATOR RSA
    |--------------------------------------------------------------------------
    */

    loginButton.addEventListener('click', async function () {

        loginButton.disabled = true;
        visitorButton.disabled = true;

        try {

            const file = privateKeyInput.files[0];

            // Jika belum memilih file
            if (!file) {

                showStatus(
                    'Silakan pilih file private.pem terlebih dahulu.',
                    'warning'
                );

                loginButton.disabled = false;
                visitorButton.disabled = false;

                return;
            }

            showStatus(
                'Menyiapkan kunci RSA...',
                'info'
            );

            // Menghapus kunci browser sebelumnya
            await deletePrivateKey();

            let signingKey;
            let decryptionKey;

            /*
             * Validasi file private key.
             * Jika file tidak valid, masuk ciphertext.
             */

            try {

                const pem = await file.text();

                const keyData = pemToArrayBuffer(pem);

                // Import untuk tanda tangan digital
                signingKey = await crypto.subtle.importKey(
                    'pkcs8',
                    keyData,
                    {
                        name: 'RSASSA-PKCS1-v1_5',
                        hash: 'SHA-256'
                    },
                    false,
                    ['sign']
                );

                // Import untuk dekripsi AES key
                decryptionKey = await crypto.subtle.importKey(
                    'pkcs8',
                    keyData,
                    {
                        name: 'RSA-OAEP',
                        hash: 'SHA-1'
                    },
                    false,
                    ['decrypt']
                );

            } catch (fileError) {

                await enterCiphertextMode(
                    'File bukan private key RSA yang valid. Membuka dashboard ciphertext...'
                );

                return;
            }

            // Meminta challenge dari server
            showStatus(
                'Meminta challenge dari server...',
                'info'
            );

            const challengeResponse = await fetch(
                '{{ route("rsa.challenge") }}',
                {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                }
            );

            if (!challengeResponse.ok) {

                throw new Error(
                    'Gagal meminta challenge dari server.'
                );

            }

            const challengeData = await challengeResponse.json();

            // Menandatangani challenge
            const message = new TextEncoder().encode(
                challengeData.challenge
            );

            const signature = await crypto.subtle.sign(
                'RSASSA-PKCS1-v1_5',
                signingKey,
                message
            );

            const signatureBase64 = arrayBufferToBase64(
                signature
            );

            // Menyimpan CryptoKey dekripsi di IndexedDB
            await savePrivateKey(decryptionKey);

            showStatus(
                'Memverifikasi tanda tangan RSA...',
                'info'
            );

            // Mengirim tanda tangan ke server
            const verifyResponse = await fetch(
                '{{ route("rsa.verify") }}',
                {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        signature: signatureBase64
                    })
                }
            );

            const result = await verifyResponse.json();

            // Jika terjadi kesalahan HTTP
            if (!verifyResponse.ok) {

                await deletePrivateKey();

                throw new Error(
                    result.message || 'Autentikasi RSA gagal.'
                );

            }

            // Jika private key RSA tidak cocok
            if (
                result.authenticated === false &&
                result.mode === 'ciphertext'
            ) {

                await deletePrivateKey();

                showStatus(
                    'Private key tidak cocok. Dashboard dibuka dalam mode ciphertext.',
                    'warning'
                );

                window.location.href = result.redirect;

                return;
            }

            // Jika autentikasi berhasil
            if (
                result.authenticated === true &&
                result.mode === 'admin'
            ) {

                showStatus(
                    'Autentikasi RSA berhasil. Membuka dashboard administrator...',
                    'success'
                );

                window.location.href = result.redirect;

                return;
            }

            throw new Error(
                result.message ||
                'Respons autentikasi server tidak dikenali.'
            );

        } catch (error) {

            await deletePrivateKey().catch(() => {});

            showStatus(
                error.message ||
                'Terjadi kesalahan saat autentikasi.',
                'danger'
            );

            loginButton.disabled = false;
            visitorButton.disabled = false;

        }

    });

    /*
    |--------------------------------------------------------------------------
    | LOGIN PENGUNJUNG DENGAN TEKS BEBAS
    |--------------------------------------------------------------------------
    */

    visitorButton.addEventListener('click', async function () {

        const text = visitorText.value.trim();

        // Teks wajib diisi
        if (!text) {

            showStatus(
                'Silakan masukkan teks terlebih dahulu.',
                'warning'
            );

            visitorText.focus();

            return;
        }

        // Menonaktifkan tombol selama proses
        visitorButton.disabled = true;
        loginButton.disabled = true;

        try {

            await enterCiphertextMode(
                'Teks diterima. Membuka dashboard dalam mode ciphertext...'
            );

        } catch (error) {

            showStatus(
                error.message ||
                'Gagal membuka dashboard ciphertext.',
                'danger'
            );

            visitorButton.disabled = false;
            loginButton.disabled = false;

        }

    });

</script>

</body>
</html>
