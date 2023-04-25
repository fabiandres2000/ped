<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CosEval extends Model
{
    protected $table = 'evalpreg';
    protected $fillable = [
        'ideval',
        'idpreg',
        'conse',
        'tipo',
    ];

    public static function Guardar($datos, $eval, $preg)
    {

        return CosEval::create([
            'ideval' => $eval,
            'idpreg' => $preg,
            'conse' => $datos['PregConse'],
            'tipo' => $datos['Tipreguntas'],
        ]);

    }

    public static function DelPreg($id)
    {
        $Opc = CosEval::where('idpreg', $id);
        $Opc->delete();
    }

    public static function GrupPreg($id)
    {
        $GrupPreg = CosEval::where('ideval', $id)
            ->get();
        return $GrupPreg;
    }

    public static function ConsulPreg($Preg,$Eval){
        $Pregunta = CosEval::leftJoin("retroalimentacion","retroalimentacion.pregunta","evalpreg.idpreg")
        ->where('ideval', $Eval)
        ->where('idpreg',$Preg)
        ->first();
    return $Pregunta;

    }

}
