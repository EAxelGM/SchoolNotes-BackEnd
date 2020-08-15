<?php

namespace App;

//mongoDB coneccion
use Jenssegers\Mongodb\Eloquent\Model as MongoModel;

class Publicacion extends MongoModel
{ 
    protected $primaryKey="_id";
    protected $table='publicaciones';

    protected $fillable = [
        'contenido',  
        'reacciones',
        'activo', 
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
    
    public function comentarios(){
        return $this->hasMany('App\Comentario');
    }
}
