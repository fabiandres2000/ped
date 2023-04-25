<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class PuntPregMEPruebaSimulacro extends Model
{
    protected $table = 'puntuacion_preguntas_me_prueba';
    protected $fillable = [
        'sesion',
        'area',
        'pregunta',
        'alumno',
        'puntos',

    ];

    public static function Guardar($IdSesion, $IdArea, $preg, $puntos)
    {

        $Opc = PuntPregMEPruebaSimulacro::where('pregunta', $preg)
            ->where('sesion', $IdSesion)
            ->where('alumno', Auth::user()->id)
            ->where('area', $IdArea)
            ->first();
        if ($Opc) {
            $Opc->delete();
        }

        return PuntPregMEPruebaSimulacro::create([
            'sesion' => $IdSesion,
            'area' => $IdArea,
            'pregunta' => $preg,
            'alumno' => Auth::user()->id,
            'puntos' => $puntos,
        ]);

    }

    public static function ConsulPunt($IdSesion, $IdArea, $alum)
    {
        $Opc = PuntPregMEPruebaSimulacro::where('sesion', $IdSesion)
            ->where('area', $IdArea)
            ->where('alumno', $alum)
            ->get();
        return $Opc;
    }

}
