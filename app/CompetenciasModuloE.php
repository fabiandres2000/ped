<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompetenciasModuloE extends Model
{
    protected $table = 'competencias';
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

            $respuesta = CompetenciasModuloE::where('competencias.estado', 'ACTIVO')
                ->where(function ($query) use ($busqueda) {
                    $query->where('nombre', 'LIKE', '%' . $busqueda . '%');

                })
                ->select("competencias.*")
                ->orderBy('nombre', 'ASC')
                ->limit($limit)->offset($offset);

        } else {

            $respuesta = CompetenciasModuloE::where('competencias.estado', 'ACTIVO')
                ->select("competencias.*")
                ->orderBy('nombre', 'ASC')
                ->limit($limit)->offset($offset);
        }

        return $respuesta->get();
    }

    public static function numero_de_registros($busqueda)
    {
        if (!empty($busqueda)) {

            $respuesta = CompetenciasModuloE::where('estado', 'ACTIVO')
                ->where(function ($query) use ($busqueda) {
                    $query->where('nombre', 'LIKE', '%' . $busqueda . '%');
                })
                ->orderBy('nombre', 'ASC');

        } else {

            $respuesta = CompetenciasModuloE::where('estado', 'ACTIVO')
                ->orderBy('nombre', 'ASC');

        }
        return $respuesta->count();
    }

    public static function Guardar($data)
    {
        return CompetenciasModuloE::create([
            'nombre' => $data['nombre'],
            'grado' => $data['grado'],
            'descripcion' => $data['descripcion'],
            'estado' => 'ACTIVO',
        ]);
    }

    public static function BuscarAsig($id)
    {
        return CompetenciasModuloE::findOrFail($id);
    }

    public static function modificar($data, $id)
    {
        $respuesta = CompetenciasModuloE::where(['id' => $id])->update([
            'nombre' => $data['nombre'],
            'grado' => $data['grado'],
            'descripcion' => $data['descripcion'],
        ]);
        return $respuesta;
    }

    public static function editarestado($id, $estado)
    {
        $Respuesta = CompetenciasModuloE::where('id', $id)->update([
            'estado' => $estado,
        ]);
        return $Respuesta;
    }

    public static function listar()
    {
        $Asig = CompetenciasModuloE::where('estado', "ACTIVO")
            ->get();
        return $Asig;
    }

    public static function listarxGrado($Grado)
    {
        $Asig = CompetenciasModuloE::where('estado', "ACTIVO")
            ->where("grado", $Grado)
            ->get();
        return $Asig;
    }

    public static function Compexareas($Area,$grado)
    {
        $Comp = CompetenciasModuloE::join("me_asig_comp", "me_asig_comp.competencia", "competencias.id")
            ->join("asignaturas_mode", "asignaturas_mode.id", "me_asig_comp.asignatura")
            ->where("asignaturas_mode.area", $Area)
            ->where("asignaturas_mode.grado", $grado)
            ->where('competencias.estado', "ACTIVO")
            ->groupBy('competencias.id')
            ->select('competencias.*')
            ->get();
        return $Comp;
    }

}
