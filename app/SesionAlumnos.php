<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class SesionAlumnos extends Model
{
    protected $table = 'me_sesiones_alumnos';
    protected $fillable = [
        'sesion',
        'alumno',
        'estado',
    ];

    public static function Guardar($datos)
    {
        return SesionAlumnos::create([
            'sesion' => $datos['idSes'],
            'alumno' => Auth::user()->id,
            'estado' => "INICIADA",
        ]);

    }

    public static function Editar($datos)
    {
        $respuesta = SesionAlumnos::where(['sesion' => $datos['idSes']])->update([
            'estado' => "FINALIZADA",
        ]);
        return $respuesta;

    }

    public static function Consultar($datos)
    {
        $respuesta = SesionAlumnos::where("sesion", $datos['idSes'])
            ->where("alumno", Auth::user()->id);
        return $respuesta;
    }

}
