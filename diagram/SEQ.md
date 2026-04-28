# Sequence Diagram - Alur Pemesanan & Produksi (ECA Print)

Berikut adalah representasi *Sequence Diagram* yang dijabarkan dalam bentuk tabel *Markdown*. Tabel ini merepresentasikan urutan waktu, interaksi, serta pertukaran pesan (*message passing*) antar aktor dan komponen sistem dalam alur utama pemesanan cetak hingga barang diterima.

## *Lifelines* (Entitas yang Terlibat)
1. **Pelanggan** (Aktor Manusia)
2. **Sistem UI / Controller** (Antarmuka Aplikasi & Logika *Backend*)
3. **Database** (Penyimpanan Data MySQL/PostgreSQL)
4. **Admin** (Aktor Manusia)
5. **Operator** (Aktor Manusia)

---

## Tabel Sequence Diagram

| No | Pengirim (*Sender*) | Pesan / Aksi (*Message*) | Penerima (*Receiver*) | Keterangan / *Return Value* |
|:--:|:---|:---|:---|:---|
| 1 | **Pelanggan** | `aksesHalamanKatalog()` | **Sistem UI** | Meminta tampilan halaman katalog produk. |
| 2 | **Sistem UI** | `getProdukAktif()` | **Database** | Query menarik data master produk dari tabel `produk`. |
| 3 | **Database** | `dataProduk[]` | **Sistem UI** | Mengembalikan *array* / koleksi data produk. |
| 4 | **Sistem UI** | `tampilkanKatalog(dataProduk)` | **Pelanggan** | Menampilkan antarmuka daftar produk cetak. |
| 5 | **Pelanggan** | `submitPesanan(detail, file_desain)` | **Sistem UI** | Mengirimkan *form* pemesanan dan mengunggah desain. |
| 6 | **Sistem UI** | `insert(pesanan, detail_pesanan)` | **Database** | Menyimpan data dengan status awal `menunggu_konfirmasi`. |
| 7 | **Database** | `boolean true` (*success*) | **Sistem UI** | Konfirmasi baris data berhasil disimpan. |
| 8 | **Sistem UI** | `kirimNotifikasi(pesanan_baru)` | **Admin** | Sistem memunculkan *alert* pesanan baru di dasbor Admin. |
| 9 | **Admin** | `validasiPesanan(id, harga_total)` | **Sistem UI** | Admin mengecek kelayakan desain cetak & menetapkan harga. |
| 10 | **Sistem UI** | `update(status='dikonfirmasi')` | **Database** | Memperbarui kolom status pesanan di database. |
| 11 | **Sistem UI** | `kirimTagihan(id, nominal)` | **Pelanggan** | Mengirim notifikasi agar pelanggan segera melakukan pembayaran. |
| 12 | **Pelanggan** | `uploadBuktiBayar(file_gambar)` | **Sistem UI** | Pelanggan mentransfer dana dan mengirim bukti struk. |
| 13 | **Sistem UI** | `insert(pembayaran)` | **Database** | Simpan data bukti bayar, status konfirmasi `menunggu`. |
| 14 | **Sistem UI** | `kirimNotifikasi(pembayaran_masuk)` | **Admin** | Admin mendapat info bukti pembayaran masuk. |
| 15 | **Admin** | `verifikasiPembayaran(id)` | **Sistem UI** | Admin mencocokkan mutasi bank dengan struk, klik "Valid". |
| 16 | **Sistem UI** | `update(status='dalam_produksi')` | **Database** | Ubah status pesanan menjadi sedang diproduksi. |
| 17 | **Sistem UI** | `insert(produksi, status='antrian')` | **Database** | Menyisipkan penugasan / antrian ke tabel `produksi`. |
| 18 | **Sistem UI** | `tampilkanAntrianProduksi()` | **Operator** | *Refresh* halaman antrian pada perangkat Operator. |
| 19 | **Operator** | `mulaiProduksi(id_produksi)` | **Sistem UI** | Operator menekan tombol aksi mulai kerja. |
| 20 | **Sistem UI** | `update(status_produksi='proses')` | **Database** | Sistem mencatat status dan *timestamp* waktu mulai. |
| 21 | **Operator** | `selesaiProduksi(laporan_QC)` | **Sistem UI** | Mesin cetak selesai, Operator melapor proses *Quality Check*. |
| 22 | **Sistem UI** | `update(status_produksi='selesai')` | **Database** | Memperbarui status akhir tabel `produksi`. |
| 23 | **Sistem UI** | `update(status_pesanan='siap_diambil')`| **Database** | Sistem secara *trigger* otomatis mengubah status pesanan. |
| 24 | **Sistem UI** | `kirimNotifikasi(barang_siap)` | **Pelanggan** | Mengirim email / notifikasi barang siap diambil ke pelanggan. |
| 25 | **Pelanggan** | `ambilBarangFisik()` | **Admin** | Proses fisik tatap muka di *outlet* ECA Print. |
| 26 | **Admin** | `selesaikanTransaksi(id_pesanan)` | **Sistem UI** | Admin menyerahkan barang dan menekan tombol selesai di *Backoffice*. |
| 27 | **Sistem UI** | `update(status='selesai')` | **Database** | Perbaruan status pesanan yang final (*closed*). |
| 28 | **Sistem UI** | `generateKuitansi(PDF)` | **Pelanggan** | Mengembalikan file / halaman *Invoice* cetak untuk disimpan pelanggan. |

---
## Cara Membaca Tabel:
- Alur ini berjalan sekuensial (kronologis berdasarkan waktu) dari baris paling atas (Nomor 1) hingga selesai (Nomor 28).
- Pengirim (**Sender**) menginisiasi panggilan aksi atau sistem (**Message**).
- Penerima (**Receiver**) menerima eksekusi dan mengembalikan informasi atau respons (*Return Value* / Keterangan).
