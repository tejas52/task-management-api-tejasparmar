<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProjectController;

//Register user route
Route::post('/register', [AuthController::class, 'register']);

//User login route
Route::post('/login', [AuthController::class, 'login']);

//allow access to only loggedin user
Route::middleware('auth:sanctum')->group(function () {

    // Authenticated user
    Route::get('/user', function (Request $request) {
        return response()->json([
            'status' => true,
            'data' => $request->user(),
        ]);
    });

    // Logout
    Route::post('/logout', [AuthController::class, 'logout']);

    // Projects CRUD (apiResource)
    Route::apiResource('projects', ProjectController::class);

});


