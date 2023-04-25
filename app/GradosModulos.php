<?php

namespace App;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class GradosModulos extends Model
{

    protected $table = 'grados_modulos';
    protected $fillable = [
        'modulo',
        'objetivo_modulo',
        'presentacion_modulo',
        'avance_modulo',
        'grado_modulo',
        'estado_modulo',
    ];

    public static function ListModulos($id)
    {
        if (Auth::user()->tipo_usuario == "Administrador") {
            $Modulo = GradosModulos::join("modulos_transversales", "modulos_transversales.id", "grados_modulos.modulo")
                ->select('modulos_transversales.nombre', 'grados_modulos.*')
                ->where('grados_modulos.modulo', $id)
                ->where('estado_modulo', 'ACTIVO')
                ->get();
        } else if (Auth::user()->tipo_usuario == "Profesor") {
            $Modulo = GradosModulos::join("mod_prof", "mod_prof.grado", "grados_modulos.id")
                ->join("modulos_transversales", "modulos_transversales.id", "mod_prof.asignatura")
                ->where('mod_prof.profesor', Auth::user()->id)
                ->where('grados_modulos.modulo', $id)
                ->where('estado_modulo', 'ACTIVO')
                ->select("grados_modulos.*", 'modulos_transversales.nombre')
                ->groupBy("mod_prof.grado")
                ->orderBy("grados_modulos.grado_modulo", 'ASC')
                ->get();
        } else {
            $Modulo = GradosModulos::join("modulos_transversales", "modulos_transversales.id", "grados_modulos.modulo")
                ->where('grados_modulos.grado_modulo', Auth::user()->grado_usuario)
                ->where('modulos_transversales', $id)
                ->where('estado_modulo', 'ACTIVO')
                ->select("grados_modulos.*", 'modulos_transversales.nombre')
                ->get();
        }

        return $Modulo;
    }

    public static function Desmodulo($id)
    {
        $DesModulo = GradosModulos::join("modulos_transversales", "modulos_transversales.id", "grados_modulos.modulo")
            ->select('modulos_transversales.nombre', 'grados_modulos.*')
            ->where('grados_modulos.id', $id)
            ->first();
        return $DesModulo;
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
                    $respuesta = GradosModulos::join("modulos_transversales", "modulos_transversales.id", "grados_modulos.modulo")
                        ->join("mod_prof", "mod_prof.grado", "grados_modulos.id")
                        ->where('modulos_transversales.estado', 'ACTIVO')
                        ->where('grados_modulos.estado_modulo', 'ACTIVO')
                        ->where('grados_modulos.modulo', $Asig)
                        ->where("mod_prof.profesor", Auth::user()->id)
                        ->where(function ($query) use ($busqueda) {
                            $query->where('modulos_transversales.nombre', 'LIKE', '%' . $busqueda . '%')
                                ->orWhere('grados_modulos.grado_modulo', 'LIKE', '%' . $busqueda . '%');
                        })
                        ->select('grados_modulos.*', 'modulos_transversales.nombre')
                        ->groupBy('grados_modulos.id')
                        ->orderBy('grados_modulos.id', 'ASC')
                        ->limit($limit)->offset($offset);
                } else {
                    $respuesta = GradosModulos::join("modulos_transversales", "modulos_transversales.id", "grados_modulos.modulo")
                        ->where('modulos_transversales.estado', 'ACTIVO')
                        ->where('grados_modulos.estado_modulo', 'ACTIVO')
                        ->where('grados_modulos.modulo', $Asig)
                        ->where(function ($query) use ($busqueda) {
                            $query->where('modulos_transversales.nombre', 'LIKE', '%' . $busqueda . '%')
                                ->orWhere('grados_modulos.grado_modulo', 'LIKE', '%' . $busqueda . '%');
                        })
                        ->select('grados_modulos.*', 'modulos_transversales.nombre')
                        ->orderBy('grados_modulos.id', 'ASC')
                        ->limit($limit)->offset($offset);
                }
            } else {
                if (Auth::user()->tipo_usuario == "Profesor") {
                    $respuesta = GradosModulos::join("modulos_transversales", "modulos_transversales.id", "grados_modulos.modulo")
                        ->join("mod_prof", "mod_prof.grado", "grados_modulos.id")
                        ->where('modulos_transversales.estado', 'ACTIVO')
                        ->where('grados_modulos.estado_modulo', 'ACTIVO')
                        ->where("mod_prof.profesor", Auth::user()->id)
                        ->where(function ($query) use ($busqueda) {
                            $query->where('modulos_transversales.nombre', 'LIKE', '%' . $busqueda . '%')
                                ->orWhere('grados_modulos.grado_modulo', 'LIKE', '%' . $busqueda . '%');
                        })
                        ->select('grados_modulos.*', 'modulos_transversales.nombre')
                        ->groupBy('grados_modulos.id')
                        ->orderBy('grados_modulos.id', 'ASC')
                        ->limit($limit)->offset($offset);
                } else {
                    $respuesta = GradosModulos::join("modulos_transversales", "modulos_transversales.id", "grados_modulos.modulo")
                        ->where('modulos_transversales.estado', 'ACTIVO')
                        ->where('grados_modulos.estado_modulo', 'ACTIVO')
                        ->where(function ($query) use ($busqueda) {
                            $query->where('modulos_transversales.nombre', 'LIKE', '%' . $busqueda . '%')
                                ->orWhere('grados_modulos.grado_modulo', 'LIKE', '%' . $busqueda . '%');
                        })
                        ->select('grados_modulos.*', 'modulos_transversales.nombre')
                        ->orderBy('grados_modulos.id', 'ASC')
                        ->limit($limit)->offset($offset);
                }
            }
        } else {
            if (!empty($Asig)) {

                if (Auth::user()->tipo_usuario == "Profesor") {
                    $respuesta = GradosModulos::join("modulos_transversales", "modulos_transversales.id", "grados_modulos.modulo")
                        ->join("mod_prof", "mod_prof.grado", "grados_modulos.id")
                        ->where('modulos_transversales.estado', 'ACTIVO')
                        ->where('estado_modulo', 'ACTIVO')
                        ->where('grados_modulos.modulo', $Asig)
                        ->where("mod_prof.profesor", Auth::user()->id)
                        ->select('grados_modulos.*', 'modulos_transversales.nombre')
                        ->groupBy('grados_modulos.id')
                        ->orderBy('grados_modulos.id', 'ASC')
                        ->limit($limit)->offset($offset);
                } else {
                    $respuesta = GradosModulos::join("modulos_transversales", "modulos_transversales.id", "grados_modulos.modulo")
                        ->where('modulos_transversales.estado', 'ACTIVO')
                        ->where('estado_modulo', 'ACTIVO')
                        ->where('grados_modulos.modulo', $Asig)
                        ->select('grados_modulos.*', 'modulos_transversales.nombre')
                        ->orderBy('grados_modulos.id', 'ASC')
                        ->limit($limit)->offset($offset);
                }
            } else {
                if (Auth::user()->tipo_usuario == "Profesor") {
                    $respuesta = GradosModulos::join("modulos_transversales", "modulos_transversales.id", "grados_modulos.modulo")
                        ->join("mod_prof", "mod_prof.grado", "grados_modulos.id")
                        ->where('modulos_transversales.estado', 'ACTIVO')
                        ->where('estado_modulo', 'ACTIVO')
                        ->where("mod_prof.profesor", Auth::user()->id)
                        ->select('grados_modulos.*', 'modulos_transversales.nombre')
                        ->groupBy('grados_modulos.id')
                        ->orderBy('grados_modulos.id', 'ASC')
                        ->limit($limit)->offset($offset);
                } else {
                    $respuesta = GradosModulos::join("modulos_transversales", "modulos_transversales.id", "grados_modulos.modulo")
                        ->where('modulos_transversales.estado', 'ACTIVO')
                        ->where('estado_modulo', 'ACTIVO')
                        ->select('grados_modulos.*', 'modulos_transversales.nombre')
                        ->orderBy('grados_modulos.id', 'ASC')
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
                    $respuesta = GradosModulos::join("modulos_transversales", "modulos_transversales.id", "grados_modulos.modulo")
                        ->join("mod_prof", "mod_prof.grado", "grados_modulos.id")
                        ->where('modulos_transversales.estado', 'ACTIVO')
                        ->where('grados_modulos.estado_modulo', 'ACTIVO')
                        ->where('grados_modulos.modulo', $Asig)
                        ->where("mod_prof.profesor", Auth::user()->id)
                        ->where(function ($query) use ($busqueda) {
                            $query->where('modulos_transversales.nombre', 'LIKE', '%' . $busqueda . '%')
                                ->orWhere('grados_modulos.grado_modulo', 'LIKE', '%' . $busqueda . '%');
                        })
                        ->select('grados_modulos.*', 'modulos_transversales.nombre')
                        ->groupBy('grados_modulos.id')
                        ->orderBy('grados_modulos.id', 'ASC');
                } else {
                    $respuesta = GradosModulos::join("modulos_transversales", "modulos_transversales.id", "grados_modulos.modulo")
                        ->where('modulos_transversales.estado', 'ACTIVO')
                        ->where('grados_modulos.estado_modulo', 'ACTIVO')
                        ->where('grados_modulos.modulo', $Asig)
                        ->where(function ($query) use ($busqueda) {
                            $query->where('modulos_transversales.nombre', 'LIKE', '%' . $busqueda . '%')
                                ->orWhere('grados_modulos.grado_modulo', 'LIKE', '%' . $busqueda . '%');
                        })
                        ->select('grados_modulos.*', 'modulos_transversales.nombre')
                        ->orderBy('grados_modulos.id', 'ASC');
                }
            } else {
                if (Auth::user()->tipo_usuario == "Profesor") {
                    $respuesta = GradosModulos::join("modulos_transversales", "modulos_transversales.id", "grados_modulos.modulo")
                        ->join("mod_prof", "mod_prof.grado", "grados_modulos.id")
                        ->where('modulos_transversales.estado', 'ACTIVO')
                        ->where('grados_modulos.estado_modulo', 'ACTIVO')
                        ->where("mod_prof.profesor", Auth::user()->id)
                        ->where(function ($query) use ($busqueda) {
                            $query->where('modulos_transversales.nombre', 'LIKE', '%' . $busqueda . '%')
                                ->orWhere('grados_modulos.grado_modulo', 'LIKE', '%' . $busqueda . '%');
                        })
                        ->select('grados_modulos.*', 'modulos_transversales.nombre')
                        ->groupBy('grados_modulos.id')
                        ->orderBy('grados_modulos.id', 'ASC');
                } else {
                    $respuesta = GradosModulos::join("modulos_transversales", "modulos_transversales.id", "grados_modulos.modulo")
                        ->where('modulos_transversales.estado', 'ACTIVO')
                        ->where('grados_modulos.estado_modulo', 'ACTIVO')
                        ->where(function ($query) use ($busqueda) {
                            $query->where('modulos_transversales.nombre', 'LIKE', '%' . $busqueda . '%')
                                ->orWhere('grados_modulos.grado_modulo', 'LIKE', '%' . $busqueda . '%');
                        })
                        ->select('grados_modulos.*', 'modulos_transversales.nombre')
                        ->orderBy('grados_modulos.id', 'ASC');
                }
            }
        } else {
            if (!empty($Asig)) {

                if (Auth::user()->tipo_usuario == "Profesor") {
                    $respuesta = GradosModulos::join("modulos_transversales", "modulos_transversales.id", "grados_modulos.modulo")
                        ->join("mod_prof", "mod_prof.grado", "grados_modulos.id")
                        ->where('modulos_transversales.estado', 'ACTIVO')
                        ->where('estado_modulo', 'ACTIVO')
                        ->where('grados_modulos.modulo', $Asig)
                        ->where("mod_prof.profesor", Auth::user()->id)
                        ->select('grados_modulos.*', 'modulos_transversales.nombre')
                        ->groupBy('grados_modulos.id')
                        ->orderBy('grados_modulos.id', 'ASC');
                } else {
                    $respuesta = GradosModulos::join("modulos_transversales", "modulos_transversales.id", "grados_modulos.modulo")
                        ->where('modulos_transversales.estado', 'ACTIVO')
                        ->where('estado_modulo', 'ACTIVO')
                        ->where('grados_modulos.modulo', $Asig)
                        ->select('grados_modulos.*', 'modulos_transversales.nombre')
                        ->orderBy('grados_modulos.id', 'ASC');
                }
            } else {
                if (Auth::user()->tipo_usuario == "Profesor") {
                    $respuesta = GradosModulos::join("modulos_transversales", "modulos_transversales.id", "grados_modulos.modulo")
                        ->join("mod_prof", "mod_prof.grado", "grados_modulos.id")
                        ->where('modulos_transversales.estado', 'ACTIVO')
                        ->where('estado_modulo', 'ACTIVO')
                        ->where("mod_prof.profesor", Auth::user()->id)
                        ->select('grados_modulos.*', 'modulos_transversales.nombre')
                        ->groupBy('grados_modulos.id')
                        ->orderBy('grados_modulos.id', 'ASC');
                } else {
                    $respuesta = GradosModulos::join("modulos_transversales", "modulos_transversales.id", "grados_modulos.modulo")
                        ->where('modulos_transversales.estado', 'ACTIVO')
                        ->where('estado_modulo', 'ACTIVO')
                        ->select('grados_modulos.*', 'modulos_transversales.nombre')
                        ->orderBy('grados_modulos.id', 'ASC');
                }
            }
        }
        return $respuesta->count();
    }

    public static function listar()
    {
        if (Auth::user()->tipo_usuario == "Profesor") {
            $Asig = GradosModulos::join("modulos_transversales", "modulos_transversales.id", "grados_modulos.modulo")
                ->join('mod_prof', 'mod_prof.grado', 'grados_modulos.id')
                ->where('modulos_transversales.estado', 'ACTIVO')
                ->where('estado_modulo', 'ACTIVO')
                ->where('mod_prof.profesor', Auth::user()->id)
                ->select("grados_modulos.*", "modulos_transversales.nombre")
                ->orderBy("grados_modulos.grado_modulo", 'ASC')
                ->groupBy('grados_modulos.id')
                ->get();
        } else {
            $Asig = GradosModulos::join("modulos_transversales", "modulos_transversales.id", "grados_modulos.modulo")
                ->where('modulos_transversales.estado', 'ACTIVO')
                ->where('estado_modulo', 'ACTIVO')
                ->select("grados_modulos.*", "modulos_transversales.nombre")
                ->orderBy("grados_modulos.grado_modulo", 'ASC')
                ->get();
        }

        //        dd($Asig);die();
        return $Asig;
    }

    public static function ListarxAsig($idAsig)
    {
        $Grado = GradosModulos::where('modulo', $idAsig)
            ->get();
        return $Grado;
    }

    public static function BuscarAsig($id)
    {
        return GradosModulos::findOrFail($id);
    }

    public static function VerfDel($id)
    {
        $VerfDel = GradosModulos::where('id', $id)
            ->where('id', '>=', 1)
            ->where('id', '<=', 38)
            ->get();

        return $VerfDel;
    }

    public static function Guardar($data)
    {

        return GradosModulos::create([
            'modulo' => $data['modulo'],
            'grado_modulo' => $data['grado_modulo'],
            'objetivo_modulo' => $data['objetivo_modulo'],
            'presentacion_modulo' => $data['presentacion_modulo'],
            'avance_modulo' => '0',
            'estado_modulo' => 'ACTIVO',
        ]);
    }

    public static function modificar($data, $id)
    {
        $respuesta = GradosModulos::where(['id' => $id])->update([
            'modulo' => $data['modulo'],
            'grado_modulo' => $data['grado_modulo'],
            'objetivo_modulo' => $data['objetivo_modulo'],
            'presentacion_modulo' => $data['presentacion_modulo'],
        ]);
        return $respuesta;
    }

    public static function editarestado($id, $estado)
    {
        $Respuesta = GradosModulos::where('id', $id)->update([
            'estado_modulo' => $estado,
        ]);
        return $Respuesta;
    }

    /* public static function modulo($grado) {
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
}*/
}
