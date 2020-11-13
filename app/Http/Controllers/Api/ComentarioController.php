<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Comentario;
use App\User;
use App\Traits\Validaciones;

class ComentarioController extends Controller
{
    use Validaciones;

    public function index(){
        return 'Aun falta programar esta parte';
    }
    
    public function store(Request $request){
        $comentario = null;
        $validator = Validator::make($request->all(), [
            'comentario' => 'required|string',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        //$user = User::find($request->user_id);
        $user = Auth::user();

        $activo = $this->userActivoVerificado($user);
        if($activo['code'] == 200){
            $comentario = new Comentario;
            $comentario->apunte_id = $request->apunte_id;
            $comentario->publicacion_id = $request->publicacion_id;
            $comentario->comentario = $request->comentario;
            $comentario->reacciones = [];
            $comentario->user_id = $user->_id;
            $comentario->save();
            $comentario->user;
            $activo['mensaje'] = 'Comentario Creada';
        }

        return response()->json([
            'message' => $activo['mensaje'],
            'data' => $comentario,
        ],$activo['code']);
    }
    
    public function show($id){
        $comentario = Comentario::find($id);
        $mensaje = $comentario ? 'Encontramos el comentario.':'Al parecer no encontramos esta comentario...';
        $code = $comentario ? 200:404;

        if($comentario){
            $comentario->user;
            $comentario->publicacion;
            $comentario->apunte;
        }

        return response()->json([
            'message' => $mensaje,
            'data' => $comentario,
        ],$code);
    }
    
    public function update(Request $request, $id){
        $user = Auth::user();
        $comentario = Comentario::find($id);
        $code = $comentario ? 200 : 404;
        $code==200 ? $comentario->user : $comentario;
        if($code == 200){
            //$user = User::find($request->user_id);
            $valida = $this->userActivoVerificado($user);
            if($valida['code'] == 200){
                $comentario->comentario = $request->comentario;
                $comentario->save();
                $mensaje= 'Comentario actualizado';
            }else{
                $mensaje = $valida['mensaje'];
                $code = $valida['code'];
            }
        }else{
            $mensaje = 'No pudimos encontrar el comentario.';
        }

        return response()->json([
            'message' => $mensaje,
            'data' => $comentario,
        ],$code);
    }
    
    public function destroy($id){
        $comentario = Comentario::find($id);
        $code = $comentario ? 200 : 404;
        if($code == 200){
            $comentario->delete();
            $mensaje= 'comentario Borrado con exito!';
        }else{
            $mensaje = 'No pudimos encontrar el comentario.';
        }

        return response()->json([
            'message' => $mensaje,
        ],$code);
    }
}
