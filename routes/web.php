<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomepageController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\CustomerController;
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
Route::get('/kategori',[HomepageController::class,'kategori']);
Route::get('/kategori/{slug}',[HomepageController::class,'produkperkategori']);
Route::get('/produk',[HomepageController::class,'produk']);
Route::get('/produk/{slug}',[HomepageController::class,'produkdetail']);


Route::prefix('admin')->group(function () {
    Route::get('/',[DashboardController::class,'index']);
    Route::resource('kategori',KategoriController::class);
    Route::resource('produk',ProdukController::class);
    Route::resource('customer',CustomerController::class);

    
});