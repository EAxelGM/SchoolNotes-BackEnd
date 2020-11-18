<?php

namespace App;

//mongoDB conexion
use Jenssegers\Mongodb\Eloquent\Model as MongoModel;

class Carrera extends MongoModel
{
    protected $primaryKey="_id";
    protected $table='carreras';

    protected $fillable = [ 
        'nombre', 
        'img',
        'created_by',
        'activo',
    ];

    public function user(){
        return $this->belongsTo('App\User','created_by');
    }

    public function setNombreAttribute($valor){
        $this->attributes['nombre'] = strtolower($valor);
    }
    public function getNombreAttribute($valor){
        return ucwords($valor);
    }
}
