<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Http\Controllers\AuthController;


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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::middleware('auth:sanctum')->get('/users/{user}', function (Request $request) {
//     return $request->user();
// });

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('users', UserController::class);
    Route::apiResource('projects', ProjectController::class);

    // Route::get('/refresh',function () {
    //   return "HURENSOHN";
    // });

    Route::get('/refresh', [AuthController::class, 'refresh']);
    // Route::get('user', [UserController::class, 'getLoggedInUser']);
    // Route::apiResource('user', UserController::class);
});

// Route::apiResource('users', UserController::class);

// Route::apiResource([
//     'users' => UserController::class
//     // 'projects' => ProjectController:class,
//     // 'drillings' => DrillingsController:class
//   ]);

// Route::get('/user/{id}', function ($id) {
//     return new UserResource(User::findOrFail($id));
// });
