<?php

use App\Http\Controllers\AbsenController;
use App\Http\Controllers\DaftarUkmController;
use App\Http\Controllers\ForgotPassword;
use App\Http\Controllers\JadwalPiketController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ManageUkm;
use App\Http\Controllers\ManageUserController;
use App\Http\Controllers\RapatProkerController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\RekrutUkmController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WhatssapController;
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

Route::post('/forgot-password', [ForgotPassword::class, 'forgot_password']);

Route::post('/reset-password', [ForgotPassword::class, 'reset_password']);

Route::get('/get-all-organization', [ManageUkm::class, 'get_all_organization']);

Route::get('/get-formulir/{organization}', [DaftarUkmController::class, 'get_formulir']);

Route::get('/get-role', [ManageUserController::class, 'get_role']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/cek-token', [LoginController::class, 'cek']);
    Route::post('/register', [RegisterController::class, 'store']);
    Route::get('/logout', [LoginController::class, 'logout']);
    Route::get('/allUser', [UserController::class, 'index']);
    Route::get('/myrole', [UserController::class, 'showRole']);
    Route::get('/me', [UserController::class, 'me']);
    Route::get('/myorganization', [UserController::class, 'myorganization']);
    Route::get('/jadwal-piket', [JadwalPiketController::class, 'showAll']);
    Route::get('/jadwal-piket/organization', [JadwalPiketController::class, 'show_piket_org']);
    Route::get('/all-organization', [ManageUserController::class, 'all_organization']);
    Route::get('/user-organization', [ManageUserController::class, 'user_organization']);
    Route::get('/all-rapat-proker', [RapatProkerController::class, 'all_rapat_proker']);
    Route::post('/absensi', [AbsenController::class, 'store']);
    Route::get('/data-presensi', [AbsenController::class, 'show']);
    Route::post('/change-password', [UserController::class, 'change_password']);
    Route::get('/profile', [UserController::class, 'profile']);
    Route::post('/change-profile', [UserController::class, 'change_profile']);
});

Route::middleware(['auth:sanctum', 'Admin'])->group(function () {
    Route::post('/rekrut-ukm', [RekrutUkmController::class, 'rekrut']);
    Route::get('/admin-organization', [UserController::class, 'show_admin_organization']);
    Route::get('/rekrut-cancel/{form}', [RekrutUkmController::class, 'cancel']);
    Route::get('/formulir', [DaftarUkmController::class, 'index']);
    Route::post('/edit-formulir/{form}', [DaftarUkmController::class, 'edit_formulir']);
    Route::get('/daftar-calon', [DaftarUkmController::class, 'showAll']);
    Route::post('/angkat-calon/{dataform}', [DaftarUkmController::class, 'angkat_calon']);
    Route::post('/select-angkat-calon', [DaftarUkmController::class, 'select_angkat_calon']);
    Route::post('/select-reject-calon', [DaftarUkmController::class, 'select_reject_calon']);
    Route::post('/add-petugas-piket', [JadwalPiketController::class, 'add_petugas_piket']);
    Route::delete('/delete-petugas-piket/{userpiket}', [JadwalPiketController::class, 'delete_petugas_piket']);
    Route::put('/update-petugas-piket', [JadwalPiketController::class, 'update_petugas_piket']);
    Route::post('/rapat-proker', [RapatProkerController::class, 'rapat_proker']);
    Route::put('/edit-rapat-proker/{rapatproker}', [RapatProkerController::class, 'edit_rapat_proker']);
    Route::get('/data-organization', [ManageUkm::class, 'data_organization']);
    Route::post('/update-data-organization/{organization}', [ManageUkm::class, 'update_data_organization']);
    Route::post('/delete-rapat-proker/{rapatproker}', [RapatProkerController::class, 'delete_rapat_proker']);

    // whatssapp gateway
    Route::post('/create-session', [WhatssapController::class, 'create_session']);
});

Route::middleware(['auth:sanctum', 'superAdmin'])->group(function () {
    Route::post('/ganti-role', [ManageUserController::class, 'ganti_role']);
    Route::get('/all-role-except-sa', [ManageUserController::class, 'all_role_except_sa']);
    Route::get('/all-organization', [ManageUkm::class, 'all_organization']);
    Route::post('/tambah-ukm', [ManageUkm::class, 'tambah_ukm']);
    Route::put('/edit-ukm/{organization}', [ManageUkm::class, 'edit_ukm']);
    Route::post('/password-reset/{user}', [ManageUkm::class, 'password_reset']);
});
