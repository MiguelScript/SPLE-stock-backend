<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Products\ProductsController;
use App\Http\Controllers\Sales\SalesController;
use App\Http\Controllers\Compras\ComprasController;
use App\Http\Controllers\Customers\CustomersController;
use App\Http\Controllers\DollarRate\DollarRateController;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

/* Route::post('/login', [AuthController::class, 'authenticate']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout']); */
// include("admin-routes/usuarios.php");


Route::group([
    'prefix' => 'auth'

], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/me', [AuthController::class, 'get_user']);
});



Route::group(['middleware' => ['jwt.verify']], function () {
    /*AÑADE AQUI LAS RUTAS QUE QUIERAS PROTEGER CON JWT*/
});

// Route::apiResource('productos', ProductsController::class);

// Route::apiResource('ventas', SalesController::class);

#region productos
Route::controller(ProductsController::class)->group(function () {
    Route::get('/productos', 'index');
    Route::get('/productos/search', 'search');
    Route::post('/productos', 'store');
    Route::put('/productos/{id}', 'update');
    Route::put('/productos/{id}/cambiar-status', 'changeStatus');
});
#endregion

#region productos
Route::controller(CustomersController::class)->group(function () {
    Route::get('/clientes', 'index');
    Route::get('/clientes/{id}', 'get_customer_data');
    Route::post('/clientes', 'store');
    Route::put('/clientes/{id}', 'update');
    Route::put('/clientes/{id}/cambiar-status', 'changeStatus');
});
#endregion

#region compras
Route::controller(ComprasController::class)->group(function () {
    Route::get('/compras', 'index');
    // Route::get('/compras', 'getAll');
    Route::post('/compras', 'store');
    Route::put('/compras/{id}', 'update');
    Route::put('/compras/{id}/cambiar-status', 'changeStatus');
});
#endregion

#region ventas
Route::controller(SalesController::class)->group(function () {
    Route::get('/ventas', 'get_ventas');
    Route::get('/ventas/nueva', 'new_sale_get_data');
    Route::post('/ventas', 'store');
    Route::get('/ventas/{id}', 'show');
    Route::put('/ventas/{id}/cambiar-status', 'changeStatus');
});
#endregion


#region tasa-dolar
Route::controller(DollarRateController::class)->group(function () {
    Route::get('/tasa-dolar/actual', 'get_current');
    Route::post('/tasa-dolar', 'store');
});
#endregion
