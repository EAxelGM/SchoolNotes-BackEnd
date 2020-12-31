<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\User;
use App\Apunte;
use App\Pregunta;
use App\Etiqueta;
use App\Portafolio;
use App\Traits\Funciones;

class BusquedasController extends Controller
{
    use Funciones;
    public $paginate = 12;

    public function busqueda(Request $request){
        $code = 200;
        $mensaje = "Success";
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

            case 'portafolios':
                $data = $this->portafolios($request->data);
            break;

            case 'usuarios_por_universidad';
                $data = $this->userUniversidades($request->data);
            break;

            case 'usuarios_por_carrera';
                $data = $this->userCarreras($request->data);
            break;            

            default:
                $data = [];
                $mensaje="Este tipo aun no esta implementado...";
                $code = 421;
            break;
        }
        return response()->json([
            'message' => $mensaje,
            'data' => $data,
        ],$code);
    }

    public function nombres($name){
        $data = User::where('name', 'LIKE', '%'.$name.'%')->with('universidad:nombre,img','carrera:nombre,img')->orderBy('created_at', 'DESC')->paginate($this->paginate);
        return $data;
    }

    public function apuntes($name){
        $data = Apunte::where('titulo', 'LIKE', '%'.$name.'%')->with('user:name,img_perfil')->orderBy('created_at', 'DESC')->paginate($this->paginate);
        return $data;
    }

    public function preguntas($name){
        $data = Pregunta::where('pregunta', 'LIKE', '%'.$name.'%')->with('user:name,img_perfil')->orderBy('created_at', 'DESC')->paginate($this->paginate);
        return $data;
    }

    public function portafolios($name){
        $data = Portafolio::where('nombre', 'LIKE', '%'.$name.'%')->with('user:name,img_perfil')->orderBy('created_at', 'DESC')->paginate($this->paginate);
        return $data;
    }

    public function userUniversidades($id){
        $data = User::where([
            ['activo', 1],
            ['universidad_id', $id]
        ])->with('universidad:nombre,img','carrera:nombre,img')->orderBy('created_at', 'DESC')->paginate($this->paginate);
        return $data;
    }

    public function userCarreras($id){
        $data = User::where([
            ['activo', 1],
            ['carrera_id', $id]
        ])->with('universidad:nombre,img','carrera:nombre,img')->paginate($this->paginate);
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
        $mensaje="Success";
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

            case 'portafolios':
                $data = Portafolio::where('etiquetas_ids', $etiqueta->_id)->with('user:name,img_perfil')->paginate($this->paginate);
            break;

            default:
                $data = [];
                $code = 421;
                $mensaje="Este tipo aun no esta implementado...";
            break;

        }

        return response()->json([
            'message' => $mensaje,
            'data' => $data,
        ],$code);
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

        foreach($user->seguidores as $id){
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
