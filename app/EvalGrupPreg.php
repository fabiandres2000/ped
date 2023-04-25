<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EvalGrupPreg extends Model {

    protected $table = 'eval_grupregunta';
    protected $fillable = [
        'evaluacion',
        'pregunta',
        'puntuacion'
    ];

    public static function Guardar($data, $idEval) {

        foreach ($data["txtpreg"] as $key => $val) {
            if ($data["txtpunt"][$key] == null) {
                $data["txtpunt"][$key] = "10";
            }
            $grupPre = EvalGrupPreg::create([
                        'evaluacion' => $idEval,
                        'pregunta' => $data["txtpreg"][$key],
                        'puntuacion' => $data["txtpunt"][$key]
            ]);
        }
        return $grupPre;
    }

    public static function ModifOpcPreg($data) {
        $Opc = EvalGrupPreg::where('evaluacion', $data["Id_Eval"]);
        $Opc->delete();

        foreach ($data["txtpreg"] as $key => $val) {
            if ($data["txtpunt"][$key] == null) {
                $data["txtpunt"][$key] = "10";
            }
            $grupPre = EvalGrupPreg::create([
                        'evaluacion' => $data["Id_Eval"],
                        'pregunta' => $data["txtpreg"][$key],
                        'puntuacion' => $data["txtpunt"][$key]
            ]);
        }
        return $grupPre;
    }

    public static function GrupPreg($id) {
        $GrupPreg = EvalGrupPreg::where('evaluacion', $id)
                ->get();
        return $GrupPreg;
    }

}
