<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KetentuanBerkas extends Model
{
    use HasFactory;

    protected $table = 'ketentuan_berkas';

    protected $fillable = [
        'nama',
        'jenjang_sekolah',
        'is_required'
    ];


    /**
     * Get all berkas for this ketentuan
     */
    public function berkas()
    {
        return $this->hasMany(Berkas::class, 'ketentuan_berkas_id');
    }
}
