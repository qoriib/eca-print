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

        <div class="card">
            <div class="card-body">
                <form action="{{ route('pengaturan.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row g-4">
                        <div class="col-md-12">
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

                            <hr class="my-4">
                            <h5 class="fs-6 fw-bold mb-3">Informasi Rekening Bank 1</h5>
                            <div class="row g-3 mb-4">
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold small">Nama Bank</label>
                                    <input type="text" name="bank_1" class="form-control" value="{{ old('bank_1', $pengaturan->bank_1 ?? '') }}" placeholder="Contoh: Bank BCA">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold small">Nomor Rekening</label>
                                    <input type="text" name="norek_1" class="form-control font-monospace" value="{{ old('norek_1', $pengaturan->norek_1 ?? '') }}" placeholder="123456789">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold small">Atas Nama</label>
                                    <input type="text" name="atas_nama_1" class="form-control" value="{{ old('atas_nama_1', $pengaturan->atas_nama_1 ?? '') }}" placeholder="Nama Pemilik Rekening">
                                </div>
                            </div>

                            <h5 class="fs-6 fw-bold mb-3">Informasi Rekening Bank 2 (Opsional)</h5>
                            <div class="row g-3 mb-4">
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold small">Nama Bank</label>
                                    <input type="text" name="bank_2" class="form-control" value="{{ old('bank_2', $pengaturan->bank_2 ?? '') }}" placeholder="Contoh: Bank Mandiri">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold small">Nomor Rekening</label>
                                    <input type="text" name="norek_2" class="form-control font-monospace" value="{{ old('norek_2', $pengaturan->norek_2 ?? '') }}" placeholder="987654321">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold small">Atas Nama</label>
                                    <input type="text" name="atas_nama_2" class="form-control" value="{{ old('atas_nama_2', $pengaturan->atas_nama_2 ?? '') }}" placeholder="Nama Pemilik Rekening">
                                </div>
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
@endsection
