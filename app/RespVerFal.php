<?php

namespace App;

use Auth;
use Illuminate\Database\Eloquent\Model;

class RespVerFal extends Model
{

    protected $table = 'resp_pregverfal';
    protected $fillable = [
        'evaluacion',
        'alumno',
        'pregunta',
        'respuesta_alumno',
        'fecha',
    ];

    public static function Guardar($data, $fecha)
    {

        $Opc = RespVerFal::where('pregunta', $data["Pregunta"])
            ->where('alumno', Auth::user()->id)
            ->first();
        if ($Opc) {
            $Opc->delete();
        }

        if (isset($data["radpregVerFal"])) {
            foreach ($data["radpregVerFal"] as $key2 => $val2) {
                $resp = $data["radpregVerFal"][$key2];
            }

            $respuestaVerFal = RespVerFal::create([
                'evaluacion' => $data["IdEvaluacion"],
                'alumno' => Auth::user()->id,
                'pregunta' => $data["Pregunta"],
                'respuesta_alumno' => $resp,
                'fecha' => $fecha,
            ]);
        }

        $respuesta = [
            'RegViejo' => $Opc,
            'RegNuevo' => $respuestaVerFal,
        ];

        return $respuesta;
    }

    public static function DesRespVerFal($DesEva)
    {

        $Alumno = $DesEva->alumno;
        $evalua = $DesEva->evaluacion;
        return RespVerFal::join('evaluacion', 'evaluacion.id', 'resp_pregverfal.evaluacion')
            ->join('eval_verfal', 'eval_verfal.id', 'resp_pregverfal.pregunta')
            ->join('alumnos', 'alumnos.usuario_alumno', 'resp_pregverfal.alumno')
            ->select('resp_pregverfal.id', 'resp_pregverfal.evaluacion', 'resp_pregverfal.respuesta_alumno', 'resp_pregverfal.fecha', 'eval_verfal.respuesta', 'eval_verfal.puntaje', 'eval_verfal.pregunta', 'evaluacion.intentos_perm', 'evaluacion.calif_usando', 'evaluacion.punt_max', 'evaluacion.titulo', 'evaluacion.tip_evaluacion', 'alumnos.nombre_alumno', 'alumnos.apellido_alumno')
            ->where('resp_pregverfal.alumno', $Alumno)
            ->where('resp_pregverfal.evaluacion', $evalua)
            ->get();
    }

    public static function VaciarRegistros()
    {
        $Respuesta = RespVerFal::truncate();
        return $Respuesta;
    }

}
