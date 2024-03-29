<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Portafolio;
use App\Apunte;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Traits\Funciones;
use App\Traits\Validaciones;
use App\Traits\Transacciones;


class PortafolioController extends Controller
{
    use Funciones, Validaciones, Transacciones;

    public function index()
    {
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $user = Auth::user();
        $portafolios = [];

        foreach($user->etiquetas_ids as $etiqueta){
            $portafolios_for = Portafolio::where('etiquetas_ids', $etiqueta)->with('user:name,img_perfil')->get();
            foreach($portafolios_for as $i){
                array_push($portafolios, $i);
            }
        }

        $portafolios = $this->paginacionPersonalizada($page, $portafolios, 12, 'created_at');

        return response()->json([
            'message' => 'Success',
            'data' => $portafolios,
        ],200);
    }
    
    public function store(Request $request)
    {
        $validator = $this->datosPortafolio($request->all());
        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }
        $user = Auth::user();

        $img = $this->subirFile($user, $request->file('img_destacada'), $request->nombre);
        if($img['path'] == null){
            return response()->json([
                'message' => $img['message'],
                'data' => $img['path'],
            ],$img['code']);
        }

        $portafolio = Portafolio::create([
            'nombre' => $request->nombre, 
            'descripcion' => $request->descripcion, 
            'user_id' => $user->_id,
            'img' => $img['path'],
            'apuntes_ids' => json_decode($request->apuntes_ids),
            'etiquetas_ids' => json_decode($request->etiquetas_ids),
        ]);
        $portafolio->user;

        /**Validar clips */
        $valida = $this->desbloquearPortafolio($user,$portafolio, 0, 0, []);

        return response()->json([
            'message' => 'Portafolio Creado',
            'data' => $portafolio,
        ],200);
    }
    
    public function show($id)
    {
        $portafolio = Portafolio::with('user:name,img_perfil')->find($id);
        if(!$portafolio){
            return response()->json([
                'message' => 'Portafolio no encontrado',
            ],404);
        }

        $apuntes = [];
        foreach($portafolio->apuntes_ids as $id){
            $note = Apunte::where('_id', $id)->with('user:name,img_perfil')->first();
            if($note){
                array_push($apuntes,$note);
            }else{
                $compras = $portafolio->apuntes_ids;
                $clave = array_search($id, $compras);
                unset($compras[$clave]);
                $compras = array_values($compras);
                $portafolio->apuntes_ids = $compras;
                $portafolio->save();
            }
        }

        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        //$apuntes = $this->paginacionPersonalizada($page, $apuntes, 12, 'created_at');
        $portafolio['apuntes'] = $apuntes;

        return response()->json([
            'message' => 'Success',
            'data' => $portafolio,
        ],200);
    }
    
    public function update(Request $request, $id)
    {
        $portafolio = Portafolio::find($id);
        $user = Auth::user();
        if(!$portafolio){
            return response()->json([
                'message' => 'Portafolio no encontrado',
            ],404);
        }
        if($portafolio->user_id != $user->_id){
            return response()->json([
                'message' => 'No eres dueño de este portafolio',
            ],403);
        }

        $portafolio->fill($request->all());
        if($portafolio->isClean()){
            return response()->json(['message'=>'Especifica al menos un valor diferente'],421);
        }
        $portafolio->apuntes_ids = json_decode($request->apuntes_ids);
        $portafolio->etiquetas_ids = json_decode($request->etiquetas_ids);
        
        if($request->file('img_destacada')){
            $img = $this->subirFile($user, $request->file('img_destacada'), $request->titulo);
            $portafolio->img = $img['path'];
        }
        $portafolio->save();

        return response()->json([
            'message' => 'Portafolio Editado',
            'data' => $portafolio,
        ],200);
    }
    
    public function destroy($id)
    {
        $portafolio = Portafolio::find($id);
        $user = Auth::user();
        $contrasena = isset($_GET['contrasena']) ? $_GET['contrasena']: '';
        
        if(!$portafolio){
            return response()->json([
                'message' => 'Portafolio no encontrado',
            ],404);
        }

        if($portafolio->user_id != $user->_id){
            return response()->json([
                'message' => 'No eres dueño de este portafolio',
            ],403);
        }

        if(!Hash::check($contrasena, $user->password)){
            return response()->json([
                'message' => 'La contraseña es incorrecta.',
            ],421);
        }

        $portafolio->delete();
        return response()->json([
            'message' => 'Portafolio Eliminado.',
        ],200);
    }

    public function misPorta($id){
        $user = User::find($id);
        if(!$user){
            return response()->json([
                'message' => 'No se encontro el usuario.',
            ],404);
        }
        $user->portafolios_comprados = $user->portafolios_comprados ? $user->portafolios_comprados : [];
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $portafolios = [];

        foreach($user->portafolios_comprados as $id){
            $note = Portafolio::where('_id', $id)->with('user:name,img_perfil')->first();
            if($note){
                array_push($portafolios,$note);
            }else{
                $compras = $user->portafolios_comprados;
                $clave = array_search($id, $compras);
                unset($compras[$clave]);
                $compras = array_values($compras);
                $user->portafolios_comprados = $compras;
                $user->save();
            }
        }

        $portafolios = $this->paginacionPersonalizada($page, $portafolios, 12, 'nombre');

        return response()->json([
            'message' => 'success',
            'data' => $portafolios,
        ],200);
    }
    
    public function comprarPorta(Request $request){
        $user = Auth::user();
        $portafolio = Portafolio::find($request->portafolio_id);

        if(!$portafolio){
            return response()->json([
                'message' => 'Portafolio no encotrado'
            ],404);
        }

        $precio_porta = $request->precio_portafolio;
        $paga_user = $request->ganancia;
        $apuntes_ids_faltantes = json_decode($request->apuntes_ids_faltantes);

        /**Validar clips */
        $valida = $this->desbloquearPortafolio($user,$portafolio, $precio_porta, $paga_user, $apuntes_ids_faltantes);

        return response()->json([
            'message' => $valida['mensaje'],
        ],$valida['code']);
    }

    public function portaUser($id){
        $user = User::find($id);

        if(!$user){
            return response()->json(['message' => 'Usuario no existente'],404);
        }

        $portafolio = Portafolio::where('user_id', $user->_id)->with('user:name,img_perfil')->paginate(12);

        return response()->json([
            'message' => 'Success',
            'data' => $portafolio,
        ],200);
    }

    public function savePorta(Request $request){
        $user = Auth::user();
        $portafolio = Portafolio::find($request->portafolio_id);
        if(!$portafolio){
            return response()->json([
                'message' => 'Portafolio no encotrado'
            ],404);
        }
        $portafolios_comprados = isset($user->portafolios_comprados) ? $user->portafolios_comprados : [];
        if(in_array($portafolio->_id, $portafolios_comprados)){
            $clave = array_search($portafolio->_id, $portafolios_comprados);
            unset($portafolios_comprados[$clave]);
            $portafolios_comprados = array_values($portafolios_comprados);
        }else{
            array_push($portafolios_comprados, $portafolio->_id);
        }
        $user->portafolios_comprados = $portafolios_comprados;
        $user->save();

        return response()->json([
            'message' => 'Success.',
            'data' => $user,
        ],200);
    }
}
