<?php

use Illuminate\Http\Request;

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
Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::get('me', 'AuthController@me');
    Route::post('registrar', 'AuthController@registrar');

});

Route::apiResource('clientes', 'ClientesController');
Route::post('clientes/{id}/financiamientos', 'ClientesController@financiamientos');
Route::post('clientes/{id}/solicitudes', 'ClientesController@solicitudes');

Route::post('financiamientos/buscar', 'FinanciamientosController@buscar');
Route::post('financiamientos/{id}/activar', 'FinanciamientosController@activar');
Route::post('financiamientos/{id}/inactivar', 'FinanciamientosController@inactivar');
Route::post('financiamientos/{id}/cancelar', 'FinanciamientosController@cancelar');

Route::post('pagos/buscar', 'PagosController@buscar');
Route::post('pagos/{id}/pagar', 'PagosController@pagar');
Route::post('pagos/{id}/deshacer', 'PagosController@deshacer');

Route::get('tasas/{tipo}', 'TasasController@index');
Route::get('tasas/obtenerTasaPorMonto/{tipo}/{monto}', 'TasasController@show');
Route::apiResource('tasas', 'TasasController');
Route::post('tasas/calcular/prestamo', 'TasasController@calcular');

Route::apiResource('solicitudes', 'SolicitudesController');
Route::post('solicitudes/buscar', 'SolicitudesController@buscar');
Route::post('solicitudes/{id}/cancelar', 'SolicitudesController@cancelar');
Route::post('solicitudes/{id}/aprobar', 'SolicitudesController@aprobar');
Route::post('solicitudes/{id}/declinar', 'SolicitudesController@declinar');








