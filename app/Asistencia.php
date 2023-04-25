<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Session;

class Asistencia extends Model
{

    protected $table = 'asistencia';
    protected $fillable = [
        'id_alumno',
        'id_modulo',
        'fecha',
        'estado',
        'valor',
        'origen'
       
    ];

    public static function GuardarAsistencia($data)
    {
        return Asistencia::updateOrCreate([
            'id_alumno' => $data['id_alumno'],
            'id_modulo' => $data['id_modulo'],
            'fecha' => $data['fecha'],
            'origen' => $data['OrAs'],
        ], [
            'valor' => $data['valor'],
            'estado' => 'ACTIVO',
        ]);
    }

    public static function listarAsistencias($id_alumno, $id_modulo, $fecha,$or)
    {
        return Asistencia::where('estado', 'ACTIVO')
            ->where('id_modulo', $id_modulo)
            ->where('id_alumno', $id_alumno)
            ->where('origen',$or)
            ->where('fecha', $fecha)
            ->select('id_alumno', 'id_modulo', 'fecha', 'valor')
            ->first();
    }

    public static function listar($Alum, $Fecha,$or)
    {
        $Asistencia = Asistencia::where('id_alumno', $Alum)
            ->where('id_modulo', Session::get('IDMODULO'))
            ->where('origen',$or)
            ->whereRaw('month(fecha) = month("' . $Fecha . '")')
            ->whereRaw('year(fecha) = year("' . $Fecha . '")')
            ->orderBy('fecha', 'ASC')
            ->get();
        return $Asistencia;
    }

    
    public static function VaciarRegistros(){
        $Intentos = Asistencia::truncate();
        return $Intentos;
     }

     public static function listarAsistenciasMes($id_alumno, $id_modulo, $fecha,$dia)
     {
 
       
         $resuli= Asistencia::where('estado', 'ACTIVO')
             ->where('id_modulo', $id_modulo)
             ->where('id_alumno', $id_alumno)
             ->whereRaw('month(fecha) = month("' . $fecha . '")')
             ->whereRaw('year(fecha) = year("' . $fecha . '")')
             ->whereRaw('day(fecha) = "' . $dia . '"')
             ->select('id_alumno', 'id_modulo', 'fecha', 'valor')
             ->first();
             return $resuli;
     }

}
