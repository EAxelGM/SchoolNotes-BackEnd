<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Carrera;
use App\User;

class CarreraController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $carreras = Carrera::where('activo', 1)->get();

        return response()->json([
            'message' => 'Success',
            'data' => $carreras,
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
        $car_existe = Carrera::where([['activo', 1],['nombre', strtolower($request->nombre)]])->with('user:name,img_perfil')->first();
        if($car_existe){
            return response()->json([
                'message' => 'Esta carrera Ya existe',
            ],404);
        }
        
        $user = Auth::user();
        $carrera = Carrera::create([
            'nombre' => $request->nombre,
            'img' => null,
            'created_by' => $user->_id,
            'activo' => 1,
        ]);
        //Envio de correos al momento de crear una universidad


        return response()->json([
            'message' => 'Carrera creada con exito.',
            'data' => $carrera,
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
        $carrera = Carrera::where([['activo', 1], ['_id', $id]])->with('user:name,img_perfil')->first();
        if(!$carrera){
            return response()->json([
                'message' => 'No encontramos esta carrera',
            ],404);
        }

        return response()->json([
            'message' => 'Success',
            'data' => $carrera,

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
        // Proximamente
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
            $carrera = Carrera::find($id);
            if($carrera){
                switch($carrera->activo){
                    case 0:
                        $carrera->activo = 1;
                        $mensaje = 'La carrera se ha activado con exito.';
                        $code = 200;
                    break;
                    case 1:
                        $carrera->activo = 0;
                        $mensaje = 'La carrera se ha desactivado con exito.';
                        $code = 200;
                    break;
                    default:
                        $carrera->activo = 1;
                        $mensaje = 'Oops... ha ocurrido un error, la carrera sigue activa.';
                        $code = 405;
                    break;
                }
                $carrera->save();
                return response()->json([
                    'message' => $mensaje,
                    'data' => $carrera,
                ],$code);
            }else{
                return response()->json([
                    'message' => 'No se encontro la carrera',
                ],404);
            }
        }else{
            return response()->json([
                'message' => 'No tienes permiso de realizar esta accion',
            ],421);
        }
    }
}
