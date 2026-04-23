<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['nama_kategori', 'deskripsi'])]
class KategoriProduk extends Model
{
    use HasFactory;

    protected $table = 'kategori_produk';

    public function produk()
    {
        return $this->hasMany(Produk::class);
    }
}
