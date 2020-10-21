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
        'img_destacada',
        'reacciones',
        'activo', 
        'user_id',
        'etiquetas_ids',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function comentarios()
    {
        return $this->hasMany('App\Comentario');
    }

    public function etiqueta()
    {
        return $this->belongsTo('App\Etiqueta');
    }
}
