<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\PesertaPpdb as Peserta;

class Hasil extends Model
{
    use HasFactory;

    protected $table = 'hasils';

    protected $fillable = [
        'peserta_id',
        'hasil',
        'jurusan_id'
    ];

    public function peserta(): BelongsTo
    {
        return $this->belongsTo(Peserta::class);
    }

    public function jurusan(): BelongsTo
    {
        return $this->belongsTo(Jurusan::class);
    }
}
