@extends('layouts.dashboard')

@section('title', 'Admin Dashboard')
@section('role_name', 'Administrator')

@section('content')
    <div class="row g-4 mb-4">
        <div class="col-md-6 col-lg-3">
            <div class="card stat-card bg-primary text-white">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-uppercase mb-2" style="opacity: 0.8;">Total Pesanan</h6>
                            <h2 class="fw-semibold mb-0">{{ $data['total_pesanan'] }}</h2>
                        </div>
                        <i class="bi bi-cart fs-1" style="opacity: 0.3;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card stat-card bg-warning text-white">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-uppercase mb-2" style="opacity: 0.8;">Pesanan Baru</h6>
                            <h2 class="fw-semibold mb-0">{{ $data['pesanan_baru'] }}</h2>
                        </div>
                        <i class="bi bi-plus-circle fs-1" style="opacity: 0.3;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card stat-card bg-info text-white">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-uppercase mb-2" style="opacity: 0.8;">Dalam Produksi</h6>
                            <h2 class="fw-semibold mb-0">{{ $data['pesanan_dalam_produksi'] }}</h2>
                        </div>
                        <i class="bi bi-gear fs-1" style="opacity: 0.3;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card stat-card bg-success text-white">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-uppercase mb-2" style="opacity: 0.8;">Total Pelanggan</h6>
                            <h2 class="fw-semibold mb-0">{{ $data['total_pelanggan'] }}</h2>
                        </div>
                        <i class="bi bi-people fs-1" style="opacity: 0.3;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Pesanan Terbaru -->
        <div class="col-lg-8 mb-4">
            <div class="card overflow-hidden">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h5 class="fw-semibold mb-0">Pesanan Terbaru</h5>
                    <a href="{{ route('pesanan.index') }}" class="btn btn-sm btn-outline-primary">
                        Lihat Semua
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">Kode</th>
                                    <th>Pelanggan</th>
                                    <th>Tanggal</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th class="text-end pe-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data['pesanan_terbaru'] as $pesanan)
                                    <tr class="text-nowrap">
                                        <td class="ps-4 fw-medium text-primary">{{ $pesanan->kode_pesanan }}</td>
                                        <td>{{ $pesanan->user->name }}</td>
                                        <td class="font-monospace">{{ $pesanan->tanggal_pesan->format('d/m/Y') }}</td>
                                        <td class="font-monospace">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</td>
                                        <td>
                                            @php
                                                $badges = [
                                                    'menunggu_konfirmasi' => 'bg-warning',
                                                    'dikonfirmasi' => 'bg-info',
                                                    'dalam_produksi' => 'bg-primary',
                                                    'selesai_produksi' => 'bg-info',
                                                    'siap_diambil' => 'bg-success',
                                                    'selesai' => 'bg-success',
                                                    'dibatalkan' => 'bg-danger'
                                                ];
                                            @endphp
                                            <span
                                                class="badge {{ $badges[$pesanan->status] ?? 'bg-secondary' }} text-capitalize">
                                                {{ str_replace('_', ' ', $pesanan->status) }}
                                            </span>
                                        </td>
                                        <td class="text-end pe-4">
                                            <a href="{{ route('pesanan.show', $pesanan) }}" class="btn btn-sm btn-light rounded-circle">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">Belum ada pesanan masuk</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pembayaran Menunggu -->
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="fw-semibold mb-0">Pembayaran Menunggu</h5>
                </div>
                <div class="card-body p-3">
                    @forelse($data['pembayaran_terbaru'] as $bayar)
                        <div class="d-flex align-items-center mb-3 p-3 bg-light rounded-3 border">
                            <div class="flex-shrink-0">
                                <div class="bg-white p-2 rounded shadow-sm">
                                    <i class="bi bi-wallet2 text-primary fs-4"></i>
                                </div>
                            </div>
                            <div class="ms-3 flex-grow-1">
                                <div class="fw-semibold small">{{ $bayar->pesanan->kode_pesanan }}</div>
                                <div class="text-muted small">Rp {{ number_format($bayar->jumlah_bayar, 0, ',', '.') }}</div>
                                <div class="small text-muted">{{ $bayar->pesanan->user->name }}</div>
                            </div>
                            <a href="{{ route('pembayaran.show', $bayar) }}"
                                class="btn btn-sm btn-primary px-3">Konfirmasi</a>
                        </div>
                    @empty
                        <div class="text-center py-4 text-muted">
                            <i class="bi bi-check-circle fs-1 mb-2 d-block opacity-25"></i>
                            Semua pembayaran sudah dikonfirmasi
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection