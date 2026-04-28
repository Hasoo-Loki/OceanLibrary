<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Rating;
use Illuminate\Support\Facades\App;

class Book extends Model
{
    protected $table = 'books';

    protected $fillable = [
        'judul',
        'penulis',
        'kategori',
        'tahun',
        'sinopsis',
        'gambar',
        'stock',
        'tersedia',
    ];


    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function ratings()
    {
        return $this->hasMany(\App\Models\Rating::class);
    }

    public function favorites()
    {
        return $this->hasMany(\App\Models\Favorite::class);
    }
}