<?php

use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PublisherController;
use App\Http\Controllers\RoleController;
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

Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/role', [RoleController::class, 'index'])->name('roles');
    Route::resource('/admin-user', AdminUserController::class);
    Route::resource('/publisher', PublisherController::class);
    Route::post('/toggle-publisher-status/{publisher}',[PublisherController::class,'togglePublisherStatus'])
        ->name('toggle-publisher-status');
    Route::get('/publisher-profile',[PublisherController::class,'viewPublisherProfile']);
});
