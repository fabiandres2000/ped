<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UrlDocuMod extends Model
{
  
    protected $table = 'archivos_docentesmod';
    protected $fillable = [
        'modulo',
        'url_programacion',
        'url_contenido'
    ];

    public static function ConsDocumentos($Modu) {

        $Docum = UrlDocuMod::where('modulo', $Modu)
                ->first();
        return $Docum;
    }
}
