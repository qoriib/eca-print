@extends('layouts.dashboard')

@section('title', 'Laporan Produksi')
@section('role_name', 'Administrator')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <div>
        <a href="{{ route('laporan.index') }}" class="text-decoration-none small mb-1 d-block">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Pusat Laporan
        </a>
        <h3 class="fs-5 fw-semibold mb-0">Laporan Produksi</h3>
    </div>
    <div class="d-flex gap-2">
        <button onclick="window.print()" class="btn btn-outline-warning">
            <i class="bi bi-printer me-2"></i>Cetak Laporan
        </button>
    </div>
</div>

<!-- Filter Card -->
<div class="card mb-4 border-0 shadow-sm">
    <div class="card-body p-4">
        <form action="{{ route('laporan.produksi') }}" method="GET" class="row g-3 align-items-end">
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
                    <option value="antrian" {{ request('status') == 'antrian' ? 'selected' : '' }}>Antrian</option>
                    <option value="desain" {{ request('status') == 'desain' ? 'selected' : '' }}>Desain</option>
                    <option value="cetak" {{ request('status') == 'cetak' ? 'selected' : '' }}>Cetak</option>
                    <option value="finishing" {{ request('status') == 'finishing' ? 'selected' : '' }}>Finishing</option>
                    <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>
            <div class="col-md-3">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-warning w-100">Filter</button>
                    <a href="{{ route('laporan.produksi') }}" class="btn btn-light border w-100">Reset</a>
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
                        <th class="ps-4">Pesanan</th>
                        <th>Tanggal Mulai</th>
                        <th>Tanggal Selesai</th>
                        <th>Operator</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($laporan as $row)
                    <tr>
                        <td class="ps-4 fw-semibold font-monospace text-primary">{{ $row->pesanan->kode_pesanan }}</td>
                        <td>{{ $row->tanggal_mulai ? $row->tanggal_mulai->format('d/m/Y') : '-' }}</td>
                        <td>{{ $row->tanggal_selesai ? $row->tanggal_selesai->format('d/m/Y') : '-' }}</td>
                        <td>
                            @if($row->operator)
                            <div class="d-flex align-items-center">
                                <div class="bg-light rounded-circle me-2 d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">
                                    <i class="bi bi-person small"></i>
                                </div>
                                <span class="small">{{ $row->operator->name }}</span>
                            </div>
                            @else
                            <span class="text-muted small italic">Belum ditentukan</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $statusBadges = [
                                    'antrian' => 'bg-danger',
                                    'desain' => 'bg-warning text-dark',
                                    'cetak' => 'bg-primary',
                                    'finishing' => 'bg-info',
                                    'selesai' => 'bg-success',
                                ];
                            @endphp
                            <span class="badge {{ $statusBadges[$row->status_produksi] ?? 'bg-secondary' }} px-3 text-capitalize">
                                {{ $row->status_produksi }}
                            </span>
                        </td>
                        <td class="text-end pe-4">
                            <a href="{{ route('produksi.show', $row) }}" class="btn btn-sm btn-light">
                                <i class="bi bi-gear"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">Tidak ada data produksi yang sesuai filter.</td>
                    </tr>
                    @endforelse
                </tbody>
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
        .btn, .filter-card, .sidebar, .navbar, a[href*=" Kembali"] {
            display: none !important;
        }
        .card { border: none !important; box-shadow: none !important; }
        body { background: white !important; }
    }
</style>
@endsection
