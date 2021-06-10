<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\SampleListController;
use App\Http\Controllers\FileImportController;


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



Route::get('/deletespatial/{id}', 'FileImportController@deleteSpatial');

Route::get('/displayspatial/{id}', 'FileImportController@displaySpatial');

Route::get('/downloadspatial/{id}', 'FileImportController@downloadSpatial');

Route::post('/uploadspatial/{id}', 'FileImportController@uploadSpatial');


Route::post('/deletecsv/{id}', 'FileImportController@delete');
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


// Route::get('/login', function () {
//     // return view('welcome');
//     return view('vuedashboard');
// });
// Route::apiResource('users', UserController::class);
// Route::middleware('auth:sanctum')->group(function () {
//   Route::post('upload_avatar', [UserController::class, 'upload_user_photo']);
// });

Route::get('/sampletest', [SampleListController::class, 'index']);
// Route::post('upload_avatar', [UserController::class, 'upload_user_photo']);
// Route::post('/login', 'AuthController@login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::post('/register', [AuthController::class, 'register']);
// Route::apiResource('projects', ProjectController::class);
Route::get('/testproject', [ProjectController::class, 'testproject']);

Route::get('/getUsers', [UserController::class, 'getUsers']);
Route::get('/getClients', [UserController::class, 'getClients']);
Route::get('/getAdmins', [UserController::class, 'getAdmins']);

Route::get('/getUserProjects/{id}', [UserController::class, 'getUserProjects']);
Route::get('/getProjectSpatials/{id}', [ProjectController::class, 'getProjectSpatials']);



Route::post('/assignuser', [ProjectController::class, 'assignUser']);
Route::post('/assignclient', [ProjectController::class, 'assignClient']);

Route::post('/unassignUser', [ProjectController::class, 'unassignUser']);

// Route::get('projects', ProjectController::class);
Route::get('/getProjectUsers/{id}', [ProjectController::class, 'getProjectUsers']);

Route::post('/reassignUser', [ProjectController::class, 'reassignUser']);

Route::get('/getCampaign/{id}', [CampaignController::class, 'show']);
Route::get('/getProject/{id}', [ProjectController::class, 'show']);

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
