<?php

use App\Http\Controllers\TestController;
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


Route::post('login', [TestController::class ,'login']);
Route::post('register', [TestController::class ,'register']);

Route::middleware('jwt.auth')->group(function () {
    Route::post('logout', [TestController::class ,'logout']);
    Route::post('refresh', [TestController::class ,'refresh']);
    Route::get('test', [TestController::class ,'test']);
    Route::get('generateToken', [TestController::class ,'generateToken']);
});

