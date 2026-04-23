<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['kategori_produk_id', 'nama_produk', 'deskripsi', 'harga_satuan', 'satuan', 'gambar', 'is_aktif'])]
class Produk extends Model
{
    use HasFactory;

    protected $table = 'produk';

    protected function casts(): array
    {
        return [
            'harga_satuan' => 'decimal:2',
            'is_aktif' => 'boolean',
        ];
    }

    public function kategoriProduk()
    {
        return $this->belongsTo(KategoriProduk::class, 'kategori_produk_id');
    }

    public function detailPesanan()
    {
        return $this->hasMany(DetailPesanan::class);
    }
}
