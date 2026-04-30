@extends('layouts.dashboard')

@section('title', 'Manajemen Produksi')
@section('role_name', Auth::user()->role === 'admin' ? 'Administrator' : 'Operator')

@section('content')
    <div class="card mb-4">
        <div class="card-body p-4">
            <form action="{{ route('produksi.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label small fw-bold">Status</label>
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
                        <a href="{{ route('produksi.index') }}" class="btn btn-light border" title="Reset"><i class="bi bi-arrow-clockwise"></i></a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    @if(request()->filled('start_date') || request()->filled('end_date'))
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-info text-white">
                    <div class="card-body p-4 text-center">
                        <h6 class="text-uppercase small fw-bold opacity-75 mb-2">Total Volume Produksi (Filter)</h6>
                        <h3 class="fw-bold mb-0 font-monospace">{{ $total_produksi }}</h3>
                        <small class="opacity-75">Pesanan dalam proses/selesai</small>
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
                            <th class="ps-4">Pesanan</th>
                            <th>Pelanggan</th>
                            <th>Deadline</th>
                            <th>Operator</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($produksi as $item)
                            <tr class="text-nowrap">
                                <td class="ps-4">
                                    <div class="fw-semibold text-primary font-monospace">{{ $item->pesanan->kode_pesanan }}</div>
                                    <small class="text-muted">{{ $item->pesanan->detailPesanan->count() }} Item Cetakan</small>
                                </td>
                                <td>{{ $item->pesanan->user->name }}</td>
                                <td class="font-monospace">
                                    @if($item->pesanan->tanggal_deadline)
                                        <span
                                            class="{{ $item->pesanan->tanggal_deadline < today() ? 'text-danger fw-semibold' : '' }}">
                                            {{ $item->pesanan->tanggal_deadline->format('d/m/Y') }}
                                        </span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if($item->operator)
                                        <div class="d-flex align-items-center">
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($item->operator->name) }}&size=30&background=random"
                                                class="rounded me-2">
                                            <small>{{ $item->operator->name }}</small>
                                        </div>
                                    @else
                                        <span class="text-muted small">Belum ada</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $badges = [
                                            'antrian' => 'bg-danger',
                                            'desain' => 'bg-warning text-dark',
                                            'cetak' => 'bg-primary',
                                            'finishing' => 'bg-info',
                                            'selesai' => 'bg-success'
                                        ];
                                    @endphp
                                    <span class="badge {{ $badges[$item->status_produksi] }} px-3 text-capitalize">
                                        {{ str_replace('_', ' ', $item->status_produksi) }}
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    @if($item->status_produksi === 'antrian' && Auth::user()->role === 'operator')
                                        <form action="{{ route('produksi.ambil', $item) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-primary px-3">Ambil</button>
                                        </form>
                                    @endif
                                    <a href="{{ route('produksi.show', $item) }}"
                                        class="btn btn-sm btn-light ms-1">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">Tidak ada data produksi yang sesuai</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($produksi->hasPages())
                <div class="p-4 border-top">
                    {{ $produksi->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection