<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class  ImgGradosModulosTransv extends Model
{

    protected $table = 'img_grados_modulos';
    protected $fillable = [
        'modulo_img',
        'url_img',
    ];

    public static function imgmodulo()
    {
        $ImgModulo = ImgGradosModulosTransv::orderBy('modulo_img', 'ASC')
            ->get();
        return $ImgModulo;
    }
    public static function ListImg($id)
    {
        $ImgModulos = ImgGradosModulosTransv::where('modulo_img', $id)
            ->get();
        return $ImgModulos;
    }

    public static function Guardar($data)
    {

        foreach ($data["img"] as $key => $val) {
            $respuesta = ImgGradosModulosTransv::create([
                'modulo_img' => $data["modulo_id"],
                'url_img' => $data["img"][$key],
            ]);
        }
        return $respuesta;
    }

    public static function EliminarImg($id)
    {
        $Archi = ImgGradosModulosTransv::find($id);
        $Archi->delete();
        return $Archi;
    }
}
