<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;
    
    protected $table = 'images'; // Add this line to specify the table name
    protected $fillable = [
        'public_id',
        'file_name',
        'file_type',
        'url',
        'secure_url',
        'size'
    ];

    public function imageable()
    {
        return $this->morphTo();
    }
}
