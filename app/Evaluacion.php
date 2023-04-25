<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class Evaluacion extends Model
{

    protected $table = 'evaluacion';
    protected $fillable = [
        'contenido',
        'titulo',
        'hab_conversacion',
        'intentos_perm',
        'calif_usando',
        'punt_max',
        'intentos_real',
        'clasificacion',
        'estado',
        'enunciado',
        'animacion',
        'tiempo',
        'docente',
        'origen_eval',
        'hab_tiempo',
        'calxdoc',
    ];

    public static function Guardar($datos, $calxdoc)
    {
        $Doc = "";
        if (Auth::user()->tipo_usuario == "Profesor") {
            $Doc = Auth::user()->id;
        }

        if ($datos["Punt_Max"] == null || $datos["Punt_Max"] == "0") {
            $datos["Punt_Max"] = "10";
        }

        if (!isset($datos["animacion"])) {
            $datos["animacion"] = "";
        }
        return Evaluacion::create([
            'contenido' => $datos['tema_id'],
            'titulo' => $datos['titulo'],
            'hab_conversacion' => $datos['HabConv'],
            'intentos_perm' => $datos['cb_intentosPer'],
            'calif_usando' => $datos['cb_CalUsando'],
            'punt_max' => $datos['Punt_Max'],
            'intentos_real' => '0',
            'clasificacion' => $datos['clasificacion'],
            'estado' => 'INACTIVO',
            'enunciado' => $datos['summernoteRelacione'],
            'animacion' => $datos['animacion'],
            'tiempo' => $datos['TEval'],
            'docente' => $Doc,
            'origen_eval' => $datos['origen_eval'],
            'hab_tiempo' => $datos['TextHabTiempo'],
            'calxdoc' => $calxdoc,
        ]);
    }

    public static function ModifEval($datos, $id, $calxdoc)
    {

        $Doc = "";
        if (Auth::user()->tipo_usuario == "Profesor") {
            $Doc = Auth::user()->id;
        }

        if (!isset($datos["animacion"])) {
            $datos["animacion"] = "";
        }

        $respuesta = Evaluacion::where(['id' => $id])->update([
            'titulo' => $datos['titulo'],
            'hab_conversacion' => $datos['HabConv'],
            'intentos_perm' => $datos['cb_intentosPer'],
            'calif_usando' => $datos['cb_CalUsando'],
            'punt_max' => $datos['Punt_Max'],
            'clasificacion' => $datos['clasificacion'],
            'enunciado' => $datos['summernoteRelacione'],
            'animacion' => $datos['animacion'],
            'tiempo' => $datos['TEval'],
            'docente' => $Doc,
            'hab_tiempo' => $datos['TextHabTiempo'],
            'calxdoc' => $calxdoc,
        ]);

        return $respuesta;
    }

    public static function ModifEvalCalxDoc($id, $calxdoc)
    {
        $respuesta = Evaluacion::where(['id' => $id])->update([
            'calxdoc' => $calxdoc,
        ]);

        return $respuesta;
    }

    public static function VerfDel($id)
    {
        $VerfDel = Evaluacion::where('id', $id)
        ->where('id', '>=', 1)
        ->where('id', '<=', 6464)
        ->get();
       
        return $VerfDel;
    }

    public static function VerificarAsig($data)
    {
        $Exite = "no";
        foreach ($data["idEvaluacion"] as $key => $val) {
            if ($data["EvalSel"][$key] == "si") {

                $Eval = Evaluacion::leftJoin("contenido", "contenido.id", "evaluacion.contenido")
                    ->where("evaluacion.id", $data['idEvaluacion'][$key])
                    ->where("evaluacion.estado", "ACTIVO")
                    ->select("contenido.modulo")
                    ->first();

                $AsigProf = AsigProf::where("grado", $Eval->modulo)
                    ->where("profesor", $data["docenteReasig"])
                    ->get();

                if ($AsigProf->count() > 0) {
                    $Exite = "si";
                }

            }

        }
        return $Exite;

    }

    public static function VerificarModu($data)
    {
        $Exite = "no";
        foreach ($data["idEvaluacion"] as $key => $val) {
            if ($data["EvalSel"][$key] == "si") {

                $Eval = Evaluacion::leftJoin("contenido_modulo", "contenido_modulo.id", "evaluacion.contenido")
                    ->where("evaluacion.id", $data['idEvaluacion'][$key])
                    ->where("evaluacion.estado", "ACTIVO")
                    ->select("contenido_modulo.modulo")
                    ->first();

                $AsigProf = ModProf::where("grado", $Eval->modulo)
                    ->where("profesor", $data["docenteReasig"])
                    ->get();

                if ($AsigProf->count() > 0) {
                    $Exite = "si";
                }

            }

        }
        return $Exite;

    }

    public static function ReasignarAsig($data)
    {

        foreach ($data["idEvaluacion"] as $key => $val) {

            if ($data["EvalSel"][$key] == "si") {
                $respuesta = Evaluacion::where(['id' => $data["idEvaluacion"][$key], "docente" => $data["docenteOld"]])->update([
                    'docente' => $data['docenteReasig'],
                ]);
            }
        }

        return $respuesta;
    }

    public static function listarEvalxDocente($Doce)
    {
        $Eval = DB::connection("mysql")->select("SELECT CONCAT(asig.nombre,' - Grado ',modu.grado_modulo,'°') AS asignatura, eval.titulo, eval.id, conte.modulo,eval.docente "
            . " FROM evaluacion eval "
            . " LEFT JOIN contenido conte ON eval.contenido=conte.id"
            . " LEFT JOIN (SELECT * FROM asig_prof WHERE profesor = " . $Doce . ")  ap "
            . " ON conte.modulo=ap.grado  "
            . " LEFT JOIN modulos modu ON conte.modulo=modu.id"
            . " LEFT JOIN asignaturas asig ON modu.asignatura=asig.id"
            . " WHERE  eval.docente=" . $Doce . " AND eval.origen_eval='C' AND  eval.estado='ACTIVO' AND ap.grado IS NULL");

        return $Eval;
    }

    public static function listarEvalxDocenteModu($Doce)
    {
        $Eval = DB::connection("mysql")->select("SELECT CONCAT(asig.nombre,' - Grado ',modu.grado_modulo,'°') AS asignatura, eval.titulo, eval.id, conte.modulo,eval.docente "
            . " FROM evaluacion eval "
            . " LEFT JOIN contenido_modulo conte ON eval.contenido=conte.id"
            . " LEFT JOIN (SELECT * FROM mod_prof WHERE profesor = " . $Doce . ")  ap "
            . " ON conte.modulo=ap.grado  "
            . " LEFT JOIN grados_modulos modu ON conte.modulo=modu.id"
            . " LEFT JOIN modulos_transversales asig ON modu.modulo=asig.id"
            . " WHERE  eval.docente=" . $Doce . " AND eval.origen_eval='M' AND  eval.estado='ACTIVO' AND ap.grado IS NULL");

        return $Eval;
    }

    public static function ModifEvalFin($datos, $id)
    {

        $Doc = "";
        if (Auth::user()->tipo_usuario == "Profesor") {
            $Doc = Auth::user()->id;
        }

        if (!isset($datos["animacion"])) {
            $datos["animacion"] = "";
        }

        $respuesta = Evaluacion::where(['id' => $id])->update([
            'titulo' => $datos['titulo'],
            'hab_conversacion' => $datos['HabConv'],
            'intentos_perm' => $datos['cb_intentosPer'],
            'calif_usando' => $datos['cb_CalUsando'],
            'punt_max' => $datos['Punt_Max'],
            'estado' => 'ACTIVO',
            'clasificacion' => $datos['clasificacion'],
            'enunciado' => $datos['summernoteRelacione'],
            'animacion' => $datos['animacion'],
            'tiempo' => $datos['TEval'],
            'docente' => $Doc,
            'hab_tiempo' => $datos['TextHabTiempo'],
        ]);

        return $respuesta;
    }

    public static function DesEval($id)
    {
        $DesEval = Evaluacion::where('id', $id)
            ->first();
        return $DesEval;
    }

    public static function BusEval($id)
    {
        $DesEval = Evaluacion::where('id', $id)
            ->first();
        return $DesEval;
    }

    public static function BusEvalxtema($id, $or)
    {

        $DesEval = Evaluacion::where('contenido', $id)
            ->where('estado', 'ACTIVO')
            ->where('origen_eval', $or)
            ->get();

        return $DesEval;
    }

    public static function ListEval($id, $or)
    {

        if (Auth::user()->tipo_usuario == "Profesor") {
            $Usu = Auth::user()->id;
            $DesEval = Evaluacion::where('contenido', $id)
                ->where('estado', 'ACTIVO')
                ->where('evaluacion.origen_eval', $or)
                ->where(function ($query) use ($Usu) {
                    $query->where('evaluacion.docente', "")
                        ->orWhere('evaluacion.docente', $Usu);
                })
                ->get();
        } else if (Auth::user()->tipo_usuario == "Estudiante") {
            $Usu = Session::get('USUDOCENTE');
            $DesEval = Evaluacion::where('contenido', $id)
                ->where('estado', 'ACTIVO')
                ->where('evaluacion.origen_eval', $or)
                ->where(function ($query) use ($Usu) {
                    $query->where('evaluacion.docente', "")
                        ->orWhere('evaluacion.docente', $Usu);
                })
                ->get();
        } else {
            $Usu = Auth::user()->id;
            $DesEval = Evaluacion::where('contenido', $id)
                ->where('evaluacion.origen_eval', $or)
                ->where('estado', 'ACTIVO')
                ->get();
        }

        return $DesEval;
    }

    public static function ListEvalCal($id)
    {
        $DesEval = Evaluacion::join('contenido', 'contenido.id', 'evaluacion.contenido')
            ->where('contenido', $id)
            ->where('evaluacion.estado', 'ACTIVO')
            ->where('evaluacion.origen_eval', 'C')
            ->whereNotIn('evaluacion.origen_eval', ['M'])
            ->select('evaluacion.id', 'evaluacion.titulo', 'contenido.titu_contenido', 'evaluacion.clasificacion')
            ->get();
        return $DesEval;
    }
    public static function ListEvalCalLab($id)
    {
        $DesEval = Evaluacion::join('laboratorios', 'laboratorios.id', 'evaluacion.contenido')
            ->where('contenido', $id)
            ->where('evaluacion.estado', 'ACTIVO')
            ->where('evaluacion.origen_eval', 'L')
            ->whereNotIn('evaluacion.origen_eval', ['M'])
            ->select('evaluacion.id', 'evaluacion.titulo', 'laboratorios.titulo as titu_contenido', 'evaluacion.clasificacion')
            ->get();
        return $DesEval;
    }

    public static function ListEvalCalMod($id)
    {
        $DesEval = Evaluacion::join('contenido_modulo', 'contenido_modulo.id', 'evaluacion.contenido')
            ->where('contenido', $id)
            ->where('evaluacion.estado', 'ACTIVO')
            ->where('evaluacion.origen_eval', 'M')
            ->select('evaluacion.id', 'evaluacion.titulo', 'contenido_modulo.titu_contenido', 'evaluacion.clasificacion')
            ->get();
        return $DesEval;
    }

    public static function ListEvalxClasif($id, $clasf, $or)
    {
        if (Auth::user()->tipo_usuario == "Profesor") {
            $Usu = Auth::user()->id;
            $DesEval = Evaluacion::where('contenido', $id)
                ->where('clasificacion', $clasf)
                ->where('estado', 'ACTIVO')
                ->where('origen_eval', $or)
                ->where(function ($query) use ($Usu) {
                    $query->where('evaluacion.docente', "")
                        ->orWhere('evaluacion.docente', $Usu);
                })
                ->get();
        } else if (Auth::user()->tipo_usuario == "Estudiante") {
            $Usu = Session::get('USUDOCENTE');
            $DesEval = Evaluacion::where('contenido', $id)
                ->where('clasificacion', $clasf)
                ->where('origen_eval', $or)
                ->where('estado', 'ACTIVO')
                ->where(function ($query) use ($Usu) {
                    $query->where('evaluacion.docente', "")
                        ->orWhere('evaluacion.docente', $Usu);
                })
                ->get();
        } else {
            $DesEval = Evaluacion::where('contenido', $id)
                ->where('clasificacion', $clasf)
                ->where('origen_eval', $or)
                ->where('estado', 'ACTIVO')
                ->get();
        }

        return $DesEval;
    }

    public static function UpdateIntentos($id)
    {
        $DesEval = Evaluacion::find($id);
        $DesEval->intentos_real = $DesEval->intentos_real + 1;
        $DesEval->save();
    }

    public static function editarestado($id, $estado)
    {
        $Respuesta = Evaluacion::where('id', $id)->update([
            'estado' => $estado,
        ]);
        return $Respuesta;
    }

    public static function editarvideo($id, $video)
    {
        $Respuesta = Evaluacion::where('id', $id)->update([
            'animacion' => $video,
        ]);
        return $Respuesta;
    }

    public static function listarPond($id)
    {
        $Usu = Auth::user()->id;
        $Temas = Evaluacion::leftJoin('pond_eval', 'evaluacion.id', '=', 'pond_eval.evaluacion', 'pond_eval.docente', '=', $Usu)
            ->where('evaluacion.contenido', $id)
            ->where('evaluacion.estado', "ACTIVO")
            ->where('evaluacion.clasificacion', "PRODUC")
            ->select('evaluacion.id', 'evaluacion.titulo', 'pond_eval.porcentaje', 'pond_eval.tponde')
            ->get();
        return $Temas;
    }

    public static function DatosEvla($id, $or)
    {
        if ($or == "IFEVAL") {
            $DatEval = Evaluacion::join('contenido', 'contenido.id', 'evaluacion.contenido')
                ->join('unidades', 'unidades.id', 'contenido.unidad')
                ->join('modulos', 'modulos.id', 'unidades.modulo')
                ->join('asignaturas', 'modulos.asignatura', 'asignaturas.id')
                ->select('evaluacion.titulo', 'asignaturas.nombre', 'modulos.grado_modulo', 'unidades.des_unidad', 'contenido.titu_contenido', 'evaluacion.calif_usando', 'evaluacion.tiempo', 'evaluacion.punt_max', 'evaluacion.tip_evaluacion', 'evaluacion.id', 'evaluacion.enunciado', 'evaluacion.hab_conversacion', 'evaluacion.calxdoc')
                ->where('evaluacion.id', $id)
                ->first();
        } else {
            $DatEval = Evaluacion::join('contenido', 'contenido.id', 'evaluacion.contenido')
                ->join('unidades', 'unidades.id', 'contenido.unidad')
                ->join('modulos', 'modulos.id', 'unidades.modulo')
                ->join('eval_intentos', 'eval_intentos.evaluacion', 'evaluacion.id')
                ->join('asignaturas', 'modulos.asignatura', 'asignaturas.id')
                ->select('asignaturas.nombre', 'unidades.des_unidad', 'contenido.titu_contenido', 'evaluacion.intentos_perm', 'evaluacion.calif_usando', 'evaluacion.punt_max', 'evaluacion.tiempo', 'eval_intentos.int_realizados', 'evaluacion.tip_evaluacion', 'evaluacion.id', 'evaluacion.hab_conversacion', 'evaluacion.titulo', 'evaluacion.calxdoc', 'evaluacion.hab_tiempo')
                ->where('evaluacion.id', $id)
                ->where('eval_intentos.alumnos', Auth::user()->id)
                ->first();
        }

        return $DatEval;
    }

    public static function DatosEvalMod($id, $or)
    {
        if ($or == "IFEVAL") {
            $DatEval = Evaluacion::join('contenido_modulo', 'contenido_modulo.id', 'evaluacion.contenido')
                ->join('unidades_modulos', 'unidades_modulos.id', 'contenido_modulo.unidad')
                ->join('grados_modulos', 'grados_modulos.id', 'unidades_modulos.modulo')
                ->join('modulos_transversales', 'grados_modulos.modulo', 'modulos_transversales.id')
                ->select('evaluacion.titulo', 'modulos_transversales.nombre', 'grados_modulos.grado_modulo', 'unidades_modulos.des_unidad', 'contenido_modulo.titu_contenido', 'evaluacion.calif_usando', 'evaluacion.tiempo', 'evaluacion.punt_max', 'evaluacion.tip_evaluacion', 'evaluacion.id', 'evaluacion.enunciado', 'evaluacion.hab_conversacion', 'evaluacion.calxdoc')
                ->where('evaluacion.id', $id)
                ->first();
        } else {
            $DatEval = Evaluacion::join('contenido_modulo', 'contenido_modulo.id', 'evaluacion.contenido')
                ->join('unidades_modulos', 'unidades_modulos.id', 'contenido_modulo.unidad')
                ->join('grados_modulos', 'grados_modulos.id', 'unidades_modulos.modulo')
                ->join('eval_intentos', 'eval_intentos.evaluacion', 'evaluacion.id')
                ->join('modulos_transversales', 'grados_modulos.modulo', 'modulos_transversales.id')
                ->select('modulos_transversales.nombre', 'unidades_modulos.des_unidad', 'contenido_modulo.titu_contenido', 'evaluacion.intentos_perm', 'evaluacion.calif_usando', 'evaluacion.punt_max', 'evaluacion.tiempo', 'eval_intentos.int_realizados', 'evaluacion.tip_evaluacion', 'evaluacion.id', 'evaluacion.hab_conversacion', 'evaluacion.titulo', 'evaluacion.calxdoc', 'evaluacion.hab_tiempo')
                ->where('evaluacion.id', $id)
                ->where('eval_intentos.alumnos', Auth::user()->id)
                ->first();
        }

        return $DatEval;
    }

    public static function DatosEvalME($id, $or)
    {
        if ($or == "IFEVAL") {
            $DatEval = Evaluacion::join('temas_moduloe', 'temas_moduloe.id', 'evaluacion.contenido')
                ->join('asignaturas_mode', 'temas_moduloe.asignatura', 'asignaturas_mode.id')
                ->select('evaluacion.titulo', 'asignaturas_mode.nombre', 'asignaturas_mode.grado', 'temas_moduloe.titulo as titu', 'evaluacion.calif_usando', 'evaluacion.tiempo', 'evaluacion.punt_max', 'evaluacion.tip_evaluacion', 'evaluacion.id', 'evaluacion.enunciado', 'evaluacion.hab_conversacion', 'evaluacion.calxdoc')
                ->where('evaluacion.id', $id)
                ->first();
        } else {
            $DatEval = Evaluacion::join('temas_moduloe', 'temas_moduloe.id', 'evaluacion.contenido')
                ->join('eval_intentos', 'eval_intentos.evaluacion', 'evaluacion.id')
                ->join('asignaturas_mode', 'temas_moduloe.asignatura', 'asignaturas_mode.id')
                ->select('asignaturas_mode.nombre', 'temas_moduloe.titulo as titu', 'evaluacion.intentos_perm', 'evaluacion.calif_usando', 'evaluacion.punt_max', 'evaluacion.tiempo', 'eval_intentos.int_realizados', 'evaluacion.tip_evaluacion', 'evaluacion.id', 'evaluacion.hab_conversacion', 'evaluacion.titulo', 'evaluacion.calxdoc', 'evaluacion.hab_tiempo')
                ->where('evaluacion.id', $id)
                ->where('eval_intentos.alumnos', Auth::user()->id)
                ->first();
        }

        return $DatEval;
    }

    public static function DatosEvaluacion($id)
    {
        $DatEval = Evaluacion::join('eval_intentos', 'eval_intentos.evaluacion', 'evaluacion.id')
            ->select('evaluacion.intentos_perm', 'evaluacion.calif_usando', 'evaluacion.punt_max', 'eval_intentos.int_realizados')
            ->where('evaluacion.id', $id)
            ->where('eval_intentos.alumnos', Auth::user()->id)
            ->first();
        return $DatEval;
    }

    public static function DesRespVerFal($DesEva)
    {

        $Alumno = $DesEva->alumno;
        $evalua = $DesEva->evaluacion;
        return Evaluacion::leftjoin('eval_verfal', 'eval_verfal.evaluacion', 'evaluacion.id')
            ->leftjoin('resp_pregverfal', function ($join) use ($Alumno) {
                $join->on('eval_verfal.id', '=', 'resp_pregverfal.pregunta');
                $join->where('resp_pregverfal.alumno', '=', $Alumno);
            })
            ->leftjoin('alumnos', 'alumnos.usuario_alumno', 'resp_pregverfal.alumno')
            ->select('resp_pregverfal.id', 'resp_pregverfal.evaluacion', 'resp_pregverfal.respuesta_alumno', 'resp_pregverfal.fecha', 'eval_verfal.respuesta', 'eval_verfal.puntaje', 'eval_verfal.pregunta', 'evaluacion.intentos_perm', 'evaluacion.calif_usando', 'evaluacion.punt_max', 'evaluacion.titulo', 'evaluacion.tip_evaluacion', 'alumnos.nombre_alumno', 'alumnos.apellido_alumno')
            ->where('eval_verfal.evaluacion', $evalua)
            ->get();
    }

    public static function Gestion($busqueda, $pagina, $limit, $id)
    {
        if ($pagina == "1") {
            $offset = 0;
        } else {
            $pagina--;
            $offset = $pagina * $limit;
        }
        if (!empty($busqueda)) {
            $respuesta = Evaluacion::join('contenido', 'contenido.id', 'evaluacion.contenido')
                ->join('unidades', 'unidades.id', 'contenido.unidad')
                ->where(function ($query) use ($busqueda) {
                    $query->where('evaluacion.titulo', 'LIKE', '%' . $busqueda . '%')
                        ->orWhere('contenido.titu_contenido', 'LIKE', '%' . $busqueda . '%');
                })
                ->where('unidades.modulo', $id)
                ->where('evaluacion.estado', 'ACTIVO')
                ->whereNotIn('evaluacion.origen_eval', ['M'])
                ->select('contenido.titu_contenido', 'evaluacion.*')
                ->orderBy('evaluacion.id', 'ASC')
                ->limit($limit)->offset($offset);
        } else {
            $respuesta = Evaluacion::join('contenido', 'contenido.id', 'evaluacion.contenido')
                ->join('unidades', 'unidades.id', 'contenido.unidad')
                ->where('unidades.modulo', $id)
                ->where('evaluacion.estado', 'ACTIVO')
                ->whereNotIn('evaluacion.origen_eval', ['M'])
                ->select('contenido.titu_contenido', 'evaluacion.*')
                ->orderBy('evaluacion.id', 'ASC')
                ->limit($limit)->offset($offset);
        }

        return $respuesta->get();
    }

    public static function GestionMod($busqueda, $pagina, $limit, $id)
    {
        if ($pagina == "1") {
            $offset = 0;
        } else {
            $pagina--;
            $offset = $pagina * $limit;
        }
        if (!empty($busqueda)) {
            $respuesta = Evaluacion::join('contenido_modulo', 'contenido_modulo.id', 'evaluacion.contenido')
                ->join('unidades_modulos', 'unidades_modulos.id', 'contenido.unidad')
                ->where(function ($query) use ($busqueda) {
                    $query->where('evaluacion.titulo', 'LIKE', '%' . $busqueda . '%')
                        ->orWhere('contenido_modulo.titu_contenido', 'LIKE', '%' . $busqueda . '%');
                })
                ->where('unidades_modulos.modulo', $id)
                ->where('evaluacion.estado', 'ACTIVO')
                ->where('evaluacion.origen_eval', 'M')
                ->select('contenido_modulo.titu_contenido', 'evaluacion.*')
                ->orderBy('evaluacion.id', 'ASC')
                ->limit($limit)->offset($offset);
        } else {
            $respuesta = Evaluacion::join('contenido_modulo', 'contenido_modulo.id', 'evaluacion.contenido')
                ->join('unidades_modulos', 'unidades_modulos.id', 'contenido_modulo.unidad')
                ->where('unidades_modulos.modulo', $id)
                ->where('evaluacion.estado', 'ACTIVO')
                ->where('evaluacion.origen_eval', 'M')
                ->select('contenido_modulo.titu_contenido', 'evaluacion.*')
                ->orderBy('evaluacion.id', 'ASC')
                ->limit($limit)->offset($offset);
        }

        return $respuesta->get();
    }

    public static function numero_de_registros($busqueda, $id)
    {
        if (!empty($busqueda)) {
            $respuesta = Evaluacion::join('contenido', 'contenido.id', 'evaluacion.contenido')
                ->join('unidades', 'unidades.id', 'contenido.unidad')
                ->where(function ($query) use ($busqueda) {
                    $query->where('evaluacion.titulo', 'LIKE', '%' . $busqueda . '%')
                        ->orWhere('unidades.des_unidad', 'LIKE', '%' . $busqueda . '%');
                })
                ->where('unidades.modulo', $id)
                ->whereNotIn('evaluacion.origen_eval', ['M'])
                ->orderBy('evaluacion.id', 'ASC');
        } else {
            $respuesta = Evaluacion::join('contenido', 'contenido.id', 'evaluacion.contenido')
                ->join('unidades', 'unidades.id', 'contenido.unidad')
                ->whereNotIn('evaluacion.origen_eval', ['M'])
                ->where('unidades.modulo', $id);
        }
        return $respuesta->count();
    }

    public static function numero_de_registrosMod($busqueda, $id)
    {
        if (!empty($busqueda)) {
            $respuesta = Evaluacion::join('contenido_modulo', 'contenido_modulo.id', 'evaluacion.contenido')
                ->join('unidades_modulos', 'unidades_modulos.id', 'contenido_modulo.unidad')
                ->where(function ($query) use ($busqueda) {
                    $query->where('evaluacion.titulo', 'LIKE', '%' . $busqueda . '%')
                        ->orWhere('unidades_modulos.des_unidad', 'LIKE', '%' . $busqueda . '%');
                })
                ->where('unidades_modulos.modulo', $id)
                ->where('evaluacion.origen_eval', 'M')
                ->orderBy('evaluacion.id', 'ASC');
        } else {
            $respuesta = Evaluacion::join('contenido_modulo', 'contenido_modulo.id', 'evaluacion.contenido')
                ->join('unidades_modulos', 'unidades_modulos.id', 'contenido_modulo.unidad')
                ->where('evaluacion.origen_eval', 'M')
                ->where('unidades_modulos.modulo', $id);
        }
        return $respuesta->count();
    }

}
