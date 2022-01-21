<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::group(['middleware' => ['cors']], function () {

    Route::group([
        //'domain'     => 'http://127.0.0.1:5000/admin/public'
        //'prefix'     => 'admin',
        //'namespace'  => 'Admin'
        //'middleware' => 'role:admin'
    ], function () {


    /* RUTAS CATEGORIAS */

    Route::post('/admin/categorias', 'AdminCategoriasController@store');

    Route::get('/admin/categorias/edit/{id?}', 'AdminCategoriasController@getCategoriasEdit')->name('categorias.editar');

    Route::patch('/admin/categorias/update/{id?}', 'AdminCategoriasController@postCategoriasEdit')->name('categorias.update');

    Route::delete('/admin/categorias/delete/{id?}', 'AdminCategoriasController@destroy')->name('categorias.eliminar');


    /* RUTAS PRODUCTOS */

    Route::post('/admin/productos', 'AdminProductosController@store');

    Route::get('/admin/productos/edit/{id?}', 'AdminProductosController@getProductosEdit')->name('productos.editar');

    Route::patch('/admin/productos/update/{id?}', 'AdminProductosController@postProductosEdit')->name('productos.update');

    Route::delete('/admin/productos/delete/{id?}', 'AdminProductosController@destroy')->name('productos.eliminar');


    /* RUTAS PROVEEDORES */

    Route::post('/admin/proveedores', 'AdminProveedoresController@store');

    Route::get('/admin/proveedores/edit/{id?}', 'AdminProveedoresController@getProveedorEdit')->name('proveedores.editar');

    Route::patch('/admin/proveedores/update/{id?}', 'AdminProveedoresController@postProveedorEdit')->name('proveedores.update');

    Route::delete('/admin/proveedores/delete/{id?}', 'AdminProveedoresController@destroy')->name('proveedores.eliminar');


    /* RUTAS CLIENTES */

    Route::post('/admin/clientes', 'AdminClientesController@store');

    Route::get('/admin/clientes/edit/{id?}', 'AdminClientesController@getClienteEdit')->name('clientes.editar');

    Route::patch('/admin/clientes/update/{id?}', 'AdminClientesController@postClienteEdit')->name('clientes.update');

    Route::delete('/admin/clientes/delete/{id?}', 'AdminClientesController@destroy')->name('clientes.eliminar');


    /* RUTAS COMPRAS */

    Route::post('/admin/compras', 'AdminComprasController@store');

    Route::post('/admin/compras-detalles', 'AdminComprasController@AgregarProductoCompra');

    Route::patch('/admin/compras/update/{id?}', 'AdminComprasController@postComprasEdit')->name('compras.update');

    Route::delete('/admin/compras/delete/{id?}', 'AdminComprasController@destroyProductoCompra')->name('compras.eliminar');


 /* RUTAS VENTAS */

    Route::post('/admin/ventas', 'AdminVentasController@store');

    Route::post('/admin/ventas-detalles', 'AdminVentasController@AgregarProductoVentas');

    Route::patch('/admin/ventas/update/{id?}', 'AdminVentasController@postVentasEdit')->name('ventas.update');//

    Route::delete('/admin/ventas/delete/{id?}', 'AdminVentasController@destroyProductoVentas')->name('ventas.eliminar');


 /* RUTAS REPORTES ALMACEN */

    Route::get('/admin/reportes/almacen', 'ReportesController@ReportesAlmacen');

    Route::get('/admin/reportes/ingresos-por-fecha', 'ReportesController@ResultReporteIngresosPorFecha');

    Route::post('/admin/reportes/ingresos-por-fecha', 'ReportesController@ResultReporteIngresosPorFecha');

    Route::get('/admin/reportes/salidas-por-fecha', 'ReportesController@ResultReporteSalidasPorFecha');

    Route::post('/admin/reportes/salidas-por-fecha', 'ReportesController@ResultReporteSalidasPorFecha');

    });

});
