<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Parte1 extends Model
{
    protected $table = 'me_parte1';
    protected $fillable = [
        'banco',
        'competencia',
        'componente',
        'palabras',
    ];

    public static function Guardar($datos, $banco)
    {
        return Parte1::create([
            'banco' => $banco,
            'competencia' => $datos['competencia'],
            'componente' => $datos['componente'],
            'palabras' => implode(",", $datos['cb_Opciones']),
        ]);
    }

    public static function Consultar($idpreg)
    {
        $Preguntas = Parte1::where('id', $idpreg)
            ->first();
        return $Preguntas;
    }


    public static function DelPreg($idbanco){
        $Preg = Parte1::where('id', $idbanco);
        $Preg->delete();
    }

    
    public static function ConsultarPreg($idbanco)
    {
        $Preguntas = Parte1::where('banco', $idbanco)
            ->first();
        return $Preguntas;
    }

    public static function modificar($datos, $idbanco,$Preg)
    {

        $respuesta = Parte1::where(['id' => $Preg])->update([
            'banco' => $idbanco,
            'competencia' => $datos['competencia'],
            'componente' => $datos['componente'],
            'palabras' => implode(",", $datos['cb_Opciones'])
        ]);

        return $respuesta;

    }

}
