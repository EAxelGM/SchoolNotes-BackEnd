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
}
