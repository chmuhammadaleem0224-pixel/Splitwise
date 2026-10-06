<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\BalanceController;
use App\Http\Controllers\SettlementController;
use App\Support\ApiResponse;

Route::get('/', function () {
    return app(ApiResponse::class)->success('Splitwise API is running.', [
        'service' => 'splitwise-api',
        'status' => 'ok',
    ]);
});

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth.token')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('me', [AuthController::class, 'me']);

    Route::apiResource('groups', GroupController::class);
    Route::get('groups/{group}/members', [GroupController::class, 'members']);
    Route::post('groups/{group}/members', [GroupController::class, 'addMember']);
    Route::delete('groups/{group}/members/{user}', [GroupController::class, 'removeMember']);

    Route::get('groups/{group}/expenses', [ExpenseController::class, 'index']);
    Route::post('groups/{group}/expenses', [ExpenseController::class, 'store']);
    Route::get('expenses/{expense}', [ExpenseController::class, 'show']);
    Route::put('expenses/{expense}', [ExpenseController::class, 'update']);
    Route::delete('expenses/{expense}', [ExpenseController::class, 'destroy']);

    Route::get('groups/{group}/balances', [BalanceController::class, 'balances']);
    Route::get('groups/{group}/debts', [BalanceController::class, 'debts']);

    Route::get('groups/{group}/settlements', [SettlementController::class, 'index']);
    Route::post('groups/{group}/settlements', [SettlementController::class, 'store']);
});
