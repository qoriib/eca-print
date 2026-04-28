# Class Diagram - ECA Print (Laravel Models)

Berikut adalah rancangan *Class Diagram* yang merepresentasikan struktur Eloquent Model, atribut (kolom database), dan *method* relasi Eloquent dalam kerangka kerja Laravel untuk proyek ECA Print.

## 1. Class: `User`
- **Tipe:** Model
- **Atribut:**
  - `+ id : bigint`
  - `+ name : string`
  - `+ email : string`
  - `+ password : string`
  - `+ role : enum ('admin', 'operator', 'pelanggan')`
  - `+ no_telepon : string`
  - `+ alamat : text`
  - `+ remember_token : string`
  - `+ created_at : timestamp`
  - `+ updated_at : timestamp`
- **Method (Relasi Eloquent):**
  - `+ pesanan() : HasMany` (Mendapatkan semua pesanan milik pelanggan)
  - `+ produksi() : HasMany` (Mendapatkan semua tugas produksi yang dikerjakan oleh operator)
  - `+ konfirmasiPembayaran() : HasMany` (Mendapatkan semua histori konfirmasi pembayaran yang diverifikasi admin)
  - `+ notifikasi() : HasMany` (Mendapatkan semua notifikasi yang ditujukan untuk user ini)

## 2. Class: `KategoriProduk`
- **Tipe:** Model
- **Atribut:**
  - `+ id : bigint`
  - `+ nama_kategori : string`
  - `+ deskripsi : text`
  - `+ created_at : timestamp`
  - `+ updated_at : timestamp`
- **Method (Relasi Eloquent):**
  - `+ produk() : HasMany` (Mendapatkan semua produk yang berada di bawah kategori ini)

## 3. Class: `Produk`
- **Tipe:** Model
- **Atribut:**
  - `+ id : bigint`
  - `+ kategori_produk_id : bigint`
  - `+ nama_produk : string`
  - `+ deskripsi : text`
  - `+ harga_satuan : decimal`
  - `+ satuan : string`
  - `+ gambar : string`
  - `+ is_aktif : boolean`
  - `+ created_at : timestamp`
  - `+ updated_at : timestamp`
- **Method (Relasi Eloquent):**
  - `+ kategori() : BelongsTo` (Mendapatkan data kategori yang menaungi produk ini)
  - `+ detailPesanan() : HasMany` (Mendapatkan histori pesanan item produk ini)

## 4. Class: `Pesanan`
- **Tipe:** Model
- **Atribut:**
  - `+ id : bigint`
  - `+ kode_pesanan : string`
  - `+ user_id : bigint`
  - `+ tanggal_pesan : date`
  - `+ tanggal_deadline : date`
  - `+ status : enum`
  - `+ total_harga : decimal`
  - `+ status_pembayaran : enum`
  - `+ catatan_pelanggan : text`
  - `+ catatan_admin : text`
  - `+ created_at : timestamp`
  - `+ updated_at : timestamp`
- **Method (Relasi Eloquent):**
  - `+ pelanggan() : BelongsTo` (Mendapatkan data akun pelanggan yang membuat pesanan)
  - `+ detailPesanan() : HasMany` (Mendapatkan detail item produk yang ada di dalam pesanan ini)
  - `+ produksi() : HasMany` (Mendapatkan riwayat progres tahap produksi pesanan ini)
  - `+ pembayaran() : HasMany` (Mendapatkan riwayat pembayaran dari pesanan ini)

## 5. Class: `DetailPesanan`
- **Tipe:** Model
- **Atribut:**
  - `+ id : bigint`
  - `+ pesanan_id : bigint`
  - `+ produk_id : bigint`
  - `+ jumlah : integer`
  - `+ ukuran : string`
  - `+ bahan : string`
  - `+ finishing : string`
  - `+ harga_satuan : decimal`
  - `+ subtotal : decimal`
  - `+ file_desain : string`
  - `+ keterangan : text`
  - `+ created_at : timestamp`
  - `+ updated_at : timestamp`
- **Method (Relasi Eloquent):**
  - `+ pesanan() : BelongsTo` (Mendapatkan data master pesanan terkait)
  - `+ produk() : BelongsTo` (Mendapatkan data master produk yang sedang dipesan)

## 6. Class: `Produksi`
- **Tipe:** Model
- **Atribut:**
  - `+ id : bigint`
  - `+ pesanan_id : bigint`
  - `+ operator_id : bigint`
  - `+ tanggal_mulai : date`
  - `+ tanggal_selesai : date`
  - `+ status_produksi : enum`
  - `+ catatan_produksi : text`
  - `+ created_at : timestamp`
  - `+ updated_at : timestamp`
- **Method (Relasi Eloquent):**
  - `+ pesanan() : BelongsTo` (Mendapatkan data pesanan induk dari proses produksi ini)
  - `+ operator() : BelongsTo` (Mendapatkan data akun operator yang mengerjakan produksi)

## 7. Class: `Pembayaran`
- **Tipe:** Model
- **Atribut:**
  - `+ id : bigint`
  - `+ pesanan_id : bigint`
  - `+ kode_pembayaran : string`
  - `+ jumlah_bayar : decimal`
  - `+ jenis_pembayaran : enum`
  - `+ metode_pembayaran : enum`
  - `+ tanggal_bayar : date`
  - `+ bukti_pembayaran : string`
  - `+ status_konfirmasi : enum`
  - `+ dikonfirmasi_oleh : bigint`
  - `+ catatan : text`
  - `+ created_at : timestamp`
  - `+ updated_at : timestamp`
- **Method (Relasi Eloquent):**
  - `+ pesanan() : BelongsTo` (Mendapatkan data pesanan tagihan)
  - `+ adminKonfirmasi() : BelongsTo` (Mendapatkan data admin yang melakukan verifikasi)

## 8. Class: `Notifikasi`
- **Tipe:** Model
- **Atribut:**
  - `+ id : bigint`
  - `+ user_id : bigint`
  - `+ judul : string`
  - `+ pesan : text`
  - `+ tipe : string`
  - `+ is_read : boolean`
  - `+ created_at : timestamp`
  - `+ updated_at : timestamp`
- **Method (Relasi Eloquent):**
  - `+ user() : BelongsTo` (Mendapatkan data pemilik notifikasi tersebut)

---

## Keterangan Relasi Antar Class

Dalam konteks *Class Diagram*, relasi di atas diterjemahkan ke dalam pola kardinalitas (Multiplicity):

1. **`User` (1) ke `Pesanan` (0..*)**: Hubungan Satu-ke-Banyak (*HasMany*). Seorang pengguna bisa memiliki banyak pesanan.
2. **`User` (1) ke `Produksi` (0..*)**: Hubungan Satu-ke-Banyak (*HasMany*). Seorang operator bisa mengerjakan banyak jadwal produksi.
3. **`User` (1) ke `Pembayaran` (0..*)**: Hubungan Satu-ke-Banyak (*HasMany*). Seorang admin bisa memverifikasi banyak pembayaran.
4. **`User` (1) ke `Notifikasi` (0..*)**: Hubungan Satu-ke-Banyak (*HasMany*).
5. **`KategoriProduk` (1) ke `Produk` (0..*)**: Hubungan Satu-ke-Banyak (*HasMany*). Satu kategori menaungi banyak varian produk.
6. **`Pesanan` (1) ke `DetailPesanan` (1..*)**: Hubungan Komposisi (*Composition* / *HasMany*). Sebuah pesanan mutlak tersusun dari satu atau beberapa detail (item belanja).
7. **`Produk` (1) ke `DetailPesanan` (0..*)**: Hubungan Agregasi (*Aggregation*). Produk digunakan oleh detail pesanan.
8. **`Pesanan` (1) ke `Produksi` (0..*)**: Hubungan Satu-ke-Banyak (*HasMany*). Satu pesanan cetak dapat dibagi-bagi tahap pengerjaan produksinya.
9. **`Pesanan` (1) ke `Pembayaran` (0..*)**: Hubungan Satu-ke-Banyak (*HasMany*). Satu tagihan pesanan bisa dibayar berulang (contoh: *DP* lalu dilanjutkan *Pelunasan*).
