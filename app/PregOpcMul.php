<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PregOpcMul extends Model
{

    protected $table = 'preg_mult_eval';
    protected $fillable = [
        'evaluacion',
        'pregunta',
        'puntuacion',
    ];

    public static function Guardar($preg, $punt, $evalu)
    {

        return PregOpcMul::create([
            'evaluacion' => $evalu,
            'pregunta' => $preg,
            'puntuacion' => $punt,
        ]);
    }

    public static function ModiPreMul($preg, $punt, $idpreg, $eva)
    {
        $respuesta = PregOpcMul::where('id', $idpreg)->update([
            'evaluacion' => $eva,
            'pregunta' => $preg,
            'puntuacion' => $punt,
        ]);
        return $respuesta;
    }

    public static function GrupPreg($id)
    {

        $GrupPreg = PregOpcMul::where('evaluacion', $id)
            ->get();
        return $GrupPreg;
    }

    public static function ConsulPreg($id)
    {
        $GrupPreg = PregOpcMul::where('id', $id)
            ->first();
        return $GrupPreg;
    }

    public static function ConsulPregAll($id)
    {
        $GrupPreg = PregOpcMul::where('evaluacion', $id)
            ->get();
        return $GrupPreg;
    }

    public static function DelPregunta($IdPreg)
    {
        $Opc = PregOpcMul::where('id', $IdPreg)
            ->delete();
    }

}
