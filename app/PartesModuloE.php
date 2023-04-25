<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PartesModuloE extends Model
{
    
    protected $table = 'partes_me';
    protected $fillable = [
        'parte',
        'descripcion'
    ];


    public static function LisPartes() {
        $Partes = PartesModuloE::get();
        return $Partes;
    }

    public static function BuscParte($parte) {
        $Partes = PartesModuloE::where("parte",$parte)
        ->first();
        return $Partes;
    }

}
