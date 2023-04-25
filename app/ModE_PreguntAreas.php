<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ModE_PreguntAreas extends Model
{
    protected $table = 'preg_competencia';
    protected $fillable = [
        'sesion_area',
        'pregunta',
        'sesion',
        'banco',
        'parte'
    ];

    public static function Guardar($datos, $id)
    {
        foreach ($datos["Preguntas"] as $key => $val) {

            $respu = ModE_PreguntAreas::create([
                'sesion_area' => $id,
                'pregunta' => $datos["Preguntas"][$key],
                'sesion' => $datos['IdSesionGen'],
                'banco' => $datos["PregBancoId"][$key],
                'parte' => $datos["PregTipPreg"][$key],
            ]);
        }

        return $respu;

    }

    public static function EliminarPreg($idSesArea){
        $Preg = ModE_PreguntAreas::where('sesion_area', $idSesArea);
        $Preg->delete();
    }

    public static function Modificar($datos, $id)
    {

        $Preg = ModE_PreguntAreas::where('sesion_area', $id);
        $Preg->delete();

        foreach ($datos["Preguntas"] as $key => $val) {

            $respu = ModE_PreguntAreas::create([
                'sesion_area' => $id,
                'pregunta' => $datos["Preguntas"][$key],
                'sesion' => $datos['IdSesionGen'],
                'banco' => $datos["PregBancoId"][$key],
                'parte' => $datos["PregTipPreg"][$key],
            ]);
        }

        return $respu;

    }

    public static function ConsultarInf($id)
    {
        $Pregun = ModE_PreguntAreas::join("preguntas_me", "preguntas_me.id", "preg_competencia.pregunta")
            ->join("banco_preg_me", "banco_preg_me.id", "preguntas_me.banco")
            ->where('sesion_area', $id)
            ->select("preguntas_me.*", "banco_preg_me.enunciado","banco_preg_me.tipo_pregunta")
            ->get();

        return $Pregun;
    }

    public static function ConsultarInfIngles($id)
    {
        $Pregun = ModE_PreguntAreas::join("preguntas_me", "preguntas_me.id", "preg_competencia.pregunta")
        ->join("banco_preg_me", "banco_preg_me.id", "preg_competencia.banco")
            ->where('sesion_area', $id)
            ->select("preg_competencia.*", "banco_preg_me.enunciado","banco_preg_me.tipo_pregunta",'preguntas_me.id AS idpregme')
            ->groupBy("banco_preg_me.id")
            ->get();
        return $Pregun;
    }

    public static function Eliminar($Id){
        
        $Are = ModE_PreguntAreas::where('sesion_area', $Id);
        $Are->delete();
        return "1";

    }

    public static function EliminarSesion($Id){
        
        $Are = ModE_PreguntAreas::where('sesion', $Id);
        $Are->delete();
        return "1";

    }

}
