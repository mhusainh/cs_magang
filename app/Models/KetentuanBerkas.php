<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KetentuanBerkas extends Model
{
    use HasFactory, SoftDeletes;

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
