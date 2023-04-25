<?php

namespace App;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class RespEvalComp extends Model
{

    protected $table = 'resp_evalpregcomp';
    protected $fillable = [
        'alumno',
        'evaluacion',
        'pregunta',
        'respuesta',
        'fecha',
        'evaluada',
        'calificacion',
        'califvisible',
    ];

    public static function Guardar($DetPreg,$datos, $fecha)
    {
        $idEval = $datos['IdEvaluacion'];
        $punt_max = $DetPreg->puntaje;

        $Opc = RespEvalComp::where('pregunta',  $datos['Pregunta'])
            ->where('alumno', Auth::user()->id)
            ->first();
            if ($Opc) {
                $Opc->delete();
            }

        $RespComplete = RespEvalComp::create([
            'alumno' => Auth::user()->id,
            'evaluacion' => $idEval,
            'pregunta' =>  $datos['Pregunta'],
            'respuesta' =>  $datos['RespPregComplete'],
            'fecha' => $fecha,
            'evaluada' => 'NO',
            'calificacion' => '0/' . $punt_max,
            'califvisible' => '0/' . $punt_max,
        ]);

        $respuesta =[
            'RegViejo' => $Opc,
            'RegNuevo' =>$RespComplete
        ];

        return $respuesta;

    }

    public static function DesEvalResp($DesEva)
    {
        $Alumno = $DesEva->alumno;
        $evalua = $DesEva->evaluacion;

        $respeval = RespEvalComp::join('evaluacion', 'evaluacion.id', 'resp_evalpregcomp.evaluacion')
            ->join('eval_complete', 'eval_complete.id', 'resp_evalpregcomp.pregunta')
            ->join('users', 'users.id', 'resp_evalpregcomp.alumno')
            ->select('resp_evalpregcomp.id', 'resp_evalpregcomp.evaluacion', 'resp_evalpregcomp.pregunta', 'resp_evalpregcomp.respuesta', 'resp_evalpregcomp.fecha', 'resp_evalpregcomp.evaluada', 'resp_evalpregcomp.calificacion', 'evaluacion.intentos_perm', 'evaluacion.calif_usando', 'evaluacion.punt_max', 'eval_complete.opciones', 'eval_complete.parrafo', 'evaluacion.titulo', 'evaluacion.tip_evaluacion', 'users.nombre_usuario')
            ->where('resp_evalpregcomp.alumno', $Alumno)
            ->where('resp_evalpregcomp.evaluacion', $evalua)
            ->get();
        return $respeval;
    }

    public static function DesResp($Preg, $Est)
    {
        $DesEval = RespEvalComp::where('pregunta', $Preg)
            ->where('alumno', $Est)
            ->first();
        return $DesEval;

    }

    public static function GuarEvalCal($id, $cal, $calvis)
    {
        $Respuesta = RespEvalComp::where('id', $id)->update([
            'calificacion' => $cal,
            'califvisible' => $calvis,
            'evaluada' => 'SI',
        ]);

        $DesResp = RespEvalComp::where('id', $id)
            ->first();
        return $DesResp;
    }

      public static function VaciarRegistros(){
        $Respuesta = RespEvalComp::truncate();
        return $Respuesta;
     }

}
