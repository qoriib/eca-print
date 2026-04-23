@extends('layouts.dashboard')

@section('title', 'Daftar Detail Pesanan')
@section('role_name', 'Administrator')

@section('content')
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('pesanan.show', $pesanan) }}" class="btn btn-light rounded-circle me-3">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h3 class="fw-bold mb-0">Detail Item Pesanan: {{ $pesanan->kode_pesanan }}</h3>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Produk</th>
                        <th>Ukuran</th>
                        <th>Bahan</th>
                        <th>Finishing</th>
                        <th>Jumlah</th>
                        <th>Subtotal</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pesanan->detailPesanan as $detail)
                    <tr>
                        <td class="ps-4">
                            <div class="fw-bold">{{ $detail->produk->nama_produk }}</div>
                            <small class="text-muted">@Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</small>
                        </td>
                        <td>{{ $detail->ukuran ?? '-' }}</td>
                        <td>{{ $detail->bahan ?? '-' }}</td>
                        <td>{{ $detail->finishing ?? '-' }}</td>
                        <td>{{ $detail->jumlah }} {{ $detail->produk->satuan }}</td>
                        <td><span class="fw-bold text-primary">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</span></td>
                        <td class="text-end pe-4">
                            @if($pesanan->status === 'menunggu_konfirmasi')
                                <form action="{{ route('detail-pesanan.destroy', $detail) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-light text-danger rounded-circle" onclick="return confirm('Hapus item ini?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
