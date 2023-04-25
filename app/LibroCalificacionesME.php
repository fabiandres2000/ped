<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class LibroCalificacionesME extends Model
{
    protected $table = 'libro_calificaciones_me';
    protected $fillable = [
        'alumno',
        'banco',
        'puntuacion',
        'calificacion',
        'fecha_pres',
        'calf_prof',
        'docente',
        'estado_eval',
        'tema',
    ];

    public static function Guardar($datos, $respviejo, $resp, $fecha)
    {
        $Alumno = Auth::user()->id;
        $IdEval = $datos['IdEval'];
        $Tema = $datos['Tema'];
        $puntMaxi = $datos['TPreg'];
        $TiemEval = "";
        $estado = "EN PROCESO";

        $Libro = self::BusEval($Alumno, $Tema);

        if ($Libro) {
            $puntaje = $Libro->puntuacion;
        } else {
            $puntaje = 0;
        }

        $Preg = PregOpcMulME::where('id', $resp->pregunta)
            ->first();
        $DesOpcPreg = OpcPregMulModuloE::where('pregunta', $resp->pregunta)
            ->get();

        foreach ($DesOpcPreg as $OP) {
            if ($OP->id == $resp->respuesta) {
                if ($OP->correcta == "si") {
                    $puntaje = (int) $puntaje + 1;
                    $PunPreg = \App\PuntPregME::Guardar($Tema, $resp->pregunta, '1');
                } else {
                    $PunPreg = \App\PuntPregME::Guardar($Tema, $resp->pregunta, '0');
                }
            }
        }

        $Calificacion = $puntaje . "/" . strval($puntMaxi);

        if ($datos['nPregunta'] == "Ultima") {

            $estado = "CALIFICADA";

        }
        $puntaje = 0;

        $PunPreg = \App\PuntPregME::ConsulPuntEval($Tema, Auth::user()->id);
        foreach ($PunPreg as $PunP) {
            $puntaje = (int) $puntaje + (int) $PunP->puntos;
        }

        $Calificacion = $puntaje . "/" . strval($puntMaxi);

        return LibroCalificacionesME::updateOrCreate([
            'alumno' => $Alumno, 'tema' => $Tema,
        ], [
            'alumno' => $Alumno,
            'banco' => $IdEval,
            'puntuacion' => $puntaje,
            'calificacion' => $Calificacion,
            'fecha_pres' => $fecha,
            'calf_prof' => 'no',
            'docente' => "",
            'estado_eval' => $estado,
            'tema' => $Tema,
        ]);
    }

    public static function BusEval($id, $alum)
    {
        $DesEval = LibroCalificacionesME::where('evaluacion', $id)
            ->where("alumno", $alum)
            ->first();
        return $DesEval;
    }

    public static function BusEvalDel($id)
    {
        $DesEval = LibroCalificacionesME::where('evaluacion', $id)
            ->first();
        return $DesEval;
    }

}
