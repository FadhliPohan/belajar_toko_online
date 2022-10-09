<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdukPromo extends Model
{
    use HasFactory;
     protected $table = 'produk_promo';
    protected $fillable = [
        'produk_id',
        'harga_awal',
        'harga_akhir',
        'diskon_persen',
        'diskon_nominal',
        'user_id',
    ];

    public function produk() {
        return $this->belongsTo(Produk::class,'produk_id');
    }

    public function user() {
        return $this->belongsTo(User::class,'user_id');
    }
}
