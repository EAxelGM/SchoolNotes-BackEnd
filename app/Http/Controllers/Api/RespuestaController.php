<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\Validaciones;
use App\Respuesta;
use App\User;

class RespuestaController extends Controller
{
    use Validaciones;

    public function index()
    {
        $respuestas = Respuesta::with('user:name,apellidos,img_perfil')->orderBy('created_at', 'DESC')->paginate(4);
        return response()->json([
            'message' => 'Success',
            'data' => $respuestas,
        ],200);
    }
    
    public function store(Request $request)
    {
        $validator = $this->datosRespuesta($request->all());
        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        $user = User::find($request->user_id);
        $valida = $this->userActivo($user);
        if($valida['code'] != 200){
            return response()->json([
                'message' => $valida['mensaje'],
            ],$valida['code']);
        }

        $respuesta = new Respuesta;
        $respuesta->contenido = $request->contenido;
        $respuesta->user_id = $request->user_id;
        $respuesta->pregunta_id = $request->pregunta_id;
        $respuesta->verificado = 0;
        $respuesta->reacciones = [];
        $respuesta->save();

        return response()->json([
            'message' => 'Respuesta creada',
            'data' => $respuesta,
        ],200);
    }
    
    public function show($id)
    {
        $respuesta = Respuesta::with(['user:name,apellidos,img_perfil','pregunta.user:name,apellidos,img_perfil'])->find($id);
        if(!$respuesta){
            return response()->json([
                'message' => 'No se encontre esta respuesta',
            ],404);
        }
        /* $respuesta->user;
        $respuesta->publicacion; */
        return response()->json([
            'message' => 'Se encontro la respuesta',
            'data' => $respuesta,
        ],200);


    }
    
    public function update(Request $request, $id)
    {
        $respuesta = Respuesta::find($id);
        if(!$respuesta){
            return response()->json([
                'message' => 'No se encontro esta respuesta',
            ],404);
        }

        $validator = $this->datosRespuesta($request->all());
        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }
        
        if($respuesta->user_id != $request->user_id){
            return response()->json([
                'message' => 'Lo sentimos pero no eres dueño de esta respuesta',
            ],421);
        }

        $respuesta->fill($request->all());
        if($respuesta->isClean()){
            return response()->json(['message'=>'Especifica al menos un valor diferente'],421);
        }

        $respuesta->save();
        return response()->json([
            'message' => 'respuesta modificada',
            'data' => $respuesta,
        ],200);
    }
    
    public function destroy($id)
    {
        $respuesta = Respuesta::find($id);
        if(!$respuesta){
            return response()->json([
                'message' => 'No se encontro esta respuesta',
            ],404);
        }

        $respuesta->delete();
        return response()->json([
            'message' => 'Se borro la respuesta',
        ],200);
    }
}
