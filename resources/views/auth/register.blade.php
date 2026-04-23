@extends('layouts.auth')

@section('title', 'Daftar Akun')

@section('content')
<div class="text-center mb-5">
    <h3 class="fw-bold text-dark">Buat Akun</h3>
    <p class="text-muted small">Bergabunglah untuk mulai memesan cetakan</p>
</div>

<form action="{{ route('register') }}" method="POST">
    @csrf
    <div class="row">
        <div class="col-md-12 mb-3">
            <label for="name" class="form-label">Nama Lengkap</label>
            <div class="input-group">
                <span class="input-group-text">
                    <i class="bi bi-person"></i>
                </span>
                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" placeholder="Nama lengkap Anda" value="{{ old('name') }}" required autofocus>
            </div>
            @error('name')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-12 mb-3">
            <label for="email" class="form-label">Alamat Email</label>
            <div class="input-group">
                <span class="input-group-text">
                    <i class="bi bi-envelope"></i>
                </span>
                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" placeholder="nama@email.com" value="{{ old('email') }}" required>
            </div>
            @error('email')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6 mb-3">
            <label for="password" class="form-label">Kata Sandi</label>
            <div class="input-group">
                <span class="input-group-text">
                    <i class="bi bi-lock"></i>
                </span>
                <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="••••••••" required>
            </div>
            @error('password')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6 mb-3">
            <label for="password_confirmation" class="form-label">Konfirmasi</label>
            <div class="input-group">
                <span class="input-group-text">
                    <i class="bi bi-shield-check"></i>
                </span>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="••••••••" required>
            </div>
        </div>

        <div class="col-md-12 mb-3">
            <label for="no_telepon" class="form-label">Nomor WhatsApp</label>
            <div class="input-group">
                <span class="input-group-text">
                    <i class="bi bi-whatsapp"></i>
                </span>
                <input type="text" name="no_telepon" id="no_telepon" class="form-control @error('no_telepon') is-invalid @enderror" placeholder="08xxxxxx" value="{{ old('no_telepon') }}">
            </div>
            @error('no_telepon')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-12 mb-4">
            <label for="alamat" class="form-label">Alamat Lengkap</label>
            <textarea name="alamat" id="alamat" class="form-control @error('alamat') is-invalid @enderror" rows="2" placeholder="Alamat pengiriman...">{{ old('alamat') }}</textarea>
            @error('alamat')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="d-grid mb-4">
        <button type="submit" class="btn btn-primary">Daftar Akun Baru</button>
    </div>

    <div class="text-center">
        <p class="text-muted small mb-0">Sudah punya akun? <a href="{{ route('login') }}" class="text-primary text-decoration-none">Login di sini</a></p>
    </div>
</form>
@endsection
