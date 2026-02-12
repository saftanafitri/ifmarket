<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Photo extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'url', // SIMPAN PATH SAJA (contoh: products/abc.jpg)
    ];

    protected $appends = [
        'full_url',
    ];

    // Relasi ke Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Accessor: URL lengkap (local / s3 / minio)
    public function getFullUrlAttribute()
    {
        return Storage::url($this->url);
    }
}
