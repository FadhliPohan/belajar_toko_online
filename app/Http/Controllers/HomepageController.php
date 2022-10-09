<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Produk;
use App\Models\ProdukPromo;
use App\Models\Slideshow;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomepageController extends Controller
{
    public function index() {
         $itemproduk = Produk::orderBy('created_at', 'desc')->limit(6)->get();
        $itempromo = ProdukPromo::orderBy('created_at', 'desc')->limit(6)->get();
        $itemkategori = Kategori::orderBy('nama_kategori', 'asc')->limit(6)->get();
        $itemslide = Slideshow::get();
        $data = array('title' => 'Homepage',
            'itemproduk' => $itemproduk,
            'itempromo' => $itempromo,
            'itemkategori' => $itemkategori,
            'itemslide' => $itemslide,
        );
        return view('homepage.index', $data);
        // return view('homepage.index',[
        //     'title'=>'HomePage'
        // ]);
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
         $itemkategori = Kategori::orderBy('nama_kategori', 'asc')->limit(6)->get();
        $itemproduk = Produk::orderBy('created_at', 'desc')->limit(6)->get();
        $data = array('title' => 'Kategori Produk',
                    'itemkategori' => $itemkategori,
                    'itemproduk' => $itemproduk);
        return view('homepage.kategori', $data);
        // return view('homepage.kategori',[
        //     'title'=>'kategori Produk'
        // ]);
    }

    public function kategoribyslug(Request $request, $slug) {
        $itemproduk = Produk::orderBy('nama_produk', 'desc')
                            ->where('status', 'publish')
                            ->whereHas('kategori', function($q) use ($slug) {
                                $q->where('slug_kategori', $slug);
                            })
                            ->paginate(18);
        $listkategori = Kategori::orderBy('nama_kategori', 'asc')
                                ->where('status', 'publish')
                                ->get();
        $itemkategori = Kategori::where('slug_kategori', $slug)
                                ->where('status', 'publish')
                                ->first();
        if ($itemkategori) {
            $data = array('title' => $itemkategori->nama_kategori,
                        'itemproduk' => $itemproduk,
                        'listkategori' => $listkategori,
                        'itemkategori' => $itemkategori);
            return view('homepage.produk', $data)->with('no', ($request->input('page') - 1) * 18);            
        } else {
            return abort('404');
        }
    }

    public function produk(Request $request) {
        $itemproduk = Produk::orderBy('nama_produk', 'desc')
                            ->where('status', 'publish')
                            ->paginate(18);
        $listkategori = Kategori::orderBy('nama_kategori', 'asc')
                                ->where('status', 'publish')
                                ->get();
        $data = array('title' => 'Produk',
                    'itemproduk' => $itemproduk,
                    'listkategori' => $listkategori);
        return view('homepage.produk', $data)->with('no', ($request->input('page') - 1) * 18);
    }
  
    public function produkdetail($id) {
        $itemproduk = Produk::where('slug_produk', $id)
                            ->where('status', 'publish')
                            ->first();
        if ($itemproduk) {
            if (Auth::user()) {//cek kalo user login
                $itemuser = Auth::user();
                $itemwishlist = Wishlist::where('produk_id', $itemproduk->id)
                                        ->where('user_id', $itemuser->id)
                                        ->first();
                $data = array('title' => $itemproduk->nama_produk,
                        'itemproduk' => $itemproduk,
                        'itemwishlist' => $itemwishlist);
            } else {
                $data = array('title' => $itemproduk->nama_produk,
                            'itemproduk' => $itemproduk);
            }
            return view('homepage.produkdetail', $data);            
        } else {
            // kalo produk ga ada, jadinya tampil halaman tidak ditemukan (error 404)
            return abort('404');
        }
    }
}
