<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use Session;

class DesarrollTema extends Model {

    protected $table = 'cont_documento';
    protected $fillable = [
        'contenido',
        'titulo',
        'cont_documento',
        'hab_conversacion',
        'zona_libre'
    ];

    public static function Destemas($id, $ZL) {

        $DesTemas = DesarrollTema::where('contenido', $id)
                ->select('titulo','cont_documento','hab_conversacion')
                ->where('zona_libre', $ZL)
                ->first();
        return $DesTemas;
    }

    public static function GuardarContTema($data) {

        return DesarrollTema::create([
                    'contenido' => $data['tema_id'],
                    'titulo' => $data['titu_contenido'],
                    'cont_documento' => $data['summernoteCont'],
                    'hab_conversacion' => $data['HabConv'],
                    'zona_libre' => $data['ZL'],
        ]);
    }

    public static function modificar($datos, $id) {
        
        $respuesta = DesarrollTema::where(['contenido' => $id, 'zona_libre' => $datos['ZL']])->update([
            'titulo' => $datos['titu_contenido'],
            'cont_documento' => $datos['summernoteCont'],
            'hab_conversacion' => $datos['HabConv']
        ]);
        return $respuesta;
    }

    public static function BuscarTema($id, $ZL) {
        $InfTema = DesarrollTema::where('contenido', $id)
                ->where('zona_libre', $ZL)
                ->first();
        return $InfTema;
    }

}
