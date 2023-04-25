<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AsigComponentemduloE extends Model
{
    protected $table = 'me_asig_compo';
    protected $fillable = [
        'asignatura',
        'componente',
    ];

    public static function Guardar($data, $asig)
    {
        foreach ($data["txtComponentes"] as $key => $val) {
            $respuesta = AsigComponentemduloE::create([
                'asignatura' => $asig,
                'componente' => $data["txtComponentes"][$key],
            ]);
        }

        return $respuesta;
    }

     
    public static function Listarxasig($Asig){
        $respuesta = AsigComponentemduloE::leftJoin('componentes', 'componentes.id', '=', 'me_asig_compo.componente')
        ->where('me_asig_compo.asignatura', $Asig)
        ->select("componentes.id", "componentes.nombre")
        ->get();
        return $respuesta;
    }

    public static function modificar($data,$id){
        
        $Opc = AsigComponentemduloE::where('asignatura', $id);
        $Opc->delete();

        foreach ($data["txtComponentes"] as $key => $val) {
            $respuesta = AsigComponentemduloE::create([
                'asignatura' => $id,
                'componente' => $data["txtComponentes"][$key],
            ]);
        }

        return $respuesta;
    }
}
