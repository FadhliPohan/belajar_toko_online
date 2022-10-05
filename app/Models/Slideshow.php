<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slideshow extends Model
{
    use HasFactory;

    protected $table = "slideshow";
    protected $fillable = [
        'foto',
        'caption_title',
        'caption_content',
        'user_id',
    ];

    public function user() {
        return $this->belongsTo('App\User', 'user_id');
    }
}
