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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(['prefix' => 'v1'],function () {
    Route::post('authenticate', 'AuthController@authenticate');
    Route::get('entidades', 'EntidadController@getEntidad');

    Route::group( ['middleware' => ['jwt.auth']], function () {
        /**
         * Obtener Todos los Permisos y Todos los Roles
         */
        Route::get('role/all', 'UserController@getAllRoles');
        Route::get('permission/all', 'UserController@getAllPermissions');
        /**
         * Gestion de Usuarios
         */
        Route::get('users', 'UserController@index');
        Route::get('users/{id}', 'UserController@show');
        Route::delete('user/delete/{id}', 'UserController@destroy');
        Route::post('user/create', 'UserController@create');
        Route::put('user/update/{id}', 'UserController@update');
        Route::get('user', 'AuthController@show');
        Route::get('token', 'AuthController@getToken');

        /**
         * Rutas del plan Tecnico Economico
         */


        Route::get('user', 'AuthController@user');
        Route::post('logout', 'AuthController@logout');





        Route::get('indicadores/ventas/{entidad}', 'IndicatorsController@getVentas');
        Route::get('indicadores/gastos/{entidad}', 'IndicatorsController@getGastos');
        Route::get('indicadores/ingresos/{entidad}', 'IndicatorsController@getIngresos');
        Route::get('indicadores/utilidades/{entidad}', 'IndicatorsController@getUtilidades');


        /**
         * Rutas indicadores por trimestre
         *
         */

        Route::get('indicadores/ventastrimestrales/{entidad}', 'IndicatorsController@getVentasTrimestrales');
        Route::get('indicadores/gastostrimestrales/{entidad}', 'IndicatorsController@getGastosTrimestrales');
        Route::get('indicadores/ingresostrimestrales/{entidad}', 'IndicatorsController@getIngresosTrimestrales');




        /**
         *  Rutas servicio  facturacion
         *
         */

        Route::get('indicadores/facturacion-almacen', 'BillingController@getFacturacionAlmacen');
        Route::get('indicadores/facturacion-servicio', 'BillingController@getFacturacionServicio');

        /**
         *  Rutas servicio  Personal
         *
         */

        Route::get('personal/trabajadores', 'personalController@getCantidadTrabajadores');






    });
});
Route::middleware('jwt.refresh')->get('/token/refresh', 'AuthController@refresh');

