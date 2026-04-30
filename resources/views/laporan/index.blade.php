@extends('layouts.dashboard')

@section('title', 'Pusat Laporan')
@section('role_name', 'Administrator')

@section('content')
<div class="mb-4">
    <h3 class="fs-5 fw-semibold mb-1">Pusat Laporan</h3>
    <p class="text-muted">Pilih jenis laporan yang ingin Anda lihat atau cetak.</p>
</div>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card h-100 border-0 shadow-sm transition-hover">
            <div class="card-body p-4 text-center">
                <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-4" style="width: 70px; height: 70px;">
                    <i class="bi bi-cart-check fs-2"></i>
                </div>
                <h5 class="fw-semibold">Laporan Pesanan</h5>
                <p class="text-muted small mb-4">Pantau statistik pesanan masuk, status, dan riwayat transaksi pelanggan.</p>
                <a href="{{ route('laporan.pesanan') }}" class="btn btn-primary w-100">Buka Laporan</a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card h-100 border-0 shadow-sm transition-hover">
            <div class="card-body p-4 text-center">
                <div class="bg-success-subtle text-success rounded-circle d-flex align-items-center justify-content-center mx-auto mb-4" style="width: 70px; height: 70px;">
                    <i class="bi bi-cash-coin fs-2"></i>
                </div>
                <h5 class="fw-semibold">Laporan Pembayaran</h5>
                <p class="text-muted small mb-4">Audit pemasukan, status pembayaran (DP/Lunas), dan total pendapatan.</p>
                <a href="{{ route('laporan.pembayaran') }}" class="btn btn-success w-100 text-white">Buka Laporan</a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card h-100 border-0 shadow-sm transition-hover">
            <div class="card-body p-4 text-center">
                <div class="bg-warning-subtle text-warning rounded-circle d-flex align-items-center justify-content-center mx-auto mb-4" style="width: 70px; height: 70px;">
                    <i class="bi bi-gear-wide-connected fs-2"></i>
                </div>
                <h5 class="fw-semibold">Laporan Produksi</h5>
                <p class="text-muted small mb-4">Monitoring kinerja produksi, beban kerja operator, dan waktu pengerjaan.</p>
                <a href="{{ route('laporan.produksi') }}" class="btn btn-warning w-100">Buka Laporan</a>
            </div>
        </div>
    </div>
</div>

<style>
    .transition-hover {
        transition: all 0.3s ease;
    }
    .transition-hover:hover {
        transform: translateY(-5px);
    }
</style>
@endsection
