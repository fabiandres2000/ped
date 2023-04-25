<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class TemasModulos extends Model
{

    protected $table = 'contenido_modulo';
    protected $fillable = [
        'modulo',
        'periodo',
        'unidad',
        'titu_contenido',
        'tip_contenido',
        'hab_cont_didact',
        'estado',
        'objetivo_general',
        'docente',
    ];

    public static function temas($id)
    {
        if (Auth::user()->tipo_usuario == "Profesor") {
            $Usu = Auth::user()->id;

            $Temas = DB::connection("mysql")->select("SELECT conte.*, tdoc.visto_doc, tdoc.habilitado_doc,tdoc.ocultar_doc "
                . " FROM contenido_modulo conte  LEFT  JOIN (SELECT * FROM temas_mod_docentes WHERE doc = " . $Usu . " AND  grupo=".Session::get('GrupActual').") tdoc ON conte.id =tdoc.tema "
                . "LEFT JOIN orden_temas_modulos ot ON conte.id=ot.tema AND ot.docente=" . $Usu
                . " WHERE conte.unidad=" . $id . " AND conte.estado='ACTIVO' "
                . " AND (conte.docente = ''  OR conte.docente = " . $Usu . ")   ORDER BY ISNULL(conse), conse ASC");
        } else if (Auth::user()->tipo_usuario == "Estudiante") {
            $Usu = Session::get('USUDOCENTE');
            $Grup = Session::get('GrupoEst');

            $Temas = DB::connection("mysql")->select("SELECT conte.*, tdoc.visto_doc, tdoc.habilitado_doc,tdoc.ocultar_doc "
                . " FROM contenido_modulo conte  LEFT  JOIN (SELECT * FROM temas_mod_docentes WHERE doc = " . $Usu . " AND  grupo=".$Grup.") tdoc ON conte.id =tdoc.tema "
                . "LEFT JOIN orden_temas_modulos ot ON conte.id=ot.tema AND ot.docente=" . $Usu
                . " WHERE conte.unidad=" . $id . " AND conte.estado='ACTIVO' "
                . " AND (conte.docente = ''  OR conte.docente = " . $Usu . ")   ORDER BY ISNULL(conse), conse ASC");
        } else {
            $Temas = TemasModulos::where('unidad', $id)
                ->where('estado', 'ACTIVO')
                ->get();
        }

        return $Temas;
    }

    public static function VerfDel($id)
    {
        $VerfDel = TemasModulos::where('id', $id)
            ->where('id', '>=', 1)
            ->where('id', '<=', 630)
            ->get();

        return $VerfDel;
    }


    public static function listarTemasxDocente($Doce)
    {
        $Temas = DB::connection("mysql")->select("select concat(asig.nombre,' - Grado ',modu.grado_modulo,'°') as asignatura, conte.titu_contenido, conte.id, conte.modulo,conte.docente from contenido_modulo conte "
            . "left join (SELECT * FROM mod_prof WHERE profesor = " . $Doce . ")  ap "
            . "on conte.modulo=ap.grado "
            . "left join grados_modulos modu on conte.modulo=modu.id "
            . "left join modulos_transversales asig on modu.modulo=asig.id "
            . "where  conte.docente=" . $Doce . " and  conte.estado='ACTIVO' and ap.grado is null");

        return $Temas;
    }

    public static function VerificarAsig($data)
    {
        $Exite = "no";
        foreach ($data["idTema"] as $key => $val) {

            if ($data["TemSel"][$key] == "si") {
                $Temas = TemasModulos::where('id', $data["idTema"][$key])
                    ->where('estado', 'ACTIVO')
                    ->first();

                $AsigProf = ModProf::where("grado", $Temas->modulo)
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

        foreach ($data["idTema"] as $key => $val) {
            if ($data["TemSel"][$key] == "si") {
                $respuesta = TemasModulos::where(['id' => $data["idTema"][$key], "docente" => $data["docenteOld"]])->update([
                    'docente' => $data['docenteReasig'],
                ]);
            }
        }

        return $respuesta;
    }

    public static function LisTemasProg($id)
    {

        $PorcAsig = 0;
        $PorPeri = 0;
        $SumTemaVis = 0;
        $SumTema = 0;
        $Grup = Session::get('GrupActual');


        if (Auth::user()->tipo_usuario == "Profesor") {
            $Usu = Auth::user()->id;

            $TemasxPer = DB::connection("mysql")->select("SELECT conte.*, tdoc.visto_doc, tdoc.habilitado_doc,tdoc.ocultar_doc  FROM contenido_modulo conte  LEFT  JOIN (SELECT * FROM temas_mod_docentes WHERE doc = " . $Usu . ") tdoc ON conte.id =tdoc.tema WHERE conte.modulo=" . $id . " AND conte.estado='ACTIVO' AND (conte.docente = ''  OR conte.docente = " . $Usu . ")   GROUP BY conte.periodo");
        } else if (Auth::user()->tipo_usuario == "Estudiante") {
            $Usu = Session::get('USUDOCENTE');

            $TemasxPer = DB::connection("mysql")->select("SELECT conte.*, tdoc.visto_doc, tdoc.habilitado_doc,tdoc.ocultar_doc  FROM contenido_modulo conte  LEFT  JOIN (SELECT * FROM temas_mod_docentes WHERE doc = " . $Usu . " AND  grupo=".$Grup.") tdoc ON conte.id =tdoc.tema WHERE conte.modulo=" . $id . " AND conte.estado='ACTIVO' AND (conte.docente = ''  OR conte.docente = " . $Usu . ")   GROUP BY conte.periodo");
        } else {
            $TemasxPer = TemasModulos::where('modulo', $id)
                ->where('estado', 'ACTIVO')
                ->groupBy('contenido_modulo.periodo')
                ->get();
        }

        foreach ($TemasxPer as $TxP) {
            $Periodo = \App\PeriodosModTransv::BuscarPerido($TxP->periodo);
            $PorcPer = $Periodo->avance_perido;

            if (Auth::user()->tipo_usuario == "Profesor") {
                $Usu = Auth::user()->id;

                $Temas = DB::connection("mysql")->select("SELECT conte.*, tdoc.visto_doc, tdoc.habilitado_doc,tdoc.ocultar_doc  FROM contenido_modulo conte  LEFT  JOIN (SELECT * FROM temas_mod_docentes WHERE doc = " . $Usu . ") tdoc ON conte.id =tdoc.tema WHERE conte.periodo=" . $TxP->periodo . " AND conte.modulo=" . $id . " AND conte.estado='ACTIVO' AND (conte.docente = ''  OR conte.docente = " . $Usu . ")  ORDER BY conte.id ASC");
            } else if (Auth::user()->tipo_usuario == "Estudiante") {
                $Usu = Session::get('USUDOCENTE');
                $Temas = DB::connection("mysql")->select("SELECT conte.*, tdoc.visto_doc, tdoc.habilitado_doc,tdoc.ocultar_doc  FROM contenido_modulo conte  LEFT  JOIN (SELECT * FROM temas_mod_docentes WHERE doc = " . $Usu . " AND  grupo=".$Grup.") tdoc ON conte.id =tdoc.tema WHERE conte.periodo=" . $TxP->periodo . " AND conte.modulo=" . $id . " AND conte.estado='ACTIVO' AND (conte.docente = ''  OR conte.docente = " . $Usu . ")  ORDER BY conte.id ASC");
            } else {
                $Temas = TemasModulos::where('modulo', $id)
                    ->leftJoin('temas_mod_docentes', 'contenido_modulo.id', '=', 'temas_mod_docentes.tema')
                    ->where('periodo', $TxP->periodo)
                    ->where('estado', 'ACTIVO')
                    ->get();
            }

            foreach ($Temas as $Tem) {
                $visto = "";

                if ($Tem->visto_doc == null) {
                    $visto = "NO";
                } else {
                    $visto = $Tem->visto_doc;
                }

                if ($visto == "SI") {
                    $SumTemaVis++;
                }

                $SumTema++;
            }

            $PorPeri = ($SumTemaVis * 100) / $SumTema;
            $PorPeri = round($PorPeri);

            $Porc = $PorcPer / 100;

            $porcToPer = $PorPeri * $Porc;
            //            print_r($TxP->periodo.'->'.$SumTema.'->'.$porcToPer.'- ');
            $SumTemaVis = 0;
            $SumTema = 0;
            $PorcAsig = $PorcAsig + $porcToPer;
        }
        //        die;

        return $PorcAsig;
    }

    public static function listarPond($id)
    {
        $Usu = Auth::user()->id;
        $Temas = TemasModulos::leftJoin('pond_temas', 'contenido.id', '=', 'pond_temas.tema', 'pond_temas.docente', '=', $Usu)
            ->where('contenido.unidad', $id)
            ->where('contenido.estado', "ACTIVO")
            ->select('contenido.id', 'contenido.titu_contenido', 'pond_temas.porcentaje', 'pond_temas.tponde')
            ->get();
        return $Temas;
    }

    public static function LisTemasProgEst($id, $Usu)
    {

        $PorcAsig = 0;
        $PorPeri = 0;
        $SumTemaVis = 0;
        $SumTema = 0;
            $Grup = Session::get('GrupoEst');

        $TemasxPer = DB::connection("mysql")->select("SELECT conte.*, tdoc.visto_doc, tdoc.habilitado_doc FROM contenido_modulo conte  LEFT  JOIN (SELECT * FROM temas_mod_docentes WHERE doc = " . $Usu . " AND grupo=".$Grup.") tdoc ON conte.id =tdoc.tema WHERE conte.modulo=" . $id . " AND conte.estado='ACTIVO' AND (conte.docente = ''  OR conte.docente = " . $Usu . ") GROUP BY conte.periodo");

        foreach ($TemasxPer as $TxP) {
            $Periodo = \App\PeriodosModTransv::BuscarPerido($TxP->periodo);
            $PorcPer = $Periodo->avance_perido;

            $Temas = DB::connection("mysql")->select("SELECT conte.*, tdoc.visto_doc, tdoc.habilitado_doc FROM contenido_modulo conte  LEFT  JOIN (SELECT * FROM temas_mod_docentes WHERE doc = " . $Usu . " AND grupo=".$Grup.") tdoc ON conte.id =tdoc.tema WHERE conte.periodo=" . $TxP->periodo . " AND conte.modulo=" . $id . " AND conte.estado='ACTIVO' AND (conte.docente = ''  OR conte.docente = " . $Usu . ")  ORDER BY conte.id ASC");

            foreach ($Temas as $Tem) {
                $visto = "";

                if ($Tem->visto_doc == null) {
                    $visto = "NO";
                } else {
                    $visto = $Tem->visto_doc;
                }

                if ($visto == "SI") {
                    $SumTemaVis++;
                }

                $SumTema++;
            }

            $PorPeri = ($SumTemaVis * 100) / $SumTema;
            $PorPeri = round($PorPeri);

            $Porc = $PorcPer / 100;

            $porcToPer = $PorPeri * $Porc;

            $SumTemaVis = 0;
            $SumTema = 0;
            $PorcAsig = $PorcAsig + $porcToPer;
        }

        return $PorcAsig;
    }

    public static function Gestion($busqueda, $pagina, $limit, $Asig)
    {
        if ($pagina == "1") {
            $offset = 0;
        } else {
            $pagina--;
            $offset = $pagina * $limit;
        }

        $Usu = Auth::user()->id;

        if (!empty($busqueda)) {
            if (!empty($Asig)) {
                if (Auth::user()->tipo_usuario == "Profesor") {
                    $respuesta = TemasModulos::join('unidades_modulos', 'unidades_modulos.id', 'contenido_modulo.unidad')
                        ->join('grados_modulos', 'grados_modulos.id', 'unidades_modulos.modulo')
                        ->join('mod_prof', 'mod_prof.grado', 'grados_modulos.id')
                        ->join('modulos_transversales', 'modulos_transversales.id', 'grados_modulos.modulo')
                        ->where('grados_modulos.id', $Asig)
                        ->where('mod_prof.profesor', Auth::user()->id)
                        ->where('modulos_transversales.estado', 'ACTIVO')
                        ->where('contenido_modulo.estado', 'ACTIVO')
                        ->where(function ($query) use ($busqueda) {
                            $query->where('contenido_modulo.titu_contenido', 'LIKE', '%' . $busqueda . '%')
                                ->orWhere('contenido_modulo.tip_contenido', 'LIKE', '%' . $busqueda . '%')
                                ->orWhere('unidades_modulos.nom_unidad', 'LIKE', '%' . $busqueda . '%')
                                ->orWhere('modulos_transversales.nombre', 'LIKE', '%' . $busqueda . '%');
                        })
                        ->where(function ($query) use ($Usu) {
                            $query->where('contenido_modulo.docente', "")
                                ->orWhere('contenido_modulo.docente', $Usu);
                        })
                        ->select('contenido_modulo.*', 'modulos_transversales.nombre', 'grados_modulos.grado_modulo', 'unidades_modulos.nom_unidad', 'unidades_modulos.des_unidad')
                        ->groupBy("contenido_modulo.id")
                        ->orderBy('modulos_transversales.nombre', 'ASC')
                        ->limit($limit)->offset($offset);
                } else {
                    $respuesta = TemasModulos::join('unidades_modulos', 'unidades_modulos.id', 'contenido_modulo.unidad')
                        ->join('grados_modulos', 'grados_modulos.id', 'unidades_modulos.modulo')
                        ->join('modulos_transversales', 'modulos_transversales.id', 'grados_modulos.modulo')
                        ->where('grados_modulos.id', $Asig)
                        ->where('modulos_transversales.estado', 'ACTIVO')
                        ->where('contenido_modulo.estado', 'ACTIVO')
                        ->where(function ($query) use ($busqueda) {
                            $query->where('contenido_modulo.titu_contenido', 'LIKE', '%' . $busqueda . '%')
                                ->orWhere('contenido_modulo.tip_contenido', 'LIKE', '%' . $busqueda . '%')
                                ->orWhere('unidades_modulos.nom_unidad', 'LIKE', '%' . $busqueda . '%')
                                ->orWhere('modulos_transversales.nombre', 'LIKE', '%' . $busqueda . '%');
                        })
                        ->select('contenido_modulo.*', 'modulos_transversales.nombre', 'grados_modulos.grado_modulo', 'unidades_modulos.nom_unidad', 'unidades_modulos.des_unidad')
                        ->orderBy('modulos_transversales.nombre', 'ASC')
                        ->limit($limit)->offset($offset);
                }
            } else {
                if (Auth::user()->tipo_usuario == "Profesor") {
                    $respuesta = TemasModulos::join('unidades_modulos', 'unidades_modulos.id', 'contenido_modulo.unidad')
                        ->join('grados_modulos', 'grados_modulos.id', 'unidades_modulos.modulo')
                        ->join('mod_prof', 'mod_prof.grado', 'grados_modulos.id')
                        ->join('modulos_transversales', 'modulos_transversales.id', 'grados_modulos.modulo')
                        ->where('mod_prof.profesor', Auth::user()->id)
                        ->where('modulos_transversales.estado', 'ACTIVO')
                        ->where('contenido_modulo.estado', 'ACTIVO')
                        ->where(function ($query) use ($busqueda) {
                            $query->where('contenido_modulo.titu_contenido', 'LIKE', '%' . $busqueda . '%')
                                ->orWhere('contenido_modulo.tip_contenido', 'LIKE', '%' . $busqueda . '%')
                                ->orWhere('unidades_modulos.nom_unidad', 'LIKE', '%' . $busqueda . '%')
                                ->orWhere('modulos_transversales.nombre', 'LIKE', '%' . $busqueda . '%');
                        })
                        ->where(function ($query) use ($Usu) {
                            $query->where('contenido_modulo.docente', "")
                                ->orWhere('contenido_modulo.docente', $Usu);
                        })
                        ->select('contenido_modulo.*', 'modulos_transversales.nombre', 'grados_modulos.grado_modulo', 'unidades_modulos.nom_unidad', 'unidades_modulos.des_unidad')
                        ->groupBy("contenido_modulo.id")
                        ->orderBy('modulos_transversales.nombre', 'ASC')
                        ->limit($limit)->offset($offset);
                } else {
                    $respuesta = TemasModulos::join('unidades_modulos', 'unidades_modulos.id', 'contenido_modulo.unidad')
                        ->join('grados_modulos', 'grados_modulos.id', 'unidades_modulos.modulo')
                        ->join('modulos_transversales', 'modulos_transversales.id', 'grados_modulos.modulo')
                        ->where('modulos_transversales.estado', 'ACTIVO')
                        ->where('contenido_modulo.estado', 'ACTIVO')
                        ->where(function ($query) use ($busqueda) {
                            $query->where('contenido_modulo.titu_contenido', 'LIKE', '%' . $busqueda . '%')
                                ->orWhere('contenido_modulo.tip_contenido', 'LIKE', '%' . $busqueda . '%')
                                ->orWhere('unidades_modulos.nom_unidad', 'LIKE', '%' . $busqueda . '%')
                                ->orWhere('modulos_transversales.nombre', 'LIKE', '%' . $busqueda . '%');
                        })
                        ->select('contenido_modulo.*', 'modulos_transversales.nombre', 'grados_modulos.grado_modulo', 'unidades_modulos.nom_unidad', 'unidades_modulos.des_unidad')
                        ->orderBy('modulos_transversales.nombre', 'ASC')
                        ->limit($limit)->offset($offset);
                }
            }
        } else {
            if (!empty($Asig)) {
                if (Auth::user()->tipo_usuario == "Profesor") {
                    $respuesta = TemasModulos::join('unidades_modulos', 'unidades_modulos.id', 'contenido_modulo.unidad')
                        ->join('grados_modulos', 'grados_modulos.id', 'unidades_modulos.modulo')
                        ->join('mod_prof', 'mod_prof.grado', 'grados_modulos.id')
                        ->join('modulos_transversales', 'modulos_transversales.id', 'grados_modulos.modulo')
                        ->where('grados_modulos.id', $Asig)
                        ->where('mod_prof.profesor', Auth::user()->id)
                        ->where('modulos_transversales.estado', 'ACTIVO')
                        ->where('contenido_modulo.estado', 'ACTIVO')
                        ->where(function ($query) use ($Usu) {
                            $query->where('contenido_modulo.docente', "")
                                ->orWhere('contenido_modulo.docente', $Usu);
                        })
                        ->select('contenido_modulo.*', 'modulos_transversales.nombre', 'grados_modulos.grado_modulo', 'unidades_modulos.nom_unidad', 'unidades_modulos.des_unidad')
                        ->groupBy("contenido_modulo.id")
                        ->orderBy('modulos_transversales.nombre', 'ASC')
                        ->limit($limit)->offset($offset);
                } else {
                    $respuesta = TemasModulos::join('unidades_modulos', 'unidades_modulos.id', 'contenido_modulo.unidad')
                        ->join('grados_modulos', 'grados_modulos.id', 'unidades_modulos.modulo')
                        ->join('modulos_transversales', 'modulos_transversales.id', 'grados_modulos.modulo')
                        ->where('grados_modulos.id', $Asig)
                        ->where('modulos_transversales.estado', 'ACTIVO')
                        ->where('contenido_modulo.estado', 'ACTIVO')
                        ->select('contenido_modulo.*', 'modulos_transversales.nombre', 'grados_modulos.grado_modulo', 'unidades_modulos.nom_unidad', 'unidades_modulos.des_unidad')
                        ->orderBy('modulos_transversales.nombre', 'ASC')
                        ->limit($limit)->offset($offset);
                }
            } else {
                if (Auth::user()->tipo_usuario == "Profesor") {

                    $respuesta = TemasModulos::join('unidades_modulos', 'unidades_modulos.id', 'contenido_modulo.unidad')
                        ->join('grados_modulos', 'grados_modulos.id', 'unidades_modulos.modulo')
                        ->join('mod_prof', 'mod_prof.grado', 'grados_modulos.id')
                        ->join('modulos_transversales', 'modulos_transversales.id', 'grados_modulos.modulo')
                        ->where('mod_prof.profesor', Auth::user()->id)
                        ->where('modulos_transversales.estado', 'ACTIVO')
                        ->where('contenido_modulo.estado', 'ACTIVO')
                        ->where(function ($query) use ($Usu) {
                            $query->where('contenido_modulo.docente', "")
                                ->orWhere('contenido_modulo.docente', $Usu);
                        })
                        ->select('contenido_modulo.*', 'modulos_transversales.nombre', 'grados_modulos.grado_modulo', 'unidades_modulos.nom_unidad', 'unidades_modulos.des_unidad')
                        ->groupBy("contenido_modulo.id")
                        ->orderBy('modulos_transversales.nombre', 'ASC')
                        ->limit($limit)->offset($offset);
                } else {
                    $respuesta = TemasModulos::join('unidades_modulos', 'unidades_modulos.id', 'contenido_modulo.unidad')
                        ->join('grados_modulos', 'grados_modulos.id', 'unidades_modulos.modulo')
                        ->join('modulos_transversales', 'modulos_transversales.id', 'grados_modulos.modulo')
                        ->where('modulos_transversales.estado', 'ACTIVO')
                        ->where('contenido_modulo.estado', 'ACTIVO')
                        ->select('contenido_modulo.*', 'modulos_transversales.nombre', 'grados_modulos.grado_modulo', 'unidades_modulos.nom_unidad', 'unidades_modulos.des_unidad')
                        ->orderBy('modulos_transversales.nombre', 'ASC')
                        ->limit($limit)->offset($offset);
                }
            }
        }

        return $respuesta->get();
    }

    public static function numero_de_registros($busqueda, $Asig)
    {
        $Usu = Auth::user()->id;
        if (!empty($busqueda)) {
            if (!empty($Asig)) {
                if (Auth::user()->tipo_usuario == "Profesor") {
                    $respuesta = TemasModulos::join('unidades_modulos', 'unidades_modulos.id', 'contenido_modulo.unidad')
                        ->join('grados_modulos', 'grados_modulos.id', 'unidades_modulos.modulo')
                        ->join('mod_prof', 'mod_prof.grado', 'grados_modulos.id')
                        ->join('modulos_transversales', 'modulos_transversales.id', 'grados_modulos.modulo')
                        ->where('grados_modulos.id', $Asig)
                        ->where('mod_prof.profesor', Auth::user()->id)
                        ->where('modulos_transversales.estado', 'ACTIVO')
                        ->where('contenido_modulo.estado', 'ACTIVO')
                        ->where(function ($query) use ($busqueda) {
                            $query->where('contenido_modulo.titu_contenido', 'LIKE', '%' . $busqueda . '%')
                                ->orWhere('contenido_modulo.tip_contenido', 'LIKE', '%' . $busqueda . '%')
                                ->orWhere('unidades_modulos.nom_unidad', 'LIKE', '%' . $busqueda . '%')
                                ->orWhere('modulos_transversales.nombre', 'LIKE', '%' . $busqueda . '%');
                        })
                        ->where(function ($query) use ($Usu) {
                            $query->where('contenido_modulo.docente', "")
                                ->orWhere('contenido_modulo.docente', $Usu);
                        })
                        ->select('contenido_modulo.*', 'modulos_transversales.nombre', 'grados_modulos.grado_modulo', 'unidades_modulos.nom_unidad', 'unidades_modulos.des_unidad')
                        ->groupBy("contenido_modulo.id")
                        ->orderBy('modulos_transversales.nombre', 'ASC');
                } else {
                    $respuesta = TemasModulos::join('unidades_modulos', 'unidades_modulos.id', 'contenido_modulo.unidad')
                        ->join('grados_modulos', 'grados_modulos.id', 'unidades_modulos.modulo')
                        ->join('modulos_transversales', 'modulos_transversales.id', 'grados_modulos.modulo')
                        ->where('grados_modulos.id', $Asig)
                        ->where('modulos_transversales.estado', 'ACTIVO')
                        ->where('contenido_modulo.estado', 'ACTIVO')
                        ->where(function ($query) use ($busqueda) {
                            $query->where('contenido_modulo.titu_contenido', 'LIKE', '%' . $busqueda . '%')
                                ->orWhere('contenido_modulo.tip_contenido', 'LIKE', '%' . $busqueda . '%')
                                ->orWhere('unidades_modulos.nom_unidad', 'LIKE', '%' . $busqueda . '%')
                                ->orWhere('modulos_transversales.nombre', 'LIKE', '%' . $busqueda . '%');
                        })
                        ->select('grados_modulos.*', 'modulos_transversales.nombre', 'grados_modulos.grado_modulo', 'unidades_modulos.nom_unidad', 'unidades_modulos.des_unidad')
                        ->orderBy('modulos_transversales.nombre', 'ASC');
                }
            } else {
                if (Auth::user()->tipo_usuario == "Profesor") {
                    $respuesta = TemasModulos::join('unidades_modulos', 'unidades_modulos.id', 'contenido_modulo.unidad')
                        ->join('grados_modulos', 'grados_modulos.id', 'unidades_modulos.modulo')
                        ->join('mod_prof', 'mod_prof.grado', 'grados_modulos.id')
                        ->join('modulos_transversales', 'modulos_transversales.id', 'grados_modulos.modulo')
                        ->where('mod_prof.profesor', Auth::user()->id)
                        ->where('modulos_transversales.estado', 'ACTIVO')
                        ->where('contenido_modulo.estado', 'ACTIVO')
                        ->where(function ($query) use ($busqueda) {
                            $query->where('contenido_modulo.titu_contenido', 'LIKE', '%' . $busqueda . '%')
                                ->orWhere('contenido_modulo.tip_contenido', 'LIKE', '%' . $busqueda . '%')
                                ->orWhere('unidades_modulos.nom_unidad', 'LIKE', '%' . $busqueda . '%')
                                ->orWhere('modulos_transversales.nombre', 'LIKE', '%' . $busqueda . '%');
                        })
                        ->where(function ($query) use ($Usu) {
                            $query->where('contenido_modulo.docente', "")
                                ->orWhere('contenido_modulo.docente', $Usu);
                        })
                        ->select('contenido_modulo.*', 'modulos_transversales.nombre', 'grados_modulos.grado_modulo', 'unidades_modulos.nom_unidad', 'unidades_modulos.des_unidad')
                        ->groupBy("contenido_modulo.id")
                        ->orderBy('modulos_transversales.nombre', 'ASC');
                } else {
                    $respuesta = TemasModulos::join('unidades_modulos', 'unidades_modulos.id', 'contenido_modulo.unidad')
                        ->join('grados_modulos', 'grados_modulos.id', 'unidades_modulos.modulo')
                        ->join('modulos_transversales', 'modulos_transversales.id', 'grados_modulos.modulo')
                        ->where('modulos_transversales.estado', 'ACTIVO')
                        ->where('contenido_modulo.estado', 'ACTIVO')
                        ->where(function ($query) use ($busqueda) {
                            $query->where('contenido_modulo.titu_contenido', 'LIKE', '%' . $busqueda . '%')
                                ->orWhere('contenido_modulo.tip_contenido', 'LIKE', '%' . $busqueda . '%')
                                ->orWhere('unidades_modulos.nom_unidad', 'LIKE', '%' . $busqueda . '%')
                                ->orWhere('modulos_transversales.nombre', 'LIKE', '%' . $busqueda . '%');
                        })
                        ->select('grados_modulos.*', 'modulos_transversales.nombre', 'grados_modulos.grado_modulo', 'unidades_modulos.nom_unidad', 'unidades_modulos.des_unidad')
                        ->orderBy('modulos_transversales.nombre', 'ASC');
                }
            }
        } else {
            if (!empty($Asig)) {
                if (Auth::user()->tipo_usuario == "Profesor") {
                    $respuesta = TemasModulos::join('unidades_modulos', 'unidades_modulos.id', 'contenido_modulo.unidad')
                        ->join('grados_modulos', 'grados_modulos.id', 'unidades_modulos.modulo')
                        ->join('mod_prof', 'mod_prof.grado', 'grados_modulos.id')
                        ->join('modulos_transversales', 'modulos_transversales.id', 'grados_modulos.modulo')
                        ->where('grados_modulos.id', $Asig)
                        ->where('mod_prof.profesor', Auth::user()->id)
                        ->where('modulos_transversales.estado', 'ACTIVO')
                        ->where('contenido_modulo.estado', 'ACTIVO')
                        ->where(function ($query) use ($Usu) {
                            $query->where('contenido_modulo.docente', "")
                                ->orWhere('contenido_modulo.docente', $Usu);
                        })
                        ->select('contenido_modulo.*', 'modulos_transversales.nombre', 'grados_modulos.grado_modulo', 'unidades_modulos.nom_unidad', 'unidades_modulos.des_unidad')
                        ->groupBy("contenido_modulo.id")
                        ->orderBy('modulos_transversales.nombre', 'ASC');
                } else {
                    $respuesta = TemasModulos::join('unidades_modulos', 'unidades_modulos.id', 'contenido_modulo.unidad')
                        ->join('grados_modulos', 'grados_modulos.id', 'unidades_modulos.modulo')
                        ->join('modulos_transversales', 'modulos_transversales.id', 'grados_modulos.modulo')
                        ->where('grados_modulos.id', $Asig)
                        ->where('modulos_transversales.estado', 'ACTIVO')
                        ->where('contenido_modulo.estado', 'ACTIVO')
                        ->select('contenido_modulo.*', 'modulos_transversales.nombre', 'grados_modulos.grado_modulo', 'unidades_modulos.nom_unidad', 'unidades_modulos.des_unidad')
                        ->orderBy('modulos_transversales.nombre', 'ASC');
                }
            } else {
                if (Auth::user()->tipo_usuario == "Profesor") {

                    $respuesta = TemasModulos::join('unidades_modulos', 'unidades_modulos.id', 'contenido_modulo.unidad')
                        ->join('grados_modulos', 'grados_modulos.id', 'unidades_modulos.modulo')
                        ->join('mod_prof', 'mod_prof.grado', 'grados_modulos.id')
                        ->join('modulos_transversales', 'modulos_transversales.id', 'grados_modulos.modulo')
                        ->where('mod_prof.profesor', Auth::user()->id)
                        ->where('modulos_transversales.estado', 'ACTIVO')
                        ->where('contenido_modulo.estado', 'ACTIVO')
                        ->where(function ($query) use ($Usu) {
                            $query->where('contenido_modulo.docente', "")
                                ->orWhere('contenido_modulo.docente', $Usu);
                        })
                        ->select('contenido_modulo.*', 'modulos_transversales.nombre', 'grados_modulos.grado_modulo', 'unidades_modulos.nom_unidad', 'unidades_modulos.des_unidad')
                        ->groupBy("contenido_modulo.id")
                        ->orderBy('modulos_transversales.nombre', 'ASC');
                } else {
                    $respuesta = TemasModulos::join('unidades_modulos', 'unidades_modulos.id', 'contenido_modulo.unidad')
                        ->join('grados_modulos', 'grados_modulos.id', 'unidades_modulos.modulo')
                        ->join('modulos_transversales', 'modulos_transversales.id', 'grados_modulos.modulo')
                        ->where('modulos_transversales.estado', 'ACTIVO')
                        ->where('contenido_modulo.estado', 'ACTIVO')
                        ->select('contenido_modulo.*', 'modulos_transversales.nombre', 'grados_modulos.grado_modulo', 'unidades_modulos.nom_unidad', 'unidades_modulos.des_unidad')
                        ->orderBy('modulos_transversales.nombre', 'ASC');
                }
            }
        }
        return $respuesta->count();
    }

    public static function BuscarTema($id)
    {

        return TemasModulos::findOrFail($id);
    }

    public static function Listar()
    {

        return TemasModulos::where("estado", "ACTIVO")->get();
    }

    public static function BuscarUnidadTema($id)
    {

        return TemasModulos::where('unidad', $id)
            ->where('estado', 'ACTIVO')
            ->get();
    }

    public static function LisTemas($id)
    {
        if (Auth::user()->tipo_usuario == "Profesor") {
            $Usu = Auth::user()->id;

            $Temas = DB::connection("mysql")->select("SELECT conte.*, tdoc.visto_doc, tdoc.habilitado_doc FROM contenido_modulo conte  LEFT  JOIN (SELECT * FROM temas_mod_docentes WHERE doc = " . $Usu . " AND  grupo=".Session::get('GrupActual').") tdoc ON conte.id =tdoc.tema WHERE conte.modulo=" . $id . " AND conte.estado='ACTIVO' AND (conte.docente = ''  OR conte.docente = " . $Usu . ") ORDER BY conte.id ASC");

            return $Temas;
        } else if (Auth::user()->tipo_usuario == "Estudiante") {
            $Usu = Session::get('USUDOCENTE');
            $Grup = Session::get('GrupoEst');

            $Temas = DB::connection("mysql")->select("SELECT conte.*, tdoc.visto_doc, tdoc.habilitado_doc FROM contenido_modulo conte  LEFT  JOIN (SELECT * FROM temas_mod_docentes WHERE doc = " . $Usu . " AND  grupo=".$Grup.") tdoc ON conte.id =tdoc.tema WHERE conte.modulo=" . $id . " AND conte.estado='ACTIVO' AND (conte.docente = ''  OR conte.docente = " . $Usu . ") ORDER BY conte.id ASC");
            return $Temas;
        } else {
            $Temas = TemasModulos::where('modulo', $id)
                ->where('contenido_modulo.estado', 'ACTIVO')
                ->where("contenido_modulo.estado", "ACTIVO")
                ->get();
            return $Temas;
        }
    }

    public static function GuardarTipCont($datos)
    {
        $Doc = "";
        if (Auth::user()->tipo_usuario == "Profesor") {
            $Doc = Auth::user()->id;
        }
        return TemasModulos::create([
            'modulo' => $datos['modulo'],
            'periodo' => $datos['periodo'],
            'unidad' => $datos['unidad'],
            'titu_contenido' => $datos['titu_contenido'],
            'tip_contenido' => $datos['tip_contenido'],
            'hab_cont_didact' => $datos['hab_cont_didact'],
            'estado' => 'ACTIVO',
            'objetivo_general' => $datos['objetivo_general'],
            'docente' => ''
        ]);
    }

    public static function modificar($datos, $id)
    {
        $respuesta = TemasModulos::where(['id' => $id])->update([
            'modulo' => $datos['modulo'],
            'periodo' => $datos['periodo'],
            'unidad' => $datos['unidad'],
            'titu_contenido' => $datos['titu_contenido'],
            'tip_contenido' => $datos['tip_contenido'],
            'hab_cont_didact' => $datos['hab_cont_didact'],
            'objetivo_general' => $datos['objetivo_general'],
        ]);
        return $respuesta;
    }

    public static function editarestado($id, $estado)
    {
        $Respuesta = TemasModulos::where('id', $id)->update([
            'estado' => $estado,
        ]);
        return $Respuesta;
    }

    public static function cambiarvisto($id, $estado)
    {
        $Respuesta = TemasModulos::where('id', $id)->update([
            'visto' => $estado,
        ]);
        // dd($Respuesta);die;
        return $Respuesta;
    }

    public static function cambiarHabil($id, $estado)
    {
        if ($estado == "Habi") {
            $estado = "SI";
        } else {
            $estado = "NO";
        }
        $Respuesta = TemasModulos::where('id', $id)->update([
            'habilitado' => $estado,
        ]);
        // dd($Respuesta);die;
        return $Respuesta;
    }
}
