<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubirArcTemaModulos extends Model
{
    
    protected $table = 'cont_archivos_modulos';
    protected $fillable = [
        'contenido',
        'titulo',
        'nom_arch',
        'url_arch',
        'hab_conversacion',
        'zona_libre'
    ];

    public static function GuardarArchCont($datos) {

        $filename = "";
       
        foreach ($datos['archi'] as $key => $val) {
            $archivos= SubirArcTemaModulos::create([
                        'contenido' => $datos['tema_id'],
                        'titulo' => $datos['titu_contenido'],
                        'nom_arch' => $datos["archi"][$key],
                        'hab_conversacion' => $datos['HabConv'],
                        'zona_libre' => $datos['ZL']
            ]);
            
        }
        return $archivos;

    }

    public static function DesArch($id,$ZL) {
        $DesArc = SubirArcTemaModulos::where('contenido', $id)
                ->where('zona_libre', $ZL)
                ->first();
        return $DesArc;
    }

    public static function BuscarArchi($id) {
        $InfTema = SubirArcTemaModulos::where('contenido', $id)
                ->get();
        return $InfTema;
    }

    public static function EliminarArch($id) {
        $Archi = SubirArcTemaModulos::find($id);
        $Archi->delete();
        return $Archi;
    }
}
