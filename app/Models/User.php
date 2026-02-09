<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory;
    use HasApiTokens;

    /**
     * Kolom yang dapat diisi secara massal.
     */
    protected $fillable = ['username', 'password', 'role'];

    /**
     * Menyembunyikan atribut tertentu saat model diubah menjadi array atau JSON.
     */
    protected $hidden = ['password'];

    /**
     * Mutator untuk mengenkripsi password saat disimpan ke database.
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
