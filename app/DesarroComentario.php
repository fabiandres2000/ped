<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class DesarroComentario extends Model {

    protected $table = 'cont_comentarios';
    protected $fillable = [
        'contenido',
        'titulo',
        'cont_comentario',
        'grado',
        'grupo',
        'jornada',
        'fecha',
        'docente'
    ];

    public static function Guardar($data) {

        return DesarroComentario::create([
                    'contenido' => $data['tema_id'],
                    'titulo' => $data['titu_contenido'],
                    'cont_comentario' => $data['summernoteComent'],
                    'grado' => $data['grado'],
                    'grupo' => $data['grupo'],
                    'jornada' => $data['jornada'],
                    'fecha' => $data['fecha'],
                    'docente' => Auth::user()->id
        ]);
    }

    public static function modificar($datos, $id) {
        $respuesta = DesarroComentario::where(['contenido' => $id])->update([
            'titulo' => $datos['titu_contenido'],
            'cont_comentario' => $datos['summernoteComent'],
            'grado' => $datos['grado'],
            'grupo' => $datos['grupo'],
            'jornada' => $datos['jornada'],
            'fecha' => $datos['fecha'],
            'docente' => Auth::user()->id
        ]);
        return $respuesta;
    }

    public static function LisComentarioEst($grado, $grupo, $jornada) {
        $fecha = date('Y-m-d');
        $Comnet = DesarroComentario::where('fecha', $fecha)
                ->where('grado', $grado)
                ->where('grupo', $grupo)
                ->where('jornada', $jornada)
                ->get();
       
        return $Comnet;
    }

    public static function LisComentario() {
        $Comnet = DesarroComentario::get();
        return $Comnet;
    }

    public static function BuscarTema($id) {
        $InfTema = DesarroComentario::where('contenido', $id)
                ->first();
        return $InfTema;
    }

}
