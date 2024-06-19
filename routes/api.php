<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\TransactionController;
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
//        Route::post('register', [AuthController::class, 'register']);
    });

    Route::middleware(['auth:api', 'admin'])->prefix('admin')->group(function () {
        Route::post('create/user', [AdminController::class, 'createUser']);
        Route::post('users', [AdminController::class, 'getUsers']);
        Route::get('roles', [AdminController::class, 'getRoles']);
        Route::post('create/role', [AdminController::class, 'createRole']);
        Route::post('create/user/role', [AdminController::class, 'createUserRole']);
        Route::get('user/roles', [AdminController::class, 'getUserRoles']);

        Route::post('create/main-store-product', [TransactionController::class, 'createMainStoreData']);
        Route::get('main-store-products', [AdminController::class, 'getMainStoreProducts']);
        Route::post('create/outlet-store-product', [TransactionController::class, 'createOutletStoreData']);
        Route::get('outlet-store-products', [AdminController::class, 'getOutletStoreProducts']);
        Route::post('create/transaction-detail', [TransactionController::class, 'createTransactionDetail']);

        Route::post('approve/transaction/{id}', [TransactionController::class, 'approveTxDetail']);

        Route::get('transactions', [TransactionController::class, 'getTransactions']);
        Route::get('approved/transactions', [TransactionController::class, 'getApprovedTransactions']);
    });

    Route::middleware(['auth:api', 'managerial'])->prefix('management')->group(function (){
        Route::get('main-store-products', [AdminController::class, 'getMainStoreProducts']);
        Route::get('outlet-store-products', [AdminController::class, 'getOutletStoreProducts']);
        Route::get('transactions', [TransactionController::class, 'getTransactions']);
        Route::get('approved/transactions', [TransactionController::class, 'getApprovedTransactions']);

    });
});



//Route::middleware(['auth', 'role:admin'])->group(function () {
//    Route::get('dashboard', 'AdminController@dashboard');
//    Route::resource('products', 'ProductController');
//});
