@extends('layouts.dashboard')

@section('title', 'Manajemen Produksi')
@section('role_name', Auth::user()->role === 'admin' ? 'Administrator' : 'Operator')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold">Antrian Produksi</h3>
    <div class="d-flex gap-2">
        <form action="{{ route('produksi.index') }}" method="GET" class="d-flex gap-2">
            <select name="status" class="form-select rounded-pill" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="antrian" {{ request('status') == 'antrian' ? 'selected' : '' }}>Antrian</option>
                <option value="proses" {{ request('status') == 'proses' ? 'selected' : '' }}>Proses</option>
                <option value="quality_check" {{ request('status') == 'quality_check' ? 'selected' : '' }}>Quality Check</option>
                <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
            </select>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4">
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
                    <tr>
                        <td class="ps-4">
                            <div class="fw-bold text-primary">{{ $item->pesanan->kode_pesanan }}</div>
                            <small class="text-muted">{{ $item->pesanan->detailPesanan->count() }} Item Cetakan</small>
                        </td>
                        <td>{{ $item->pesanan->user->name }}</td>
                        <td>
                            @if($item->pesanan->tanggal_deadline)
                                <span class="{{ $item->pesanan->tanggal_deadline < today() ? 'text-danger fw-bold' : '' }}">
                                    {{ $item->pesanan->tanggal_deadline->format('d/m/Y') }}
                                </span>
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @if($item->operator)
                                <div class="d-flex align-items-center">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($item->operator->name) }}&size=30&background=random" class="rounded-circle me-2">
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
                                    'proses' => 'bg-primary',
                                    'quality_check' => 'bg-info',
                                    'selesai' => 'bg-success'
                                ];
                            @endphp
                            <span class="badge {{ $badges[$item->status_produksi] }} rounded-pill px-3 text-capitalize">
                                {{ str_replace('_', ' ', $item->status_produksi) }}
                            </span>
                        </td>
                        <td class="text-end pe-4">
                            @if($item->status_produksi === 'antrian' && Auth::user()->role === 'operator')
                                <form action="{{ route('produksi.ambil', $item) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-primary rounded-pill px-3">Ambil</button>
                                </form>
                            @endif
                            <a href="{{ route('produksi.show', $item) }}" class="btn btn-sm btn-light rounded-circle ms-1">
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
