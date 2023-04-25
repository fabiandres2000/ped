<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EvalRelacione extends Model {

    protected $table = 'eval_relacione_def';
    protected $fillable = [
        'evaluacion',
        'opcion',
        'definicion',
        'pregunta'
    ];

    public static function Guardar($data, $Preg,$eval) {

        foreach ($data["txtopcpreg"] as $key => $val) {
            $grupPre = EvalRelacione::create([
                        'evaluacion' => $eval,
                        'opcion' => $data["Mesnsaje"][$key],
                        'definicion' => $data["txtopcpreg"][$key],
                        'pregunta' => $Preg,
            ]);
        }

        return $grupPre;
    }

    public static function Modificar($datos,$Preg,$eval) {

        $Opc = EvalRelacione::where('pregunta', $Preg);
        $Opc->delete();

        foreach ($datos["txtopcpreg"] as $key => $val) {
            $grupPre = EvalRelacione::create([
                        'evaluacion' => $eval,
                        'opcion' => $datos["Mesnsaje"][$key],
                        'definicion' => $datos["txtopcpreg"][$key],
                        'pregunta' => $Preg,
            ]);
        }

        return $grupPre;
    }

    public static function PregRelDef($id) {
        $EvalRel = EvalRelacione::where('pregunta', $id)
                ->get();
        return $EvalRel;
    }

    public static function PregRelDefAll($id) {
        $EvalRel = EvalRelacione::where('evaluacion', $id)
                ->get();
        return $EvalRel;
    }

    public static function DelPreg($id){
        $Opc = EvalRelacione::where('pregunta', $id);
        $Opc->delete();
    }


}
