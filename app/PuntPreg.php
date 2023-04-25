<?php

namespace App;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PuntPreg extends Model
{
    protected $table = 'puntuacion_preguntas';
    protected $fillable = [
        'evaluacion',
        'pregunta',
        'alumno',
        'puntos',

    ];

    public static function Guardar($eval, $preg, $puntos)
    {

        $Opc = PuntPreg::where('pregunta', $preg)
            ->where('alumno', Auth::user()->id)
            ->first();
        if ($Opc) {
            $Opc->delete();
        }

        return PuntPreg::create([
            'evaluacion' => $eval,
            'pregunta' => $preg,
            'alumno' => Auth::user()->id,
            'puntos' => $puntos,
        ]);

    }

    public static function ConsulPunt($preg, $alum)
    {

        $Opc = PuntPreg::where('pregunta', $preg)
            ->where('alumno', $alum)
            ->first();
        return $Opc;

    }

    public static function ConsulPuntEval($eval, $alum)
    {

        $Opc = PuntPreg::where('evaluacion', $eval)
            ->where('alumno', $alum)
            ->get();
        return $Opc;

    }


    public static function UpdatePuntPreg($Eval, $Pregunta, $Alumno, $Puntaje)
    {
        $respuesta = PuntPreg::where(['evaluacion' => $Eval, 'pregunta' => $Pregunta, 'alumno' => $Alumno])->update([
            'puntos' => $Puntaje,

        ]);
        return $respuesta;

    }

    public static function VaciarRegistros(){
        $Intentos = PuntPreg::truncate();
        return $Intentos;
     }

     public static function ConsulRetro($eval){

        $InfRetro = DB::connection("mysql")->select("SELECT ra.retro, pp.puntos, pp.pregunta, ep.tipo FROM evalpreg ep LEFT JOIN puntuacion_preguntas pp ON ep.idpreg=pp.pregunta LEFT JOIN retroalimentacion ra ON ep.idpreg=ra.pregunta  where ep.ideval ='".$eval."' and pp.alumno='".Auth::user()->id."'");

        return $InfRetro;

    }

}
