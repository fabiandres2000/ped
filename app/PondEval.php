<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use Session;

class PondEval extends Model {

    protected $table = 'pond_eval';
    protected $fillable = [
        'modulo',
        'tema',
        'evaluacion',
        'porcentaje',
        'docente',
        'tponde'
    ];
    
    
    public static function Guardar($data) {

        $Opc = PondEval::where('modulo', Session::get('IDMODULO'))
                ->where('tema', $data["idTema"])
                ->where('docente', Auth::user()->id);
        $Opc->delete();

    foreach ($data["Eval"] as $key => $val) {
            $PondPer = PondEval::create([
                        'modulo' => Session::get('IDMODULO'),
                        'tema' => $data["idTema"],
                        'evaluacion' => $data["Eval"][$key],
                        'porcentaje' => $data["PorcEval"][$key],
                        'docente' => Auth::user()->id,
                        'tponde' => $data["t_ponderaEval"]
            ]);
        }

        return $PondPer;
    }

}
