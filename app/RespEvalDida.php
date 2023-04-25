<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use Session;

class RespEvalDida extends Model {

    protected $table = 'resp_evalpregdida';
    protected $fillable = [
        'alumno',
        'evaluacion',
        'pregunta',
        'respuesta',
        'fecha',
        'evaluada',
        'calificacion',
        'califvisible'
    ];

    public static function Guardar($DetEval, $Id_PregEnsayo, $Text_Respt, $fecha) {
        $idEval = $DetEval->id;
        $punt_max = $DetEval->punt_max;
        return RespEvalDida::create([
                    'alumno' => Auth::user()->id,
                    'evaluacion' => $idEval,
                    'pregunta' => $Id_PregEnsayo,
                    'respuesta' => $Text_Respt,
                    'fecha' => $fecha,
                    'evaluada' => 'NO',
                    'calificacion' => '0/' . $punt_max,
                    'califvisible' => '0/' . $punt_max
        ]);
    }

    public static function DesEvalResp($DesEva) {
        $Alumno = $DesEva->alumno;
        $evalua = $DesEva->evaluacion;

        $respeval = RespEvalDida::join('evaluacion', 'evaluacion.id', 'resp_evalpregdida.evaluacion')
                ->join('eval_pregdidactica', 'eval_pregdidactica.id', 'resp_evalpregdida.pregunta')
                ->join('users', 'users.id', 'resp_evalpregdida.alumno')
                ->select('resp_evalpregdida.id', 'resp_evalpregdida.evaluacion', 'resp_evalpregdida.pregunta', 'resp_evalpregdida.pregunta', 'resp_evalpregdida.respuesta', 'resp_evalpregdida.fecha', 'resp_evalpregdida.evaluada', 'resp_evalpregdida.calificacion', 'evaluacion.intentos_perm', 'evaluacion.calif_usando', 'evaluacion.punt_max', 'eval_pregdidactica.pregunta', 'eval_pregdidactica.cont_didactico', 'evaluacion.titulo', 'evaluacion.tip_evaluacion', 'users.nombre_usuario')
                ->where('resp_evalpregdida.alumno', $Alumno)
                ->where('resp_evalpregdida.evaluacion', $evalua)
                ->get();
        return $respeval;
    }

    public static function GuarEvalCal($id, $cal, $calvis) {
        $Respuesta = RespEvalDida::where('id', $id)->update([
            'calificacion' => $cal,
            'califvisible' => $calvis,
            'evaluada' => 'SI'
        ]);


        $DesResp = RespEvalDida::where('id', $id)
                ->first();
        return $DesResp;
    }

}
