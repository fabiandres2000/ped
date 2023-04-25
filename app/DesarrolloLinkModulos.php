<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DesarrolloLinkModulos extends Model
{
    protected $table = 'cont_link_modulos';
    protected $fillable = [
        'contenido',
        'titulo',
        'url',
        'hab_conversacion',
        'zona_libre',
    ];

    public static function DesLink($id, $ZL)
    {
        $DesLink = DesarrolloLinkModulos::where('contenido', $id)
            ->where('zona_libre', $ZL)
            ->get();
        return $DesLink;
    }

    public static function Guardar($data)
    {

        foreach ($data["txturl"] as $key => $val) {
            $respuesta = DesarrolloLinkModulos::create([
                'contenido' => $data["tema_id"],
                'titulo' => $data['titu_contenido'],
                'url' => $data["txturl"][$key],
                'hab_conversacion' => $data['HabConv'],
                'zona_libre' => $data['ZL'],
            ]);
        }
        return $respuesta;
    }

    public static function GuardarZN($data)
    {
        $respuesta = "";
        foreach ($data["linkVideo"] as $key => $val) {
            if ($data["linkVideo"][$key] != "") {
                $respuesta = DesarrolloLinkModulos::create([
                    'contenido' => $data["tema_id"],
                    'titulo' => $data['titu_contenido'],
                    'url' => $data["linkVideo"][$key],
                    'hab_conversacion' => $data['HabConv'],
                    'zona_libre' => $data['ZL'],
                ]);
            }
        }
        return $respuesta;
    }

    public static function Modificar($data, $id)
    {

        $Archi = DesarrolloLinkModulos::where('contenido', $id)
            ->where('zona_libre', $data['ZL']);
        $Archi->delete();

        foreach ($data["txturl"] as $key => $val) {
            $respuesta = DesarrolloLinkModulos::create([
                'contenido' => $id,
                'titulo' => $data['titu_contenido'],
                'url' => $data["txturl"][$key],
                'hab_conversacion' => $data['HabConv'],
                'zona_libre' => $data['ZL'],
            ]);
        }
        return $respuesta;
    }
}
