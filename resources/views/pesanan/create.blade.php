@extends('layouts.dashboard')

@section('title', 'Buat Pesanan Baru')
@section('role_name', 'Pelanggan')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-11">
        <div class="d-flex align-items-center mb-4">
            <a href="{{ route('pesanan.index') }}" class="btn btn-light rounded-circle me-3">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h3 class="fs-5 fw-semibold mb-0">Form Pemesanan Cetak</h3>
        </div>

        <form action="{{ route('pesanan.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="row g-4">
                <!-- Kiri: Daftar Item -->
                <div class="col-md-8">
                    <div id="items-container">
                        <!-- Item Template (Item Pertama) -->
                        <div class="card mb-4 item-card">
                            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                                <h6 class="fw-semibold mb-0">Item #1</h6>
                            </div>
                            <div class="card-body p-4">
                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <label class="form-label fw-semibold small">Pilih Produk</label>
                                        <select name="items[0][produk_id]" class="form-select produk-select" required>
                                            <option value="" disabled selected>Pilih produk yang ingin dicetak</option>
                                            @foreach($produk as $p)
                                                <option value="{{ $p->id }}" data-harga="{{ $p->harga_satuan }}" data-satuan="{{ $p->satuan }}">
                                                    {{ $p->nama_produk }} - Rp {{ number_format($p->harga_satuan, 0, ',', '.') }}/{{ $p->satuan }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold small">Jumlah (Qty)</label>
                                        <div class="input-group">
                                            <input type="number" name="items[0][jumlah]" class="form-control item-qty" min="1" value="1" required>
                                            <span class="input-group-text bg-white satuan-label">unit</span>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <label class="form-label fw-semibold small">Ukuran (Opsional)</label>
                                        <input type="text" name="items[0][ukuran]" class="form-control" placeholder="Contoh: A4, 2x3 meter, 9x5 cm">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small">Bahan (Opsional)</label>
                                        <input type="text" name="items[0][bahan]" class="form-control" placeholder="Contoh: Art Paper 260gr, Flexy 280gr">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small">Finishing (Opsional)</label>
                                        <input type="text" name="items[0][finishing]" class="form-control" placeholder="Contoh: Laminating Glossy, Mata Ayam">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fw-semibold small">Upload Desain (Opsional)</label>
                                        <input type="file" name="items[0][file_desain]" class="form-control" accept=".jpg,.jpeg,.png,.pdf,.ai,.cdr">
                                        <small class="text-muted">Format: JPG, PNG, PDF, AI, CDR (Maks. 10MB)</small>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fw-semibold small">Keterangan Tambahan</label>
                                        <textarea name="items[0][keterangan]" class="form-control" rows="2" placeholder="Catatan khusus untuk item ini..."></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="button" id="add-item" class="btn btn-outline-primary w-100 py-3 border-dashed mb-4">
                        <i class="bi bi-plus-circle me-2"></i>Tambah Item Cetakan Lainnya
                    </button>
                </div>

                <!-- Kanan: Ringkasan & Deadline -->
                <div class="col-md-4">
                    <div class="sticky-top" style="top: 2rem; z-index: 1;">
                        <div class="card mb-4">
                            <div class="card-body p-4">
                                <h5 class="fw-semibold mb-4 text-center">Ringkasan Pesanan</h5>
                                
                                <div class="mb-4">
                                    <label for="tanggal_deadline" class="form-label fw-semibold small">Tanggal Deadline (Harapan Selesai)</label>
                                    <input type="date" name="tanggal_deadline" id="tanggal_deadline" class="form-control" min="{{ date('Y-m-d') }}">
                                    <small class="text-muted">Kami akan berusaha menyelesaikannya tepat waktu.</small>
                                </div>

                                <div class="mb-4">
                                    <label for="catatan_pelanggan" class="form-label fw-semibold small">Catatan Pesanan (Global)</label>
                                    <textarea name="catatan_pelanggan" id="catatan_pelanggan" class="form-control" rows="3" placeholder="Pesan untuk admin..."></textarea>
                                </div>

                                <div class="bg-light p-3 rounded-3 mb-4">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted">Estimasi Total</span>
                                        <span class="fw-semibold" id="total-estimasi">Rp 0</span>
                                    </div>
                                    <small class="text-muted d-block text-center mt-2" style="font-size: 0.7rem;">*Harga final akan dikonfirmasi oleh Admin.</small>
                                </div>

                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary btn-lg fw-semibold">Proses Pesanan</button>
                                    <a href="{{ route('pesanan.index') }}" class="btn btn-light">Batal</a>
                                </div>
                            </div>
                        </div>

                        <div class="card border-0 bg-primary-subtle text-primary">
                            <div class="card-body p-4">
                                <div class="d-flex">
                                    <i class="bi bi-info-circle-fill fs-4 me-3"></i>
                                    <small>Setelah membuat pesanan, silakan tunggu konfirmasi Admin untuk melakukan pembayaran.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
    .border-dashed {
        border-style: dashed !important;
    }
</style>

@push('scripts')
<script>
    let itemCount = 1;

    document.getElementById('add-item').addEventListener('click', function() {
        const container = document.getElementById('items-container');
        const template = container.querySelector('.item-card').cloneNode(true);
        
        // Reset values and update names
        template.querySelector('h6').textContent = `Item #${itemCount + 1}`;
        
        template.querySelectorAll('[name]').forEach(input => {
            const name = input.getAttribute('name');
            input.setAttribute('name', name.replace(/\[\d+\]/, `[${itemCount}]`));
            if (input.tagName === 'SELECT' || input.tagName === 'INPUT' || input.tagName === 'TEXTAREA') {
                input.value = (input.type === 'number') ? 1 : '';
            }
        });

        // Add remove button if not exists
        if (!template.querySelector('.btn-remove-item')) {
            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.className = 'btn btn-sm btn-link text-danger btn-remove-item';
            removeBtn.innerHTML = '<i class="bi bi-trash"></i> Hapus';
            removeBtn.onclick = function() {
                template.remove();
                calculateTotal();
            };
            template.querySelector('.card-header').appendChild(removeBtn);
        }

        container.appendChild(template);
        itemCount++;
        
        // Re-attach listeners for new elements
        attachListeners();
    });

    function attachListeners() {
        document.querySelectorAll('.produk-select, .item-qty').forEach(el => {
            el.onchange = calculateTotal;
            el.onkeyup = calculateTotal;
            
            if (el.classList.contains('produk-select')) {
                el.addEventListener('change', function() {
                    const selected = this.options[this.selectedIndex];
                    const satuan = selected.getAttribute('data-satuan') || 'unit';
                    this.closest('.card-body').querySelector('.satuan-label').textContent = satuan;
                });
            }
        });
    }

    function calculateTotal() {
        let total = 0;
        document.querySelectorAll('.item-card').forEach(card => {
            const select = card.querySelector('.produk-select');
            const qty = card.querySelector('.item-qty').value || 0;
            
            if (select.selectedIndex > 0) {
                const harga = select.options[select.selectedIndex].getAttribute('data-harga') || 0;
                total += (harga * qty);
            }
        });
        document.getElementById('total-estimasi').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
    }

    attachListeners();
    calculateTotal();
</script>
@endpush
@endsection
