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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(
    ['prefix' => 'admin', 'middleware' => ['auth']],
    function () {
        Route::resource('categories', 'CategoryController');
        Route::get('transactions/input/{product_id}', 'TransactionController@create');
        Route::resource('transactions', 'TransactionController');
        Route::resource('products', 'ProductController');
        Route::get('products/{product_id}/images', 'ProductController@images');
        Route::get('products/{product_id}/add-image', 'ProductController@add_image');
        Route::post('products/images/{product_id}', 'ProductController@upload_image');
        Route::delete('products/images/{product_id}', 'ProductController@delete_image');
    }
);