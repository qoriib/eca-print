<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengaturan extends Model
{
    protected $table = 'pengaturan';
    
    protected $fillable = [
        'nama_usaha',
        'alamat',
        'no_hp',
        'email',
        'catatan_footer',
        'bank_1',
        'norek_1',
        'atas_nama_1',
        'bank_2',
        'norek_2',
        'atas_nama_2',
    ];
}
