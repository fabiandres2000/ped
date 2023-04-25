<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class OrdenTemasModulos extends Model
{
    protected $table = 'orden_temas_modulos';
    protected $fillable = [
        'conse',
        'docente',
        'unidad',
        'tema',
    ];

    public static function OrdenTemas($data)
    {

        $Opc = OrdenTemasModulos::where('unidad', $data['UniTema'])
            ->where('docente', Auth::user()->id);
        $Opc->delete();
        $j=1;
        foreach ($data["ConsTem"] as $key => $val) {
            $respuesta = OrdenTemasModulos::create([
                'conse' => $j,
                'docente' => Auth::user()->id,
                'unidad' => $data["UniTema"],
                'tema' => $data["ConsTem"][$key],
            ]);
            $j++;
        }
        return $respuesta;

    }
}
