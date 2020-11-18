<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Universidad as Uni;
use App\User;

class UniversidadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $universidades = Uni::where('activo', 1)->get();

        return response()->json([
            'message' => 'Success',
            'data' => $universidades
        ],200);
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $uni_existe = Uni::where('nombre', strtolower($request->nombre))->with('user:name,img_perfil')->first();
        if($uni_existe){
            return response()->json([
                'message' => 'Esta universidad ya existe.',
                'data' => $uni_existe,
            ]);
        }

        $user = Auth::user();
        $universidad = Uni::create([
            'nombre' => $request->nombre,
            'img' => null,
            'created_by' => $user->_id,
            'activo' => 1,
        ]);
        //Envio de correos al momento de crear una universidad


        return response()->json([
            'message' => 'Universidad creada con exito, la universidad ha pasado a revisión.',
            'data' => $universidad,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $uni = Uni::where([['activo', 1],['_id', $id]])->with('user:name,img_perfil')->first();
        if(!$uni){
            return response()->json([
                'message' => 'No se encontro la universidad',
            ],404);
        }

        return response()->json([
            'message' => 'Success',
            'data' => $uni,
        ],200);
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = Auth::user();
        if($user->tipo == 'administrador'){
            $uni = Uni::find($id);
            if($uni){
                switch($uni->activo){
                    case 0:
                        $uni->activo = 1;
                        $mensaje = 'La universidad se ha activado con exito.';
                        $code = 200;
                    break;
                    case 1:
                        $uni->activo = 0;
                        $mensaje = 'La universidad se ha desactivado con exito.';
                        $code = 200;
                    break;
                    default:
                        $uni->activo = 1;
                        $mensaje = 'Oops... ha ocurrido un error, la universidad sigue activa.';
                        $code = 405;
                    break;
                }
                $uni->save();
                return response()->json([
                    'message' => $mensaje,
                    'data' => $uni,
                ],$code);
            }else{
                return response()->json([
                    'message' => 'No se encontro la universidad',
                ],404);
            }
        }else{
            return response()->json([
                'message' => 'No tienes permiso de realizar esta accion',
            ],421);
        }
    }
}
