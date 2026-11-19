# SecureAdmin – Sistem Informasi Keamanan Data

SecureAdmin adalah aplikasi sistem informasi berbasis web menggunakan Laravel yang menerapkan kriptografi simetris dan asimetris untuk melindungi data.

Aplikasi ini dibuat untuk memenuhi tugas mata kuliah Keamanan Data dan Informasi.

## 1. Fitur Aplikasi

- Login dan autentikasi pengguna.
- Hak akses Admin dan User.
- Admin dapat menambah, melihat, mengubah, dan menghapus data.
- Data disimpan dalam bentuk ciphertext.
- Admin dapat melakukan dekripsi untuk melihat data asli.
- User biasa hanya dapat melihat data dalam bentuk ciphertext.
- Riwayat aktivitas pengguna.
- Demonstrasi proses enkripsi dan dekripsi.

## 2. Teknologi yang Digunakan

- Laravel 10
- PHP 8.1
- MySQL / MariaDB
- Bootstrap 5
- OpenSSL
- HTML, CSS, dan JavaScript

## 3. Metode Kriptografi

SecureAdmin menggunakan metode Hybrid Encryption:

**Kriptografi Simetris**
- AES-256-GCM digunakan untuk mengenkripsi data.
- Menghasilkan ciphertext, IV, dan authentication tag.

**Kriptografi Asimetris**
- RSA-2048 digunakan untuk mengenkripsi kunci AES.
- Public key digunakan untuk proses enkripsi kunci.
- Private key digunakan untuk proses dekripsi kunci AES.

Dengan metode tersebut, data dilindungi menggunakan kombinasi algoritma simetris dan asimetris.

## 4. Hak Akses Pengguna

| Fitur | Admin | User |
|---|---|---|
| Login | Ya | Ya |
| Melihat ciphertext | Ya | Ya |
| Melihat plaintext | Ya | Tidak |
| Menambah data | Ya | Tidak |
| Mengubah data | Ya | Tidak |
| Menghapus data | Ya | Tidak |
| Riwayat aktivitas | Ya | Tidak |
| Demonstrasi enkripsi | Ya | Tidak |

## 5. Struktur Database

Database aplikasi bernama `secureadmin_db`, dengan tabel utama:

- users
- admin_data
- activity_logs
- migrations
- password_reset_tokens
- personal_access_tokens
- failed_jobs

File database SQL tersedia di:

`database/secureadmin_db.sql`

## 6. Instalasi

1. Clone repository:

   ```bash
   git clone https://github.com/hikmatmiptahudin-jpg/projek_UTS_Keamanan-Data-Dan-Informasi-.git
   ```

2. Masuk ke folder proyek:

   ```bash
   cd projek_UTS_Keamanan-Data-Dan-Informasi-
   ```

3. Instal dependency:

   ```bash
   composer install
   npm install
   ```

4. Salin file konfigurasi:

   ```bash
   copy .env.example .env
   ```

5. Atur koneksi database pada file `.env`.

6. Buat application key:

   ```bash
   php artisan key:generate
   ```

7. Jalankan migrasi database:

   ```bash
   php artisan migrate
   ```

8. Siapkan pasangan kunci RSA 2048-bit pada lokasi yang digunakan aplikasi, yaitu `storage/app/keys/`. Private key harus disimpan secara rahasia dan tidak boleh diunggah ke GitHub.

9. Jalankan aplikasi:

   ```bash
   php artisan serve
   ```

10. Buka aplikasi melalui:

   `http://127.0.0.1:8000`

## 7. Keamanan

- File `.env` tidak disertakan dalam repository.
- RSA private key tidak disertakan dalam repository.
- Kunci enkripsi disimpan secara terpisah dari source code.
- Hak akses pengguna dibatasi berdasarkan role.

## 8. Repository

GitHub: https://github.com/hikmatmiptahudin-jpg/projek_UTS_Keamanan-Data-Dan-Informasi-.git

---

**Proyek SecureAdmin – Tugas Keamanan Data dan Informasi**