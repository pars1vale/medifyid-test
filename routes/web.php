<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\MasterItemsController;
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

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/master-items', [MasterItemsController::class, 'index']);
Route::get('/master-items/export', [MasterItemsController::class, 'exportExcel']);
Route::get('/master-items/search', [MasterItemsController::class, 'search']);
Route::get('/master-items/form/{method}/{id?}', [MasterItemsController::class, 'formView']);
Route::post('/master-items/form/{method}/{id?}', [MasterItemsController::class, 'formSubmit']);
Route::get('/master-items/view/{kode}', [MasterItemsController::class, 'singleView']);
Route::get('/master-items/delete/{id}', [MasterItemsController::class, 'delete']);
Route::get('/master-items/update-random-data', [MasterItemsController::class, 'updateRandomData']);

Route::get('/kategori', [KategoriController::class, 'index']);
Route::get('/kategori/search', [KategoriController::class, 'search']);
Route::get('/kategori/form/{method}/{id?}', [KategoriController::class, 'formView']);
Route::post('/kategori/form/{method}/{id?}', [KategoriController::class, 'formSubmit']);
Route::get('/kategori/view/{kode}', [KategoriController::class, 'singleView']);
Route::get('/kategori/print/{kode}', [KategoriController::class, 'printPdf']);
Route::get('/kategori/delete/{id}', [KategoriController::class, 'delete']);
