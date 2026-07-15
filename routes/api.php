<?php

use App\Http\Controllers\Api\SampleController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ShipmentController;
use App\Http\Controllers\Api\LedgerController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Admin\Api\AdminSampleController;
use App\Http\Controllers\Admin\Api\AdminOrderController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Customer Portal Routes  ->  partners.ibacrafts.com/api/portal/*
| Guarded by: auth:sanctum + EnsureCustomerScope (forces company_id scoping)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:sanctum', 'customer.scope'])
    ->prefix('portal')
    ->group(function () {
        Route::get('dashboard', [\App\Http\Controllers\Api\DashboardController::class, 'index']);

        Route::get('samples', [SampleController::class, 'index']);
        Route::get('samples/{sample}', [SampleController::class, 'show']);
        Route::post('samples/{sample}/approve', [SampleController::class, 'approve']);
        Route::post('samples/{sample}/revise', [SampleController::class, 'requestRevision']);

        Route::get('orders', [OrderController::class, 'index']);
        Route::get('orders/{order}', [OrderController::class, 'show']);

        Route::get('shipments', [ShipmentController::class, 'index']);
        Route::get('shipments/{shipment}', [ShipmentController::class, 'show']);

        Route::get('ledger', [LedgerController::class, 'index']);
        Route::get('ledger/statement', [LedgerController::class, 'downloadStatement']);

        Route::get('messages', [MessageController::class, 'index']);
        Route::post('messages', [MessageController::class, 'store']);
    });

/*
|--------------------------------------------------------------------------
| Internal Admin Routes  ->  partners.ibacrafts.com/api/admin/*
| Guarded by: auth:sanctum + role:admin,super_admin
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:sanctum', 'role:admin,super_admin'])
    ->prefix('admin')
    ->group(function () {
        Route::apiResource('samples', AdminSampleController::class);
        Route::apiResource('orders', AdminOrderController::class);
        // ...shipments, ledger, companies (client onboarding), users management
    });
