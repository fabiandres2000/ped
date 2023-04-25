<?php

namespace App;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class ModProf extends Model
{

    protected $table = 'mod_prof';
    protected $fillable = [
        'profesor',
        'asignatura',
        'grado',
        'grupo',
        'jornada',
    ];

    public static function Guardar($data)
    {

        $jornada = "";

        if ($data["profe_jornada"] == "Jornada Tarde") {
            $jornada = "JT";
        } else if ($data["profe_jornada"] == "Jornada Nocturna") {
            $jornada = "JN";
        } else {
            $jornada = "JM";
        }

        $delAsi = ModProf::where('profesor', $data["profe_id"])
            ->delete();

        foreach ($data["txtasig"] as $key => $val) {
            $respuesta = ModProf::create([
                'profesor' => $data["profe_id"],
                'asignatura' => $data["txtasig"][$key],
                'grado' => $data["txtgrado"][$key],
                'grupo' => $data["txtgrupo"][$key],
                'jornada' => $jornada,
            ]);
        }
        return $respuesta;
    }

    public static function ConsultarGrupo($Grupo){

        $Grupo = ModProf::where('grupo', $Grupo)
        ->count();
    return $Grupo;
    }

    public static function listaProf($id)
    {
        $listDoce = DB::connection("mysql")->select("select ap.id, gru.grupo, ap.jornada, usuario_profesor, concat(nombre,' ', apellido, ' - Grupo: ', gru.grupo) ndocente " 
        ." from mod_prof ap "
        ." left join profesores prof on ap.profesor=prof.usuario_profesor "
        ." LEFT JOIN grupos_transversales gru ON ap.grupo=gru.id "
        ." where  prof.estado='ACTIVO' AND grado=".$id);

        return $listDoce;
    }

    public static function listaEditProf($grado,$tema){
        $listDoce =DB::connection("mysql")->select("SELECT grup.grupo, ap.jornada, usuario_profesor, CONCAT(nombre,' ',apellido) ndocente, tdoc.id idtemdoc" 
        ." FROM  mod_prof ap  "
        ." LEFT JOIN (SELECT * FROM temas_mod_docentes WHERE tema = ".$tema.") tdoc ON ap.profesor =tdoc.doc "
        ." LEFT JOIN profesores prof ON ap.profesor=prof.usuario_profesor "
        ." LEFT JOIN grupos_transversales grup ON ap.grupo=grup.id "
        ." WHERE prof.estado='ACTIVO' AND grado=".$grado);

        return $listDoce;
        
    }

    public static function EliminarAsignacion($grup, $doce, $jorn){

        $jornada = "";
        if ($jorn == "Jornada Tarde") {
            $jornada = "JT";
        } else if ($jorn == "Jornada Nocturna") {
            $jornada = "JN";
        } else {
            $jornada = "JM";
        }

        $DelTem = DB::connection("mysql")->select("DELETE FROM mod_prof WHERE grupo=".$grup." AND profesor=".$doce." AND jornada='".$jornada."'");

        return $DelTem;
    }

    public static function DelAsignacion($doce){


        $DelTem = DB::connection("mysql")->select("DELETE FROM mod_prof WHERE  profesor=".$doce."");

        return $DelTem;
    }

    public static function listar($id)
    {

        $Asig = ModProf::join("modulos_transversales", "modulos_transversales.id", "mod_prof.asignatura")
            ->join("grados_modulos", "grados_modulos.id", "mod_prof.grado")
            ->join("grupos_transversales", "grupos_transversales.id", "mod_prof.grupo")
            ->join("para_grupos", "para_grupos.id", "grupos_transversales.grupo")
            ->where('profesor', $id)
            ->select('mod_prof.*', 'grados_modulos.grado_modulo', 'modulos_transversales.nombre', 'grupos_transversales.grupo as gr', 'para_grupos.descripcion')
            ->get();

        return $Asig;
    }

    public static function GruposxDoc()
    {
        $Grupos = ModProf::join("grupos_transversales", 'grupos_transversales.id', "mod_prof.grupo")
            ->join("para_grupos", 'para_grupos.id', "grupos_transversales.grupo")
            ->where('profesor', Auth::user()->id)
            ->where('grado', Session::get('IDMODULO'))
            ->select('mod_prof.*', 'para_grupos.descripcion as gr', 'para_grupos.id as idgrupo')
            ->orderBy('para_grupos.id', 'ASC')
            ->get();
        return $Grupos;
    }

    public static function BuscDat($id)
    {

        $DatProf = ModProf::join("profesores", "profesores.usuario_profesor", "mod_prof.profesor")
            ->join("grados_modulos", 'grados_modulos.id', "mod_prof.grado")
            ->where('mod_prof.grado', $id)
            ->where('mod_prof.grupo', Session::get('GRUPO'))
            ->where('profesores.jornada', Session::get('JORNADA'))
            ->where('grados_modulos.grado_modulo', Auth::user()->grado_usuario)
            ->select('profesores.*')
            ->first();

        return $DatProf;
    }

    public static function BuscAsigAsing($idGra, $idGrup, $jornada)
    {

        $DatProf = ModProf::join("profesores", "profesores.usuario_profesor", "mod_prof.profesor")
            ->join("grados_modulos", 'grados_modulos.id', "mod_prof.grado")
            ->where('mod_prof.grado', $idGra)
            ->where('mod_prof.grupo', $idGrup)
            ->where('profesores.jornada', $jornada)
            ->select('profesores.*')
            ->selectRaw('(CASE WHEN profesores.jornada = "JM" THEN "Jornada Mañana" WHEN profesores.jornada = "JT" THEN "Jornada Tarde" ELSE "Jornada Nocturna" END) AS Jorna')
            ->first();

        return $DatProf;
    }
}
