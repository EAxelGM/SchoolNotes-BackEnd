<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Publicacion;
use App\User;
use App\Traits\EnviarCorreos;
use App\Traits\Validaciones;

class PublicacionController extends Controller
{
    use EnviarCorreos, Validaciones;
    
    public function index(){
        $id = isset($_GET['user_id']);
        if(!$id){
            return response()->json([
                'message' => 'La ruta esta mal escrita.',
            ]);
        }
        $id = $_GET['user_id'];
        
        $user = User::find($id);
        if(!$user){
            return response()->json([
                'message' => 'Este ID '. $id .' no existe',
            ]);
        }

        $publicaciones = [];
        foreach($user->seguidos as $seguido_id){
            $todas_publicaciones = Publicacion::where([['user_id', $seguido_id],['activo', 1]])->with('user')->get();
            if(!empty($todas_publicaciones)){
                foreach($todas_publicaciones as $publicacion){
                    $publicacion->comentarios;
                    array_push($publicaciones, $publicacion);
                }
            }
        }

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

        $user = User::find($request->user_id);

        $activo = $this->userActivoVerificado($user);
        if($activo['code'] == 201){
            $publicacion = new Publicacion;
            $publicacion->contenido = $request->contenido;
            $publicacion->reacciones = [];
            $publicacion->activo = 1;
            $publicacion->user_id = $request->user_id;
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
        $publicacion = Publicacion::find($id);
        $code = $publicacion ? 200 : 404;
        $code==200 ? $publicacion->user : $publicacion;
        if($code == 200){
            if($publicacion->user_id == $request->user_id){
                $user = User::find($request->user_id);
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
            switch ($publicacion->activo) {
                case 1:
                    $publicacion->activo = 0;
                    $publicacion->save();
                    $mensaje= 'Publicacion Borrada con exito!';
                break;
                case 0:
                    $publicacion->activo = 1;
                    $publicacion->save();
                    $mensaje= 'Publicacion activada con exito!';
                break;
                
                default:
                    $mensaje= 'Oops... Al parecer hubo un error al eliminar.';
                break;
            }
            
        }else{
            $mensaje = 'No pudimos encontrar la publicacion.';
        }

        return response()->json([
            'message' => $mensaje,
        ],$code);
    }
}
