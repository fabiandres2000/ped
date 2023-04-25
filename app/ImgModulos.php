<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use Session;

class ImgModulos extends Model {

    protected $table = 'img_modulos';
    protected $fillable = [
        'modulo_img',
        'url_img'
    ];

    public static function imgmodulo() {
        $ImgModulo = ImgModulos::orderBy('modulo_img', 'ASC')
                ->get();
        return $ImgModulo;
    }
    public static function ListImg($id) {
        $ImgModulos = ImgModulos::where('modulo_img', $id)
                ->get();
        return $ImgModulos;
    }

    public static function Guardar($data) {

        foreach ($data["img"] as $key => $val) {
            $respuesta = ImgModulos::create([
                        'modulo_img' => $data["modulo_id"],
                        'url_img' => $data["img"][$key]
            ]);
        }
       return $respuesta; 
    }
    
       public static function EliminarImg($id)
    {
        $Archi=ImgModulos::find($id);
        $Archi->delete();
        return $Archi;
    }

}
