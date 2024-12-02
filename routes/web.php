<?php

use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\SubtaskController;
use App\Http\Controllers\TaskController;
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
Route::post('addCategory', [CategoriesController::class, 'addCategory']);
Route::get('getCategories', [CategoriesController::class, 'getCategories']);
Route::post('deleteCategory/{id}', [CategoriesController::class, 'deleteCategory']);
Route::post('updateCategory/{id}', [CategoriesController::class, 'updateCategory']);


//Task routes

Route::post('addTask', [TaskController::class, 'addTask']);
Route::get('getTasks', [TaskController::class, 'getTasks']);

//Subtask routes
Route::post('addSubtask', [SubtaskController::class, 'addSubtask']);
Route::get('getSubtasks/{id}', [SubtaskController::class, 'getSubtasks']);

Route::get('/{any}', function () {
    return view('welcome');
})->where('any', '^(?!api).*$');
