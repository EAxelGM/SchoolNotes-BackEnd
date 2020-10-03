<?php

namespace App;

//mongoDB coneccion
use Jenssegers\Mongodb\Eloquent\Model as MongoModel;

class Apunte extends MongoModel
{
    protected $primaryKey="_id";
    protected $table='apuntes';

    protected $fillable = [
        'titulo',  
        'slug',  
        'descripcion',  
        'archivo',
        'reacciones',
        'activo', 
        'user_id',
        'etiquetas_ids',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
