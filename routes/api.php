<?php

use App\Http\Controllers\KomentarController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/login', [UserController::class, 'login']);
Route::post('/create', [UserController::class, 'create'])->middleware('JwtAuth');
Route::post('/read', [UserController::class, 'read']);
Route::post('/komentar', [KomentarController::class, 'komentar']);
Route::get('/allKomentar', [KomentarController::class, 'allKomentar']);
