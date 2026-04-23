@extends('layouts.dashboard')

@section('title', 'Tambah Produk')
@section('role_name', 'Administrator')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="d-flex align-items-center mb-4">
            <a href="{{ route('produk.index') }}" class="btn btn-light rounded-circle me-3">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h3 class="fs-5 fw-semibold mb-0">Tambah Produk Baru</h3>
        </div>

        <form action="{{ route('produk.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row g-4">
                <!-- Informasi Produk -->
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body p-4 p-md-5">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="nama_produk" class="form-label fw-semibold">Nama Produk</label>
                                    <input type="text" class="form-control @error('nama_produk') is-invalid @enderror" id="nama_produk" name="nama_produk" value="{{ old('nama_produk') }}" required placeholder="Contoh: Brosur A4 Art Paper">
                                    @error('nama_produk')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="kategori_produk_id" class="form-label fw-semibold">Kategori</label>
                                    <select class="form-select @error('kategori_produk_id') is-invalid @enderror" id="kategori_produk_id" name="kategori_produk_id" required>
                                        <option value="" disabled selected>Pilih Kategori</option>
                                        @foreach($kategori as $kat)
                                            <option value="{{ $kat->id }}" {{ old('kategori_produk_id') == $kat->id ? 'selected' : '' }}>{{ $kat->nama_kategori }}</option>
                                        @endforeach
                                    </select>
                                    @error('kategori_produk_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="satuan" class="form-label fw-semibold">Satuan</label>
                                    <input type="text" class="form-control @error('satuan') is-invalid @enderror" id="satuan" name="satuan" value="{{ old('satuan', 'pcs') }}" required placeholder="pcs, lembar, meter, dll">
                                    @error('satuan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-12">
                                    <label for="harga_satuan" class="form-label fw-semibold">Harga Satuan</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white">Rp</span>
                                        <input type="number" class="form-control @error('harga_satuan') is-invalid @enderror" id="harga_satuan" name="harga_satuan" value="{{ old('harga_satuan') }}" required min="0" placeholder="0">
                                    </div>
                                    @error('harga_satuan')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label for="deskripsi" class="form-label fw-semibold">Deskripsi Produk</label>
                                    <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi" name="deskripsi" rows="5" placeholder="Jelaskan detail produk, spesifikasi, atau minimal order...">{{ old('deskripsi') }}</textarea>
                                    @error('deskripsi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Gambar & Pengaturan -->
                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-body p-4">
                            <label class="form-label fw-semibold d-block mb-3">Gambar Produk</label>
                            <div class="text-center mb-3">
                                <div id="imagePreview" class="bg-light d-flex align-items-center justify-content-center overflow-hidden" style="height: 200px; border: 2px dashed #dee2e6;">
                                    <i class="bi bi-image text-muted fs-1 opacity-25"></i>
                                </div>
                            </div>
                            <input type="file" class="form-control @error('gambar') is-invalid @enderror" id="gambar" name="gambar" accept="image/*" onchange="previewImage(this)">
                            <small class="text-muted d-block mt-2">Maksimal 2MB (JPG, PNG, WebP)</small>
                            @error('gambar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body p-4">
                            <label class="form-label fw-semibold d-block mb-3">Pengaturan</label>
                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input" type="checkbox" role="switch" id="is_aktif" name="is_aktif" value="1" {{ old('is_aktif', true) ? 'checked' : '' }}>
                                <label class="form-check-label fw-medium" for="is_aktif">Produk Aktif</label>
                            </div>
                            <small class="text-muted">Produk yang aktif akan muncul di katalog pesanan pelanggan.</small>
                        </div>
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-primary btn-lg fw-semibold">Simpan Produk</button>
                        <a href="{{ route('produk.index') }}" class="btn btn-light btn-lg">Batal</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function previewImage(input) {
        const preview = document.getElementById('imagePreview');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.innerHTML = `<img src="${e.target.result}" class="w-100 h-100" style="object-fit: cover;">`;
                preview.style.border = 'none';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
@endsection
