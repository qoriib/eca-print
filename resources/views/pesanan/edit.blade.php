@extends('layouts.dashboard')

@section('title', 'Edit Pesanan')
@section('role_name', 'Pelanggan')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="d-flex align-items-center mb-4">
            <a href="{{ route('pesanan.show', $pesanan) }}" class="btn btn-light me-3">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h3 class="fs-5 fw-semibold mb-0">Edit Pesanan: {{ $pesanan->kode_pesanan }}</h3>
        </div>

        <div class="alert alert-info border-0 shadow-sm mb-4">
            <i class="bi bi-info-circle-fill me-2"></i> Anda hanya dapat mengubah informasi dasar pesanan. Untuk mengubah daftar item cetakan, silakan hubungi admin atau buat pesanan baru.
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('pesanan.update', $pesanan) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <label for="tanggal_deadline" class="form-label">Harapan Tanggal Selesai</label>
                        <input type="date" name="tanggal_deadline" id="tanggal_deadline" class="form-control" 
                               value="{{ old('tanggal_deadline', $pesanan->tanggal_deadline ? $pesanan->tanggal_deadline->format('Y-m-d') : '') }}"
                               min="{{ date('Y-m-d') }}">
                    </div>

                    <div class="mb-4">
                        <label for="catatan_pelanggan" class="form-label">Catatan Untuk Admin</label>
                        <textarea name="catatan_pelanggan" id="catatan_pelanggan" class="form-control" rows="4">{{ old('catatan_pelanggan', $pesanan->catatan_pelanggan) }}</textarea>
                    </div>

                    <div class="d-grid gap-2 mt-5">
                        <button type="submit" class="btn btn-primary btn-lg fw-semibold">Perbarui Pesanan</button>
                        <a href="{{ route('pesanan.show', $pesanan) }}" class="btn btn-light btn-lg">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
