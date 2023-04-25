<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use Session;

class Periodos extends Model {

    protected $table = 'periodos';
    protected $fillable = [
        'modulo',
        'des_periodo',
        'avance_perido',
        'estado'
    ];

    public static function periodo($id) {
        $Periodo = Periodos::where('modulo', $id)
                 ->where('estado', 'ACTIVO')
                ->get();
        return $Periodo;
    }

       public static function BuscarPerido($id) {

        return Periodos::findOrFail($id);
    }

    public static function listar($id) {
        $Periodo = Periodos::where('modulo', $id)
                ->where('estado', 'ACTIVO')
                ->get();
        return $Periodo;
    }


    public static function listarVerf($id)
    {
        $Periodo = Periodos::rightjoin('unidades', 'unidades.periodo', 'periodos.id')
            ->where('periodos.modulo', $id)
            ->where('periodos.estado', 'ACTIVO')
            ->where('unidades.estado', 'ACTIVO')

            ->get();
        return $Periodo;
    }

    public static function listarPerPond($id) {
         $Usu = Auth::user()->id;
           $Periodo = Periodos::leftJoin('pond_periodos', 'periodos.id', '=', 'pond_periodos.periodo', 'pond_periodos.docente', '=', $Usu)
                   ->where('periodos.modulo', $id)
                   ->select('periodos.id','periodos.des_periodo','pond_periodos.porcentaje','pond_periodos.tponde')
                   ->get();
            return $Periodo;
    }

    public static function Guardar($data) {


        foreach ($data["txtperi"] as $key => $val) {

            $respuesta = Periodos::updateOrCreate([
                        'id' => $data["txtidperi"][$key]
                            ], [
                        'modulo' => $data["modulo_id"],
                        'des_periodo' => $data["txtperi"][$key],
                        'avance_perido' => $data["txtporc"][$key],
            ]);
        }
        return $respuesta;
    }

    public static function editarestado($id) {
        $Respuesta = Periodos::where('id', $id)->update([
            'estado' => "INACTIVO"
        ]);
        return $Respuesta;
    }

}
