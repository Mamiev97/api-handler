<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::post('/products/save', [ProductController::class, 'saveProducts']);
Route::get('/iphones', [ProductController::class, 'getIphones']);
Route::post('/product/add', [ProductController::class, 'addProduct']);

