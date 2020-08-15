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
        $respuesta = Respuesta::with('user')->paginate(10);
        return response()->json([
            'message' => 'Success',
            'data' => $respuesta,
        ],200);
    }
    
    public function store(Request $request)
    {
        $validator = $this->datosRespuesta($request->all());
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = User::find($id);
        $valida = $this->userActivo($user);
        if($valida != 200){
            return response()->json([
                'message' => $data['mensaje'],
            ],$data['code']);
        }

        $respuesta = new Respuesta;
        $respuesta->contenido = $request->contenido;
        $respuesta->user_id = $request->user_id;
        $respuesta->pregunta_id = $request->pregunta_id;
        $respuesta->reacciones = [];
        $respuesta->save();

        return response()->json([
            'message' => 'Respuesta creada',
            'data' => $respuesta,
        ],200);
    }
    
    public function show($id)
    {
        $respuesta = Respuesta::find($id);
        if(!$respuesta){
            return response()->json([
                'message' => 'No se encontre esta respuesta',
            ],404);
        }
        $respuesta->user;
        $respuesta->publicacion;
        return response()->json([
            'message' => 'Se encontro la respuesta',
            'data' => $respuesta,
        ],200);


    }
    
    public function update(Request $request, $id)
    {
        $respuesta = Respuesta::find($id);
        if(!$pregunbta){
            return response()->json([
                'message' => 'No se encontre esta respuesta',
            ],404);
        }

        $validator = $this->datosRespuesta($request->all());
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        if($respuesta->user_id == $request->user_id){
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
                'message' => 'No se encontre esta respuesta',
            ],404);
        }

        $respuesta->delete();
        return response()->json([
            'message' => 'Se borro la respuesta',
        ],200);
    }
}
