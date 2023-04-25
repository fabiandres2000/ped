<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;


class ComentariosForoMod extends Model
{
    protected $table = 'comentarios_foro_mod';
    protected $fillable = [
        'comentario',
        'id_foro',
        'id_usuario',
        'estado_comentarios'
    ];

    public static function Guardar($data)
    {
        return ComentariosForoMod::create([
            'comentario' => $data['comentario'],
            'id_foro' => $data['id_foro'],
            'id_usuario' => Auth::user()->id, 
            'estado_comentarios' => 'ACTIVO'
        ]);
    }

    public static function listar($id)
    {
        return ComentariosForoMod::join('users', 'users.id', 'comentarios_foro_mod.id_usuario')
            ->where('estado_comentarios', 'Activo')
            ->where('id_foro', $id)
            ->select('comentarios_foro_mod.*','users.nombre_usuario')
            ->orderBy('id', 'Desc')
            ->get();
    }
}
