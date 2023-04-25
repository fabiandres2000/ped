<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use Session;

class PondPeriodos extends Model {

    protected $table = 'pond_periodos';
    protected $fillable = [
        'modulo',
        'periodo',
        'porcentaje',
        'docente',
        'tponde'
    ];

    public static function Guardar($data) {

        $Opc = PondPeriodos::where('modulo', Session::get('IDMODULO'))
                ->where('docente', Auth::user()->id);
        $Opc->delete();

        foreach ($data["Per"] as $key => $val) {
            $PondPer = PondPeriodos::create([
                        'modulo' => Session::get('IDMODULO'),
                        'periodo' => $data["Per"][$key],
                        'porcentaje' => $data["PorcPer"][$key],
                        'docente' => Auth::user()->id,
                        'tponde' => $data["t_pondera"]
            ]);
        }

        return $PondPer;
    }

}
