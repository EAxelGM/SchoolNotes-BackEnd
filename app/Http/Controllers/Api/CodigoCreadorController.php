<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\CodigoCreador as Codigo;
use App\Traits\Validaciones;

class CodigoCreadorController extends Controller
{
    use Validaciones;

    public function index()
    {
        //
    }
    
    public function store(Request $request)
    {   
        $user = Auth::user();
        $valida = $this->datosCodigoCreador($request->all());
        if($valida->fails()){
            return response()->json($valida->errors(), 400);
        }

        $codigo = Codigo::where([['user_id', $user->_id],['activo', 1]])->first();
        if($codigo){
            return response()->json([
                'message' => 'Ya cuentas con un codigo de creador: '.$codigo->codigo,
            ],421);
        }

        $codigo = Codigo::where([['codigo', strtoupper($request->codigo)],['activo',1]])->first();
        if($codigo){
            return response()->json([
                'message' => 'El codigo '.$codigo->codigo.' actualmente ya esta en uso, Escoje otro codigo de creador.',
            ],421);

        }

        $codigo = Codigo::create([
            'codigo' => $request->codigo,
            'descuento_compra' => $request->descuento_compra ? $request->descuento_compra : 10, //10%
            'clips_registro' => $request->clips_registro ? $request->clips_registro : 20, //5 clips gratis
            'user_id' => $user->_id,
            'activo' => 1,
        ]);

        return response()->json([
            'message' => 'Tu codigo de creador se ha creado con exito!.'
        ],200);
    }
    
    public function show($id)
    {
        $codigo = Codigo::where('codigo',$id)->first();
        if(!$codigo){
            return response()->json(['message' => 'Error, este codigo no existe...'],404);
        }
        
        return response()->json([
            'message' => 'Codigo correcto.',
            'data' => $codigo,
        ],200);
    }
    
    public function idCodigo($id){
        $codigo = Codigo::find($id);
        if(!$codigo){
            return response()->json(['message' => 'Error, este codigo no existe...'],404);
        }
        
        return response()->json([
            'message' => 'Codigo correcto.',
            'data' => $codigo,
        ],200);
    }

    public function edit($id)
    {
        //
    }
    
    public function update(Request $request, $id)
    {
        //
    }
    
    public function destroy($id)
    {
        $codigo = Codigo::find($id);
        if(!$codigo){
            return response()->json(['message' => 'no existe este codigo'],404);
        }
        
        $codigo->activo = 0;
        $codigo->save();

        return response()->json(['message' => 'Codigo desactivado permanentemente.'],200);
        
    }
}
