<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class log extends Model
{
    protected $table = 'log_u';
    protected $fillable = [
        'id_usuario',
        'perfil',
        'accion',
        'id_afectado',
        'fecha',
        'hora',
    ];
    public static function Guardar($accion, $Afec)
    {
        return log::create([
            'id_usuario' => Auth::user()->id,
            'perfil' => Auth::user()->tipo_usuario,
            'accion' => $accion,
            'id_afectado' => $Afec,
            'fecha' => date('Y-m-d'),
            'hora' => date('H:i:s'),
        ]);
    }

    public static function Gestion($FIni, $FFin, $pagina, $limit, $Usu)
    {

        if ($pagina == "1") {
            $offset = 0;
        } else {
            $pagina--;
            $offset = $pagina * $limit;
        }

        if (!empty($Usu)) {
            $respuesta = DB::connection("mysql")->select("SELECT t.accion,t.descr,t.nombre_usuario, t.fc "
                . " FROM(SELECT lg.accion, are.nombre_area descr, us.nombre_usuario, CONCAT(lg.fecha, ' ',lg.hora) fc  FROM log_u lg LEFT JOIN areas are "
                . " ON lg.id_afectado=are.id "
                . " LEFT JOIN users us ON lg.id_usuario=us.id"
                . " WHERE lg.id_usuario='" . $Usu . "' AND lg.fecha BETWEEN '" . $FIni . "' AND '" . $FFin . "' AND  lg.accion IN('Área Guardada','Área Modificada','Área Eliminada')"
                . " UNION ALL"
                . " SELECT lg.accion, asig.nombre descr, us.nombre_usuario, CONCAT(lg.fecha, ' ',lg.hora) fc FROM log_u lg LEFT JOIN asignaturas asig "
                . " ON lg.id_afectado=asig.id "
                . " LEFT JOIN users us ON lg.id_usuario=us.id"
                . " WHERE lg.id_usuario='" . $Usu . "' AND lg.fecha BETWEEN '" . $FIni . "' AND '" . $FFin . "' AND  lg.accion IN('Asignatura Guardada','Asignatura Modificada','Asignatura Eliminada')"
                . " UNION ALL "
                . " SELECT lg.accion, CONCAT(asig.nombre,' Grado ',modu.grado_modulo) descr, us.nombre_usuario, CONCAT(lg.fecha, ' ',lg.hora) fc FROM log_u lg LEFT JOIN modulos modu "
                . " ON lg.id_afectado=modu.id "
                . " LEFT JOIN asignaturas asig ON modu.asignatura=asig.id"
                . " LEFT JOIN users us ON lg.id_usuario=us.id"
                . " WHERE lg.id_usuario='" . $Usu . "' AND lg.fecha BETWEEN '" . $FIni . "' AND '" . $FFin . "' AND  lg.accion IN('Grado de Asignatura Guardado','Grado de Asignatura Modificado','Grado de Asignatura Eliminado')"
                . " UNION ALL"
                . " SELECT lg.accion, CONCAT(unid.nom_unidad,' ',unid.des_unidad) descr, us.nombre_usuario, CONCAT(lg.fecha, ' ',lg.hora) fc FROM log_u lg LEFT JOIN unidades unid "
                . " ON lg.id_afectado=unid.id "
                . " LEFT JOIN users us ON lg.id_usuario=us.id"
                . " WHERE lg.id_usuario='" . $Usu . "' AND lg.fecha BETWEEN '" . $FIni . "' AND '" . $FFin . "' AND lg.accion IN('Unidad Guardada','Unidad Modificada','Unidad Eliminada')"
                . " UNION ALL"
                . " SELECT lg.accion, conte.titu_contenido descr, us.nombre_usuario, CONCAT(lg.fecha, ' ',lg.hora) fc FROM log_u lg LEFT JOIN contenido conte "
                . " ON lg.id_afectado=conte.id "
                . " LEFT JOIN users us ON lg.id_usuario=us.id"
                . " WHERE lg.id_usuario='" . $Usu . "' AND lg.fecha BETWEEN '" . $FIni . "' AND '" . $FFin . "' AND  lg.accion IN('Tema Guardado','Tema Modificado','Tema Eliminado')"
                . " UNION ALL"
                . " SELECT lg.accion, eva.titulo descr, us.nombre_usuario, CONCAT(lg.fecha, ' ',lg.hora) fc FROM log_u lg LEFT JOIN evaluacion eva "
                . " ON lg.id_afectado=eva.id "
                . " LEFT JOIN users us ON lg.id_usuario=us.id"
                . " WHERE lg.id_usuario='" . $Usu . "' AND lg.fecha BETWEEN '" . $FIni . "' AND '" . $FFin . "' AND  lg.accion IN('Evaluación Guardada','Evaluación Modificada','Evaluación Eliminada')"
                . " UNION ALL"
                . " SELECT lg.accion, CONCAT(alu.ident_alumno,' - ',alu.nombre_alumno,' ',alu.apellido_alumno) descr, us.nombre_usuario, CONCAT(lg.fecha, ' ',lg.hora) fc FROM log_u lg LEFT JOIN alumnos alu "
                . " ON lg.id_afectado=alu.id "
                . " LEFT JOIN users us ON lg.id_usuario=us.id"
                . " WHERE lg.id_usuario='" . $Usu . "' AND lg.fecha BETWEEN '" . $FIni . "' AND '" . $FFin . "' AND  lg.accion IN('Estudiante Guardado','Estudiante Modificado','Estudiante Eliminado')"
                . " UNION ALL"
                . " SELECT lg.accion, CONCAT(prof.identificacion,' - ',prof.nombre,' ',prof.apellido) descr, us.nombre_usuario, CONCAT(lg.fecha, ' ',lg.hora) fc FROM log_u lg LEFT JOIN profesores prof "
                . " ON lg.id_afectado=prof.id "
                . " LEFT JOIN users us ON lg.id_usuario=us.id"
                . " WHERE lg.id_usuario='" . $Usu . "' AND lg.fecha BETWEEN '" . $FIni . "' AND '" . $FFin . "'  AND lg.accion IN('Docente Guardado','Docente Modificado','Docente Eliminado')"
                . " UNION ALL"
                . " SELECT lg.accion, asig.nombre descr, us.nombre_usuario, CONCAT(lg.fecha, ' ',lg.hora) fc FROM log_u lg LEFT JOIN modulos_transversales asig "
                . " ON lg.id_afectado=asig.id "
                . " LEFT JOIN users us ON lg.id_usuario=us.id"
                . " WHERE lg.id_usuario='" . $Usu . "' AND lg.fecha BETWEEN '" . $FIni . "' AND '" . $FFin . "'  AND lg.accion IN('Módulo Transversal Guardado','Módulo Transversal Editado','Módulo Transversal Eliminado')"
                . " UNION ALL"
                . " SELECT lg.accion, CONCAT(asig.nombre,' Grado ',modu.grado_modulo) descr, us.nombre_usuario, CONCAT(lg.fecha, ' ',lg.hora) fc FROM log_u lg LEFT JOIN grados_modulos modu "
                . " ON lg.id_afectado=modu.id "
                . " LEFT JOIN modulos_transversales asig ON modu.modulo=asig.id"
                . " LEFT JOIN users us ON lg.id_usuario=us.id"
                . " WHERE lg.id_usuario='" . $Usu . "' AND lg.fecha BETWEEN '" . $FIni . "' AND '" . $FFin . "'  AND lg.accion IN('Grado de Módulo Guardado','Grado de Módulo Modificado','Grado de Módulo Eliminado')"
                . " UNION ALL"
                . " SELECT lg.accion, CONCAT(unid.nom_unidad,' ',unid.des_unidad) descr, us.nombre_usuario, CONCAT(lg.fecha, ' ',lg.hora) fc FROM log_u lg LEFT JOIN unidades_modulos unid "
                . " ON lg.id_afectado=unid.id "
                . " LEFT JOIN users us ON lg.id_usuario=us.id"
                . " WHERE lg.id_usuario='" . $Usu . "' AND lg.fecha BETWEEN '" . $FIni . "' AND '" . $FFin . "'  AND lg.accion IN('Unidad de Módulos Guardada','Unidad de Módulos Modificada','Unidad de Módulos Eliminada')"
                . " UNION ALL"
                . " SELECT lg.accion, conte.titu_contenido descr, us.nombre_usuario, CONCAT(lg.fecha, ' ',lg.hora) fc FROM log_u lg LEFT JOIN contenido_modulo conte "
                . " ON lg.id_afectado=conte.id "
                . " LEFT JOIN users us ON lg.id_usuario=us.id"
                . " WHERE lg.id_usuario='" . $Usu . "' AND lg.fecha BETWEEN '" . $FIni . "' AND '" . $FFin . "'  AND lg.accion IN('Tema de Módulo Guardado','Tema de Módulo Modificado','Tema de Módulo Eliminado')"
                . " UNION ALL"
                . " SELECT lg.accion, eva.titulo descr, us.nombre_usuario, CONCAT(lg.fecha, ' ',lg.hora) fc FROM log_u lg LEFT JOIN evaluacion eva "
                . " ON lg.id_afectado=eva.id "
                . " LEFT JOIN users us ON lg.id_usuario=us.id"
                . " WHERE lg.id_usuario='" . $Usu . "' AND lg.fecha BETWEEN '" . $FIni . "' AND '" . $FFin . "'  AND lg.accion IN('Evaluación Módulo Guardada','Evaluación Módulo Modificada','Evaluación Módulo Eliminada')) AS t limit " . $offset . "," . $limit . "");

        } else {
            $respuesta = DB::connection("mysql")->select("SELECT t.accion,t.descr,t.nombre_usuario, t.fc "
                . " FROM(SELECT lg.accion, are.nombre_area descr, us.nombre_usuario, CONCAT(lg.fecha, ' ',lg.hora) fc  FROM log_u lg LEFT JOIN areas are "
                . " ON lg.id_afectado=are.id "
                . " LEFT JOIN users us ON lg.id_usuario=us.id"
                . " WHERE lg.fecha BETWEEN '" . $FIni . "' AND '" . $FFin . "' AND  lg.accion IN('Área Guardada','Área Modificada','Área Eliminada')"
                . " UNION ALL"
                . " SELECT lg.accion, asig.nombre descr, us.nombre_usuario, CONCAT(lg.fecha, ' ',lg.hora) fc FROM log_u lg LEFT JOIN asignaturas asig "
                . " ON lg.id_afectado=asig.id "
                . " LEFT JOIN users us ON lg.id_usuario=us.id"
                . " WHERE lg.fecha BETWEEN '" . $FIni . "' AND '" . $FFin . "' AND  lg.accion IN('Asignatura Guardada','Asignatura Modificada','Asignatura Eliminada')"
                . " UNION ALL "
                . " SELECT lg.accion, CONCAT(asig.nombre,' Grado ',modu.grado_modulo) descr, us.nombre_usuario, CONCAT(lg.fecha, ' ',lg.hora) fc FROM log_u lg LEFT JOIN modulos modu "
                . " ON lg.id_afectado=modu.id "
                . " LEFT JOIN asignaturas asig ON modu.asignatura=asig.id"
                . " LEFT JOIN users us ON lg.id_usuario=us.id"
                . " WHERE lg.fecha BETWEEN '" . $FIni . "' AND '" . $FFin . "' AND  lg.accion IN('Grado de Asignatura Guardado','Grado de Asignatura Modificado','Grado de Asignatura Eliminado')"
                . " UNION ALL"
                . " SELECT lg.accion, CONCAT(unid.nom_unidad,' ',unid.des_unidad) descr, us.nombre_usuario, CONCAT(lg.fecha, ' ',lg.hora) fc FROM log_u lg LEFT JOIN unidades unid "
                . " ON lg.id_afectado=unid.id "
                . " LEFT JOIN users us ON lg.id_usuario=us.id"
                . " WHERE lg.fecha BETWEEN '" . $FIni . "' AND '" . $FFin . "' AND lg.accion IN('Unidad Guardada','Unidad Modificada','Unidad Eliminada')"
                . " UNION ALL"
                . " SELECT lg.accion, conte.titu_contenido descr, us.nombre_usuario, CONCAT(lg.fecha, ' ',lg.hora) fc FROM log_u lg LEFT JOIN contenido conte "
                . " ON lg.id_afectado=conte.id "
                . " LEFT JOIN users us ON lg.id_usuario=us.id"
                . " WHERE lg.fecha BETWEEN '" . $FIni . "' AND '" . $FFin . "' AND  lg.accion IN('Tema Guardado','Tema Modificado','Tema Eliminado')"
                . " UNION ALL"
                . " SELECT lg.accion, eva.titulo descr, us.nombre_usuario, CONCAT(lg.fecha, ' ',lg.hora) fc FROM log_u lg LEFT JOIN evaluacion eva "
                . " ON lg.id_afectado=eva.id "
                . " LEFT JOIN users us ON lg.id_usuario=us.id"
                . " WHERE lg.fecha BETWEEN '" . $FIni . "' AND '" . $FFin . "' AND  lg.accion IN('Evaluación Guardada','Evaluación Modificada','Evaluación Eliminada')"
                . " UNION ALL"
                . " SELECT lg.accion, CONCAT(alu.ident_alumno,' - ',alu.nombre_alumno,' ',alu.apellido_alumno) descr, us.nombre_usuario, CONCAT(lg.fecha, ' ',lg.hora) fc FROM log_u lg LEFT JOIN alumnos alu "
                . " ON lg.id_afectado=alu.id "
                . " LEFT JOIN users us ON lg.id_usuario=us.id"
                . " WHERE lg.fecha BETWEEN '" . $FIni . "' AND '" . $FFin . "' AND  lg.accion IN('Estudiante Guardado','Estudiante Modificado','Estudiante Eliminado')"
                . " UNION ALL"
                . " SELECT lg.accion, CONCAT(prof.identificacion,' - ',prof.nombre,' ',prof.apellido) descr, us.nombre_usuario, CONCAT(lg.fecha, ' ',lg.hora) fc FROM log_u lg LEFT JOIN profesores prof "
                . " ON lg.id_afectado=prof.id "
                . " LEFT JOIN users us ON lg.id_usuario=us.id"
                . " WHERE lg.fecha BETWEEN '" . $FIni . "' AND '" . $FFin . "' AND lg.accion IN('Docente Guardado','Docente Modificado','Docente Eliminado')"
                . " UNION ALL"
                . " SELECT lg.accion, asig.nombre descr, us.nombre_usuario, CONCAT(lg.fecha, ' ',lg.hora) fc FROM log_u lg LEFT JOIN modulos_transversales asig "
                . " ON lg.id_afectado=asig.id "
                . " LEFT JOIN users us ON lg.id_usuario=us.id"
                . " WHERE lg.id_usuario='" . $Usu . "' AND lg.fecha BETWEEN '" . $FIni . "' AND '" . $FFin . "'  AND lg.accion IN('Módulo Transversal Guardado','Módulo Transversal Editado','Módulo Transversal Eliminado')"
                . " UNION ALL"
                . " SELECT lg.accion, CONCAT(asig.nombre,' Grado ',modu.grado_modulo) descr, us.nombre_usuario, CONCAT(lg.fecha, ' ',lg.hora) fc FROM log_u lg LEFT JOIN grados_modulos modu "
                . " ON lg.id_afectado=modu.id "
                . " LEFT JOIN modulos_transversales asig ON modu.modulo=asig.id"
                . " LEFT JOIN users us ON lg.id_usuario=us.id"
                . " WHERE lg.id_usuario='" . $Usu . "' AND lg.fecha BETWEEN '" . $FIni . "' AND '" . $FFin . "'  AND lg.accion IN('Grado de Módulo Guardado','Grado de Módulo Modificado','Grado de Módulo Eliminado')"
                . " UNION ALL"
                . " SELECT lg.accion, CONCAT(unid.nom_unidad,' ',unid.des_unidad) descr, us.nombre_usuario, CONCAT(lg.fecha, ' ',lg.hora) fc FROM log_u lg LEFT JOIN unidades_modulos unid "
                . " ON lg.id_afectado=unid.id "
                . " LEFT JOIN users us ON lg.id_usuario=us.id"
                . " WHERE lg.id_usuario='" . $Usu . "' AND lg.fecha BETWEEN '" . $FIni . "' AND '" . $FFin . "'  AND lg.accion IN('Unidad de Módulos Guardada','Unidad de Módulos Modificada','Unidad de Módulos Eliminada')"
                . " UNION ALL"
                . " SELECT lg.accion, conte.titu_contenido descr, us.nombre_usuario, CONCAT(lg.fecha, ' ',lg.hora) fc FROM log_u lg LEFT JOIN contenido_modulo conte "
                . " ON lg.id_afectado=conte.id "
                . " LEFT JOIN users us ON lg.id_usuario=us.id"
                . " WHERE lg.id_usuario='" . $Usu . "' AND lg.fecha BETWEEN '" . $FIni . "' AND '" . $FFin . "'  AND lg.accion IN('Tema de Módulo Guardado','Tema de Módulo Modificado','Tema de Módulo Eliminado')"
                . " UNION ALL"
                . " SELECT lg.accion, eva.titulo descr, us.nombre_usuario, CONCAT(lg.fecha, ' ',lg.hora) fc FROM log_u lg LEFT JOIN evaluacion eva "
                . " ON lg.id_afectado=eva.id "
                . " LEFT JOIN users us ON lg.id_usuario=us.id"
                . " WHERE lg.id_usuario='" . $Usu . "' AND lg.fecha BETWEEN '" . $FIni . "' AND '" . $FFin . "'  AND lg.accion IN('Evaluación Módulo Guardada','Evaluación Módulo Modificada','Evaluación Módulo Eliminada')) AS t limit " . $offset . "," . $limit . "");

        }

        return $respuesta;
    }

    public static function numero_de_registros($FIni, $FFin, $Usu)
    {
        if (!empty($Usu)) {
            $respuesta = DB::connection("mysql")->select("SELECT t.accion,t.descr,t.nombre_usuario, t.fc FROM(SELECT lg.accion, are.nombre_area descr, us.nombre_usuario, CONCAT(lg.fecha, ' ',lg.hora) fc FROM log_u lg LEFT JOIN areas are "
                . " ON lg.id_afectado=are.id "
                . " LEFT JOIN users us ON lg.id_usuario=us.id"
                . " WHERE lg.fecha BETWEEN '" . $FIni . "' AND '" . $FFin . "' AND lg.id_usuario='" . $Usu . "' AND lg.accion IN('Área Guardada','Área Modificada','Área Eliminada')"
                . " UNION ALL"
                . " SELECT lg.accion, asig.nombre descr, us.nombre_usuario, CONCAT(lg.fecha, ' ',lg.hora) fc FROM log_u lg LEFT JOIN asignaturas asig "
                . " ON lg.id_afectado=asig.id "
                . " LEFT JOIN users us ON lg.id_usuario=us.id"
                . " WHERE lg.fecha BETWEEN '" . $FIni . "' AND '" . $FFin . "' AND lg.id_usuario='" . $Usu . "' AND lg.accion IN('Asignatura Guardada','Asignatura Modificada','Asignatura Eliminada')"
                . " UNION ALL "
                . " SELECT lg.accion, CONCAT(asig.nombre,' Grado ',modu.grado_modulo) descr, us.nombre_usuario, CONCAT(lg.fecha, ' ',lg.hora) fc FROM log_u lg LEFT JOIN modulos modu "
                . " ON lg.id_afectado=modu.id "
                . " LEFT JOIN asignaturas asig ON modu.asignatura=asig.id"
                . " LEFT JOIN users us ON lg.id_usuario=us.id"
                . " WHERE lg.fecha BETWEEN '" . $FIni . "' AND '" . $FFin . "' AND lg.id_usuario='" . $Usu . "' AND lg.accion IN('Grado de Asignatura Guardado','Grado de Asignatura Modificado','Grado de Asignatura Eliminado')"
                . " UNION ALL"
                . " SELECT lg.accion, CONCAT(unid.nom_unidad,' ',unid.des_unidad) descr, us.nombre_usuario, CONCAT(lg.fecha, ' ',lg.hora) fc FROM log_u lg LEFT JOIN unidades unid "
                . " ON lg.id_afectado=unid.id "
                . " LEFT JOIN users us ON lg.id_usuario=us.id"
                . " WHERE lg.fecha BETWEEN '" . $FIni . "' AND '" . $FFin . "' AND lg.id_usuario='" . $Usu . "' AND lg.accion IN('Unidad Guardada','Unidad Modificada','Unidad Eliminada')"
                . " UNION ALL"
                . " SELECT lg.accion, conte.titu_contenido descr, us.nombre_usuario, CONCAT(lg.fecha, ' ',lg.hora) fc FROM log_u lg LEFT JOIN contenido conte "
                . " ON lg.id_afectado=conte.id "
                . " LEFT JOIN users us ON lg.id_usuario=us.id"
                . " WHERE lg.fecha BETWEEN '" . $FIni . "' AND '" . $FFin . "' AND lg.id_usuario='" . $Usu . "' AND lg.accion IN('Tema Guardado','Tema Modificado','Tema Eliminado')"
                . " UNION ALL"
                . " SELECT lg.accion, eva.titulo descr, us.nombre_usuario, CONCAT(lg.fecha, ' ',lg.hora) fc FROM log_u lg LEFT JOIN evaluacion eva "
                . " ON lg.id_afectado=eva.id "
                . " LEFT JOIN users us ON lg.id_usuario=us.id"
                . " WHERE lg.fecha BETWEEN '" . $FIni . "' AND '" . $FFin . "' AND lg.id_usuario='" . $Usu . "' AND lg.accion IN('Evaluación Guardada','Evaluación Modificada','Evaluación Eliminada')"
                . " UNION ALL"
                . " SELECT lg.accion, CONCAT(alu.ident_alumno,' - ',alu.nombre_alumno,' ',alu.apellido_alumno) descr, us.nombre_usuario, CONCAT(lg.fecha, ' ',lg.hora) fc FROM log_u lg LEFT JOIN alumnos alu "
                . " ON lg.id_afectado=alu.id "
                . " LEFT JOIN users us ON lg.id_usuario=us.id"
                . " WHERE lg.fecha BETWEEN '" . $FIni . "' AND '" . $FFin . "' AND lg.id_usuario='" . $Usu . "' AND lg.accion IN('Estudiante Guardado','Estudiante Modificado','Estudiante Eliminado')"
                . " UNION ALL"
                . " SELECT lg.accion, CONCAT(prof.identificacion,' - ',prof.nombre,' ',prof.apellido) descr, us.nombre_usuario, CONCAT(lg.fecha, ' ',lg.hora) fc FROM log_u lg LEFT JOIN profesores prof "
                . " ON lg.id_afectado=prof.id "
                . " LEFT JOIN users us ON lg.id_usuario=us.id"
                . " WHERE lg.fecha BETWEEN '" . $FIni . "' AND '" . $FFin . "' AND lg.id_usuario='" . $Usu . "' AND lg.accion IN('Docente Guardado','Docente Modificado','Docente Eliminado')) AS t");

        } else {
            $respuesta = DB::connection("mysql")->select("SELECT t.accion,t.descr,t.nombre_usuario, t.fc FROM(SELECT lg.accion, are.nombre_area descr, us.nombre_usuario, CONCAT(lg.fecha, ' ',lg.hora) fc  FROM log_u lg LEFT JOIN areas are "
                . " ON lg.id_afectado=are.id "
                . " LEFT JOIN users us ON lg.id_usuario=us.id"
                . " WHERE lg.fecha BETWEEN '" . $FIni . "' AND '" . $FFin . "' AND  lg.accion IN('Área Guardada','Área Modificada','Área Eliminada')"
                . " UNION ALL"
                . " SELECT lg.accion, asig.nombre descr, us.nombre_usuario, CONCAT(lg.fecha, ' ',lg.hora) fc FROM log_u lg LEFT JOIN asignaturas asig "
                . " ON lg.id_afectado=asig.id "
                . " LEFT JOIN users us ON lg.id_usuario=us.id"
                . " WHERE lg.fecha BETWEEN '" . $FIni . "' AND '" . $FFin . "' AND  lg.accion IN('Asignatura Guardada','Asignatura Modificada','Asignatura Eliminada')"
                . " UNION ALL "
                . " SELECT lg.accion, CONCAT(asig.nombre,' Grado ',modu.grado_modulo) descr, us.nombre_usuario, CONCAT(lg.fecha, ' ',lg.hora) fc FROM log_u lg LEFT JOIN modulos modu "
                . " ON lg.id_afectado=modu.id "
                . " LEFT JOIN asignaturas asig ON modu.asignatura=asig.id"
                . " LEFT JOIN users us ON lg.id_usuario=us.id"
                . " WHERE lg.fecha BETWEEN '" . $FIni . "' AND '" . $FFin . "' AND  lg.accion IN('Grado de Asignatura Guardado','Grado de Asignatura Modificado','Grado de Asignatura Eliminado')"
                . " UNION ALL"
                . " SELECT lg.accion, CONCAT(unid.nom_unidad,' ',unid.des_unidad) descr, us.nombre_usuario, CONCAT(lg.fecha, ' ',lg.hora) fc FROM log_u lg LEFT JOIN unidades unid "
                . " ON lg.id_afectado=unid.id "
                . " LEFT JOIN users us ON lg.id_usuario=us.id"
                . " WHERE lg.fecha BETWEEN '" . $FIni . "' AND '" . $FFin . "' AND lg.accion IN('Unidad Guardada','Unidad Modificada','Unidad Eliminada')"
                . " UNION ALL"
                . " SELECT lg.accion, conte.titu_contenido descr, us.nombre_usuario, CONCAT(lg.fecha, ' ',lg.hora) fc FROM log_u lg LEFT JOIN contenido conte "
                . " ON lg.id_afectado=conte.id "
                . " LEFT JOIN users us ON lg.id_usuario=us.id"
                . " WHERE lg.fecha BETWEEN '" . $FIni . "' AND '" . $FFin . "' AND  lg.accion IN('Tema Guardado','Tema Modificado','Tema Eliminado')"
                . " UNION ALL"
                . " SELECT lg.accion, eva.titulo descr, us.nombre_usuario, CONCAT(lg.fecha, ' ',lg.hora) fc FROM log_u lg LEFT JOIN evaluacion eva "
                . " ON lg.id_afectado=eva.id "
                . " LEFT JOIN users us ON lg.id_usuario=us.id"
                . " WHERE lg.fecha BETWEEN '" . $FIni . "' AND '" . $FFin . "' AND  lg.accion IN('Evaluación Guardada','Evaluación Modificada','Evaluación Eliminada')"
                . " UNION ALL"
                . " SELECT lg.accion, CONCAT(alu.ident_alumno,' - ',alu.nombre_alumno,' ',alu.apellido_alumno) descr, us.nombre_usuario, CONCAT(lg.fecha, ' ',lg.hora) fc FROM log_u lg LEFT JOIN alumnos alu "
                . " ON lg.id_afectado=alu.id "
                . " LEFT JOIN users us ON lg.id_usuario=us.id"
                . " WHERE lg.fecha BETWEEN '" . $FIni . "' AND '" . $FFin . "' AND  lg.accion IN('Estudiante Guardado','Estudiante Modificado','Estudiante Eliminado')"
                . " UNION ALL"
                . " SELECT lg.accion, CONCAT(prof.identificacion,' - ',prof.nombre,' ',prof.apellido) descr, us.nombre_usuario, CONCAT(lg.fecha, ' ',lg.hora) fc FROM log_u lg LEFT JOIN profesores prof "
                . " ON lg.id_afectado=prof.id "
                . " LEFT JOIN users us ON lg.id_usuario=us.id"
                . " WHERE lg.fecha BETWEEN '" . $FIni . "' AND '" . $FFin . "' AND lg.accion IN('Docente Guardado','Docente Modificado','Docente Eliminado')) AS t");
        }
        return count($respuesta);
    }
}
