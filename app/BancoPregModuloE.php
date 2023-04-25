<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BancoPregModuloE extends Model
{
    protected $table = 'banco_preg_me';
    protected $fillable = [
        'asignatura',
        'enunciado',
        'npreguntas',
        'estado',
        'tipo_pregunta',
        'enunc_preg',
    ];

    public static function Gestion($busqueda, $pagina, $limit, $Comp)
    {
        if ($pagina == "1") {
            $offset = 0;
        } else {
            $pagina--;
            $offset = $pagina * $limit;
        }

        if (!empty($busqueda)) {
            if (!empty($Comp)) {

                $respuesta = BancoPregModuloE::leftJoin('asignaturas_mode', 'asignaturas_mode.id', '=', 'banco_preg_me.asignatura')
                    ->leftJoin('preguntas_me', 'preguntas_me.banco', '=', 'banco_preg_me.id')
                    ->where('banco_preg_me.estado', 'ACTIVO')
                    ->where('banco_preg_me.asignatura', $busqueda)
                    ->where('preguntas_me.componente', $Comp)
                    ->select("banco_preg_me.*", "asignaturas_mode.nombre", "asignaturas_mode.grado")
                    ->groupBy("banco_preg_me.id")
                    ->limit($limit)->offset($offset);

            } else {
                $respuesta = BancoPregModuloE::leftJoin('asignaturas_mode', 'asignaturas_mode.id', '=', 'banco_preg_me.asignatura')
                    ->where('banco_preg_me.estado', 'ACTIVO')
                    ->where('banco_preg_me.asignatura', $busqueda)
                    ->select("banco_preg_me.*", "asignaturas_mode.nombre", "asignaturas_mode.grado")
                    ->limit($limit)->offset($offset);
            }

        } else {
            $respuesta = BancoPregModuloE::leftJoin('asignaturas_mode', 'asignaturas_mode.id', '=', 'banco_preg_me.asignatura')
                ->where('banco_preg_me.estado', 'ACTIVO')
                ->select("banco_preg_me.*", "asignaturas_mode.nombre", "asignaturas_mode.grado")
                ->limit($limit)->offset($offset);

        }

        return $respuesta->get();
    }

    public static function numero_de_registros($busqueda, $Comp)
    {

        if (!empty($busqueda)) {
            if (!empty($Comp)) {

                $respuesta = BancoPregModuloE::leftJoin('asignaturas_mode', 'asignaturas_mode.id', '=', 'banco_preg_me.asignatura')
                    ->leftJoin('preguntas_me', 'preguntas_me.banco', '=', 'banco_preg_me.id')
                    ->where('banco_preg_me.estado', 'ACTIVO')
                    ->where('banco_preg_me.asignatura', $busqueda)
                    ->where('preguntas_me.componente', $Comp)
                    ->select("banco_preg_me.*", "asignaturas_mode.nombre", "asignaturas_mode.grado");

            } else {
                $respuesta = BancoPregModuloE::leftJoin('asignaturas_mode', 'asignaturas_mode.id', '=', 'banco_preg_me.asignatura')
                    ->where('banco_preg_me.estado', 'ACTIVO')
                    ->where('banco_preg_me.asignatura', $busqueda)
                    ->select("banco_preg_me.*", "asignaturas_mode.nombre", "asignaturas_mode.grado");
            }

        } else {
            $respuesta = BancoPregModuloE::leftJoin('asignaturas_mode', 'asignaturas_mode.id', '=', 'banco_preg_me.asignatura')
                ->where('banco_preg_me.estado', 'ACTIVO')
                ->select("banco_preg_me.*", "asignaturas_mode.nombre", "asignaturas_mode.grado");

        }
        return $respuesta->count();
    }

    public static function Guardar($datos)
    {

        $enun = "";
        if (isset($datos["EnunPreg"])) {
            $enun = $datos["EnunPreg"];
        }

        return BancoPregModuloE::create([
            'asignatura' => $datos['asignatura'],
            'enunciado' => $datos['enunciado'],
            'npreguntas' => $datos['npreguntas'],
            'estado' => 'INACTIVO',
            'tipo_pregunta' => $datos['Tipreguntas'],
            'enunc_preg' => $enun,
        ]);
    }

    public static function ModifEval($datos, $id)
    {

        $enun = "";
        if (isset($datos["EnunPreg"])) {
            $enun = $datos["EnunPreg"];
        }
        $respuesta = BancoPregModuloE::where(['id' => $id])->update([
            'asignatura' => $datos['asignatura'],
            'enunciado' => $datos['enunciado'],
            'npreguntas' => $datos['npreguntas'],
            'estado' => 'INACTIVO',
            'enunc_preg' => $enun,
        ]);

        return $respuesta;
    }

    public static function ModifEvalFin($datos, $id)
    {
        $respuesta = BancoPregModuloE::where(['id' => $id])->update([
            'asignatura' => $datos['asignatura'],
            'enunciado' => $datos['enunciado'],
            'npreguntas' => $datos['npreguntas'],
            'estado' => 'ACTIVO',
        ]);

        return $respuesta;
    }

    public static function DelPreg($id)
    {
        $respuesta = BancoPregModuloE::where(['id' => $id])->update([
            'estado' => 'ELIMINADO',
        ]);

        return $respuesta;
    }

    public static function DelBancoPreg($id, $estado)
    {
        $respuesta = BancoPregModuloE::where(['id' => $id])->update([
            'estado' => $estado,
        ]);

        return $respuesta;
    }

    public static function BuscarPregPract($Preg)
    {

        $Preguntas = BancoPregModuloE::leftJoin('preguntas_me', 'preguntas_me.banco', '=', 'banco_preg_me.id')
            ->where('banco_preg_me.id', $Preg)
            ->select('banco_preg_me.enunciado', 'preguntas_me.*')
            ->get();
        return $Preguntas;

    }

    public static function BusPregBanc($id)
    {
        $DesEval = BancoPregModuloE::where('id', $id)
            ->first();
        return $DesEval;
    }

}
