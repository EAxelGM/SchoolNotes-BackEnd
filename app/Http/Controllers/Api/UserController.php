<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use App\User;
use App\Etiqueta;
use Carbon\Carbon;
use App\Traits\EnviarCorreos;
use App\Traits\Validaciones;
use App\Traits\Generador;
use App\Traits\Imagenes;
use App\Traits\Transacciones;
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
    use EnviarCorreos, Imagenes, Validaciones, Generador, Transacciones;

    public $paginate = 5;

    public function index(){
        //return view('layouts.mail');
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
            $user->universidad;
            $user->carrera;
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
            if($user->descripcion_perfil == '' && $request->descripcion_perfil != ''){
                $user->clips = $user->clips+5;
                $user->save();
            } 

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
        //$user = User::find($request->id);
        $user = Auth::user();
        $code = $user ? 200 : 404;
        $mensaje_clip = '';
        if($code == 200){
            //foto de perfil
            $validator = Validator::make($request->all(), [
                'img_perfil' => 'mimes:jpeg,jpg,png', //Permitimos estos tipos de archivos
            ]);
            if($validator->fails()){
                return response()->json($validator->errors(), 400);
            }
            if($user->img_perfil == asset('img_perfiles/default.png')){
              $user->clips = $user->clips+10;
              $mensaje_clip = ', Se te han añadido 10 clips a tu cuenta.';
            }
            if($request->file('img_perfil')){
                $path = Storage::disk('fotos_perfiles')->put('img_perfiles', $request->file('img_perfil'));
                $user->fill(['img_perfil' => asset($path)])->save();
            }
        }
        $mensaje = $user ? 'Foto de perfil modificada con exito'.$mensaje_clip : 'No existe ningun usuario con ese ID';

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
        $user->codigoCreador;
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
            'portafolios_comprados' => [],
            'universidad_id' => null,
            'carrera_id' => null,
            'tipo' => 'usuario',
            'activo' => 1,
        ]);

        //Validacion del codigo 
        if($request->codigo != null){
            $codigo = $this->registroCodigoCreador($user, $request->codigo);
            if($codigo['code'] > 400){
                return response()->json(['message' => $codigo['message']],$codigo['code']);
            }
        }

        //Empareja con la primer publicacion gratis
        $this->bienvenida($user, 0);

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
                'message' => 'El correo ya ha sido verificado antes. Porfavor solo actualice la página',
            ],421);
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
                if($user->token_verificacion['token'] == $token && !$user->correo_verificado){
                    $clips = 20;
                    $user->correo_verificado = true;
                    $user->clips = $user->clips+$clips;
                    $user->save();
                    return redirect("https://schoolnotes.live/login?correo=".$user->email."&verificacion=true&razon=success&clips=$clips");
                }else{
                    return redirect("https://schoolnotes.live/login?correo=".$user->email."&verificacion=false&razon=invalid");
                }
            }else{
                return redirect("https://schoolnotes.live/login?correo=".$user->email."&verificacion=false&razon=expiration");
            }
        }else{
            return redirect("https://schoolnotes.live/login?correo=null&verificacion=false&razon=notfound");
        }
    }

    public function cambiarContrasena(Request $request){
        //$user = User::find($request->id);
        $user = Auth::user();

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

    public function recuperarPassword2(Request $request,$email, $token){
        $user = User::where('email', $email)->first();
        if(!$user){
            return response()->json([
                'message' => 'La cuenta no existe',
            ],404);
        }
        $valida = $this->recuperarPassword($email,$token);
        if($valida['code'] == 200){
            if($request->password == $request->password_confirmation){
                $user->password = Hash::make($request->password);
                $user->save();
            }else{
                $valida['mensaje'] = 'Las contraseñas no coinciden';
                $valida['code'] = 421;
            }
        }
        
        return response()->json([
            'message' => $valida['mensaje'],
            'code' => $valida['code'],
        ],$valida['code']);
    }

    public function recuperarPassword($email, $token){
        $user = User::where('email', $email)->first();
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
                        $data['mensaje'] = 'Tu contraseña se ha modificado con exito!';
                        $data['code'] = 200;
                    }else{
                        $data['mensaje'] = 'Al parecer este token es invalido. ';
                        $data['code'] = 421;
                    }
                }else{
                    $data['mensaje'] = 'Lo sentimos pero este token ha expirado, porfavor solicita un reenvio para verificar tu correo electronico.';
                    $data['code'] = 403;
                }
                return $data;
            }
        }else{
            $mensaje = 'No encontramos ninguna cuenta relacionada con '.$email;
            $code = 404;
        }
        return response()->json([
            'message' => $mensaje,
            'code' => $code,
        ],$code);
    }
}
