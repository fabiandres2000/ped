<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ComponentesModuloE extends Model
{
    protected $table = 'componentes';
    protected $fillable = [
        'nombre',
        'grado',
        'descripcion',
        'estado',
    ];

    public static function Gestion($busqueda, $pagina, $limit)
    {
        if ($pagina == "1") {
            $offset = 0;
        } else {
            $pagina--;
            $offset = $pagina * $limit;
        }
        if (!empty($busqueda)) {

            $respuesta = ComponentesModuloE::where('componentes.estado', 'ACTIVO')
                ->where(function ($query) use ($busqueda) {
                    $query->where('nombre', 'LIKE', '%' . $busqueda . '%');

                })
                ->select("componentes.*")
                ->orderBy('nombre', 'ASC')
                ->limit($limit)->offset($offset);

        } else {

            $respuesta = ComponentesModuloE::where('componentes.estado', 'ACTIVO')
                ->select("componentes.*")
                ->orderBy('nombre', 'ASC')
                ->limit($limit)->offset($offset);
        }

        return $respuesta->get();
    }

    public static function numero_de_registros($busqueda)
    {
        if (!empty($busqueda)) {

            $respuesta = ComponentesModuloE::where('estado', 'ACTIVO')
                ->where(function ($query) use ($busqueda) {
                    $query->where('nombre', 'LIKE', '%' . $busqueda . '%');
                })
                ->orderBy('nombre', 'ASC');

        } else {

            $respuesta = ComponentesModuloE::where('estado', 'ACTIVO')
                ->orderBy('nombre', 'ASC');

        }
        return $respuesta->count();
    }

    public static function Guardar($data)
    {
        return ComponentesModuloE::create([
            'nombre' => $data['nombre'],
            'grado' => $data['grado'],
            'descripcion' => $data['descripcion'],
            'estado' => 'ACTIVO',
        ]);
    }

    public static function BuscarAsig($id)
    {
        return ComponentesModuloE::findOrFail($id);
    }

    public static function modificar($data, $id)
    {
        $respuesta = ComponentesModuloE::where(['id' => $id])->update([
            'nombre' => $data['nombre'],
            'grado' => $data['grado'],
            'descripcion' => $data['descripcion'],
        ]);
        return $respuesta;
    }

    public static function editarestado($id, $estado)
    {
        $Respuesta = ComponentesModuloE::where('id', $id)->update([
            'estado' => $estado,
        ]);
        return $Respuesta;
    }

    public static function listar()
    {
        $Asig = ComponentesModuloE::where('estado', "ACTIVO")
            ->get();
        return $Asig;
    }

    public static function listarxGrado($Grado)
    {
        $Asig = ComponentesModuloE::where('estado', "ACTIVO")
            ->where("grado", $Grado)
            ->get();
        return $Asig;
    }

  
    public static function Compoxareas($Area,$grado)
    {
        $Comp = ComponentesModuloE::join("me_asig_compo", "me_asig_compo.componente", "componentes.id")
            ->join("asignaturas_mode", "asignaturas_mode.id", "me_asig_compo.asignatura")
            ->where("asignaturas_mode.area", $Area)
            ->where("asignaturas_mode.grado", $grado)
            ->where('componentes.estado', "ACTIVO")
            ->groupBy('componentes.id')
            ->select('componentes.*')
            ->get();
        return $Comp;
    }
}
