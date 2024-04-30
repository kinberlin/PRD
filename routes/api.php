<?php

use App\Http\Controllers\Rest\CartController;
use App\Http\Controllers\Rest\WishlistController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::middleware(['web'])->group(function () {
    Route::get('/wishlist/delete/{id}', [WishlistController::class, 'destroy']);
    Route::post('/wishlist', [WishlistController::class, 'store']);
    Route::get('/cart/delete/{id}/{qty}', [CartController::class, 'destroy']);
    Route::post('/cart', [CartController::class, 'store']);
    //Route::apiResource("cart", CartController::class);
  });
