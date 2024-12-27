<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'url', // Path lengkap sesuai dengan yang disimpan di database
    ];

    // Relasi dengan produk
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
