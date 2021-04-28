<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

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

Route::get('/{any}', function () {
    return view('vuedashboard');
})->where('any', '.*');

// Route::get('/login', function () {
//     // return view('welcome');
//     return view('vuedashboard');
// });


// Route::post('/login', 'AuthController@login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// ->where('any', '.*');

// Route::get('/{any}', function () {
//     return view('vuedashboard');
// })->where('any', '.*')->middleware(['auth'])->name('dashboard');
//
// require __DIR__.'/auth.php';


// Route::get('/test', function () {
//   return view('vuedashboard');
// });

// ->where('any', '.*');
