<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pesanan', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pesanan')->unique(); // e.g. ECA-20240101-001
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict'); // pelanggan
            $table->date('tanggal_pesan');
            $table->date('tanggal_deadline')->nullable();
            $table->enum('status', [
                'menunggu_konfirmasi',
                'dikonfirmasi',
                'dalam_produksi',
                'selesai_produksi',
                'siap_diambil',
                'selesai',
                'dibatalkan'
            ])->default('menunggu_konfirmasi');
            $table->decimal('total_harga', 14, 2)->default(0);
            $table->enum('status_pembayaran', ['belum_bayar', 'dp', 'lunas'])->default('belum_bayar');
            $table->text('catatan_pelanggan')->nullable();
            $table->text('catatan_admin')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanan');
    }
};
