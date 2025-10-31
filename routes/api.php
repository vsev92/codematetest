<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\AccountController;

Route::get('/balance/{user}', [AccountController::class, 'balance']);
Route::post('/deposit', [TransactionController::class, 'deposit']);
Route::post('/withdraw', [TransactionController::class, 'withdraw']);
Route::post('/transfer', [TransactionController::class, 'transfer']);
