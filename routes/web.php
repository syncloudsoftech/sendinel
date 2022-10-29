<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::view('/', 'home')->name('home');

Route::controller(App\Http\Controllers\UploadController::class)
    ->name('upload.')
    ->group(function () {

        Route::get('/', 'form')->name('form');
        Route::post('/', 'submit')->name('submit');
    });

Route::controller(App\Http\Controllers\DownloadController::class)
    ->name('download.')
    ->prefix('d')
    ->group(function () {

        Route::get('{hash}', 'show')->name('show');
        Route::post('{upload}', 'submit')->name('submit');
    });

// Auth::routes();

Route::middleware('auth')->group(function () {

    Route::view('/dashboard', 'dashboard')->name('dashboard');
});
