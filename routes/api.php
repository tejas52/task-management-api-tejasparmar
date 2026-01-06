<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;


//Register user route
Route::post('/register', [AuthController::class, 'register']);

//User login route
Route::post('/login', [AuthController::class, 'login']);

//allow access to only loggedin user
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', function (Request $request) {
        return response()->json([
            'status' => true,
            'data' => $request->user()
        ]);
    });

    Route::post('/logout', [AuthController::class, 'logout']);
});

