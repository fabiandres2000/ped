<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EvalPregEnsay extends Model
{

    protected $table = 'eval_pregensayo';
    protected $fillable = [
        'id',
        'evaluacion',
        'pregunta',
        'puntaje'
    ];

    public static function Guardar($datos, $eval)
    {

        return EvalPregEnsay::create([
            'evaluacion' => $eval,
            'pregunta' => $datos['summernote_pregensay'],
            'puntaje' => $datos['puntaje']
        ]);
    }

    public static function ModifPreg($datos)
    {
        $respuesta = EvalPregEnsay::where('id', $datos['id-pregensay'])->update([
            'pregunta' => $datos['summernote_pregensay'],
            'puntaje' => $datos['puntaje']
        ]);
        return $respuesta;
    }

    public static function PregEnsay($id)
    {
        $DesEval = EvalPregEnsay::where('evaluacion', $id)
            ->first();
        return $DesEval;

    }

    public static function consulPregEnsay($id)
    {
        
        $DesEval = EvalPregEnsay::where('id', $id)
            ->first();
        return $DesEval;

    }

    public static function consulPregEnsayAll($id)
    {
        
        $DesEval = EvalPregEnsay::where('evaluacion', $id)
            ->get();
        return $DesEval;

    }

    public static function DelPreg($id){
        $Opc = EvalPregEnsay::where('id', $id);
        $Opc->delete();
    }

}
