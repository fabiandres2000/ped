<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use Session;

class ComentZona extends Model {

    protected $table = 'comet_zonalibre';
    protected $fillable = [
        'grado',
        'fecha',
        'usuario',
        'perfil',
        'comentario',
        'grupo',
        'jornada',
        'docente'
    ];

    public static function Guardar($Coment, $grado, $grupo, $jornada, $docente) {
        $fecha = date('Y-m-d');
        return ComentZona::create([
                    'grado' => $grado,
                    'fecha' => $fecha,
                    'usuario' => Auth::user()->id,
                    'perfil' => Auth::user()->tipo_usuario,
                    'comentario' => $Coment,
                    'grupo' => $grupo,
                    'jornada' => $jornada,
                    'docente' => $docente
        ]);
    }

    public static function Consultar($grado, $grupo, $jorna) {
        $fecha = date('Y-m-d');
        $Respuesta = ComentZona::join('users', 'users.id', 'comet_zonalibre.usuario')
                ->where('docente', Auth::user()->id)
                ->where('grado', $grado)
                ->where('grupo', $grupo)
                ->where('jornada', $jorna)
                ->Where('fecha', $fecha)
                ->select("users.nombre_usuario", "comet_zonalibre.comentario", "users.tipo_usuario",'comet_zonalibre.usuario')
                ->get();

        return $Respuesta;
    }

    public static function Consultar2($grado, $grupo, $jorna) {
        $fecha = date('Y-m-d');

        $Respuesta = ComentZona::join('users', 'users.id', 'comet_zonalibre.usuario')
                ->where('docente', Auth::user()->id)
                ->where('grado', $grado)
                ->where('grupo', $grupo)
                ->where('jornada', $jorna)
                ->Where('fecha', $fecha)
                ->select("users.nombre_usuario", "comet_zonalibre.comentario", "users.tipo_usuario",'comet_zonalibre.usuario')
                ->get();


        return $Respuesta;
    }

    public static function Consultar2Est($grado, $grupo, $jornada) {
        $fecha = date('Y-m-d');

        $Respuesta = ComentZona::join('users', 'users.id', 'comet_zonalibre.usuario')
                ->where('grado', $grado)
                ->Where('grupo', $grupo)
                ->Where('jornada', $jornada)
                ->Where('fecha', $fecha)
                ->select("users.nombre_usuario", "comet_zonalibre.comentario", "users.tipo_usuario",'comet_zonalibre.usuario')
                ->get();

        return $Respuesta;
    }

    public static function VaciarRegistros(){
    $Respuesta = ComentZona::truncate();
        return $Respuesta;
     }

}
