@extends('layouts.dashboard')

@section('title', 'Laporan Pesanan')
@section('role_name', 'Administrator')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <div>
        <a href="{{ route('laporan.index') }}" class="text-decoration-none small mb-1 d-block">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Pusat Laporan
        </a>
        <h3 class="fs-5 fw-semibold mb-0">Laporan Pesanan</h3>
    </div>
    <div class="d-flex gap-2">
        <button onclick="window.print()" class="btn btn-outline-primary">
            <i class="bi bi-printer me-2"></i>Cetak Laporan
        </button>
    </div>
</div>

<!-- Filter Card -->
<div class="card mb-4 border-0 shadow-sm">
    <div class="card-body p-4">
        <form action="{{ route('laporan.pesanan') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Tanggal Mulai</label>
                <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Tanggal Selesai</label>
                <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Status</label>
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="menunggu_konfirmasi" {{ request('status') == 'menunggu_konfirmasi' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                    <option value="dikonfirmasi" {{ request('status') == 'dikonfirmasi' ? 'selected' : '' }}>Dikonfirmasi</option>
                    <option value="dalam_produksi" {{ request('status') == 'dalam_produksi' ? 'selected' : '' }}>Dalam Produksi</option>
                    <option value="selesai_produksi" {{ request('status') == 'selesai_produksi' ? 'selected' : '' }}>Selesai Produksi</option>
                    <option value="siap_diambil" {{ request('status') == 'siap_diambil' ? 'selected' : '' }}>Siap Diambil</option>
                    <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="dibatalkan" {{ request('status') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </div>
            <div class="col-md-3">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                    <a href="{{ route('laporan.pesanan') }}" class="btn btn-light border w-100">Reset</a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Table Card -->
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Kode</th>
                        <th>Tanggal</th>
                        <th>Pelanggan</th>
                        <th>Total Harga</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Detail</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($laporan as $row)
                    <tr>
                        <td class="ps-4 fw-semibold font-monospace">{{ $row->kode_pesanan }}</td>
                        <td class="text-nowrap">{{ $row->tanggal_pesan->format('d/m/Y') }}</td>
                        <td>
                            <div class="fw-semibold">{{ $row->nama_pelanggan ?? $row->user->name }}</div>
                            <small class="text-muted">{{ $row->no_hp_pelanggan ?? $row->user->no_telepon }}</small>
                        </td>
                        <td class="font-monospace">Rp {{ number_format($row->total_harga, 0, ',', '.') }}</td>
                        <td>
                            @php
                                $statusBadges = [
                                    'menunggu_konfirmasi' => 'bg-secondary',
                                    'dikonfirmasi' => 'bg-info',
                                    'dalam_produksi' => 'bg-primary',
                                    'selesai_produksi' => 'bg-warning text-dark',
                                    'siap_diambil' => 'bg-success',
                                    'selesai' => 'bg-dark',
                                    'dibatalkan' => 'bg-danger',
                                ];
                            @endphp
                            <span class="badge {{ $statusBadges[$row->status] ?? 'bg-secondary' }} px-3">
                                {{ ucwords(str_replace('_', ' ', $row->status)) }}
                            </span>
                        </td>
                        <td class="text-end pe-4">
                            <a href="{{ route('pesanan.show', $row) }}" class="btn btn-sm btn-light">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">Tidak ada data laporan yang sesuai filter.</td>
                    </tr>
                    @endforelse
                </tbody>
                @if($laporan->count() > 0)
                <tfoot class="bg-light fw-bold">
                    <tr>
                        <td colspan="3" class="text-end ps-4">TOTAL KESELURUHAN:</td>
                        <td colspan="3" class="ps-3 font-monospace text-primary">
                            Rp {{ number_format($laporan->sum('total_harga'), 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
        @if($laporan->hasPages())
        <div class="p-4 border-top">
            {{ $laporan->links() }}
        </div>
        @endif
    </div>
</div>

<style>
    @media print {
        .btn, .card-header, .filter-card, .sidebar, .navbar, .breadcrumb, a[href*=" Kembali"] {
            display: none !important;
        }
        .card {
            border: none !important;
            box-shadow: none !important;
        }
        body {
            background: white !important;
        }
        .content-wrapper {
            margin: 0 !important;
            padding: 0 !important;
        }
    }
</style>
@endsection
