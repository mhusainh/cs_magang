<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PekerjaanOrtu extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pekerjaan_ortus';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'pekerjaan',
    ];

    public function biodataOrtuAyah()
    {
        return $this->hasMany(BiodataOrtu::class, 'pekerjaan_ayah_id');
    }

    public function biodataOrtuIbu()
    {
        return $this->hasMany(BiodataOrtu::class, 'pekerjaan_ibu_id');
    }
}
