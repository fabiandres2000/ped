<?php

namespace App;

use Auth;
use Illuminate\Database\Eloquent\Model;

class Laboratorios extends Model
{

    protected $table = 'laboratorios';
    protected $fillable = [
        'modulo',
        'periodo',
        'unidad',
        'titulo',
        'objetivo',
        'fund_teorico',
        'materiales',
        'habilitado',
        'docente',
        'estado',
    ];

    public static function temas($id)
    {
        if (Auth::user()->tipo_usuario == "Profesor") {
            $Usu = Auth::user()->id;
            $Temas = Laboratorios::where('unidad', $id)
                ->where('estado', 'ACTIVO')
                ->select('laboratorios.*', 'laboratorios.titulo AS titu_contenido')
                ->get();
        } else if (Auth::user()->tipo_usuario == "Estudiante") {
            $Usu = Session::get('USUDOCENTE');
            $Temas = Laboratorios::where('unidad', $id)
                ->where('estado', 'ACTIVO')
                ->select('laboratorios.*', 'laboratorios.titulo AS titu_contenido')
                ->get();
        } else {
            $Temas = Laboratorios::where('unidad', $id)
                ->where('estado', 'ACTIVO')
                ->select('laboratorios.*', 'laboratorios.titulo AS titu_contenido')
                ->get();
        }

        return $Temas;
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
                    $respuesta = Laboratorios::join('unidades', 'unidades.id', 'laboratorios.unidad')
                        ->join('modulos', 'modulos.id', 'unidades.modulo')
                        ->join('asig_prof', 'asig_prof.grado', 'modulos.id')
                        ->join('asignaturas', 'asignaturas.id', 'modulos.asignatura')
                        ->where('modulos.id', $Asig)
                        ->where('asig_prof.profesor', Auth::user()->id)
                        ->where('asignaturas.estado', 'ACTIVO')
                        ->where('laboratorios.estado', 'ACTIVO')
                        ->where(function ($query) use ($busqueda) {
                            $query->where('laboratorios.titulo', 'LIKE', '%' . $busqueda . '%')
                                ->orWhere('unidades.nom_unidad', 'LIKE', '%' . $busqueda . '%')
                                ->orWhere('asignaturas.nombre', 'LIKE', '%' . $busqueda . '%');
                        })
                        ->where(function ($query) use ($Usu) {
                            $query->where('laboratorios.docente', "")
                                ->orWhere('laboratorios.docente', $Usu);
                        })
                        ->select('laboratorios.*', 'asignaturas.nombre', 'modulos.grado_modulo', 'unidades.nom_unidad', 'unidades.des_unidad')
                        ->orderBy('asignaturas.nombre', 'ASC')
                        ->groupBy('laboratorios.id')
                        ->limit($limit)->offset($offset);
                } else {
                    $respuesta = Laboratorios::join('unidades', 'unidades.id', 'laboratorios.unidad')
                        ->join('modulos', 'modulos.id', 'unidades.modulo')
                        ->join('asignaturas', 'asignaturas.id', 'modulos.asignatura')
                        ->where('modulos.id', $Asig)
                        ->where('asignaturas.estado', 'ACTIVO')
                        ->where('laboratorios.estado', 'ACTIVO')
                        ->where(function ($query) use ($busqueda) {
                            $query->where('laboratorios.titulo', 'LIKE', '%' . $busqueda . '%')
                                ->orWhere('unidades.nom_unidad', 'LIKE', '%' . $busqueda . '%')
                                ->orWhere('asignaturas.nombre', 'LIKE', '%' . $busqueda . '%');
                        })
                        ->select('laboratorios.*', 'asignaturas.nombre', 'modulos.grado_modulo', 'unidades.nom_unidad', 'unidades.des_unidad')
                        ->orderBy('asignaturas.nombre', 'ASC')
                        ->groupBy('laboratorios.id')
                        ->limit($limit)->offset($offset);
                }
            } else {
                if (Auth::user()->tipo_usuario == "Profesor") {
                    $respuesta = Laboratorios::join('unidades', 'unidades.id', 'laboratorios.unidad')
                        ->join('modulos', 'modulos.id', 'unidades.modulo')
                        ->join('asig_prof', 'asig_prof.grado', 'modulos.id')
                        ->join('asignaturas', 'asignaturas.id', 'modulos.asignatura')
                        ->where('asig_prof.profesor', Auth::user()->id)
                        ->where('asignaturas.estado', 'ACTIVO')
                        ->where('laboratorios.estado', 'ACTIVO')
                        ->where(function ($query) use ($busqueda) {
                            $query->where('laboratorios.titulo', 'LIKE', '%' . $busqueda . '%')
                                ->orWhere('unidades.nom_unidad', 'LIKE', '%' . $busqueda . '%')
                                ->orWhere('asignaturas.nombre', 'LIKE', '%' . $busqueda . '%');
                        })
                        ->where(function ($query) use ($Usu) {
                            $query->where('laboratorios.docente', "")
                                ->orWhere('laboratorios.docente', $Usu);
                        })
                        ->select('laboratorios.*', 'asignaturas.nombre', 'modulos.grado_modulo', 'unidades.nom_unidad', 'unidades.des_unidad')
                        ->orderBy('asignaturas.nombre', 'ASC')
                        ->groupBy('laboratorios.id')
                        ->limit($limit)->offset($offset);
                } else {
                    $respuesta = Laboratorios::join('unidades', 'unidades.id', 'laboratorios.unidad')
                        ->join('modulos', 'modulos.id', 'unidades.modulo')
                        ->join('asignaturas', 'asignaturas.id', 'modulos.asignatura')
                        ->where('asignaturas.estado', 'ACTIVO')
                        ->where('laboratorios.estado', 'ACTIVO')
                        ->where(function ($query) use ($busqueda) {
                            $query->where('laboratorios.titulo', 'LIKE', '%' . $busqueda . '%')
                                ->orWhere('unidades.nom_unidad', 'LIKE', '%' . $busqueda . '%')
                                ->orWhere('asignaturas.nombre', 'LIKE', '%' . $busqueda . '%');
                        })
                        ->select('laboratorios.*', 'asignaturas.nombre', 'modulos.grado_modulo', 'unidades.nom_unidad', 'unidades.des_unidad')
                        ->orderBy('asignaturas.nombre', 'ASC')
                        ->groupBy('laboratorios.id')
                        ->limit($limit)->offset($offset);
                }
            }
        } else {
            if (!empty($Asig)) {
                if (Auth::user()->tipo_usuario == "Profesor") {
                    $respuesta = Laboratorios::join('unidades', 'unidades.id', 'laboratorios.unidad')
                        ->join('modulos', 'modulos.id', 'unidades.modulo')
                        ->join('asig_prof', 'asig_prof.grado', 'modulos.id')
                        ->join('asignaturas', 'asignaturas.id', 'modulos.asignatura')
                        ->where('modulos.id', $Asig)
                        ->where('asig_prof.profesor', Auth::user()->id)
                        ->where('asignaturas.estado', 'ACTIVO')
                        ->where('laboratorios.estado', 'ACTIVO')
                        ->where(function ($query) use ($Usu) {
                            $query->where('laboratorios.docente', "")
                                ->orWhere('laboratorios.docente', $Usu);
                        })
                        ->select('laboratorios.*', 'asignaturas.nombre', 'modulos.grado_modulo', 'unidades.nom_unidad', 'unidades.des_unidad')
                        ->orderBy('asignaturas.nombre', 'ASC')
                        ->groupBy('laboratorios.id')
                        ->limit($limit)->offset($offset);
                } else {
                    $respuesta = Laboratorios::join('unidades', 'unidades.id', 'laboratorios.unidad')
                        ->join('modulos', 'modulos.id', 'unidades.modulo')
                        ->join('asignaturas', 'asignaturas.id', 'modulos.asignatura')
                        ->where('modulos.id', $Asig)
                        ->where('asignaturas.estado', 'ACTIVO')
                        ->where('laboratorios.estado', 'ACTIVO')
                        ->select('laboratorios.*', 'asignaturas.nombre', 'modulos.grado_modulo', 'unidades.nom_unidad', 'unidades.des_unidad')
                        ->orderBy('asignaturas.nombre', 'ASC')
                        ->groupBy('laboratorios.id')
                        ->limit($limit)->offset($offset);
                  
                }
            } else {
                if (Auth::user()->tipo_usuario == "Profesor") {

                    $respuesta = Laboratorios::join('unidades', 'unidades.id', 'laboratorios.unidad')
                        ->join('modulos', 'modulos.id', 'unidades.modulo')
                        ->join('asig_prof', 'asig_prof.grado', 'modulos.id')
                        ->join('asignaturas', 'asignaturas.id', 'modulos.asignatura')
                        ->where('asig_prof.profesor', Auth::user()->id)
                        ->where('asignaturas.estado', 'ACTIVO')
                        ->where('laboratorios.estado', 'ACTIVO')
                        ->where(function ($query) use ($Usu) {
                            $query->where('laboratorios.docente', "")
                                ->orWhere('laboratorios.docente', $Usu);
                        })
                        ->select('laboratorios.*', 'asignaturas.nombre', 'modulos.grado_modulo', 'unidades.nom_unidad', 'unidades.des_unidad')
                        ->orderBy('asignaturas.nombre', 'ASC')
                        ->groupBy('laboratorios.id')
                        ->limit($limit)->offset($offset);
                } else {

                    $respuesta = Laboratorios::join('unidades', 'unidades.id', 'laboratorios.unidad')
                        ->join('modulos', 'modulos.id', 'unidades.modulo')
                        ->join('asignaturas', 'asignaturas.id', 'modulos.asignatura')
                        ->where('asignaturas.estado', 'ACTIVO')
                        ->where('laboratorios.estado', 'ACTIVO')
                        ->select('laboratorios.*', 'asignaturas.nombre', 'modulos.grado_modulo', 'unidades.nom_unidad', 'unidades.des_unidad')
                        ->orderBy('asignaturas.nombre', 'ASC')
                        ->groupBy('laboratorios.id')
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
                    $respuesta = Laboratorios::join('unidades', 'unidades.id', 'laboratorios.unidad')
                        ->join('modulos', 'modulos.id', 'unidades.modulo')
                        ->join('asig_prof', 'asig_prof.grado', 'modulos.id')
                        ->join('asignaturas', 'asignaturas.id', 'modulos.asignatura')
                        ->where('modulos.id', $Asig)
                        ->where('asig_prof.profesor', Auth::user()->id)
                        ->where('asignaturas.estado', 'ACTIVO')
                        ->where('laboratorios.estado', 'ACTIVO')
                        ->where(function ($query) use ($busqueda) {
                            $query->where('laboratorios.titulo', 'LIKE', '%' . $busqueda . '%')
                                ->orWhere('unidades.nom_unidad', 'LIKE', '%' . $busqueda . '%')
                                ->orWhere('asignaturas.nombre', 'LIKE', '%' . $busqueda . '%');
                        })
                        ->where(function ($query) use ($Usu) {
                            $query->where('laboratorios.docente', "")
                                ->orWhere('laboratorios.docente', $Usu);
                        })
                        ->orderBy('asignaturas.nombre', 'ASC')
                        ->groupBy('laboratorios.id')
                        ->get();
                } else {
                    $respuesta = Laboratorios::join('unidades', 'unidades.id', 'laboratorios.unidad')
                        ->join('modulos', 'modulos.id', 'unidades.modulo')
                        ->join('asignaturas', 'asignaturas.id', 'modulos.asignatura')
                        ->where('modulos.id', $Asig)
                        ->where('asignaturas.estado', 'ACTIVO')
                        ->where('laboratorios.estado', 'ACTIVO')
                        ->where(function ($query) use ($busqueda) {
                            $query->where('laboratorios.titulo', 'LIKE', '%' . $busqueda . '%')
                                ->orWhere('unidades.nom_unidad', 'LIKE', '%' . $busqueda . '%')
                                ->orWhere('asignaturas.nombre', 'LIKE', '%' . $busqueda . '%');
                        })
                        ->orderBy('asignaturas.nombre', 'ASC')
                        ->groupBy('laboratorios.id')
                        ->get();
                }
            } else {
                if (Auth::user()->tipo_usuario == "Profesor") {
                    $respuesta = Laboratorios::join('unidades', 'unidades.id', 'laboratorios.unidad')
                        ->join('modulos', 'modulos.id', 'unidades.modulo')
                        ->join('asig_prof', 'asig_prof.grado', 'modulos.id')
                        ->join('asignaturas', 'asignaturas.id', 'modulos.asignatura')
                        ->where('asig_prof.profesor', Auth::user()->id)
                        ->where('asignaturas.estado', 'ACTIVO')
                        ->where('laboratorios.estado', 'ACTIVO')
                        ->where(function ($query) use ($busqueda) {
                            $query->where('laboratorios.titulo', 'LIKE', '%' . $busqueda . '%')
                                ->orWhere('unidades.nom_unidad', 'LIKE', '%' . $busqueda . '%')
                                ->orWhere('asignaturas.nombre', 'LIKE', '%' . $busqueda . '%');
                        })
                        ->where(function ($query) use ($Usu) {
                            $query->where('laboratorios.docente', "")
                                ->orWhere('laboratorios.docente', $Usu);
                        })
                        ->orderBy('asignaturas.nombre', 'ASC')
                        ->groupBy('laboratorios.id')
                        ->get();
                } else {
                    $respuesta = Laboratorios::join('unidades', 'unidades.id', 'laboratorios.unidad')
                        ->join('modulos', 'modulos.id', 'unidades.modulo')
                        ->join('asignaturas', 'asignaturas.id', 'modulos.asignatura')
                        ->where('asignaturas.estado', 'ACTIVO')
                        ->where('laboratorios.estado', 'ACTIVO')
                        ->where(function ($query) use ($busqueda) {
                            $query->where('laboratorios.titulo', 'LIKE', '%' . $busqueda . '%')
                                ->orWhere('unidades.nom_unidad', 'LIKE', '%' . $busqueda . '%')
                                ->orWhere('asignaturas.nombre', 'LIKE', '%' . $busqueda . '%');
                        })
                        ->orderBy('asignaturas.nombre', 'ASC')
                        ->groupBy('laboratorios.id')
                        ->get();
                }
            }
        } else {

            if (!empty($Asig)) {

                if (Auth::user()->tipo_usuario == "Profesor") {
                    $respuesta = Laboratorios::join('unidades', 'unidades.id', 'laboratorios.unidad')
                        ->join('modulos', 'modulos.id', 'unidades.modulo')
                        ->join('asig_prof', 'asig_prof.grado', 'modulos.id')
                        ->join('asignaturas', 'asignaturas.id', 'modulos.asignatura')
                        ->where('modulos.id', $Asig)
                        ->where('asig_prof.profesor', Auth::user()->id)
                        ->where('asignaturas.estado', 'ACTIVO')
                        ->where('laboratorios.estado', 'ACTIVO')
                        ->where(function ($query) use ($Usu) {
                            $query->where('laboratorios.docente', "")
                                ->orWhere('laboratorios.docente', $Usu);
                        })
                        ->orderBy('asignaturas.nombre', 'ASC')
                        ->groupBy('laboratorios.id')
                        ->get();
                } else {
                    $respuesta = Laboratorios::join('unidades', 'unidades.id', 'laboratorios.unidad')
                        ->join('modulos', 'modulos.id', 'unidades.modulo')
                        ->join('asignaturas', 'asignaturas.id', 'modulos.asignatura')
                        ->where('modulos.id', $Asig)
                        ->where('asignaturas.estado', 'ACTIVO')
                        ->where('laboratorios.estado', 'ACTIVO')
                        ->orderBy('asignaturas.nombre', 'ASC')
                        ->groupBy('laboratorios.id')
                        ->get();

                }
            } else {

                if (Auth::user()->tipo_usuario == "Profesor") {

                    $respuesta = Laboratorios::join('unidades', 'unidades.id', 'laboratorios.unidad')
                        ->join('modulos', 'modulos.id', 'unidades.modulo')
                        ->join('asig_prof', 'asig_prof.grado', 'modulos.id')
                        ->join('asignaturas', 'asignaturas.id', 'modulos.asignatura')
                        ->where('asig_prof.profesor', Auth::user()->id)
                        ->where('asignaturas.estado', 'ACTIVO')
                        ->where('laboratorios.estado', 'ACTIVO')
                        ->where(function ($query) use ($Usu) {
                            $query->where('laboratorios.docente', "")
                                ->orWhere('laboratorios.docente', $Usu);
                        })
                        ->orderBy('asignaturas.nombre', 'ASC')
                        ->groupBy('laboratorios.id')
                        ->get();
                } else {

                    $respuesta = Laboratorios::join('unidades', 'unidades.id', 'laboratorios.unidad')
                        ->join('modulos', 'modulos.id', 'unidades.modulo')
                        ->join('asignaturas', 'asignaturas.id', 'modulos.asignatura')
                        ->where('asignaturas.estado', 'ACTIVO')
                        ->where('laboratorios.estado', 'ACTIVO')
                        ->orderBy('asignaturas.nombre', 'ASC')
                        ->groupBy('laboratorios.id')
                        ->get();
                }
            }
        }

        return $respuesta->count();
    }

    public static function Guardar($datos)
    {
        $Doc = "";
        if (Auth::user()->tipo_usuario == "Profesor") {
            $Doc = Auth::user()->id;
        }
        return Laboratorios::create([
            'modulo' => $datos['modulo'],
            'periodo' => $datos['periodo'],
            'unidad' => $datos['unidad'],
            'titulo' => $datos['titulo'],
            'objetivo' => $datos['objetivo'],
            'fund_teorico' => $datos['summernoteTeoria'],
            'materiales' => $datos['summernoteMateriales'],
            'habilitado' => "NO",
            'docente' => $Doc,
            'estado' => 'ACTIVO',
        ]);
    }

    public static function listar()
    {
        if (Auth::user()->tipo_usuario == "Profesor") {
            $Asig = Laboratorios::join("modulos", "modulos.id", "laboratorios.modulo")
                ->join("asignaturas", "asignaturas.id", "modulos.asignatura")
                ->join('asig_prof', 'asig_prof.grado', 'modulos.id')
                ->where('asignaturas.estado', 'ACTIVO')
                ->where('estado_modulo', 'ACTIVO')
                ->where('asig_prof.profesor', Auth::user()->id)
                ->select("modulos.*", "asignaturas.nombre")
                ->orderBy("modulos.grado_modulo", 'ASC')
                ->groupBy('modulos.id')
                ->get();
        } else {
            $Asig = Laboratorios::join("modulos", "modulos.id", "laboratorios.modulo")
                ->join("asignaturas", "asignaturas.id", "modulos.asignatura")
                ->where('asignaturas.estado', 'ACTIVO')
                ->where('estado_modulo', 'ACTIVO')
                ->select("modulos.*", "asignaturas.nombre")
                ->orderBy("modulos.grado_modulo", 'ASC')
                ->groupBy('modulos.id')
                ->get();
        }

//        dd($Asig);die();
        return $Asig;
    }

    public static function modificar($datos, $id)
    {
        $Doc = "";
        if (Auth::user()->tipo_usuario == "Profesor") {
            $Doc = Auth::user()->id;
        }
        $respuesta = Laboratorios::where(['id' => $id])->update([
            'modulo' => $datos['modulo'],
            'periodo' => $datos['periodo'],
            'unidad' => $datos['unidad'],
            'titulo' => $datos['titulo'],
            'objetivo' => $datos['objetivo'],
            'fund_teorico' => $datos['summernoteTeoria'],
            'materiales' => $datos['summernoteMateriales'],
            'habilitado' => "NO",
            'docente' => $Doc,
            'estado' => 'ACTIVO',
        ]);
        return $respuesta;
    }

    public static function BuscarLab($id)
    {

        return Laboratorios::findOrFail($id);
    }

    public static function ListLab($id)
    {

        $ListLab = Laboratorios::join('unidades', 'unidades.id', 'laboratorios.unidad')
            ->join('modulos', 'modulos.id', 'unidades.modulo')
            ->where('laboratorios.modulo', $id)
            ->selectRaw('count(*) as nlab,unidades.nom_unidad,unidades.des_unidad,unidades.id')
            ->groupBy('laboratorios.unidad')
            ->get();
        return $ListLab;

    }

    public static function ListLaboTemas($id)
    {

        $ListLab = Laboratorios::join('unidades', 'unidades.id', 'laboratorios.unidad')
            ->join('modulos', 'modulos.id', 'unidades.modulo')
            ->join('asig_prof', 'asig_prof.grado', 'modulos.id')
            ->join('asignaturas', 'asignaturas.id', 'modulos.asignatura')
            ->where('asignaturas.estado', 'ACTIVO')
            ->where('laboratorios.estado', 'ACTIVO')
            ->where('laboratorios.unidad', $id)
            ->select('laboratorios.*')
            ->orderBy('laboratorios.titulo', 'ASC')
            ->groupBy('laboratorios.id')
            ->get();
        return $ListLab;

    }

    public static function BuscarLaborarios($mod)
    {
        $Respuesta = Laboratorios::where('modulo', $mod)
            ->where("estado", 'ACTIVO');
        return $Respuesta;
    }

    public static function editarestado($id, $estado)
    {
        $Respuesta = Laboratorios::where('id', $id)->update([
            'estado' => $estado,
        ]);
        return $Respuesta;
    }

}
