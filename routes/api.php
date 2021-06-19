<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\SampleListController;
use App\Http\Controllers\FileImportController;
use App\Http\Controllers\DrillingController;


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
Route::post('/login', [AuthController::class, 'login'])->name('login');


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/getUsers', [UserController::class, 'getUsers']);
    Route::get('/getClients', [UserController::class, 'getClients']);
    Route::get('/getAdmins', [UserController::class, 'getAdmins']);
    Route::get('/getUserProjects/{id}', [UserController::class, 'getUserProjects']);
    Route::get('/getProjectSpatials/{id}', [ProjectController::class, 'getProjectSpatials']);
    Route::post('/assignuser', [ProjectController::class, 'assignUser']);
    Route::post('/assignclient', [ProjectController::class, 'assignClient']);
    Route::post('/unassignUser', [ProjectController::class, 'unassignUser']);
    Route::get('/getProjectUsers/{id}', [ProjectController::class, 'getProjectUsers']);
    Route::post('/reassignUser', [ProjectController::class, 'reassignUser']);
    Route::get('/getProject/{id}', [ProjectController::class, 'show']);
    Route::get('tableheader/{tablename}',  [TableController::class, 'index']);
    Route::get('/getdrillings/{id}', [ProjectController::class, 'showDrillings']);
    Route::get('/getwells/{id}', [ProjectController::class, 'getProjectWells']);
    Route::get('/gethandsamples/{id}/', [ProjectController::class ,'getProjectHandsamples']);
    Route::get('/getdrillingsamplelist/{id}', [ProjectController::class ,'getProjectDrillingSampleList']);
    Route::get('/getwellsamplelist/{id}', [ProjectController::class ,'getProjectWellSampleList']);
    Route::get('/gethandsamplesamplelist/{id}', [ProjectController::class ,'getProjectHandSampleSampleList']);
    Route::get('/getprojectdata/{id}', [ProjectController::class,'getProjectData']);
    Route::get('/getimports/{id}', [FileImportController::class, 'getImports']);
    Route::get('/deletespatial/{id}', [FileImportController::class,'deleteSpatial']);
    Route::get('/displayspatial/{id}', [FileImportController::class, 'displaySpatial']);
    Route::get('/downloadspatial/{id}', [FileImportController::class, 'downloadSpatial']);
    Route::post('/uploadspatial/{id}', [FileImportController::class, 'uploadSpatial']);
    Route::post('/deletecsv/{id}', [FileImportController::class, 'delete']);
    Route::post('/importcsv/handsample', [FileImportController::class, 'storeHandSample']);
    Route::post('/importcsv/drilling', [FileImportController::class, 'storeDrilling']);
    Route::post('/importcsv/well', [FileImportController::class, 'storeWell']);
    Route::post('/importcsv/drilling/alteration', [FileImportController::class, 'storeDrillingAlteration']);
    Route::post('/importcsv/drilling/assay', [FileImportController::class, 'storeDrillingAssay']);
    Route::post('/importcsv/drilling/lithology', [FileImportController::class, 'storeDrillingLithology']);
    Route::post('/importcsv/drilling/mineralization', [FileImportController::class, 'storeDrillingMiniralization']);
    Route::post('/importcsv/drilling/survey', [FileImportController::class, 'storeDrillingSurvey']);
    Route::post('/importcsv/well/assay', [FileImportController::class, 'storeWellAssay']);
    Route::post('/importcsv/well/lithology', [FileImportController::class, 'storeWellLithology']);
    Route::post('/importcsv/well/survey', [FileImportController::class, 'storeWellSurvey']);
    Route::post('/importcsv/drilling/samplelist', [FileImportController::class, 'storeDrillingSamplelist']);
    Route::post('/importcsv/handsample/samplelist', [FileImportController::class, 'storeHandsampleSamplelist']);
    Route::post('/importcsv/well/samplelist', [FileImportController::class, 'storeWellSamplelist']);
    Route::get('project/{id}/wells', [ProjectController::class, 'getProjectWells']);
    Route::post('upload_avatar', [UserController::class, 'upload_user_photo']);
    Route::apiResource('users', UserController::class);
    Route::apiResource('projects', ProjectController::class);
    Route::apiResource('drillings', DrillingController::class);
    Route::apiResource('projects', ProjectController::class);
});
