<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use Session;

class DesarrolloLink extends Model {

    protected $table = 'cont_link';
    protected $fillable = [
        'contenido',
        'titulo',
        'url',
        'hab_conversacion',
        'zona_libre'
    ];

    public static function DesLink($id, $ZL) {
        $DesLink = DesarrolloLink::where('contenido', $id)
                ->where('zona_libre', $ZL)
                ->get();
        return $DesLink;
    }

    public static function Guardar($data) {
       
        foreach ($data["txturl"] as $key => $val) {
            $respuesta = DesarrolloLink::create([
                        'contenido' => $data["tema_id"],
                        'titulo' => $data['titu_contenido'],
                        'url' => $data["txturl"][$key],
                        'hab_conversacion' => $data['HabConv'],
                        'zona_libre' => $data['ZL']
            ]);
        }
        return $respuesta;
    }

    public static function GuardarZN($data) {
        $respuesta = "";
        foreach ($data["linkVideo"] as $key => $val) {
            if ($data["linkVideo"][$key] != "") {
                $respuesta = DesarrolloLink::create([
                            'contenido' => $data["tema_id"],
                            'titulo' => $data['titu_contenido'],
                            'url' => $data["linkVideo"][$key],
                            'hab_conversacion' => $data['HabConv'],
                            'zona_libre' => $data['ZL']
                ]);
            }
        }
        return $respuesta;
    }

    public static function Modificar($data, $id) {

        $Archi = DesarrolloLink::where('contenido', $id)
                ->where('zona_libre', $data['ZL']);
        $Archi->delete();

        foreach ($data["txturl"] as $key => $val) {
            $respuesta = DesarrolloLink::create([
                        'contenido' => $id,
                        'titulo' => $data['titu_contenido'],
                        'url' => $data["txturl"][$key],
                        'hab_conversacion' => $data['HabConv'],
                        'zona_libre' => $data['ZL']
            ]);
        }
        return $respuesta;
    }

}
