@extends('layouts.dashboard')

@section('title', 'Detail Produk')
@section('role_name', 'Administrator')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="d-flex align-items-center mb-4">
            <a href="{{ route('produk.index') }}" class="btn btn-light rounded-circle me-3">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h3 class="fw-semibold mb-0">Detail Produk</h3>
            @if(Auth::user()->role === 'admin')
            <div class="ms-auto">
                <a href="{{ route('produk.edit', $produk) }}" class="btn btn-primary px-4">
                    <i class="bi bi-pencil me-2"></i>Edit Produk
                </a>
            </div>
            @endif
        </div>

        <div class="card overflow-hidden">
            <div class="row g-0">
                <div class="col-md-5 bg-light d-flex align-items-center justify-content-center" style="min-height: 400px;">
                    @if($produk->gambar)
                        <img src="{{ asset('storage/' . $produk->gambar) }}" class="w-100 h-100" style="object-fit: cover;">
                    @else
                        <i class="bi bi-image text-muted fs-1 opacity-25"></i>
                    @endif
                </div>
                <div class="col-md-7">
                    <div class="card-body p-4 p-md-5">
                        <div class="mb-4">
                            <span class="badge bg-primary-subtle text-primary px-3 mb-2 text-uppercase fw-semibold">{{ $produk->kategoriProduk->nama_kategori }}</span>
                            <h2 class="fw-semibold mb-1">{{ $produk->nama_produk }}</h2>
                            <div class="text-muted">Status: 
                                @if($produk->is_aktif)
                                    <span class="text-success fw-semibold"><i class="bi bi-check-circle-fill me-1"></i>Aktif</span>
                                @else
                                    <span class="text-danger fw-semibold"><i class="bi bi-x-circle-fill me-1"></i>Non-aktif</span>
                                @endif
                            </div>
                        </div>

                        <div class="mb-4 bg-light p-4">
                            <label class="text-muted small text-uppercase fw-semibold d-block mb-1">Harga Satuan</label>
                            <h3 class="fw-semibold text-primary mb-0">Rp {{ number_format($produk->harga_satuan, 0, ',', '.') }} <small class="text-muted fs-6 fw-normal">/ {{ $produk->satuan }}</small></h3>
                        </div>

                        <div class="mb-4">
                            <label class="text-muted small text-uppercase fw-semibold d-block mb-2">Deskripsi Produk</label>
                            <div class="text-dark" style="line-height: 1.6;">
                                {!! nl2br(e($produk->deskripsi ?? 'Tidak ada deskripsi untuk produk ini.')) !!}
                            </div>
                        </div>

                        <div class="row g-3 pt-4 border-top">
                            <div class="col-sm-6">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary-subtle p-2 rounded-3 me-3 text-primary">
                                        <i class="bi bi-box-seam fs-4"></i>
                                    </div>
                                    <div>
                                        <div class="text-muted small">Satuan</div>
                                        <div class="fw-semibold text-capitalize">{{ $produk->satuan }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="d-flex align-items-center">
                                    <div class="bg-info-subtle p-2 rounded-3 me-3 text-info">
                                        <i class="bi bi-tags fs-4"></i>
                                    </div>
                                    <div>
                                        <div class="text-muted small">Kategori</div>
                                        <div class="fw-semibold">{{ $produk->kategoriProduk->nama_kategori }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
