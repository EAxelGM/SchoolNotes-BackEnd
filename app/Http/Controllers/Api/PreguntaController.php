<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Pregunta;
use App\Respuesta;
use App\Etiqueta;
use App\User;
use App\Traits\Validaciones;
use App\Traits\Funciones;
use App\Traits\Transacciones;
use App\Traits\EnviarCorreos;

class PreguntaController extends Controller
{
    use Validaciones, Funciones,Transacciones, EnviarCorreos;

    public function index()
    {
        $opc = isset($_GET['opc']);
        if($opc){
            $opc = $_GET['opc'];
            switch($opc){
                case 'mis_preguntas':
                    $user_id = isset($_GET['user_id']);
                    if($user_id){
                        $user_id = $_GET['user_id'];
                        $preguntas = Pregunta::where('user_id', $user_id)->with(['user:name,img_perfil','respuestas.user:name,img_perfil'])->orderBy('created_at','DESC')->paginate(4);
                        $code = 200;
                    }else{
                        $preguntas = 'porfavor introduce el parametro faltante';
                        $code = 405;
                    }
                break;

                case 'sin_responder':
                    $data = [];
                    $preguntas = Pregunta::with('user:name,img_perfil')->get();
                    foreach($preguntas as $pregunta){
                        if($pregunta->respuestas()->count() == 0){
                            array_push($data, $pregunta);
                        }
                    }
                    if(!isset($_GET['page'])){
                        return response()->json([
                            'message' => 'Ruta mal escrita',
                            'data' => 'error',
                        ],405);
                    }
                    $preguntas = $this->paginacionPersonalizada($_GET['page'], $data, 4, 'created_at');
                    $code = 200;
                break;

                case 'sin_verificar':
                    $data = [];
                    $preguntas = Pregunta::where('verificado', 0)->with(['user:name,img_perfil','respuestas'])->get();
                    foreach($preguntas as $pregunta){
                        if($pregunta->respuestas()->count() != 0){
                            array_push($data, $pregunta);
                        }
                    }
                    if(!isset($_GET['page'])){
                        return response()->json([
                            'message' => 'Ruta mal escrita',
                            'data' => 'error',
                        ],405);
                    }
                    $preguntas = $this->paginacionPersonalizada($_GET['page'], $data, 4, 'created_at');
                    $code = 200;
                break;

                case 'verificadas':
                    $preguntas = Pregunta::where('verificado', 1)->with(['user:name,img_perfil','respuestas.user:name,img_perfil'])->orderBy('created_at','DESC')->paginate(4);
                    $code = 200;
                break;

                default:
                    $preguntas = 'Al parecer la ruta esta mal';
                    $code = 404;
                break;

            }
        }else{
            $preguntas = Pregunta::with(['user:name,img_perfil','respuestas.user:name,img_perfil'])->orderBy('created_at','DESC')->paginate(4);
            $code = 200;
        }
        
        return response()->json([
            'message' => 'Success',
            'data' => $preguntas,
        ],$code);
    }
    
    public function store(Request $request)
    {
        $validator = $this->datosPregunta($request->all());
        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        //$user = User::find($request->user_id);
        $user = Auth::user();
        $data = $this->userActivo($user);
        if($data['code'] != 200){
            return response()->json([
                'message' => $data['mensaje'],
            ],$data['code']);
        }

        $pregunta = new Pregunta;
        $pregunta->pregunta = $request->pregunta;
        $pregunta->descripcion = $request->descripcion;
        $pregunta->user_id = $user->_id;
        $pregunta->verificado = 0;
        $pregunta->activo = 1;
        $pregunta->reacciones = [];
        $pregunta->etiquetas_ids = $request->etiquetas_ids;
        $pregunta->save();

        $valida = $this->creacionPregunta($user,$pregunta, 15);

        //Enviar correos a seguidores
        $this->avisaSeguidoresPregunta($user, $pregunta);

        return response()->json([
            'message' => $valida['mensaje'],
            'data' => $pregunta
        ],$valida['code']);
    }
    
    public function show($id)
    {
        $pregunta = Pregunta::with(['user:name,img_perfil','respuestas.user:name,img_perfil'])->find($id);
        if(!$pregunta){
            return response()->json([
                'message' => 'No se encontro esta pregunta',
            ],404);
        }
        $etiquetas = [];
        foreach($pregunta->etiquetas_ids as $etiqueta_id){
            $etiqueta = Etiqueta::find($etiqueta_id);
            array_push($etiquetas,$etiqueta);
        }
        $pregunta['etiquetas'] = $etiquetas;

        return response()->json([
            'message' => 'Se encontro la pregunta',
            'data' => $pregunta,
        ],200);
    }
    
    public function update(Request $request, $id)
    {
        $pregunta = Pregunta::find($id);
        if(!$pregunta){
            return response()->json([
                'message' => 'No se encontro esta pregunta',
            ],404);
        }
        $validator = $this->datosPregunta($request->all());
        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }
        //return [$request->all(), $pregunta];
        if($pregunta->user_id != Auth::user()->_id){
            return response()->json([
                'message' => 'Lo sentimos pero no eres dueño de esta pregunta',
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
        $code = $pregunta ? 200 : 404;
        if($code == 200){
            $respuestas = Respuesta::where('pregunta_id', $pregunta->_id)->delete();
            $this->borrarObjeto('pregunta_id',$pregunta->_id);
            $pregunta->delete();
            $mensaje = 'Preguntas y respuestas borradas con exito.';
        }else{
            $mensaje = 'No pudimos encontrar la pregunta.';
        }

        return response()->json([
            'message' => $mensaje,
        ],$code);
    }

    public function misPreguntas(){}
}
