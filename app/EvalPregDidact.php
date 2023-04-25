<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EvalPregDidact extends Model {

    protected $table = 'eval_pregdidactica';
    protected $fillable = [
        'evaluacion',
        'cont_didactico'
    ];

    public static function Guardar($datos, $eval) {

        return EvalPregDidact::create([
                    'evaluacion' => $eval,
                    'cont_didactico' => $datos['archivo']
        ]);
    }

    public static function Modificar($datos, $id) {

        $respuesta = EvalPregDidact::updateOrCreate([
                    'evaluacion' => $id], [
                    'cont_didactico' => $datos['archivo']
        ]);
        return $respuesta;
    }

    public static function PregDida($id) {
        $DesEval = EvalPregDidact::where('evaluacion', $id)
                ->first();
        return $DesEval;
    }

    
    public static function EliminarVideo($id) {
        $Archi = EvalPregDidact::where('evaluacion', $id);
        $Archi->delete();
        return $Archi;
    }


}
