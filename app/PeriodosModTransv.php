<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PeriodosModTransv extends Model
{

    protected $table = 'periodos_modtransv';
    protected $fillable = [
        'modulo',
        'des_periodo',
        'avance_perido',
        'estado',
    ];

    public static function periodo($id)
    {
        $Periodo = PeriodosModTransv::where('modulo', $id)
            ->where('estado', 'ACTIVO')
            ->get();
        return $Periodo;
    }

    public static function BuscarPerido($id)
    {

        return PeriodosModTransv::findOrFail($id);
    }

    public static function listar($id)
    {
        $Periodo = PeriodosModTransv::where('modulo', $id)
            ->where('estado', 'ACTIVO')
            ->get();
        return $Periodo;
    }

    public static function listarVerf($id)
    {
        $Periodo = PeriodosModTransv::rightjoin('unidades_modulos', 'unidades_modulos.periodo', 'periodos_modtransv.id')
            ->where('periodos_modtransv.modulo', $id)
            ->where('periodos_modtransv.estado', 'ACTIVO')
            ->where('unidades_modulos.estado', 'ACTIVO')
            ->get();

          
        return $Periodo;
    }

    public static function Guardar($data)
    {
        foreach ($data["txtperi"] as $key => $val) {

            $respuesta = PeriodosModTransv::updateOrCreate([
                'id' => $data["txtidperi"][$key],
            ], [
                'modulo' => $data["modulo_id"],
                'des_periodo' => $data["txtperi"][$key],
                'avance_perido' => $data["txtporc"][$key],
            ]);
        }
        return $respuesta;
    }

    public static function editarestado($id)
    {
        $Respuesta = PeriodosModTransv::where('id', $id)->update([
            'estado' => "INACTIVO",
        ]);
        return $Respuesta;
    }
}
