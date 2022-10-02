<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
     public function index(Request $request) {
        $itemuser = $request->user();
        $itemgambar = Image::where('user_id', $itemuser->id)->paginate(20);
        // $data = array('title' => 'Data Image',
        //             'itemgambar' => $itemgambar);
        return view('image.index', ['title' => 'Data Image', 'itemgambar'=>$itemgambar])->with('no', ($request->input('page', 1) - 1) * 20);
    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

            $itemuser = $request->user();
         if($foto = $request->file('image')){
            $penggunaimage = date('YmdHis') . "." .
            $foto->getClientOriginalExtension();
            //extention menyimpan
            $foto->storeAs('public/dataimage', $penggunaimage);
            //extention ke database
            $inputangambar['url'] ="$penggunaimage";
        }

      
        $inputangambar['user_id'] = $itemuser->id;
        Image::create($inputangambar);
        return back()->with('success', 'Image berhasil diupload');
        // return redirect()->route('image.index')
        // ->with('success','Berhasil ditambahkan');
    }

    public function destroy(Request $request, $id) {
        $itemuser = $request->user();
        $itemgambar = Image::where('user_id', $itemuser->id)
                            ->where('id', $id)
                            ->first();
        if ($itemgambar) {
            Storage::delete('public/dataimage', $itemgambar->url);
            $itemgambar->delete();
            return back()->with('success', 'Data berhasil dihapus');
        } else {
            return back()->with('error', 'Data tidak ditemukan');
        }
    }
    
    public function upload($fileupload, $itemuser, $penggunaimage) {
        $penggunaimage = date('YmdHis') . "." .
            $fileupload->getClientOriginalExtension();
        $path = $fileupload->storeAs('public/dataimage', $penggunaimage);
        $inputangambar['url'] = $penggunaimage;
        $inputangambar['user_id'] = $itemuser->id;
        return Image::create($inputangambar);
    }
}
