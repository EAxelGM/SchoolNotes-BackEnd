<?php

namespace App;

//mongoDB conexion
use Jenssegers\Mongodb\Eloquent\Model as MongoModel;

class Warning extends MongoModel
{
    protected $primaryKey="_id";
    protected $table='warnings';

    protected $fillable = [ 
        'user_id', 
        'motivo',
    ];
}
