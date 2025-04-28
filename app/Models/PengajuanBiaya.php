<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanBiaya extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_biaya';
    protected $fillable = [
        'nominal',
        'jurusan',
        'jenjang_sekolah'
    ];
}
