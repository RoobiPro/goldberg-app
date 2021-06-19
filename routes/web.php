<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileImportController;
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

// Route::get('/project/{id}/handsamples', [ProjectController::class ,'getProjectHandsamples']);
// Route::get('/project/{id}/drillingsamplelist', [ProjectController::class ,'getProjectDrillingSampleList']);
// Route::get('/project/{id}/drillings', [ProjectController::class, 'showDrillings']);
// Route::get('/project/{id}/wells', [ProjectController::class, 'getProjectWells']);


Route::get('/{any}', function () {
    return view('vuedashboard');
})->where('any', '.*');
