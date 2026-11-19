
@extends('layouts.admin')

@section('title', 'Edit Data Rahasia')

@section('content')

<div class="container-fluid">

    <h3 class="mb-2">Edit Data Rahasia</h3>

    <p class="text-muted mb-4">
        Data #{{ $item->id }}
    </p>

    <div class="alert alert-warning">
        <strong>Keamanan:</strong>
        Data didekripsi dan dienkripsi kembali di browser.
        Server hanya menerima data terenkripsi.
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">

            <div id="decryptStatus" class="alert alert-info">
                Menyiapkan proses dekripsi...
            </div>

            <form action="{{ route('data.update', $item->id) }}"
                  method="POST"
                  id="editDataForm">

                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Judul Data</label>

                    <input type="text"
                           id="judul"
                           class="form-control"
                           maxlength="255"
                           required
                           disabled>
                </div>

                <div class="mb-3">
                    <label class="form-label">Isi Data Rahasia</label>

                    <textarea id="isi"
                              class="form-control"
                              rows="7"
                              required
                              disabled></textarea>
                </div>

                <!-- Data terenkripsi yang dikirim ke server -->
                <input type="hidden" name="encrypted_data" id="encrypted_data">
                <input type="hidden" name="encrypted_key" id="encrypted_key">
                <input type="hidden" name="iv" id="iv">
                <input type="hidden" name="auth_tag" id="auth_tag">

                <button type="submit"
                        id="saveButton"
                        class="btn btn-primary"
                        disabled>
                    Simpan dan Enkripsi Ulang
                </button>

                <a href="{{ route('data.index') }}"
                   class="btn btn-secondary">
                    Kembali
                </a>

            </form>

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

    // Public key RSA dari server
    const publicKeyPem = @json($publicKeyPem);

    const form = document.getElementById('editDataForm');
    const decryptStatus = document.getElementById('decryptStatus');
    const judulInput = document.getElementById('judul');
    const isiInput = document.getElementById('isi');
    const saveButton = document.getElementById('saveButton');

    let publicKey = null;
    let readyToSave = false;

    // Mengubah Base64 menjadi byte
    function base64ToBytes(base64) {
        const binary = atob(base64);
        const bytes = new Uint8Array(binary.length);

        for (let i = 0; i < binary.length; i++) {
            bytes[i] = binary.charCodeAt(i);
        }

        return bytes;
    }

    // Mengubah byte menjadi Base64
    function bytesToBase64(bytes) {
        let binary = '';

        for (let i = 0; i < bytes.length; i++) {
            binary += String.fromCharCode(bytes[i]);
        }

        return btoa(binary);
    }

    // Mengambil private key dari IndexedDB
    function getPrivateKey() {
        return new Promise((resolve, reject) => {

            const request = indexedDB.open('secureadmin_keys', 1);

            request.onerror = () => reject(
                new Error('Database private key tidak dapat dibuka.')
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

    // Mengimpor public key RSA
    async function importPublicKey(pem) {

        const pemContents = pem
            .replace('-----BEGIN PUBLIC KEY-----', '')
            .replace('-----END PUBLIC KEY-----', '')
            .replace(/\s/g, '');

        const binary = atob(pemContents);
        const keyBytes = new Uint8Array(binary.length);

        for (let i = 0; i < binary.length; i++) {
            keyBytes[i] = binary.charCodeAt(i);
        }

        return await crypto.subtle.importKey(
            'spki',
            keyBytes,
            {
                name: 'RSA-OAEP',
                hash: 'SHA-1'
            },
            false,
            ['encrypt']
        );
    }

    // Proses dekripsi data lama
    async function decryptExistingData() {

        try {

            decryptStatus.textContent =
                'Mengambil private key dari browser...';

            const privateKey = await getPrivateKey();

            decryptStatus.textContent =
                'Membuka AES key menggunakan RSA-OAEP...';

            const aesKeyBuffer = await crypto.subtle.decrypt(
                {
                    name: 'RSA-OAEP'
                },
                privateKey,
                base64ToBytes(encryptedKey)
            );

            const aesKey = await crypto.subtle.importKey(
                'raw',
                aesKeyBuffer,
                {
                    name: 'AES-GCM'
                },
                false,
                ['decrypt']
            );

            decryptStatus.textContent =
                'Mendekripsi data menggunakan AES-256-GCM...';

            const ciphertextBytes = base64ToBytes(encryptedData);
            const tagBytes = base64ToBytes(authenticationTag);

            const combinedData = new Uint8Array(
                ciphertextBytes.length + tagBytes.length
            );

            combinedData.set(ciphertextBytes, 0);
            combinedData.set(tagBytes, ciphertextBytes.length);

            const plainBuffer = await crypto.subtle.decrypt(
                {
                    name: 'AES-GCM',
                    iv: base64ToBytes(initializationVector),
                    tagLength: 128
                },
                aesKey,
                combinedData
            );

            const plainText = new TextDecoder().decode(plainBuffer);
            const originalData = JSON.parse(plainText);

            if (
                typeof originalData.judul !== 'string' ||
                typeof originalData.isi !== 'string'
            ) {
                throw new Error('Format data hasil dekripsi tidak sesuai.');
            }

            // Mengisi formulir
            judulInput.value = originalData.judul;
            isiInput.value = originalData.isi;

            // Mengaktifkan formulir
            judulInput.disabled = false;
            isiInput.disabled = false;

            // Menyiapkan public key
            publicKey = await importPublicKey(publicKeyPem);

            readyToSave = true;
            saveButton.disabled = false;

            decryptStatus.className = 'alert alert-success';

            decryptStatus.textContent =
                'Data berhasil didekripsi. Silakan ubah data dan simpan.';

        } catch (error) {

            console.error('Kesalahan dekripsi:', error);

            decryptStatus.className = 'alert alert-danger';

            decryptStatus.textContent =
                'Proses gagal: ' + error.message;
        }
    }

    // Enkripsi data baru sebelum dikirim
    form.addEventListener('submit', async function(event) {

        event.preventDefault();

        if (!readyToSave || !publicKey) {
            alert('Proses dekripsi belum selesai.');
            return;
        }

        if (!form.reportValidity()) {
            return;
        }

        try {

            saveButton.disabled = true;

            decryptStatus.className = 'alert alert-info';
            decryptStatus.textContent =
                'Mengenkripsi data di browser...';

            // 1. Menggabungkan judul dan isi menjadi JSON
            const plainText = JSON.stringify({
                judul: judulInput.value,
                isi: isiInput.value
            });

            const plainBytes = new TextEncoder().encode(plainText);

            // 2. Membuat AES-256 key baru
            const aesKey = await crypto.subtle.generateKey(
                {
                    name: 'AES-GCM',
                    length: 256
                },
                true,
                ['encrypt']
            );

            // 3. Membuat IV acak 12 byte
            const ivBytes = crypto.getRandomValues(
                new Uint8Array(12)
            );

            // 4. Enkripsi menggunakan AES-GCM
            const encryptedBuffer = await crypto.subtle.encrypt(
                {
                    name: 'AES-GCM',
                    iv: ivBytes,
                    tagLength: 128
                },
                aesKey,
                plainBytes
            );

            // 5. Memisahkan ciphertext dan authentication tag
            const encryptedBytes = new Uint8Array(encryptedBuffer);

            const ciphertext = encryptedBytes.slice(
                0,
                encryptedBytes.length - 16
            );

            const authTag = encryptedBytes.slice(
                encryptedBytes.length - 16
            );

            // 6. Mengekspor AES key
            const rawAesKey = await crypto.subtle.exportKey(
                'raw',
                aesKey
            );

            // 7. Membungkus AES key menggunakan RSA-OAEP
            const wrappedKey = await crypto.subtle.encrypt(
                {
                    name: 'RSA-OAEP'
                },
                publicKey,
                rawAesKey
            );

            // 8. Menyiapkan ciphertext untuk dikirim
            document.getElementById('encrypted_data').value =
                bytesToBase64(ciphertext);

            document.getElementById('encrypted_key').value =
                bytesToBase64(new Uint8Array(wrappedKey));

            document.getElementById('iv').value =
                bytesToBase64(ivBytes);

            document.getElementById('auth_tag').value =
                bytesToBase64(authTag);

            // 9. Mengirim formulir tanpa plaintext
            decryptStatus.className = 'alert alert-success';
            decryptStatus.textContent =
                'Enkripsi berhasil. Mengirim data terenkripsi ke server...';

            // Field plaintext tidak dikirim
            judulInput.disabled = true;
            isiInput.disabled = true;

            form.submit();

        } catch (error) {

            console.error('Kesalahan enkripsi:', error);

            decryptStatus.className = 'alert alert-danger';
            decryptStatus.textContent =
                'Enkripsi gagal: ' + error.message;

            saveButton.disabled = false;

            judulInput.disabled = false;
            isiInput.disabled = false;
        }
    });

    // Menjalankan proses dekripsi
    decryptExistingData();
</script>
@endif

@endsection
