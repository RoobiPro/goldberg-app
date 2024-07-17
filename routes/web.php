<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileImportController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\UserController;

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

// In routes/web.php
// In routes/web.php
Route::get('/env', function () {
    return response()->json([
        'APP_NAME' => env('APP_NAME'),
        'APP_ENV' => env('APP_ENV'),
        'APP_KEY' => env('APP_KEY'),
        'APP_DEBUG' => env('APP_DEBUG'),
        'APP_URL' => env('APP_URL'),

        'LOG_CHANNEL' => env('LOG_CHANNEL'),
        'LOG_LEVEL' => env('LOG_LEVEL'),

        'DB_CONNECTION' => env('DB_CONNECTION'),
        'DB_HOST' => env('DB_HOST'),
        'DB_PORT' => env('DB_PORT'),
        'DB_DATABASE' => env('DB_DATABASE'),
        'DB_USERNAME' => env('DB_USERNAME'),
        'DB_PASSWORD' => env('DB_PASSWORD'),

        'BROADCAST_DRIVER' => env('BROADCAST_DRIVER'),
        'CACHE_DRIVER' => env('CACHE_DRIVER'),
        'QUEUE_CONNECTION' => env('QUEUE_CONNECTION'),

        'SESSION_DRIVER' => env('SESSION_DRIVER'),
        'SESSION_LIFETIME' => env('SESSION_LIFETIME'),
        'SESSION_SECURE_COOKIE' => env('SESSION_SECURE_COOKIE'),
        'SANCTUM_STATEFUL_DOMAINS' => env('SANCTUM_STATEFUL_DOMAINS'),
        'SESSION_DOMAIN' => env('SESSION_DOMAIN'),

        'MEMCACHED_HOST' => env('MEMCACHED_HOST'),

        'REDIS_HOST' => env('REDIS_HOST'),
        'REDIS_PASSWORD' => env('REDIS_PASSWORD'),
        'REDIS_PORT' => env('REDIS_PORT'),

        'MAIL_MAILER' => env('MAIL_MAILER'),
        'MAIL_HOST' => env('MAIL_HOST'),
        'MAIL_PORT' => env('MAIL_PORT'),
        'MAIL_USERNAME' => env('MAIL_USERNAME'),
        'MAIL_PASSWORD' => env('MAIL_PASSWORD'),
        'MAIL_ENCRYPTION' => env('MAIL_ENCRYPTION'),
        'MAIL_FROM_ADDRESS' => env('MAIL_FROM_ADDRESS'),
        'MAIL_FROM_NAME' => env('MAIL_FROM_NAME'),

        'AWS_ACCESS_KEY_ID' => env('AWS_ACCESS_KEY_ID'),
        'AWS_SECRET_ACCESS_KEY' => env('AWS_SECRET_ACCESS_KEY'),
        'AWS_DEFAULT_REGION' => env('AWS_DEFAULT_REGION'),
        'AWS_BUCKET' => env('AWS_BUCKET'),

        'PUSHER_APP_ID' => env('PUSHER_APP_ID'),
        'PUSHER_APP_KEY' => env('PUSHER_APP_KEY'),
        'PUSHER_APP_SECRET' => env('PUSHER_APP_SECRET'),
        'PUSHER_APP_CLUSTER' => env('PUSHER_APP_CLUSTER'),

        'MIX_PUSHER_APP_KEY' => env('MIX_PUSHER_APP_KEY'),
        'MIX_PUSHER_APP_CLUSTER' => env('MIX_PUSHER_APP_CLUSTER'),
    ]);
});




Route::get('/{any}', function () {
    return view('vuedashboard');
})->where('any', '.*');
