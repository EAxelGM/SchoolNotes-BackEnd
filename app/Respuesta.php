<?php

namespace App;

//mongoDB conexion
use Jenssegers\Mongodb\Eloquent\Model as MongoModel;

class Respuesta extends MongoModel
{
    protected $primaryKey="_id";
    protected $table='respuestas';

    protected $fillable = [ 
        'contenido', 
        'user_id',
        'pregunta_id', 
        'verificado',
        'reacciones',
    ];

    public function pregunta(){
        return $this->belongsTo('App\Pregunta');
    }

    public function user(){
        return $this->belongsTo('App\User');
    }
}
