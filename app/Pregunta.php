<?php

namespace App;

//mongoDB conexion
use Jenssegers\Mongodb\Eloquent\Model as MongoModel;

class Pregunta extends MongoModel
{
    protected $primaryKey="_id";
    protected $table='preguntas';

    protected $fillable = [
        'contenido', 
        'user_id',
        'verificado', 
        'reacciones',
    ];

    public function respuestas(){
        return $this->hasMany('App\Respuesta');
    }
    
    public function user(){
        return $this->belongsTo('App\User');
    }
}
