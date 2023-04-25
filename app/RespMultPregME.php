<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class RespMultPregME extends Model
{
    protected $table = 'resp_pregmultiple_me';
    protected $fillable = [
        'alumno',
        'tema',
        'pregunta',
        'respuesta',
        'fecha',
    ];

    public static function Guardar($data, $fecha)
    {
        $Opc = RespMultPregME::where('pregunta', $data["Pregunta"])
            ->where('alumno', Auth::user()->id)
            ->first();
        if ($Opc) {
            $Opc->delete();
        }

        foreach ($data["Opciones"] as $key => $val) {
            if ($data["OpcionSel"][$key] == "si") {

                $grupPre = RespMultPregME::create([
                    'alumno' => Auth::user()->id,
                    'tema' => $data["Tema"],
                    'pregunta' => $data["PreguntaOpc"],
                    'respuesta' => $data["Opciones"][$key],
                    'fecha' => $fecha
                ]);
            }
        }
        $respuesta =[
            'RegViejo' => $Opc,
            'RegNuevo' =>$grupPre
        ];

        return $respuesta ;
    }

    public static function BuscResulPreg($tem){
        $RespPreg = RespMultPregME::leftjoin('preguntas_me', 'preguntas_me.id', 'resp_pregmultiple_me.pregunta')
        ->leftjoin('opc_mult_eval_me', 'opc_mult_eval_me.id', 'resp_pregmultiple_me.respuesta')
        ->select('preguntas_me.pregunta','opc_mult_eval_me.correcta')
        ->where('resp_pregmultiple_me.tema', $tem)
        ->where('resp_pregmultiple_me.alumno', Auth::user()->id)
        ->get();

        return $RespPreg;
    }
}
