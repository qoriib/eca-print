@extends('layouts.dashboard')

@section('title', 'Laporan Pembayaran')
@section('role_name', 'Administrator')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <div>
        <a href="{{ route('laporan.index') }}" class="text-decoration-none small mb-1 d-block">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Pusat Laporan
        </a>
        <h3 class="fs-5 fw-semibold mb-0">Laporan Pembayaran</h3>
    </div>
    <div class="d-flex gap-2">
        <button onclick="window.print()" class="btn btn-outline-success">
            <i class="bi bi-printer me-2"></i>Cetak Laporan
        </button>
    </div>
</div>

<!-- Filter Card -->
<div class="card mb-4 border-0 shadow-sm">
    <div class="card-body p-4">
        <form action="{{ route('laporan.pembayaran') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Tanggal Mulai</label>
                <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Tanggal Selesai</label>
                <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Metode</label>
                <select name="metode" class="form-select">
                    <option value="">Semua Metode</option>
                    <option value="transfer" {{ request('metode') == 'transfer' ? 'selected' : '' }}>Transfer Bank</option>
                    <option value="tunai" {{ request('metode') == 'tunai' ? 'selected' : '' }}>Tunai / Cash</option>
                </select>
            </div>
            <div class="col-md-3">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success w-100 text-white">Filter</button>
                    <a href="{{ route('laporan.pembayaran') }}" class="btn btn-light border w-100">Reset</a>
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
                        <th class="ps-4">No. Ref</th>
                        <th>Tanggal Bayar</th>
                        <th>Pesanan</th>
                        <th>Pelanggan</th>
                        <th>Metode</th>
                        <th>Jumlah Bayar</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($laporan as $row)
                    <tr>
                        <td class="ps-4 fw-semibold font-monospace">{{ $row->kode_pembayaran }}</td>
                        <td>{{ $row->tanggal_bayar->format('d/m/Y') }}</td>
                        <td class="font-monospace small text-primary">{{ $row->pesanan->kode_pesanan }}</td>
                        <td>{{ $row->pesanan->nama_pelanggan ?? $row->pesanan->user->name }}</td>
                        <td><span class="badge bg-light text-dark border px-3 text-uppercase small">{{ $row->metode_pembayaran }}</span></td>
                        <td class="fw-bold font-monospace">Rp {{ number_format($row->jumlah_bayar, 0, ',', '.') }}</td>
                        <td class="text-end pe-4">
                            <a href="{{ route('pembayaran.show', $row) }}" class="btn btn-sm btn-light">
                                <i class="bi bi-receipt"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">Tidak ada data pembayaran yang sesuai filter.</td>
                    </tr>
                    @endforelse
                </tbody>
                @if($laporan->count() > 0)
                <tfoot class="bg-light fw-bold">
                    <tr>
                        <td colspan="5" class="text-end ps-4">TOTAL PENDAPATAN:</td>
                        <td colspan="2" class="ps-3 font-monospace text-success">
                            Rp {{ number_format($laporan->sum('jumlah_bayar'), 0, ',', '.') }}
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
        .btn, .filter-card, .sidebar, .navbar, a[href*=" Kembali"] {
            display: none !important;
        }
        .card { border: none !important; box-shadow: none !important; }
        body { background: white !important; }
    }
</style>
@endsection
