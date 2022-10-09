<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Kategori;
use App\Models\Produk;
use App\Models\ProdukImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

         $itemproduk = Produk::orderBy('created_at', 'desc')->paginate(20);
        $data = array('title' => 'Produk',
                    'itemproduk' => $itemproduk);
        return view('produk.index', $data)->with('no', ($request->input('page', 1) - 1) * 20);
   
        //     return view('produk.index',[
        //     'title'=>'Produk'
        // ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

         $itemkategori = Kategori::orderBy('nama_kategori', 'asc')->get();
        $data = array('title' => 'Form Produk Baru',
                    'itemkategori' => $itemkategori);
        return view('produk.create', $data);
        //     return view('produk.create',[
        //     'title'=>'create produk'
        // ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
          $this->validate($request, [
            'kode_produk' => 'required|unique:produk',
            'nama_produk' => 'required',
            'slug_produk' => 'required',
            'deskripsi_produk' => 'required',
            'kategori_id' => 'required',
            'qty' => 'required|numeric',
            'satuan' => 'required',
            'harga' => 'required|numeric'
        ]);
        $itemuser = $request->user();//ambil data user yang login
        $slug = \Str::slug($request->slug_produk);//buat slug dari input slug produk
        $inputan = $request->all();
        $inputan['slug_produk'] = $slug;
        $inputan['user_id'] = $itemuser->id;
        $inputan['status'] = 'publish';
        $itemproduk = Produk::create($inputan);
        return redirect()->route('produk.index')->with('success', 'Data berhasil disimpan');
   
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       $itemproduk = Produk::findOrFail($id);
        $data = array('title' => 'Foto Produk',
                'itemproduk' => $itemproduk);
        return view('produk.show', $data);
        //      return view('produk.show',[
        //     'title'=>'show produk'
        // ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

               $itemproduk = Produk::findOrFail($id);
        $itemkategori = Kategori::orderBy('nama_kategori', 'asc')->get();
        $data = array('title' => 'Form Edit Produk',
                'itemproduk' => $itemproduk,
                'itemkategori' => $itemkategori);
        return view('produk.edit', $data);
        //     return view('produk.edit',[
        //     'title'=>'Edit produk'
        // ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
          $this->validate($request, [
            'kode_produk' => 'required|unique:produk,id,'.$id,
            'nama_produk' => 'required',
            'slug_produk' => 'required',
            'deskripsi_produk' => 'required',
            'kategori_id' => 'required',
            'qty' => 'required|numeric',
            'satuan' => 'required',
            'harga' => 'required|numeric'
        ]);
        $itemproduk = Produk::findOrFail($id);
        // kalo ga ada error page not found 404
        $slug = \Str::slug($request->slug_produk);//slug kita gunakan nanti pas buka produk
        // kita validasi dulu, biar tidak ada slug yang sama
        $validasislug = Produk::where('id', '!=', $id)//yang id-nya tidak sama dengan $id
                                ->where('slug_produk', $slug)
                                ->first();
        if ($validasislug) {
            return back()->with('error', 'Slug sudah ada, coba yang lain');
        } else {
            $inputan = $request->all();
            $inputan['slug'] = $slug;
            $itemproduk->update($inputan);
            return redirect()->route('produk.index')->with('success', 'Data berhasil diupdate');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
         $itemproduk = Produk::findOrFail($id);//cari berdasarkan id = $id, 
        // kalo ga ada error page not found 404
        if ($itemproduk->delete()) {
            return back()->with('success', 'Data berhasil dihapus');
        } else {
            return back()->with('error', 'Data gagal dihapus');
        }
    }

    public function uploadimage(Request $request) {
        $this->validate($request, [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'produk_id' => 'required',
        ]);
        $itemuser = $request->user();
        $itemproduk = Produk::where('user_id', $itemuser->id)
                                ->where('id', $request->produk_id)
                                ->first();
        if ($itemproduk) {
            $fileupload = $request->file('image');
            $penggunaimage = date('YmdHis') . "." .
            $fileupload->getClientOriginalExtension();
            $itemgambar = (new ImageController)->upload($fileupload, $itemuser, $penggunaimage);
            $inputan['foto'] ="$penggunaimage";
            // simpan ke table produk_images
            $inputan = $request->all();
            // simpan ke produk image
            ProdukImage::create($inputan);
            // update banner produk
            $itemproduk->update(['foto' => $penggunaimage]);
            // end update banner produk
            return back()->with('success', 'Image berhasil diupload');
        } else {
            return back()->with('error', 'Kategori tidak ditemukan');
        }
    }

    public function deleteimage(Request $request, $id) {
        // ambil data produk image by id
        $itemprodukimage = ProdukImage::findOrFail($id);
        // ambil produk by produk_id kalau tidak ada maka error 404
        $itemproduk = Produk::findOrFail($itemprodukimage->produk_id);
        // kita cari dulu database berdasarkan url gambar
        $itemgambar = Image::where('url', $itemprodukimage->foto)->first();
        // hapus imagenya
        if ($itemgambar) {
            Storage::delete($itemgambar->url);
            $itemgambar->delete();
        }
        // baru update hapus produk image
        $itemprodukimage->delete();
        //ambil 1 buah produk image buat diupdate jadi banner produk
        $itemsisaprodukimage = ProdukImage::where('produk_id', $itemproduk->id)->first();
        if ($itemsisaprodukimage) {
            $itemproduk->update(['foto' => $itemsisaprodukimage->foto]);
        } else {
            $itemproduk->update(['foto' => null]);
        }
        //end update jadi banner produk
        return back()->with('success', 'Data berhasil dihapus');
    }

     public function loadasync($id) {
        $itemproduk = Produk::findOrFail($id);
        $respon = [
            'status' => 'success',
            'msg' => 'Data ditemukan',
            'itemproduk' => $itemproduk
        ];
        return response()->json($respon, 200);
    }
}
