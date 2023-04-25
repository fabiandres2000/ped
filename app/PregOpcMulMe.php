<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PregOpcMulMe extends Model
{

    protected $table = 'preguntas_me';
    protected $fillable = [
        'banco',
        'competencia',
        'componente',
        'pregunta',
    ];

    public static function Guardar($preg, $compe, $compo, $evalu)
    {

        return PregOpcMulMe::create([
            'banco' => $evalu,
            'competencia' => $compe,
            'componente' => $compo,
            'pregunta' => $preg,
        ]);
    }

    public static function ModiPreMul($preg, $compe, $compo, $idpreg, $evalu)
    {
        $respuesta = PregOpcMulMe::where('id', $idpreg)->update([
            'banco' => $evalu,
            'competencia' => $compe,
            'componente' => $compo,
            'pregunta' => $preg,
        ]);
        return $respuesta;
    }

    public static function ConsulPreg($id)
    {
        $GrupPreg = PregOpcMulMe::where('id', $id)
            ->first();
        return $GrupPreg;
    }
    public static function ConsulPregBan($idBanco)
    {
        $GrupPreg = PregOpcMulMe::where('banco', $idBanco)
            ->first();
        return $GrupPreg;
    }

    public static function DelPregunta($IdPreg)
    {
       
        $Opc = PregOpcMulMe::where('id', $IdPreg)
            ->delete();
    }

    public static function ConsulPregAll($id)
    {
        $GrupPreg = PregOpcMulMe::where('banco', $id)
            ->leftjoin('competencias', 'competencias.id', 'preguntas_me.competencia')
            ->leftjoin('componentes', 'componentes.id', 'preguntas_me.componente')
            ->select('preguntas_me.*', "competencias.nombre as nombre_compe", "componentes.nombre as nombre_compo")
            ->get();
        return $GrupPreg;
    }

    public static function BuscaPregCompexCompo($copmpe, $Compo){
        $GrupPreg = PregOpcMulMe::join("banco_preg_me", "banco_preg_me.id", "preguntas_me.banco")
        ->join("asignaturas_mode", "asignaturas_mode.id", "banco_preg_me.asignatura")
        ->join("partes_me","banco_preg_me.tipo_pregunta", "partes_me.parte")
        ->where("competencia", $copmpe)
        ->where("componente", $Compo)
        ->select("banco_preg_me.id","banco_preg_me.npreguntas","componente","banco_preg_me.tipo_pregunta","partes_me.descripcion")
        ->groupBy('banco_preg_me.id')
        ->get();

        return $GrupPreg;
    }

    public static function GenPregAle($datos)
    {
        $npreg = $datos['n_preguntas'];
        $grado = $datos['prueba'];
        $area = $datos['area'];

        $IdPreg = array();
        $IdBanco = array();
        $prueba = array();
     
        $tPregTot=0;
        foreach ($datos["txtcomp"] as $key => $val) {
            $parcxc = explode("-", $datos["txtcomp"][$key]);
            $compe = $parcxc[0];
            $compo = $parcxc[1];
            

            $nPregxComp = $datos["txtporc"][$key];

            //dd($compe." ".$compo." ".$area." ".$grado);

            if($area==5){  
                $GrupPreg2 = PregOpcMulMe::join("banco_preg_me", "banco_preg_me.id", "preguntas_me.banco")
                ->join("asignaturas_mode", "asignaturas_mode.id", "banco_preg_me.asignatura")
                ->where("competencia", $compe)
                ->where("componente", $compo)
                ->where("asignaturas_mode.area", $area)
                ->where("asignaturas_mode.grado", $grado)
                ->inRandomOrder()
                ->select("banco_preg_me.id","banco_preg_me.npreguntas","componente")
                ->get();
                
                $tpreg=0;

                foreach ($GrupPreg2 as $Preg) {
                    $tPregTot =$tPregTot+ $Preg->npreguntas;
                    if(!in_array($Preg->id,$IdBanco)){

                        $tpreg=$tpreg+$Preg->npreguntas;
                     
                        if($tpreg<=$nPregxComp){
                            array_push($prueba,$tpreg.'<='.$nPregxComp.'banco. '.$Preg->id.' n preguntas. '.$Preg->npreguntas.' Componente: '.$Preg->componente);
                            array_push($IdBanco, $Preg->id);
                        }
                    }
                }


                $GrupPreg2 = PregOpcMulMe::join("banco_preg_me", "banco_preg_me.id", "preguntas_me.banco")
                ->join("asignaturas_mode", "asignaturas_mode.id", "banco_preg_me.asignatura")
                ->whereIn("banco_preg_me.id", $IdBanco)
                ->select("preguntas_me.id")
               ->get();


            }else{
                $GrupPreg2 = PregOpcMulMe::join("banco_preg_me", "banco_preg_me.id", "preguntas_me.banco")
                ->join("asignaturas_mode", "asignaturas_mode.id", "banco_preg_me.asignatura")
                ->where("competencia", $compe)
                ->where("componente", $compo)
                ->where("asignaturas_mode.area", $area)
                ->where("asignaturas_mode.grado", $grado)
                ->inRandomOrder()
                ->select("preguntas_me.id")
                ->limit($nPregxComp)
                ->get();
            }
           
            //array_push($prueba,$GrupPreg2,$IdPreg);
            ///Agregar  id de preguntas en array
            foreach ($GrupPreg2 as $Preg) {
                array_push($IdPreg, $Preg->id);
            }

           
        }

        array_push($prueba,$IdPreg);

      //  array_push($prueba,$GrupPreg2,$IdBanco,$IdPreg);





        $GrupPreg = PregOpcMulMe::join("banco_preg_me", "banco_preg_me.id", "preguntas_me.banco")
            ->whereIn("preguntas_me.id", $IdPreg)
            ->select("preguntas_me.*", "banco_preg_me.enunciado","banco_preg_me.tipo_pregunta")
            ->orderBy('preguntas_me.banco', 'ASC')
            ->get();

        return $GrupPreg;

    }
}
