<?php

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

Route::group(['middleware' => ['jwt.verify']], function() {
    /*AÑADE AQUI LAS RUTAS QUE QUIERAS PROTEGER CON JWT*/
    Route::group(['namespace' => 'Api'], function() {
        Route::get('/layout-email', 'UserController@index')->name('layout');
        Route::get('/validar/{id}/{token}', 'UserController@validarCorreo')->name('validarCorreo');
        Route::get('/recuperar-contrasena/{id}/{token}', 'UserController@recuperarPassword')->name('recuperarContrasena');
    });
});

//Auth::routes();
//Route::get('/home', 'HomeController@index')->name('home');

