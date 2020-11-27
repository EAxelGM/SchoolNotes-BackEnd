<?php

namespace App;

//mongoDB conexion
use Jenssegers\Mongodb\Eloquent\Model as MongoModel;

class Pregunta extends MongoModel
{
    protected $primaryKey="_id";
    protected $table='preguntas';
    //protected $appends = ['respuestas_count'];
    
    protected $fillable = [
        'pregunta', 
        'descripcion', 
        'user_id',
        'etiquetas_ids',
        'verificado', 
        'reacciones',
        'activo',
    ];

    public function respuestas(){
        return $this->hasMany('App\Respuesta')->orderBy('verificado', 'DESC');
    }
    
    public function user(){
        return $this->belongsTo('App\User');
    }
    
    /* public function getRespuestasCountAttribute() { 
        return $this->respuestas->count(); 
    } */


}
