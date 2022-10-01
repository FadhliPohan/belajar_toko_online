<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(){
        return view('user.index',[
            'title'=>'User Profil'
        ]);
    }

    public function setting(){
        return view('user.setting',[
            'title'=>'Profil Setting'
        ]);
    }
}
