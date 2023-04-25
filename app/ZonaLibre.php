<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class ZonaLibre extends Model {

    protected $table = 'zona_libre';
    protected $fillable = [
        'grado',
        'grupo',
        'jornada',
        'fecha',
        'tip_contenido',
        'titu_contenido',
        'estado',
        'tip_video',
        'docente'
    ];

    public static function Gestion($busqueda, $pagina, $limit, $Asig) {
        if ($pagina == "1") {
            $offset = 0;
        } else {
            $pagina--;
            $offset = $pagina * $limit;
        }
        if (!empty($busqueda)) {
            if (!empty($Asig)) {
                $respuesta = ZonaLibre::where('docente', Auth::user()->id)
                                ->where('estado', 'ACTIVO')
                                ->where(function ($query) use ($busqueda) {
                                    $query->where('titu_contenido', 'LIKE', '%' . $busqueda . '%')
                                    ->orWhere('tip_contenido', 'LIKE', '%' . $busqueda . '%')
                                    ->orWhere('grado', $busqueda);
                                })
                                ->orderBy('grado', 'ASC')
                                ->limit($limit)->offset($offset);
            } else {
                $respuesta = ZonaLibre::where('docente', Auth::user()->id)
                                ->where('estado', 'ACTIVO')
                                ->where(function ($query) use ($busqueda) {
                                    $query->where('titu_contenido', 'LIKE', '%' . $busqueda . '%')
                                    ->orWhere('tip_contenido', 'LIKE', '%' . $busqueda . '%')
                                    ->orWhere('grado', $busqueda);
                                })
                                ->orderBy('grado', 'ASC')
                                ->limit($limit)->offset($offset);
            }
        } else {
            if (!empty($Asig)) {
                $respuesta = ZonaLibre::where('docente', Auth::user()->id)
                                ->where('estado', 'ACTIVO')
                                ->where('grado', $Asig)
                                ->orderBy('grado', 'ASC')
                                ->limit($limit)->offset($offset);
            } else {

                $respuesta = ZonaLibre::where('docente', Auth::user()->id)
                                ->where('estado', 'ACTIVO')
                                ->orderBy('grado', 'ASC')
                                ->limit($limit)->offset($offset);
            }
        }

        return $respuesta->get();
    }

    public static function numero_de_registros($busqueda, $Asig) {
        if (!empty($busqueda)) {
            if (!empty($Asig)) {
                $respuesta = ZonaLibre::where('docente', Auth::user()->id)
                        ->where('estado', 'ACTIVO')
                        ->where(function ($query) use ($busqueda) {
                            $query->where('titu_contenido', 'LIKE', '%' . $busqueda . '%')
                            ->orWhere('tip_contenido', 'LIKE', '%' . $busqueda . '%')
                            ->orWhere('grado', $busqueda);
                        })
                        ->orderBy('grado', 'ASC');
            } else {
                $respuesta = ZonaLibre::where('docente', Auth::user()->id)
                        ->where('estado', 'ACTIVO')
                        ->where(function ($query) use ($busqueda) {
                            $query->where('titu_contenido', 'LIKE', '%' . $busqueda . '%')
                            ->orWhere('tip_contenido', 'LIKE', '%' . $busqueda . '%')
                            ->orWhere('grado', $busqueda);
                        })
                        ->orderBy('grado', 'ASC');
            }
        } else {
            if (!empty($Asig)) {
                $respuesta = ZonaLibre::where('docente', Auth::user()->id)
                        ->where('estado', 'ACTIVO')
                        ->where('grado', $Asig)
                        ->orderBy('grado', 'ASC');
            } else {

                $respuesta = ZonaLibre::where('docente', Auth::user()->id)
                        ->where('estado', 'ACTIVO')
                        ->orderBy('grado', 'ASC');
            }
        }
        return $respuesta->count();
    }

    public static function GuardarTipCont($datos) {

        return ZonaLibre::create([
                    'grado' => $datos['grado'],
                    'grupo' => $datos['grupo'],
                    'jornada' => $datos['jornada'],
                    'fecha' => $datos['fecha'],
                    'titu_contenido' => $datos['titu_contenido'],
                    'tip_contenido' => $datos['tip_contenido'],
                    'estado' => 'ACTIVO',
                    'tip_video' => $datos['tip_video'],
                    'docente' => Auth::user()->id
        ]);
    }

    public static function modificar($datos, $id) {
        $respuesta = ZonaLibre::where(['id' => $id])->update([
            'grado' => $datos['grado'],
            'grupo' => $datos['grupo'],
            'jornada' => $datos['jornada'],
            'fecha' => $datos['fecha'],
            'titu_contenido' => $datos['titu_contenido'],
            'tip_contenido' => $datos['tip_contenido'],
            'tip_video' => $datos['tip_video'],
            'docente' => Auth::user()->id
        ]);
        return $respuesta;
    }

    public static function LisTemas($id, $grado, $grupo, $jornada) {
        $fecha = date('Y-m-d');

        $Temas = ZonaLibre::where('fecha', $fecha)
                ->where('docente', $id)
                ->where('grado', $grado)
                ->where('grupo', $grupo)
                ->where('jornada', $jornada)
                ->get();
        return $Temas;
    }

    public static function LisTemasEst($grado, $grupo, $jornada) {
        $fecha = date('Y-m-d');

        $Temas = ZonaLibre::where('fecha', $fecha)
                ->where('grado', $grado)
                ->where('grupo', $grupo)
                ->where('jornada', $jornada)
                ->get();
        return $Temas;
    }

    public static function editarestado($id, $estado) {
        $Respuesta = ZonaLibre::where('id', $id)->update([
            'estado' => $estado
        ]);
        return $Respuesta;
    }

    public static function BuscarTema($id) {

        return ZonaLibre::findOrFail($id);
    }

    
    public static function VaciarRegistros(){
        $Respuesta = ZonaLibre::truncate();
        return $Respuesta;
     }

}
