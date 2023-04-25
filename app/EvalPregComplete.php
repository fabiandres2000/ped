<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EvalPregComplete extends Model {

    protected $table = 'eval_complete';
    protected $fillable = [
        'id',
        'evaluacion',
        'opciones',
        'parrafo',
        'puntaje'
    ];

    public static function Guardar($datos, $eval) {

        return EvalPregComplete::create([
                    'evaluacion' => $eval,
                    'opciones' => $datos['cb_Opciones'],
                    'parrafo' => $datos['summernoteCompPar'],
                    'puntaje' => $datos['puntaje']
        ]);
    }

    public static function Modificar($datos) {
        $respuesta = EvalPregComplete::where('evaluacion', $datos["Id_Eval"])->update([
            'opciones' => $datos['cb_Opciones'],
            'parrafo' => $datos['summernoteCompPar']
        ]);
        return $respuesta;
    }

    public static function PregComplete($id) {
        $DesEval = EvalPregComplete::where('evaluacion', $id)
                ->first();
        return $DesEval;
    }

    public static function ConsultComplete($id) {
        $DesEval = EvalPregComplete::where('id', $id)
                ->first();
        return $DesEval;
    }
    
    public static function ConsultCompleteAll($id) {
        $DesEval = EvalPregComplete::where('evaluacion', $id)
                ->GET();
        return $DesEval;
    }


    public static function DelPreg($id){
        $Opc = EvalPregComplete::where('id', $id);
        $Opc->delete();
    }

}
