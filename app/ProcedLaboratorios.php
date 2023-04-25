<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProcedLaboratorios extends Model
{

    protected $table = 'proced_laboratorio';
    protected $fillable = [
        'laboratorio',
        'procedimiento',
        'vide_proced',
    ];

    public static function Guardar($datos)
    {
        $ProcLab = "0";
        if (isset($datos["TextProce"])) {

            foreach ($datos["TextProce"] as $key => $val) {
                $VidProc = "";
                $infVid = explode("/", $datos["ArchivoSel"][$key]);

                if ($infVid[0] == "si") {
                    foreach ($datos["VideoProc"] as $key2 => $val2) {
                        if ($datos["VideoProcOr"][$key2] == $infVid[1]) {
                            $VidProc = $datos["VideoProc"][$key2];
                        }
                    }
                } else {
                    $VidProc = "";
                }

                $ProcLab = ProcedLaboratorios::create([
                    'laboratorio' => $datos['labo_id'],
                    'procedimiento' => $datos["TextProce"][$key],
                    'vide_proced' => $VidProc,
                ]);
            }
        }
        return $ProcLab;
    }

    public static function Modificar($datos)
    {
        $ProcLab = "0";

        $Opc = ProcedLaboratorios::where('laboratorio', $datos["labo_id"]);
        $Opc->delete();
        if (isset($datos["TextProce"])) {


            foreach ($datos["TextProce"] as $key => $val) {
                $VidProc = "";
                $infVid = explode("/", $datos["ArchivoSel"][$key]);

                if ($infVid[0] == "si") {

                    foreach ($datos["VideoProc"] as $key2 => $val2) {

                        if ($datos["VideoProcOr"][$key2] == $infVid[1]) {
                            $VidProc = $datos["VideoProc"][$key2];
                        }
                    }
                } else {
                    if ($infVid[1] != "") {
                        $VidProc = $infVid[1];
                    } else {
                        $VidProc = "";
                    }
                }

                $ProcLab = ProcedLaboratorios::create([
                    'laboratorio' => $datos['labo_id'],
                    'procedimiento' => $datos["TextProce"][$key],
                    'vide_proced' => $VidProc,
                ]);
            }
        }
        return $ProcLab;
    }

    public static function BuscarProc($id)
    {
        $ProcLab = ProcedLaboratorios::where('laboratorio', $id)
            ->get();
        return $ProcLab;
    }
}
