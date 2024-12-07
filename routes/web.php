<?php


use App\Http\Controllers\GroupController;
use App\Http\Controllers\NotifficationController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SubtaskController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TeamMemberController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;




Route::post('registerUser', [UsersController::class, 'registerUser']);
Route::post('loginUser', [UsersController::class, 'loginUser']);
Route::get('isLogged', [UsersController::class, 'isLogged']);
Route::post('logout', [UsersController::class, 'logout']);
Route::post('/uploadProfile', [UsersController::class, 'uploadProfileImage']);
Route::post('changePassword', [UsersController::class, 'changePassword']);
Route::get('/search-members', [UsersController::class, 'searchMembers']);


//Admin routes

Route::get('getUsers', [UsersController::class, 'getUsers']);
Route::post('deleteUser/{id}', [UsersController::class, 'deleteUser']);
Route::post('promoteUser/{id}', [UsersController::class, 'promoteUser']);



//Task routes

Route::post('addTask', [TaskController::class, 'addTask']);
Route::get('getTasks', [TaskController::class, 'getTasks']);
Route::post('deleteTask/{id}', [TaskController::class, 'deleteTask']);
Route::post('importantTask/{id}', [TaskController::class, 'importantTask']);
Route::get('getImportant', [TaskController::class, 'getImportant']);
Route::post('deleteImportant/{id}', [TaskController::class, 'deleteImportant']);
Route::post('addImportant', [TaskController::class, 'addImportant']);
Route::post('addDeadline/{id}', [TaskController::class, 'addDeadline']);
Route::post('deleteCompleted/{id}', [TaskController::class, 'deleteCompleted']);
Route::get('/getCompletedImportantTasks', [TaskController::class, 'getCompletedImportantTasks']);
Route::get('/getCompletedTasks', [TaskController::class, 'getCompletedTasks']);
Route::get('/getUncompletedTask', [TaskController::class, 'getUncompletedTasks']);


//Group tasks routes


Route::post('addGroup', [GroupController::class, 'addGroup']);
Route::get('getGroup', [GroupController::class, 'getGroup']);
Route::post('deleteGroup/{id}', [GroupController::class, 'deleteGroup']);
Route::get('getGroupData/{id}', [GroupController::class, 'getGroupData']);
Route::get('getGroupsWithTaskCount', [GroupController::class, 'getGroupsWithTaskCount']);


//Sub routes
Route::post('addSubtask', [SubtaskController::class, 'addSubtask']);
Route::get('getSubtasks/{id}', [SubtaskController::class, 'getSubtasks']);
Route::post('deleteSubtask/{id}', [SubtaskController::class, 'deleteSubtask']);
Route::post('addDeadlineSubTask/{id}', [SubtaskController::class, 'addDeadlineSubTask']);
Route::post('importantSubtask/{id}', [SubtaskController::class, 'importantSubtask']);
Route::get('getImportantSubtasks', [SubtaskController::class, 'getImportantSubtasks']);
Route::get('getCompletedSubtasks/{id}', [SubtaskController::class, 'getCompletedSubtasks']);
Route::post('deleteCompletedSubtask/{id}', [SubtaskController::class, 'deleteCompletedSubtask']);


//Team routes

Route::post('addTeam', [TeamController::class, 'addTeam']);
Route::get('getTeams', [TeamController::class, 'getTeams']);
Route::post('updateTeam/{id}', [TeamController::class, 'updateTeam']);
Route::post('deleteTeam/{id}', [TeamController::class, 'deleteTeam']);
Route::get('getTeamData/{id}', [TeamController::class, 'getTeamData']);



//Project routes


Route::post('/addProject/{id}', [ProjectController::class, 'addProject']);
Route::get('/getProject/{id}',  [ProjectController::class, 'getProject']);

//Team members

Route::post('addMember/{id}', [TeamMemberController::class, 'addMember']);


//Reminder routes
Route::post('/send-reminder', [NotifficationController::class, 'sendReminder']);




Route::get('/{any}', function () {
    return view('welcome');
})->where('any', '^(?!api).*$');
