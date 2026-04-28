<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    protected $table = 'notifikasi';
    protected $fillable = ['user_id', 'book_id', 'peminjaman_id', 'pesan', 'dibaca'];

    public function user() { return $this->belongsTo(User::class); }
    public function book() { return $this->belongsTo(Book::class); }
    public function peminjaman() { return $this->belongsTo(Peminjaman::class); }
}