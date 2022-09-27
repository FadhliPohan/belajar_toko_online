<?php

use App\Http\Controllers\HomepageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/', 'HomepageController@index');XXX
// Route::resource('/',HomepageController::class);
Route::get('/', [App\Http\Controllers\HomepageController::class, 'index'])->name('home');
Route::get('/about',[HomepageController::class,'about']);
Route::get('/kontak',[HomepageController::class,'kontak']);