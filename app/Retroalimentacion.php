<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Retroalimentacion extends Model
{
    protected $table = 'retroalimentacion';
    protected $fillable = [
        'evaluacion',
        'pregunta',
        'alumno',
        'retro',
    ];

    public static function guardar($Eval, $Pregunta, $Alumno, $Retro)
    {

        $Retroalimentacion = Retroalimentacion::updateOrCreate([
            'alumno' => $Alumno, 'pregunta' => $Pregunta,
        ], [
            'evaluacion' => $Eval,
            'pregunta' => $Pregunta,
            'alumno' => $Alumno,
            'retro' => $Retro,
        ]);

        return $Retroalimentacion;
    }

    public static function ConsulRetro($preg, $alum)
    {
        $Retro = Retroalimentacion::where('pregunta', $preg)
            ->where('alumno', $alum)
            ->first();

        if ($Retro) {
            return $Retro->retro;
        } else {
            return "";
        }
    }

    public static function VaciarRegistros()
    {
        $Respuesta = Retroalimentacion::truncate();
        return $Respuesta;
    }

}
