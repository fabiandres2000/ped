<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

class Profesores extends Model
{

    protected $table = 'profesores';
    protected $fillable = [
        'identificacion',
        'nombre',
        'apellido',
        'direccion',
        'telefono',
        'email',
        'usuario_profesor',
        'estado',
        'foto',
        'jornada',
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
            $respuesta = Profesores::where('estado', 'ACTIVO')
                ->where(function ($query) use ($busqueda) {
                    $query->where('identificacion', 'LIKE', '%' . $busqueda . '%')
                        ->orWhere('nombre', 'LIKE', '%' . $busqueda . '%')
                        ->orWhere('apellido', 'LIKE', '%' . $busqueda . '%')
                        ->orWhere('email', 'LIKE', '%' . $busqueda . '%');
                })
                ->selectRaw('CONCAT_WS(" ",nombre,apellido) as nomb')
                ->selectRaw('(CASE WHEN jornada = "JM" THEN "Jornada Mañana" WHEN jornada = "JT" THEN "Jornada Tarde" ELSE "Jornada Nocturna" END) AS Jorna')
                ->select('profesores.*')
                ->orderBy('nombre', 'ASC')
                ->limit($limit)->offset($offset);
        } else {
            $respuesta = Profesores::where('estado', 'ACTIVO')
                ->selectRaw('CONCAT_WS(" ",nombre,apellido) as nomb')
                ->select('profesores.*')
                ->selectRaw('(CASE WHEN jornada = "JM" THEN "Jornada Mañana" WHEN jornada = "JT" THEN "Jornada Tarde" ELSE "Jornada Nocturna" END) AS Jorna')
                ->orderBy('nombre', 'ASC')
                ->limit($limit)->offset($offset);
        }

        return $respuesta->get();
    }

    public static function numero_de_registros($busqueda)
    {
        if (!empty($busqueda)) {
            $respuesta = Profesores::where('estado', 'ACTIVO')
                ->where(function ($query) use ($busqueda) {
                    $query->where('identificacion', 'LIKE', '%' . $busqueda . '%')
                        ->orWhere('nombre', 'LIKE', '%' . $busqueda . '%')
                        ->orWhere('apellido', 'LIKE', '%' . $busqueda . '%')
                        ->orWhere('email', 'LIKE', '%' . $busqueda . '%');
                })
                ->orderBy('nombre', 'ASC');
        } else {
            $respuesta = Profesores::where('estado', 'ACTIVO')
                ->orderBy('nombre', 'ASC');
        }
        return $respuesta->count();
    }

    public static function Guardar($data)
    {

        return Profesores::create([
            'identificacion' => $data['identificacion'],
            'nombre' => $data['nombre'],
            'apellido' => $data['apellido'],
            'direccion' => $data['direccion'],
            'telefono' => $data['telefono'],
            'email' => $data['email'],
            'usuario_profesor' => $data['usuario_profesor'],
            'estado' => 'ACTIVO',
            'foto' => $data['img'],
            'jornada' => $data['jornada'],
        ]);
    }

    public static function ListarxdoceAsig()
    {

        return Profesores::leftJoin('asig_prof', 'asig_prof.profesor', 'profesores.usuario_profesor')
            ->where("profesores.estado", "ACTIVO")
            ->get();
    }

    public static function BuscarProf($id)
    {

        return Profesores::findOrFail($id);
    }

    public static function BuscarProfFoto($Usu)
    {

        return Profesores::where("usuario_profesor", $Usu)->first();
    }

    public static function Listar()
    {

        return Profesores::where("estado", "ACTIVO")
            ->get();
    }

    public static function BuscarIdProf($ident)
    {

        return Profesores::where("identificacion", $ident)
            ->where("estado", "ACTIVO")
            ->first();
    }

    public static function BuscarProfVal($id)
    {

        return Profesores::where("id", $id)
            ->where('estado', 'ACTIVO')
            ->first();
    }

    public static function modificar($data, $id)
    {
        $respuesta = Profesores::where(['id' => $id])->update([
            'identificacion' => $data['identificacion'],
            'nombre' => $data['nombre'],
            'apellido' => $data['apellido'],
            'direccion' => $data['direccion'],
            'telefono' => $data['telefono'],
            'email' => $data['email'],
            'foto' => $data['img'],
            'jornada' => $data['jornada'],
        ]);
        return $respuesta;
    }

    public static function modificarPerfil($data, $id)
    {
        $respuesta = Profesores::where(['id' => $id])->update([
            'identificacion' => $data['identificacion'],
            'nombre' => $data['nombre'],
            'apellido' => $data['apellido'],
            'direccion' => $data['direccion'],
            'telefono' => $data['telefono'],
            'email' => $data['email'],
            'foto' => $data['img'],
        ]);
        return $respuesta;
    }

    public static function editarestado($id, $estado)
    {
        $Respuesta = Profesores::where('id', $id)->update([
            'estado' => $estado,
        ]);
        return $Respuesta;
    }

    public static function Buscar($id)
    {
        return Profesores::join('users', 'users.id', 'profesores.usuario_profesor')
            ->select('profesores.*', 'users.login_usuario')
            ->selectRaw('(CASE WHEN jornada = "JM" THEN "Jornada Mañana" WHEN jornada = "JT" THEN "Jornada Tarde" ELSE "Jornada Nocturna" END) AS jorna')
            ->where('usuario_profesor', $id)->first();
    }

    public static function alumnos()
    {

        return Modulos::leftJoin('alumnos', 'alumnos.grado_alumno', 'modulos.grado_modulo')
            ->leftJoin('asignaturas', 'asignaturas.id', 'modulos.asignatura')
            ->where('modulos.estado_modulo', 'ACTIVO')
            ->where('alumnos.estado_alumno', 'ACTIVO')
            ->where('alumnos.grupo', Session::get('GrupAct'))
            ->where('alumnos.jornada', Session::get('JORDOCE'))
            ->where('modulos.id', Session::get('IDMODULO'))
            ->select(
                'alumnos.ident_alumno', 'alumnos.nombre_alumno', 'alumnos.apellido_alumno', 'alumnos.foto_alumno', 'alumnos.id AS IDALUMNO', 'alumnos.usuario_alumno AS usuario', 'modulos.id AS IDMODULO', 'asignaturas.nombre AS MODULO'
            )
            ->orderBy('alumnos.apellido_alumno', 'ASC')
            ->get();
    }

    public static function alumnosMod()
    {
        return GradosModulos::leftJoin('alumnos', 'alumnos.grado_alumno', 'grados_modulos.grado_modulo')
            ->leftJoin('modulos_transversales', 'modulos_transversales.id', 'grados_modulos.modulo')
            ->where('grados_modulos.estado_modulo', 'ACTIVO')
            ->where('alumnos.estado_alumno', 'ACTIVO')
            ->where('alumnos.grupo', Session::get('GrupAct'))
            ->where('alumnos.jornada', Session::get('JORDOCE'))
            ->where('grados_modulos.id', Session::get('IDMODULO'))
            ->select(
                'alumnos.ident_alumno', 'alumnos.nombre_alumno', 'alumnos.apellido_alumno', 'alumnos.foto_alumno', 'alumnos.id AS IDALUMNO', 'alumnos.usuario_alumno AS usuario', 'grados_modulos.id AS IDMODULO', 'modulos_transversales.nombre AS MODULO'
            )
            ->orderBy('alumnos.apellido_alumno', 'ASC')
            ->get();
    }

}
