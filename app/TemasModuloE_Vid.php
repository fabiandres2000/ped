<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TemasModuloE_Vid extends Model
{
    protected $table = 'videos_moduloe';
    protected $fillable = [
        'tema',
        'video',
    ];

    public static function Guardar($data)
    {
        return TemasModuloE_Vid::create([
            'tema' => $data['tema_id'],
            'video' => $data['Vid'],
        ]);
    }

    public static function BuscarTema($id)
    {
        $InfTema = TemasModuloE_Vid::where('tema', $id)
            ->first();
        return $InfTema;
    }

    public static function ElimnarRegistros($id)
    {

        $Archi = TemasModuloE_Vid::where("tema", $id);
        $Archi->delete();
        return $Archi;

    }
}
