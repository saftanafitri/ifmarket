<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'photos',
        'video',
        'category',
        'description',
        'seller_name',
        'email',
        'instagram',
        'linkedin',
        'github',
        'product_link'
    ];

    protected $casts = [
        'photos' => 'array',
    ];

    public function photos()
    {
        return $this->hasMany(Photo::class);
    }
}

