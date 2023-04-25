<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContDidacticoME extends Model
{
    protected $table = 'cont_didactico_modulos_e';
    protected $fillable = [
        'contenido',
        'titulo',
        'cont_didactico',
    ];

    public static function GuardarContDidctico($data)
    {
        foreach ($data["archi"] as $key => $val) {
            $Animaciones = ContDidactico::create([
                'contenido' => $data['tema_id'],
                'titulo' => $data['TituAnim'][$key],
                'cont_didactico' => $data['archi'][$key],
            ]);
        }
        return $Animaciones;
    }

    public static function ConsultarContDidctico($id)
    {

        $videoos = ContDidactico::where('contenido', $id)
            ->get();

        return $videoos;

    }
    

    public static function ElimninarVideo($id)
    {
        $Archi = ContDidactico::find($id);
        $Archi->delete();
        return $Archi;
    }

}
