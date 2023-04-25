<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;


class PuntPregME extends Model
{
    protected $table = 'puntuacion_preguntas_me';
    protected $fillable = [
        'tema',
        'pregunta',
        'alumno',
        'puntos'

    ];

    public static function Guardar($tema, $preg, $puntos)
    {

        $Opc = PuntPregME::where('pregunta', $preg)
            ->where('alumno', Auth::user()->id)
            ->where('tema', $tema)
            ->first();
        if ($Opc) {
            $Opc->delete();
        }

        return PuntPregME::create([
            'tema' => $tema,
            'pregunta' => $preg,
            'alumno' => Auth::user()->id,
            'puntos' => $puntos,
        ]);

    }

    public static function ConsulPunt($preg, $alum)
    {
        $Opc = PuntPregME::where('pregunta', $preg)
            ->where('alumno', $alum)
            ->first();
        return $Opc;
    }

    public static function ConsulPuntEval($tema, $alum)
    {
        $Opc = PuntPregME::where('tema', $tema)
            ->where('alumno', $alum)
            ->get();
        return $Opc;

    }


    public static function UpdatePuntPreg($tema, $Pregunta, $Alumno, $Puntaje)
    {
        $respuesta = PuntPregME::where(['tema' => $tema, 'pregunta' => $Pregunta, 'alumno' => $Alumno])->update([
            'puntos' => $Puntaje,

        ]);
        return $respuesta;

    }
}
