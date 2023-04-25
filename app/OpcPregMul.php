<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OpcPregMul extends Model
{

    protected $table = 'opc_mult_eval';
    protected $fillable = [
        'pregunta',
        'opciones',
        'correcta',
        'evaluacion',
    ];

    public static function Guardar($data, $id, $ideval)
    {

        foreach ($data["txtopcpreg"] as $key => $val) {
            $respuesta = OpcPregMul::create([
                'pregunta' => $id,
                'opciones' => $data["txtopcpreg"][$key],
                'correcta' => $data["OpcCorecta"][$key],
                'evaluacion' => $ideval,
            ]);
        }
        return $respuesta;
    }

    public static function ModOpcPreg($data, $ideval)
    {
        $Opc = OpcPregMul::where('pregunta', $data['IdpreguntaMul']);
        $Opc->delete();
        foreach ($data["txtopcpreg"] as $key => $val) {
            $respuesta = OpcPregMul::create([
                'pregunta' => $data['IdpreguntaMul'],
                'opciones' => $data["txtopcpreg"][$key],
                'correcta' => $data["OpcCorecta"][$key],
                'evaluacion' => $ideval,
            ]);
        }
        return $respuesta;
    }

    public static function DelOpciones($IdPreg)
    {
        $Opc = OpcPregMul::where('pregunta', $IdPreg);
        $Opc->delete();
        return "1";
    }

    public static function GrupOpc($id, $eval)
    {
        $DesOpcPreg = OpcPregMul::where('pregunta', $id)
            ->where('evaluacion', $eval)
            ->get();
        return $DesOpcPreg;
    }

    public static function ConsulGrupOpc($id, $eval)
    {
        $DesOpcPreg = OpcPregMul::where('pregunta', $id)
            ->where('evaluacion', $eval)
            ->get();
        return $DesOpcPreg;
    }

    public static function ConsulGrupOpcPreg($id)
    {
        $DesOpcPreg = OpcPregMul::where('pregunta', $id)
            ->get();
        return $DesOpcPreg;
    }

    public static function ConsulGrupOpcPregAll($id)
    {
        $DesOpcPreg = OpcPregMul::where('evaluacion', $id)
            ->get();
        return $DesOpcPreg;
    }

    public static function GrupOpcResp($id, $alumno)
    {

        $DesOpcPreg = OpcPregMul::leftjoin('resp_pregmultiple', function ($join) use ($alumno) {
            $join->on('opc_mult_eval.id', '=', 'resp_pregmultiple.respuesta')
                ->where('resp_pregmultiple.alumno', '=', $alumno);
        })
            ->select('opc_mult_eval.*', 'resp_pregmultiple.respuesta')
            ->where('opc_mult_eval.pregunta', $id)
            ->get();
        return $DesOpcPreg;
    }

    public static function BuscOpc($id)
    {
        $DesOpcPreg = OpcPregMul::join('resp_pregmultiple', 'resp_pregmultiple.respuesta', 'opc_mult_eval.id')
            ->join('preg_mult_eval', 'preg_mult_eval.id', 'opc_mult_eval.pregunta')
            ->select('opc_mult_eval.pregunta', 'opc_mult_eval.correcta', 'preg_mult_eval.puntuacion')
            ->where('preg_mult_eval.evaluacion', $id)
            ->get();
        return $DesOpcPreg;
    }

    public static function BuscOpcResp($id, $Est)
    {
        $DesOpcPreg = OpcPregMul::join('resp_pregmultiple', 'resp_pregmultiple.respuesta', 'opc_mult_eval.id')
            ->join('preg_mult_eval', 'preg_mult_eval.id', 'opc_mult_eval.pregunta')
            ->select('opc_mult_eval.pregunta', 'opc_mult_eval.correcta', 'preg_mult_eval.puntuacion', 'resp_pregmultiple.respuesta')
            ->where('resp_pregmultiple.pregunta', $id)
            ->where('resp_pregmultiple.alumno', $Est)
            ->first();

        return $DesOpcPreg;
    }

    public static function GrupOpc2($eva)
    {

        $DesOpcPreg = OpcPregMul::where('evaluacion', $eva)
            ->select('id', 'pregunta', 'opciones', 'correcta')
            ->get();
        return $DesOpcPreg;
    }

}
