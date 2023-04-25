<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompAreaSession extends Model
{
    protected $table = 'comp_sesio_area';
    protected $fillable = [
        'id_sexarea',
        'competencia',
        'componente',
        'porcpreg',
        'sesion'
    ];

    public static function Guardar($datos, $id)
    {
        foreach ($datos["txtcomp"] as $key => $val) {

            $parCxC = explode('-', $datos["txtcomp"][$key]);
            $respu = CompAreaSession::create([
                'id_sexarea' => $id,
                'competencia' => $parCxC[0],
                'componente' => $parCxC[1],
                'porcpreg' => $datos["txtporc"][$key],
                'sesion' => $datos['IdSesion']
            ]);
        }

        return $respu;

    }

    public static function Modificar($datos, $id)
    {

        $Preg = CompAreaSession::where('id_sexarea', $id);
        $Preg->delete();

        foreach ($datos["txtcomp"] as $key => $val) {
            $parCxC = explode('-', $datos["txtcomp"][$key]);
            $respu = CompAreaSession::create([
                'id_sexarea' => $id,
                'competencia' => $parCxC[0],
                'componente' => $parCxC[1],
                'porcpreg' => $datos["txtporc"][$key],
            ]);
        }

        return $respu;

    }

    public static function ConsultarInf($id)
    {
        $Areas = CompAreaSession::join("competencias","competencias.id","comp_sesio_area.competencia")
        ->join("componentes","componentes.id","comp_sesio_area.componente")
        ->select("comp_sesio_area.*","competencias.nombre AS nomcomp","componentes.nombre AS nomcompo")
        ->where('id_sexarea', $id)
        ->get();
        return $Areas;
    }

    public static function Eliminar($Id){
        
        $Are = CompAreaSession::where('id_sexarea', $Id);
        $Are->delete();
        return "1";

    }

    
    public static function EliminarSesion($Id){
        
        $Are = CompAreaSession::where('sesion', $Id);
        $Are->delete();
        return "1";

    }

    


}
