<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Categoria;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use App\Traits\Validaciones;

class CategoriaController extends Controller
{
    use Validaciones;
    public function index(){
        $categorias = Categoria::where('activo',1)->get();
        $mensaje = 'Success';
        $code = 200;
        return response()->json([
            'message' => $mensaje,
            'data' => $categorias,
        ],$code);
    }

    public function store(Request $request){
        $request['slug']= Str::slug($request->nombre, '-');
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string',
            'slug' => 'required|string|unique:categorias',
        ]);
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }
        $categoria = new Categoria;
        $categoria->nombre = $request->nombre;
        $categoria->slug = Str::slug($request->nombre);
        $categoria->activo = 1;
        $categoria->save();
        $mensaje = 'Categoria Creada.';
        $code = 200;
        
        return response()->json([
            'message' => $mensaje,
            'data' => $categoria,
        ],$code);
    }
    
    public function show($id){
        $categoria = Categoria::find($id);
        $code = $categoria ? 200 : 404;
        if($code == 200){
            //traer todos los apuntes con esta categoria.
        }
        return $categoria;
    }
    
    public function update(Request $request, $id){
        $categoria = Categoria::find($id);
        $code = $categoria ? 200 : 404;
        
        if($code != 200){
            $mensaje = 'No encontramos la categoria que estas buscando';
            return response()->json([
                'message' => $mensaje,
            ],$code);
        }
        
        $request['slug']= Str::slug($request->nombre, '-');
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string',
            'slug' => 'required|string|unique:categorias',
        ]);
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $categoria->nombre = $request->nombre;
        $categoria->slug = Str::slug($request->nombre);
        $categoria->save();
        $mensaje = 'Categoria modificada.';
        return response()->json([
            'message' => $mensaje,
            'data' => $categoria,
        ],$code);
    }
    
    public function destroy($id){
        $categoria = Categoria::find($id);
        $code = $categoria ? 200 : 404;
        if($code == 200){
            switch ($categoria->activo) {
                case 1:
                    $categoria->activo = 0;
                    $categoria->save();
                    $mensaje= 'Categoria Borrada con exito!';
                break;
                case 0:
                    $categoria->activo = 1;
                    $categoria->save();
                    $mensaje= 'Categoria activada con exito!';
                break;
                
                default:
                    $mensaje= 'Oops... Al parecer hubo un error al eliminar.';
                break;
            }
            
        }else{
            $mensaje = 'No pudimos encontrar la categoria.';
        }

        return response()->json([
            'message' => $mensaje,
        ],$code);
    }
}
