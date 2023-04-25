<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use Session;

class Comentarios extends Model
{
    protected $table = 'comentarios';
    protected $fillable = [
        'comentario',
        'id_foro',
        'id_usuario',
        'estado_comentarios'
    ];

    public static function Guardar($data)
    {
        return Comentarios::create([
            'comentario' => $data['comentario'],
            'id_foro' => $data['id_foro'],
            'id_usuario' => Auth::user()->id,
            'estado_comentarios' => 'ACTIVO'
        ]);
    }

    public static function listar($id)
    {
        return Comentarios::join('users', 'users.id', 'comentarios.id_usuario')
            ->where('estado_comentarios', 'Activo')
            ->where('id_foro', $id)
            ->select('comentarios.*','users.nombre_usuario')
            ->orderBy('id', 'Desc')
            ->get();
    }

    public static function VaciarRegistros(){
        $Respuesta = Comentarios::truncate();
        return $Respuesta;
     }
}
