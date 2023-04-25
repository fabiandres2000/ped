<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ComentTemas extends Model {

    protected $table = 'coment_eval';
    protected $fillable = [
        'evaluacion',
        'usuario',
        'alumno',
        'docente',
        'comentario',
        'visto'
    ];

    public static function Guardar($idEval, $Coment, $Doce) {
        return ComentTemas::create([
                    'evaluacion' => $idEval,
                    'usuario' => Auth::user()->id,
                    'alumno' => Auth::user()->id,
                    'docente' => $Doce,
                    'comentario' => $Coment,
                    'visto' => 'NO'
        ]);
    }

    public static function GuardarDoce($idEval, $Coment, $Doce, $Alu) {
        return ComentTemas::create([
                    'evaluacion' => $idEval,
                    'usuario' => Auth::user()->id,
                    'alumno' => $Alu,
                    'docente' => $Doce,
                    'comentario' => $Coment,
                    'visto' => 'NO'
        ]);
    }

    public static function UpdatVistNotDoce($idEval, $idDoce, $idalumn) {
        $Respuesta = ComentTemas::where('evaluacion', $idEval)
                ->where("docente", $idDoce)
                ->where("alumno", $idalumn)
                ->update([
            'visto' => "SI"
        ]);
        return $Respuesta;
    }

    public static function Consultar($idEval, $idDoce) {
        $Respuesta = ComentTemas::join('users', 'users.id', 'coment_eval.usuario')
                ->where('evaluacion', $idEval)
                ->where('alumno', Auth::user()->id)
                ->where('docente', $idDoce)
                ->select("users.nombre_usuario", "comentario")
                ->get();
        return $Respuesta;
    }

    public static function ConsultarDetDoce($idEval, $idDoce, $idalumn) {
        $Respuesta = ComentTemas::join('users', 'users.id', 'coment_eval.usuario')
                ->where('evaluacion', $idEval)
                ->where('alumno', $idalumn)
                ->where('coment_eval.docente', $idDoce)
                ->select("users.nombre_usuario", "comentario")
                ->get();
        return $Respuesta;
    }

    public static function ConsultarDoce($alum,$or) {
        $Respuesta = ComentTemas::join('users', 'users.id', 'coment_eval.usuario')
                ->join('evaluacion', 'evaluacion.id', 'coment_eval.evaluacion')
                ->where('coment_eval.docente', Session::get('IDDOCE'))
                ->where('alumno', $alum)
                ->where('evaluacion.origen_eval', $or)
                ->where('visto', "NO")
                ->whereRaw('DATE_FORMAT(coment_eval.created_at, "%Y-%M-%d")', 'DATE_FORMAT(now(), "%Y-%M-%d')
                ->select("users.nombre_usuario", "comentario", "evaluacion.titulo", "coment_eval.visto", 'coment_eval.usuario', 'coment_eval.docente', 'coment_eval.evaluacion')
                ->first();


        return $Respuesta;
    }
    
    

    public static function ConsultarDoce2($alum) {

        $Respuesta = ComentTemas::join('users', 'users.id', 'coment_eval.usuario')
                        ->join('evaluacion', 'evaluacion.id', 'coment_eval.evaluacion')
                        ->where('coment_eval.docente', Session::get('IDDOCE'))
                        ->where('alumno', $alum)
                        ->whereRaw('DATE_FORMAT(coment_eval.created_at, "%Y-%M-%d")', 'DATE_FORMAT(now(), "%Y-%M-%d')
                        ->select("users.nombre_usuario", "comentario", "evaluacion.titulo", "coment_eval.visto", 'coment_eval.usuario', 'coment_eval.alumno', 'coment_eval.docente', 'coment_eval.evaluacion')
                        ->get()->last();
        return $Respuesta;
    }

    public static function VaciarRegistros(){
        $Intentos = ComentTemas::truncate();
        return $Intentos;
     }

}
