# Entity Relationship Diagram (ERD) - ECA Print

Berikut adalah daftar Entitas, Atribut, dan Relasi berdasarkan file *migration* pada proyek ECA Print.

## Entitas & Atribut

### 1. `users`
- `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
- `name` VARCHAR(255)
- `email` VARCHAR(255) UNIQUE
- `password` VARCHAR(255)
- `role` ENUM('admin', 'operator', 'pelanggan') DEFAULT 'pelanggan'
- `no_telepon` VARCHAR(20) NULL
- `alamat` TEXT NULL
- `remember_token` VARCHAR(100) NULL
- `created_at` TIMESTAMP NULL
- `updated_at` TIMESTAMP NULL

### 2. `password_reset_tokens`
- `email` VARCHAR(255) PRIMARY KEY
- `token` VARCHAR(255)
- `created_at` TIMESTAMP NULL

### 3. `sessions`
- `id` VARCHAR(255) PRIMARY KEY
- `user_id` BIGINT UNSIGNED NULL INDEX
- `ip_address` VARCHAR(45) NULL
- `user_agent` TEXT NULL
- `payload` LONGTEXT
- `last_activity` INT INDEX

### 4. `kategori_produk`
- `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
- `nama_kategori` VARCHAR(255)
- `deskripsi` TEXT NULL
- `created_at` TIMESTAMP NULL
- `updated_at` TIMESTAMP NULL

### 5. `produk`
- `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
- `kategori_produk_id` BIGINT UNSIGNED (FK)
- `nama_produk` VARCHAR(255)
- `deskripsi` TEXT NULL
- `harga_satuan` DECIMAL(12, 2)
- `satuan` VARCHAR(255) DEFAULT 'pcs'
- `gambar` VARCHAR(255) NULL
- `is_aktif` BOOLEAN DEFAULT 1
- `created_at` TIMESTAMP NULL
- `updated_at` TIMESTAMP NULL

### 6. `pesanan`
- `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
- `kode_pesanan` VARCHAR(255) UNIQUE
- `user_id` BIGINT UNSIGNED (FK)
- `tanggal_pesan` DATE
- `tanggal_deadline` DATE NULL
- `status` ENUM('menunggu_konfirmasi', 'dikonfirmasi', 'dalam_produksi', 'selesai_produksi', 'siap_diambil', 'selesai', 'dibatalkan') DEFAULT 'menunggu_konfirmasi'
- `total_harga` DECIMAL(14, 2) DEFAULT 0.00
- `status_pembayaran` ENUM('belum_bayar', 'dp', 'lunas') DEFAULT 'belum_bayar'
- `catatan_pelanggan` TEXT NULL
- `catatan_admin` TEXT NULL
- `created_at` TIMESTAMP NULL
- `updated_at` TIMESTAMP NULL

### 7. `detail_pesanan`
- `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
- `pesanan_id` BIGINT UNSIGNED (FK)
- `produk_id` BIGINT UNSIGNED (FK)
- `jumlah` INT
- `ukuran` VARCHAR(255) NULL
- `bahan` VARCHAR(255) NULL
- `finishing` VARCHAR(255) NULL
- `harga_satuan` DECIMAL(12, 2)
- `subtotal` DECIMAL(14, 2)
- `file_desain` VARCHAR(255) NULL
- `keterangan` TEXT NULL
- `created_at` TIMESTAMP NULL
- `updated_at` TIMESTAMP NULL

### 8. `produksi`
- `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
- `pesanan_id` BIGINT UNSIGNED (FK)
- `operator_id` BIGINT UNSIGNED NULL (FK)
- `tanggal_mulai` DATE NULL
- `tanggal_selesai` DATE NULL
- `status_produksi` ENUM('antrian', 'proses', 'quality_check', 'selesai') DEFAULT 'antrian'
- `catatan_produksi` TEXT NULL
- `created_at` TIMESTAMP NULL
- `updated_at` TIMESTAMP NULL

### 9. `pembayaran`
- `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
- `pesanan_id` BIGINT UNSIGNED (FK)
- `kode_pembayaran` VARCHAR(255) UNIQUE
- `jumlah_bayar` DECIMAL(14, 2)
- `jenis_pembayaran` ENUM('dp', 'pelunasan', 'full') DEFAULT 'full'
- `metode_pembayaran` ENUM('transfer', 'tunai', 'qris') DEFAULT 'transfer'
- `tanggal_bayar` DATE
- `bukti_pembayaran` VARCHAR(255) NULL
- `status_konfirmasi` ENUM('menunggu', 'dikonfirmasi', 'ditolak') DEFAULT 'menunggu'
- `dikonfirmasi_oleh` BIGINT UNSIGNED NULL (FK)
- `catatan` TEXT NULL
- `created_at` TIMESTAMP NULL
- `updated_at` TIMESTAMP NULL

### 10. `notifikasi`
- `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
- `user_id` BIGINT UNSIGNED (FK)
- `judul` VARCHAR(255)
- `pesan` TEXT
- `tipe` VARCHAR(255) DEFAULT 'info'
- `is_read` BOOLEAN DEFAULT 0
- `created_at` TIMESTAMP NULL
- `updated_at` TIMESTAMP NULL

*(Catatan: Tabel bawaan framework seperti `cache`, `jobs`, `failed_jobs` tidak disertakan di sini karena lebih difokuskan pada tabel yang berhubungan langsung dengan logika bisnis sistem)*

## Relasi

- **`users` (1) - (N) `pesanan`**
  - **Keterangan:** `pesanan.user_id` merujuk ke `users.id` (pengguna sebagai pelanggan).

- **`users` (1) - (N) `produksi`**
  - **Keterangan:** `produksi.operator_id` merujuk ke `users.id` (pengguna sebagai operator yang mengerjakan).

- **`users` (1) - (N) `pembayaran`**
  - **Keterangan:** `pembayaran.dikonfirmasi_oleh` merujuk ke `users.id` (pengguna admin yang mengkonfirmasi).

- **`users` (1) - (N) `notifikasi`**
  - **Keterangan:** `notifikasi.user_id` merujuk ke `users.id` (penerima notifikasi).

- **`kategori_produk` (1) - (N) `produk`**
  - **Keterangan:** `produk.kategori_produk_id` merujuk ke `kategori_produk.id`.

- **`pesanan` (1) - (N) `detail_pesanan`**
  - **Keterangan:** `detail_pesanan.pesanan_id` merujuk ke `pesanan.id`.

- **`produk` (1) - (N) `detail_pesanan`**
  - **Keterangan:** `detail_pesanan.produk_id` merujuk ke `produk.id`.

- **`pesanan` (1) - (N) `produksi`**
  - **Keterangan:** `produksi.pesanan_id` merujuk ke `pesanan.id`.

- **`pesanan` (1) - (N) `pembayaran`**
  - **Keterangan:** `pembayaran.pesanan_id` merujuk ke `pesanan.id`.
