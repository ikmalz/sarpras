<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BorrowingApiController;
use App\Http\Controllers\API\CategoryApiController;
use App\Http\Controllers\API\ItemApiController;
use App\Http\Controllers\API\ReturningApiController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\ItemController;
use App\Models\EmergencyRequest;
use Illuminate\Support\Facades\Auth;

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
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::put('/users/{id}', [AuthController::class, 'update']);
    Route::delete('/users/{id}', [AuthController::class, 'destroy']);

    Route::prefix('categories')->group(function () {
        Route::get('/index', [CategoryApiController::class, 'index']);
        Route::get('/show/{id}', [CategoryApiController::class, 'show']);
        Route::post('/store', [CategoryApiController::class, 'store']);
        Route::put('/update/{id}', [CategoryApiController::class, 'update']);
        Route::delete('/destroy/{id}', [CategoryApiController::class, 'destroy']);
        Route::get('/{id}/items', [CategoryApiController::class, 'items']);
    });

    Route::prefix('items')->group(function () {
        Route::get('/index', [ItemApiController::class, 'index']);
        Route::get('/show/{id}', [ItemApiController::class, 'show']);
        Route::post('/store', [ItemApiController::class, 'store']);
        Route::put('/update/{id}', [ItemApiController::class, 'update']);
        Route::delete('/destroy/{id}', [ItemApiController::class, 'destroy']);
    });

    Route::prefix('borrowings')->group(function () {
        Route::get('/', [BorrowingApiController::class, 'index']);
        Route::post('/store', [BorrowingApiController::class, 'store']);
        Route::put('/approve/{id}', [BorrowingApiController::class, 'approve']);
        Route::put('/reject/{id}', [BorrowingApiController::class, 'reject']);
        Route::put('/taken/{id}', [BorrowingApiController::class, 'markAsTaken']);
    });

    Route::prefix('returnings')->group(function () {
        Route::post('/return/{id}', [ReturningApiController::class, 'returnItem']);
        Route::put('/approve/{id}', [ReturningApiController::class, 'approve']);
    });
});

Route::middleware('auth:sanctum')->get('/admin/notifications', function () {
    return response()->json(Auth::user()->notifications);
});

Route::middleware('auth:sanctum')->post('/logout', function (Request $request) {
    $request->user()->currentAccessToken()->delete();
    return response()->json(['message' => 'Logout berhasil']);
});
