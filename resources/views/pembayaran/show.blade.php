@extends('layouts.dashboard')

@section('title', 'Detail Pembayaran')
@section('role_name', Auth::user()->role === 'admin' ? 'Administrator' : 'Pelanggan')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-11">
        <div class="d-flex align-items-center mb-4">
            <a href="{{ route('pembayaran.index') }}" class="btn btn-light rounded-circle me-3">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h3 class="fw-bold mb-0">Detail Pembayaran: {{ $pembayaran->kode_pembayaran }}</h3>
        </div>

        <div class="row g-4">
            <!-- Rincian Pembayaran -->
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="fw-bold mb-0">Informasi Transaksi</h5>
                            @php
                                $badges = [
                                    'menunggu' => 'bg-warning',
                                    'dikonfirmasi' => 'bg-success',
                                    'ditolak' => 'bg-danger'
                                ];
                            @endphp
                            <span class="badge {{ $badges[$pembayaran->status_konfirmasi] }} rounded-pill px-4 py-2 fs-6">
                                {{ ucfirst($pembayaran->status_konfirmasi) }}
                            </span>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="text-muted ps-0">Pesanan</td>
                                    <td class="fw-bold text-end">
                                        <a href="{{ route('pesanan.show', $pembayaran->pesanan) }}" class="text-decoration-none">
                                            {{ $pembayaran->pesanan->kode_pesanan }} <i class="bi bi-box-arrow-up-right small ms-1"></i>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted ps-0">Pelanggan</td>
                                    <td class="fw-bold text-end">{{ $pembayaran->pesanan->user->name }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted ps-0">Tanggal Bayar</td>
                                    <td class="fw-bold text-end">{{ $pembayaran->tanggal_bayar->format('d M Y') }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted ps-0">Metode</td>
                                    <td class="fw-bold text-end text-capitalize">{{ $pembayaran->metode_pembayaran }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted ps-0">Jenis</td>
                                    <td class="fw-bold text-end text-capitalize">{{ $pembayaran->jenis_pembayaran }}</td>
                                </tr>
                                <tr class="border-top">
                                    <td class="text-muted ps-0 pt-3 fs-5">Jumlah Bayar</td>
                                    <td class="fw-bold text-end pt-3 fs-4 text-primary">Rp {{ number_format($pembayaran->jumlah_bayar, 0, ',', '.') }}</td>
                                </tr>
                            </table>
                        </div>

                        @if($pembayaran->catatan)
                        <div class="mt-4 p-3 bg-light rounded-3">
                            <label class="small fw-bold text-muted text-uppercase mb-1 d-block">Catatan Pelanggan</label>
                            <p class="mb-0 small italic">"{{ $pembayaran->catatan }}"</p>
                        </div>
                        @endif

                        @if($pembayaran->status_konfirmasi === 'menunggu' && Auth::user()->role === 'admin')
                        <div class="mt-5 pt-4 border-top">
                            <h6 class="fw-bold mb-3">Tindakan Admin</h6>
                            <form action="{{ route('pembayaran.konfirmasi', $pembayaran) }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label small fw-bold">Pilih Aksi</label>
                                    <div class="d-flex gap-2">
                                        <input type="radio" class="btn-check" name="status_konfirmasi" id="konfirmasi_acc" value="dikonfirmasi" checked>
                                        <label class="btn btn-outline-success rounded-pill flex-grow-1" for="konfirmasi_acc">Terima Pembayaran</label>

                                        <input type="radio" class="btn-check" name="status_konfirmasi" id="konfirmasi_reject" value="ditolak">
                                        <label class="btn btn-outline-danger rounded-pill flex-grow-1" for="konfirmasi_reject">Tolak</label>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold">Catatan Konfirmasi (Opsional)</label>
                                    <textarea name="catatan" class="form-control" rows="3" placeholder="Alasan penolakan atau catatan tambahan..."></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary w-100 rounded-pill py-2 fw-bold">Simpan Konfirmasi</button>
                            </form>
                        </div>
                        @endif

                        @if($pembayaran->dikonfirmasi_oleh)
                        <div class="mt-4 p-3 bg-success-subtle rounded-3 border border-success">
                            <div class="small fw-bold text-success mb-1">Dikonfirmasi Oleh:</div>
                            <div class="fw-bold">{{ $pembayaran->dikonfirmasiOleh->name }}</div>
                            <div class="small text-muted">{{ $pembayaran->updated_at->format('d/m/Y H:i') }}</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Bukti Pembayaran -->
            <div class="col-md-6">
                <div class="card h-100 overflow-hidden">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="fw-bold mb-0">Bukti Pembayaran</h5>
                    </div>
                    <div class="card-body p-0 bg-light d-flex align-items-center justify-content-center" style="min-height: 400px;">
                        @if($pembayaran->bukti_pembayaran)
                            @php $extension = pathinfo($pembayaran->bukti_pembayaran, PATHINFO_EXTENSION); @endphp
                            @if(in_array(strtolower($extension), ['jpg', 'jpeg', 'png']))
                                <img src="{{ asset('storage/' . $pembayaran->bukti_pembayaran) }}" class="img-fluid shadow-sm" style="max-height: 600px;">
                            @else
                                <div class="text-center">
                                    <i class="bi bi-file-earmark-pdf fs-1 text-danger mb-3"></i>
                                    <div class="fw-bold mb-3">Dokumen PDF</div>
                                    <a href="{{ asset('storage/' . $pembayaran->bukti_pembayaran) }}" target="_blank" class="btn btn-primary rounded-pill px-4">
                                        <i class="bi bi-eye me-2"></i>Lihat Dokumen
                                    </a>
                                </div>
                            @endif
                        @else
                            <div class="text-center text-muted p-5">
                                <i class="bi bi-image fs-1 mb-3 opacity-25"></i>
                                <p>Tidak ada unggahan bukti pembayaran.</p>
                            </div>
                        @endif
                    </div>
                    @if($pembayaran->bukti_pembayaran)
                    <div class="card-footer bg-white border-0 p-3 text-center">
                        <a href="{{ asset('storage/' . $pembayaran->bukti_pembayaran) }}" download class="btn btn-sm btn-outline-secondary rounded-pill px-4">
                            <i class="bi bi-download me-2"></i>Unduh Bukti Bayar
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
