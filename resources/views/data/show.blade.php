
@extends('layouts.admin')

@section('title', 'Detail Data Rahasia')

@section('content')
<div class="container-fluid">

    <h3 class="mb-4">Detail Data Rahasia</h3>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white">
            <h5 class="mb-0">Informasi Data #{{ $item->id }}</h5>
        </div>

        <div class="card-body">

            @if($isAdmin)
                <div class="alert alert-success">
                    <strong>Administrator RSA Terverifikasi.</strong>
                    Dekripsi dilakukan langsung di browser menggunakan private key.
                </div>

                <div class="alert alert-info" id="decryptStatus">
                    Menyiapkan proses dekripsi...
                </div>

                <!-- HASIL DEKRIPSI -->
                <div id="decryptedResult" style="display: none;">

                    <h5 class="text-success mb-3">
                        Data Berhasil Didekripsi
                    </h5>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Judul Asli</label>
                        <input
                            type="text"
                            id="plainTitle"
                            class="form-control"
                            readonly
                        >
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Isi Data Asli</label>
                        <textarea
                            id="plainContent"
                            class="form-control"
                            rows="5"
                            readonly
                        ></textarea>
                    </div>

                </div>

            @else
                <div class="alert alert-warning">
                    <strong>Mode Ciphertext.</strong>
                    Anda belum terautentikasi sebagai administrator.
                    Data hanya ditampilkan dalam bentuk terenkripsi.
                </div>
            @endif

            <div class="mb-3">
                <label class="form-label">ID Data</label>
                <input
                    type="text"
                    class="form-control"
                    value="{{ $item->id }}"
                    readonly
                >
            </div>

            <div class="mb-3">
                <label class="form-label">Status</label>
                <input
                    type="text"
                    class="form-control"
                    value="Terenkripsi"
                    readonly
                >
            </div>

            <hr>

            <h5 class="mb-3">Informasi Hasil Enkripsi</h5>

            <div class="mb-3">
                <label class="form-label">Ciphertext (AES-256-GCM)</label>
                <textarea
                    class="form-control"
                    rows="5"
                    readonly
                >{{ $item->encrypted_data }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Encrypted AES Key (RSA-OAEP)</label>
                <textarea
                    class="form-control"
                    rows="4"
                    readonly
                >{{ $item->encrypted_key }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Initialization Vector (IV)</label>
                <input
                    type="text"
                    class="form-control"
                    value="{{ $item->iv }}"
                    readonly
                >
            </div>

            <div class="mb-3">
                <label class="form-label">Authentication Tag</label>
                <input
                    type="text"
                    class="form-control"
                    value="{{ $item->auth_tag }}"
                    readonly
                >
            </div>

            <div class="alert alert-info">
                <strong>Informasi Keamanan:</strong>
                Private key tetap berada di browser. Server hanya menyediakan
                ciphertext, encrypted AES key, IV, dan authentication tag.
            </div>

            <a href="{{ route('data.index') }}" class="btn btn-secondary">
                Kembali
            </a>

        </div>
    </div>

</div>

@if($isAdmin)
<script>
    // Data terenkripsi dari database
    const encryptedData = @json($item->encrypted_data);
    const encryptedKey = @json($item->encrypted_key);
    const initializationVector = @json($item->iv);
    const authenticationTag = @json($item->auth_tag);

    // Elemen halaman
    const decryptStatus = document.getElementById('decryptStatus');
    const decryptedResult = document.getElementById('decryptedResult');

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

            const request = indexedDB.open('secureadmin_keys', 1);

            request.onerror = () => reject(
                new Error('Database kunci browser tidak dapat dibuka.')
            );

            request.onsuccess = () => {

                const db = request.result;

                if (!db.objectStoreNames.contains('keys')) {
                    db.close();
                    reject(new Error('Penyimpanan private key tidak ditemukan.'));
                    return;
                }

                const transaction = db.transaction('keys', 'readonly');

                const getRequest = transaction
                    .objectStore('keys')
                    .get('rsa_decryption_key');

                getRequest.onsuccess = () => {
                    const key = getRequest.result;
                    db.close();

                    if (!key) {
                        reject(new Error(
                            'Private key tidak ditemukan. Silakan login RSA kembali.'
                        ));
                        return;
                    }

                    resolve(key);
                };

                getRequest.onerror = () => {
                    db.close();
                    reject(new Error('Gagal mengambil private key.'));
                };
            };
        });
    }

    // Proses dekripsi
    async function decryptData() {

        try {

            decryptStatus.className = 'alert alert-info';
            decryptStatus.textContent = 'Mengambil private key dari browser...';

            // 1. Mengambil private key
            const privateKey = await getPrivateKey();

            // 2. Membuka AES key menggunakan RSA-OAEP
            decryptStatus.textContent = 'Membuka AES key menggunakan RSA-OAEP...';

            const encryptedAESKey = base64ToBytes(encryptedKey);

            const aesKeyBuffer = await crypto.subtle.decrypt(
                {
                    name: 'RSA-OAEP'
                },
                privateKey,
                encryptedAESKey
            );

            // 3. Import AES key
            const aesKey = await crypto.subtle.importKey(
                'raw',
                aesKeyBuffer,
                {
                    name: 'AES-GCM'
                },
                false,
                ['decrypt']
            );

            // 4. Menggabungkan ciphertext dan authentication tag
            decryptStatus.textContent = 'Mendekripsi data menggunakan AES-256-GCM...';

            const ciphertextBytes = base64ToBytes(encryptedData);
            const tagBytes = base64ToBytes(authenticationTag);

            const combinedData = new Uint8Array(
                ciphertextBytes.length + tagBytes.length
            );

            combinedData.set(ciphertextBytes, 0);
            combinedData.set(tagBytes, ciphertextBytes.length);

            // 5. Dekripsi AES-GCM
            const ivBytes = base64ToBytes(initializationVector);

            const plainBuffer = await crypto.subtle.decrypt(
                {
                    name: 'AES-GCM',
                    iv: ivBytes,
                    tagLength: 128
                },
                aesKey,
                combinedData
            );

            // 6. Mengubah hasil dekripsi menjadi JSON
            const plainText = new TextDecoder().decode(plainBuffer);
            const originalData = JSON.parse(plainText);

            // 7. Menampilkan hasil dekripsi
            document.getElementById('plainTitle').value =
                originalData.judul || '';

            document.getElementById('plainContent').value =
                originalData.isi || '';

            decryptedResult.style.display = 'block';

            decryptStatus.className = 'alert alert-success';
            decryptStatus.textContent =
                'Berhasil! Data berhasil didekripsi di browser.';

        } catch (error) {

            console.error('Kesalahan dekripsi:', error);

            decryptStatus.className = 'alert alert-danger';
            decryptStatus.textContent =
                'Dekripsi gagal: ' + error.message;
        }
    }

    // Jalankan proses dekripsi
    decryptData();
</script>
@endif

@endsection
