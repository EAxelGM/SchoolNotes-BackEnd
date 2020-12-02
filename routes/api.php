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
Route::group(['namespace' => 'Api'], function() {
    //Login y Register para JWT
    Route::post('register', 'UserController@register');
    Route::post('login', 'UserController@authenticate');
    Route::get('profile', 'UserController@getAuthenticatedUser');
    Route::post('loggout', 'UserController@loggout');

    //Rutas para sitemap
    Route::get('slug-apuntes', 'ApunteController@aputnesSiteMap');

    /**ETIQUETAS */
    Route::resource('etiquetas', 'EtiquetaController')->only(['index']);

    /**APUNTES */
    Route::resource('apuntes', 'ApunteController');

    /**CODIGOS CREADORES */
    Route::resource('codigo-creador', 'CodigoCreadorController')->only(['show']);
    Route::get('codigo-creador-id/{id}', 'CodigoCreadorController@idCodigo');

    /**REENVIAR CORREO */
    Route::get('reenviar-correo-verificacion/{id}','UserController@enviarCorreo');
    Route::get('recuperar-password/{email}/{token}','UserController@recuperarPassword');
    Route::post('recuperar-password/{email}/{token}','UserController@recuperarPassword2');

});

Route::group(['middleware' => ['jwt.verify']], function() {
    /*AÑADE AQUI LAS RUTAS QUE QUIERAS PROTEGER CON JWT*/
    Route::group(['namespace' => 'Api'], function() {
        // Rutas de los controladores dentro del Namespace "App\Http\Controllers\Api"

        /** USUARIOS */
        Route::resource('usuarios', 'UserController');
        Route::post('cambiar-contrasena', 'UserController@cambiarContrasena');
        Route::post('usuario-img-perfil', 'UserController@imgPerfil');
        Route::get('re-enviar-correo-verificacion/{id}', 'UserController@enviarCorreo');
        Route::get('yo-sigo/{id}', 'BusquedasController@yoSigo');
        Route::get('me-siguen/{id}', 'BusquedasController@meSiguen');

        /**CODIGOS CREADORES */
        Route::resource('codigo-creador', 'CodigoCreadorController')->except(['show']);

        /**ETIQUETAS */
        Route::resource('etiquetas', 'EtiquetaController')->except(['index']);

        /**pUBLICACIONES */
        Route::resource('publicaciones', 'PublicacionController');

        /**PREGUNTAS */
        Route::resource('preguntas', 'PreguntaController');

        /**APUNTES */
        Route::get('apuntes/{id}/usuario', 'ApunteController@apuntesUsuario');
        Route::get('apuntes/{id}/documentos-guardados', 'ApunteController@apuntesGuardados');
        Route::post('upload-file', 'ApunteController@uploadFile');
        Route::post('comprar-apunte', 'ApunteController@comprarApunte');

        /**COMENTARIOS */
        Route::resource('comentarios', 'ComentarioController');

        /**RESPUESTAS */
        Route::resource('respuestas', 'RespuestaController');
        Route::post('valida-respuesta', 'RespuestaController@validaRespuesta');

        /**SEGUIMIENTOS */
        Route::post('seguir', 'SeguirController@seguir');

        /**REACCIONES */
        Route::post('reaccion', 'ReaccionController@index');

        /**CLIPS */
        Route::post('comprar-clips', 'ClipsController@compraClips');
        Route::post('intercambio-clips-a-diamonds', 'ClipsController@intercambioClipsADiamonds');
        Route::post('intercambio-diamonds-a-clips', 'ClipsController@intercambioDiamondsAClips');

        /**WARNINGS */
        Route::resource('warning', 'WarningController');

        /**BUSQUEDAS */
        Route::post('buscar', 'BusquedasController@busqueda');
        Route::post('buscar-etiqueta', 'BusquedasController@etiquetas');

        /**PORTAFOLIOS */
        Route::resource('portafolios', 'PortafolioController');
        Route::post('comprar-portafolio', 'PortafolioController@comprarPorta');
        Route::get('mis-portafolios/{id}', 'PortafolioController@misPorta');
        
        /**UNIVERSIDADES */
        Route::resource('universidad', 'UniversidadController');

        /**CARRERAS */
        Route::resource('carrera', 'CarreraController');

    });
});


Route::resource('categorias', 'Api\CategoriaController');
