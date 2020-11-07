<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\Funciones;
use App\Traits\Validaciones;
use App\Traits\Transacciones;
use App\User;
use App\Apunte;
use App\Etiqueta;
use App\Comentario;

class ApunteController extends Controller
{
    use Funciones, Validaciones, Transacciones;

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
            ->with('user:name,img_perfil')->get();

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

        $img = $this->subirFile($user, $request->file('img_destacada'), $request->titulo);
        if($img['path'] == null){
            return response()->json([
                'message' => $img['message'],
                'data' => $img['path'],
            ],$img['code']);
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
        $apunte->img_destacada = $img['path'];
        $apunte->user_id = $user->_id;
        $apunte->etiquetas_ids = $request->etiquetas_ids;
        $apunte->reacciones = [];
        $apunte->activo = 1;
        $apunte->save();
        $apunte->user;

        //Se le dan 25 clips por subir un apunte y se le desbloquea igual manera
        $this->pagoApunte($user,$apunte, 25);
        $this->desbloquearApunte($user, $apunte, 0, 0);

        return response()->json([
            'message' => $subir['message'],
            'data' => $apunte,
        ],$subir['code']);
    }

    public function show($id)
    {
        $apunte = Apunte::where([['slug', $id],['activo', 1]])
        ->with('user:name,img_perfil','comentarios.user:name,img_perfil')->first();
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
            $comentarios = Comentario::where('apunte_id', $apunte->_id)->delete();
            $this->borrarObjeto('apunte_id',$apunte->_id);
            $apunte->delete();
            
            $mensaje = 'Apunte y comentarios borrados.';
        }else{
            $mensaje = 'No pudimos encontrar el Apunte.';
        }

        return response()->json([
            'message' => $mensaje,
        ],$code);
    }

    public function apuntesUsuario($id){
        $mis_apuntes = Apunte::where([['user_id', $id],['activo', 1]])
        ->with('user:name,img_perfil')->paginate(4);

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
        $valida = $this->desbloquearApunte($user,$apunte, 25, 10);

        return response()->json([
            'message' => $valida['mensaje'],
        ],$valida['code']);
    }

    public function apuntesGuardados($id){
        $page = isset($_GET['page']);
        if(!$page){
            return response()->json([
                'message' => 'La ruta esta mal escrita.',
            ],405);
        }
        $page = $_GET['page'];

        $user = User::find($id);
        if(!$user){
            return response()->json([
                'message' => 'Este usuario no existe',
            ],404);
        }
        $apuntes = [];

        foreach($user->apuntes_comprados as $id){
            $note = Apunte::where('_id', $id)->with('user:name,img_perfil')->first();
            if($note){
                array_push($apuntes,$note);
            }else{
                $compras = $user->apuntes_comprados;
                $clave = array_search($id, $compras);
                unset($compras[$clave]);
                $compras = array_values($reacciones);
                $user->apuntes_comprados = $compras;
                $user->save();
            }
        }

        $apuntes = $this->paginacionPersonalizada($page, $apuntes, 4, 'created_at');

        return response()->json([
            'message' => 'success',
            'data' => $apuntes,
        ],200);
    }

    public function aputnesSiteMap() {
        $slugs = Apunte::all()->pluck('slug');

        return response()->json([
            'message' => 'success',
            'data' => $slugs
        ],200);
    }

}
