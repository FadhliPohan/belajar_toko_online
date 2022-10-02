<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
     protected $table = 'images';
    protected $fillable = [
        'user_id',
        'url',
    ];

    public function user() {//user yang menginput data image
        return $this->belongsTo('App\User', 'user_id');
    }
}
