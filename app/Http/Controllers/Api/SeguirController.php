<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\Validaciones;
use App\User;


class SeguirController extends Controller
{
    use Validaciones;

    public function seguir(Request $request){
        //Validamos el usuario este activo.
        $user = User::find($request->user_id);
        $activo = $this->userActivoVerificado($user);
        if($activo['code'] != 200){
            return response()->json([
                'message' => $activo['mensaje'],
            ],$activo['code']);
        }

        //Buscamos a la persona que vayamos a seguir.
        $seguir = User::find($request->seguir);
        $activo = $this->userActivo($seguir);
        if($activo['code'] == 200){
            if(!in_array($user->_id,$seguir->seguidores)){
                //LLenar campo seguidores.
                $seguidores = $seguir->seguidores;
                array_push($seguidores, $user->_id);
                $seguir->seguidores = $seguidores;
                $seguir->save();

                //llenar campo Seguidor
                $seguidos = $user->seguidos;
                array_push($seguidos, $seguir->_id);
                $user->seguidos = $seguidos;
                $user->save();
                
                $activo['mensaje'] = 'Empezasta seguir a '. $seguir->name;
            }else{
                //Buscar y eliminar campo seguidores.
                $seguidores = $seguir->seguidores;
                $clave = array_search($user->_id, $seguidores);
                unset($seguidores[$clave]);
                $seguidores = array_values($seguidores);
                $seguir->seguidores = $seguidores;
                $seguir->save();

                //buscar y eliminar campo Seguidor
                $seguidos = $user->seguidos;
                $clave = array_search($seguir->_id, $seguidos);
                unset($seguidos[$clave]);
                $seguidos = array_values($seguidos);
                $user->seguidos = $seguidos;
                $user->save();

                $activo['mensaje'] = 'Dejaste de seguir a '. $seguir->name;
            }
        } 
        
        return response()->json([
            'message' => $activo['mensaje'],
        ],$activo['code']);
    }
}
