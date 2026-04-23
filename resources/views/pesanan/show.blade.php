@extends('layouts.dashboard')

@section('title', 'Detail Pesanan')
@section('role_name', Auth::user()->role === 'admin' ? 'Administrator' : 'Pelanggan')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-11">
        <div class="d-flex flex-column flex-md-row align-items-md-center mb-4 gap-3">
            <a href="{{ route('pesanan.index') }}" class="btn btn-light rounded-circle me-md-2">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h3 class="fw-semibold mb-1">Pesanan {{ $pesanan->kode_pesanan }}</h3>
                <small class="text-muted">Dibuat pada {{ $pesanan->tanggal_pesan->format('d M Y, H:i') }}</small>
            </div>
            
            <div class="ms-md-auto d-flex gap-2">
                @if(Auth::user()->role === 'admin')
                    <button type="button" class="btn btn-primary px-4" data-bs-toggle="modal" data-bs-target="#updateStatusModal">
                        <i class="bi bi-pencil-square me-2"></i>Update Status
                    </button>
                @endif
                
                @if(Auth::user()->role === 'pelanggan' && $pesanan->status === 'menunggu_konfirmasi')
                    <form action="{{ route('pesanan.batalkan', $pesanan) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger px-4" onclick="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')">
                            Batalkan Pesanan
                        </button>
                    </form>
                @endif

                @if($pesanan->status === 'menunggu_konfirmasi' && Auth::user()->role === 'pelanggan' && !$pesanan->pembayaran)
                    <a href="{{ route('pembayaran.create', $pesanan) }}" class="btn btn-success px-4">
                        <i class="bi bi-wallet2 me-2"></i>Bayar Sekarang
                    </a>
                @endif
            </div>
        </div>

        <div class="row g-4">
            <!-- Kolom Utama -->
            <div class="col-md-8">
                <!-- Status Pesanan (Progress Stepper) -->
                <div class="card mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-semibold mb-4">Status Transaksi</h5>
                        <div class="d-flex justify-content-between position-relative mb-5 px-md-5">
                            @php
                                $steps = [
                                    'menunggu_konfirmasi' => 'Dipesan',
                                    'dikonfirmasi' => 'Dikonfirmasi',
                                    'dalam_produksi' => 'Produksi',
                                    'siap_diambil' => 'Siap',
                                    'selesai' => 'Selesai'
                                ];
                                $currentIdx = array_search($pesanan->status, array_keys($steps));
                                if ($pesanan->status === 'dibatalkan') $currentIdx = -1;
                            @endphp

                            @foreach($steps as $key => $label)
                                <div class="text-center z-1">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2 {{ array_search($key, array_keys($steps)) <= $currentIdx ? 'bg-primary text-white' : 'bg-light text-muted' }}" style="width: 40px; height: 40px;">
                                        @if(array_search($key, array_keys($steps)) < $currentIdx)
                                            <i class="bi bi-check-lg"></i>
                                        @else
                                            <small>{{ $loop->iteration }}</small>
                                        @endif
                                    </div>
                                    <div class="small fw-semibold {{ array_search($key, array_keys($steps)) <= $currentIdx ? 'text-primary' : 'text-muted' }}">{{ $label }}</div>
                                </div>
                            @endforeach
                            <!-- Progress Line -->
                            <div class="position-absolute top-0 start-0 w-100 mt-4 translate-middle-y z-0" style="height: 2px; padding: 0 10%;">
                                <div class="bg-light w-100 h-100 position-relative">
                                    <div class="bg-primary h-100 transition-all" style="width: {{ $currentIdx >= 0 ? ($currentIdx / (count($steps)-1) * 100) : 0 }}%;"></div>
                                </div>
                            </div>
                        </div>

                        @if($pesanan->status === 'dibatalkan')
                            <div class="alert alert-danger border-0 rounded-3 d-flex align-items-center mb-0">
                                <i class="bi bi-x-circle-fill me-3 fs-4"></i>
                                <div>
                                    <div class="fw-semibold">Pesanan Dibatalkan</div>
                                    <small>Pesanan ini telah dibatalkan dan tidak dapat diproses lebih lanjut.</small>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Daftar Item -->
                <div class="card mb-4">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="fw-semibold mb-0">Rincian Item Cetakan</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4">Produk</th>
                                        <th>Spesifikasi</th>
                                        <th>Harga</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pesanan->detailPesanan as $detail)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="fw-semibold">{{ $detail->produk->nama_produk }}</div>
                                            <small class="text-muted">{{ $detail->jumlah }} {{ $detail->produk->satuan }}</small>
                                        </td>
                                        <td>
                                            <div class="small text-muted">
                                                @if($detail->ukuran) <div>Size: {{ $detail->ukuran }}</div> @endif
                                                @if($detail->bahan) <div>Bahan: {{ $detail->bahan }}</div> @endif
                                                @if($detail->finishing) <div>Finishing: {{ $detail->finishing }}</div> @endif
                                            </div>
                                        </td>
                                        <td>Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                                        <td><span class="fw-semibold">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</span></td>
                                    </tr>
                                    @if($detail->file_desain)
                                    <tr class="bg-light-subtle">
                                        <td colspan="4" class="ps-4 py-2">
                                            <a href="{{ asset('storage/' . $detail->file_desain) }}" target="_blank" class="small text-decoration-none">
                                                <i class="bi bi-file-earmark-image me-1"></i> Lihat File Desain
                                            </a>
                                        </td>
                                    </tr>
                                    @endif
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-light">
                                    <tr>
                                        <td colspan="3" class="text-end fw-semibold">Total Pembayaran:</td>
                                        <td class="ps-3"><h5 class="fw-semibold text-primary mb-0">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</h5></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Catatan & Pesan -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-body p-4">
                                <h6 class="fw-semibold mb-3"><i class="bi bi-chat-left-text me-2 text-primary"></i>Catatan Pelanggan</h6>
                                <p class="small text-muted mb-0">{{ $pesanan->catatan_pelanggan ?? 'Tidak ada catatan.' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-body p-4">
                                <h6 class="fw-semibold mb-3"><i class="bi bi-reply-all me-2 text-primary"></i>Catatan Admin</h6>
                                <p class="small text-muted mb-0">{{ $pesanan->catatan_admin ?? 'Belum ada catatan dari admin.' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kolom Samping -->
            <div class="col-md-4">
                <!-- Info Pelanggan (Hanya Admin) -->
                @if(Auth::user()->role !== 'pelanggan')
                <div class="card mb-4">
                    <div class="card-body p-4">
                        <h6 class="fw-semibold mb-3">Data Pemesan</h6>
                        <div class="d-flex align-items-center mb-3">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($pesanan->user->name) }}&background=random" class="rounded-circle me-3" width="45">
                            <div>
                                <div class="fw-semibold">{{ $pesanan->user->name }}</div>
                                <small class="text-muted">{{ $pesanan->user->email }}</small>
                            </div>
                        </div>
                        <div class="small mb-1"><strong>WhatsApp:</strong> {{ $pesanan->user->no_telepon ?? '-' }}</div>
                        <div class="small"><strong>Alamat:</strong> {{ $pesanan->user->alamat ?? '-' }}</div>
                    </div>
                </div>
                @endif

                <!-- Deadline & Produksi -->
                <div class="card mb-4">
                    <div class="card-body p-4 text-center">
                        <div class="mb-3">
                            <div class="text-muted small text-uppercase fw-semibold mb-1">Target Selesai</div>
                            <h4 class="fw-semibold {{ $pesanan->tanggal_deadline && $pesanan->tanggal_deadline < today() ? 'text-danger' : 'text-primary' }}">
                                {{ $pesanan->tanggal_deadline ? $pesanan->tanggal_deadline->format('d M Y') : 'Belum Ditentukan' }}
                            </h4>
                        </div>
                        <hr>
                        <div class="mt-3">
                            <div class="text-muted small text-uppercase fw-semibold mb-1">Status Produksi</div>
                            <div class="fw-semibold">
                                {{ $pesanan->produksi ? ucwords(str_replace('_', ' ', $pesanan->produksi->status_produksi)) : 'Belum Masuk Produksi' }}
                            </div>
                            @if($pesanan->produksi && $pesanan->produksi->operator)
                                <small class="text-muted">Dikerjakan oleh: {{ $pesanan->produksi->operator->name }}</small>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Pembayaran -->
                <div class="card">
                    <div class="card-body p-4">
                        <h6 class="fw-semibold mb-3">Status Pembayaran</h6>
                        @if($pesanan->pembayaran)
                            <div class="d-flex align-items-center p-3 bg-light rounded-3">
                                <div class="bg-success-subtle text-success p-2 rounded-circle me-3">
                                    <i class="bi bi-cash-stack"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold small">Sudah Dibayar</div>
                                    <div class="text-muted extra-small">Rp {{ number_format($pesanan->pembayaran->jumlah_bayar, 0, ',', '.') }}</div>
                                </div>
                                <a href="{{ route('pembayaran.show', $pesanan->pembayaran) }}" class="ms-auto btn btn-sm btn-link p-0 text-decoration-none">Lihat</a>
                            </div>
                        @else
                            <div class="text-center py-3">
                                <div class="text-warning mb-2"><i class="bi bi-exclamation-circle fs-3"></i></div>
                                <div class="fw-semibold small">Belum Ada Pembayaran</div>
                                @if(Auth::user()->role === 'pelanggan')
                                    <a href="{{ route('pembayaran.create', $pesanan) }}" class="btn btn-sm btn-outline-primary mt-3 px-4">Bayar Sekarang</a>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if(Auth::user()->role === 'admin')
<!-- Update Status Modal -->
<div class="modal fade" id="updateStatusModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-semibold">Update Status Pesanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('pesanan.update', $pesanan) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Status Pesanan</label>
                        <select name="status" class="form-select">
                            @foreach($steps as $key => $label)
                                <option value="{{ $key }}" {{ $pesanan->status == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                            <option value="dibatalkan" {{ $pesanan->status == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tanggal Deadline</label>
                        <input type="date" name="tanggal_deadline" class="form-control" value="{{ $pesanan->tanggal_deadline ? $pesanan->tanggal_deadline->format('Y-m-d') : '' }}">
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-semibold">Catatan Admin</label>
                        <textarea name="catatan_admin" class="form-control" rows="3">{{ $pesanan->catatan_admin }}</textarea>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<style>
    .extra-small { font-size: 0.75rem; }
    .transition-all { transition: all 0.5s ease; }
</style>
@endsection
