<?php

use App\Http\Controllers\Sales\CreateSaleController;
use App\Http\Controllers\Sales\UpdateSaleController;
use App\Http\Controllers\Sales\DeleteSaleController;
use App\Http\Controllers\Sales\GetSalesController;

Route::post('/admin-ventas', [GetSalesController::class, '__invoke']);
Route::post('/ventas-crear', [CreateSaleController::class, '__invoke']);
Route::post('/ventas-actualizar', [UpdateSaleController::class, '__invoke']);
Route::post('/ventas-eliminiar', [DeleteSaleController::class, '__invoke']);