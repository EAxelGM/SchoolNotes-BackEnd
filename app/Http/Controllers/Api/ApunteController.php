<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\Funciones;
use App\Traits\Validaciones;
use App\User;
use App\Apunte;
use App\Etiqueta;

class ApunteController extends Controller
{
    use Funciones, Validaciones;

    public function index()
    {
        $id = isset($_GET['user_id']);
        $page = isset($_GET['page']);
        if(!$id || !$page){
            return response()->json([
                'message' => 'La ruta esta mal escrita.',
            ],405);
        }
        $id = $_GET['user_id'];
        $page = $_GET['page'];
        
        $user = User::find($id);
        if(!$user){
            return response()->json([
                'message' => 'Este ID '. $id .' no existe',
            ],404);
        }

        $apuntes = [];

        foreach($user->etiquetas_ids as $etiqueta){
            $apuntes_for = Apunte::where([
                ['etiquetas_ids', $etiqueta],
                ['activo', 1],
            ])
            ->with('user:name,apellidos,img_perfil')->get();

            foreach($apuntes_for as $i){
                array_push($apuntes, $i);
            }
        }
        
        $apuntes = $this->paginacionPersonalizada($page, $apuntes, 4, 'created_at');
        return response()->json([
            'message' => 'Success',
            'data' => $apuntes,
        ],200);
    }
    
    public function store(Request $request)
    {
        $user = User::find($request->user_id);
        $valida = $this->userActivoVerificado($user);
        if($valida['code'] != 200){
            return response()->json([
                'message' => $valida['mensaje'],
            ],$valida['code']);
        }

        $subir = $this->subirFile($user, $request->file('file'), $request->titulo);
        
        if($subir['path'] == null){
            return response()->json([
                'message' => $subir['message'],
                'data' => $subir['path'],
            ],$subir['code']);
        }
        $slug = explode(".", $subir['casi_slug']);

        $apunte = new Apunte;
        $apunte->titulo = $request->titulo;
        $apunte->slug = $slug[0];
        $apunte->descripcion = $request->descripcion;
        $apunte->archivo = $subir['path'];
        $apunte->user_id = $user->_id;
        $apunte->etiquetas_ids = $request->etiquetas_ids;
        $apunte->reacciones = [];
        $apunte->activo = 1;
        $apunte->save();
        $apunte->user;

        return response()->json([
            'message' => $subir['message'],
            'data' => $apunte,
        ],$subir['code']);
    }
    
    public function show($id)
    {
        $apunte = Apunte::where([['slug', $id],['activo', 1]])
        ->with('user:name,apellidos,img_perfil','comentarios.user:name,apellidos,img_perfil')->first();
        if(!$apunte){
            return response()->json([
                'message' => 'No encontramos el apunte',
            ],404);
        }
        return response()->json([
            'message' => 'Success',
            'data' => $apunte,
        ],200);
    }
    
    public function update(Request $request, $id)
    {
        return response()->json(['message' => 'Por el momento esta cancelado editar apuntes'],404);
    }
    
    public function destroy($id)
    {
        $apunte = Apunte::find($id);
        $code = $apunte ? 200 : 404;
        if($code == 200){
            switch ($apunte->activo) {
                case 1:
                    $apunte->activo = 0;
                    $apunte->save();
                    $mensaje= 'Apunte Borrado con exito!';
                break;
                case 0:
                    $apunte->activo = 1;
                    $apunte->save();
                    $mensaje= 'Apunte Activado con exito!';
                break;
                
                default:
                    $mensaje= 'Oops... Al parecer hubo un error al eliminar.';
                break;
            }
            
        }else{
            $mensaje = 'No pudimos encontrar el Apunte.';
        }

        return response()->json([
            'message' => $mensaje,
        ],$code);
    }

    public function apuntesUsuario($id){
        $mis_apuntes = Apunte::where([['user_id', $id],['activo', 1]])
        ->with('user:name,apellidos,img_perfil')->paginate(4);
        
        return response()->json([
            'message' => 'success',
            'data' => $mis_apuntes,
        ],200);
    }

    public function comprarApunte(Request $request){
        
        $apunte = Apunte::find($request->apunte_id);
        $user = User::find($request->user_id);

        if(!$apunte || !$user){
            return response()->json([
                'message' => 'apunte o usuario no existen'
            ],404);
        }
        
        /**Validar clips */


        $apuntes_comprados = $user->apuntes_comprados;
        if(in_array($apunte->_id, $apuntes_comprados)){
            return response()->json([
                'message' => 'Ya has comprado -'.$apunte->titulo.'- anteriormente',
            ],421);
        }                                                                                                                                                                                                                  
        array_push($apuntes_comprados, $apunte->_id);

        $user->apuntes_comprados = $apuntes_comprados;
        $user->save();        

        return response()->json([
            'message' => 'Apunte comprado.',
        ]);
    }


}
