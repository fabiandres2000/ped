<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AsignaturasModuloE extends Model
{
    protected $table = 'asignaturas_mode';
    protected $fillable = [
        'nombre',
        'grado',
        'area',
        'descripcion',
        'imagen',
        'docente',
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
                $respuesta = AsignaturasModuloE::join("areas_me","areas_me.id","asignaturas_mode.area")
                ->where('asignaturas_mode.estado', 'ACTIVO')
                    ->where('asignaturas_mode.area', $Asig)
                    ->where(function ($query) use ($busqueda) {
                        $query->where('nombre', 'LIKE', '%' . $busqueda . '%');
                    })
                    ->select("asignaturas_mode.*","areas_me.nombre_area")
                    ->orderBy('nombre', 'ASC')
                    ->limit($limit)->offset($offset);
            } else {
                $respuesta = AsignaturasModuloE::join("areas_me","areas_me.id","asignaturas_mode.area")
                ->where('asignaturas_mode.estado', 'ACTIVO')
                    ->where(function ($query) use ($busqueda) {
                        $query->where('nombre', 'LIKE', '%' . $busqueda . '%');
                    })
                    ->select("asignaturas_mode.*","areas_me.nombre_area")
                    ->orderBy('nombre', 'ASC')
                    ->limit($limit)->offset($offset);
            }

        } else {
            if (!empty($Asig)) {
                $respuesta = AsignaturasModuloE::join("areas_me","areas_me.id","asignaturas_mode.area")
                ->where('asignaturas_mode.estado', 'ACTIVO')
                    ->where('asignaturas_mode.area', $Asig)
                    ->select("asignaturas_mode.*","areas_me.nombre_area")
                    ->orderBy('nombre', 'ASC')
                    ->limit($limit)->offset($offset);
            } else {
                $respuesta = AsignaturasModuloE::join("areas_me","areas_me.id","asignaturas_mode.area")
                ->where('asignaturas_mode.estado', 'ACTIVO')
                    ->select("asignaturas_mode.*","areas_me.nombre_area")
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
                $respuesta = AsignaturasModuloE::where('estado', 'ACTIVO')
                    ->where('asignaturas_mode.area', $Asig)
                    ->where(function ($query) use ($busqueda) {
                        $query->where('nombre', 'LIKE', '%' . $busqueda . '%');
                    })
                    ->orderBy('nombre', 'ASC');
            } else {
                $respuesta = AsignaturasModuloE::where('estado', 'ACTIVO')
                    ->where(function ($query) use ($busqueda) {
                        $query->where('nombre', 'LIKE', '%' . $busqueda . '%');
                    })
                    ->orderBy('nombre', 'ASC');
            }

        } else {
            if (!empty($Asig)) {
                $respuesta = AsignaturasModuloE::where('estado', 'ACTIVO')
                    ->where('asignaturas_mode.area', $Asig)
                    ->orderBy('nombre', 'ASC');
            } else {
                $respuesta = AsignaturasModuloE::where('estado', 'ACTIVO')
                    ->orderBy('nombre', 'ASC');
            }

        }
        return $respuesta->count();
    }

    public static function listar()
    {
        $Asig = AsignaturasModuloE::where('estado', "ACTIVO")
            ->get();
        return $Asig;
    }



    public static function Guardar($data)
    {
        return AsignaturasModuloE::create([
            'nombre' => $data['nombre'],
            'grado' => $data['grado'],
            'area' => $data['area'],
            'descripcion' => $data['descripcion'],
            'imagen' => $data['img'],
            'docente' => $data['docente'],
            'estado' => 'ACTIVO',
        ]);
    }

    public static function BuscarAsig($id)
    {
        return AsignaturasModuloE::findOrFail($id);
    }

    public static function modificar($data, $id)
    {
        $respuesta = AsignaturasModuloE::where(['id' => $id])->update([
            'nombre' => $data['nombre'],
            'grado' => $data['grado'],
            'area' => $data['area'],
            'descripcion' => $data['descripcion'],
            'imagen' => $data['img'],
            'docente' => $data['docente']
        ]);
        return $respuesta;
    }

    public static function editarestado($id, $estado)
    {
        $Respuesta = AsignaturasModuloE::where('id', $id)->update([
            'estado' => $estado,
        ]);
        return $Respuesta;
    }

    public static function AsigxUsu($Grado, $TipUsu)
    {
        if ($TipUsu == "Administrador") {

        } else if ($TipUsu == "Profesor") {

        } else {
           
            $Asig = AsignaturasModuloE::where('grado', $Grado)
                ->where('estado', 'ACTIVO')
                ->get();
        }
        return $Asig;

    }

}
