<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ImgAsignatura extends Model
{
     protected $table = 'img_asignaturas';
    protected $fillable = [
        'asig_img',
        'url_img'
    ];
    
    
       public static function Guardar($data) {

        foreach ($data["img"] as $key => $val) {
            $respuesta = ImgAsignatura::create([
                        'asig_img' => $data["asig_id"],
                        'url_img' => $data["img"][$key]
            ]);
        }
       return $respuesta; 
    }
    
    
       public static function ListImg($id) {
        $ImgModulos = ImgAsignatura::where('asig_img', $id)
                ->get();
        return $ImgModulos;
    }

    
    public static function imgAsig() {
        $ImgAig = ImgAsignatura::get();
        return $ImgAig;
    }
    
           public static function EliminarImg($id)
    {
        $Archi=ImgAsignatura::find($id);
        $Archi->delete();
        return $Archi;
    }
    
}
