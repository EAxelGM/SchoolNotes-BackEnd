<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Pregunta;
use App\Traits\Validaciones;

class PreguntaController extends Controller
{
    use Validaciones;

    public function index()
    {
        $preguntas = Pregunta::with('user')->paginate(10);
        return response()->json([
            'message' => 'Success',
            'data' => $preguntas,
        ],200);
    }
    
    public function store(Request $request)
    {
        $validator = $this->datosPregunta($request->all());
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
        
        $pregunta = new Pregunta;
        $pregunta->contenido = $request->contenido;
        $pregunta->user_id = $request->user_id;
        $pregunta->verificado = 0;
        $pregunta->reacciones = [];
        $pregunta->save();
        
        return response()->json([
            'message' => 'Pregunta creada.',
            'data' => $pregunta
        ],201);
    }
    
    public function show($id)
    {
        $pregunta = Pregunta::find($id);
        if(!$pregunta){
            return response()->json([
                'message' => 'No se encontre esta pregunta',
            ],404);
        }
        $pregunta->respuestas;
        $pregunta->user;
        return response()->json([
            'message' => 'Se encontro la pregunta',
            'data' => $pregunta,
        ],200);
    }
    
    public function update(Request $request, $id)
    {
        $pregunta = Pregunta::find($id);
        if(!$pregunbta){
            return response()->json([
                'message' => 'No se encontre esta pregunta',
            ],404);
        }

        $validator = $this->datosPregunta($request->all());
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        if($pregunta->user_id == $request->user_id){
            return response()->json([
                'message' => 'Lo sentimos pero no eres dueño de esta publicacion',
            ],421);
        }

        $pregunta->fill($request->all());
        if($pregunta->isClean()){
            return response()->json(['message'=>'Especifica al menos un valor diferente'],421);
        }

        $pregunta->save();
        return response()->json([
            'message' => 'Pregunta modificada',
            'data' => $pregunta,
        ],200);
    }
    
    public function destroy($id)
    {
        $pregunta = Pregunta::find($id);
        if(!$pregunta){
            return response()->json([
                'message' => 'No se encontre esta pregunta',
            ],404);
        }

        $pregunta->delete();
        return response()->json([
            'message' => 'Se borro la pregunta',
        ],200);
    }
}
