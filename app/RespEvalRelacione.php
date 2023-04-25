<?php

namespace App;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class RespEvalRelacione extends Model
{

    protected $table = 'resp_pregrelacione';
    protected $fillable = [
        'evaluacion',
        'alumno',
        'pregunta',
        'respuesta_alumno',
        'fecha',
        'eval_preg',
        'consecu',
    ];

    public static function Guardar($data, $fecha)
    {

        $Opc = RespEvalRelacione::where('eval_preg', $data["Pregunta"])
            ->where('alumno', Auth::user()->id)
            ->get();

            $RespPreRel = RespEvalRelacione::leftjoin('eval_relacione_def', 'resp_pregrelacione.pregunta', 'eval_relacione_def.id')
            ->leftjoin('eval_relacione_opc', 'resp_pregrelacione.respuesta_alumno', 'eval_relacione_opc.id')
            ->where('resp_pregrelacione.eval_preg', $data["Pregunta"])
            ->select('eval_relacione_def.opcion', 'eval_relacione_opc.correcta')
            ->get();
        $Opc2 = $RespPreRel;
        
        if ($Opc) {
            $Opc->each->delete();
        }

        foreach ($data["RespPreg"] as $key => $val) {
            $respuestaRelac = RespEvalRelacione::create([
                'evaluacion' => $data["IdEvaluacion"],
                'alumno' => Auth::user()->id,
                'pregunta' => $data["RespPreg"][$key],
                'respuesta_alumno' => $data["RespSelect"][$key],
                'fecha' => $fecha,
                'eval_preg' => $data["Pregunta"],
                'consecu' => $data["ConsPreg"][$key],
            ]);
        }

        $respuesta = [
            'RegViejo' => $Opc2,
            'RegNuevo' => $respuestaRelac,
        ];

        return $respuesta;
    }

    public static function RelacResp($idPreg,$Est)
    {
        $DesVerFal = RespEvalRelacione::where('resp_pregrelacione.eval_preg', $idPreg)
            ->where('resp_pregrelacione.alumno', $Est)
            ->get();
        return $DesVerFal;
    }

    

    public static function RelacRespEtt($idPreg,$Est)
    {
        $DesVerFal = RespEvalRelacione::leftjoin("eval_relacione_opc","eval_relacione_opc.id","resp_pregrelacione.respuesta_alumno")
        ->where('resp_pregrelacione.eval_preg', $idPreg)
            ->where('resp_pregrelacione.alumno', $Est)
            ->select('eval_relacione_opc.respuesta')
            ->get();
        return $DesVerFal;
    }

    
    public static function RelacRespEst($idPreg,$Est)
    {
        $DesVerFal = RespEvalRelacione::where('resp_pregrelacione.eval_preg', $idPreg)
            ->where('resp_pregrelacione.alumno', $Est)
            ->get();
        return $DesVerFal;
    }

    public static function VaciarRegistros(){
        $Respuesta = RespEvalRelacione::truncate();
        return $Respuesta;
     }

}
