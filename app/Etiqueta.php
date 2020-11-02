<?php

namespace App;

//mongoDB coneccion
use Jenssegers\Mongodb\Eloquent\Model as MongoModel;

class Etiqueta extends MongoModel
{
    protected $primaryKey="_id";
    protected $table='etiquetas';

    protected $fillable = [
        'nombre', 
        'slug',
        'created_by',
        'activo', 
    ];

    public function setNombreAttribute($valor){
        $this->attributes['nombre'] = strtolower($valor);
    }
    public function getNombreAttribute($valor){
        return ucwords($valor);
    }
}
