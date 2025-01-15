<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;

    /**
     * Kolom yang dapat diisi secara massal.
     */
    protected $fillable = ['username', 'password'];

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
}
