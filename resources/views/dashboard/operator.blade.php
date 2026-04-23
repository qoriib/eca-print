@extends('layouts.dashboard')

@section('title', 'Operator Dashboard')
@section('role_name', 'Operator Produksi')

@section('content')
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card stat-card bg-danger text-white">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-uppercase mb-2" style="opacity: 0.8;">Antrian Produksi</h6>
                            <h2 class="fw-semibold mb-0">{{ $data['antrian_produksi'] }}</h2>
                        </div>
                        <i class="bi bi-clock-history fs-1" style="opacity: 0.3;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card bg-primary text-white">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-uppercase mb-2" style="opacity: 0.8;">Sedang Saya Kerjakan</h6>
                            <h2 class="fw-semibold mb-0">{{ $data['sedang_proses'] }}</h2>
                        </div>
                        <i class="bi bi-play-circle fs-1" style="opacity: 0.3;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card bg-success text-white">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-uppercase mb-2" style="opacity: 0.8;">Selesai Hari Ini</h6>
                            <h2 class="fw-semibold mb-0">{{ $data['selesai_hari_ini'] }}</h2>
                        </div>
                        <i class="bi bi-check2-circle fs-1" style="opacity: 0.3;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
            <h5 class="fw-semibold mb-0">Daftar Pekerjaan Produksi</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Kode Pesanan</th>
                            <th>Pelanggan</th>
                            <th>Item Cetakan</th>
                            <th>Status Produksi</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data['daftar_antrian'] as $produksi)
                            <tr class="text-nowrap">
                                <td class="ps-4 fw-medium text-primary">{{ $produksi->pesanan->kode_pesanan }}</td>
                                <td>{{ $produksi->pesanan->user->name }}</td>
                                <td>
                                    @foreach($produksi->pesanan->detailPesanan as $detail)
                                        <span class="badge bg-light text-dark border">{{ $detail->produk->nama_produk }}
                                            ({{ $detail->jumlah }} {{ $detail->produk->satuan }})</span>
                                    @endforeach
                                </td>
                                <td>
                                    @if($produksi->status_produksi === 'antrian')
                                        <span class="badge bg-danger rounded-pill px-3">Antrian</span>
                                    @else
                                        <span
                                            class="badge bg-primary rounded-pill px-3 text-capitalize">{{ $produksi->status_produksi }}</span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    @if($produksi->status_produksi === 'antrian')
                                        <form action="{{ route('produksi.ambil', $produksi) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-primary rounded-pill px-3">Ambil
                                                Pekerjaan</button>
                                        </form>
                                    @else
                                        <a href="{{ route('produksi.show', $produksi) }}"
                                            class="btn btn-sm btn-outline-primary rounded-pill px-3">Update Status</a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="bi bi-emoji-smile fs-1 mb-2 d-block"></i>
                                    Tidak ada antrian pekerjaan saat ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection