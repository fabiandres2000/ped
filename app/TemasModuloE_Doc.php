<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TemasModuloE_Doc extends Model
{
    protected $table = 'documento_moduloe';
    protected $fillable = [
        'tema',
        'contenido'
    ];

    public static function Guardar($data)
    {
        return TemasModuloE_Doc::create([
            'tema' => $data['tema_id'],
            'contenido' => $data['summernoteCont']
        ]);
    }

    public static function BuscarTema($id) {
        $InfTema = TemasModuloE_Doc::where('tema', $id)
                ->first();
        return $InfTema;
    }

    public static function modificar($data, $id)
    {
        $respuesta = TemasModuloE_Doc::where(['tema' => $id])->update([
            'contenido' => $data['summernoteCont']
        ]);
        return $respuesta;
    }

    
    public static function ElimnarRegistros($id){

        $Archi=TemasModuloE_Doc
        ::where("tema",$id);
        $Archi->delete();
        return $Archi;

    }


}
