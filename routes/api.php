<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\API\PostController;
use App\Http\Controllers\API\CategoryController;

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

// Product API Routes
Route::apiResource('products', ProductController::class);
// Route::middleware('auth:sanctum')->group(function () {
//     Route::apiResource('products', ProductController::class);
// });

//Posts API Routes
Route::apiResource('posts', PostController::class);

//Categories API Routes
Route::apiResource('categories', CategoryController::class);