@extends('layouts.dashboard')

@section('title', 'Kategori Produk')
@section('role_name', 'Administrator')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fs-5 fw-semibold mb-0">Kategori Produk</h3>
        <a href="{{ route('kategori-produk.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>Tambah Kategori
        </a>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4" style="width: 50px;">#</th>
                            <th>Nama Kategori</th>
                            <th>Deskripsi</th>
                            <th class="text-center">Jumlah Produk</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kategori as $item)
                            <tr class="text-nowrap">
                                <td class="ps-4 text-muted">
                                    {{ ($kategori->currentPage() - 1) * $kategori->perPage() + $loop->iteration }}</td>
                                <td><span class="fw-medium">{{ $item->nama_kategori }}</span></td>
                                <td>{{ Str::limit($item->deskripsi, 50) ?? '-' }}</td>
                                <td class="text-center">
                                    <span class="badge bg-light text-primary border px-3">
                                        {{ $item->produk_count }} Produk
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="btn-group">
                                        <a href="{{ route('kategori-produk.show', $item) }}"
                                            class="btn btn-sm btn-light rounded-circle me-1" title="Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('kategori-produk.edit', $item) }}"
                                            class="btn btn-sm btn-light rounded-circle me-1" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-light text-danger rounded-circle"
                                            data-bs-toggle="modal" data-bs-target="#deleteModal{{ $item->id }}" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>

                                    <!-- Delete Modal -->
                                    <div class="modal fade" id="deleteModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content border-0 shadow">
                                                <div class="modal-header border-0">
                                                    <h5 class="modal-title fw-semibold">Konfirmasi Hapus</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body text-start">
                                                    Apakah Anda yakin ingin menghapus kategori
                                                    <strong>{{ $item->nama_kategori }}</strong>?
                                                    @if($item->produk_count > 0)
                                                        <div class="alert alert-danger mt-2 py-2 small border-0">
                                                            <i class="bi bi-exclamation-octagon me-1"></i> Kategori ini tidak dapat
                                                            dihapus karena masih memiliki <strong>{{ $item->produk_count }}
                                                                produk</strong>.
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="modal-footer border-0">
                                                    <button type="button" class="btn btn-light px-4"
                                                        data-bs-dismiss="modal">Batal</button>
                                                    @if($item->produk_count == 0)
                                                        <form action="{{ route('kategori-produk.destroy', $item) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger px-4">Hapus</button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">Belum ada data kategori</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($kategori->hasPages())
                <div class="p-4 border-top">
                    {{ $kategori->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection