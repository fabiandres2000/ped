<?php

namespace App;

use Auth;
use Illuminate\Database\Eloquent\Model;

class EvalVerFal extends Model
{

    protected $table = 'eval_verfal';
    protected $fillable = [
        'evaluacion',
        'pregunta',
        'respuesta',
        'puntaje',
    ];

    public static function Guardar($data, $idEval)
    {
        foreach ($data["radpregVerFal"] as $key2 => $val2) {
            $resp = $data["radpregVerFal"][$key2];
        }
        $respuesta = EvalVerFal::create([
            'evaluacion' => $idEval,
            'pregunta' => $data["summernote_pregverdFals"],
            'respuesta' => $resp,
            'puntaje' => $data["puntaje"],
        ]);
        return $respuesta;
    }

    public static function ModifPreg($data)
    {
        foreach ($data["radpregVerFal"] as $key2 => $val2) {
            $resp = $data["radpregVerFal"][$key2];
        }
        $respuesta = EvalVerFal::where('id', $data['id-pregverfal'])->update([
            'pregunta' => $data["summernote_pregverdFals"],
            'respuesta' => $resp,
            'puntaje' => $data["puntaje"],
        ]);

        return $respuesta;
    }

    public static function VerFal($id)
    {
        $PregVerFal = EvalVerFal::where('evaluacion', $id)
            ->get();
        return $PregVerFal;
    }

    public static function ConVerFal($id)
    {
        $PregVerFal = EvalVerFal::where('id', $id)
            ->first();
        return $PregVerFal;
    }
    

    public static function VerFalResp($id,$Est)
    {
        $DesVerFal = EvalVerFal::join('resp_pregverfal', 'resp_pregverfal.pregunta', 'eval_verfal.id')
            ->select('resp_pregverfal.pregunta', 'resp_pregverfal.respuesta_alumno', 'eval_verfal.respuesta', 'eval_verfal.puntaje')
            ->where('resp_pregverfal.pregunta', $id)
            ->where('resp_pregverfal.alumno', $Est)
            ->first();
        return $DesVerFal;
    }

    public static function DelPreg($id){
        $Opc = EvalVerFal::where('id', $id);
        $Opc->delete();
    }

}
