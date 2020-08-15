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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/layout-email', 'Api\UserController@index')->name('layout');
Route::get('/validar/{id}/{token}', 'Api\UserController@validarCorreo')->name('validarCorreo');
Route::get('/recuperar-contrasena/{id}/{token}', 'Api\UserController@recuperarPassword')->name('recuperarContrasena');
