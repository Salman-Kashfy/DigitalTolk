<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Modules\Translation\Http\Controllers\TranslationController;
use App\Modules\Auth\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Config;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/health', function (Request $request) {
    $defaultConnection = Config::get('database.default'); // This should be 'mysql' as per your setup
    $databaseName = Config::get("database.connections.{$defaultConnection}.database"); // Should be 'laravel_docker'
    $databaseUser = Config::get("database.connections.{$defaultConnection}.username"); // Should be 'user'
    $databaseHost = Config::get("database.connections.{$defaultConnection}.host"); // This will typically be 'mysql' (the service name in docker-compose)

    return response()->json([
        'status' => true,
        'message' => 'Hello world!',
        'database_name' => $databaseName,
        'database_user' => $databaseUser, // <--- Add this line
        'database_host' => $databaseHost  // <--- Add this line
    ]);
});

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::apiResource('translations', TranslationController::class);
    Route::get('translations/export', [TranslationController::class, 'export'])->name('translations.export');

    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
});
