<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seller extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'name']; // Field yang dapat diisi

    public function product()
    {
        return $this->belongsTo(Product::class); // Relasi ke tabel products
    }
}
