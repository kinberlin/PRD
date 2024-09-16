<?php

use App\Http\Controllers\GanttController;
use App\Http\Controllers\REST\TaskController;
use App\Http\Controllers\REST\LinkController;
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
Route::resource('task', TaskController::class); 
Route::resource('link', LinkController::class);
Route::get('/data/{id}', [GanttController::class, 'get']);
Route::post('/task/proof', [TaskController::class, 'proof']);
/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::middleware(['web'])->group(function () {

    //Route::apiResource("cart", CartController::class);
  });*/
