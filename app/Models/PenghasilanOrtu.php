<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PenghasilanOrtu extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'penghasilan_ortus';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'kategori',
        'range_penghasilan',
    ];

    public function biodataOrtu()
    {
        return $this->hasMany(BiodataOrtu::class, 'penghasilan_ortu_id');
    }
}
