<?php

namespace App;

use Auth;
use Illuminate\Database\Eloquent\Model;

class Mensajes extends Model
{
    protected $table = 'mensajes';
    protected $fillable = [
        'mensaje',
        'id_usuario',
        'id_receptor',
        'perfil'
    ];

    public static function guardar($data)
    {
        $usuarios = Mensajes::create([
            'mensaje' => $data['txtMensaje'],
            'id_usuario' => Auth::user()->id,
            'id_receptor' => $data['id_receptor'],
            'perfil' =>Auth::user()->tipo_usuario
        ]);
        return $usuarios;
    }

    public static function listar($data)
    {
        $mensajes = Mensajes::join('users', 'users.id', '=', 'mensajes.id_usuario')
            ->orWhere(function ($query) use ($data) {
                $query->where('id_usuario', Auth::user()->id)
                    ->where('id_receptor', $data['id_receptor']);
            })
            ->orWhere(function ($query) use ($data) {
                $query->where('id_usuario', $data['id_receptor'])
                    ->where('id_receptor', Auth::user()->id);
            })
            ->select(
                'mensajes.mensaje AS MENSAJE',
                'users.nombre_usuario AS NICK',
                'users.id AS IDUSU',
                'mensajes.perfil AS TIPUSU'
            )
            ->orderBy('mensajes.id', 'ASC')
            ->get();
        return $mensajes;
    }

    public static function ultimacon($id_receptor)
    {
        $mensajes = Mensajes::join('users', 'users.id', '=', 'mensajes.id_usuario')
            ->orWhere(function ($query) use ($id_receptor) {
                $query->where('id_usuario', Auth::user()->id)
                    ->where('id_receptor', $id_receptor);
            })
            ->orWhere(function ($query) use ($id_receptor) {
                $query->where('id_usuario', $id_receptor)
                    ->where('id_receptor', Auth::user()->id);
            })
            ->select(
                'mensajes.mensaje AS MENSAJE',
                'users.nombre_usuario AS NICK',
                'users.id AS IDUSU',
                'mensajes.created_at AS FECHA',
                'mensajes.id AS IDMEN'
            )
            ->orderBy('mensajes.id', 'ASC')
            ->get();
        return $mensajes;
    }

    public static function buscarAlumnos($grado, $grupo)
    {
        return Usuarios::join('alumnos', 'users.id', 'alumnos.usuario_alumno')
            ->select(
                'users.nombre_usuario',
                'users.id',
                'alumnos.nombre_alumno'
            )
            ->where('alumnos.grupo', $grupo)
            ->where('alumnos.grado_alumno', $grado)
            ->where('users.estado_usuario', 'ACTIVO')
            ->get();
    }

    public static function VaciarRegistros(){
        $Respuesta = Mensajes::truncate();
        return $Respuesta;
     }
}
