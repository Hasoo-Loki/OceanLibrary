<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
 
class Favorite extends Model
{
    protected $fillable = ['user_id', 'book_id'];
 
    // Relasi ke Book — wajib ada agar dropdown navbar bisa load data buku
    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
 