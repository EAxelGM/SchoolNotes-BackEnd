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

Route::group(['middleware' => ['jwt.verify']], function() {
    /*AÑADE AQUI LAS RUTAS QUE QUIERAS PROTEGER CON JWT*/
});

Route::group(['namespace' => 'Api'], function() {
    // Rutas de los controladores dentro del Namespace "App\Http\Controllers\Api"

    //Login y Register para JWT
    Route::post('register', 'UserController@register');
    Route::post('login', 'UserController@authenticate');
    Route::get('profile', 'UserController@getAuthenticatedUser');
    Route::post('loggout', 'UserController@loggout');
    
    /** USUARIOS */
    Route::resource('usuarios', 'UserController');
    Route::post('cambiar-contrasena', 'UserController@cambiarContrasena');
    Route::post('usuario-img-perfil', 'UserController@imgPerfil');
    Route::get('re-enviar-correo-verificacion/{id}', 'UserController@enviarCorreo');
    
    /**pUBLICACIONES */
    Route::resource('publicaciones', 'PublicacionController');
    
    /**APUNTES */
    Route::resource('apuntes', 'ApunteController');
    Route::get('apuntes/{id}/usuario', 'ApunteController@apuntesUsuario');
    Route::post('upload-file', 'ApunteController@uploadFile');
    Route::post('comprar-apunte', 'ApunteController@comprarApunte');
    
    /**ETIQUETAS */
    Route::resource('etiquetas', 'EtiquetaController');

    /**COMENTARIOS */
    Route::resource('comentarios', 'ComentarioController');

    /**PREGUNTAS */
    Route::resource('preguntas', 'PreguntaController');

    /**RESPUESTAS */
    Route::resource('respuestas', 'RespuestaController');

    /**SEGUIMIENTOS */
    Route::post('seguir', 'SeguirController@seguir');

    /**REACCIONES */
    Route::post('reaccion', 'ReaccionController@index');

    /**RESPUESTAS */
    Route::post('valida-respuesta', 'RespuestaController@validaRespuesta');

    /**CLIPS */
    Route::post('comprar-clips', 'ClipsController@compraClips');
    
});

Route::resource('categorias', 'Api\CategoriaController');


