<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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


Route::prefix('v1/')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('login', [AuthController::class, 'login']);
        Route::post('register', [AuthController::class, 'register']);
    });

    Route::middleware(['auth:api', 'admin'])->prefix('admin')->group(function () {
        Route::get('roles', [AdminController::class, 'getRoles']);
        Route::post('create/role', [AdminController::class, 'createRole']);
        Route::post('create/user/role', [AdminController::class, 'createUserRole']);
        Route::get('user/roles', [AdminController::class, 'getUserRoles']);
    });
});



//Route::middleware(['auth', 'role:admin'])->group(function () {
//    Route::get('dashboard', 'AdminController@dashboard');
//    Route::resource('products', 'ProductController');
//});
