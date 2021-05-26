<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Http\Controllers\AuthController;
// use App\Http\Controllers\ProjectController;


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


Route::get('/refresh', [AuthController::class, 'refresh']);

Route::get('tableheader/{tablename}',  'TableController@index');


Route::middleware('auth:sanctum')->group(function () {
    Route::post('upload_avatar', [UserController::class, 'upload_user_photo']);
    Route::apiResource('projects', ProjectController::class);
    Route::apiResource('users', UserController::class);
    Route::apiResource('projects', ProjectController::class);
    Route::apiResource('drillings', DrillingController::class);
    Route::apiResource('campaign', CampaignController::class);
    Route::get('project/{id}/campaigns', 'ProjectController@showCampaigns');


    // Route::get('/refresh',function () {
    //   return "HURENSOHN";
    // });
    Route::post('upload_avatar', 'UserController@upload_user_photo');
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
