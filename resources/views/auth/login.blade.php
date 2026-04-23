@extends('layouts.auth')

@section('title', 'Login')

@section('content')
<div class="text-center mb-5">
    <h3 class="fw-bold text-dark">Selamat Datang</h3>
    <p class="text-muted small">Silakan masuk ke akun Anda</p>
</div>

<form action="{{ route('login') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label for="email" class="form-label">Alamat Email</label>
        <div class="input-group">
            <span class="input-group-text">
                <i class="bi bi-envelope"></i>
            </span>
            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" placeholder="nama@email.com" value="{{ old('email') }}" required autofocus>
        </div>
        @error('email')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-4">
        <div class="d-flex justify-content-between align-items-center mb-1">
            <label for="password" class="form-label mb-0">Kata Sandi</label>
        </div>
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

    <div class="mb-4">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="remember" id="remember">
            <label class="form-check-label small text-muted" for="remember">
                Ingat saya di perangkat ini
            </label>
        </div>
    </div>

    <div class="d-grid mb-4">
        <button type="submit" class="btn btn-primary">Masuk ke Dashboard</button>
    </div>

    <div class="text-center">
        <p class="text-muted small mb-0">Belum punya akun? <a href="{{ route('register') }}" class="text-primary text-decoration-none">Daftar Sekarang</a></p>
    </div>
</form>

<style>
    .extra-small { font-size: 0.75rem; }
</style>
@endsection
