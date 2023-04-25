<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class sesiones extends Model {

    protected $table = 'sesiones_activas';
    protected $fillable = [
        'usuario',
        'hora'
    ];

public static function Guardar($Usuario) {
        $ldate = date('Y-m-d H:i:s');
        return sesiones::updateOrCreate([
                    'usuario' => $Usuario
                        ], [
                    'usuario' => $Usuario,
                    'hora' => $ldate
        ]);
    }
    
    
    public static function ConsActivos($Usu) {
       
        $Ses = sesiones::where('usuario', $Usu)
                ->first();
        return $Ses;
    }

}
