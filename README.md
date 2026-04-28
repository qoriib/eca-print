# ECA Print

Proyek aplikasi berbasis web yang dikembangkan menggunakan framework Laravel untuk mengelola berbagai kebutuhan operasional dan alur kerja pencetakan.

## 🚀 Fitur Utama
- Pengelolaan pesanan pencetakan
- Manajemen pengguna dan hak akses
- Laporan dan rekapitulasi data
- Antarmuka pengguna yang responsif

## 🛠️ Persyaratan Sistem

Sebelum menjalankan proyek ini, pastikan sistem Anda telah menginstal:
- PHP >= 8.2
- Composer
- Node.js & NPM
- Database (MySQL / PostgreSQL)

## 📦 Cara Instalasi

Ikuti langkah-langkah di bawah ini untuk menyiapkan dan menjalankan proyek di lingkungan lokal Anda:

1. **Clone repositori**
   ```bash
   git clone <url-repositori>
   cd eca-print
   ```

2. **Instal dependensi Backend (PHP)**
   ```bash
   composer install
   ```

3. **Instal dependensi Frontend (JavaScript)**
   ```bash
   npm install
   npm run build
   ```

4. **Konfigurasi Environment**
   Salin file contoh environment ke `.env` lalu sesuaikan konfigurasi kredensial database Anda.
   ```bash
   cp .env.example .env
   ```

5. **Generate Application Key**
   ```bash
   php artisan key:generate
   ```

6. **Migrasi dan Seeder Database**
   Jalankan perintah ini untuk membuat struktur tabel dan mengisi data awal.
   ```bash
   php artisan migrate --seed
   ```

7. **Jalankan Local Server**
   ```bash
   php artisan serve
   ```
   Aplikasi sekarang dapat diakses melalui browser pada `http://localhost:8000`.

## 🤝 Kontribusi

Jika Anda ingin berkontribusi pada proyek ini, silakan buat _pull request_ atau buka _issue_ baru untuk mendiskusikan penambahan fitur atau perbaikan *bug*.

## 📄 Lisensi

Proyek ini bersifat *open-source* dan dilisensikan di bawah [MIT license](https://opensource.org/licenses/MIT).
