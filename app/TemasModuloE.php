<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TemasModuloE extends Model
{
    protected $table = 'temas_moduloe';
    protected $fillable = [
        'titulo',
        'asignatura',
        'componente',
        'tipo_contenido',
        'animacion',
        'estado',
    ];

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
                $respuesta = TemasModuloE::leftJoin('asignaturas_mode', 'asignaturas_mode.id', '=', 'temas_moduloe.asignatura')
                    ->leftjoin('componentes', 'temas_moduloe.componente', 'componentes.id')
                    ->where('temas_moduloe.estado', 'ACTIVO')
                    ->where('temas_moduloe.asignatura', $Asig)
                    ->where(function ($query) use ($busqueda) {
                        $query->where('nombre', 'LIKE', '%' . $busqueda . '%');
                    })
                    ->select("temas_moduloe.*", "asignaturas_mode.nombre", "asignaturas_mode.grado", "componentes.nombre  as ncom")
                    ->orderBy('nombre', 'ASC')
                    ->limit($limit)->offset($offset);
            } else {
                $respuesta = TemasModuloE::leftJoin('asignaturas_mode', 'asignaturas_mode.id', '=', 'temas_moduloe.asignatura')
                    ->leftjoin('componentes', 'temas_moduloe.componente', 'componentes.id')
                    ->where('temas_moduloe.estado', 'ACTIVO')
                    ->where(function ($query) use ($busqueda) {
                        $query->where('nombre', 'LIKE', '%' . $busqueda . '%');
                    })
                    ->select("temas_moduloe.*", "asignaturas_mode.nombre", "asignaturas_mode.grado", "componentes.nombre as ncom")
                    ->orderBy('nombre', 'ASC')
                    ->limit($limit)->offset($offset);
            }

        } else {
            if (!empty($Asig)) {
                $respuesta = TemasModuloE::leftJoin('asignaturas_mode', 'asignaturas_mode.id', '=', 'temas_moduloe.asignatura')
                    ->leftjoin('componentes', 'temas_moduloe.componente', 'componentes.id')
                    ->where('temas_moduloe.estado', 'ACTIVO')
                    ->where('temas_moduloe.asignatura', $Asig)
                    ->select("temas_moduloe.*", "asignaturas_mode.nombre", "asignaturas_mode.grado", "componentes.nombre as ncom")
                    ->orderBy('nombre', 'ASC')
                    ->limit($limit)->offset($offset);
            } else {
                $respuesta = TemasModuloE::leftJoin('asignaturas_mode', 'asignaturas_mode.id', '=', 'temas_moduloe.asignatura')
                    ->leftjoin('componentes', 'temas_moduloe.componente', 'componentes.id')
                    ->where('temas_moduloe.estado', 'ACTIVO')
                    ->select("temas_moduloe.*", "asignaturas_mode.nombre", "asignaturas_mode.grado", "componentes.nombre as ncom")
                    ->orderBy('nombre', 'ASC')
                    ->limit($limit)->offset($offset);
            }

        }

        return $respuesta->get();
    }

    public static function numero_de_registros($busqueda, $Asig)
    {
        if (!empty($busqueda)) {
            if (!empty($Asig)) {
                $respuesta = TemasModuloE::where('estado', 'ACTIVO')
                    ->where('temas_moduloe.asignatura', $Asig)
                    ->where(function ($query) use ($busqueda) {
                        $query->where('nombre', 'LIKE', '%' . $busqueda . '%');
                    })
                    ->orderBy('nombre', 'ASC');
            } else {
                $respuesta = TemasModuloE::where('estado', 'ACTIVO')
                    ->where(function ($query) use ($busqueda) {
                        $query->where('nombre', 'LIKE', '%' . $busqueda . '%');
                    })
                    ->orderBy('nombre', 'ASC');
            }

        } else {
            if (!empty($Asig)) {
                $respuesta = TemasModuloE::where('estado', 'ACTIVO')
                    ->where('temas_moduloe.asignatura', $Asig)
                    ->orderBy('nombre', 'ASC');
            } else {
                $respuesta = TemasModuloE::where('estado', 'ACTIVO')
                    ->orderBy('nombre', 'ASC');
            }

        }
        return $respuesta->count();
    }

    public static function Guardar($data)
    {
        return TemasModuloE::create([
            'titulo' => $data['titulo'],
            'asignatura' => $data['asignatura'],
            'componente' => $data['componente'],
            'tipo_contenido' => $data['tipo_contenido'],
            'animacion' => $data['animacion'],
            'estado' => 'ACTIVO',
        ]);
    }

    public static function BuscarTem($id)
    {
        return TemasModuloE::findOrFail($id);
    }

    public static function modificar($data, $id)
    {
        $respuesta = TemasModuloE::where(['id' => $id])->update([
            'titulo' => $data['titulo'],
            'asignatura' => $data['asignatura'],
            'componente' => $data['componente'],
            'tipo_contenido' => $data['tipo_contenido'],
            'animacion' => $data['animacion'],
        ]);
        return $respuesta;
    }

    public static function listar()
    {
        $Asig = TemasModuloE::where('estado', "ACTIVO")
            ->get();
        return $Asig;
    }

    public static function listarxAssig($Asig)
    {
        $Asig = TemasModuloE::where('estado', "ACTIVO")
            ->where('asignatura', $Asig)
            ->get();
        return $Asig;
    }

    public static function BuscarNomAsig($tem)
    {
        $Asig = TemasModuloE::join("asignaturas_mode", "asignaturas_mode.id", "temas_moduloe.asignatura")
            ->where('temas_moduloe.id', $tem)
            ->select('asignaturas_mode.nombre')
            ->first();
        return $Asig;
    }

    public static function editarestado($id, $estado)
    {
        $Respuesta = TemasModuloE::where('id', $id)->update([
            'estado' => $estado,
        ]);
        return $Respuesta;
    }

}
