<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class MensajesModulosChat extends Model
{
   
    protected $table = 'mensajes_mod_chat';
    protected $fillable = [
        'mensaje',
        'id_usuario',
        'id_receptor',
        'perfil'
    ];

    public static function guardar($data)
    {
        $usuarios = MensajesModulosChat::create([
            'mensaje' => $data['txtMensaje'],
            'id_usuario' => Auth::user()->id,
            'id_receptor' => $data['id_receptor'],
            'perfil' =>Auth::user()->tipo_usuario
        ]);
        return $usuarios;
    }

    public static function listar($data)
    {
        $mensajes = MensajesModulosChat::join('users', 'users.id', '=', 'mensajes_mod_chat.id_usuario')
            ->orWhere(function ($query) use ($data) {
                $query->where('id_usuario', Auth::user()->id)
                    ->where('id_receptor', $data['id_receptor']);
            })
            ->orWhere(function ($query) use ($data) {
                $query->where('id_usuario', $data['id_receptor'])
                    ->where('id_receptor', Auth::user()->id);
            })
            ->select(
                'mensajes_mod_chat.mensaje AS MENSAJE',
                'users.nombre_usuario AS NICK',
                'users.id AS IDUSU',
                'mensajes_mod_chat.perfil AS TIPUSU'
            )
            ->orderBy('mensajes_mod_chat.id', 'ASC')
            ->get();
           
        return $mensajes;
    }

    public static function ultimacon($id_receptor)
    {
        $mensajes = MensajesModulosChat::join('users', 'users.id', '=', 'mensajes_mod_chat.id_usuario')
            ->orWhere(function ($query) use ($id_receptor) {
                $query->where('id_usuario', Auth::user()->id)
                    ->where('id_receptor', $id_receptor);
            })
            ->orWhere(function ($query) use ($id_receptor) {
                $query->where('id_usuario', $id_receptor)
                    ->where('id_receptor', Auth::user()->id);
            })
            ->select(
                'mensajes_mod_chat.mensaje AS MENSAJE',
                'users.nombre_usuario AS NICK',
                'users.id AS IDUSU',
                'mensajes_mod_chat.created_at AS FECHA',
                'mensajes_mod_chat.id AS IDMEN'
            )
            ->orderBy('mensajes_mod_chat.id', 'ASC')
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
            ->where('users.grado_usuario', $grado)
            ->where('users.estado_usuario', 'Activo')
            ->get();
    }
}
