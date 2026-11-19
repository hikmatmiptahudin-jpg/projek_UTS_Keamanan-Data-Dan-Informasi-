# SecureAdmin – Sistem Informasi Keamanan Data

SecureAdmin adalah aplikasi sistem informasi berbasis web yang dikembangkan menggunakan Laravel untuk menerapkan keamanan data melalui kombinasi kriptografi simetris dan asimetris (*Hybrid Encryption*).

Aplikasi ini dirancang untuk melindungi informasi dengan menyimpan data dalam bentuk ciphertext serta menyediakan mekanisme autentikasi Admin menggunakan RSA. Pengguna yang tidak memiliki autentikasi yang valid hanya dapat mengakses informasi dalam bentuk terenkripsi.

Proyek ini dibuat untuk memenuhi tugas mata kuliah **Keamanan Data dan Informasi**.

## 1. Fitur Aplikasi

### Halaman Utama
- Landing page sebagai halaman awal aplikasi.
- Informasi mengenai SecureAdmin dan fitur keamanan yang diterapkan.
- Navigasi menuju halaman login Admin.

### Autentikasi dan Hak Akses
- Autentikasi Admin menggunakan mekanisme kunci RSA.
- Verifikasi autentikasi menggunakan mekanisme challenge dan signature.
- Pengelolaan sesi autentikasi Admin.
- Pembatasan akses berdasarkan hak akses pengguna.
- Pengunjung tanpa autentikasi hanya dapat melihat data dalam bentuk ciphertext.

### Pengelolaan Data
- Admin dapat menambah data.
- Admin dapat melihat data.
- Admin dapat mengubah data.
- Admin dapat menghapus data.
- Data disimpan dalam bentuk ciphertext.
- Admin yang berhasil melewati autentikasi dapat melihat data asli melalui proses dekripsi.

### Keamanan dan Monitoring
- Enkripsi data menggunakan AES-256-GCM.
- Enkripsi kunci AES menggunakan RSA-2048.
- Riwayat aktivitas pengguna.
- Demonstrasi proses enkripsi dan dekripsi.
- Perlindungan akses terhadap halaman dashboard.

## 2. Teknologi yang Digunakan

| Teknologi | Kegunaan |
|---|---|
| Laravel 10 | Framework aplikasi |
| PHP 8.1 | Bahasa pemrograman backend |
| MySQL / MariaDB | Database |
| Bootstrap 5 | Framework antarmuka |
| OpenSSL | Implementasi kriptografi |
| HTML | Struktur halaman |
| CSS | Tampilan aplikasi |
| JavaScript | Interaksi dan proses autentikasi di browser |

## 3. Metode Kriptografi

SecureAdmin menerapkan metode **Hybrid Encryption**, yaitu kombinasi algoritma kriptografi simetris dan asimetris.

### A. Kriptografi Simetris – AES-256-GCM

AES-256-GCM digunakan untuk mengenkripsi data utama.

Karakteristik:
- Menggunakan kunci AES dengan panjang 256-bit.
- Menghasilkan ciphertext.
- Menggunakan Initialization Vector (IV).
- Menghasilkan Authentication Tag untuk memverifikasi integritas dan keaslian data terenkripsi.

### B. Kriptografi Asimetris – RSA-2048

RSA-2048 digunakan untuk melindungi kunci AES melalui pasangan kunci publik dan privat.

Pembagian fungsi:
- Public key digunakan untuk mengenkripsi kunci AES.
- Private key digunakan untuk mendekripsi kunci AES.
- Private key harus dijaga kerahasiaannya.

### C. Mekanisme Autentikasi RSA

Selain untuk mendukung proses enkripsi, RSA digunakan dalam mekanisme autentikasi Admin.

Proses autentikasi meliputi:
1. Admin membuka halaman login.
2. Sistem menghasilkan challenge.
3. Browser menggunakan private key untuk menandatangani challenge.
4. Server memverifikasi signature menggunakan public key.
5. Jika verifikasi berhasil, Admin mendapatkan akses terautentikasi.
6. Jika verifikasi gagal, akses ke informasi asli tidak diberikan dan dashboard tetap menampilkan ciphertext.

Dengan kombinasi tersebut, SecureAdmin menerapkan perlindungan kerahasiaan, integritas, dan pembatasan akses terhadap data.

## 4. Hak Akses Pengguna

| Fitur | Admin Terautentikasi | Pengunjung / Akses Tidak Valid |
|---|---|---|
| Membuka landing page | Ya | Ya |
| Membuka halaman login | Ya | Ya |
| Melihat dashboard | Ya | Ya |
| Melihat ciphertext | Ya | Ya |
| Melihat plaintext | Ya | Tidak |
| Menambah data | Ya | Tidak |
| Mengubah data | Ya | Tidak |
| Menghapus data | Ya | Tidak |
| Mengakses data terdekripsi | Ya | Tidak |
| Melihat riwayat aktivitas | Ya | Tidak |

**Catatan:** Akses tidak valid tidak memperoleh hak untuk melihat data asli. Tampilan ciphertext merupakan mode pembatasan akses, bukan pengganti mekanisme keamanan server.

## 5. Struktur Database

Database aplikasi bernama `secureadmin_db`.

Tabel utama yang digunakan meliputi:

- `users` – menyimpan informasi pengguna.
- `admin_data` – menyimpan data yang dilindungi enkripsi.
- `activity_logs` – menyimpan riwayat aktivitas.
- `landing_pages` – menyimpan informasi halaman utama jika digunakan.
- `migrations` – mencatat migrasi database.
- `password_reset_tokens` – menyimpan token reset password.
- `personal_access_tokens` – menyimpan token akses.
- `failed_jobs` – mencatat pekerjaan yang gagal.

File database SQL tersedia di:

`database/secureadmin_db.sql`

## 6. Instalasi dan Konfigurasi

### A. Persiapan

Pastikan perangkat sudah memiliki:
- PHP 8.1 atau versi yang sesuai dengan kebutuhan proyek.
- Composer.
- Node.js dan NPM.
- MySQL atau MariaDB.
- Git.

### B. Clone Repository

```bash
git clone https://github.com/hikmatmiptahudin-jpg/projek_UTS_Keamanan-Data-Dan-Informasi-.git
```

Masuk ke direktori proyek:

```bash
cd projek_UTS_Keamanan-Data-Dan-Informasi-
```

### C. Instalasi Dependency

```bash
composer install
npm install
```

### D. Konfigurasi Environment

Salin file konfigurasi:

```bash
copy .env.example .env
```

Atur konfigurasi database pada file `.env` sesuai lingkungan masing-masing.

Buat application key:

```bash
php artisan key:generate
```

### E. Konfigurasi Database

Buat database dengan nama:

`secureadmin_db`

Kemudian jalankan migrasi:

```bash
php artisan migrate
```

Jika menggunakan file database SQL yang disediakan, impor file tersebut melalui phpMyAdmin atau MySQL sesuai kondisi database.

### F. Konfigurasi Kunci RSA

Siapkan pasangan kunci RSA dengan panjang 2048-bit sesuai mekanisme konfigurasi aplikasi.

Ketentuan keamanan:
- Public key digunakan sesuai kebutuhan proses enkripsi dan verifikasi.
- Private key harus disimpan secara rahasia.
- Private key tidak boleh diunggah ke GitHub.
- Jangan menyimpan private key di direktori publik.
- Pastikan lokasi penyimpanan kunci sesuai konfigurasi aplikasi.
- Jangan membagikan private key kepada pihak lain.

### G. Menjalankan Aplikasi

Jalankan server Laravel:

```bash
php artisan serve
```

Jika diperlukan, jalankan proses build frontend:

```bash
npm run build
```

Buka aplikasi melalui browser:

`http://127.0.0.1:8000`

Halaman awal aplikasi adalah landing page SecureAdmin. Login Admin dapat diakses melalui halaman login yang tersedia pada landing page.

## 7. Keamanan Aplikasi

Beberapa ketentuan keamanan yang diterapkan:

- File `.env` tidak disertakan dalam repository.
- RSA private key tidak disertakan dalam repository.
- Kunci enkripsi disimpan secara terpisah dari source code.
- File konfigurasi sensitif dikecualikan menggunakan `.gitignore`.
- Data utama disimpan dalam bentuk ciphertext.
- Proses dekripsi dibatasi untuk Admin yang berhasil terautentikasi.
- Middleware digunakan untuk membatasi akses ke halaman tertentu.
- Verifikasi autentikasi dilakukan menggunakan mekanisme RSA.
- Pengunjung yang tidak terautentikasi tidak memperoleh akses terhadap plaintext.

**Penting:** Keamanan aplikasi tetap bergantung pada konfigurasi server, pengelolaan private key, validasi input, pengamanan sesi, serta pengaturan hak akses database.

## 8. Repository GitHub

Repository proyek:

https://github.com/hikmatmiptahudin-jpg/projek_UTS_Keamanan-Data-Dan-Informasi-.git

Branch utama: `main`

---

**SecureAdmin – Proyek Tugas Mata Kuliah Keamanan Data dan Informasi**