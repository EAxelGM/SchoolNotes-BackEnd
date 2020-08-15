<?php

namespace App;

//mongoDB conexion
use Jenssegers\Mongodb\Eloquent\Model as MongoModel;

class Comentario extends MongoModel
{
    protected $primaryKey="_id";
    protected $table='comentarios';

    protected $fillable = [
        'apunte_id',
        'publicacion_id',   
        'comentario',
        'reacciones',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function publicacion()
    {
        return $this->belongsTo('App\Publicacion');
    }

    public function apunte()
    {
        return $this->belongsTo('App\Apunte');
    }
}
