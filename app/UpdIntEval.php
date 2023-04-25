<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class UpdIntEval extends Model {

    protected $table = 'eval_intentos';
    protected $fillable = [
        'evaluacion',
        'alumnos',
        'int_realizados'
    ];

    public static function guardar($IdEval) {
        $Resp = UpdIntEval::where('evaluacion', $IdEval)
                ->where('alumnos', Auth::user()->id)
                ->first();
     
        if ($Resp) {
              
            $respuesta = UpdIntEval::where('evaluacion', $IdEval)
                    ->where('alumnos',Auth::user()->id)
                    ->first();
            $respuesta->int_realizados = $respuesta->int_realizados + 1;
            $respuesta->save();
            
        } else {
            $respuesta = UpdIntEval::create([
                        'evaluacion' => $IdEval,
                        'alumnos' => Auth::user()->id,
                        'int_realizados' => '1',
            ]);
        }

        return $respuesta;
    }

    public static function ConsulInt($eval,$alumn){
        $Intentos = UpdIntEval::where('evaluacion', $eval)
        ->where('alumnos',$alumn)
        ->first();
    return $Intentos;

    }

     public static function VaciarRegistros(){
        $Intentos = UpdIntEval::truncate();
        return $Intentos;
     }

}
