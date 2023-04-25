<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class RespGrupPreg extends Model {

    protected $table = 'resp_grupregunta';
    protected $fillable = [
        'evaluacion',
        'alumno',
        'pregunta',
        'respuesta',
        'fecha',
        'punt_obtenida'
    ];

    public static function Guardar($data, $fecha) {

        $Opc = RespGrupPreg::where('evaluacion', $data["IdEvaluacion"])
                ->where('alumno', Auth::user()->id);
        $Opc->delete();


        foreach ($data["idPre"] as $key => $val) {
            $grupPre = RespGrupPreg::create([
                        'evaluacion' => $data["IdEvaluacion"],
                        'alumno' => Auth::user()->id,
                        'pregunta' => $data["idPre"][$key],
                        'respuesta' => $data["Resp"][$key],
                        'fecha' => $fecha,
                        'punt_obtenida' => '0'
            ]);
        }
        return $grupPre;
    }
    public static function RegistCalf($data) {

        foreach ($data["idPre"] as $key => $val) {
            
             $respuesta = RespGrupPreg::where('id', $data["idPre"][$key])
                    ->first();
            $respuesta->punt_obtenida = $data["PuntObt"][$key];
            $respuesta->save();
            
        }
        return $respuesta;
    }

    public static function DesRespGrupPrg($DesEva) {

        $Alumno = $DesEva->alumno;
        $evalua = $DesEva->evaluacion;
        return RespGrupPreg::join('evaluacion', 'evaluacion.id', 'resp_grupregunta.evaluacion')
                        ->join('eval_grupregunta', 'eval_grupregunta.id', 'resp_grupregunta.pregunta')
                        ->join('alumnos', 'alumnos.usuario_alumno', 'resp_grupregunta.alumno')
                        ->select('resp_grupregunta.id', 'resp_grupregunta.evaluacion', 'resp_grupregunta.fecha', 
                                'resp_grupregunta.respuesta', 'eval_grupregunta.puntuacion', 'eval_grupregunta.pregunta',
                                'evaluacion.intentos_perm', 'evaluacion.calif_usando', 'evaluacion.punt_max',
                                'evaluacion.titulo', 'evaluacion.tip_evaluacion', 'alumnos.nombre_alumno',
                                'alumnos.apellido_alumno','resp_grupregunta.punt_obtenida')
                        ->where('resp_grupregunta.alumno', $Alumno)
                        ->where('resp_grupregunta.evaluacion', $evalua)
                        ->get();
    }

}
