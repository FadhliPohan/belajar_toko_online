<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomepageController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\UserController;
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


Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function() {
    Route::get('/',[DashboardController::class,'index']);
    Route::resource('kategori',KategoriController::class);
    Route::resource('produk',ProdukController::class);
    Route::resource('customer',CustomerController::class);
    Route::resource('transaksi',TransaksiController::class);
    Route::get('profil',[UserController::class,'index']);
    Route::get('setting',[UserController::class,'setting']);
    Route::get('laporan',[LaporanController::class,'index']);
    Route::get('proseslaporan',[LaporanController::class,'proses']);
    Route::get('image',[ImageController::class,'index']);
    Route::post('image',[ImageController::class,'store']);
    Route::delete('image/{id}',[ImageController::class,'destroy']);
    Route::post('imagekategori',[KategoriController::class,'uploadimage']);
    Route::delete('imagekategori/{id}',[KategoriController::class,'deleteimage']);
  

});
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
