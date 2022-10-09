<?php

/* use App\Http\Controllers\Products\CreateProductController;
use App\Http\Controllers\Products\UpdateProductController;
use App\Http\Controllers\Products\DeleteProductController; */
use App\Http\Controllers\Stats\GetStatsController;

Route::post('/obtener-estadisticas', [GetStatsController::class, '__invoke']);

