<?php

namespace App;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Database\Eloquent\Model;

class TemasDocentes extends Model
{

    protected $table = 'temas_docentes';
    protected $fillable = [
        'tema',
        'doc',
        'visto_doc',
        'habilitado_doc',
        'ocultar_doc',
        'grupo'
    ];

    public static function Guardar($tema, $estado)
    {

        $Temas = TemasDocentes::where("tema", $tema)
            ->where("doc", Auth::user()->id)
            ->count();

        if ($Temas > 0) {
            return TemasDocentes::where(['tema' => $tema, 'doc' => Auth::user()->id, 'grupo' => Session::get('GrupActual')])->update([
                'visto_doc' => $estado,
                'grupo' => Session::get('GrupActual')
            ]);
        } else {
            return TemasDocentes::create([
                'tema' => $tema,
                'doc' => Auth::user()->id,
                'visto_doc' => $estado,
                'grupo' => Session::get('GrupActual')
            ]);
        }
    }

    public static function GuardarHabi($tema, $Habi)
    {
        if ($Habi == "Habi") {
            $Habi = "SI";
        } else {
            $Habi = "NO";
        }

        $Temas = TemasDocentes::where("tema", $tema)
            ->where("doc", Auth::user()->id)
            ->count();

        if ($Temas > 0) {
            TemasDocentes::where(['tema' => $tema, 'doc' => Auth::user()->id, 'grupo' => Session::get('GrupActual')])->update([
                'habilitado_doc' => $Habi,
                'grupo' => Session::get('GrupActual')
            ]);
        } else {
            return TemasDocentes::create([
                'tema' => $tema,
                'doc' => Auth::user()->id,
                'habilitado_doc' => $Habi,
                'grupo' => Session::get('GrupActual')
            ]);
        }
    }


    public static function GuardarMost($tema, $Most)
    {
        if ($Most == "Most") {
            $Most = "SI";
        } else {
            $Most = "NO";
        }

        $Temas = TemasDocentes::where("tema", $tema)
            ->where("doc", Auth::user()->id)
            ->count();

        if ($Temas > 0) {
            return TemasDocentes::where(['tema' => $tema, 'doc' => Auth::user()->id, 'grupo' => Session::get('GrupActual')])->update([
                'ocultar_doc' => $Most,
                'grupo' => Session::get('GrupActual')
            ]);
        } else {
            return TemasDocentes::create([
                'tema' => $tema,
                'doc' => Auth::user()->id,
                'ocultar_doc' => $Most,
                'grupo' => Session::get('GrupActual')
            ]);
        }
    }

    public static function GuardarCompTema($data)
    {

        $Opc = TemasDocentes::where('tema', $data["tema_id"])
            ->delete();

        foreach ($data["idDocente"] as $key => $val) {
            if($data["DoceSel"][$key]=="si"){
                $respuesta = TemasDocentes::create([
                    'tema' => $data["tema_id"],
                    'doc' => $data["idDocente"][$key],
                    'grupo' => $data["grupo"][$key],
                ]);
            }
        }

        return $respuesta;
    }
}
