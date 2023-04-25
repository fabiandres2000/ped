<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class RespEvalEnsay extends Model {

    protected $table = 'resp_evalpregensayo';
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

    public static function Guardar($DetPreg,$datos, $fecha) {
        $idEval = $datos['IdEvaluacion'];
        $punt_max = $DetPreg->puntaje;
        $Text_Respt=$datos['RespPregEns'];
        if($Text_Respt==null){
            $Text_Respt="Esta Evaluación no fue Resuelta...";
        }

        $Opc = RespEvalEnsay::where('pregunta', $datos['Pregunta'])
                ->where('alumno', Auth::user()->id)
                ->first();
                if ($Opc) {
                    $Opc->delete();
                }

        $RespEnsayo =  RespEvalEnsay::create([
                    'alumno' => Auth::user()->id,
                    'evaluacion' => $idEval,
                    'pregunta' => $datos['Pregunta'],
                    'respuesta' => $Text_Respt,
                    'fecha' => $fecha,
                    'evaluada' => 'NO',
                    'calificacion' => '0/' . $punt_max,
                    'califvisible' => '0/' . $punt_max
        ]);

        $respuesta =[
            'RegViejo' => $Opc,
            'RegNuevo' =>$RespEnsayo
        ];

        return $respuesta;
    

    }

    public static function DesEvalResp($DesEva) {
        $Alumno = $DesEva->alumno;
        $evalua = $DesEva->evaluacion;
      
            return RespEvalEnsay::join('evaluacion', 'evaluacion.id', 'resp_evalpregensayo.evaluacion')
                            ->join('eval_pregensayo', 'eval_pregensayo.id', 'resp_evalpregensayo.pregunta')
                            ->join('users', 'users.id', 'resp_evalpregensayo.alumno')
                            ->select('resp_evalpregensayo.id', 'resp_evalpregensayo.evaluacion', 'resp_evalpregensayo.pregunta', 'resp_evalpregensayo.pregunta', 'resp_evalpregensayo.respuesta', 'resp_evalpregensayo.fecha', 'resp_evalpregensayo.evaluada', 'resp_evalpregensayo.calificacion', 'evaluacion.intentos_perm', 'evaluacion.calif_usando', 'evaluacion.punt_max', 'eval_pregensayo.pregunta', 'evaluacion.titulo', 'evaluacion.tip_evaluacion', 'users.nombre_usuario')
                            ->where('resp_evalpregensayo.alumno', $Alumno)
                            ->where('resp_evalpregensayo.evaluacion', $evalua)
                            ->get();
        
    }

    public static function DesResp($Preg,$Est) {
        $DesEval = RespEvalEnsay::where('pregunta', $Preg)
        ->where('alumno',$Est)
        ->first();
    return $DesEval;
       
    }

    public static function GuarEvalCal($id, $cal, $calvis) {
        $Respuesta = RespEvalEnsay::where('id', $id)->update([
            'calificacion' => $cal,
            'califvisible' => $calvis,
            'evaluada' => 'SI'
        ]);


        $DesResp = RespEvalEnsay::where('id', $id)
                ->first();
        return $DesResp;
    }

    public static function VaciarRegistros(){
        $Respuesta = RespEvalEnsay::truncate();
        return $Respuesta;
     }

}
