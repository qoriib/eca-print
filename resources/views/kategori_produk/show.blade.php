@extends('layouts.dashboard')

@section('title', 'Detail Kategori')
@section('role_name', 'Administrator')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="d-flex align-items-center mb-4">
            <a href="{{ route('kategori-produk.index') }}" class="btn btn-light rounded-circle me-3">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h3 class="fw-bold mb-0">Detail Kategori: {{ $kategoriProduk->nama_kategori }}</h3>
            <div class="ms-auto">
                <a href="{{ route('kategori-produk.edit', $kategoriProduk) }}" class="btn btn-primary rounded-pill px-4">
                    <i class="bi bi-pencil me-2"></i>Edit Kategori
                </a>
            </div>
        </div>

        <div class="row g-4">
            <!-- Informasi Kategori -->
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 p-4">
                    <div class="mb-4">
                        <label class="text-muted small text-uppercase fw-bold d-block mb-1">Nama Kategori</label>
                        <h4 class="fw-bold text-primary">{{ $kategoriProduk->nama_kategori }}</h4>
                    </div>
                    <div class="mb-4">
                        <label class="text-muted small text-uppercase fw-bold d-block mb-1">Deskripsi</label>
                        <p class="text-muted">{{ $kategoriProduk->deskripsi ?? 'Tidak ada deskripsi' }}</p>
                    </div>
                    <div class="mb-0 pt-3 border-top">
                        <label class="text-muted small text-uppercase fw-bold d-block mb-1">Total Produk</label>
                        <h3 class="fw-bold mb-0">{{ $kategoriProduk->produk->count() }}</h3>
                        <small class="text-muted">Produk terdaftar dalam kategori ini</small>
                    </div>
                </div>
            </div>

            <!-- Daftar Produk dalam Kategori -->
            <div class="col-md-8">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="fw-bold mb-0">Daftar Produk Terkait</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4">Produk</th>
                                        <th>Harga</th>
                                        <th>Status</th>
                                        <th class="text-end pe-4">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($kategoriProduk->produk as $produk)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                @if($produk->gambar)
                                                    <img src="{{ asset('storage/' . $produk->gambar) }}" class="rounded-3 me-3" width="45" height="45" style="object-fit: cover;">
                                                @else
                                                    <div class="bg-light rounded-3 me-3 d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                                                        <i class="bi bi-image text-muted"></i>
                                                    </div>
                                                @endif
                                                <div class="fw-bold">{{ $produk->nama_produk }}</div>
                                            </div>
                                        </td>
                                        <td>Rp {{ number_format($produk->harga_satuan, 0, ',', '.') }}/{{ $produk->satuan }}</td>
                                        <td>
                                            @if($produk->is_aktif)
                                                <span class="badge bg-success-subtle text-success rounded-pill px-3">Aktif</span>
                                            @else
                                                <span class="badge bg-danger-subtle text-danger rounded-pill px-3">Non-aktif</span>
                                            @endif
                                        </td>
                                        <td class="text-end pe-4">
                                            <a href="{{ route('produk.show', $produk) }}" class="btn btn-sm btn-light rounded-circle">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5 text-muted">Belum ada produk dalam kategori ini</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
