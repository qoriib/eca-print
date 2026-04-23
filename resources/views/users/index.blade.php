@extends('layouts.dashboard')

@section('title', 'Manajemen Pengguna')
@section('role_name', 'Administrator')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fs-5 fw-semibold mb-0">Manajemen Pengguna</h3>
        <a href="{{ route('users.create') }}" class="btn btn-primary">
            <i class="bi bi-person-plus me-2"></i>Tambah Pengguna
        </a>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Nama</th>
                            <th>Email</th>
                            <th>No. Telepon</th>
                            <th>Role</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr class="text-nowrap">
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=random"
                                            class="rounded-circle me-3" width="40">
                                        <div>
                                            <div class="fw-semibold">{{ $user->name }}</div>
                                            <small class="text-muted">Terdaftar: <span
                                                    class="font-monospace">{{ $user->created_at->format('d/m/Y') }}</span></small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td class="font-monospace">{{ $user->no_telepon ?? '-' }}</td>
                                <td>
                                    @php
                                        $badges = [
                                            'admin' => 'bg-danger',
                                            'operator' => 'bg-primary',
                                            'pelanggan' => 'bg-success'
                                        ];
                                    @endphp
                                    <span class="badge {{ $badges[$user->role] }} text-capitalize px-3">
                                        {{ $user->role }}
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="btn-group">
                                        <a href="{{ route('users.show', $user) }}"
                                            class="btn btn-sm btn-light me-1" title="Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('users.edit', $user) }}"
                                            class="btn btn-sm btn-light me-1" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        @if($user->id !== Auth::id())
                                            <button type="button" class="btn btn-sm btn-light text-danger"
                                                data-bs-toggle="modal" data-bs-target="#deleteModal{{ $user->id }}" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        @endif
                                    </div>

                                    <!-- Delete Modal -->
                                    @if($user->id !== Auth::id())
                                        <div class="modal fade" id="deleteModal{{ $user->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content border-0 shadow">
                                                    <div class="modal-header border-0">
                                                        <h5 class="modal-title fw-semibold">Konfirmasi Hapus</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body text-start">
                                                        Apakah Anda yakin ingin menghapus pengguna
                                                        <strong>{{ $user->name }}</strong>?
                                                        @if($user->pesanan()->count() > 0)
                                                            <div class="alert alert-warning mt-2 py-2 small">
                                                                <i class="bi bi-exclamation-triangle me-1"></i> Pengguna ini memiliki
                                                                data pesanan dan mungkin tidak dapat dihapus.
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="modal-footer border-0">
                                                        <button type="button" class="btn btn-light px-4"
                                                            data-bs-dismiss="modal">Batal</button>
                                                        <form action="{{ route('users.destroy', $user) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger px-4">Hapus</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">Belum ada data pengguna</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($users->hasPages())
                <div class="p-4 border-top">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection