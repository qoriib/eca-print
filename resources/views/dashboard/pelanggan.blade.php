@extends('layouts.dashboard')

@section('title', 'Pelanggan Dashboard')
@section('role_name', 'Pelanggan')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-md-4">
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
    <div class="col-md-4">
        <div class="card stat-card bg-info text-white">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="text-uppercase mb-2" style="opacity: 0.8;">Pesanan Aktif</h6>
                        <h2 class="fw-semibold mb-0">{{ $data['pesanan_aktif'] }}</h2>
                    </div>
                    <i class="bi bi-activity fs-1" style="opacity: 0.3;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card bg-success text-white">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="text-uppercase mb-2" style="opacity: 0.8;">Pesanan Selesai</h6>
                        <h2 class="fw-semibold mb-0">{{ $data['pesanan_selesai'] }}</h2>
                    </div>
                    <i class="bi bi-check-circle fs-1" style="opacity: 0.3;"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
        <h5 class="fw-semibold mb-0">Pesanan Terbaru Saya</h5>
        <a href="{{ route('pesanan.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-2"></i>Buat Pesanan Baru</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Kode Pesanan</th>
                        <th>Tanggal Pesan</th>
                        <th>Item</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data['pesanan_terbaru'] as $pesanan)
                    <tr class="text-nowrap">
                        <td class="ps-4 fw-medium text-primary">{{ $pesanan->kode_pesanan }}</td>
                        <td>{{ $pesanan->tanggal_pesan->format('d M Y') }}</td>
                        <td>
                            @foreach($pesanan->detailPesanan as $detail)
                                <div class="small fw-medium">{{ $detail->produk->nama_produk }}</div>
                                <div class="text-muted extra-small">{{ $detail->jumlah }} {{ $detail->produk->satuan }}</div>
                            @endforeach
                        </td>
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
                            <span class="badge {{ $badges[$pesanan->status] ?? 'bg-secondary' }} text-capitalize">
                                {{ str_replace('_', ' ', $pesanan->status) }}
                            </span>
                        </td>
                        <td class="text-end pe-4">
                            <a href="{{ route('pesanan.show', $pesanan) }}" class="btn btn-sm btn-light rounded-circle"><i class="bi bi-eye"></i></a>
                            @if($pesanan->status === 'menunggu_konfirmasi')
                                <a href="{{ route('pembayaran.create', $pesanan) }}" class="btn btn-sm btn-outline-success px-3 ms-2">Bayar</a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="bi bi-cart-x fs-1 mb-2 d-block"></i>
                            Anda belum memiliki pesanan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .extra-small {
        font-size: 0.75rem;
    }
</style>
@endsection
