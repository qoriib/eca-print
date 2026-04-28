# Activity Diagram - Alur Pemesanan & Produksi (ECA Print)

Berikut adalah representasi *Activity Diagram* dengan format tabel *Swimlane* yang menggambarkan proses terintegrasi dari pembuatan pesanan, pembayaran, pengerjaan produksi, hingga pengambilan barang pesanan cetak.

| No | Pelanggan | Sistem (Aplikasi) | Admin | Operator |
|:--:|:---|:---|:---|:---|
| 1 | **[Start]** Memilih produk dan mengisi detail pemesanan | | | |
| 2 | Mengunggah file desain cetak | | | |
| 3 | | Menyimpan pesanan (Status: `menunggu_konfirmasi`) & mengirim notifikasi ke Admin | | |
| 4 | | | Mengecek detail pesanan dan kelayakan file desain pelanggan | |
| 5 | | | **[Decision]** Apakah pesanan dan desain dapat dikerjakan? <br><br> - *Ya:* Lanjut ke No. 7 <br> - *Tidak:* Lanjut ke No. 6 | |
| 6 | | Mengubah status pesanan menjadi `dibatalkan` & memberi notifikasi pelanggan | Membatalkan pesanan **[End/Selesai]** | |
| 7 | | Mengubah status pesanan menjadi `dikonfirmasi` & mengirim notifikasi rincian harga ke pelanggan | Mengkonfirmasi pesanan dan menetapkan nominal tagihan / harga akhir | |
| 8 | Melakukan pembayaran (DP/Lunas) dan mengunggah bukti bayar | | | |
| 9 | | Menyimpan data pembayaran (Status: `menunggu`) & mengingatkan Admin | | |
| 10 | | | Mengecek validasi bukti transfer/pembayaran | |
| 11 | | | **[Decision]** Apakah bukti pembayaran valid? <br><br> - *Ya:* Lanjut ke No. 13 <br> - *Tidak:* Lanjut ke No. 12 | |
| 12 | Menerima notifikasi gagal bayar dan harus mengunggah bukti valid (Kembali ke No. 8) | Memperbarui status konfirmasi pembayaran menjadi `ditolak` | Menolak bukti pembayaran | |
| 13 | | Memperbarui status pesanan menjadi `dalam_produksi` | Memverifikasi pembayaran dan menugaskan Operator Produksi pada sistem | |
| 14 | | Mengenerate data antrian pada tabel produksi (Status: `antrian`) | | |
| 15 | | | | Mengecek antrian tugas masuk pada sistem produksi |
| 16 | | Memperbarui status produksi menjadi `proses` | | Memulai pengerjaan cetak & menekan tombol mulai |
| 17 | | | | Menyelesaikan proses cetak dan melakukan *Quality Check (QC)* |
| 18 | | | | **[Decision]** Apakah hasil cetak lulus QC? <br><br> - *Ya:* Lanjut ke No. 19 <br> - *Tidak:* Ulangi pengerjaan (Kembali ke No. 16) |
| 19 | | Secara otomatis memperbarui status pesanan menjadi `siap_diambil` | | Memperbarui status produksi menjadi `selesai` |
| 20 | Menerima notifikasi bahwa pesanan siap diambil | | | |
| 21 | Datang ke lokasi, mengambil pesanan, dan (melunasi sisa tagihan jika sebelumnya DP) | | | |
| 22 | | Memperbarui status pesanan menjadi `selesai` | Menyelesaikan transaksi & menyerahkan barang cetakan | |
| 23 | **[End]** Menerima barang fisik dan kuitansi | | | |

---

## Panduan Membaca Tabel:
- Alur dibaca secara berurutan dari baris nomor 1 (paling atas) ke baris akhir (paling bawah).
- Setiap kolom merepresentasikan **Swimlane** (Aktor atau Sistem) yang menunjukkan siapa penanggung jawab *(PIC)* yang mengeksekusi aksi/langkah tersebut.
- Notasi **[Decision]** menunjukkan adanya logika percabangan di mana proses bergantung pada kondisi tertentu (seperti verifikasi kelayakan desain dan verifikasi pembayaran). Aksi akan diarahkan ke nomor spesifik sesuai dengan keputusannya.
