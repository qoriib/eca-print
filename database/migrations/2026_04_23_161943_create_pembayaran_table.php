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
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesanan_id')->constrained('pesanan')->onDelete('cascade');
            $table->string('kode_pembayaran')->unique();
            $table->decimal('jumlah_bayar', 14, 2);
            $table->enum('jenis_pembayaran', ['dp', 'pelunasan', 'full'])->default('full');
            $table->enum('metode_pembayaran', ['transfer', 'tunai', 'qris'])->default('transfer');
            $table->date('tanggal_bayar');
            $table->string('bukti_pembayaran')->nullable(); // path file foto bukti
            $table->enum('status_konfirmasi', ['menunggu', 'dikonfirmasi', 'ditolak'])->default('menunggu');
            $table->foreignId('dikonfirmasi_oleh')->nullable()->constrained('users')->onDelete('set null');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
