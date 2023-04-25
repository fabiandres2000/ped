<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class EvalRelacioneOpc extends Model
{

    protected $table = 'eval_relacione_opc';
    protected $fillable = [
        'evaluacion',
        'respuesta',
        'correcta',
        'pregunta',
    ];

    public static function Guardar($data, $idPreg, $idEval)
    {

        foreach ($data["txtopcResp"] as $key => $val) {

            $grupPre = EvalRelacioneOpc::create([
                'evaluacion' => $idEval,
                'respuesta' => $data["txtopcResp"][$key],
                'correcta' => $data["respuestas"][$key],
                'pregunta' => $idPreg,
            ]);
        }

        return $grupPre;
    }

    public static function Modificar($data, $idPreg, $idEval)
    {

        $Opc = EvalRelacioneOpc::where('pregunta', $idPreg);
        $Opc->delete();

        foreach ($data["txtopcResp"] as $key => $val) {

            $grupPre = EvalRelacioneOpc::create([
                'evaluacion' => $idEval,
                'respuesta' => $data["txtopcResp"][$key],
                'correcta' => $data["respuestas"][$key],
                'pregunta' => $idPreg,
            ]);
        }

        return $grupPre;
    }

    public static function PregRelOpc($id)
    {
        $EvalRelOpc = EvalRelacioneOpc::where('pregunta', $id)
            ->inRandomOrder()
            ->get();
        return $EvalRelOpc;
    }

    public static function PregRelOpcAll($id)
    {
        $EvalRelOpc = EvalRelacioneOpc::where('evaluacion', $id)
            ->get();
        return $EvalRelOpc;
    }

    public static function PregRelOpcadd($id)
    {
        $EvalRelOpc = EvalRelacioneOpc::where('pregunta', $id)
            ->where('correcta', '-')
            ->get();
        return $EvalRelOpc;
    }

    public static function PregRelOpcaddAll($id)
    {
        $EvalRelOpc = EvalRelacioneOpc::where('evaluacion', $id)
            ->where('correcta', '-')
            ->get();
        return $EvalRelOpc;
    }

    public static function RelacResp($id)
    {
        $DesVerFal = EvalRelacioneOpc::join('resp_pregrelacione', 'resp_pregrelacione.pregunta', 'eval_relacione_opc.id')
            ->select('resp_pregrelacione.pregunta', 'resp_pregrelacione.respuesta_alumno', 'eval_relacione_opc.correcta', 'eval_relacione_opc.puntaje', 'eval_relacione_opc.respuesta')
            ->where('eval_relacione_opc.evaluacion', $id)
            ->where('resp_pregrelacione.alumno', Auth::user()->id)
            ->get();
        return $DesVerFal;
    }

    public static function DelPreg($id)
    {
        $Opc = EvalRelacioneOpc::where('pregunta', $id);
        $Opc->delete();
    }

}
