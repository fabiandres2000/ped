<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use Session;

class Foro extends Model
{
    protected $table = 'foro';
    protected $fillable = [
        'titulo',
        'contenido',
        'id_pro',
        'id_asig',
        'estado_foro'
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
            $respuesta = Foro::where('estado_foro', 'ACTIVO')
                ->where(function ($query) use ($busqueda) {
                    $query->where('titulo', 'LIKE', '%' . $busqueda . '%')
                        ->orWhere('contenido', 'LIKE', '%' . $busqueda . '%');
                })
                ->where('id_pro', Auth::user()->id)
                ->where('id_asig', Session::get('IDMODULO'))
                ->orderBy('id', 'DESC')
                ->limit($limit)->offset($offset);
        } else {
            $respuesta = Foro::where('estado_foro', 'ACTIVO')
                ->where('id_asig', Session::get('IDMODULO'))
                ->orderBy('id', 'DESC')
                ->limit($limit)->offset($offset);
        }

        return $respuesta->get();
    }

    public static function numero_de_registros($busqueda)
    {
        if (!empty($busqueda)) {
            $respuesta = Foro::where('estado_foro', 'ACTIVO')
                ->where(function ($query) use ($busqueda) {
                    $query->where('titulo', 'LIKE', '%' . $busqueda . '%')
                        ->orWhere('contenido', 'LIKE', '%' . $busqueda . '%');
                })
                ->where('id_pro', Auth::user()->id)
                ->where('id_asig', Session::get('IDMODULO'))
                ->orderBy('id', 'DESC');
        } else {
            $respuesta = Foro::where('estado_foro', 'ACTIVO')
                ->where('id_pro', Auth::user()->id)
                ->where('id_asig', Session::get('IDMODULO'))
                ->orderBy('id', 'DESC');
        }
        return $respuesta->count();
    }

    public static function Guardar($data)
    {
        return Foro::create([
            'titulo' => $data['titulo'],
            'contenido' => $data['contenido'],
            'id_pro' => Auth::user()->id,
            'id_asig' => Session::get('IDMODULO'),
            'estado_foro' => 'ACTIVO'
        ]);
    }

    public static function BuscarForo($id)
    {
        return Foro::findOrFail($id);
    }

    public static function modificar($data, $id)
    {
        $respuesta = Foro::where(['id' => $id])->update([
            'titulo' => $data['titulo'],
            'contenido' => $data['contenido']
        ]);
        return $respuesta;
    }

    public static function editarestado($id, $estado)
    {
        $Respuesta = Foro::where('id', $id)->update([
            'estado_foro' => $estado
        ]);
        return $Respuesta;
    }
    public static function VaciarRegistros(){
        $Respuesta = Foro::truncate();
        return $Respuesta;
     }
}
