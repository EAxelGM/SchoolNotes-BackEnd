<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\CompraClips;
use App\User;
use App\HistorialDiamondsClips as DiamondClips;

class ClipsController extends Controller
{
    public function compraClips(Request $request){
        $user = User::find($request->user_id);
        if(!$user){
            return response()->json([
                'message' => 'Este usuario no existe',
            ],404);
        }
        $code = 200;
        switch($request->plan){
            case 1:
                $compra = 50;
            break;

            case 2:
                $compra = 100;
            break;

            case 3:
                $compra = 150;
            break;

            default:
                $compra = 0;
                $mensaje = 'Al parecer tuvimos un error... no se han añadido diamonds clips a tu cuenta';
                $code = 421;
            break;
        }
        $user->diamond_clips = $user->diamond_clips + $compra;
        $user->save();

        if($code == 200){
            $historial = DiamondClips::create([
                'user_paga' => null,
                'cantidad_paga' => 0,
                'user_recibe' => $user->_id,
                'cantidad_recibe' => $compra,
                'clips_empresa' => -$compra,
                'descripcion' => 'El usuario '.$user->name.' '.$user->apellidos.', compro '.$compra.' diamond clips',
                'pregunta_id' => null,
                'apunte_id' => null,
            ]);
            $mensaje = 'Comprar exitosa, se han añadido '.$compra.' diamonds clips a tu cuenta.';
        }

        return response()->json([
            'message' => $mensaje,
            'data' => $user,
        ],$code);
    }
}
