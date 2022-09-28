<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomepageController extends Controller
{
    public function index() {
        return view('homepage.index',[
            'title'=>'HomePage'
        ]);
    }

    public function about(){
        return view('homepage.about',[
            'title'=>'Tentang Kami'
        ]);
    }
    public function kontak(){
        return view('homepage.kontak',[
            'title'=>'Tentang Kami'
        ]);
    }
    public function kategori(){
        return view('homepage.kategori',[
            'title'=>'kategori Produk'
        ]);
    }
}
