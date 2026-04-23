<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'kode_pesanan',
    'user_id',
    'tanggal_pesan',
    'tanggal_deadline',
    'status',
    'total_harga',
    'status_pembayaran',
    'catatan_pelanggan',
    'catatan_admin'
])]
class Pesanan extends Model
{
    use HasFactory;

    protected $table = 'pesanan';

    protected function casts(): array
    {
        return [
            'tanggal_pesan' => 'date',
            'tanggal_deadline' => 'date',
            'total_harga' => 'decimal:2',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function detailPesanan()
    {
        return $this->hasMany(DetailPesanan::class);
    }

    public function produksi()
    {
        return $this->hasOne(Produksi::class);
    }

    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class);
    }
}
