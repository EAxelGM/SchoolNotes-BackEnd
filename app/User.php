<?php

namespace App;

//use Illuminate\Foundation\Auth\User as AuthenticatableJWT;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;
use Illuminate\Notifications\Notifiable;
//mongoDB coneccion
use Jenssegers\Mongodb\Eloquent\Model as MongoModel;
//JWT
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends MongoModel implements Authenticatable,JWTSubject
{
    //use Notifiable;
    use AuthenticableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'correo_verificado',
        'token_verificacion',
        'descripcion_perfil',
        'fecha_nacimiento',
        'img_perfil',
        'seguidos',
        'seguidores',
        'etiquetas_ids',
        'clips',
        'diamond_clips',
        'apuntes_comprados',
        'universidad_id',
        'carrera_id',
        'tipo',
        'activo',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'token_verificacion',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /*
        funciones para JWT
    */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function publicaciones(){
        return $this->hasMany('App\Publicacion');
    }

    public function apuntes(){
        return $this->hasMany('App\Apunte');
    }

    public function codigoCreador(){
        return $this->hasOne('App\CodigoCreador');
    }

    public function universidad(){
        return $this->belongsTo('App\Universidad', 'universidad_id');
    }

    public function carrera(){
        return $this->belongsTo('App\Carrera', 'carrera_id');
    }

}
