<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'pesanan_id',
    'kode_pembayaran',
    'jumlah_bayar',
    'jenis_pembayaran',
    'metode_pembayaran',
    'tanggal_bayar',
    'bukti_pembayaran',
    'status_konfirmasi',
    'dikonfirmasi_oleh',
    'catatan'
])]
class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayaran';

    protected function casts(): array
    {
        return [
            'tanggal_bayar' => 'date',
            'jumlah_bayar' => 'decimal:2',
        ];
    }

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class);
    }

    public function dikonfirmasiOleh()
    {
        return $this->belongsTo(User::class, 'dikonfirmasi_oleh');
    }
}
