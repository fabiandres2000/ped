<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use Session;

class PondTemas extends Model
{
        protected $table = 'pond_temas';
    protected $fillable = [
        'modulo',
        'unidad',
        'tema',
        'porcentaje',
        'docente',
        'tponde'
    ];
    
    
    public static function Guardar($data) {

        $Opc = PondTemas::where('modulo', Session::get('IDMODULO'))
                ->where('unidad', $data["idUnida"])
                ->where('docente', Auth::user()->id);
        $Opc->delete();

    foreach ($data["Tema"] as $key => $val) {
            $PondPer = PondTemas::create([
                        'modulo' => Session::get('IDMODULO'),
                        'unidad' => $data["idUnida"],
                        'tema' => $data["Tema"][$key],
                        'porcentaje' => $data["PorcTema"][$key],
                        'docente' => Auth::user()->id,
                        'tponde' => $data["t_ponderaTema"]
            ]);
        }

        return $PondPer;
    }
    
    
}
