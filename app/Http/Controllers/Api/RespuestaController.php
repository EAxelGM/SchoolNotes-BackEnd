<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Traits\Validaciones;
use App\Traits\Transacciones;
use App\Traits\EnviarCorreos;
use App\Respuesta;
use App\Pregunta;
use App\User;

class RespuestaController extends Controller
{
    use Validaciones,Transacciones,EnviarCorreos;

    public function index()
    {
        $respuestas = Respuesta::with('user:name,img_perfil')->orderBy('created_at', 'DESC')->paginate(4);
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

        //$user = User::find($request->user_id);
        $user = Auth::user();
        $valida = $this->userActivo($user);
        if($valida['code'] != 200){
            return response()->json([
                'message' => $valida['mensaje'],
            ],$valida['code']);
        }

        $respuesta = new Respuesta;
        $respuesta->contenido = $request->contenido;
        $respuesta->user_id = $user->_id;
        $respuesta->pregunta_id = $request->pregunta_id;
        $respuesta->verificado = 0;
        $respuesta->reacciones = [];
        $respuesta->save();
        $respuesta->user;

        //Notifica al propietario de la pregunta cuando alguien ya halla respondido su pregunta.
        $this->notificarRespuesta($user, $respuesta);

        return response()->json([
            'message' => 'Respuesta creada',
            'data' => $respuesta,
        ],200);
    }

    public function show($id)
    {
        $respuesta = Respuesta::with(['user:name,img_perfil','pregunta.user:name,img_perfil'])->find($id);
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

        if($respuesta->user_id != Auth::user()->_id){
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

    public function validaRespuesta(Request $request){
        $respuesta = Respuesta::find($request->id);
        if(!$respuesta){
            return response()->json([
                'message' => 'Al parecer no existe',
            ],404);
        }

        $pregunta = Pregunta::find($respuesta->pregunta_id);
        if(!$pregunta){
            return response()->json([
                'message' => 'Al parecer no existe la pregunta',
            ],404);
        }

        switch($respuesta->verificado){
            case 1:
                $respuesta->verificado = 0;
                $pregunta->verificado = 0;
                $mensaje = 'La respuesta se quito de verificados';
                $code = 200;
            break;
            case 0:
                if($pregunta->verificado == 1){
                    return response()->json([
                        'message' => 'al parecer ya existe una respuesta verificada para esta pregunta. ',
                    ],403);
                }
                $this->verificacionRespuesta($respuesta,$pregunta, 5);
                $respuesta->verificado = 1;
                $pregunta->verificado = 1;
                $mensaje = 'La respuesta se ha verificado';
                $code = 200;
            break;
            default:
                $respuesta->verificado = 0;
                $pregunta->verificado = 0;
                $mensaje = 'Oops... hubo un error, vuelve a intentarlo';
                $code = 421;
            break;
        }
        $respuesta->save();
        $pregunta->save();

        return response()->json([
            'message' => $mensaje,
        ],$code);
    }
}
