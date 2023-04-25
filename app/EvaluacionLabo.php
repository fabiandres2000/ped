<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use Session;

class EvaluacionLabo extends Model
{
        protected $table = 'evaluacion_labo';
    protected $fillable = [
        'laboratorio',
        'tip_evaluacion',
        'titulo',
        'hab_conversacion',
        'intentos_perm',
        'calif_usando',
        'punt_max',
        'intentos_real',
        'clasificacion',
        'estado',
        'enunciado',
        'animacion',
        'tiempo',
        'docente'
    ];

     public static function ListEval($id) {

        if (Auth::user()->tipo_usuario == "Profesor") {
            $Usu = Auth::user()->id;
            $DesEval = EvaluacionLabo::where('laboratorio', $id)
                    ->where('estado', 'ACTIVO')
                    ->where(function ($query) use ($Usu) {
                        $query->where('evaluacion.docente', "")
                        ->orWhere('evaluacion.docente', $Usu);
                    })
                    ->get();
        } else if (Auth::user()->tipo_usuario == "Estudiante") {
            $Usu = Session::get('USUDOCENTE');
            $DesEval = EvaluacionLabo::where('laboratorio', $id)
                    ->where('estado', 'ACTIVO')
                    ->where(function ($query) use ($Usu) {
                        $query->where('evaluacion.docente', "")
                        ->orWhere('evaluacion.docente', $Usu);
                    })
                    ->get();
        } else {
            $Usu = Auth::user()->id;
            $DesEval = EvaluacionLabo::where('laboratorio', $id)
                    ->where('estado', 'ACTIVO')
                    ->get();
        }

        return $DesEval;
    }

}
