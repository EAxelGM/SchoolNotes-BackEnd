<?php

namespace App;

//mongoDB conexion
use Jenssegers\Mongodb\Eloquent\Model as MongoModel;

class Portafolio extends MongoModel
{
    protected $primaryKey="_id";
    protected $table='portafolios';
    
    protected $fillable = [
        'nombre', 
        'descripcion', 
        'img',
        'apuntes_id',
        'etiquetas_ids',
    ];
}
