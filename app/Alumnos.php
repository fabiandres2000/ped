<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

class Alumnos extends Model
{

    protected $table = 'alumnos';
    protected $fillable = [
        'ident_alumno',
        'nombre_alumno',
        'apellido_alumno',
        'grado_alumno',
        'grupo',
        'sexo_alumno',
        'nacimiento_alumno',
        'direccion_alumno',
        'telefono_alumno',
        'email_alumno',
        'usuario_alumno',
        'estado_alumno',
        'foto_alumno',
        'jornada',
    ];

    public static function Gestion($busqueda, $pagina, $limit)
    {
        if ($pagina == "1") {
            $offset = 0;
        } else {
            $pagina--;
            $offset = $pagina * $limit;
        }
        if (!empty($busqueda)) {
            $respuesta = Alumnos::join("para_grupos", "para_grupos.id", "alumnos.grupo")
            ->where('estado_alumno', 'ACTIVO')
                ->where(function ($query) use ($busqueda) {
                    $query->where('ident_alumno', 'LIKE', '%' . $busqueda . '%')
                        ->orWhere('nombre_alumno', 'LIKE', '%' . $busqueda . '%')
                        ->orWhere('apellido_alumno', 'LIKE', '%' . $busqueda . '%')
                        ->orWhere('email_alumno', 'LIKE', '%' . $busqueda . '%');
                })
                ->selectRaw('CONCAT_WS(" ",nombre_alumno,apellido_alumno) as nomb')
                ->select('alumnos.*','para_grupos.descripcion')
                ->selectRaw('(CASE WHEN jornada = "JM" THEN "Jornada Mañana" WHEN jornada = "JT" THEN "Jornada Tarde" ELSE "Jornada Nocturna" END) AS Jorna')
                ->orderBy('nombre_alumno', 'ASC')
                ->limit($limit)->offset($offset);
        } else {
            $respuesta = Alumnos::join("para_grupos", "para_grupos.id", "alumnos.grupo")
            ->where('estado_alumno', 'ACTIVO')
                ->selectRaw('CONCAT_WS(" ",nombre_alumno,apellido_alumno) as nomb')
                ->select('alumnos.*','para_grupos.descripcion')
                ->selectRaw('(CASE WHEN jornada = "JM" THEN "Jornada Mañana" WHEN jornada = "JT" THEN "Jornada Tarde" ELSE "Jornada Nocturna" END) AS Jorna')
                ->orderBy('nombre_alumno', 'ASC')
                ->limit($limit)->offset($offset);
        }

        return $respuesta->get();
    }

    public static function numero_de_registros($busqueda)
    {
        if (!empty($busqueda)) {
            $respuesta = Alumnos::where('estado_alumno', 'ACTIVO')
                ->Where('jornada', Session::get('JORDOCE'))
                ->where(function ($query) use ($busqueda) {
                    $query->where('ident_alumno', 'LIKE', '%' . $busqueda . '%')
                        ->orWhere('nombre_alumno', 'LIKE', '%' . $busqueda . '%')
                        ->orWhere('apellido_alumno', 'LIKE', '%' . $busqueda . '%')
                        ->orWhere('email_alumno', 'LIKE', '%' . $busqueda . '%');
                })
                ->orderBy('nombre_alumno', 'ASC');
        } else {
            $respuesta = Alumnos::where('estado_alumno', 'ACTIVO')
                ->orderBy('nombre_alumno', 'ASC');
        }
        return $respuesta->count();
    }

    public static function GestionAlumnosEval($busqueda, $pagina, $limit, $grado)
    {
        if ($pagina == "1") {
            $offset = 0;
        } else {
            $pagina--;
            $offset = $pagina * $limit;
        }
        if (!empty($busqueda)) {
            $respuesta = Alumnos::where('estado_alumno', 'ACTIVO')
                ->where('grado_alumno', $grado)
                ->where('alumnos.grupo', Session::get('GrupActual'))
                ->where('jornada', Session::get('JORDOCE'))
                ->where(function ($query) use ($busqueda) {
                    $query->where('ident_alumno', 'LIKE', '%' . $busqueda . '%')
                        ->orWhere('nombre_alumno', 'LIKE', '%' . $busqueda . '%')
                        ->orWhere('apellido_alumno', 'LIKE', '%' . $busqueda . '%')
                        ->orWhere('email_alumno', 'LIKE', '%' . $busqueda . '%');
                })
                ->selectRaw('CONCAT_WS(" ",nombre_alumno,apellido_alumno) as nomb')
                ->select('alumnos.*')
                ->orderBy('nombre_alumno', 'ASC')
                ->limit($limit)->offset($offset);
        } else {
            $respuesta = Alumnos::where('estado_alumno', 'ACTIVO')
                ->where('grado_alumno', $grado)
                ->where('alumnos.grupo', Session::get('GrupActual'))

                ->where('jornada', Session::get('JORDOCE'))
                ->selectRaw('CONCAT_WS(" ",nombre_alumno,apellido_alumno) as nomb')
                ->select('alumnos.*')
                ->orderBy('nombre_alumno', 'ASC')
                ->limit($limit)->offset($offset);
        }

        return $respuesta->get();
    }

    public static function numero_de_registrosAlumnosEval($busqueda, $grado)
    {
        if (!empty($busqueda)) {
            $respuesta = Alumnos::where('estado_alumno', 'ACTIVO')
                ->where('grado_alumno', $grado)
                ->where('alumnos.grupo', Session::get('GrupActual'))
                ->where('jornada', Session::get('JORDOCE'))
                ->where(function ($query) use ($busqueda) {
                    $query->where('ident_alumno', 'LIKE', '%' . $busqueda . '%')
                        ->orWhere('nombre_alumno', 'LIKE', '%' . $busqueda . '%')
                        ->orWhere('apellido_alumno', 'LIKE', '%' . $busqueda . '%')
                        ->orWhere('email_alumno', 'LIKE', '%' . $busqueda . '%');
                })
                ->orderBy('nombre_alumno', 'ASC');
        } else {
            $respuesta = Alumnos::where('estado_alumno', 'ACTIVO')
                ->where('grado_alumno', $grado)
                ->where('alumnos.grupo', Session::get('GrupActual'))
                ->where('jornada', Session::get('JORDOCE'))
                ->orderBy('nombre_alumno', 'ASC');
        }
        return $respuesta->count();
    }

    public static function Guardar($data)
    {
        return Alumnos::create([
            'ident_alumno' => $data['ident_alumno'],
            'nombre_alumno' => $data['nombre_alumno'],
            'apellido_alumno' => $data['apellido_alumno'],
            'grado_alumno' => $data['grado_alumno'],
            'grupo' => $data['grupo'],
            'sexo_alumno' => $data['sexo_alumno'],
            'nacimiento_alumno' => $data['fnacimiento'],
            'direccion_alumno' => $data['direccion_alumno'],
            'telefono_alumno' => $data['telefono_alumno'],
            'email_alumno' => $data['email_alumno'],
            'usuario_alumno' => $data['usuario_alumno'],
            'estado_alumno' => 'ACTIVO',
            'foto_alumno' => $data['img'],
            'jornada' => $data['jornada'],
        ]);
    }

    public static function BuscarAlum($id)
    {

        return Alumnos::findOrFail($id);
    }

    public static function BuscarAlumVal($iden)
    {

        return Alumnos::where("id", $iden)
        ->where("estado","ACTIVO")
        ->first();
    }

    public static function TotAlumnos()
    {

        return Alumnos::where("estado_alumno", "ACTIVO")->get();
    }

    public static function BuscarIdAlum($iden)
    {

        return Alumnos::where("ident_alumno", $iden)->first();
    }

    public static function BuscarAlumFoto($usu)
    {

        return Alumnos::where("usuario_alumno", $usu)->first();
    }

    public static function ConsulSex($data)
    {

        $Resp = Alumnos::where("estado_alumno", "ACTIVO")
            ->selectRaw('count(*) as cant, sexo_alumno');
        if ($data['idasig'] != null) {
            $Resp = $Resp->where('grado_alumno', $data['idasig']);
        }
        if ($data['idjorn'] != null) {
            $Resp = $Resp->where('jornada', $data['idjorn']);
        }

        $Resp = $Resp->groupBy("sexo_alumno")
            ->get();
        return $Resp;
    }

    public static function ConsulGrado($data)
    {

        $Resp = Alumnos::where("estado_alumno", "ACTIVO")
            ->selectRaw('count(*) as cant, grado_alumno, concat("Grado ",grado_alumno,"°") grado');
        if ($data['idasig'] != null) {
            $Resp = $Resp->where('grado_alumno', $data['idasig']);
        }
        if ($data['idjorn'] != null) {
            $Resp = $Resp->where('jornada', $data['idjorn']);
        }
        $Resp = $Resp->groupBy("grado_alumno")
            ->orderBy('grado_alumno', 'ASC')
            ->get();

        return $Resp;
    }

    public static function ConsulGrupEta($data)
    {

        $Resp = Alumnos::selectRaw('count(*) cant,CASE WHEN TIMESTAMPDIFF(YEAR, nacimiento_alumno, CURDATE()) < 6 THEN "< 6" WHEN TIMESTAMPDIFF(YEAR, nacimiento_alumno, CURDATE()) < 10 THEN "6 a 10" WHEN TIMESTAMPDIFF(YEAR, nacimiento_alumno, CURDATE()) < 14 THEN "11 a 14" WHEN TIMESTAMPDIFF(YEAR, nacimiento_alumno, CURDATE()) < 16 THEN "15 a 16" ELSE "> 16" END AS edad');
        if ($data['idasig'] != null) {
            $Resp = $Resp->where('grado_alumno', $data['idasig']);
        }
        if ($data['idjorn'] != null) {
            $Resp = $Resp->where('jornada', $data['idjorn']);
        }
        $Resp = $Resp->groupBy("edad")
            ->orderBy('cant', 'ASC')
            ->get();
        return $Resp;
    }

    public static function ConsulExtraEd($data)
    {

        $Resp = Alumnos::selectRaw('grado_alumno,TIMESTAMPDIFF(YEAR, nacimiento_alumno, CURDATE()) AS edad');
        if ($data['idasig'] != null) {
            $Resp = $Resp->where('grado_alumno', $data['idasig']);
        }
        if ($data['idjorn'] != null) {
            $Resp = $Resp->where('jornada', $data['idjorn']);
        }
        $Resp = $Resp->get();
        return $Resp;
    }

    public static function modificar($data, $id)
    {
        $respuesta = Alumnos::where(['id' => $id])->update([
            'ident_alumno' => $data['ident_alumno'],
            'nombre_alumno' => $data['nombre_alumno'],
            'apellido_alumno' => $data['apellido_alumno'],
            'grado_alumno' => $data['grado_alumno'],
            'grupo' => $data['grupo'],
            'sexo_alumno' => $data['sexo_alumno'],
            'nacimiento_alumno' => $data['fnacimiento'],
            'direccion_alumno' => $data['direccion_alumno'],
            'telefono_alumno' => $data['telefono_alumno'],
            'email_alumno' => $data['email_alumno'],
            'foto_alumno' => $data['img'],
            'jornada' => $data['jornada'],
        ]);
        return $respuesta;
    }

    public static function modificarPerf($data, $id) {
        $respuesta = Alumnos::where(['id' => $id])->update([
            'ident_alumno' => $data['ident_alumno'],
            'nombre_alumno' => $data['nombre_alumno'],
            'apellido_alumno' => $data['apellido_alumno'],
            'sexo_alumno' => $data['sexo_alumno'],
            'nacimiento_alumno' => $data['fnacimiento'],
            'direccion_alumno' => $data['direccion_alumno'],
            'telefono_alumno' => $data['telefono_alumno'],
            'email_alumno' => $data['email_alumno'],
            'foto_alumno' => $data['img']
        ]);
        return $respuesta;
    }

    public static function editarestado($id, $estado)
    {
        $Respuesta = Alumnos::where('id', $id)->update([
            'estado_alumno' => $estado,
        ]);
        return $Respuesta;
    }

    public static function CambiarGradAlumnos($Datos, $Grado, $Grupo, $Jornada)
    {
        foreach ($Datos["idestu"] as $key => $val) {
            if ($Datos["EstSel"][$key] == "si") {
                $Respuesta = Alumnos::where('id', $Datos["idestu"][$key])->update([
                    'grado_alumno' => $Grado,
                    'grupo' => $Grupo,
                    'jornada' => $Jornada,
                ]);

                $usuario = Usuarios::where(['id' => $Datos["usuEstu"][$key]])->update([
                    'grado_usuario' => $Grado
                ]);
            }
        }

        return $Respuesta;
    }

    public static function Desvincular($Datos)
    {
        foreach ($Datos["idestu"] as $key => $val) {
            if ($Datos["EstSel"][$key] == "si") {
                $Alumnos = Alumnos::findOrFail($Datos["idestu"][$key]);
                if ($Datos["DesEst"] == "Egresado") {
                    AlumnosEgresados::create([
                        'ident_alumno' => $Alumnos->ident_alumno,
                        'nombre_alumno' => $Alumnos->nombre_alumno,
                        'apellido_alumno' => $Alumnos->apellido_alumno,
                        'grado_alumno' => $Alumnos->grado_alumno,
                        'grupo' => $Alumnos->grupo,
                        'sexo_alumno' => $Alumnos->sexo_alumno,
                        'nacimiento_alumno' => $Alumnos->nacimiento_alumno,
                        'direccion_alumno' => $Alumnos->direccion_alumno,
                        'telefono_alumno' => $Alumnos->telefono_alumno,
                        'email_alumno' => $Alumnos->email_alumno,
                        'usuario_alumno' => $Alumnos->usuario_alumno,
                        'estado_alumno' => $Alumnos->estado_alumno,
                        'foto_alumno' => $Alumnos->foto_alumno,
                        'jornada' => $Alumnos->jornada,
                    ]);

                    $Respuesta = Alumnos::where('id', $Datos["idestu"][$key])->update([
                        'estado_alumno' => 'ELIMINADOS',
                    ]);
                } else {
                    $Respuesta = Alumnos::where('id', $Datos["idestu"][$key])->update([
                        'estado_alumno' => 'ELIMINADOS',
                    ]);
                }
            }
        }
        return $Respuesta;
    }

    public static function Buscar($id)
    {
        return Alumnos::join('users', 'users.id', 'alumnos.usuario_alumno')
            ->select('alumnos.*', 'users.login_usuario')
            ->where('usuario_alumno', $id)->first();
    }

    public static function BuscarListAlumnos($Grado, $Grupo, $Jornada)
    {
        return Alumnos::where('grado_alumno', $Grado)
            ->where('grupo', $Grupo)
            ->where('jornada', $Jornada)
            ->where('estado_alumno', 'ACTIVO')
            ->get();
    }

    public static function GuardarAlumnoImport($Data, $Alumnos)
    {

        foreach ($Alumnos as $Alum) {

        //Validar formatos fecha excel

            if ($Data['grado_import'] == $Alum['Grado'] && $Data['grupo_import'] == $Alum['Grupo'] && $Data['jornada_import'] == $Alum['Jornada']) {

                $Usuarios = Usuarios::create([
                    'nombre_usuario' => $Alum['Nombre_1'] . ' ' . $Alum['Nombre_2'] . ' ' . $Alum['Apellido_1'] . ' ' . $Alum['Apellido_2'],
                    'login_usuario' => $Alum['Identificacion'],
                    'pasword_usuario' => bcrypt($Alum['Identificacion']),
                    'tipo_usuario' => 'Estudiante',
                    'email_usuario' => $Alum['Email'],
                    'grado_usuario' => $Alum['Grado'],
                    'estado_usuario' => 'ACTIVO'
                ]);
                $imgalumno = "estud_mas_defaul.jpg";

                if ($Alum['Sexo'] == "Femenino") {
                    $imgalumno = "estud_fem_defaul.jpg";
                }

                $var = $Alum['Fecha_Nacimiento'];
                $date = str_replace('/', '-', $var);
                $newfecha = date('Y-m-d', strtotime($date));



                $Alumnos = Alumnos::create([
                    'ident_alumno' => $Alum['Identificacion'],
                    'nombre_alumno' => $Alum['Nombre_1'] . ' ' . $Alum['Nombre_2'],
                    'apellido_alumno' => $Alum['Apellido_1'] . ' ' . $Alum['Apellido_2'],
                    'grado_alumno' => $Alum['Grado'],
                    'grupo' => $Alum['Grupo'],
                    'sexo_alumno' => $Alum['Sexo'],
                    'nacimiento_alumno' => $newfecha,
                    'direccion_alumno' => $Alum['Direccion'],
                    'telefono_alumno' => $Alum['Telefono'],
                    'email_alumno' => $Alum['Email'],
                    'usuario_alumno' => $Usuarios->id,
                    'estado_alumno' => 'ACTIVO',
                    'foto_alumno' => $imgalumno,
                    'jornada' => $Alum['Jornada']
                ]);

            }

        }

        return $Alumnos;

    }

}
