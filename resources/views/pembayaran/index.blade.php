@extends('layouts.dashboard')

@section('title', 'Riwayat Pembayaran')
@section('role_name', Auth::user()->role === 'admin' ? 'Administrator' : 'Pelanggan')

@section('content')
    <div class="card mb-4">
        <div class="card-body p-4">
            <form action="{{ route('pembayaran.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label small fw-bold">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                        <option value="dikonfirmasi" {{ request('status') == 'dikonfirmasi' ? 'selected' : '' }}>Dikonfirmasi</option>
                        <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold">Mulai Tgl</label>
                    <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold">Sampai Tgl</label>
                    <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                </div>
                <div class="col-md-3 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                    @if(request()->anyFilled(['status', 'start_date', 'end_date']))
                        <a href="{{ route('pembayaran.index') }}" class="btn btn-light border" title="Reset"><i class="bi bi-arrow-clockwise"></i></a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    @if(request()->filled('start_date') || request()->filled('end_date'))
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-success text-white">
                    <div class="card-body p-4 text-center">
                        <h6 class="text-uppercase small fw-bold opacity-75 mb-2">Total Pembayaran Masuk (Filter)</h6>
                        <h3 class="fw-bold mb-0 font-monospace">Rp {{ number_format($total_nominal, 0, ',', '.') }}</h3>
                        <small class="opacity-75">{{ $pembayaran->total() }} transaksi ditemukan</small>
                    </div>
                </div>
            </div>
        </div>
    @endif

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
                                <td class="ps-4 fw-semibold font-monospace text-primary">{{ $item->kode_pembayaran }}</td>
                                <td>{{ $item->pesanan->kode_pesanan }}</td>
                                @if(Auth::user()->role === 'admin')
                                    <td>{{ $item->pesanan->user->name }}</td>
                                @endif
                                <td class="font-monospace">{{ $item->tanggal_bayar->format('d/m/Y') }}</td>
                                <td><span class="fw-semibold font-monospace">Rp {{ number_format($item->jumlah_bayar, 0, ',', '.') }}</span>
                                </td>
                                <td>
                                    @php
                                        $badges = [
                                            'menunggu' => 'bg-warning',
                                            'dikonfirmasi' => 'bg-success',
                                            'ditolak' => 'bg-danger'
                                        ];
                                    @endphp
                                    <span class="badge {{ $badges[$item->status_konfirmasi] }} px-3">
                                        {{ ucfirst($item->status_konfirmasi) }}
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <a href="{{ route('pembayaran.show', $item) }}" class="btn btn-sm btn-light">
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