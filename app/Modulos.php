<?php

namespace App;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class Modulos extends Model
{

    protected $table = 'modulos';
    protected $fillable = [
        'asignatura',
        'objetivo_modulo',
        'presentacion_modulo',
        'avance_modulo',
        'grado_modulo',
        'estado_modulo',
        'area',
    ];

    public static function ListModulos($id)
    {
        if (Auth::user()->tipo_usuario == "Administrador") {
            $Modulo = Modulos::join("asignaturas", "asignaturas.id", "modulos.asignatura")
                ->select('asignaturas.nombre', 'modulos.*')
                ->where('modulos.asignatura', $id)
                ->where('estado_modulo', 'ACTIVO')
                ->get();
        } else if (Auth::user()->tipo_usuario == "Profesor") {
            $Modulo = Modulos::join("asig_prof", "asig_prof.grado", "modulos.id")
                ->join("asignaturas", "asignaturas.id", "asig_prof.asignatura")
                ->where('asig_prof.profesor', Auth::user()->id)
                ->where('modulos.asignatura', $id)
                ->where('estado_modulo', 'ACTIVO')
                ->select("modulos.*", 'asignaturas.nombre')
                ->groupBy("asig_prof.grado")
                ->orderBy("modulos.grado_modulo", 'ASC')
                ->get();
        } else {
            $Modulo = Modulos::join("asignaturas", "asignaturas.id", "modulos.asignatura")
                ->where('modulos.grado_modulo', Auth::user()->grado_usuario)
                ->where('asignatura', $id)
                ->where('estado_modulo', 'ACTIVO')
                ->select("modulos.*", 'asignaturas.nombre')
                ->get();
        }

        return $Modulo;
    }

    public static function Desmodulo($id)
    {
        $DesModulo = Modulos::join("asignaturas", "asignaturas.id", "modulos.asignatura")
            ->select('asignaturas.nombre', 'modulos.*')
            ->where('modulos.id', $id)
            ->first();
        return $DesModulo;
    }

    public static function VerfDel($idAsig)
    {
        $VerfDel = Modulos::where('id', $idAsig)
        ->where('id', '>=', 1)
        ->where('id', '<=', 86)
        ->get();
        
        return $VerfDel;
    }

    public static function Gestion($busqueda, $pagina, $limit, $Asig)
    {

        if ($pagina == "1") {
            $offset = 0;
        } else {
            $pagina--;
            $offset = $pagina * $limit;
        }
        if (!empty($busqueda)) {
            if (!empty($Asig)) {
                if (Auth::user()->tipo_usuario == "Profesor") {
                    $respuesta = Modulos::join("asignaturas", "asignaturas.id", "modulos.asignatura")
                        ->join('asig_prof', 'asig_prof.grado', 'modulos.id')
                        ->where('asignaturas.estado', 'ACTIVO')
                        ->where('modulos.estado_modulo', 'ACTIVO')
                        ->where('modulos.asignatura', $Asig)
                        ->where('asig_prof.profesor', Auth::user()->id)
                        ->where(function ($query) use ($busqueda) {
                            $query->where('asignaturas.nombre', 'LIKE', '%' . $busqueda . '%')
                                ->orWhere('modulos.grado_modulo', 'LIKE', '%' . $busqueda . '%');
                        })
                        ->select('modulos.*', 'asignaturas.nombre')
                        ->groupBy("modulos.id")
                        ->orderBy('modulos.id', 'ASC')
                        ->limit($limit)->offset($offset);
                } else {
                    $respuesta = Modulos::join("asignaturas", "asignaturas.id", "modulos.asignatura")
                        ->where('asignaturas.estado', 'ACTIVO')
                        ->where('modulos.estado_modulo', 'ACTIVO')
                        ->where('modulos.asignatura', $Asig)
                        ->where(function ($query) use ($busqueda) {
                            $query->where('asignaturas.nombre', 'LIKE', '%' . $busqueda . '%')
                                ->orWhere('modulos.grado_modulo', 'LIKE', '%' . $busqueda . '%');
                        })
                        ->select('modulos.*', 'asignaturas.nombre')
                        ->orderBy('modulos.id', 'ASC')
                        ->limit($limit)->offset($offset);
                }

            } else {
                if (Auth::user()->tipo_usuario == "Profesor") {
                    $respuesta = Modulos::join("asignaturas", "asignaturas.id", "modulos.asignatura")
                        ->join('asig_prof', 'asig_prof.grado', 'modulos.id')
                        ->where('asignaturas.estado', 'ACTIVO')
                        ->where('modulos.estado_modulo', 'ACTIVO')
                        ->where('asig_prof.profesor', Auth::user()->id)
                        ->where(function ($query) use ($busqueda) {
                            $query->where('asignaturas.nombre', 'LIKE', '%' . $busqueda . '%')
                                ->orWhere('modulos.grado_modulo', 'LIKE', '%' . $busqueda . '%');
                        })
                        ->select('modulos.*', 'asignaturas.nombre')
                        ->groupBy("modulos.id")
                        ->orderBy('modulos.id', 'ASC')
                        ->limit($limit)->offset($offset);
                } else {
                    $respuesta = Modulos::join("asignaturas", "asignaturas.id", "modulos.asignatura")
                        ->where('asignaturas.estado', 'ACTIVO')
                        ->where('modulos.estado_modulo', 'ACTIVO')
                        ->where(function ($query) use ($busqueda) {
                            $query->where('asignaturas.nombre', 'LIKE', '%' . $busqueda . '%')
                                ->orWhere('modulos.grado_modulo', 'LIKE', '%' . $busqueda . '%');
                        })
                        ->select('modulos.*', 'asignaturas.nombre')
                        ->orderBy('modulos.id', 'ASC')
                        ->limit($limit)->offset($offset);
                }

            }
        } else {
            if (!empty($Asig)) {
                if (Auth::user()->tipo_usuario == "Profesor") {

                    $respuesta = Modulos::join("asignaturas", "asignaturas.id", "modulos.asignatura")
                        ->join('asig_prof', 'asig_prof.grado', 'modulos.id')
                        ->where('asignaturas.estado', 'ACTIVO')
                        ->where('estado_modulo', 'ACTIVO')
                        ->where('modulos.asignatura', $Asig)
                        ->where('asig_prof.profesor', Auth::user()->id)
                        ->select('modulos.*', 'asignaturas.nombre')
                        ->groupBy("modulos.id")
                        ->orderBy('modulos.id', 'ASC')
                        ->limit($limit)->offset($offset);
                } else {

                    $respuesta = Modulos::join("asignaturas", "asignaturas.id", "modulos.asignatura")
                        ->where('asignaturas.estado', 'ACTIVO')
                        ->where('estado_modulo', 'ACTIVO')
                        ->where('modulos.asignatura', $Asig)
                        ->select('modulos.*', 'asignaturas.nombre')
                        ->orderBy('modulos.id', 'ASC')
                        ->limit($limit)->offset($offset);
                }

            } else {
                if (Auth::user()->tipo_usuario == "Profesor") {
                    $respuesta = Modulos::join("asignaturas", "asignaturas.id", "modulos.asignatura")
                        ->join('asig_prof', 'asig_prof.grado', 'modulos.id')
                        ->where('asignaturas.estado', 'ACTIVO')
                        ->where('estado_modulo', 'ACTIVO')
                        ->select('modulos.*', 'asignaturas.nombre')
                        ->where('asig_prof.profesor', Auth::user()->id)
                        ->orderBy('modulos.id', 'ASC')
                        ->groupBy("modulos.id")
                        ->limit($limit)->offset($offset);
                } else {
                    $respuesta = Modulos::join("asignaturas", "asignaturas.id", "modulos.asignatura")
                        ->where('asignaturas.estado', 'ACTIVO')
                        ->where('estado_modulo', 'ACTIVO')
                        ->select('modulos.*', 'asignaturas.nombre')
                        ->orderBy('modulos.id', 'ASC')
                        ->limit($limit)->offset($offset);
                }

            }
        }

        return $respuesta->get();
    }

    public static function numero_de_registros($busqueda, $Asig)
    {
        if (!empty($busqueda)) {
            if (!empty($Asig)) {
                if (Auth::user()->tipo_usuario == "Profesor") {
                    $respuesta = Modulos::join("asignaturas", "asignaturas.id", "modulos.asignatura")
                        ->join('asig_prof', 'asig_prof.grado', 'modulos.id')
                        ->where('asignaturas.estado', 'ACTIVO')
                        ->where('modulos.estado_modulo', 'ACTIVO')
                        ->where('modulos.asignatura', $Asig)
                        ->where('asig_prof.profesor', Auth::user()->id)
                        ->where(function ($query) use ($busqueda) {
                            $query->where('asignaturas.nombre', 'LIKE', '%' . $busqueda . '%')
                                ->orWhere('modulos.grado_modulo', 'LIKE', '%' . $busqueda . '%');
                        })
                        ->select('modulos.*', 'asignaturas.nombre')
                        ->groupBy("modulos.id")
                        ->orderBy('modulos.id', 'ASC');
                } else {
                    $respuesta = Modulos::join("asignaturas", "asignaturas.id", "modulos.asignatura")
                        ->where('asignaturas.estado', 'ACTIVO')
                        ->where('modulos.estado_modulo', 'ACTIVO')
                        ->where('modulos.asignatura', $Asig)
                        ->where(function ($query) use ($busqueda) {
                            $query->where('asignaturas.nombre', 'LIKE', '%' . $busqueda . '%')
                                ->orWhere('modulos.grado_modulo', 'LIKE', '%' . $busqueda . '%');
                        })
                        ->select('modulos.*', 'asignaturas.nombre')
                        ->orderBy('modulos.id', 'ASC');
                }

            } else {
                if (Auth::user()->tipo_usuario == "Profesor") {
                    $respuesta = Modulos::join("asignaturas", "asignaturas.id", "modulos.asignatura")
                        ->join('asig_prof', 'asig_prof.grado', 'modulos.id')
                        ->where('asignaturas.estado', 'ACTIVO')
                        ->where('modulos.estado_modulo', 'ACTIVO')
                        ->where('asig_prof.profesor', Auth::user()->id)
                        ->where(function ($query) use ($busqueda) {
                            $query->where('asignaturas.nombre', 'LIKE', '%' . $busqueda . '%')
                                ->orWhere('modulos.grado_modulo', 'LIKE', '%' . $busqueda . '%');
                        })
                        ->select('modulos.*', 'asignaturas.nombre')
                        ->groupBy("modulos.id")
                        ->orderBy('modulos.id', 'ASC');
                } else {
                    $respuesta = Modulos::join("asignaturas", "asignaturas.id", "modulos.asignatura")
                        ->where('asignaturas.estado', 'ACTIVO')
                        ->where('modulos.estado_modulo', 'ACTIVO')
                        ->where(function ($query) use ($busqueda) {
                            $query->where('asignaturas.nombre', 'LIKE', '%' . $busqueda . '%')
                                ->orWhere('modulos.grado_modulo', 'LIKE', '%' . $busqueda . '%');
                        })
                        ->select('modulos.*', 'asignaturas.nombre')
                        ->orderBy('modulos.id', 'ASC');
                }

            }
        } else {
            if (!empty($Asig)) {
                if (Auth::user()->tipo_usuario == "Profesor") {

                    $respuesta = Modulos::join("asignaturas", "asignaturas.id", "modulos.asignatura")
                        ->join('asig_prof', 'asig_prof.grado', 'modulos.id')
                        ->where('asignaturas.estado', 'ACTIVO')
                        ->where('estado_modulo', 'ACTIVO')
                        ->where('modulos.asignatura', $Asig)
                        ->where('asig_prof.profesor', Auth::user()->id)
                        ->select('modulos.*', 'asignaturas.nombre')
                        ->groupBy("modulos.id")
                        ->orderBy('modulos.id', 'ASC');
                } else {

                    $respuesta = Modulos::join("asignaturas", "asignaturas.id", "modulos.asignatura")
                        ->where('asignaturas.estado', 'ACTIVO')
                        ->where('estado_modulo', 'ACTIVO')
                        ->where('modulos.asignatura', $Asig)
                        ->select('modulos.*', 'asignaturas.nombre')
                        ->orderBy('modulos.id', 'ASC');
                }

            } else {
                if (Auth::user()->tipo_usuario == "Profesor") {
                    $respuesta = Modulos::join("asignaturas", "asignaturas.id", "modulos.asignatura")
                        ->join('asig_prof', 'asig_prof.grado', 'modulos.id')
                        ->where('asignaturas.estado', 'ACTIVO')
                        ->where('estado_modulo', 'ACTIVO')
                        ->select('modulos.*', 'asignaturas.nombre')
                        ->where('asig_prof.profesor', Auth::user()->id)
                        ->orderBy('modulos.id', 'ASC')
                        ->groupBy("modulos.id");
                } else {
                    $respuesta = Modulos::join("asignaturas", "asignaturas.id", "modulos.asignatura")
                        ->where('asignaturas.estado', 'ACTIVO')
                        ->where('estado_modulo', 'ACTIVO')
                        ->select('modulos.*', 'asignaturas.nombre')
                        ->orderBy('modulos.id', 'ASC');
                }

            }
        }
        return $respuesta->count();
    }

    public static function listar()
    {
        if (Auth::user()->tipo_usuario == "Profesor") {
            $Asig = Modulos::join("asignaturas", "asignaturas.id", "modulos.asignatura")
                ->join('asig_prof', 'asig_prof.grado', 'modulos.id')
                ->where('asignaturas.estado', 'ACTIVO')
                ->where('estado_modulo', 'ACTIVO')
                ->where('asig_prof.profesor', Auth::user()->id)
                ->select("modulos.*", "asignaturas.nombre")
                ->orderBy("modulos.grado_modulo", 'ASC')
                ->groupBy('modulos.id')
                ->get();
        } else {
            $Asig = Modulos::join("asignaturas", "asignaturas.id", "modulos.asignatura")
                ->where('asignaturas.estado', 'ACTIVO')
                ->where('estado_modulo', 'ACTIVO')
                ->select("modulos.*", "asignaturas.nombre")
                ->orderBy("modulos.grado_modulo", 'ASC')
                ->get();
        }


        return $Asig;
    }

    public static function ListarxAsig($idAsig)
    {
        $Grado = Modulos::where('asignatura', $idAsig)
            ->get();
        return $Grado;
    }

    public static function BuscarAsig($id)
    {
        return Modulos::findOrFail($id);
    }

    public static function Guardar($data)
    {
        return Modulos::create([
            'asignatura' => $data['nombre'],
            'grado_modulo' => $data['grado_modulo'],
            'objetivo_modulo' => $data['objetivo_modulo'],
            'presentacion_modulo' => $data['presentacion_modulo'],
            'avance_modulo' => '0',
            'estado_modulo' => 'ACTIVO',
            'area' => $data['area'],
        ]);
    }

    public static function modificar($data, $id)
    {
        $respuesta = Modulos::where(['id' => $id])->update([
            'asignatura' => $data['nombre'],
            'grado_modulo' => $data['grado_modulo'],
            'objetivo_modulo' => $data['objetivo_modulo'],
            'presentacion_modulo' => $data['presentacion_modulo'],
            'area' => $data['area'],
        ]);
        return $respuesta;
    }

    public static function editarestado($id, $estado)
    {
        $Respuesta = Modulos::where('id', $id)->update([
            'estado_modulo' => $estado,
        ]);
        return $Respuesta;
    }

    public static function modulo($grado)
    {
        if (Auth::user()->tipo_usuario == "Profesor") {
            $Modulo = Asignaturas::join("asig_prof", "modulos.id", "asig_prof.asignatura")
                ->join("profesores", "profesores.usuario_profesor", "asig_prof.profesor")
                ->where('profesores.usuario_profesor', Auth::user()->id)
                ->where('estado_modulo', 'ACTIVO')
                ->select("modulos.*")
                ->get();
        } else if (Auth::user()->tipo_usuario == "Estudiante") {
            $Modulo = Asignaturas::where('grado_modulo', $grado)
                ->where('estado_modulo', 'ACTIVO')
                ->get();
        } else if (Auth::user()->tipo_usuario == "Administrador") {
            $Modulo = Asignaturas::where('estado_modulo', 'ACTIVO')
                ->get();
        }
        return $Modulo;
    }

}
