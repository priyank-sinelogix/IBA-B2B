<?php

use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\SampleWebController;
use App\Http\Controllers\Web\OrderWebController;
use App\Http\Controllers\Web\ShipmentWebController;
use App\Http\Controllers\Web\FinanceWebController;
use App\Http\Controllers\Web\MessageWebController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect('/login'));

// Guest
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Authenticated customer portal (AdminLTE views)
Route::middleware(['auth', 'customer.scope'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/dashboard', [DashboardController::class, 'index']);

    Route::get('/samples', [SampleWebController::class, 'index']);
    Route::get('/samples/{sample}', [SampleWebController::class, 'show']);
    Route::post('/samples/{sample}/approve', [SampleWebController::class, 'approve']);
    Route::post('/samples/{sample}/revise', [SampleWebController::class, 'requestRevision']);

    Route::get('/orders', [OrderWebController::class, 'index']);

    Route::get('/shipments', [ShipmentWebController::class, 'index']);

    Route::get('/finance', [FinanceWebController::class, 'index']);
    Route::get('/finance/statement/download', [FinanceWebController::class, 'downloadStatement']);

    Route::get('/messages', [MessageWebController::class, 'index']);
    Route::post('/messages', [MessageWebController::class, 'store']);
});
