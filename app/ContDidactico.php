<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContDidactico extends Model
{

    protected $table = 'cont_didactico';
    protected $fillable = [
        'contenido',
        'titulo',
        'cont_didactico',
        'zona_libre',
    ];

    public static function GuardarContDidctico($data)
    {
        foreach ($data["archi"] as $key => $val) {
            $Animaciones = ContDidactico::create([
                'contenido' => $data['tema_id'],
                'titulo' => $data['TituAnim'][$key],
                'cont_didactico' => $data['archi'][$key],
                'zona_libre' => $data['ZL'],
            ]);
        }
        return $Animaciones;
    }

    public static function modificar($datos, $id)
    {
        foreach ($datos["archi"] as $key => $val) {
            $Animaciones = ContDidactico::create([
                'contenido' => $id,
                'titulo' => $datos['TituAnim'][$key],
                'cont_didactico' => $datos['archi'][$key],
                'zona_libre' => $datos['ZL'],
            ]);
        }
        return $Animaciones;
    }

    public static function BuscarTema($id, $ZL)
    {
        $InfTema = ContDidactico::where('contenido', $id)
            ->where('zona_libre', $ZL)
            ->get();
        return $InfTema;
    }

    public static function EliminarCont($id)
    {
        $Archi = ContDidactico::find($id);
        $Archi->delete();
        return $Archi;
    }

}
