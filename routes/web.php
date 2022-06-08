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
Route::get('/products', 'ProductController@getAllProducts');
Route::get('/products/category/{category_id}', 'ProductController@getProductsByCategory');


Route::middleware(['cors'])->group(function () {
    Route::post('/confirmOrder', 'OrderController@confirmOrder');
    Route::post('/order', 'OrderController@showNota');
    Route::post('/statusOrder', 'OrderController@getOrderStatus');
});


Route::group(
    ['prefix' => 'admin', 'middleware' => ['auth']],
    function () {
        Route::resource('categories', 'CategoryController');
        Route::get('transactions/searchPurchasereport', 'TransactionController@searchPurchasereport');
        Route::get('transactions/searchTransactionreport', 'TransactionController@searchTransactionreport');
        Route::get('transactions/searchDispatchreport', 'TransactionController@searchDispatchreport');
        Route::get('transactions/searchStockreport', 'TransactionController@searchStockreport');
        Route::get('transactions/searchSellingreport', 'OrderController@searchSellingreport');
        Route::get('transactions/purchaseReportbydate/{days}', 'TransactionController@purchaseReportbydate');
        Route::get('transactions/transactionReportbydate/{days}', 'TransactionController@transactionReportbydate');
        Route::get('transactions/dispatchReportbydate/{days}', 'TransactionController@dispatchReportbydate');
        Route::get('transactions/stockReportbydate/{days}', 'TransactionController@stockReportbydate');
        Route::get('transactions/transactionReport', 'TransactionController@transactionReport');
        Route::get('transactions/dispatchReport', 'TransactionController@dispatchReport');
        Route::get('transactions/purchaseReport', 'TransactionController@purchaseReport');
        Route::get('transactions/sellingReport', 'OrderController@sellingReport');
        Route::get('transactions/sellingReportByDate/{days}', 'OrderController@sellingReportByDate');
        Route::get('transactions/stockReport', 'TransactionController@stockReport');
        
        Route::get('products/search', 'ProductController@searchProduct');
        Route::get('transactions/input/{product_id}', 'TransactionController@create');
        Route::resource('transactions', 'TransactionController');
        Route::resource('products', 'ProductController');
        Route::get('products/{product_id}/images', 'ProductController@images');
        Route::get('products/{product_id}/add-image', 'ProductController@add_image');
        Route::post('products/images/{product_id}', 'ProductController@upload_image');
        Route::delete('products/images/{product_id}', 'ProductController@delete_image');
    }
);