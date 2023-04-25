<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use Session;

class Presentacion extends Model
{
         protected $table = 'presentacion';

    protected $fillable = [
        'id_modulo',
        'grado',
        'presentacion',
        'objetivo'
    ];

    public static function presentacion($id) {
        $Pres = Presentacion::where('id_modulo', $id)
                ->first();
        return $Pres;
        
    }
    public static function consultaprese($id) {
        $Pres = Presentacion::where('id_modulo', $id)
                ->first();
        return $Pres;
        
    }
}
