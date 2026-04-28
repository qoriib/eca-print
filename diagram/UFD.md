# User Flow Diagram - ECA Print

Berikut adalah representasi *User Flow* (Alur Pengguna) dari antarmuka aplikasi ECA Print, disajikan dalam bentuk daftar hirarkis (*list*) beserta percabangan kondisinya (*branching*). Alur ini dibagi berdasarkan masing-masing peran pengguna (*Role*).

---

## 1. User Flow: Pelanggan (*Customer*)
Alur pelanggan mulai dari melihat produk hingga menyelesaikan pesanan.

- **[Mulai]** Akses Halaman Utama / *Landing Page*
- Memilih Menu **"Katalog Produk"**
- Menekan salah satu produk -> Masuk ke **Halaman Detail Produk**
- Mengisi **Form Detail Pesanan** (Jumlah, Ukuran, Bahan, dll) & Mengunggah File Desain
- Klik tombol **"Buat Pesanan"**
- Sistem menampilkan halaman "Pesanan Berhasil Dibuat" (Status: *Menunggu Konfirmasi*)
- **[Percabangan: Validasi Admin]**
  - ├── **Jika Pesanan Ditolak Admin:** 
  - │   └── Menerima notifikasi penolakan -> Pesanan dibatalkan **[Selesai]**
  - └── **Jika Pesanan Diterima Admin:** 
      └── Menerima notifikasi rincian tagihan -> Lanjut ke **Halaman Pembayaran**
- Di Halaman Pembayaran: Memilih metode pembayaran & mengunggah foto bukti transfer
- Klik tombol **"Konfirmasi Pembayaran"**
- **[Percabangan: Validasi Bukti Bayar]**
  - ├── **Jika Bukti Tidak Valid:** 
  - │   └── Menerima notifikasi gagal -> Diminta kembali ke Halaman Pembayaran untuk unggah ulang.
  - └── **Jika Bukti Valid:** 
      └── Pesanan masuk ke tahap **"Dalam Produksi"**
- Pelanggan memantau status secara berkala di halaman **"Pesanan Saya"**
- Menerima Notifikasi **"Barang Siap Diambil"**
- Pelanggan mendatangi toko fisik untuk mengambil pesanan cetak
- Admin mengonfirmasi penyerahan -> Status berubah menjadi **"Selesai"**
- Pelanggan mengunduh Kuitansi / *Invoice* Digital **[Selesai]**

---

## 2. User Flow: Admin Backoffice
Alur admin dalam memvalidasi pesanan dan pembayaran.

- **[Mulai]** Login -> Masuk ke **Dashboard Admin**
- Mengakses Menu **"Pesanan Masuk"**
- Memilih pesanan berstatus *Menunggu Konfirmasi* -> Masuk ke **Halaman Detail Validasi**
- **[Percabangan: Pengecekan Desain]**
  - ├── **Jika file rusak/tidak layak cetak:** 
  - │   └── Klik "Tolak Pesanan" beserta alasan -> Pesanan Batal **[Selesai]**
  - └── **Jika file valid:** 
      └── Menginput perhitungan total harga -> Klik "Konfirmasi Pesanan"
- Mengakses Menu **"Validasi Pembayaran"**
- Memilih data pembayaran yang masuk -> Mengecek struk dengan mutasi rekening
- **[Percabangan: Pengecekan Rekening]**
  - ├── **Jika dana belum masuk/struk palsu:** 
  - │   └── Klik "Tolak Bukti" -> Pelanggan akan diminta unggah ulang.
  - └── **Jika dana sesuai:** 
      └── Klik "Verifikasi Pembayaran" -> Memilih nama Operator Produksi untuk ditugaskan.
- Mengakses Menu **"Siap Diambil"** (Jika barang sudah selesai dicetak operator)
- Menyerahkan barang ke pelanggan di toko fisik
- Klik tombol **"Selesaikan Transaksi"** -> Pesanan *Closed* **[Selesai]**

---

## 3. User Flow: Operator Produksi
Alur operator mesin/produksi dalam mengerjakan pesanan.

- **[Mulai]** Login -> Masuk ke **Dashboard Operator**
- Mengakses Menu **"Antrian Produksi"**
- Melihat daftar pekerjaan masuk
- Klik tombol **"Mulai Dikerjakan"** pada antrian teratas -> Status berubah menjadi *Proses*
- Melakukan pekerjaan fisik (mencetak, *cutting*, *finishing*, dll)
- Pekerjaan fisik selesai -> Melakukan *Quality Check* (QC)
- **[Percabangan: Hasil QC]**
  - ├── **Jika hasil cetak gagal/cacat:** 
  - │   └── Klik tombol "Catatan Kendala" -> Input kendala (misal mesin error) -> Mengulang proses cetak ulang fisik.
  - └── **Jika hasil cetak sempurna:** 
      └── Klik tombol **"Selesai Produksi"**
- Sistem secara otomatis melempar pesanan ke daftar "Siap Diambil" milik Admin.
- Kembali memantau menu Antrian Produksi **[Selesai]**

---

### Catatan Simbol:
- **[Mulai] / [Selesai]** : Menandakan awalan dan akhiran sebuah alur bagi *user* tersebut.
- **[Percabangan]** : Menandakan titik di mana aksi selanjutnya bergantung pada suatu keputusan bisnis atau validasi sistem. Baris yang bercabang mewakili rute *flow* yang berbeda.
