<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'name',
        'slug',
        'photos',
        'video',
        'description',
        'email',
        'instagram',
        'linkedin',
        'github',
        'product_link',
        'category_id',
        'seller_name',
        'status',
        'user_id',
    ];
    
    protected $cast = [
        'status' => 'string',
    ];

    // Relasi ke tabel photos
    public function photos()
    {
        return $this->hasMany(Photo::class, 'product_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function sellers()
    {
        return $this->hasMany(Seller::class, 'product_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }



    // Tambahkan logika otomatis untuk slug
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });

        static::updating(function ($product) {
            if ($product->isDirty('name') && empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });
    }
}
