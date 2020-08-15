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

//Login y Register para JWT
Route::post('register', 'Api\UserController@register');
Route::post('login', 'Api\UserController@authenticate');
Route::get('profile', 'Api\UserController@getAuthenticatedUser');
Route::post('loggout', 'Api\UserController@loggout');

Route::group(['middleware' => ['jwt.verify']], function() {
    /*AÑADE AQUI LAS RUTAS QUE QUIERAS PROTEGER CON JWT*/
});

Route::resource('usuarios', 'Api\UserController');
Route::post('cambiar-contrasena', 'Api\UserController@cambiarContrasena');
Route::post('usuario-img-perfil', 'Api\UserController@imgPerfil');

Route::resource('publicaciones', 'Api\PublicacionController');
Route::resource('apuntes', 'Api\ApunteController');
Route::resource('etiquetas', 'Api\EtiquetaController');
Route::resource('categorias', 'Api\CategoriaController');
Route::resource('comentarios', 'Api\ComentarioController');
Route::resource('preguntas', 'Api\PreguntaController');
Route::resource('respuestas', 'Api\RespuestaController');

Route::post('seguir', 'Api\SeguirController@seguir');
Route::post('reaccion', 'Api\ReaccionController@index');



Route::get('re-enviar-correo-verificacion/{id}', 'Api\UserController@enviarCorreo');
