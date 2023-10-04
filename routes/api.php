<?php

use App\Http\Controllers\Api\V1\InvoiceController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TesteController;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::prefix('/v1')->group(function () {
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{user}', [UserController::class, 'show']);

    Route::apiResource('invoices', InvoiceController::class)->middleware('auth:sanctum');

    Route::get('/filter/invoices', [InvoiceController::class, 'filter']);

    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/teste', [TesteController::class, 'index'])->middleware('auth:sanctum');
});
