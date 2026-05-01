@extends('layouts.store')

@section('title', 'Percetakan Digital & Offset Terbaik')

@section('content')
    <!-- Hero Section -->
    <header class="hero-section">
        <div class="container">
            <div class="row justify-content-center text-center">
                <div class="col-lg-10">
                    <span class="badge bg-primary-subtle text-primary px-3 py-2 mb-4 fw-bold">Solusi Cetak Profesional & Terpercaya</span>
                    <h1 class="display-2 fw-bolder mb-4" style="letter-spacing: -2px; line-height: 1.1;">Cetak Impian Anda Jadi <span class="text-primary">Kenyataan.</span></h1>
                    <p class="lead text-muted mb-5 mx-auto" style="max-width: 700px;">Kami melayani berbagai kebutuhan cetak digital, offset, dan merchandise dengan kualitas premium, harga terjangkau, dan pengerjaan tepat waktu.</p>
                    <div class="d-flex justify-content-center gap-3">
                        <a href="https://wa.me/{{ $pengaturan->no_hp ?? '' }}" target="_blank" class="btn btn-outline-dark btn-lg px-4">Hubungi Admin</a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Catalog Section -->
    <section id="katalog" class="py-5 bg-white">
        <div class="container">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-5 gap-4">
                <div>
                    <h2 class="fw-bold mb-1">Katalog Produk</h2>
                    <p class="text-muted mb-0">Pilih produk cetak yang Anda butuhkan</p>
                </div>
                
                <!-- Search & Filter -->
                <div class="col-lg-7">
                    <form action="{{ route('home') }}#katalog" method="GET" class="row g-2">
                        <div class="col-md-5">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
                                <input type="text" name="search" class="form-control border-start-0" placeholder="Cari produk..." value="{{ request('search') }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <select name="kategori" class="form-select" onchange="this.form.submit()">
                                <option value="">Semua Kategori</option>
                                @foreach($categories as $kat)
                                    <option value="{{ $kat->id }}" {{ request('kategori') == $kat->id ? 'selected' : '' }}>
                                        {{ $kat->nama_kategori }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary w-100">Filter</button>
                        </div>
                    </form>
                </div>
            </div>

            @if($produk->isEmpty())
                <div class="text-center py-5">
                    <i class="bi bi-search fs-1 text-muted mb-3 opacity-25"></i>
                    <h4 class="text-muted">Produk tidak ditemukan</h4>
                    <p>Coba gunakan kata kunci atau kategori lain.</p>
                    <a href="{{ route('home') }}#katalog" class="btn btn-outline-primary mt-3">Reset Filter</a>
                </div>
            @else
                <div class="row g-4">
                    @foreach($produk as $item)
                        <div class="col-md-4 col-lg-3">
                            <div class="card h-100 border-0 shadow-sm overflow-hidden position-relative stat-card">
                                <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                    @if($item->gambar)
                                        <img src="{{ asset('storage/' . $item->gambar) }}" class="w-100 h-100" style="object-fit: cover;">
                                    @else
                                        <i class="bi bi-image text-muted fs-1 opacity-25"></i>
                                    @endif
                                </div>

                                <div class="card-body">
                                    <small class="text-primary fw-semibold text-uppercase" style="font-size: 0.7rem;">{{ $item->kategoriProduk->nama_kategori }}</small>
                                    <h6 class="fw-semibold mb-2 text-truncate" title="{{ $item->nama_produk }}">{{ $item->nama_produk }}</h6>
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h5 class="text-primary fw-bold font-monospace mb-0">Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</h5>
                                        <small class="text-muted">/ {{ $item->satuan }}</small>
                                    </div>
                                    <a href="{{ route('produk.show', $item) }}" class="btn btn-light btn-sm w-100 py-2">Lihat Detail</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-5 d-flex justify-content-center">
                    {{ $produk->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </section>

    <style>
        .hero-section {
            padding: 160px 0 140px;
            background: linear-gradient(135deg, #fff 0%, #f4f7ff 100%);
        }
    </style>
@endsection
