<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TemasModuloE_Img extends Model
{
    protected $table = 'imagenes_moduloe';
    protected $fillable = [
        'tema',
        'imagen'
    ];

    public static function Guardar($data)
    {
        foreach ($data["ImgTema"] as $key => $val) {
           $ImgTemas = TemasModuloE_Img::create([
                'tema' => $data['tema_id'],
                'imagen' => $data['ImgTema'][$key]
            ]);

        }
        return $ImgTemas;
    }

    public static function BuscarTema($id) {
        $InfTema = TemasModuloE_Img::where('tema', $id)
                ->get();
        return $InfTema;
    }

    public static function EliminarImg($id){

        $Archi=TemasModuloE_Img::find($id);
        $Archi->delete();
        return $Archi;

    }

    public static function ElimnarRegistros($id){

        $Archi=TemasModuloE_Img::where("tema",$id);
        $Archi->delete();
        return $Archi;

    }

    
}
