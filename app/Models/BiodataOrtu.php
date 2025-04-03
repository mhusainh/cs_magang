<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BiodataOrtu extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'peserta_id',
        'nama_ayah',
        'nama_ibu',
        'no_telp',
        'pekerjaan_ayah_id',
        'pekerjaan_ibu_id',
        'penghasilan_ortu_id',
    ];

    public function peserta(): BelongsTo
    {
        return $this->belongsTo(PesertaPpdb::class, 'peserta_id');
    }

    public function pekerjaanAyah(): BelongsTo
    {
        return $this->belongsTo(PekerjaanOrtu::class, 'pekerjaan_ayah_id');
    }

    public function pekerjaanIbu(): BelongsTo
    {
        return $this->belongsTo(PekerjaanOrtu::class, 'pekerjaan_ibu_id');
    }

    public function penghasilanOrtu(): BelongsTo
    {
        return $this->belongsTo(PenghasilanOrtu::class, 'penghasilan_ortu_id');
    }
}
