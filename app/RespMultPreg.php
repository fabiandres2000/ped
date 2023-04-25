<?php

namespace App;

use Auth;
use Illuminate\Database\Eloquent\Model;

class RespMultPreg extends Model
{

    protected $table = 'resp_pregmultiple';
    protected $fillable = [
        'alumno',
        'evaluacion',
        'pregunta',
        'respuesta',
        'fecha',
    ];

    public static function Guardar($data, $fecha)
    {
        $Opc = RespMultPreg::where('pregunta', $data["Pregunta"])
            ->where('alumno', Auth::user()->id)
            ->first();
        if ($Opc) {
            $Opc->delete();
        }

        foreach ($data["Opciones"] as $key => $val) {
            if ($data["OpcionSel"][$key] == "si") {

                $grupPre = RespMultPreg::create([
                    'alumno' => Auth::user()->id,
                    'evaluacion' => $data["IdEvaluacion"],
                    'pregunta' => $data["PreguntaOpc"],
                    'respuesta' => $data["Opciones"][$key],
                    'fecha' => $fecha,
                ]);
            }
        }
        $respuesta =[
            'RegViejo' => $Opc,
            'RegNuevo' =>$grupPre
        ];

        return $respuesta ;
    }

    public static function VaciarRegistros(){
        $Respuesta = RespMultPreg::truncate();
        return $Respuesta;
     }

}
