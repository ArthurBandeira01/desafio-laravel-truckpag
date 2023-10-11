<?php

use App\Enums\ProductStatus;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', [ProductController::class, 'index'])->name('statusApi');
Route::get('/products', [ProductController::class, 'listProducts'])->name('listProducts');
Route::get('/products/{code}', [ProductController::class, 'showProduct'])->name('showProduct');
Route::put('/products/{code}', [ProductController::class, 'updateProduct'])->name('updateProduct');
Route::delete('/products/{code}', [ProductController::class, 'deleteProduct'])->name('deleteProducts');
