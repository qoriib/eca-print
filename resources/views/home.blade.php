@extends('layouts.store')

@section('title', 'Percetakan Digital & Offset Terbaik')

@section('content')
    <!-- Hero Section -->
    <header class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill mb-3 fw-bold">Solusi Cetak Profesional</span>
                    <h1 class="display-3 fw-bolder mb-4" style="letter-spacing: -2px; line-height: 1.1;">Cetak Impian Anda Jadi <span class="text-primary">Kenyataan.</span></h1>
                    <p class="lead text-muted mb-5">Kami melayani berbagai kebutuhan cetak digital, offset, dan merchandise dengan kualitas terbaik dan pengerjaan cepat.</p>
                    <div class="d-flex gap-3">
                        <a href="#katalog" class="btn btn-primary btn-lg px-5">Mulai Belanja</a>
                        <a href="https://wa.me/{{ $pengaturan->no_hp ?? '' }}" target="_blank" class="btn btn-outline-dark btn-lg px-5">Tanya Admin</a>
                    </div>
                </div>
                <div class="col-lg-6 d-none d-lg-block">
                    <img src="https://images.unsplash.com/photo-1562654501-a0ccc0fc3fb1?q=80&w=1932&auto=format&fit=crop" alt="Printing" class="img-fluid rounded-4 shadow-lg">
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
                    <a href="{{ route('home') }}#katalog" class="btn btn-outline-primary rounded-pill mt-3">Reset Filter</a>
                </div>
            @else
                <div class="row g-4">
                    @foreach($produk as $item)
                        <div class="col-md-4 col-lg-3">
                            <div class="card h-100 overflow-hidden border-0 shadow-sm">
                                <div class="position-relative">
                                    @if($item->gambar)
                                        <img src="{{ asset('storage/' . $item->gambar) }}" class="card-img-top" alt="{{ $item->nama_produk }}" style="height: 200px; object-fit: cover;">
                                    @else
                                        <img src="https://placehold.co/600x400?text={{ urlencode($item->nama_produk) }}" class="card-img-top" alt="{{ $item->nama_produk }}" style="height: 200px; object-fit: cover;">
                                    @endif
                                    <div class="position-absolute top-0 start-0 m-2">
                                        <span class="badge bg-white text-primary shadow-sm px-3 py-2 rounded-pill small fw-bold">
                                            {{ $item->kategoriProduk->nama_kategori }}
                                        </span>
                                    </div>
                                </div>
                                <div class="card-body p-4">
                                    <h6 class="fw-bold mb-2 text-truncate">{{ $item->nama_produk }}</h6>
                                    <div class="mb-3">
                                        <span class="text-muted small d-block">Harga mulai</span>
                                        <span class="fw-bold fs-5 text-primary">Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</span>
                                        <small class="text-muted">/{{ $item->satuan }}</small>
                                    </div>
                                    <a href="{{ route('produk.show', $item) }}" class="btn btn-outline-primary btn-sm w-100 rounded-pill py-2 fw-bold">Lihat Detail</a>
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

    <!-- Services / Features -->
    <section class="py-5 bg-light">
        <div class="container text-center mb-5">
            <h2 class="fw-bold">Mengapa Memilih Kami?</h2>
        </div>
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 p-4 border-0 text-center">
                        <div class="bg-primary-subtle text-primary rounded-circle d-inline-flex p-3 mb-3 mx-auto" style="width: 70px; height: 70px; align-items: center; justify-content: center;">
                            <i class="bi bi-lightning-fill fs-3"></i>
                        </div>
                        <h5 class="fw-bold">Cepat & Tepat</h5>
                        <p class="text-muted mb-0 small">Pengerjaan tepat waktu sesuai deadline yang disepakati dengan kualitas terjaga.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 p-4 border-0 text-center">
                        <div class="bg-success-subtle text-success rounded-circle d-inline-flex p-3 mb-3 mx-auto" style="width: 70px; height: 70px; align-items: center; justify-content: center;">
                            <i class="bi bi-shield-check fs-3"></i>
                        </div>
                        <h5 class="fw-bold">Kualitas Premium</h5>
                        <p class="text-muted mb-0 small">Menggunakan mesin cetak terbaru dan bahan berkualitas tinggi untuk hasil maksimal.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 p-4 border-0 text-center">
                        <div class="bg-warning-subtle text-warning rounded-circle d-inline-flex p-3 mb-3 mx-auto" style="width: 70px; height: 70px; align-items: center; justify-content: center;">
                            <i class="bi bi-wallet2 fs-3"></i>
                        </div>
                        <h5 class="fw-bold">Harga Terjangkau</h5>
                        <p class="text-muted mb-0 small">Harga kompetitif dengan berbagai pilihan paket yang bisa disesuaikan dengan budget Anda.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-5 bg-white">
        <div class="container">
            <div class="bg-primary rounded-4 p-5 text-center text-white shadow-lg">
                <h2 class="fw-bold display-6 mb-4">Siap Mencetak Pesanan Anda?</h2>
                <p class="lead mb-5 opacity-75">Daftar sekarang dan nikmati kemudahan memesan cetakan secara online.</p>
                <div class="d-flex justify-content-center flex-wrap gap-3">
                    <a href="{{ route('register') }}" class="btn btn-light btn-lg px-5 text-primary fw-bold">Daftar Gratis</a>
                    <a href="https://wa.me/{{ $pengaturan->no_hp ?? '' }}" target="_blank" class="btn btn-outline-light btn-lg px-5 fw-bold">
                        <i class="bi bi-whatsapp me-2"></i>Hubungi Kami
                    </a>
                </div>
            </div>
        </div>
    </section>

    <style>
        .hero-section {
            padding: 100px 0 80px;
            background: linear-gradient(135deg, #fff 0%, #f4f7ff 100%);
        }
    </style>
@endsection
