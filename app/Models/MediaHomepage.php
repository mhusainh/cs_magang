<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaHomepage extends Model
{
    use HasFactory;

    protected $fillable = [
        'urutan',
        'url',
        'public_id',
    ];
    protected $table = 'media_homepage';
}
