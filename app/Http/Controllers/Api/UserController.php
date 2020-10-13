<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\User;
use App\Etiqueta;
use Carbon\Carbon;
use App\Traits\EnviarCorreos;
use App\Traits\Validaciones;
use App\Traits\Generador;
use App\Traits\Imagenes;
use Illuminate\Support\Facades\Storage;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

//Uso del Faker para respaldar contraseña
use Faker\Generator as Faker;
use Illuminate\Support\Str;

class UserController extends Controller
{
    use EnviarCorreos, Imagenes, Validaciones, Generador;

    public $paginate = 5;

    public function index(){
        if(!isset($_GET['search']) || $_GET['search'] == ''){
            $users = User::where('activo', 1)->paginate($this->paginate);
            return response()->json([
                'message' => 'Todos los usuarios activos.',
                'data' => $users,
            ]);
        }else{
            $users = User::where([
                ['activo', 1],
                ['name', 'like', '%'.$_GET['search'].'%']
            ])->paginate($this->paginate);
            return response()->json([
                'message' => 'Todos los usuarios activos.',
                'data' => $users,
            ]);
        }
    }

    public function store(Request $request){
        //
    }
    
    public function show($id){
        $user = User::find($id);
        $mensaje = $user ? 'Usuario encontrado con exito' : 'No existe ningun usuario con ese ID';
        $code = $user ? 200 : 404;
        if($code == 200){
            $user->publicaciones;
            $user->apuntes;
            $etiquetas = [];
            foreach($user->etiquetas_ids as $etiqueta_id){
                $etiqueta = Etiqueta::find($etiqueta_id);
                if($etiqueta){
                    array_push($etiquetas, $etiqueta);
                }
            }
            $user['etiquetas'] = $etiquetas;
        }
        return response()->json([
            'message' => $mensaje,
            'data' => $user,
        ],$code);
    } 

    public function update(Request $request, $id){
        $user = User::find($id);

        $code = $user ? 200 : 404;

        if($code >= 200 && 299 >= $code){
            $user->fill($request->all());
            if($user->isClean()){
                return response()->json(['message'=>'Especifica al menos un valor diferente'],421);
            }
            $user->save();

            //foto de perfil
            if($request->file('img_perfil')){
                $path = Storage::disk('public')->put('img_perfiles', $request->file('img_perfil'));
                $user->fill(['file' => asset($path)])->save();
            }
        }
        $mensaje = $user ? 'Usuario modificado con exito' : 'No existe ningun usuario con ese ID';
        return response()->json([
            'message' => $mensaje,
            'data' => $user,
        ],$code);
    }

    public function imgPerfil(Request $request){
        $user = User::find($request->id);
        $code = $user ? 200 : 404;

        if($code == 200){
            //foto de perfil
            $validator = Validator::make($request->all(), [
                'img_perfil' => 'mimes:jpeg,jpg,png', //Permitimos estos tipos de archivos
            ]);
            if($validator->fails()){
                return response()->json($validator->errors()->toJson(), 400);
            }
            if($request->file('img_perfil')){
                $path = Storage::disk('fotos_perfiles')->put('img_perfiles', $request->file('img_perfil'));
                $user->fill(['img_perfil' => asset($path)])->save();
            }
        }
        $mensaje = $user ? 'Foto de perfil modificada con exito' : 'No existe ningun usuario con ese ID';
        
        return response()->json([
            'message' => $mensaje,
            'data' => $user,
        ],$code);
    }
    
    public function destroy($id){
        $user = User::find($id);
        $code = $user ? 200 : 404;
        if($code == 200){
            switch ($user->activo) {
                case 1:
                    $user->activo = 0;
                    $mensaje = 'Usuario desactivado con exito';
                    $user->save();
                break;

                case 0:
                    $user->activo = 1;
                    $mensaje = 'Usuario activado con exito';
                    $user->save();
                break;
                
                default:
                    $mensaje = 'Oops... hubo un error con este usuario: '.$user->_id;
                break;
            }
        }else{
            $mensaje = 'No pudimos encontrar a este usuario para poder eliminarlo.';
        }
        
        return response()->json([
            'message' => $mensaje,
            'data' => $user,
        ],$code);
    }

    //Funciones para JWT
    public function authenticate(Request $request){
        $credentials = $request->only('email', 'password');
        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['message' => 'Correo Electronico o Contraseña son incorrectos'], 400);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'Oops... hubo un error y no pudimos crearte el token'], 500);
        }
        return response()->json(compact('token'));
    }

    //Loggout
    public function loggout(Request $request)
    {
        $this->validate($request, ['token' => 'required']);

        try {
            JWTAuth::invalidate($request->token);

            return response()->json([
                'success' => true,
                'message' => 'El logg out fue exitoso'
            ]);
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'No se pudo realizar el logg out'
            ], 500);
        }
    }
    
    public function getAuthenticatedUser(){
        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }
        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(['El token ha expirado'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['Token invalido'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }
        $user->publicaciones;
        $etiquetas = [];
        foreach($user->etiquetas_ids as $etiqueta_id){
            $etiqueta = Etiqueta::find($etiqueta_id);
            if($etiqueta){
                array_push($etiquetas, $etiqueta);
            }
        }
        $user['etiquetas'] = $etiquetas;
        return response()->json(compact('user'));
    }

    //JWT pero es un registro
    public function register(Request $request){
        $validator = $this->datosUser($request->all());
        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        $user = User::create([
            'name' => $request->get('name'),
            'apellidos' => $request->get('apellidos'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
            'correo_verificado' => false,
            'descripcion_perfil' => '',
            'fecha_nacimiento' => '',
            'img_perfil' => asset('img_perfiles/default.png'),
            'seguidos' => [],
            'seguidores' => [],
            'etiquetas_ids' => $request->get('etiquetas'),
            'clips' => 0,
            'diamond_clips' => 0,
            'apuntes_comprados' => [],
            'tipo' => 'usuario',
            'activo' => 1,
        ]);

        //crea primera publicacion y apunte + bonificacion de clips gratis!
        $this->bienvenida($user, 10);

        //Envia correo para verificar el correo electronico
        $this->enviarCorreo($user->_id);
        
        $token = JWTAuth::fromUser($user);
        return response()->json(compact('user','token'),201);
    }

    public function enviarCorreo($id){
        $user = User::find($id);
        if(!$user){
            return response()->json([
                'message' => 'Este usuario no existe'
            ],404);
        }

        if($user->correo_verificado){
            return response()->json([
                'message' => 'El correo ya ha sido verificado antes.',
            ],200);
        }

        //Generar el token
        $user = $this->tokenCorreo($user);

        //Envia correo para verificar el correo electronico
        $this->verificarCorreo($user);
        return response()->json([
            'message' => 'Correo enviado.'
        ],200);
    }

    //Validacion de correo.
    public function validarCorreo($id,$token){
        $user = User::where([
            ['_id',$id],
        ])->first();

        $code = $user ? 200 : 421;
        if($code == 200){
            $expiracion = Carbon::parse($user->token_verificacion['expira']);
            $expiracion = Carbon::now()->diffInHours($expiracion,false);
            
            if($expiracion >= 0){
                if($user->token_verificacion['token'] == $token){
                    $mensaje = !$user->correo_verificado ? 'Se ha verificado con exito el Correo Electronico '.$user->email : 'Este Correo electronico ya habia sido verificado.';
                    $user->correo_verificado = true;
                    $user->save();
                }else{
                    $mensaje = 'Al parecer este token es invalido. ';
                }
            }else{
                $mensaje = 'Lo sentimos pero este token ha expirado, porfavor solicita un reenvio para verificar tu correo electronico.';
                $code = 403;
            }
        }else{
            $mensaje = 'Al parecer esta validacion es incorrecta, vuelve a intentarlo o envia un correo electronico a schoolnotes.info@gmail.com ';
        }

        return response()->json([
            'message' => $mensaje,
            'data' => $user,
        ],$code);
    }

    public function cambiarContrasena(Request $request){
        $user = User::find($request->id);

        if($user){
            if(Hash::check($request->vieja_contrasena, $user->password)){
                if($request->nueva_contrasena == $request->repetir_contrasena){
                    $user->password = Hash::make($request->nueva_contrasena);
                    $user->save();
                    return response()->json([
                        'message' => 'La contraseña se ha modifificado con exito.',
                    ], 200);
                    
                }else{
                    return response()->json([
                        'message' => 'Las contrseñas no coinciden',
                    ], 421);
                }
            }else{
                return response()->json([
                    'message' => 'La contraseña antigua es incorrecta',
                ], 421);

            }
        }else{
            return response()->json([
                'message' => 'Este usuario no existe.',
            ], 421);
        }
        
        return response()->json([
            'message' => 'Ha ocurrido un error.',
        ], 500);
    }

    public function recuperarPassword($id, $token){
        $user = User::find($id);
        if($user){
            if($token == 'enviar-mail'){
                //Generar el token
                $user = $this->tokenCorreo($user);
                //Enviar correo electronico
                $this->recuperarContrasena($user);
                $mensaje = 'Hemos enviado un correo electronico a '.$user->email.', porfavor revisa en tu bandeja de entrada o spam y termina el procedimiento.';
                $code = 201;
            }else{
                $expiracion = Carbon::parse($user->token_verificacion['expira']);
                $expiracion = Carbon::now()->diffInHours($expiracion,false);
                
                if($expiracion >= 0){
                    if($user->token_verificacion['token'] == $token){
                        $mensaje = 'Hemos comprobado, que tu solicitaste este cambio, porfavor continua con tu cambio de contraseña.';
                        $code = 200;
                    }else{
                        $mensaje = 'Al parecer este token es invalido. ';
                        $code = 421;
                    }
                }else{
                    $mensaje = 'Lo sentimos pero este token ha expirado, porfavor solicita un reenvio para verificar tu correo electronico.';
                    $code = 403;
                }
            }
        }else{
            $mensaje = 'No encontramos ningun usuario.';
            $code = 404;
        }
        return response()->json([
            'message' => $mensaje,
            'code' => $code,
        ],$code);
    }
}
