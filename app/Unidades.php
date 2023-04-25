<?php

namespace App;

use Illuminate\Support\Facades\Auth;
use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

class Unidades extends Model
{
    protected $table = 'unidades';
    protected $fillable = [
        'periodo',
        'modulo',
        'nom_unidad',
        'des_unidad',
        'estado',
        'introduccion',
        'origunidad',
        'docente',
    ];

    public static function unidad($id)
    {
        if (Auth::user()->tipo_usuario == "Profesor") {
            $Usu = Auth::user()->id;
            $Unidade = Unidades::where('modulo', $id)
                ->where(function ($query) use ($Usu) {
                    $query->where('unidades.docente', "")
                        ->orWhere('unidades.docente', $Usu);
                })
                ->where('estado','ACTIVO')
                ->orderBy('periodo', 'ASC')
                ->get();
            return $Unidade;
        } else if (Auth::user()->tipo_usuario == "Estudiante") {
            $Usu = Session::get('USUDOCENTE');
            $Unidade = Unidades::where('modulo', $id)
                ->where(function ($query) use ($Usu) {
                    $query->where('unidades.docente', "")
                        ->orWhere('unidades.docente', $Usu);
                })
                ->where('estado','ACTIVO')
                ->orderBy('periodo', 'ASC')
                ->get();
            return $Unidade;
        } else {
            $Unidade = Unidades::where('modulo', $id)
            ->where('estado','ACTIVO')
                ->orderBy('periodo', 'ASC')
                ->get();
            return $Unidade;
        }
    }

    public static function VerfDel($id)
    {
        $VerfDel = Unidades::where('id', $id)
        ->where('id', '>=', 1)
        ->where('id', '<=', 443)
        ->get();
       
        return $VerfDel;
    }


    public static function listar($id)
    {
        if (Auth::user()->tipo_usuario == "Profesor") {
            $Usu = Auth::user()->id;
            $Unidade = Unidades::where('periodo', $id)
                ->where('unidades.estado', 'ACTIVO')
                ->where(function ($query) use ($Usu) {
                    $query->where('unidades.docente', "")
                        ->orWhere('unidades.docente', $Usu);
                })
                ->get();
        } else {
            $Unidade = Unidades::where('periodo', $id)
                ->where('unidades.estado', 'ACTIVO')
                ->get();
        }

        return $Unidade;
    }
    public static function listarUnidadesxDocente($Doce)
    {

        $Unidade = DB::connection("mysql")->select("SELECT CONCAT(asig.nombre,' - Grado ',modu.grado_modulo,'°') AS asignatura, und.nom_unidad, und.des_unidad,  und.id, und.modulo,und.docente FROM unidades und"
            . " LEFT JOIN (SELECT * FROM asig_prof WHERE profesor = " . $Doce . ")  ap"
            . " ON und.modulo=ap.grado "
            . " LEFT JOIN modulos modu ON und.modulo=modu.id"
            . " LEFT JOIN asignaturas asig ON modu.asignatura=asig.id"
            . " WHERE  und.docente=" . $Doce . " AND und.estado='ACTIVO' AND ap.grado IS NULL");

        return $Unidade;
    }

    public static function listarPond($id)
    {
        $Usu = Auth::user()->id;
        $Unidade = Unidades::leftJoin('pond_unidades', 'unidades.id', '=', 'pond_unidades.unidad', 'pond_unidades.docente', '=', $Usu)
            ->where('unidades.periodo', $id)
            ->where('unidades.estado', "ACTIVO")
            ->select('unidades.id', 'unidades.des_unidad', 'pond_unidades.porcentaje', 'pond_unidades.tponde')
            ->get();
        return $Unidade;
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
                    $respuesta = Unidades::join('modulos', 'modulos.id', 'unidades.modulo')
                        ->join('periodos', 'periodos.id', 'unidades.periodo')
                        ->join('asig_prof', 'asig_prof.grado', 'modulos.id')
                        ->join('asignaturas', 'asignaturas.id', 'modulos.asignatura')
                        ->where('asig_prof.profesor', Auth::user()->id)
                        ->where('unidades.estado', 'ACTIVO')
                        ->where('asignaturas.estado', 'ACTIVO')
                        ->where('modulos.id', $Asig)
                        ->where(function ($query) use ($busqueda) {
                            $query->where('unidades.nom_unidad', 'LIKE', '%' . $busqueda . '%')
                                ->orWhere('unidades.des_unidad', 'LIKE', '%' . $busqueda . '%')
                                ->orWhere('asignaturas.nombre', 'LIKE', '%' . $busqueda . '%');
                        })
                        ->where(function ($query) use ($Usu) {
                            $query->where('unidades.docente', "")
                                ->orWhere('unidades.docente', $Usu);
                        })
                        ->select('unidades.*', 'asignaturas.nombre', 'periodos.des_periodo', 'modulos.grado_modulo')
                        ->groupBy("unidades.id")
                        ->orderBy('nom_unidad', 'ASC')
                        ->limit($limit)->offset($offset);
                } else {
                    $respuesta = Unidades::join('modulos', 'modulos.id', 'unidades.modulo')
                        ->join('periodos', 'periodos.id', 'unidades.periodo')
                        ->join('asignaturas', 'asignaturas.id', 'modulos.asignatura')
                        ->where('unidades.estado', 'ACTIVO')
                        ->where('asignaturas.estado', 'ACTIVO')
                        ->where('modulos.id', $Asig)
                        ->where(function ($query) use ($busqueda) {
                            $query->where('unidades.nom_unidad', 'LIKE', '%' . $busqueda . '%')
                                ->orWhere('unidades.des_unidad', 'LIKE', '%' . $busqueda . '%')
                                ->orWhere('asignaturas.nombre', 'LIKE', '%' . $busqueda . '%');
                        })
                        ->select('unidades.*', 'asignaturas.nombre', 'periodos.des_periodo', 'modulos.grado_modulo')
                        ->orderBy('nom_unidad', 'ASC')
                        ->limit($limit)->offset($offset);
                }
            } else {
                if (Auth::user()->tipo_usuario == "Profesor") {
                    $respuesta = Unidades::join('modulos', 'modulos.id', 'unidades.modulo')
                        ->join('periodos', 'periodos.id', 'unidades.periodo')
                        ->join('asig_prof', 'asig_prof.grado', 'modulos.id')
                        ->join('asignaturas', 'asignaturas.id', 'modulos.asignatura')
                        ->where('asig_prof.profesor', Auth::user()->id)
                        ->where('asignaturas.estado', 'ACTIVO')
                        ->where('unidades.estado', 'ACTIVO')
                        ->where(function ($query) use ($busqueda) {
                            $query->where('unidades.nom_unidad', 'LIKE', '%' . $busqueda . '%')
                                ->orWhere('unidades.des_unidad', 'LIKE', '%' . $busqueda . '%')
                                ->orWhere('asignaturas.nombre', 'LIKE', '%' . $busqueda . '%');
                        })
                        ->where(function ($query) use ($Usu) {
                            $query->where('unidades.docente', "")
                                ->orWhere('unidades.docente', $Usu);
                        })
                        ->select('unidades.*', 'asignaturas.nombre', 'periodos.des_periodo', 'modulos.grado_modulo')
                        ->groupBy("unidades.id")
                        ->orderBy('nom_unidad', 'ASC')
                        ->limit($limit)->offset($offset);
                } else {
                    $respuesta = Unidades::join('modulos', 'modulos.id', 'unidades.modulo')
                        ->join('periodos', 'periodos.id', 'unidades.periodo')
                        ->join('asignaturas', 'asignaturas.id', 'modulos.asignatura')
                        ->where('asignaturas.estado', 'ACTIVO')
                        ->where('unidades.estado', 'ACTIVO')
                        ->where(function ($query) use ($busqueda) {
                            $query->where('unidades.nom_unidad', 'LIKE', '%' . $busqueda . '%')
                                ->orWhere('unidades.des_unidad', 'LIKE', '%' . $busqueda . '%')
                                ->orWhere('asignaturas.nombre', 'LIKE', '%' . $busqueda . '%');
                        })
                        ->select('unidades.*', 'asignaturas.nombre', 'periodos.des_periodo', 'modulos.grado_modulo')
                        ->orderBy('nom_unidad', 'ASC')
                        ->limit($limit)->offset($offset);
                }
            }
        } else {
            if (!empty($Asig)) {
                if (Auth::user()->tipo_usuario == "Profesor") {
                    $respuesta = Unidades::join('modulos', 'modulos.id', 'unidades.modulo')
                        ->join('periodos', 'periodos.id', 'unidades.periodo')
                        ->join('asig_prof', 'asig_prof.grado', 'modulos.id')
                        ->join('asignaturas', 'asignaturas.id', 'modulos.asignatura')
                        ->where('asig_prof.profesor', Auth::user()->id)
                        ->where('asignaturas.estado', 'ACTIVO')
                        ->where('unidades.estado', 'ACTIVO')
                        ->where('modulos.id', $Asig)
                        ->where(function ($query) use ($Usu) {
                            $query->where('unidades.docente', "")
                                ->orWhere('unidades.docente', $Usu);
                        })
                        ->select('unidades.*', 'asignaturas.nombre', 'periodos.des_periodo', 'modulos.grado_modulo')
                        ->groupBy("unidades.id")
                        ->orderBy('nom_unidad', 'ASC')
                        ->limit($limit)->offset($offset);
                } else {
                    $respuesta = Unidades::join('modulos', 'modulos.id', 'unidades.modulo')
                        ->join('periodos', 'periodos.id', 'unidades.periodo')
                        ->join('asignaturas', 'asignaturas.id', 'modulos.asignatura')
                        ->where('asignaturas.estado', 'ACTIVO')
                        ->where('unidades.estado', 'ACTIVO')
                        ->where('modulos.id', $Asig)
                        ->select('unidades.*', 'asignaturas.nombre', 'periodos.des_periodo', 'modulos.grado_modulo')
                        ->orderBy('nom_unidad', 'ASC')
                        ->limit($limit)->offset($offset);
                }
            } else {
                if (Auth::user()->tipo_usuario == "Profesor") {
                    $respuesta = Unidades::join('modulos', 'modulos.id', 'unidades.modulo')
                        ->join('periodos', 'periodos.id', 'unidades.periodo')
                        ->join('asig_prof', 'asig_prof.grado', 'modulos.id')
                        ->join('asignaturas', 'asignaturas.id', 'modulos.asignatura')
                        ->where('asig_prof.profesor', Auth::user()->id)
                        ->where(function ($query) use ($Usu) {
                            $query->where('unidades.docente', "")
                                ->orWhere('unidades.docente', $Usu);
                        })
                        ->where('asignaturas.estado', 'ACTIVO')
                        ->where('unidades.estado', 'ACTIVO')
                        ->select('unidades.*', 'asignaturas.nombre', 'periodos.des_periodo', 'modulos.grado_modulo')
                        ->groupBy("unidades.id")
                        ->orderBy('nom_unidad', 'ASC')
                        ->limit($limit)->offset($offset);
                } else {
                    $respuesta = Unidades::join('modulos', 'modulos.id', 'unidades.modulo')
                        ->join('periodos', 'periodos.id', 'unidades.periodo')
                        ->join('asignaturas', 'asignaturas.id', 'modulos.asignatura')
                        ->where('asignaturas.estado', 'ACTIVO')
                        ->where('unidades.estado', 'ACTIVO')
                        ->select('unidades.*', 'asignaturas.nombre', 'periodos.des_periodo', 'modulos.grado_modulo')
                        ->orderBy('nom_unidad', 'ASC')
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
                    $respuesta = Unidades::join('modulos', 'modulos.id', 'unidades.modulo')
                        ->join('periodos', 'periodos.id', 'unidades.periodo')
                        ->join('asig_prof', 'asig_prof.grado', 'modulos.id')
                        ->join('asignaturas', 'asignaturas.id', 'modulos.asignatura')
                        ->where('asig_prof.profesor', Auth::user()->id)
                        ->where('asignaturas.estado', 'ACTIVO')
                        ->where('unidades.estado', 'ACTIVO')
                        ->where('modulos.id', $Asig)
                        ->where(function ($query) use ($busqueda) {
                            $query->where('nom_unidad', 'LIKE', '%' . $busqueda . '%')
                                ->orWhere('des_unidad', 'LIKE', '%' . $busqueda . '%');
                        })
                        ->where(function ($query) use ($Usu) {
                            $query->where('unidades.docente', "")
                                ->orWhere('unidades.docente', $Usu);
                        })
                        ->groupBy("unidades.id")
                        ->orderBy('nom_unidad', 'ASC');
                } else {
                    $respuesta = Unidades::join('modulos', 'modulos.id', 'unidades.modulo')
                        ->join('periodos', 'periodos.id', 'unidades.periodo')
                        ->join('asignaturas', 'asignaturas.id', 'modulos.asignatura')
                        ->where('asignaturas.estado', 'ACTIVO')
                        ->where('unidades.estado', 'ACTIVO')
                        ->where('modulos.id', $Asig)
                        ->where(function ($query) use ($busqueda) {
                            $query->where('nom_unidad', 'LIKE', '%' . $busqueda . '%')
                                ->orWhere('des_unidad', 'LIKE', '%' . $busqueda . '%');
                        })
                        ->orderBy('nom_unidad', 'ASC');
                }
            } else {
                if (Auth::user()->tipo_usuario == "Profesor") {
                    $respuesta = Unidades::join('modulos', 'modulos.id', 'unidades.modulo')
                        ->join('periodos', 'periodos.id', 'unidades.periodo')
                        ->join('asig_prof', 'asig_prof.grado', 'modulos.id')
                        ->join('asignaturas', 'asignaturas.id', 'modulos.asignatura')
                        ->where('asig_prof.profesor', Auth::user()->id)
                        ->where('asignaturas.estado', 'ACTIVO')
                        ->where('unidades.estado', 'ACTIVO')
                        ->where(function ($query) use ($busqueda) {
                            $query->where('nom_unidad', 'LIKE', '%' . $busqueda . '%')
                                ->orWhere('des_unidad', 'LIKE', '%' . $busqueda . '%');
                        })
                        ->where(function ($query) use ($Usu) {
                            $query->where('unidades.docente', "")
                                ->orWhere('unidades.docente', $Usu);
                        })
                        ->groupBy("unidades.id")
                        ->orderBy('nom_unidad', 'ASC');
                } else {
                    $respuesta = Unidades::join('modulos', 'modulos.id', 'unidades.modulo')
                        ->join('periodos', 'periodos.id', 'unidades.periodo')
                        ->join('asignaturas', 'asignaturas.id', 'modulos.asignatura')
                        ->where('asignaturas.estado', 'ACTIVO')
                        ->where('unidades.estado', 'ACTIVO')
                        ->where(function ($query) use ($busqueda) {
                            $query->where('nom_unidad', 'LIKE', '%' . $busqueda . '%')
                                ->orWhere('des_unidad', 'LIKE', '%' . $busqueda . '%');
                        })
                        ->orderBy('nom_unidad', 'ASC');
                }
            }
        } else {
            if (!empty($Asig)) {
                if (Auth::user()->tipo_usuario == "Profesor") {
                    $respuesta = Unidades::join('modulos', 'modulos.id', 'unidades.modulo')
                        ->join('periodos', 'periodos.id', 'unidades.periodo')
                        ->join('asig_prof', 'asig_prof.grado', 'modulos.id')
                        ->join('asignaturas', 'asignaturas.id', 'modulos.asignatura')
                        ->where('asig_prof.profesor', Auth::user()->id)
                        ->where('asignaturas.estado', 'ACTIVO')
                        ->where('unidades.estado', 'ACTIVO')
                        ->where('modulos.id', $Asig)
                        ->where(function ($query) use ($Usu) {
                            $query->where('unidades.docente', "")
                                ->orWhere('unidades.docente', $Usu);
                        })
                        ->groupBy("unidades.id")
                        ->orderBy('nom_unidad', 'ASC');
                } else {
                    $respuesta = Unidades::join('modulos', 'modulos.id', 'unidades.modulo')
                        ->join('periodos', 'periodos.id', 'unidades.periodo')
                        ->join('asignaturas', 'asignaturas.id', 'modulos.asignatura')
                        ->where('asignaturas.estado', 'ACTIVO')
                        ->where('unidades.estado', 'ACTIVO')
                        ->where('modulos.id', $Asig)
                        ->orderBy('nom_unidad', 'ASC');
                }
            } else {
                if (Auth::user()->tipo_usuario == "Profesor") {
                    $respuesta = Unidades::join('modulos', 'modulos.id', 'unidades.modulo')
                        ->join('periodos', 'periodos.id', 'unidades.periodo')
                        ->join('asig_prof', 'asig_prof.grado', 'modulos.id')
                        ->join('asignaturas', 'asignaturas.id', 'modulos.asignatura')
                        ->where('asig_prof.profesor', Auth::user()->id)
                        ->where('asignaturas.estado', 'ACTIVO')
                        ->where('unidades.estado', 'ACTIVO')
                        ->groupBy("unidades.id")
                        ->orderBy('nom_unidad', 'ASC');
                } else {
                    $respuesta = Unidades::join('modulos', 'modulos.id', 'unidades.modulo')
                        ->join('periodos', 'periodos.id', 'unidades.periodo')
                        ->join('asignaturas', 'asignaturas.id', 'modulos.asignatura')
                        ->where('asignaturas.estado', 'ACTIVO')
                        ->where('unidades.estado', 'ACTIVO')
                        ->orderBy('nom_unidad', 'ASC');
                }
            }
        }
        return $respuesta->get();
    }

    public static function TitUnidades($id)
    {
        $DesUnidade = Unidades::where('id', $id)
            ->where('estado', 'ACTIVO')
            ->first();
        return $DesUnidade;
    }

    public static function listarUniPerd($per)
    {
        $Unidade = Unidades::where('periodo', $per)
            ->where('estado', 'ACTIVO')
            ->get();
        return $Unidade;

    }

    public static function VerificarAsig($data)
    {

        $Exite = "no";
        foreach ($data["idUnid"] as $key => $val) {
            if ($data["UnidSel"][$key] == "si") {
            $Unidade = Unidades::where('id', $data["idUnid"][$key])
                ->where('estado', 'ACTIVO')
                ->first();

            $AsigProf = AsigProf::where("grado", $Unidade->modulo)
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

        foreach ($data["idUnid"] as $key => $val) {
            if ($data["UnidSel"][$key] == "si") {
            $respuesta = Unidades::where(['id' => $data["idUnid"][$key], "docente" => $data["docenteOld"]])->update([
                'docente' => $data['docenteReasig'],
            ]);
        }
        }

        return $respuesta;

    }

    public static function BuscarUnidadAsig($Asig)
    {
        $Unidades = Unidades::where('modulo', $Asig)
            ->get();
        return $Unidades;
    }

    public static function BuscarUnidad($id)
    {
        return Unidades::findOrFail($id);
    }

    public static function Guardar($datos)
    {
        $Ori = "P";
        $Doc = "";
        if (Auth::user()->tipo_usuario == "Profesor") {
            $Ori = "D";
            $Doc = Auth::user()->id;
        }
        return Unidades::create([
            'periodo' => $datos['periodo'],
            'modulo' => $datos['modulo'],
            'nom_unidad' => $datos['nom_unidad'],
            'des_unidad' => $datos['des_unidad'],
            'estado' => 'ACTIVO',
            'introduccion' => $datos['introduccion'],
            'origunidad' => $Ori,
            'docente' => $Doc,
        ]);
    }

    public static function modificar($data, $id)
    {
        $respuesta = Unidades::where(['id' => $id])->update([
            'periodo' => $data['periodo'],
            'modulo' => $data['modulo'],
            'nom_unidad' => $data['nom_unidad'],
            'des_unidad' => $data['des_unidad'],
            'introduccion' => $data['introduccion'],
        ]);
        return $respuesta;
    }

    public static function editarestado($id, $estado)
    {
        $Respuesta = Unidades::where('id', $id)->update([
            'estado' => $estado,
        ]);
        return $Respuesta;
    }

}
