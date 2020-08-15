<?php

namespace App;

//mongoDB coneccion
use Jenssegers\Mongodb\Eloquent\Model as MongoModel;

class Apunte extends MongoModel
{
    protected $primaryKey="_id";
    protected $table='apuntes';

    protected $fillable = [
        'contenido',  
        'imagenes',
        'reacciones',
        'activo', 
        'user_id',
        'etiqueta_ids',
        'categoria_id',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
