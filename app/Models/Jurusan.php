<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\PesertaPpdb as Peserta;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Jurusan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'jurusan';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'jurusan',
        'jenjang_sekolah',
    ];

    public function pesertas1(): HasMany
    {
        return $this->hasMany(Peserta::class, 'jurusan1_id');
    }

    public function pesertas2(): HasMany
    {
        return $this->hasMany(Peserta::class, 'jurusan2_id');
    }

    public function hasil(): HasOne
    {
        return $this->hasOne(Hasil::class);
    }
    
}
