@extends('layouts.dashboard')

@section('title', 'Riwayat Pembayaran')
@section('role_name', Auth::user()->role === 'admin' ? 'Administrator' : 'Pelanggan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold">Daftar Pembayaran</h3>
    <div class="d-flex gap-2">
        <form action="{{ route('pembayaran.index') }}" method="GET" class="d-flex gap-2">
            <select name="status" class="form-select rounded-pill" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                <option value="dikonfirmasi" {{ request('status') == 'dikonfirmasi' ? 'selected' : '' }}>Dikonfirmasi</option>
                <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
            </select>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Kode Bayar</th>
                        <th>Kode Pesanan</th>
                        @if(Auth::user()->role === 'admin')
                        <th>Pelanggan</th>
                        @endif
                        <th>Tanggal Bayar</th>
                        <th>Jumlah</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pembayaran as $item)
                    <tr>
                        <td class="ps-4 fw-bold text-primary">{{ $item->kode_pembayaran }}</td>
                        <td>{{ $item->pesanan->kode_pesanan }}</td>
                        @if(Auth::user()->role === 'admin')
                        <td>{{ $item->pesanan->user->name }}</td>
                        @endif
                        <td>{{ $item->tanggal_bayar->format('d/m/Y') }}</td>
                        <td><span class="fw-bold">Rp {{ number_format($item->jumlah_bayar, 0, ',', '.') }}</span></td>
                        <td>
                            @php
                                $badges = [
                                    'menunggu' => 'bg-warning',
                                    'dikonfirmasi' => 'bg-success',
                                    'ditolak' => 'bg-danger'
                                ];
                            @endphp
                            <span class="badge {{ $badges[$item->status_konfirmasi] }} rounded-pill px-3">
                                {{ ucfirst($item->status_konfirmasi) }}
                            </span>
                        </td>
                        <td class="text-end pe-4">
                            <a href="{{ route('pembayaran.show', $item) }}" class="btn btn-sm btn-light rounded-circle">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ Auth::user()->role === 'admin' ? 7 : 6 }}" class="text-center py-5 text-muted">
                            <i class="bi bi-wallet2 fs-1 mb-2 d-block opacity-25"></i>
                            Belum ada riwayat pembayaran.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($pembayaran->hasPages())
        <div class="p-4 border-top">
            {{ $pembayaran->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
