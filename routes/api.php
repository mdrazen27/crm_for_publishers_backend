<?php

use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AdvertisementController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PublisherController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\StatisticController;
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
Route::get('/unauthenticated', [AuthController::class,'unauthenticated'])->name('unauthenticated');

Route::group(['middleware' => ['auth:api']], function () {
    Route::get('/roles', [RoleController::class, 'index'])->name('roles');
    Route::resource('/admin-users', AdminUserController::class);
    Route::resource('/publishers', PublisherController::class);
    Route::post('/publishers/{publisher}/toggle-status', [PublisherController::class, 'togglePublisherStatus'])
        ->name('toggle-publisher-status');
    Route::get('/publisher-profiles', [PublisherController::class, 'viewPublisherProfile'])
        ->name('publisher-profile');
    Route::resource('/advertisements', AdvertisementController::class);
    Route::post('/advertisements/{advertisement}/toggle-status', [AdvertisementController::class, 'toggleAdvertisementStatus'])
        ->name('toggle-advertisement-status');
    Route::post('/change-password', [AuthController::class, 'changePassword'])->name('change-password');

    Route::group(['prefix' => '/statistics'], function(){
        Route::get('/count-per-month', [StatisticController::class, 'countPerMonth'])
            ->name('stats-per-month');
        Route::get('/count-per-day', [StatisticController::class, 'countPerDay'])
            ->name('stats-per-day');
        Route::get('/top-ten-countries', [StatisticController::class, 'topTenCountriesByViewCount'])
            ->name('top-ten-countries');
        Route::get('/top-five-active-advertisements', [StatisticController::class, 'topFiveActiveAdvertisementsByViewCount'])
            ->name('top-five-active-advertisements');
    });
});
