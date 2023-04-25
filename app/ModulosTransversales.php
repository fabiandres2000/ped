<?php

namespace App;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class ModulosTransversales extends Model
{

    protected $table = 'modulos_transversales';
    protected $fillable = [
        'nombre',
        'estado',
        'descripcion',
    ];

    public static function Guardar($data)
    {

        return ModulosTransversales::create([
            'nombre' => $data['nombre'],
            'estado' => 'ACTIVO',
            'descripcion' => $data['descripcion'],
        ]);
    }

    public static function modificar($data, $id)
    {
        $respuesta = ModulosTransversales::where(['id' => $id])->update([
            'nombre' => $data['nombre'],
            'descripcion' => $data['descripcion'],
        ]);
        return $respuesta;
    }

    public static function VerfDel($id)
    {
        $VerfDel = ModulosTransversales::where('id', $id)
        ->where('id', '>=', 1)
        ->where('id', '<=', 5)
        ->get();
       
        return $VerfDel;
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
            $respuesta = ModulosTransversales::where('modulos_transversales.estado', 'ACTIVO')
                ->where(function ($query) use ($busqueda) {
                    $query->where('nombre', 'LIKE', '%' . $busqueda . '%');
                })
                ->select("modulos_transversales.*")
                ->orderBy('nombre', 'ASC')
                ->limit($limit)->offset($offset);
        } else {
            $respuesta = ModulosTransversales::where('modulos_transversales.estado', 'ACTIVO')
                ->select("modulos_transversales.*")
                ->orderBy('nombre', 'ASC')
                ->limit($limit)->offset($offset);
        }

        return $respuesta->get();
    }

    public static function numero_de_registros($busqueda)
    {
        if (!empty($busqueda)) {
            $respuesta = ModulosTransversales::where('estado', 'ACTIVO')
                ->where(function ($query) use ($busqueda) {
                    $query->where('nombre', 'LIKE', '%' . $busqueda . '%');
                })
                ->orderBy('nombre', 'ASC');
        } else {
            $respuesta = ModulosTransversales::where('estado', 'ACTIVO')
                ->orderBy('nombre', 'ASC');
        }
        return $respuesta->count();
    }

    public static function AsigxUsu($Usu, $TipUsu,$grupo,$jornada)
    {
        if ($TipUsu == "Administrador") {
            $Asig = ModulosTransversales::where('estado', "ACTIVO")
                ->get();
        } else if ($TipUsu == "Profesor") {
            $Asig = ModulosTransversales::join("mod_prof", "mod_prof.asignatura", "modulos_transversales.id")
                ->where('mod_prof.profesor', Auth::user()->id)
                ->where('estado', 'ACTIVO')
                ->select('modulos_transversales.id', 'modulos_transversales.nombre')
                ->groupBy('modulos_transversales.id', 'modulos_transversales.nombre')
                ->get();
        } else {
            $Asig = ModulosTransversales::join("grados_modulos", "grados_modulos.modulo", "modulos_transversales.id")
                ->join('grupos_transversales','grupos_transversales.modulo','grados_modulos.id')
                ->join('mod_prof','mod_prof.grado','grados_modulos.id')
                ->where('grados_modulos.grado_modulo', Auth::user()->grado_usuario)
                ->where('grupos_transversales.grupo',$grupo)
                ->where('mod_prof.jornada',$jornada)
                ->where('modulos_transversales.estado', 'ACTIVO')
                ->where('grados_modulos.estado_modulo', 'ACTIVO')
                ->select("grados_modulos.*", 'modulos_transversales.nombre')
                ->groupBy('mod_prof.grado')
                ->get();
        }
        return $Asig;
    }

    public static function HabiAsig($data)
    {

        $respuesta = ModulosTransversales::where('estado', '<>', "ELIMINADO")->update([
            'estado' => 'INACTIVO',
        ]);

        if (!empty($data["check_modulos"])) {
            foreach ($data["check_modulos"] as $key => $val) {
                $respuesta = ModulosTransversales::where(['id' => $data["check_modulos"][$key]])->update([
                    'estado' => 'ACTIVO',
                ]);
            }
        }
    }

    public static function listar()
    {
        if (Auth::user()->tipo_usuario == "Profesor") {
            $Asig = ModulosTransversales::join("mod_prof","mod_prof.asignatura","modulos_transversales.id")
            ->where("mod_prof.profesor", Auth::user()->id)
            ->where('estado', "ACTIVO")
            ->select("modulos_transversales.*")
            ->groupBy("modulos_transversales.id")
            ->get();
        }else{
            $Asig = ModulosTransversales::where('estado', "ACTIVO")
            ->get();
        }
        
        return $Asig;
    }

    public static function listarPar()
    {
        $Asig = ModulosTransversales::where('estado', '<>', "ELIMINADO")
            ->get();
        return $Asig;
    }
 

    public static function listarxare($Id)
    {
        $Asig = ModulosTransversales::where('estado', "ACTIVO")
            ->where('area', $Id)
            ->get();
        return $Asig;
    }

    public static function listarAsigModulo()
    {
        $Asig = ModulosTransversales::where('estado', "ACTIVO")
            ->get();
        return $Asig;
    }

    public static function BuscarAsig($id)
    {
        return ModulosTransversales::findOrFail($id);
    }

    public static function editarestado($id, $estado)
    {
        $Respuesta = ModulosTransversales::where('id', $id)->update([
            'estado' => $estado,
        ]);
        return $Respuesta;
    }

    public static function InfAsig($id)
    {
        $Asig = ModulosTransversales::where('id', $id)
            ->first();
        return $Asig;
    }
}
