<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Etiqueta;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use App\Traits\Validaciones;

class EtiquetaController extends Controller
{
    use Validaciones;

    public function index(){
        $etiquetas = Etiqueta::where('activo',1)->orderBy('slug','ASC')->get();
        $mensaje = 'Success';
        $code = 200;
        return response()->json([
            'message' => $mensaje,
            'data' => $etiquetas,
        ],$code);
    }

    public function store(Request $request){
        $request['slug']= Str::slug($request->nombre, '-');
        $validator = $this->datosEtiqueta($request->all());
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $etiqueta = new Etiqueta;
        $etiqueta->nombre = $request->nombre;
        $etiqueta->slug = Str::slug($request->nombre);
        $etiqueta->created_by = Auth::user()->_id;
        $etiqueta->activo = 1;
        $etiqueta->save();
        $mensaje = 'Etiqueta Creada.';
        $code = 200;
        
        return response()->json([
            'message' => $mensaje,
            'data' => $etiqueta,
        ],$code);
    }
    
    public function show($id){
        $etiqueta = Etiqueta::find($id);
        $code = $etiqueta ? 200 : 404;
        if($code == 200){
            //traer todos los apuntes con esta etiqueta.
        }
        return $etiqueta;
    }
    
    public function update(Request $request, $id){
        $etiqueta = Etiqueta::find($id);
        $code = $etiqueta ? 200 : 404;
        
        if($code != 200){
            $mensaje = 'No encontramos la etiqueta que estas buscando';
            return response()->json([
                'message' => $mensaje,
            ],$code);
        }
        
        $request['slug']= Str::slug($request->nombre, '-');
        $validator = $this->datosEtiqueta($request->all());
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $etiqueta->nombre = $request->nombre;
        $etiqueta->slug = Str::slug($request->nombre);
        $etiqueta->save();
        $mensaje = 'Etiqueta modificada.';

        return response()->json([
            'message' => $mensaje,
            'data' => $etiqueta,
        ],$code);
    }
    
    public function destroy($id){
        $etiqueta = Etiqueta::find($id);
        $code = $etiqueta ? 200 : 404;
        if($code == 200){
            switch ($etiqueta->activo) {
                case 1:
                    $etiqueta->activo = 0;
                    $etiqueta->save();
                    $mensaje= 'Etiqueta Borrada con exito!';
                break;

                case 0:
                    $etiqueta->activo = 1;
                    $etiqueta->save();
                    $mensaje= 'Etiqueta activada con exito!';
                break;
                
                default:
                    $mensaje= 'Oops... Al parecer hubo un error al eliminar.';
                break;
            }
        }else{
            $mensaje = 'No pudimos encontrar la etiqueta.';
        }

        return response()->json([
            'message' => $mensaje,
        ],$code);
    }
}
