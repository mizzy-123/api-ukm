<?php

use App\Http\Controllers\DaftarUkmController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\RekrutUkmController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::post('/login', [LoginController::class, 'login']);

Route::get('/perekrutan-ukm', [RekrutUkmController::class, 'showAll']);

Route::post('/daftar-ukm', [DaftarUkmController::class, 'store']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/cek-token', [LoginController::class, 'cek']);
    Route::post('/register', [RegisterController::class, 'store']);
    Route::get('/logout', [LoginController::class, 'logout']);
    Route::get('/allUser', [UserController::class, 'index']);
    Route::get('/myrole', [UserController::class, 'showRole']);
});

Route::middleware(['auth:sanctum', 'Admin'])->group(function () {
    Route::post('/rekrut-ukm', [RekrutUkmController::class, 'rekrut']);
    Route::get('/admin-organization', [UserController::class, 'show_admin_organization']);
    Route::get('/rekrut-cancel/{form}', [RekrutUkmController::class, 'cancel']);
    Route::get('/formulir', [DaftarUkmController::class, 'index']);
    Route::get('/daftar-calon', [DaftarUkmController::class, 'showAll']);
    Route::post('/angkat-calon/{dataform}', [DaftarUkmController::class, 'angkat_calon']);
});
