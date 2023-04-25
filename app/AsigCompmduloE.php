<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AsigCompmduloE extends Model
{
    protected $table = 'me_asig_comp';
    protected $fillable = [
        'asignatura',
        'competencia',
    ];

    public static function Guardar($data, $asig)
    {

         foreach ($data["txtComp"] as $key => $val) {
            $respuesta = AsigCompmduloE::create([
                'asignatura' => $asig,
                'competencia' => $data["txtComp"][$key],
            ]);
        }

        return $respuesta;
    }

    public static function Listarxasig($Asig){
        $respuesta = AsigCompmduloE::leftJoin('competencias', 'competencias.id', '=', 'me_asig_comp.competencia')
        ->where('me_asig_comp.asignatura', $Asig)
        ->select("competencias.id", "competencias.nombre")
        ->get();
        return $respuesta;
    }

    public static function modificar($data,$id){
        $Opc = AsigCompmduloE::where('asignatura', $id);
        $Opc->delete();

        foreach ($data["txtComp"] as $key => $val) {
            $respuesta = AsigCompmduloE::create([
                'asignatura' => $id,
                'competencia' => $data["txtComp"][$key],
            ]);
        }

        return $respuesta;
    }

}
