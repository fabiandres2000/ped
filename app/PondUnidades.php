<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use Session;

class PondUnidades extends Model {

    protected $table = 'pond_unidades';
    protected $fillable = [
        'modulo',
        'periodo',
        'unidad',
        'porcentaje',
        'docente',
        'tponde'
    ];

    public static function Guardar($data) {

        $Opc = PondUnidades::where('modulo', Session::get('IDMODULO'))
                ->where('periodo', $data["idPeriodo"])
                ->where('docente', Auth::user()->id);
        $Opc->delete();

        foreach ($data["Uni"] as $key => $val) {
            $PondPer = PondUnidades::create([
                        'modulo' => Session::get('IDMODULO'),
                        'periodo' => $data["idPeriodo"],
                        'unidad' => $data["Uni"][$key],
                        'porcentaje' => $data["PorcUnid"][$key],
                        'docente' => Auth::user()->id,
                        'tponde' => $data["t_ponderaUni"]
            ]);
        }

        return $PondPer;
    }

}
