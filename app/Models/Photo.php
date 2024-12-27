<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Photo extends Model
{
    use HasFactory;

    // Mendukung mass assignment
    protected $fillable = [
        'product_id', // Pastikan ini ada
        'url',        // Kolom URL
    ];

    // Menambahkan atribut tambahan 'full_url'
    protected $appends = ['full_url'];

    /**
     * Mendapatkan URL lengkap file
     *
     * @return string
     */
    public function getFullUrlAttribute()
    {
        return Storage::url('private/public/' . $this->url);
    }
}
