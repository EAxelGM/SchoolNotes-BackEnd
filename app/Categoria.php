<?php

namespace App;

//mongoDB coneccion
use Jenssegers\Mongodb\Eloquent\Model as MongoModel;

class Categoria extends MongoModel
{
    protected $primaryKey="_id";
    protected $table='categorias';

    protected $fillable = [
        'nombre', 
        'slug',
        'activo', 
    ];
}
