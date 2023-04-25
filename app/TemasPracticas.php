<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TemasPracticas extends Model
{

    protected $table = 'temas_practicas';
    protected $fillable = [
        'tema',
        'pregunta',
        'estado',
    ];

    public static function Gestion($id)
    {
        $respuesta = TemasPracticas::join('banco_preg_me', 'banco_preg_me.id', 'temas_practicas.pregunta')
            ->where('temas_practicas.tema', $id)
            ->where('temas_practicas.estado','ACTIVO')
            ->select('banco_preg_me.*')
            ->groupBy('banco_preg_me.id');
            
        return $respuesta->get();
    }

    public static function Guardar($datos,$Preg)
    {
        return TemasPracticas::create([
            'tema' => $datos['tema'],
            'pregunta' => $Preg,
            'estado' => 'INACTIVO',
        ]);
    }

    public static function ModifEval($datos, $Preg)
    {
        $respuesta = TemasPracticas::where(['pregunta' => $Preg])->update([
            'tema' => $datos['tema'],
            'pregunta' => $Preg,
            'estado' => 'INACTIVO',
        ]);

        return $respuesta;
    }

    public static function ModifEvalFin($datos, $Preg)
    {
        $respuesta = TemasPracticas::where(['pregunta' => $Preg])->update([
            'tema' => $datos['tema'],
            'pregunta' => $Preg,
            'estado' => 'ACTIVO',
        ]);

        return $respuesta;
    }

    public static function DelPregunta($id){
        $respuesta = TemasPracticas::where(['pregunta' => $id])->update([
            'estado' => 'ELIMINADO',
        ]);

        return $respuesta;
    }

    public static function BuscarPracticas($tema){

        $respuesta =TemasPracticas::where("tema",$tema)
        ->where("estado",'ACTIVO')
        ->get();

        return $respuesta;

    }

}
