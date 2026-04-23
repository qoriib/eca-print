<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'pesanan_id',
    'produk_id',
    'jumlah',
    'ukuran',
    'bahan',
    'finishing',
    'harga_satuan',
    'subtotal',
    'file_desain',
    'keterangan'
])]
class DetailPesanan extends Model
{
    use HasFactory;

    protected $table = 'detail_pesanan';

    protected function casts(): array
    {
        return [
            'harga_satuan' => 'decimal:2',
            'subtotal' => 'decimal:2',
        ];
    }

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class);
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }
}
