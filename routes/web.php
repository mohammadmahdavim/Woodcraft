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


Route::group(['middleware' => 'auth'], function () {
    Route::get('/', function () {
        return view('panel.dashboard.index');
    });
    Route::resource('units', \App\Http\Controllers\UnitController::class);
    Route::any('units/delete/{id}', [\App\Http\Controllers\UnitController::class, 'destroy']);

    Route::resource('types', \App\Http\Controllers\TypeController::class);
    Route::any('types/delete/{id}', [\App\Http\Controllers\TypeController::class, 'destroy']);

    Route::resource('customers', \App\Http\Controllers\CustomerController::class);
    Route::any('customers/delete/{id}', [\App\Http\Controllers\CustomerController::class, 'destroy']);

    Route::resource('materials', \App\Http\Controllers\MaterialController::class);
    Route::post('materials/inventory/{id}', [\App\Http\Controllers\MaterialController::class, 'inventory']);
    Route::any('materials/delete/{id}', [\App\Http\Controllers\MaterialController::class, 'destroy']);

    Route::resource('products', \App\Http\Controllers\ProductController::class);
    Route::post('products/material/{id}', [\App\Http\Controllers\ProductController::class, 'material']);
    Route::any('products/material/delete/{id}', [\App\Http\Controllers\ProductController::class, 'material_delete']);
    Route::any('products/delete/{id}', [\App\Http\Controllers\ProductController::class, 'destroy']);

    Route::resource('orders', \App\Http\Controllers\OrderController::class);
    Route::any('orders/delete/{id}', [\App\Http\Controllers\OrderController::class, 'destroy']);
    Route::get('orders/details/{id}', [\App\Http\Controllers\OrderController::class,'details']);
    Route::post('/orders/details/store', [\App\Http\Controllers\OrderController::class,'details_store']);
    Route::post('/orders/materials/store', [\App\Http\Controllers\OrderController::class,'materials_store']);
    Route::any('/orders/material/delete/{id}', [\App\Http\Controllers\OrderController::class,'materials_delete']);
    Route::any('/orders/details/delete/{id}', [\App\Http\Controllers\OrderController::class,'details_delete']);
    Route::get('orders/create/past', [\App\Http\Controllers\OrderController::class,'past_create']);
    Route::post('orders/past_store', [\App\Http\Controllers\OrderController::class,'past_store']);
    Route::get('orders/create/new', [\App\Http\Controllers\OrderController::class,'new_create']);
    Route::post('orders/new_store', [\App\Http\Controllers\OrderController::class,'new_store']);
    Route::any('orders/files/{id}', [\App\Http\Controllers\OrderController::class, 'files']);
    Route::get('orders/products/{id}', [\App\Http\Controllers\OrderController::class,'products']);
    Route::get('orders/product_price/{id}', [\App\Http\Controllers\OrderController::class,'product_price']);
    Route::get('orders/product_details/{id}', [\App\Http\Controllers\OrderController::class,'product_details']);

    Route::post('files/store', [\App\Http\Controllers\FileController::class, 'store']);
    Route::any('files/delete/{id}', [\App\Http\Controllers\FileController::class, 'delete']);
    Route::any('files/download/{id}', [\App\Http\Controllers\FileController::class, 'downloadfile']);
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
