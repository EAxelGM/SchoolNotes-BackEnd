<?php

namespace App;

//mongoDB conexion
use Jenssegers\Mongodb\Eloquent\Model as MongoModel;

class CodigoCreador extends MongoModel
{
    protected $primaryKey="_id";
    protected $table='codigos_creadores';

    protected $fillable = [
        'codigo',
        'descuento_compra',   
        'clips_registro',
        'user_id',
        'activo',
    ];

    public function setCodigoAttribute($valor){
        $this->attributes['codigo'] = strtoupper($valor);
    }
    public function getCodigoAttribute($valor){
        return strtoupper($valor);
    }
}
