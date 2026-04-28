# Use Case Diagram - ECA Print

Berikut adalah daftar Use Case, Actor, serta Relasinya (*Includes* dan *Extends*) untuk sistem ECA Print, yang disusun berdasarkan fungsionalitas dari skema *database*.

## Daftar Actors
1. **Pelanggan**: Pengguna yang memesan jasa cetak, melakukan pembayaran, dan memantau status pesanannya.
2. **Admin**: Pengelola sistem yang bertugas mengelola master data (kategori, produk), mengkonfirmasi pesanan, menugaskan operator, dan memverifikasi pembayaran.
3. **Operator**: Pegawai produksi yang bertugas mengerjakan pesanan cetak dan memperbarui status produksinya.

---

## Daftar Use Cases dan Relasi

### Actor: Pelanggan
- **UC-01: Mengelola Akun**
  - *Includes:* Melakukan Login
  - *Includes:* Melakukan Registrasi (Daftar Akun)
- **UC-02: Melihat Katalog Produk**
- **UC-03: Membuat Pesanan Cetak**
  - *Includes:* Mengisi Detail Pesanan (Produk, Jumlah, Ukuran, Bahan, Finishing)
  - *Includes:* Mengunggah File Desain
  - *Extends:* Menambahkan Catatan Pelanggan (opsional)
- **UC-04: Melakukan Pembayaran**
  - *Includes:* Memilih Metode Pembayaran (Transfer, Tunai, QRIS)
  - *Includes:* Mengunggah Bukti Pembayaran
- **UC-05: Melacak Status Pesanan & Produksi**
- **UC-06: Melihat Notifikasi**

### Actor: Admin
- **UC-07: Login ke Sistem Backoffice**
- **UC-08: Mengelola Data Master**
  - *Includes:* Mengelola Kategori Produk
  - *Includes:* Mengelola Produk (Tambah, Ubah, Nonaktifkan)
- **UC-09: Mengelola Pesanan Masuk**
  - *Includes:* Melihat Detail Pesanan Pelanggan
  - *Includes:* Mengubah Status Pesanan (Dikonfirmasi, Siap Diambil, Selesai)
  - *Extends:* Membatalkan Pesanan (jika terdapat kendala)
  - *Extends:* Menambahkan Catatan Admin (opsional)
- **UC-10: Memverifikasi Pembayaran**
  - *Includes:* Mengecek Bukti Pembayaran Pelanggan
  - *Extends:* Menolak Konfirmasi Pembayaran (jika bukti tidak valid)
- **UC-11: Mengelola Penugasan Produksi**
  - *Includes:* Menugaskan Operator ke suatu Pesanan

### Actor: Operator
- **UC-12: Login ke Sistem Produksi**
- **UC-13: Memantau Antrian Produksi**
- **UC-14: Memperbarui Status Produksi**
  - *Includes:* Mengubah Status Pengerjaan (Proses, *Quality Check*, Selesai)
  - *Extends:* Menambahkan Catatan Produksi (opsional, misal jika ada kendala teknis atau mesin rusak)

---

## Penjelasan Relasi
- **<< includes >>**: Menandakan bahwa *use case* yang dituju merupakan bagian wajib (tidak terpisahkan) agar *use case* utama dapat dijalankan/diselesaikan.
- **<< extends >>**: Menandakan bahwa *use case* yang dituju merupakan perilaku tambahan opsional (hanya dieksekusi jika kondisi atau syarat tertentu terpenuhi) dari *use case* utamanya.
