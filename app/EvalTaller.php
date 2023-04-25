<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EvalTaller extends Model
{

    protected $table = 'eval_taller';
    protected $fillable = [
        'evaluacion',
        'nom_archivo',
        'puntaje',
    ];

    public static function GuardarTallerArc($datos, $idEval)
    {

        return EvalTaller::create([
            'evaluacion' => $idEval,
            'nom_archivo' => $datos["archivo"],
            'puntaje' => $datos["puntaje"],
        ]);
    }

    public static function PregTaller($id)
    {
        $PregTaller = EvalTaller::where('id', $id)
            ->first();
        return $PregTaller;
    }

    public static function PregTallerAll($id)
    {
        $PregTaller = EvalTaller::where('evaluacion', $id)
            ->get();
        return $PregTaller;
    }

    public static function EliminarArch($id)
    {
        $Archi = EvalTaller::find($id);
        $Archi->delete();
        return $Archi;
    }

    public static function ModifPreg($datos)
    {
        $respuesta = EvalTaller::where('id', $datos["id-taller"])->update([
            'nom_archivo' => $datos["archivo"],
            'puntaje' => $datos["puntaje"],
        ]);
        return $respuesta;
    }

}
