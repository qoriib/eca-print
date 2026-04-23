@extends('layouts.dashboard')

@section('title', 'Tambah Kategori')
@section('role_name', 'Administrator')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('kategori-produk.index') }}" class="btn btn-light me-3">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <h3 class="fs-5 fw-semibold mb-0">Tambah Kategori Produk</h3>
            </div>

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('kategori-produk.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="nama_kategori" class="form-label">Nama Kategori</label>
                            <input type="text" class="form-control @error('nama_kategori') is-invalid @enderror"
                                id="nama_kategori" name="nama_kategori" value="{{ old('nama_kategori') }}" required
                                placeholder="Contoh: Brosur, Banner, Kartu Nama">
                            @error('nama_kategori')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi (Opsional)</label>
                            <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi"
                                name="deskripsi" rows="4"
                                placeholder="Jelaskan secara singkat tentang kategori ini">{{ old('deskripsi') }}</textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-primary px-5 py-2 fw-semibold">
                                <i class="bi bi-save me-2"></i>Simpan Kategori
                            </button>
                            <a href="{{ route('kategori-produk.index') }}" class="btn btn-light px-4 py-2">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection