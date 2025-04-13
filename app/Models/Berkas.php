<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Berkas extends Model
{
    use HasFactory;

    protected $table = 'berkas';

    protected $fillable = [
        'peserta_id',
        'nama_file',
        'ketentuan_berkas_id',
        'url_file',
        'public_id'
    ];

    /**
     * Get the peserta that owns the berkas
     */
    public function peserta(): BelongsTo
    {
        return $this->belongsTo(PesertaPpdb::class, 'peserta_id');
    }

    public function ketentuanBerkas(): BelongsTo
    {
        return $this->belongsTo(KetentuanBerkas::class, 'ketentuan_berkas_id');
    }
}
