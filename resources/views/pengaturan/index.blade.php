@extends('layouts.dashboard')

@section('title', 'Pengaturan Profil Usaha')
@section('role_name', 'Administrator')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="mb-4">
            <h3 class="fs-5 fw-semibold mb-1">Pengaturan Profil Usaha</h3>
            <p class="text-muted">Informasi ini akan ditampilkan pada laporan, invoice, dan identitas sistem.</p>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4 p-md-5">
                <form action="{{ route('pengaturan.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row g-4">
                        <div class="col-md-4 text-center border-end pe-md-5">
                            <label class="form-label d-block fw-semibold mb-3">Logo Usaha</label>
                            <div class="mb-3">
                                @if($pengaturan && $pengaturan->logo)
                                    <img src="{{ asset('storage/' . $pengaturan->logo) }}" id="logo-preview" class="img-fluid rounded border p-2 mb-3" style="max-height: 150px;">
                                @else
                                    <img src="https://placehold.co/200x200?text=Logo+Usaha" id="logo-preview" class="img-fluid rounded border p-2 mb-3" style="max-height: 150px;">
                                @endif
                            </div>
                            <div class="input-group input-group-sm">
                                <input type="file" name="logo" class="form-control" id="logo-input" accept="image/*">
                            </div>
                            <small class="text-muted d-block mt-2">Format: JPG, PNG. Maks 2MB.</small>
                        </div>

                        <div class="col-md-8 ps-md-5">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Nama Usaha / Percetakan</label>
                                <input type="text" name="nama_usaha" class="form-control @error('nama_usaha') is-invalid @enderror" 
                                    value="{{ old('nama_usaha', $pengaturan->nama_usaha ?? 'Eca-Print') }}" required>
                                @error('nama_usaha') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">No. WhatsApp / HP</label>
                                    <input type="text" name="no_hp" class="form-control" 
                                        value="{{ old('no_hp', $pengaturan->no_hp ?? '') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Email Usaha</label>
                                    <input type="email" name="email" class="form-control" 
                                        value="{{ old('email', $pengaturan->email ?? '') }}">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Alamat Lengkap</label>
                                <textarea name="alamat" class="form-control" rows="3">{{ old('alamat', $pengaturan->alamat ?? '') }}</textarea>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold">Catatan Footer (Invoice/Laporan)</label>
                                <textarea name="catatan_footer" class="form-control" rows="2" 
                                    placeholder="Contoh: Terima kasih atas kepercayaan Anda.">{{ old('catatan_footer', $pengaturan->catatan_footer ?? '') }}</textarea>
                                <small class="text-muted">Pesan ini akan muncul di bagian bawah dokumen cetak.</small>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary py-2 fw-semibold">
                                    <i class="bi bi-check-circle me-2"></i>Simpan Perubahan
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('logo-input').addEventListener('change', function(e) {
        if (e.target.files && e.target.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('logo-preview').src = e.target.result;
            }
            reader.readAsDataURL(e.target.files[0]);
        }
    });
</script>
@endpush
@endsection
