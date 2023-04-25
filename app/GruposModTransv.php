<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Session;

class GruposModTransv extends Model
{

    protected $table = 'grupos_transversales';
    protected $fillable = [
        'grupo',
        'modulo',
        'estado',
    ];

    public static function Guardar($data)
    {

        foreach ($data["grupos"] as $key => $val) {

      $grupod = GruposModTransv::updateOrCreate([
                'grupo' => $data["grupos"][$key], 'modulo' => $data["modulo_id"],
            ], [
                'grupo' => $data["grupos"][$key],
                'modulo' => $data["modulo_id"],
                'estado' => 'ACTIVO',
            ]);

        }
        return $grupod;
    }

    public static function ConsultarGrupo($Grupo,$Mod){

        $Periodo = GruposModTransv::where('grupo', $Grupo)
        ->where('modulo', $Mod)
        ->orderBy("grupo", "ASC")
        ->first();
    return $Periodo;
    }

    public static function  EliminarGrrupo($id,$idMod){
        $Opc = GruposModTransv::where('modulo', $idMod)->where('grupo',$id);
        $Opc->delete();
        return $Opc;
    }

    public static function listar($id)
    {
        $Periodo = GruposModTransv::join("para_grupos", "para_grupos.id", "grupos_transversales.grupo")
            ->where('modulo', $id)
            ->select('grupos_transversales.*', 'para_grupos.descripcion')
            ->orderBy("grupo", "ASC")
            ->get();
        return $Periodo;
    }

    public static function Buscar($id)
    {
        return GruposModTransv::where('modulo', $id)
            ->where('grupo', Session::get('IDGRUPO'))
            ->first();
    }

    public static function BusIdGrup($id, $Grupo)
    {
        return GruposModTransv::where('modulo', $id)
            ->where('grupo', $Grupo)
            ->first();
    }

}
