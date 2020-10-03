<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\Funciones;
use App\User;
use App\Apunte;

class ApunteController extends Controller
{
    use Funciones;

    public function index()
    {
        //
    }
    
    public function create()
    {
        //
    }
    
    public function store(Request $request)
    {
        $user = User::find($request->user_id);
        if(!$user){
            return response()->json([
                'message' => 'Error al encontrar al usuario...',

            ],404);
        }

        $subir = $this->subirFile($user, $request->file('file'), $request->titulo);
        
        if($subir['path'] == null){
            return response()->json([
                'message' => $subir['message'],
                'data' => $subir['path'],
            ],$subir['code']);
        }
        $slug = explode(".", $subir['casi_slug']);

        $apunte = new Apunte;
        $apunte->titulo = $request->titulo;
        $apunte->slug = $slug[0];
        $apunte->descripcion = $request->descripcion;
        $apunte->archivo = $subir['path'];
        $apunte->user_id = $user->_id;
        $apunte->etiquetas_ids = $request->etiquetas_ids;
        $apunte->reacciones = [];
        $apunte->activo = 1;
        $apunte->save();
        $apunte->user;

        return response()->json([
            'message' => $subir['message'],
            'data' => $apunte,
        ],$subir['code']);
    }
    
    public function show($id)
    {
        //
    }
    
    public function edit($id)
    {
        //
    }
    
    public function update(Request $request, $id)
    {
        //
    }
    
    public function destroy($id)
    {
        //
    }

    public function uploadFile(Request $request){
        $user = User::find($request->user_id);
        if(!$user){
            return response()->json([
                'message' => 'Error al encontrar al usuario...',

            ],404);
        }

        $subir = $this->subirFile($user, $request->file('file'), $request->titulo);

        return response()->json([
            'message' => $subir['message'],
            'path' => $subir['path'],
        ],$subir['code']);


    }
}
