@extends('layouts.dashboard')

@section('title', 'Edit Pengguna')
@section('role_name', 'Administrator')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('users.index') }}" class="btn btn-light me-3">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <h3 class="fs-5 fw-semibold mb-0">Edit Data Pengguna</h3>
            </div>

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('users.update', $user) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                    name="name" value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="form-label">Alamat Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                                    name="email" value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="role" class="form-label">Role Pengguna</label>
                                <select class="form-select @error('role') is-invalid @enderror" id="role" name="role"
                                    required>
                                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>
                                        Administrator</option>
                                    <option value="operator" {{ old('role', $user->role) == 'operator' ? 'selected' : '' }}>
                                        Operator Produksi</option>
                                    <option value="pelanggan" {{ old('role', $user->role) == 'pelanggan' ? 'selected' : '' }}>
                                        Pelanggan</option>
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="no_telepon" class="form-label">No. Telepon (WhatsApp)</label>
                                <input type="text" class="form-control @error('no_telepon') is-invalid @enderror"
                                    id="no_telepon" name="no_telepon" value="{{ old('no_telepon', $user->no_telepon) }}">
                                @error('no_telepon')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label for="alamat" class="form-label">Alamat Lengkap</label>
                                <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat"
                                    name="alamat" rows="3">{{ old('alamat', $user->alamat) }}</textarea>
                                @error('alamat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>


                            <div class="col-md-6">
                                <label for="password" class="form-label">Password Baru</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    id="password" name="password" placeholder="Minimal 6 karakter">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label">Konfirmasi
                                    Password</label>
                                <input type="password" class="form-control" id="password_confirmation"
                                    name="password_confirmation" placeholder="Ulangi password baru">
                            </div>
                        </div>

                        <div class="col-12 mt-3">
                            <div class="alert alert-info py-2 small border-0 mb-0">
                                <i class="bi bi-info-circle me-1"></i> Kosongkan password jika tidak ingin mengubahnya.
                            </div>
                        </div>

                        <div class="mt-4 d-flex gap-2">
                            <button type="submit" class="btn btn-primary px-5 py-2 fw-semibold">
                                <i class="bi bi-save me-2"></i>Perbarui Pengguna
                            </button>
                            <a href="{{ route('users.index') }}" class="btn btn-light px-4 py-2">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection