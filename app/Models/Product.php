<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;


    protected $table = 'products';
    protected $fillable = [
        'name',
        'photos',
        'video',
        'description',
        'seller_name',
        'email',
        'instagram',
        'linkedin',
        'github',
        'product_link',
        'category_id',
    ];


    public function photos()
    {
        return $this->hasMany(Photo::class, 'product_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    } 
}

