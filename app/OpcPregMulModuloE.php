<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OpcPregMulModuloE extends Model
{    protected $table = 'opc_mult_eval_me';
    protected $fillable = [
        'pregunta',
        'opciones',
        'correcta',
        'banco',
    ];

    public static function Guardar($data, $id, $idbanco)
    {

        foreach ($data["txtopcpreg"] as $key => $val) {
            $respuesta = OpcPregMulModuloE::create([
                'pregunta' => $id,
                'opciones' => $data["txtopcpreg"][$key],
                'correcta' => $data["OpcCorecta"][$key],
                'banco' => $idbanco,
            ]);
        }
        return $respuesta;
    }

    public static function ConsulGrupOpc($id, $eval)
    {
        $DesOpcPreg = OpcPregMulModuloE::where('pregunta', $id)
            ->where('banco', $eval)
            ->get();
        return $DesOpcPreg;
    }

    
    public static function ModOpcPreg($data, $ideval,$idPreg)
    {
        $Opc = OpcPregMulModuloE::where('pregunta', $idPreg);
        $Opc->delete();
        foreach ($data["txtopcpreg"] as $key => $val) {
            $respuesta = OpcPregMulModuloE::create([
                'pregunta' => $idPreg,
                'opciones' => $data["txtopcpreg"][$key],
                'correcta' => $data["OpcCorecta"][$key],
                'banco' => $ideval,
            ]);
        }
        return $respuesta;
    }

    public static function ConsulGrupOpcPreg($id)
    {
        $DesOpcPreg = OpcPregMulModuloE::where('pregunta', $id)
            ->get();
        return $DesOpcPreg;
    }

    public static function DelOpciones($IdPreg){
        $Opc = OpcPregMulModuloE::where('pregunta', $IdPreg);
        $Opc->delete();
        return "1";
    }

    
    public static function ConsulGrupOpcPregAll($id)
    {
        $DesOpcPreg = OpcPregMulModuloE::where('banco', $id)
            ->get();
        return $DesOpcPreg;
    }

    public static function BuscOpcResp($id,$Est)
    {
        $DesOpcPreg = OpcPregMulModuloE::join('resp_pregmultiple_me', 'resp_pregmultiple_me.respuesta', 'opc_mult_eval_me.id')
            ->join('preguntas_me', 'preguntas_me.id', 'opc_mult_eval_me.pregunta')
            ->select('opc_mult_eval_me.pregunta', 'opc_mult_eval_me.correcta', 'resp_pregmultiple_me.respuesta')
            ->where('resp_pregmultiple_me.pregunta', $id)
            ->where('resp_pregmultiple_me.alumno', $Est)
            ->first();
            
        return $DesOpcPreg;
    }

    public static function BuscOpcRespPrueba($id,$Est)
    {
        $DesOpcPreg = OpcPregMulModuloE::join('resp_pregmultiple_me_prueba', 'resp_pregmultiple_me_prueba.respuesta', 'opc_mult_eval_me.id')
            ->join('preguntas_me', 'preguntas_me.id', 'opc_mult_eval_me.pregunta')
            ->select('opc_mult_eval_me.pregunta', 'opc_mult_eval_me.correcta', 'resp_pregmultiple_me_prueba.respuesta')
            ->where('resp_pregmultiple_me_prueba.pregunta', $id)
            ->where('resp_pregmultiple_me_prueba.alumno', $Est)
            ->first();
            
        return $DesOpcPreg;
    }

    public static function BuscOpcSimu($preg){
        $DesOpcPreg = OpcPregMulModuloE::where('pregunta', $preg)
        ->get();
    return $DesOpcPreg;
    }

    

}
