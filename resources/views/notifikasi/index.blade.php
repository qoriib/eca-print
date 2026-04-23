@extends('layouts.dashboard')

@section('title', 'Notifikasi Saya')
@section('role_name', ucfirst(Auth::user()->role))

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold mb-0">Notifikasi</h3>
            @if($notifikasi->where('is_read', false)->count() > 0)
                <form action="{{ route('notifikasi.read-all') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline-primary rounded-pill btn-sm">
                        <i class="bi bi-check-all me-1"></i>Tandai Semua Dibaca
                    </button>
                </form>
            @endif
        </div>

        <div class="card overflow-hidden">
            <div class="list-group list-group-flush">
                @forelse($notifikasi as $notif)
                    <div class="list-group-item p-4 border-start border-4 {{ $notif->is_read ? 'border-light bg-white' : ($notif->tipe === 'sukses' ? 'border-success bg-success-subtle' : ($notif->tipe === 'peringatan' ? 'border-warning bg-warning-subtle' : 'border-primary bg-primary-subtle')) }}">
                        <div class="d-flex align-items-start">
                            <div class="flex-shrink-0">
                                @php
                                    $icons = [
                                        'sukses' => 'bi-check-circle-fill text-success',
                                        'peringatan' => 'bi-exclamation-triangle-fill text-warning',
                                        'info' => 'bi-info-circle-fill text-primary',
                                    ];
                                    $icon = $icons[$notif->tipe] ?? 'bi-bell-fill text-primary';
                                @endphp
                                <i class="bi {{ $icon }} fs-4"></i>
                            </div>
                            <div class="ms-3 flex-grow-1">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <h6 class="fw-bold mb-0 {{ $notif->is_read ? '' : 'text-dark' }}">
                                        {{ $notif->judul }}
                                    </h6>
                                    <small class="text-muted">{{ $notif->created_at->diffForHumans() }}</small>
                                </div>
                                <p class="mb-0 {{ $notif->is_read ? 'text-muted' : 'text-dark' }}">
                                    {{ $notif->pesan }}
                                </p>
                                
                                <div class="mt-3 d-flex gap-2">
                                    @if(!$notif->is_read)
                                        <form action="{{ route('notifikasi.read', $notif) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-link p-0 text-decoration-none">Tandai Dibaca</button>
                                        </form>
                                    @endif
                                    <form action="{{ route('notifikasi.destroy', $notif) }}" method="POST" onsubmit="return confirm('Hapus notifikasi ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-link p-0 text-danger text-decoration-none">Hapus</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-5 text-center text-muted">
                        <i class="bi bi-bell-slash fs-1 mb-3 d-block opacity-25"></i>
                        Belum ada notifikasi untuk Anda.
                    </div>
                @endforelse
            </div>
        </div>

        <div class="mt-4">
            {{ $notifikasi->links() }}
        </div>
    </div>
</div>
@endsection
