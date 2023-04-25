<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CosPregModuloE extends Model
{
    protected $table = 'pregmoduloe';
    protected $fillable = [
        'idbanco',
        'idpreg',
        'conse',
        'tipo',
    ];

    public static function Guardar($datos, $eval, $preg)
    {

        return CosPregModuloE::create([
            'idbanco' => $eval,
            'idpreg' => $preg,
            'conse' => $datos['PregConse'],
            'tipo' => '',
        ]);

    }


    public static function DelPreg($id)
    {
        $Opc = CosPregModuloE::where('idpreg', $id);
        $Opc->delete();
    }

    public static function GrupPreg($id)
    {
        $GrupPreg = CosPregModuloE::leftjoin('preguntas_me', 'preguntas_me.id', 'pregmoduloe.idpreg')
            ->leftjoin('competencias', 'competencias.id', 'preguntas_me.competencia')
            ->leftjoin('componentes', 'componentes.id', 'preguntas_me.componente')
            ->select('pregmoduloe.*', "competencias.nombre as nombre_compe", "componentes.nombre as nombre_compo")
            ->where('idbanco', $id)
            ->get();
        return $GrupPreg;
    }
}
