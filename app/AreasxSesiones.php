<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AreasxSesiones extends Model
{
    protected $table = 'me_areas_sesion';
    protected $fillable = [
        'simulacro',
        'sesion',
        'area',
        'n_preguntas',
    ];

    public static function listar($sesion)  
    {
        $Areas = AreasxSesiones::where('estado', "ACTIVO")
            ->join("areas_me","areas_me.id","area")
            ->where('sesion', $sesion)
            ->select('me_areas_sesion.*',"areas_me.nombre_area")
            ->get();
        return $Areas;
    }

}
