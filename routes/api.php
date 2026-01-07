<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\TaskController;
//Register user route
Route::post('/register', [AuthController::class, 'register']);
//User login route
Route::post('/login', [AuthController::class, 'login']);

//allow access to only loggedin user
Route::middleware('auth:sanctum')->group(function () {

    // Authenticated user profile
    Route::get('/user', function (Request $request) {
        return response()->json([
            'status' => true,
            'data' => $request->user(),
        ]);
    });

    // Logout
    Route::post('/logout', [AuthController::class, 'logout']);

    // Projects api route
    Route::apiResource('projects', ProjectController::class);

     // Tasks under a project api
    Route::get('/projects/{project}/tasks', [TaskController::class, 'index']);
    Route::post('/projects/{project}/tasks', [TaskController::class, 'store']);

    //get, update, delete task by id
    Route::get('/task/{task}', [TaskController::class, 'show']);
    Route::put('/tasks/{id}', [TaskController::class, 'update']);
    Route::delete('/tasks/{id}', [TaskController::class, 'destroy']);
});


