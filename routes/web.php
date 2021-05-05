<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProjectController;
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


// Route::get('/login', function () {
//     // return view('welcome');
//     return view('vuedashboard');
// });
// Route::apiResource('users', UserController::class);

// Route::post('/login', 'AuthController@login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::post('/register', [AuthController::class, 'register']);
// Route::apiResource('projects', ProjectController::class);
Route::get('/testproject', [ProjectController::class, 'testproject']);

Route::get('/getUsers', [UserController::class, 'getUsers']);
Route::get('/getClients', [UserController::class, 'getClients']);
Route::get('/getAdmins', [UserController::class, 'getAdmins']);

Route::post('/assignuser', [ProjectController::class, 'assignUser']);
Route::post('/assignclient', [ProjectController::class, 'assignClient']);

Route::post('/unassignUser', [ProjectController::class, 'unassignUser']);

// Route::get('projects', ProjectController::class);
Route::get('/getProjectUsers/{id}', [ProjectController::class, 'getProjectUsers']);

// ->where('any', '.*');


Route::get('/{any}', function () {
    return view('vuedashboard');
})->where('any', '.*');


// Route::get('/{any}', function () {
//     return view('vuedashboard');
// })->where('any', '.*')->middleware(['auth'])->name('dashboard');
//
// require __DIR__.'/auth.php';


// Route::get('/test', function () {
//   return view('vuedashboard');
// });

// ->where('any', '.*');
