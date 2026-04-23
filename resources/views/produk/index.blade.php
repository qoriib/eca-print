@extends('layouts.dashboard')

@section('title', 'Manajemen Produk')
@section('role_name', Auth::user()->role === 'admin' ? 'Administrator' : 'Operator')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <h3 class="fw-semibold mb-0">Manajemen Produk</h3>
    @if(Auth::user()->role === 'admin')
    <a href="{{ route('produk.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-2"></i>Tambah Produk
    </a>
    @endif
</div>

<div class="card mb-4">
    <div class="card-body p-4">
        <form action="{{ route('produk.index') }}" method="GET" class="row g-3">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Cari nama produk..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <select name="kategori" class="form-select" onchange="this.form.submit()">
                    <option value="">Semua Kategori</option>
                    @foreach($kategori as $kat)
                        <option value="{{ $kat->id }}" {{ request('kategori') == $kat->id ? 'selected' : '' }}>{{ $kat->nama_kategori }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-light w-100 border">Filter</button>
            </div>
            @if(request()->anyFilled(['search', 'kategori']))
            <div class="col-md-2">
                <a href="{{ route('produk.index') }}" class="btn btn-link text-decoration-none">Reset</a>
            </div>
            @endif
        </form>
    </div>
</div>

<div class="row g-4">
    @forelse($produk as $item)
    <div class="col-xl-3 col-lg-4 col-md-6">
        <div class="card h-100 border-0 shadow-sm overflow-hidden position-relative stat-card">
            <div class="position-absolute top-0 end-0 m-3 z-3">
                @if($item->is_aktif)
                    <span class="badge bg-success px-3">Aktif</span>
                @else
                    <span class="badge bg-danger px-3">Non-aktif</span>
                @endif
            </div>
            
            <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                @if($item->gambar)
                    <img src="{{ asset('storage/' . $item->gambar) }}" class="w-100 h-100" style="object-fit: cover;">
                @else
                    <i class="bi bi-image text-muted fs-1 opacity-25"></i>
                @endif
            </div>
            
            <div class="card-body">
                <small class="text-primary fw-semibold text-uppercase" style="font-size: 0.7rem;">{{ $item->kategoriProduk->nama_kategori }}</small>
                <h5 class="fw-semibold mb-2 text-truncate" title="{{ $item->nama_produk }}">{{ $item->nama_produk }}</h5>
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="text-primary fw-semibold mb-0">Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</h5>
                    <small class="text-muted">/ {{ $item->satuan }}</small>
                </div>
            </div>
            
            <div class="card-footer bg-white border-0 p-3 pt-0">
                <div class="d-grid gap-2 d-flex">
                    <a href="{{ route('produk.show', $item) }}" class="btn btn-light flex-grow-1">Detail</a>
                    @if(Auth::user()->role === 'admin')
                    <a href="{{ route('produk.edit', $item) }}" class="btn btn-outline-primary rounded-circle"><i class="bi bi-pencil"></i></a>
                    <button type="button" class="btn btn-outline-danger rounded-circle" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $item->id }}"><i class="bi bi-trash"></i></button>
                    @endif
                </div>
            </div>
        </div>

        @if(Auth::user()->role === 'admin')
        <!-- Delete Modal -->
        <div class="modal fade" id="deleteModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header border-0">
                        <h5 class="modal-title fw-semibold">Konfirmasi Hapus</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-start">
                        Apakah Anda yakin ingin menghapus produk <strong>{{ $item->nama_produk }}</strong>?
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Batal</button>
                        <form action="{{ route('produk.destroy', $item) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger px-4">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
    @empty
    <div class="col-12 text-center py-5">
        <div class="text-muted">
            <i class="bi bi-search fs-1 mb-3 d-block opacity-25"></i>
            Produk tidak ditemukan.
        </div>
    </div>
    @endforelse
</div>

<div class="mt-4">
    {{ $produk->appends(request()->query())->links() }}
</div>
@endsection
