<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UrlDocu extends Model {

    protected $table = 'archivos_docentes';
    protected $fillable = [
        'modulo',
        'url_programacion',
        'url_contenido'
    ];

    public static function ConsDocumentos($Modu) {

        $Docum = UrlDocu::where('modulo', $Modu)
                ->first();
        return $Docum;
    }

}
