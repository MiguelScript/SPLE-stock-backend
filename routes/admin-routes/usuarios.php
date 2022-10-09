<?php

use App\Http\Controllers\Users\CreateUserController;
use App\Http\Controllers\Users\UpdateUserController;
use App\Http\Controllers\Users\DeleteUserController;
use App\Http\Controllers\Users\GetUsersController;

Route::post('/admin-usuarios', [GetUsersController::class, '__invoke']);
Route::post('/usuarios-crear', [CreateUserController::class, 'make']);
Route::post('/usuarios-actualizar', [UpdateUserController::class, '__invoke']);
Route::post('/usuarios-eliminiar', [DeleteUserController::class, '__invoke']);
