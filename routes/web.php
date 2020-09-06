<?php

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

Route::get('language/{locale}', 'ChangeLocale');

Auth::routes();
Route::get('/products/trash', 'ProductController@trash')
    ->middleware('auth')
    ->name('products.trash');
Route::put('/products/restore/{id}', 'ProductController@restore')
    ->middleware('auth')
    ->name('products.restore');
Route::delete('/products/{productId}/image/{imageId}', 'ProductController@destroyImage')
    ->middleware('auth')
    ->name('products.image.destroy');
Route::resource('products', 'ProductController')->middleware('auth');
Route::resource('products', 'ProductController')->middleware('auth');
Route::get('/', 'ProductController@index')->middleware('auth');
