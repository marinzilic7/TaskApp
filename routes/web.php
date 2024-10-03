<?php

use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

Route::post('registerUser', [UsersController::class, 'registerUser']);
Route::post('loginUser', [UsersController::class, 'loginUser']);
Route::get('isLogged', [UsersController::class, 'isLogged']);
Route::post('logout', [UsersController::class, 'logout']);

Route::get('/{any}', function () {
    return view('welcome');
})->where('any', '^(?!api).*$');
