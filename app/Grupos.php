<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Session;

class Grupos extends Model
{

    protected $table = 'grupos';
    protected $fillable = [
        'grupo',
        'modulo',
        'estado',
    ];

    public static function Guardar($data)
    {

        foreach ($data["grupos"] as $key => $val) {

      $grupod = Grupos::updateOrCreate([
                'grupo' => $data["grupos"][$key], 'modulo' => $data["modulo_id"],
            ], [
                'grupo' => $data["grupos"][$key],
                'modulo' => $data["modulo_id"],
                'estado' => 'ACTIVO',
            ]);

        }
        return $grupod;
    }

    public static function  EliminarGrrupo($id,$idMod){
        $Opc = Grupos::where('modulo', $idMod)->where('grupo',$id);
        $Opc->delete();
        return $Opc;
    }

    public static function listar($id)
    {
        $Periodo = Grupos::join("para_grupos", "para_grupos.id", "grupos.grupo")
            ->where('modulo', $id)
            ->select('grupos.*', 'para_grupos.descripcion')
            ->orderBy("grupo", "ASC")
            ->get();
        return $Periodo;
    }

    public static function ConsultarGrupo($Grupo,$Mod){

        $Periodo = Grupos::where('grupo', $Grupo)
        ->where('modulo', $Mod)
        ->orderBy("grupo", "ASC")
        ->first();
    return $Periodo;
    }

    public static function Buscar($id)
    {
        return Grupos::where('modulo', $id)
            ->where('grupo', Session::get('IDGRUPO'))
            ->first();
    }

    public static function BusIdGrup($id, $Grupo)
    {
        return Grupos::where('modulo', $id)
            ->where('grupo', $Grupo)
            ->first();
    }

}
