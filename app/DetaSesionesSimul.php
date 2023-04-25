<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetaSesionesSimul extends Model
{

    protected $table = 'me_sesiones_simulacros';
    protected $fillable = [
        'id_simulacro',
        'sesion',
        'num_preguntas',
        'tiempo_sesion',
        'estado',
    ];

    public static function Guardar($datos)
    {
        return DetaSesionesSimul::create([
            'id_simulacro' => $datos['Id_Simu'],
            'sesion' => $datos['DescSesion'],
            'num_preguntas' => "",
            'tiempo_sesion' => $datos['TSesion'],
            'estado' => "ACTIVO",
        ]);
    }
    public static function ModificaDatos($idSesion, $Des, $tiempo)
    {
        $respuesta = DetaSesionesSimul::where(['id' => $idSesion])->update([
            'sesion' => $Des,
            'tiempo_sesion' => $tiempo,
        ]);

        return $respuesta;
    }

    public static function ConsultarSesiones($simu)
    {
        $DetSesiones = DetaSesionesSimul::leftJoin('me_sesiones_alumnos', 'me_sesiones_alumnos.sesion', '=', 'me_sesiones_simulacros.id')
            ->where('me_sesiones_simulacros.id_simulacro', $simu)
            ->where('me_sesiones_simulacros.estado', "ACTIVO")
            ->select('me_sesiones_simulacros.id', 'me_sesiones_simulacros.id_simulacro', 'me_sesiones_simulacros.sesion', 'me_sesiones_simulacros.num_preguntas', 'me_sesiones_simulacros.tiempo_sesion', 'me_sesiones_alumnos.estado')
            ->get();
        return $DetSesiones;
    }

    public static function ConsultarSesion($sesion)
    {
        $DetSesion = DetaSesionesSimul::leftJoin('me_sesiones_alumnos', 'me_sesiones_alumnos.sesion', '=', 'me_sesiones_simulacros.id')
            ->where('me_sesiones_simulacros.id', $sesion)
            ->select('me_sesiones_simulacros.id', 'me_sesiones_simulacros.id_simulacro', 'me_sesiones_simulacros.sesion', 'me_sesiones_simulacros.num_preguntas', 'me_sesiones_simulacros.tiempo_sesion', 'me_sesiones_alumnos.estado')
            ->first();
        return $DetSesion;

    }

    public static function Modificar($Sesion, $npreg)
    {
        $respuesta = DetaSesionesSimul::where(['id' => $Sesion])->update([
            'num_preguntas' => $npreg,
        ]);
        return $respuesta;
    }

    public static function Eliminar($Id)
    {

        $Are = DetaSesionesSimul::where('id', $Id);
        $Are->delete();
        return "1";
    }

}
