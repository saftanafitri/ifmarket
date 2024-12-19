<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', 'url',
    ];

     // Define the inverse relationship with the Product model
     public function product()
     {
         return $this->belongsTo(Product::class);
     }
}
