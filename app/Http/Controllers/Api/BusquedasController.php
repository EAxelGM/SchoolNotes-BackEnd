<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Apunte;
use App\Pregunta;
use App\Etiqueta;
use App\Traits\Funciones;

class BusquedasController extends Controller
{
    use Funciones;
    public $paginate = 4;

    public function busqueda(Request $request){
        $code = 200;
        switch($request->tipo){
            case 'usuarios':
                $data = $this->nombres($request->data);
            break;
            
            case 'apuntes':
                $data = $this->apuntes($request->data);
            break;
            
            case 'preguntas':
                $data = $this->preguntas($request->data);
            break;
            
            default:
                $data = [];
                $code = 421;
            break;
        }
        return response()->json([
            'message' => 'Success',
            'data' => $data,
        ],$code);
    }
    
    public function nombres($name){
        $data = User::where('name', 'LIKE', '%'.$name.'%')->paginate($this->paginate); 
        return $data;
    }

    public function apuntes($name){
        $data = Apunte::where('titulo', 'LIKE', '%'.$name.'%')->with('user:name,img_perfil')->paginate($this->paginate);
        return $data;
    }

    public function preguntas($name){
        $data = Pregunta::where('pregunta', 'LIKE', '%'.$name.'%')->with('user:name,img_perfil')->paginate($this->paginate);
        return $data;
    }

    public function etiquetas(Request $request){
        $etiqueta = Etiqueta::where('slug', $request->slug)->first();
        if(!$etiqueta){
            return response()->json([
                'message' => 'Error la etiqueta no existe',
            ]);
        }

        $code = 200;
        switch($request->tipo){
            case 'usuarios':
                $data = User::where('etiquetas_ids', $etiqueta->_id)->paginate($this->paginate);
            break;

            case 'apuntes':
                $data = Apunte::where('etiquetas_ids', $etiqueta->_id)->with('user:name,img_perfil')->paginate($this->paginate);
            break;

            case 'preguntas':
                $data = Pregunta::where('etiquetas_ids', $etiqueta->_id)->with('user:name,img_perfil')->paginate($this->paginate);
            break;

            default:
                $data = [];
                $code = 421;
            break;

        }

        return response()->json([
            'message' => 'Success',
            'data' => $data,
        ]);
    }

    public function meSiguen($id){
        $user = User::find($id);
        
        if(!$user || !isset($_GET['page'])){
            return response()->json([
                'message' => 'Usuario no existente'
            ],404);
        }

        $meSiguen = [];
        $page = $_GET['page'];

        foreach($user->seguidos as $id){
            $seguidor = User::find($id);
            if($seguidor){
                array_push($meSiguen, $seguidor);
            }
        }

        $seguidores = $this->paginacionPersonalizada($page, $meSiguen, 10, '_id');
        return response()->json([
            'message' => 'Success',
            'data' => $seguidores,
        ],200);
    }
    public function yoSigo($id){
        $user = User::find($id);
        
        if(!$user || !isset($_GET['page'])){
            return response()->json([
                'message' => 'Usuario no existente'
            ],404);
        }

        $yoSigo = [];
        $page = $_GET['page'];

        foreach($user->seguidos as $id){
            $sigo = User::find($id);
            if($sigo){
                array_push($yoSigo, $sigo);
            }
        }

        $siguiendo = $this->paginacionPersonalizada($page, $yoSigo, 10, '_id');
        return response()->json([
            'message' => 'Success',
            'data' => $siguiendo,
        ],200);
    }
    
}
