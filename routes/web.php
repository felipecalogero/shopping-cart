<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get( '/', [ProductController::class, 'showProducts'])->name('products');
Route::get('/carrinho', [CartController::class, 'showCart'])->name('cart.show');
Route::post('/adicionar-carrinho/{product}', [CartController::class, 'addToCart'])->name('cart.add');
Route::get('/remover-carrinho/{product}', [CartController::class, 'removeFromCart'])->name('cart.remove');
Route::post('/pedido-realizado', [CartController::class, 'finalOrder'])->name('cart.checkout');
