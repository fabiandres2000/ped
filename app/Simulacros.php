<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Simulacros extends Model
{

    protected $table = 'simulacros';
    protected $fillable = [
        'nombre',
        'prueba',
        'fecha',
        'estado',
    ];

    public static function Gestion($busqueda, $pagina, $limit)
    {

        if ($pagina == "1") {
            $offset = 0;
        } else {
            $pagina--;
            $offset = $pagina * $limit;
        }

        if (!empty($busqueda)) {
            $respuesta = Simulacros::where('estado', 'ACTIVO')
                ->where(function ($query) use ($busqueda) {
                    $query->where('nombre', 'LIKE', '%' . $busqueda . '%');
                })
                ->where('estado', 'ACTIVO')
                ->orderBy('nombre', 'ASC')
                ->limit($limit)->offset($offset);
        } else {
            $respuesta = Simulacros::where('estado', 'ACTIVO')
                ->orderBy('nombre', 'ASC')
                ->limit($limit)->offset($offset);
        }

        return $respuesta->get();

    }

    public static function numero_de_registros($busqueda)
    {
        if (!empty($busqueda)) {
            $respuesta = Simulacros::where('estado', 'ACTIVO')
                ->where(function ($query) use ($busqueda) {
                    $query->where('nombre', 'LIKE', '%' . $busqueda . '%');
                })
                ->where('estado', 'ACTIVO')
                ->orderBy('nombre', 'ASC');
        } else {
            $respuesta = Simulacros::where('estado', 'ACTIVO')
                ->orderBy('nombre', 'ASC');
        }
        return $respuesta->count();
    }

    
    public static function Guardar($datos)
    {
        return Simulacros::create([
            'nombre' => $datos['nombre'],
            'prueba' => $datos['prueba'],
            'fecha' => $datos['fecha'],
            'estado' => 'ACTIVO',
        ]);
    }

    public static function modificar($datos){
        $respuesta = Simulacros::where(['id' => $datos['Id_Simu']])->update([
            'nombre' => $datos['nombre'],
            'prueba' => $datos['prueba'],
            'fecha' => $datos['fecha']
        ]);
        return $respuesta;
    }

    
    public static function BuscarSimu($id)
    {
        return Simulacros::findOrFail($id);
    }

    public static function BuscarSimuxEstu($id)
    {
        return Simulacros::leftJoin("simulacro_estudiante","simulacro_estudiante.simulacro","simulacros.id")
        ->select("simulacros.*","simulacro_estudiante.estado")
        ->first();
    }

    public static function editarestado($id, $estado)
    {
        $Respuesta = Simulacros::where('id', $id)->update([
            'estado' => $estado,
        ]);
        return $Respuesta;
    }


    public static function CargarSimulacros(){
        $fecha = date('Y-m-d');
        $respuesta = Simulacros::where('fecha', $fecha)
        ->get();
        return $respuesta;


    }
}
