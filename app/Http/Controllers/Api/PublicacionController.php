<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Publicacion;
use App\User;
use App\Comentario;
use App\Traits\EnviarCorreos;
use App\Traits\Validaciones;
use App\Traits\Funciones;

class PublicacionController extends Controller
{
    use EnviarCorreos, Validaciones, Funciones;
    
    public function index(){
        $page = isset($_GET['page']);
        if(!$page){
            return response()->json([
                'message' => 'La ruta esta mal escrita.',
            ],405);
        }
        $page = $_GET['page'];
        
        //$user = User::find($id);
        $user = Auth::user();
        if(!$user){
            return response()->json([
                'message' => 'Este ID '. $id .' no existe',
            ],404);
        }

        $publicaciones = [];

        $publicaciones_mias = Publicacion::where([['user_id', $user->_id],['activo', 1]])
        ->with([
            'user:name,img_perfil',
            'comentarios.user:name,img_perfil'
        ])
        ->get();
        foreach($publicaciones_mias as $publicacion){
            array_push($publicaciones, $publicacion);
        }

        foreach($user->seguidos as $seguido_id){
            $todas_publicaciones = Publicacion::where([['user_id', $seguido_id],['activo', 1]])
            ->with([
                'user:name,img_perfil',
                'comentarios.user:name,img_perfil'
            ])->get();
            if(!empty($todas_publicaciones)){
                foreach($todas_publicaciones as $publicacion){
                    array_push($publicaciones, $publicacion);
                }
            }
        } 
        $publicaciones = $this->paginacionPersonalizada($page, $publicaciones, 4, 'created_at');

        return response()->json([
            'message' => 'success',
            'data' => $publicaciones,
        ]); 
    }
    
    public function store(Request $request){   
        $publicacion = null;
        
        $validator = $this->datosPublicacion($request->all());
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        //$user = User::find($request->user_id);
        $user = Auth::user();

        $activo = $this->userActivoVerificado($user);
        if($activo['code'] == 200){
            $publicacion = new Publicacion;
            $publicacion->contenido = $request->contenido;
            $publicacion->reacciones = [];
            $publicacion->activo = 1;
            $publicacion->user_id = $user->_id;
            $publicacion->save();
            $publicacion->user;
            $activo['mensaje'] = 'Publicacion Creada';
        }

        return response()->json([
            'message' => $activo['mensaje'],
            'data' => $publicacion,
        ],$activo['code']);
    }
    
    public function show($id){
        $publicacion = Publicacion::where([['_id',$id],['activo',1]])->first();
        $mensaje = $publicacion ? 'Encontramos la publicacion.':'Al parecer no encontramos esta publicacion...';
        $code = $publicacion ? 200:404;
        if($code == 200){
            $publicacion->user;
            $publicacion->comentarios->each(function ($comentarios){
                $comentarios->user;
            });
        }
        return response()->json([
            'message' => $mensaje,
            'data' => $publicacion,
        ],$code);
    }
    
    public function update(Request $request, $id){
        $user = Auth::user();
        $publicacion = Publicacion::find($id);
        $code = $publicacion ? 200 : 404;
        $code==200 ? $publicacion->user : $publicacion;
        if($code == 200){
            if($publicacion->user_id == $user->_id){
                //$user = User::find($user->_id);
                $valida = $this->userActivoVerificado($user);
                if($valida['code'] == 201){
                    $publicacion->contenido = $request->contenido;
                    $publicacion->save();
                    $mensaje= 'Publicacion actualizada con exito!';
                }else{
                    $mensaje = $valida['mensaje'];
                    $code = $valida['code'];
                }
            }else{
                $mensaje = 'Al parecer no eres dueño de esta publicacion.';
                $code = 421;
            }
        }else{
            $mensaje = 'No pudimos encontrar la publicacion.';
        }

        return response()->json([
            'message' => $mensaje,
            'data' => $publicacion,
        ],$code);
    }
    
    public function destroy($id){
        $publicacion = Publicacion::find($id);
        $code = $publicacion ? 200 : 404;
        if($code == 200){
            $comentarios = Comentario::where('publicacion_id', $publicacion->_id)->delete();
            $publicacion->delete();
            $mensaje = 'Publicacion y comentarios borrados.';
        }else{
            $mensaje = 'No pudimos encontrar la publicacion.';
        }

        return response()->json([
            'message' => $mensaje,
        ],$code);
    }
}
