<?php

use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;




Route::post('registerUser', [UsersController::class, 'registerUser']);
Route::post('loginUser', [UsersController::class, 'loginUser']);
Route::get('isLogged', [UsersController::class, 'isLogged']);
Route::post('logout', [UsersController::class, 'logout']);
Route::post('/uploadProfile', [UsersController::class, 'uploadProfileImage']);

//Admin routes

Route::get('getUsers', [UsersController::class, 'getUsers']);
Route::post('deleteUser/{id}', [UsersController::class, 'deleteUser']);
Route::post('promoteUser/{id}', [UsersController::class, 'promoteUser']);

Route::get('/{any}', function () {
    return view('welcome');
})->where('any', '^(?!api).*$');
