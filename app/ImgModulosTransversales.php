<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ImgModulosTransversales extends Model
{
   
    protected $table = 'img_modtransversales';
    protected $fillable = [
        'asig_img',
        'url_img'
    ];
    
    
       public static function Guardar($data) {

        foreach ($data["img"] as $key => $val) {
            $respuesta = ImgModulosTransversales::create([
                        'asig_img' => $data["asig_id"],
                        'url_img' => $data["img"][$key]
            ]);
        }
       return $respuesta; 
    }
    
    
       public static function ListImg($id) {
        $ImgModulos = ImgModulosTransversales::where('asig_img', $id)
                ->get();
        return $ImgModulos;
    }

    
    public static function imgAsig() {
        $ImgAig = ImgModulosTransversales::get();
        return $ImgAig;
    }
    
           public static function EliminarImg($id)
    {
        $Archi=ImgModulosTransversales::find($id);
        $Archi->delete();
        return $Archi;
    }
}
