<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PregRelacione extends Model
{
    protected $table = 'eval_relacione';
    protected $fillable = [
        'evaluacion',
        'enunciado',
        'puntaje'
    ];

    public static function Guardar($datos, $eval)
    {
        return PregRelacione::create([
            'evaluacion' => $eval,
            'enunciado' => $datos['EnuncRelacione'],
            'puntaje' => $datos['puntaje'],
        ]);
    }

    public static function Modificar($datos)
    {
        $respuesta = PregRelacione::where('id', $datos["id-relacione"])->update([
            'enunciado' => $datos['EnuncRelacione'],
            'puntaje' => $datos['puntaje'],
        ]);
        return $respuesta;
    }

    public static function ConRela($id)
    {
        $PregRelacione = PregRelacione::where('id', $id)
            ->first();
        return $PregRelacione;
    }

    
    public static function ConRelaAll($id)
    {
        $PregRelacione = PregRelacione::where('evaluacion', $id)
            ->get();
        return $PregRelacione;
    }

    public static function DelPreg($id){
        $Opc = PregRelacione::where('id', $id);
        $Opc->delete();
    }

}
