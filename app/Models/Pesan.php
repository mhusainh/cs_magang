<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pesan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pesan';
    protected $fillable = [
        'user_id',
        'judul',
        'deskripsi',
        'is_read'
    ];
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
