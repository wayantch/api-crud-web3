<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'image',
        'name',
        'nim',
        'prodi',
    ];

    public function image(): Attribute
    {
        return Attribute::make(
            get: fn ($image) => url('/storage/public/posts/' . $image),
        );
    }
}
