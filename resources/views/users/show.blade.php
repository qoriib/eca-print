@extends('layouts.dashboard')

@section('title', 'Detail Pengguna')
@section('role_name', 'Administrator')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="d-flex align-items-center mb-4">
            <a href="{{ route('users.index') }}" class="btn btn-light rounded-circle me-3">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h3 class="fw-bold mb-0">Detail Profil Pengguna</h3>
            <div class="ms-auto">
                <a href="{{ route('users.edit', $user) }}" class="btn btn-primary rounded-pill px-4">
                    <i class="bi bi-pencil me-2"></i>Edit Profil
                </a>
            </div>
        </div>

        <div class="row g-4">
            <!-- Informasi Utama -->
            <div class="col-md-4">
                <div class="card text-center p-4">
                    <div class="mb-3">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&size=128&background=random" class="rounded-circle shadow-sm" width="120">
                    </div>
                    <h4 class="fw-bold mb-1">{{ $user->name }}</h4>
                    <p class="text-muted mb-3">{{ $user->email }}</p>
                    <span class="badge {{ $user->role === 'admin' ? 'bg-danger' : ($user->role === 'operator' ? 'bg-primary' : 'bg-success') }} rounded-pill px-4 py-2 text-capitalize fs-6">
                        {{ $user->role }}
                    </span>
                    <hr class="my-4">
                    <div class="text-start">
                        <div class="mb-3">
                            <label class="text-muted small text-uppercase fw-bold">Nomor Telepon</label>
                            <div class="fw-semibold">{{ $user->no_telepon ?? 'Tidak ada data' }}</div>
                        </div>
                        <div class="mb-0">
                            <label class="text-muted small text-uppercase fw-bold">Alamat</label>
                            <div class="fw-semibold">{{ $user->alamat ?? 'Tidak ada data' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Riwayat Pesanan -->
            <div class="col-md-8">
                <div class="card h-100">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="fw-bold mb-0">Riwayat Pesanan</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4">Kode</th>
                                        <th>Tanggal</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th class="text-end pe-4">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($user->pesanan as $pesanan)
                                    <tr>
                                        <td class="ps-4 fw-bold text-primary">{{ $pesanan->kode_pesanan }}</td>
                                        <td>{{ $pesanan->tanggal_pesan->format('d/m/Y') }}</td>
                                        <td>Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</td>
                                        <td>
                                            <span class="badge bg-light text-dark border rounded-pill text-capitalize">
                                                {{ str_replace('_', ' ', $pesanan->status) }}
                                            </span>
                                        </td>
                                        <td class="text-end pe-4">
                                            <a href="{{ route('pesanan.show', $pesanan) }}" class="btn btn-sm btn-light rounded-circle">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-muted">Belum ada riwayat pesanan</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
