<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompraClips extends Model
{
    protected $primaryKey="_id";
    protected $table='compras_de_clips';

    protected $fillable = [
        'user_id',
        'clips_recibidos',
        'plan',

    ];
}
