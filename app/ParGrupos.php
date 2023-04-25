<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ParGrupos extends Model {

    protected $table = 'para_grupos';
    protected $fillable = [
        'descripcion',
        'tipo'
    ];

    public static function LisGrupos($Tip,$Cant) {
        $Grupos = ParGrupos::where('tipo', $Tip)
                   ->limit($Cant)
                ->get();
        return $Grupos;
    }

}
