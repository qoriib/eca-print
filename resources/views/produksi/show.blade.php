@extends('layouts.dashboard')

@section('title', 'Detail Produksi')
@section('role_name', Auth::user()->role === 'admin' ? 'Administrator' : 'Operator')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-11">
        <div class="d-flex align-items-center mb-4">
            <a href="{{ route('produksi.index') }}" class="btn btn-light me-3">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h3 class="fs-5 fw-semibold mb-0">Detail Produksi: {{ $produksi->pesanan->kode_pesanan }}</h3>
        </div>

        <div class="row g-4">
            <!-- Sidebar Informasi -->
            <div class="col-md-4">
                <!-- Status & Update Form -->
                <div class="card mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-semibold mb-3">Update Status</h5>
                        
                        @if($produksi->status_produksi === 'antrian' && Auth::user()->role === 'operator')
                            <div class="alert alert-info border-0 small mb-3">
                                <i class="bi bi-info-circle me-1"></i> Klik tombol di bawah untuk mulai mengerjakan pesanan ini.
                            </div>
                            <form action="{{ route('produksi.ambil', $produksi) }}" method="POST" class="d-grid">
                                @csrf
                                <button type="submit" class="btn btn-primary fw-semibold">Ambil Pekerjaan</button>
                            </form>
                        @else
                            <form action="{{ route('produksi.update', $produksi) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold text-muted text-uppercase">Status Produksi</label>
                                    <select name="status_produksi" class="form-select @error('status_produksi') is-invalid @enderror">
                                        <option value="antrian" {{ $produksi->status_produksi == 'antrian' ? 'selected' : '' }}>Antrian</option>
                                        <option value="proses" {{ $produksi->status_produksi == 'proses' ? 'selected' : '' }}>Proses</option>
                                        <option value="quality_check" {{ $produksi->status_produksi == 'quality_check' ? 'selected' : '' }}>Quality Check</option>
                                        <option value="selesai" {{ $produksi->status_produksi == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold text-muted text-uppercase">Catatan Produksi</label>
                                    <textarea name="catatan_produksi" class="form-control" rows="3" placeholder="Tambahkan catatan jika ada...">{{ old('catatan_produksi', $produksi->catatan_produksi) }}</textarea>
                                </div>

                                @if(Auth::user()->role === 'admin')
                                    <div class="mb-3">
                                        <label class="form-label small fw-semibold text-muted text-uppercase">Operator</label>
                                        <select name="operator_id" class="form-select">
                                            <option value="">Pilih Operator</option>
                                            @php $operators = \App\Models\User::where('role', 'operator')->get(); @endphp
                                            @foreach($operators as $op)
                                                <option value="{{ $op->id }}" {{ $produksi->operator_id == $op->id ? 'selected' : '' }}>{{ $op->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @else
                                    <input type="hidden" name="operator_id" value="{{ $produksi->operator_id }}">
                                @endif

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary fw-semibold">Perbarui Status</button>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>

                <!-- Info Pelanggan -->
                <div class="card">
                    <div class="card-body p-4">
                        <h5 class="fw-semibold mb-3">Informasi Pelanggan</h5>
                        <div class="d-flex align-items-center mb-3">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($produksi->pesanan->user->name) }}&background=random" class="rounded-circle me-3" width="45">
                            <div>
                                <div class="fw-semibold">{{ $produksi->pesanan->user->name }}</div>
                                <small class="text-muted">{{ $produksi->pesanan->user->email }}</small>
                            </div>
                        </div>
                        <div class="mb-2">
                            <i class="bi bi-whatsapp text-success me-2"></i> {{ $produksi->pesanan->user->no_telepon ?? '-' }}
                        </div>
                        <div class="small text-muted">
                            <i class="bi bi-geo-alt me-2"></i> {{ $produksi->pesanan->user->alamat ?? '-' }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detail Item Pesanan -->
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="fw-semibold mb-0">Item Cetakan</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4">Produk</th>
                                        <th>Spesifikasi</th>
                                        <th>Jumlah</th>
                                        <th class="text-end pe-4">Desain</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($produksi->pesanan->detailPesanan as $item)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="fw-semibold">{{ $item->produk->nama_produk }}</div>
                                        </td>
                                        <td>
                                            <div class="small">
                                                @if($item->ukuran) <div><strong>Ukuran:</strong> {{ $item->ukuran }}</div> @endif
                                                @if($item->bahan) <div><strong>Bahan:</strong> {{ $item->bahan }}</div> @endif
                                                @if($item->finishing) <div><strong>Finishing:</strong> {{ $item->finishing }}</div> @endif
                                            </div>
                                        </td>
                                        <td>{{ $item->jumlah }} {{ $item->produk->satuan }}</td>
                                        <td class="text-end pe-4">
                                            @if($item->file_desain)
                                                <a href="{{ asset('storage/' . $item->file_desain) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-download me-1"></i> Unduh File
                                                </a>
                                            @else
                                                <span class="text-muted small">Tidak ada file</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @if($item->keterangan)
                                    <tr class="bg-light">
                                        <td colspan="4" class="ps-4 py-2">
                                            <small class="text-muted"><strong>Keterangan:</strong> {{ $item->keterangan }}</small>
                                        </td>
                                    </tr>
                                    @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body p-4">
                        <h5 class="fw-semibold mb-3">Linimasa Produksi</h5>
                        <div class="row text-center g-3">
                            <div class="col-6 col-md-3">
                                <div class="p-3 bg-light">
                                    <div class="text-muted small mb-1 text-uppercase fw-semibold">Tanggal Masuk</div>
                                    <div class="fw-semibold font-monospace">{{ $produksi->pesanan->tanggal_pesan->format('d/m/Y') }}</div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="p-3 bg-light">
                                    <div class="text-muted small mb-1 text-uppercase fw-semibold">Deadline</div>
                                    <div class="fw-semibold font-monospace text-danger">{{ $produksi->pesanan->tanggal_deadline ? $produksi->pesanan->tanggal_deadline->format('d/m/Y') : '-' }}</div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="p-3 bg-light">
                                    <div class="text-muted small mb-1 text-uppercase fw-semibold">Mulai Kerja</div>
                                    <div class="fw-semibold font-monospace">{{ $produksi->tanggal_mulai ? $produksi->tanggal_mulai->format('d/m/Y') : '-' }}</div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="p-3 bg-light">
                                    <div class="text-muted small mb-1 text-uppercase fw-semibold">Selesai</div>
                                    <div class="fw-semibold font-monospace text-success">{{ $produksi->tanggal_selesai ? $produksi->tanggal_selesai->format('d/m/Y') : '-' }}</div>
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
