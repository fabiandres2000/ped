<?php

namespace App;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

class UnidadesModulos extends Model
{ 
    protected $table = 'unidades_modulos';
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
            $Unidade = UnidadesModulos::where('modulo', $id)
                ->where(function ($query) use ($Usu) {
                    $query->where('unidades_modulos.docente', "")
                        ->orWhere('unidades_modulos.docente', $Usu);
                })
                ->where('estado','ACTIVO')
                ->orderBy('periodo', 'ASC')
                ->get();
            return $Unidade;
        } else if (Auth::user()->tipo_usuario == "Estudiante") {
            $Usu = Session::get('USUDOCENTE');
            $Unidade = UnidadesModulos::where('modulo', $id)
                ->where(function ($query) use ($Usu) {
                    $query->where('unidades_modulos.docente', "")
                        ->orWhere('unidades_modulos.docente', $Usu);
                })
                ->where('estado','ACTIVO')

                ->orderBy('periodo', 'ASC')
                ->get();
            return $Unidade;
        } else {
            $Unidade = UnidadesModulos::where('modulo', $id)
                ->orderBy('periodo', 'ASC')
                ->where('estado','ACTIVO')
                ->get();
            return $Unidade;
        }
    }

    public static function VerfDel($id)
    {
        $VerfDel = UnidadesModulos::where('id', $id)
        ->where('id', '>=', 1)
        ->where('id', '<=', 128)
        ->get();
       
        return $VerfDel;
    }


    public static function listar($id)
    {
        if (Auth::user()->tipo_usuario == "Profesor") {
            $Usu = Auth::user()->id;
            $Unidade = UnidadesModulos::where('periodo', $id)
                ->where('unidades_modulos.estado', 'ACTIVO')
                ->where(function ($query) use ($Usu) {
                    $query->where('unidades_modulos.docente', "")
                        ->orWhere('unidades_modulos.docente', $Usu);
                })
                ->get();
        } else {
            $Unidade = UnidadesModulos::where('periodo', $id)
                ->where('unidades_modulos.estado', 'ACTIVO')
                ->get();
        }

        return $Unidade;
    }

    public static function VerificarAsig($data)
    {

        $Exite = "no";

        foreach ($data["idUnid"] as $key => $val) {
            if ($data["UnidSel"][$key] == "si") {
                $Unidade = UnidadesModulos::where('id', $data["idUnid"][$key])
                    ->where('estado', 'ACTIVO')
                    ->first();

                $AsigProf = ModProf::where("grado", $Unidade->modulo)
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
                $respuesta = UnidadesModulos::where(['id' => $data["idUnid"][$key], "docente" => $data["docenteOld"]])->update([
                    'docente' => $data['docenteReasig'],
                ]);
            }
        }

        return $respuesta;

    }

    public static function listarUnidadesxDocente($Doce)
    {

        $Unidade = DB::connection("mysql")->select("SELECT CONCAT(asig.nombre,' - Grado ',modu.grado_modulo,'°') AS asignatura, und.nom_unidad, und.des_unidad,  und.id, und.modulo,und.docente FROM unidades_modulos und"
            . " LEFT JOIN (SELECT * FROM mod_prof WHERE profesor = " . $Doce . ")  ap"
            . " ON und.modulo=ap.grado "
            . " LEFT JOIN grados_modulos modu ON und.modulo=modu.id"
            . " LEFT JOIN modulos_transversales asig ON modu.modulo=asig.id"
            . " WHERE  und.docente=" . $Doce . " AND und.estado='ACTIVO' AND ap.grado IS NULL");

        return $Unidade;
    }

    public static function listarPond($id)
    {
        $Usu = Auth::user()->id;
        $Unidade = UnidadesModulos::leftJoin('pond_unidades', 'unidades.id', '=', 'pond_unidades.unidad', 'pond_unidades.docente', '=', $Usu)
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
                    $respuesta = UnidadesModulos::join('grados_modulos', 'grados_modulos.id', 'unidades_modulos.modulo')
                        ->join('periodos_modtransv', 'periodos_modtransv.id', 'unidades_modulos.periodo')
                        ->join('mod_prof', 'mod_prof.grado', 'grados_modulos.id')
                        ->join('modulos_transversales', 'modulos_transversales.id', 'grados_modulos.modulo')
                        ->where('mod_prof.profesor', Auth::user()->id)
                        ->where('unidades_modulos.estado', 'ACTIVO')
                        ->where('modulos_transversales.estado', 'ACTIVO')
                        ->where('grados_modulos.id', $Asig)
                        ->where(function ($query) use ($busqueda) {
                            $query->where('unidades_modulos.nom_unidad', 'LIKE', '%' . $busqueda . '%')
                                ->orWhere('unidades_modulos.des_unidad', 'LIKE', '%' . $busqueda . '%')
                                ->orWhere('modulos_transversales.nombre', 'LIKE', '%' . $busqueda . '%');
                        })
                        ->where(function ($query) use ($Usu) {
                            $query->where('unidades_modulos.docente', "")
                                ->orWhere('unidades_modulos.docente', $Usu);
                        })
                        ->select('unidades_modulos.*', 'modulos_transversales.nombre', 'periodos_modtransv.des_periodo', 'grados_modulos.grado_modulo')
                        ->orderBy('nom_unidad', 'ASC')
                        ->limit($limit)->offset($offset);
                } else {
                    $respuesta = UnidadesModulos::join('grados_modulos', 'grados_modulos.id', 'unidades_modulos.modulo')
                        ->join('periodos_modtransv', 'periodos_modtransv.id', 'unidades_modulos.periodo')
                        ->join('modulos_transversales', 'modulos_transversales.id', 'grados_modulos.modulo')
                        ->where('unidades_modulos.estado', 'ACTIVO')
                        ->where('modulos_transversales.estado', 'ACTIVO')
                        ->where('grados_modulos.id', $Asig)
                        ->where(function ($query) use ($busqueda) {
                            $query->where('unidades_modulos.nom_unidad', 'LIKE', '%' . $busqueda . '%')
                                ->orWhere('unidades_modulos.des_unidad', 'LIKE', '%' . $busqueda . '%')
                                ->orWhere('modulos_transversales.nombre', 'LIKE', '%' . $busqueda . '%');
                        })
                        ->select('unidades_modulos.*', 'modulos_transversales.nombre', 'periodos_modtransv.des_periodo', 'grados_modulos.grado_modulo')
                        ->orderBy('nom_unidad', 'ASC')
                        ->limit($limit)->offset($offset);
                }
            } else {
                if (Auth::user()->tipo_usuario == "Profesor") {
                    $respuesta = UnidadesModulos::join('grados_modulos', 'grados_modulos.id', 'unidades_modulos.modulo')
                        ->join('periodos_modtransv', 'periodos_modtransv.id', 'unidades_modulos.periodo')
                        ->join('mod_prof', 'mod_prof.grado', 'grados_modulos.id')
                        ->join('modulos_transversales', 'modulos_transversales.id', 'grados_modulos.modulo')
                        ->where('unidades_modulos.estado', 'ACTIVO')
                        ->where('modulos_transversales.estado', 'ACTIVO')
                        ->where(function ($query) use ($busqueda) {
                            $query->where('unidades_modulos.nom_unidad', 'LIKE', '%' . $busqueda . '%')
                                ->orWhere('unidades_modulos.des_unidad', 'LIKE', '%' . $busqueda . '%')
                                ->orWhere('modulos_transversales.nombre', 'LIKE', '%' . $busqueda . '%');
                        })
                        ->where(function ($query) use ($Usu) {
                            $query->where('unidades_modulos.docente', "")
                                ->orWhere('unidades_modulos.docente', $Usu);
                        })
                        ->select('unidades_modulos.*', 'modulos_transversales.nombre', 'periodos_modtransv.des_periodo', 'grados_modulos.grado_modulo')
                        ->orderBy('nom_unidad', 'ASC')
                        ->limit($limit)->offset($offset);
                } else {
                    $respuesta = UnidadesModulos::join('grados_modulos', 'grados_modulos.id', 'unidades_modulos.modulo')
                        ->join('periodos_modtransv', 'periodos_modtransv.id', 'unidades_modulos.periodo')
                        ->join('modulos_transversales', 'modulos_transversales.id', 'grados_modulos.modulo')
                        ->where('unidades_modulos.estado', 'ACTIVO')
                        ->where('modulos_transversales.estado', 'ACTIVO')
                        ->where(function ($query) use ($busqueda) {
                            $query->where('unidades_modulos.nom_unidad', 'LIKE', '%' . $busqueda . '%')
                                ->orWhere('unidades_modulos.des_unidad', 'LIKE', '%' . $busqueda . '%')
                                ->orWhere('modulos_transversales.nombre', 'LIKE', '%' . $busqueda . '%');
                        })
                        ->select('unidades_modulos.*', 'modulos_transversales.nombre', 'periodos_modtransv.des_periodo', 'grados_modulos.grado_modulo')
                        ->orderBy('nom_unidad', 'ASC')
                        ->limit($limit)->offset($offset);
                }
            }
        } else {
            if (!empty($Asig)) {
                if (Auth::user()->tipo_usuario == "Profesor") {
                    $respuesta = UnidadesModulos::join('grados_modulos', 'grados_modulos.id', 'unidades_modulos.modulo')
                        ->join('periodos_modtransv', 'periodos_modtransv.id', 'unidades_modulos.periodo')
                        ->join('mod_prof', 'mod_prof.grado', 'grados_modulos.id')
                        ->join('modulos_transversales', 'modulos_transversales.id', 'grados_modulos.modulo')
                        ->where('mod_prof.profesor', Auth::user()->id)
                        ->where('unidades_modulos.estado', 'ACTIVO')
                        ->where('modulos_transversales.estado', 'ACTIVO')
                        ->where('grados_modulos.id', $Asig)
                        ->where(function ($query) use ($Usu) {
                            $query->where('unidades_modulos.docente', "")
                                ->orWhere('unidades_modulos.docente', $Usu);
                        })
                        ->select('unidades_modulos.*', 'modulos_transversales.nombre', 'periodos_modtransv.des_periodo', 'grados_modulos.grado_modulo')
                        ->orderBy('nom_unidad', 'ASC')
                        ->limit($limit)->offset($offset);
                } else {
                    $respuesta = UnidadesModulos::join('grados_modulos', 'grados_modulos.id', 'unidades_modulos.modulo')
                        ->join('periodos_modtransv', 'periodos_modtransv.id', 'unidades_modulos.periodo')
                        ->join('modulos_transversales', 'modulos_transversales.id', 'grados_modulos.modulo')
                        ->where('unidades_modulos.estado', 'ACTIVO')
                        ->where('modulos_transversales.estado', 'ACTIVO')
                        ->where('grados_modulos.id', $Asig)

                        ->select('unidades_modulos.*', 'modulos_transversales.nombre', 'periodos_modtransv.des_periodo', 'grados_modulos.grado_modulo')
                        ->orderBy('nom_unidad', 'ASC')
                        ->limit($limit)->offset($offset);
                }
            } else {
                if (Auth::user()->tipo_usuario == "Profesor") {
                    $respuesta = UnidadesModulos::join('grados_modulos', 'grados_modulos.id', 'unidades_modulos.modulo')
                        ->join('periodos_modtransv', 'periodos_modtransv.id', 'unidades_modulos.periodo')
                        ->join('mod_prof', 'mod_prof.grado', 'grados_modulos.id')
                        ->join('modulos_transversales', 'modulos_transversales.id', 'grados_modulos.modulo')
                        ->where('unidades_modulos.estado', 'ACTIVO')
                        ->where('modulos_transversales.estado', 'ACTIVO')
                        ->where(function ($query) use ($Usu) {
                            $query->where('unidades_modulos.docente', "")
                                ->orWhere('unidades_modulos.docente', $Usu);
                        })
                        ->select('unidades_modulos.*', 'modulos_transversales.nombre', 'periodos_modtransv.des_periodo', 'grados_modulos.grado_modulo')
                        ->orderBy('nom_unidad', 'ASC')
                        ->limit($limit)->offset($offset);
                } else {
                    $respuesta = UnidadesModulos::join('grados_modulos', 'grados_modulos.id', 'unidades_modulos.modulo')
                        ->join('periodos_modtransv', 'periodos_modtransv.id', 'unidades_modulos.periodo')
                        ->join('modulos_transversales', 'modulos_transversales.id', 'grados_modulos.modulo')
                        ->where('unidades_modulos.estado', 'ACTIVO')
                        ->where('modulos_transversales.estado', 'ACTIVO')

                        ->select('unidades_modulos.*', 'modulos_transversales.nombre', 'periodos_modtransv.des_periodo', 'grados_modulos.grado_modulo')
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
                    $respuesta = UnidadesModulos::join('grados_modulos', 'grados_modulos.id', 'unidades_modulos.modulo')
                        ->join('periodos_modtransv', 'periodos_modtransv.id', 'unidades_modulos.periodo')
                        ->join('mod_prof', 'mod_prof.grado', 'grados_modulos.id')
                        ->join('modulos_transversales', 'modulos_transversales.id', 'grados_modulos.modulo')
                        ->where('mod_prof.profesor', Auth::user()->id)
                        ->where('unidades_modulos.estado', 'ACTIVO')
                        ->where('modulos_transversales.estado', 'ACTIVO')
                        ->where('grados_modulos.id', $Asig)
                        ->where(function ($query) use ($busqueda) {
                            $query->where('unidades_modulos.nom_unidad', 'LIKE', '%' . $busqueda . '%')
                                ->orWhere('unidades_modulos.des_unidad', 'LIKE', '%' . $busqueda . '%')
                                ->orWhere('modulos_transversales.nombre', 'LIKE', '%' . $busqueda . '%');
                        })
                        ->where(function ($query) use ($Usu) {
                            $query->where('unidades_modulos.docente', "")
                                ->orWhere('unidades_modulos.docente', $Usu);
                        })
                        ->select('unidades_modulos.*', 'modulos_transversales.nombre', 'periodos_modtransv.des_periodo', 'grados_modulos.grado_modulo')
                        ->orderBy('nom_unidad', 'ASC');
                } else {
                    $respuesta = UnidadesModulos::join('grados_modulos', 'grados_modulos.id', 'unidades_modulos.modulo')
                        ->join('periodos_modtransv', 'periodos_modtransv.id', 'unidades_modulos.periodo')
                        ->join('modulos_transversales', 'modulos_transversales.id', 'grados_modulos.modulo')
                        ->where('unidades_modulos.estado', 'ACTIVO')
                        ->where('modulos_transversales.estado', 'ACTIVO')
                        ->where('grados_modulos.id', $Asig)
                        ->where(function ($query) use ($busqueda) {
                            $query->where('unidades_modulos.nom_unidad', 'LIKE', '%' . $busqueda . '%')
                                ->orWhere('unidades_modulos.des_unidad', 'LIKE', '%' . $busqueda . '%')
                                ->orWhere('modulos_transversales.nombre', 'LIKE', '%' . $busqueda . '%');
                        })
                        ->select('unidades_modulos.*', 'modulos_transversales.nombre', 'periodos_modtransv.des_periodo', 'grados_modulos.grado_modulo')
                        ->orderBy('nom_unidad', 'ASC');
                }
            } else {
                if (Auth::user()->tipo_usuario == "Profesor") {
                    $respuesta = UnidadesModulos::join('grados_modulos', 'grados_modulos.id', 'unidades_modulos.modulo')
                        ->join('periodos_modtransv', 'periodos_modtransv.id', 'unidades_modulos.periodo')
                        ->join('mod_prof', 'mod_prof.grado', 'grados_modulos.id')
                        ->join('modulos_transversales', 'modulos_transversales.id', 'grados_modulos.modulo')
                        ->where('unidades_modulos.estado', 'ACTIVO')
                        ->where('modulos_transversales.estado', 'ACTIVO')
                        ->where(function ($query) use ($busqueda) {
                            $query->where('unidades_modulos.nom_unidad', 'LIKE', '%' . $busqueda . '%')
                                ->orWhere('unidades_modulos.des_unidad', 'LIKE', '%' . $busqueda . '%')
                                ->orWhere('modulos_transversales.nombre', 'LIKE', '%' . $busqueda . '%');
                        })
                        ->where(function ($query) use ($Usu) {
                            $query->where('unidades_modulos.docente', "")
                                ->orWhere('unidades_modulos.docente', $Usu);
                        })
                        ->select('unidades_modulos.*', 'modulos_transversales.nombre', 'periodos_modtransv.des_periodo', 'grados_modulos.grado_modulo')
                        ->orderBy('nom_unidad', 'ASC');
                } else {
                    $respuesta = UnidadesModulos::join('grados_modulos', 'grados_modulos.id', 'unidades_modulos.modulo')
                        ->join('periodos_modtransv', 'periodos_modtransv.id', 'unidades_modulos.periodo')
                        ->join('modulos_transversales', 'modulos_transversales.id', 'grados_modulos.modulo')
                        ->where('unidades_modulos.estado', 'ACTIVO')
                        ->where('modulos_transversales.estado', 'ACTIVO')
                        ->where(function ($query) use ($busqueda) {
                            $query->where('unidades_modulos.nom_unidad', 'LIKE', '%' . $busqueda . '%')
                                ->orWhere('unidades_modulos.des_unidad', 'LIKE', '%' . $busqueda . '%')
                                ->orWhere('modulos_transversales.nombre', 'LIKE', '%' . $busqueda . '%');
                        })
                        ->select('unidades_modulos.*', 'modulos_transversales.nombre', 'periodos_modtransv.des_periodo', 'grados_modulos.grado_modulo')
                        ->orderBy('nom_unidad', 'ASC');
                }
            }
        } else {
            if (!empty($Asig)) {
                if (Auth::user()->tipo_usuario == "Profesor") {
                    $respuesta = UnidadesModulos::join('grados_modulos', 'grados_modulos.id', 'unidades_modulos.modulo')
                        ->join('periodos_modtransv', 'periodos_modtransv.id', 'unidades_modulos.periodo')
                        ->join('mod_prof', 'mod_prof.grado', 'grados_modulos.id')
                        ->join('modulos_transversales', 'modulos_transversales.id', 'grados_modulos.modulo')
                        ->where('mod_prof.profesor', Auth::user()->id)
                        ->where('unidades_modulos.estado', 'ACTIVO')
                        ->where('modulos_transversales.estado', 'ACTIVO')
                        ->where('grados_modulos.id', $Asig)
                        ->where(function ($query) use ($Usu) {
                            $query->where('unidades_modulos.docente', "")
                                ->orWhere('unidades_modulos.docente', $Usu);
                        })
                        ->select('unidades_modulos.*', 'modulos_transversales.nombre', 'periodos_modtransv.des_periodo', 'grados_modulos.grado_modulo')
                        ->orderBy('nom_unidad', 'ASC');
                } else {
                    $respuesta = UnidadesModulos::join('grados_modulos', 'grados_modulos.id', 'unidades_modulos.modulo')
                        ->join('periodos_modtransv', 'periodos_modtransv.id', 'unidades_modulos.periodo')
                        ->join('modulos_transversales', 'modulos_transversales.id', 'grados_modulos.modulo')
                        ->where('unidades_modulos.estado', 'ACTIVO')
                        ->where('modulos_transversales.estado', 'ACTIVO')
                        ->where('grados_modulos.id', $Asig)

                        ->select('unidades_modulos.*', 'modulos_transversales.nombre', 'periodos_modtransv.des_periodo', 'grados_modulos.grado_modulo')
                        ->orderBy('nom_unidad', 'ASC');
                }
            } else {
                if (Auth::user()->tipo_usuario == "Profesor") {
                    $respuesta = UnidadesModulos::join('grados_modulos', 'grados_modulos.id', 'unidades_modulos.modulo')
                        ->join('periodos_modtransv', 'periodos_modtransv.id', 'unidades_modulos.periodo')
                        ->join('mod_prof', 'mod_prof.grado', 'grados_modulos.id')
                        ->join('modulos_transversales', 'modulos_transversales.id', 'grados_modulos.modulo')
                        ->where('unidades_modulos.estado', 'ACTIVO')
                        ->where('modulos_transversales.estado', 'ACTIVO')
                        ->where(function ($query) use ($Usu) {
                            $query->where('unidades_modulos.docente', "")
                                ->orWhere('unidades_modulos.docente', $Usu);
                        })
                        ->select('unidades_modulos.*', 'modulos_transversales.nombre', 'periodos_modtransv.des_periodo', 'grados_modulos.grado_modulo')
                        ->orderBy('nom_unidad', 'ASC');
                } else {
                    $respuesta = UnidadesModulos::join('grados_modulos', 'grados_modulos.id', 'unidades_modulos.modulo')
                        ->join('periodos_modtransv', 'periodos_modtransv.id', 'unidades_modulos.periodo')
                        ->join('modulos_transversales', 'modulos_transversales.id', 'grados_modulos.modulo')
                        ->where('unidades_modulos.estado', 'ACTIVO')
                        ->where('modulos_transversales.estado', 'ACTIVO')

                        ->select('unidades_modulos.*', 'modulos_transversales.nombre', 'periodos_modtransv.des_periodo', 'grados_modulos.grado_modulo')
                        ->orderBy('nom_unidad', 'ASC');
                }
            }
        }
        return $respuesta->count();
    }

    public static function TitUnidades($id)
    {
        $DesUnidade = UnidadesModulos::where('id', $id)
            ->where('estado', 'ACTIVO')
            ->first();
        return $DesUnidade;
    }

    public static function UnidadesxPeriodo($id)
    {
        $DesUnidade = UnidadesModulos::where('periodo', $id)
            ->where('estado', 'ACTIVO')
            ->get();
        return $DesUnidade;
    }

    public static function BuscarUnidad($id)
    {
        return UnidadesModulos::findOrFail($id);
    }

    public static function Guardar($datos)
    {
        $Ori = "P";
        $Doc = "";
        if (Auth::user()->tipo_usuario == "Profesor") {
            $Ori = "D";
            $Doc = Auth::user()->id;
        }
        return UnidadesModulos::create([
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
        $respuesta = UnidadesModulos::where(['id' => $id])->update([
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
        $Respuesta = UnidadesModulos::where('id', $id)->update([
            'estado' => $estado,
        ]);
        return $Respuesta;
    }
}
