<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model as MongoModel;

class HistorialClips extends MongoModel
{
    protected $primaryKey="_id";
    protected $table='historial_clips';

    protected $fillable = [
        'user_paga',
        'cantidad_paga',
        'user_recibe',
        'cantidad_recibe',
        'clips_empresa',
        'descripcion',
        'pregunta_id',
        'apunte_id',
        'borrado',
    ];
}
