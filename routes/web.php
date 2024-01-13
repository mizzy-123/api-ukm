<?php

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function () {
    return view('mails.reset-password-template');
});

Route::get('/reset-password/{token}', function ($token) {
    // Gunakan $token sesuai kebutuhan Anda
    return redirect(env("FE_URL") . "/reset-password/$token");
})->name('password.reset');
