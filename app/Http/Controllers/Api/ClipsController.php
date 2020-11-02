<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\CompraClips;
use App\User;
use App\HistorialDiamondsClips as DiamondClips;
use App\HistorialClips as Clip;

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
                'descripcion' => 'El usuario '.$user->name.', compro '.$compra.' diamond clips',
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

    public function intercambioDiamondsAClips(Request $request){
        $user = User::find($request->user_id);
        if(!$user){
            return response()->json([
                'message' => 'No se encontro al usuario.',
            ],404);
        }
        if($request->diamonds > $user->diamond_clips){
            return response()->json([
                'message' => 'Lo sentimos no cuentas con suficientes Clips de Diamante',
            ],421); 
        }

        $clips = $request->diamonds * 10;

        if($clips != $request->clips){
            return response()->json([
                'message' => 'Oops... ha habido un error... hacker.',
            ],421); 
        }

        $user->diamond_clips = $user->diamond_clips - $request->diamonds;
        $user->clips = $user->clips + $clips;
        $user->save();

        DiamondClips::create([
            'user_paga' => $user->_id,
            'cantidad_paga' => $request->diamonds,
            'user_recibe' => null,
            'cantidad_recibe' => 0,
            'clips_empresa' => $request->diamonds,
            'descripcion' => 'El usuario '.$user->name.', Cambio '.$request->diamonds.' diamond clips por '.$clips.' clips.',
            'pregunta_id' => null,
            'apunte_id' => null,
        ]);

        Clip::create([
            'user_paga' => null,
            'cantidad_paga' => 0,
            'user_recibe' => $user->_id,
            'cantidad_recibe' => $clips,
            'clips_empresa' => -$clips,
            'descripcion' => 'El usuario '.$user->name.', Cambio '.$request->diamonds.' diamond clips por '.$clips.' clips.',
            'pregunta_id' => null,
            'apunte_id' => null,
            'borrado' => 0,
        ]);

        return response()->json([
            'message' => 'Se han restado '.$request->diamonds.' clips de diamantes y se han agregado '.$clips.' clips a tu cuenta. Gracias por realizar esta transaccion',
            'data' => $user,
        ],200);

    }

    public function intercambioClipsADiamonds(Request $request){
        $user = User::find($request->user_id);
        if(!$user){
            return response()->json([
                'message' => 'No se encontro al usuario.',
            ],404);
        }
        if($request->clips > $user->clips){
            return response()->json([
                'message' => 'Lo sentimos no cuentas con suficientes Clips',
            ],421); 
        }

        $diamonds_clips = $request->clips / 10;

        if($diamonds_clips != $request->diamonds){
            return response()->json([
                'message' => 'Oops... ha habido un error... hacker.',
            ],421); 
        }

        $user->diamond_clips = $user->diamond_clips + $diamonds_clips;
        $user->clips = $user->clips - $request->clips;
        $user->save();

        DiamondClips::create([
            'user_paga' => null,
            'cantidad_paga' => 0,
            'user_recibe' => $user->_id,
            'cantidad_recibe' => $diamonds_clips,
            'clips_empresa' => -$diamonds_clips,
            'descripcion' => 'El usuario '.$user->name.', Cambio '.$request->clips.' clips por '.$diamonds_clips.' diamond clips.',
            'pregunta_id' => null,
            'apunte_id' => null,
        ]);

        Clip::create([
            'user_paga' => $user->_id,
            'cantidad_paga' => $request->clips,
            'user_recibe' => null,
            'cantidad_recibe' => 0,
            'clips_empresa' => $request->clips,
            'descripcion' => 'El usuario '.$user->name.', Cambio '.$request->clips.' clips por '.$diamonds_clips.' diamond clips.',
            'pregunta_id' => null,
            'apunte_id' => null,
            'borrado' => 0,
        ]);

        return response()->json([
            'message' => 'Se han restado '.$request->clips.' clips y se han agregado '.$diamonds_clips.' clips de diamante a tu cuenta. Gracias por realizar esta transaccion',
            'data' => $user,
        ],200);

    }
}
