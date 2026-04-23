@extends('layouts.dashboard')

@section('title', 'Daftar Pesanan')
@section('role_name', Auth::user()->role === 'admin' ? 'Administrator' : (Auth::user()->role === 'operator' ? 'Operator' : 'Pelanggan'))

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <h3 class="fs-5 fw-semibold mb-0">Daftar Pesanan</h3>
        @if(Auth::user()->role === 'pelanggan')
            <a href="{{ route('pesanan.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-2"></i>Buat Pesanan Baru
            </a>
        @endif
    </div>

    <div class="card mb-4">
        <div class="card-body p-4">
            <form action="{{ route('pesanan.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0 ps-0"
                            placeholder="Cari Kode Pesanan (ECA-XXXX)..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        <option value="menunggu_konfirmasi" {{ request('status') == 'menunggu_konfirmasi' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                        <option value="dikonfirmasi" {{ request('status') == 'dikonfirmasi' ? 'selected' : '' }}>Dikonfirmasi
                        </option>
                        <option value="dalam_produksi" {{ request('status') == 'dalam_produksi' ? 'selected' : '' }}>Dalam
                            Produksi</option>
                        <option value="selesai_produksi" {{ request('status') == 'selesai_produksi' ? 'selected' : '' }}>
                            Selesai Produksi</option>
                        <option value="siap_diambil" {{ request('status') == 'siap_diambil' ? 'selected' : '' }}>Siap Diambil
                        </option>
                        <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="dibatalkan" {{ request('status') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan
                        </option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-light w-100 border">Filter</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Kode</th>
                            @if(Auth::user()->role !== 'pelanggan')
                                <th>Pelanggan</th>
                            @endif
                            <th>Tanggal Pesan</th>
                            <th>Total Harga</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pesanan as $item)
                            <tr>
                                <td class="ps-4 fw-semibold font-monospace text-primary">{{ $item->kode_pesanan }}</td>
                                @if(Auth::user()->role !== 'pelanggan')
                                    <td>{{ $item->user->name }}</td>
                                @endif
                                <td class="font-monospace">{{ $item->tanggal_pesan->format('d/m/Y') }}</td>
                                <td><span class="fw-semibold font-monospace">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</span>
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
                                    <span class="badge {{ $badges[$item->status] ?? 'bg-secondary' }} px-3 text-capitalize">
                                        {{ str_replace('_', ' ', $item->status) }}
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="btn-group">
                                        <a href="{{ route('pesanan.show', $item) }}"
                                            class="btn btn-sm btn-light rounded-circle me-1" title="Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        @if(Auth::user()->role === 'admin')
                                            <button type="button" class="btn btn-sm btn-light text-danger rounded-circle"
                                                data-bs-toggle="modal" data-bs-target="#deleteModal{{ $item->id }}" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        @endif
                                    </div>

                                    @if(Auth::user()->role === 'admin')
                                        <!-- Delete Modal -->
                                        <div class="modal fade" id="deleteModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content border-0 shadow">
                                                    <div class="modal-header border-0">
                                                        <h5 class="modal-title fw-semibold">Konfirmasi Hapus</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body text-start">
                                                        Apakah Anda yakin ingin menghapus pesanan
                                                        <strong>{{ $item->kode_pesanan }}</strong>?
                                                        <div class="alert alert-warning mt-2 py-2 small border-0">
                                                            <i class="bi bi-exclamation-triangle me-1"></i> Data yang sudah dihapus
                                                            tidak dapat dikembalikan.
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer border-0">
                                                        <button type="button" class="btn btn-light px-4"
                                                            data-bs-dismiss="modal">Batal</button>
                                                        <form action="{{ route('pesanan.destroy', $item) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger px-4">Hapus</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ Auth::user()->role !== 'pelanggan' ? 6 : 5 }}"
                                    class="text-center py-5 text-muted">
                                    <i class="bi bi-cart-x fs-1 mb-2 d-block opacity-25"></i>
                                    Belum ada pesanan ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($pesanan->hasPages())
                <div class="p-4 border-top">
                    {{ $pesanan->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection