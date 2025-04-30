<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PesertaPpdb extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'nisn',
        'nis',
        'nama',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'agama',
        'email',
        'no_telp',
        'jenjang_sekolah',
        'alamat',
        'jurusan1_id',
        'pengajuan_biaya',
        'wakaf',
        'spp',
        'book_vee',
        'status'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function jurusan1(): BelongsTo
    {
        return $this->belongsTo(Jurusan::class, 'jurusan1_id');
    }

    public function biodataOrtu(): HasOne
    {
        return $this->hasOne(BiodataOrtu::class, 'peserta_id');
    }

    public function berkas(): HasMany
    {
        return $this->hasMany(Berkas::class, 'peserta_id'); 
    }
}
