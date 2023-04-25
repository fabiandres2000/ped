<?php

namespace App;

use Auth;
use Illuminate\Database\Eloquent\Model;

class RespEvalTaller extends Model
{

    protected $table = 'resp_evaltaller';
    protected $fillable = [
        'alumno',
        'evaluacion',
        'archivo',
        'fecha',
        'evaluada',
        'calificacion',
        'califvisible',
        'pregunta',
    ];

    public static function Guardar($DetEval, $archivo, $fecha)
    {
        $idPreg = $DetEval->id;
        $punt_max = $DetEval->puntaje;
        $idEval = $DetEval->evaluacion;

            $Opc = RespEvalTaller::where('pregunta', $idPreg)
                ->where('alumno', Auth::user()->id)
                ->first();
                if ($Opc) {
                    $Opc->delete();
                }

            $respuestataller = RespEvalTaller::create([
                'alumno' => Auth::user()->id,
                'evaluacion' => $idEval,
                'archivo' => $archivo,
                'fecha' => $fecha,
                'evaluada' => 'NO',
                'calificacion' => '0/' . $punt_max,
                'califvisible' => '0/' . $punt_max,
                'pregunta' => $idPreg,
            ]);

            $respuesta =[
                'RegViejo' => $Opc,
                'RegNuevo' =>$respuestataller
            ];
    

        return $respuesta;

    }

    public static function DesEvalResp($DesEva)
    {
        $Alumno = $DesEva->alumno;
        $evalua = $DesEva->evaluacion;

        $respeval = RespEvalTaller::join('evaluacion', 'evaluacion.id', 'resp_evaltaller.evaluacion')
            ->join('users', 'users.id', 'resp_evaltaller.alumno')
            ->select('resp_evaltaller.id', 'resp_evaltaller.evaluacion', 'resp_evaltaller.archivo', 'resp_evaltaller.fecha', 'resp_evaltaller.evaluada', 'resp_evaltaller.calificacion', 'evaluacion.intentos_perm', 'evaluacion.calif_usando', 'evaluacion.punt_max', 'evaluacion.titulo', 'evaluacion.tip_evaluacion', 'users.nombre_usuario')
            ->where('resp_evaltaller.alumno', $Alumno)
            ->where('resp_evaltaller.evaluacion', $evalua)
            ->get();
        return $respeval;
    }

    public static function GuarEvalCal($id, $cal, $calvis)
    {
        $Respuesta = RespEvalTaller::where('id', $id)->update([
            'calificacion' => $cal,
            'califvisible' => $calvis,
            'evaluada' => 'SI',
        ]);

        $DesResp = RespEvalTaller::where('id', $id)
            ->first();
        return $DesResp;
    }

    public static function RespEvalTallerAlum($idPreg,$Est)
    {
        $DesVerFal = RespEvalTaller::where('resp_evaltaller.pregunta', $idPreg)
            ->where('resp_evaltaller.alumno', $Est)
            ->first();
        return $DesVerFal;
    }

    public static function VaciarRegistros(){
        $Respuesta = RespEvalTaller::truncate();
        return $Respuesta;
     }

}
