<?php

namespace App;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class Asignaturas extends Model
{

    protected $table = 'asignaturas';
    protected $fillable = [
        'nombre',
        'estado',
        'descripcion',
        'area',
    ];

    public static function Guardar($data)
    {

        return Asignaturas::create([
            'nombre' => $data['nombre'],
            'estado' => 'ACTIVO',
            'descripcion' => $data['descripcion'],
            'area' => $data['area'],
        ]);
    }

    public static function modificar($data, $id)
    {
        $respuesta = Asignaturas::where(['id' => $id])->update([
            'nombre' => $data['nombre'],
            'descripcion' => $data['descripcion'],
            'area' => $data['area'],
        ]);
        return $respuesta;
    }

    public static function HabiAsig($data)
    {

        $respuesta = Asignaturas::where('estado', '<>', "ELIMINADO")->update([
            'estado' => 'INACTIVO',
        ]);

        if (!empty($data["check_asignaturas"])) {

            foreach ($data["check_asignaturas"] as $key => $val) {

                $respuesta = Asignaturas::where(['id' => $data["check_asignaturas"][$key]])->update([
                    'estado' => 'ACTIVO',
                ]);
            }
        }
    }

    public static function Gestion($busqueda, $pagina, $limit)
    {
        if ($pagina == "1") {
            $offset = 0;
        } else {
            $pagina--;
            $offset = $pagina * $limit;
        }
        if (!empty($busqueda)) {
            $respuesta = Asignaturas::join("areas", "areas.id", "asignaturas.area")
                ->where('asignaturas.estado', 'ACTIVO')
                ->where(function ($query) use ($busqueda) {
                    $query->where('nombre', 'LIKE', '%' . $busqueda . '%');
                })
                ->select("asignaturas.*", 'areas.nombre_area')
                ->orderBy('nombre', 'ASC')
                ->limit($limit)->offset($offset);
        } else {
            $respuesta = Asignaturas::join("areas", "areas.id", "asignaturas.area")
                ->where('asignaturas.estado', 'ACTIVO')
                ->select("asignaturas.*", 'areas.nombre_area')
                ->orderBy('nombre', 'ASC')
                ->limit($limit)->offset($offset);
        }

        return $respuesta->get();
    }

    public static function numero_de_registros($busqueda)
    {
        if (!empty($busqueda)) {
            $respuesta = Asignaturas::where('estado', 'ACTIVO')
                ->where(function ($query) use ($busqueda) {
                    $query->where('nombre', 'LIKE', '%' . $busqueda . '%');
                })
                ->orderBy('nombre', 'ASC');
        } else {
            $respuesta = Asignaturas::where('estado', 'ACTIVO')
                ->orderBy('nombre', 'ASC');
        }
        return $respuesta->count();
    }

    public static function AsigxUsu($Usu, $TipUsu, $grupo, $jornada)
    {

        if ($TipUsu == "Administrador") {
            $Asig = Asignaturas::where('estado', "ACTIVO")
                ->get();
        } else if ($TipUsu == "Profesor") {
            $Asig = Asignaturas::join("asig_prof", "asig_prof.asignatura", "asignaturas.id")
                ->where('asig_prof.profesor', Auth::user()->id)
                ->where('estado', 'ACTIVO')
                ->select('asignaturas.id', 'asignaturas.nombre')
                ->groupBy('asignaturas.id', 'asignaturas.nombre')
                ->get();
        } else {
         
            $Asig = Asignaturas::join("modulos", "modulos.asignatura", "asignaturas.id")
                ->join('grupos', 'grupos.modulo', 'modulos.id')
                ->join('asig_prof', 'asig_prof.grado', 'modulos.id')
                ->where('modulos.grado_modulo', Auth::user()->grado_usuario)
                ->where('grupos.grupo', $grupo)
                ->where('asig_prof.jornada', $jornada)
                ->where('asignaturas.estado', 'ACTIVO')
                ->where('modulos.estado_modulo', 'ACTIVO')
                ->select("modulos.*", 'asignaturas.nombre')
                ->groupBy('asig_prof.grado')
                ->get();
               

        }
        return $Asig;
    }

    public static function listar()
    {
        if (Auth::user()->tipo_usuario == "Profesor") {
            $Asig = Asignaturas::join("asig_prof", "asig_prof.asignatura", "asignaturas.id")
                ->where("asig_prof.profesor", Auth::user()->id)
                ->where('estado', "ACTIVO")
                ->select('asignaturas.*')
                ->groupBy("asignaturas.id")
                ->get();
        } else {
            
            $Asig = Asignaturas::where('estado', "ACTIVO")
                ->get();
        }

        return $Asig;
    }

    public static function listarPar()
    {
        $Asig = Asignaturas::where('estado', '<>', "ELIMINADO")
            ->get();
        return $Asig;
    }

    public static function VerfDel($idAsig)
    {
        $VerfDel = Asignaturas::where('id', $idAsig)
        ->where('id', '>=', 1)
        ->where('id', '<=', 12)
        ->get();
        
        return $VerfDel;
    }

    public static function listarxare($Id)
    {
        $Asig = Asignaturas::where('estado', "ACTIVO")
            ->where('area', $Id)
            ->get();
        return $Asig;
    }

    public static function listarAsigModulo()
    {
        $Asig = Asignaturas::where('estado', "ACTIVO")
            ->get();
        return $Asig;
    }

    public static function BuscarAsig($id)
    {
        return Asignaturas::findOrFail($id);
    }

    public static function editarestado($id, $estado)
    {
        $Respuesta = Asignaturas::where('id', $id)->update([
            'estado' => $estado,
        ]);
        return $Respuesta;
    }

    public static function InfAsig($id)
    {
        $Asig = Asignaturas::where('id', $id)
            ->first();
        return $Asig;
    }

}
