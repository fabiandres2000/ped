<?php

namespace App;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class AsigProf extends Model
{

    protected $table = 'asig_prof';
    protected $fillable = [
        'profesor',
        'asignatura',
        'grado',
        'grupo',
        'jornada',
    ];

    public static function Guardar($data)
    {

        $delAsi = AsigProf::where('profesor', $data["profe_id"])
            ->delete();

        $jornada = "";
        if ($data["profe_jornada"] == "Jornada Tarde") {
            $jornada = "JT";
        } else if ($data["profe_jornada"] == "Jornada Nocturna") {
            $jornada = "JN";
        } else {
            $jornada = "JM";
        }

        foreach ($data["txtasig"] as $key => $val) {
            $respuesta = AsigProf::create([
                'profesor' => $data["profe_id"],
                'asignatura' => $data["txtasig"][$key],
                'grado' => $data["txtgrado"][$key],
                'grupo' => $data["txtgrupo"][$key],
                'jornada' => $jornada,
            ]);
        }




        return $respuesta;
    }



    public static function ConsultarGrupo($Grupo)
    {

        $Grupo = AsigProf::where('grupo', $Grupo)
            ->count();
        return $Grupo;
    }

    public static function listaProf($id)
    {
        $listDoce = DB::connection("mysql")->select("select ap.id, gru.grupo, ap.jornada, usuario_profesor, concat(nombre,' ', apellido, ' - Grupo: ', gru.grupo) ndocente " 
        ." from asig_prof ap "
        ." left join profesores prof on ap.profesor=prof.usuario_profesor "
        ." LEFT JOIN grupos gru ON ap.grupo=gru.id "
        ." where  prof.estado='ACTIVO' AND grado=".$id);

        return $listDoce;
    }

    public static function listaEditProf($grado,$tema){
        $listDoce =DB::connection("mysql")->select("SELECT grup.grupo, ap.jornada, usuario_profesor, CONCAT(nombre,' ',apellido) ndocente, tdoc.id idtemdoc" 
        ." FROM  asig_prof ap  "
        ." LEFT JOIN (SELECT * FROM temas_docentes WHERE tema = ".$tema.") tdoc ON ap.profesor =tdoc.doc "
        ." LEFT JOIN profesores prof ON ap.profesor=prof.usuario_profesor "
        ." LEFT JOIN grupos grup ON ap.grupo=grup.id "
        ." WHERE  prof.estado='ACTIVO' AND grado=".$grado);

        return $listDoce;

    }

    public static function EliminarAsignacion($grup, $doce, $jorn)
    {

        $jornada = "";
        if ($jorn == "Jornada Tarde") {
            $jornada = "JT";
        } else if ($jorn == "Jornada Nocturna") {
            $jornada = "JN";
        } else {
            $jornada = "JM";
        }

        $DelTem = DB::connection("mysql")->select("DELETE FROM asig_prof WHERE grupo=" . $grup . " AND profesor=" . $doce . " AND jornada='" . $jornada . "'");

        return $DelTem;
    }
    public static function DelAsignacion($doce)
    {



        $DelTem = DB::connection("mysql")->select("DELETE FROM asig_prof WHERE profesor=" . $doce . "");

        return $DelTem;
    }

    public static function listar($id)
    {
        $Asig = AsigProf::join("asignaturas", "asignaturas.id", "asig_prof.asignatura")
            ->join("modulos", "modulos.id", "asig_prof.grado")
            ->join("grupos", "grupos.id", "asig_prof.grupo")
            ->join("para_grupos", "para_grupos.id", "grupos.grupo")
            ->where('profesor', $id)
            ->select('asig_prof.*', 'modulos.grado_modulo', 'asignaturas.nombre', 'grupos.grupo as gr', 'para_grupos.descripcion')
            ->get();
        return $Asig;
    }

    public static function GruposxDoc()
    {
        $Grupos = AsigProf::join("grupos", 'grupos.id', "asig_prof.grupo")
            ->join("para_grupos", 'para_grupos.id', "grupos.grupo")
            ->where('profesor', Auth::user()->id)
            ->where('grado', Session::get('IDMODULO'))
            ->select('asig_prof.*', 'para_grupos.descripcion as gr', 'para_grupos.id as idgrupo')
            ->orderBy('para_grupos.id', 'ASC')
            ->get();
        return $Grupos;
    }

    public static function BuscDat($id)
    {
        //        dd(Session::get('GRUPO').'-'. Session::get('JORNADA').'-'.Auth::user()->grado_usuario.'-'.$id);die();
        $DatProf = AsigProf::join("profesores", "profesores.usuario_profesor", "asig_prof.profesor")
            ->join("modulos", 'modulos.id', "asig_prof.grado")
            ->where('asig_prof.grado', $id)
            ->where('asig_prof.grupo', Session::get('GRUPO'))
            ->where('profesores.jornada', Session::get('JORNADA'))
            ->where('modulos.grado_modulo', Auth::user()->grado_usuario)
            ->select('profesores.*')
            ->first();

        return $DatProf;
    }

    public static function BuscAsigAsing($idGra, $idGrup, $jornada)
    {


        $DatProf = AsigProf::join("profesores", "profesores.usuario_profesor", "asig_prof.profesor")
            ->join("modulos", 'modulos.id', "asig_prof.grado")
            ->where('asig_prof.grado', $idGra)
            ->where('asig_prof.grupo', $idGrup)
            ->where('profesores.jornada', $jornada)
            ->select('profesores.*')
            ->selectRaw('(CASE WHEN profesores.jornada = "JM" THEN "Jornada Mañana" WHEN profesores.jornada = "JT" THEN "Jornada Tarde" ELSE "Jornada Nocturna" END) AS Jorna')
            ->first();

        return $DatProf;
    }
}
