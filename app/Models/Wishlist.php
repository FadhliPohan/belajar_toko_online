<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    use HasFactory;
     protected $table = 'wishlist';
    protected $fillable = [
        'produk_id',
        'user_id',
    ];

    public function produk () {
        return $this->belongsTo('App\Produk', 'produk_id');
    }
    public function user () {
        return $this->belongsTo('App\User', 'user_id');
    }
}
