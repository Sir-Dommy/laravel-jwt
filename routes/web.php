<?php

use App\Http\Controllers\TestController;
use App\Http\Middleware\CheckRoleOrPermission;
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

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth:api', 'checkRoleOrPermission:admin, edit_users'])->group(function () {
    Route::get('test', [TestController::class ,'test']);

});
Route::post('login', [TestController::class ,'login'])->name('login');
Route::post('register', [TestController::class ,'register']);
Route::get('generateToken', [TestController::class ,'generateToken']);
