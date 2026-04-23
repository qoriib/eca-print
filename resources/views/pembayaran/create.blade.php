@extends('layouts.dashboard')

@section('title', 'Kirim Bukti Pembayaran')
@section('role_name', 'Pelanggan')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('pesanan.show', $pesanan) }}" class="btn btn-light me-3">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <h3 class="fs-5 fw-semibold mb-0">Konfirmasi Pembayaran</h3>
            </div>

            <div class="row g-4">
                <!-- Instruksi Pembayaran -->
                <div class="col-md-5">
                    <div class="card mb-4">
                        <div class="card-body p-4">
                            <h5 class="fw-semibold mb-4">Instruksi Pembayaran</h5>
                            <div class="alert alert-primary border-0 mb-4">
                                <div class="small mb-1">Total yang harus dibayar:</div>
                                <h3 class="fs-5 fw-semibold font-monospace mb-0">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}
                                </h3>
                            </div>

                            <div class="mb-4">
                                <h6 class="fw-semibold small text-uppercase text-muted">Transfer Bank (Manual)</h6>
                                <div class="d-flex align-items-center p-3 bg-light rounded mb-2">
                                    <div class="fw-semibold">Bank BCA</div>
                                    <div class="ms-auto text-end">
                                        <div class="fw-semibold font-monospace">1234567890</div>
                                        <small class="text-muted">a.n Eca Print Mandiri</small>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center p-3 bg-light rounded">
                                    <div class="fw-semibold">Bank Mandiri</div>
                                    <div class="ms-auto text-end">
                                        <div class="fw-semibold font-monospace">0987654321</div>
                                        <small class="text-muted">a.n Eca Print Mandiri</small>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-0">
                                <h6 class="fw-semibold small text-uppercase text-muted">QRIS (Otomatis)</h6>
                                <div class="text-center p-3 border bg-white">
                                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=ECAPRINT-PAYMENT"
                                        alt="QRIS" class="img-fluid mb-2">
                                    <div class="small fw-semibold">SCAN UNTUK BAYAR</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Upload -->
                <div class="col-md-7">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('pembayaran.store', $pesanan) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <label for="jumlah_bayar" class="form-label">Jumlah Yang Dibayar</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white">Rp</span>
                                            <input type="number" name="jumlah_bayar" id="jumlah_bayar"
                                                class="form-control @error('jumlah_bayar') is-invalid @enderror"
                                                value="{{ old('jumlah_bayar', $pesanan->total_harga) }}" required>
                                        </div>
                                        @error('jumlah_bayar')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="jenis_pembayaran" class="form-label">Jenis Pembayaran</label>
                                        <select name="jenis_pembayaran" id="jenis_pembayaran"
                                            class="form-select @error('jenis_pembayaran') is-invalid @enderror" required>
                                            <option value="full" {{ old('jenis_pembayaran') == 'full' ? 'selected' : '' }}>
                                                Pelunasan Penuh (100%)</option>
                                            <option value="dp" {{ old('jenis_pembayaran') == 'dp' ? 'selected' : '' }}>Uang
                                                Muka (DP)</option>
                                            <option value="pelunasan" {{ old('jenis_pembayaran') == 'pelunasan' ? 'selected' : '' }}>Sisa Pelunasan</option>
                                        </select>
                                        @error('jenis_pembayaran')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="metode_pembayaran" class="form-label">Metode</label>
                                        <select name="metode_pembayaran" id="metode_pembayaran"
                                            class="form-select @error('metode_pembayaran') is-invalid @enderror" required>
                                            <option value="transfer" {{ old('metode_pembayaran') == 'transfer' ? 'selected' : '' }}>Transfer Bank</option>
                                            <option value="qris" {{ old('metode_pembayaran') == 'qris' ? 'selected' : '' }}>
                                                QRIS</option>
                                            <option value="tunai" {{ old('metode_pembayaran') == 'tunai' ? 'selected' : '' }}>
                                                Tunai di Toko</option>
                                        </select>
                                        @error('metode_pembayaran')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-12">
                                        <label for="tanggal_bayar" class="form-label">Tanggal Bayar</label>
                                        <input type="date" name="tanggal_bayar" id="tanggal_bayar"
                                            class="form-control @error('tanggal_bayar') is-invalid @enderror"
                                            value="{{ old('tanggal_bayar', date('Y-m-d')) }}" required>
                                        @error('tanggal_bayar')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-12">
                                        <label for="bukti_pembayaran" class="form-label">Unggah Bukti Transfer</label>
                                        <input type="file" name="bukti_pembayaran" id="bukti_pembayaran"
                                            class="form-control @error('bukti_pembayaran') is-invalid @enderror"
                                            accept="image/*,application/pdf">
                                        <small class="text-muted">JPG, PNG, atau PDF (Maks. 5MB)</small>
                                        @error('bukti_pembayaran')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-12">
                                        <label for="catatan" class="form-label">Catatan Tambahan (Opsional)</label>
                                        <textarea name="catatan" id="catatan" class="form-control" rows="3"
                                            placeholder="Contoh: Transfer dari rekening a.n Budi">{{ old('catatan') }}</textarea>
                                    </div>
                                </div>

                                <div class="mt-5 d-grid">
                                    <button type="submit" class="btn btn-primary btn-lg fw-semibold">Kirim Konfirmasi
                                        Pembayaran</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection