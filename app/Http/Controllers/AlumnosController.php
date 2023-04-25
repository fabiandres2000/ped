<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Facades\Session;

class AlumnosController extends Controller
{

    public function GestionAlumnos()
    {
        $bandera = "Menu4";
        if (Auth::check()) {
            $busqueda = request()->get('txtbusqueda');
            $actual = request()->get('page');
            if ($actual == null || $actual == "") {
                $actual = 1;
            }
            $limit = 10;
            $Alumnos = \App\Alumnos::Gestion($busqueda, $actual, $limit);
            $numero_filas = \App\Alumnos::numero_de_registros(request()->get('txtbusqueda'));
            $paginas = ceil($numero_filas / $limit); //$numero_filas/10;
            return view('Alumnos.Gestion', compact('bandera', 'numero_filas', 'paginas', 'actual', 'limit', 'busqueda', 'Alumnos'));
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function GestionNuevo()
    {
        $bandera = "Menu4";
        $opc = "nuevo";
        $Grupos = "";
        if (Auth::check()) {
            $Alumno = new \App\Alumnos();
            $Cole = \App\Colegios::InfColeg(Session::get('IdColegio'));

            $ParGrupos = \App\ParGrupos::LisGrupos($Cole->num_cursos, $Cole->cant_grupos);

            $SelGrupos = "";
            foreach ($ParGrupos as $Grup) {
                $SelGrupos .= "<option value='$Grup->id' >" . strtoupper($Grup->descripcion) . "</option>";
            }

            return view('Alumnos.Nuevo', compact('bandera', 'Alumno', 'opc', 'SelGrupos'));
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function Administrar()
    {
        $bandera = "Menu4";
        $Grupos = "";
        if (Auth::check()) {
            $Alumno = new \App\Alumnos();
            $Cole = \App\Colegios::InfColeg(Session::get('IdColegio'));

            $ParGrupos = \App\ParGrupos::LisGrupos($Cole->num_cursos, $Cole->cant_grupos);

            $SelGrupos = "";
            foreach ($ParGrupos as $Grup) {
                $SelGrupos .= "<option value='$Grup->id' >" . strtoupper($Grup->descripcion) . "</option>";
            }

            return view('Alumnos.Administrar', compact('bandera', 'SelGrupos'));
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function Guardar()
    {
        $bandera = "Menu4";
        if (Auth::check()) {
            //            dd(request()->all());die();
            $this->validate(request(), [
                'ident_alumno' => 'required',
                'nombre_alumno' => 'required',
                'apellido_alumno' => 'required',
                'grado_alumno' => 'required',
                'grupo' => 'required',
                'password' => 'required|confirmed',
                'password_confirmation' => 'required',
                'usuario_alumno' => 'required|unique:alumnos,usuario_alumno',
            ], [
                'ident_alumno.required' => 'El Número de Identificación es Obligatorio',
                'nombre_alumno.required' => 'El Nombre es obligatorio',
                'apellido_alumno.required' => 'El Apellido es Obligatorio',
                'grado_alumno.unique' => 'El Grado del Estudiante es Obligatorio',
                'grupo.unique' => 'El Grupo del Estudiante es Obligatorio',
                'usuario_alumno.required' => 'El Usuario es Obligatorio',
                'password.required' => 'La contraseña es Obligatoria',
                'password_confirmation.required' => 'El Campo Repetir Contraseña es Obligatorio',
                'password.confirmed' => 'Las contraseña no Coinciden',
                'usuario_alumno.unique' => 'El Usuario ya Existe',
            ]);
            $data = request()->all();

            if ($data['sexo_alumno'] == "Masculino") {
                $img = "estud_mas_defaul.jpg";
            } else {
                $img = "estud_fem_defaul.jpg";
            }

            if (request()->hasfile('imagen')) {
                foreach (request()->file('imagen') as $file) {
                    $name = rand(1, 100) . '_' . $file->getClientOriginalName();
                    $file->move(public_path() . '/app-assets/images/Img_Estudiantes/', $name);
                    $img = $name;
                }
            }
            $data['img'] = $img;

            $usuario = \App\Usuarios::Guardar($data);
            if ($usuario) {
                $data['usuario_alumno'] = $usuario->id;
                $Alumnos = \App\Alumnos::Guardar($data);
                if ($Alumnos) {
                    $Log = \App\Log::Guardar('Estudiante Guardado', $Alumnos->id);
                    return redirect('Alumnos/Gestion')->with('success', 'Datos Guardados');
                } else {
                    return redirect('Alumnos/Gestion')->with('error', 'Datos no Guardados');
                }
            } else {
                return redirect('Alumnos/Gestion')->with('error', 'Datos no Guardados');
            }
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function editar($id)
    {
        $bandera = "Menu4";
        $opc = "editar";
        $Grupos = "";
        if (Auth::check()) {
            $Alumno = \App\Alumnos::BuscarAlum($id);
            $Cole = \App\Colegios::InfColeg(Session::get('IdColegio'));
            $ParGrupos = \App\ParGrupos::LisGrupos($Cole->num_cursos, $Cole->cant_grupos);

            $SelGrupos = "";

            foreach ($ParGrupos as $ParGrup) {
                $SelGrupos .= "<option value='$ParGrup->id' ";
                if ($Alumno->grupo == $ParGrup->id) {
                    $SelGrupos .= "selected";
                }
                $SelGrupos .= ">" . strtoupper($ParGrup->descripcion) . "</option>";
            }

            return view('Alumnos.Editar', compact('bandera', 'Alumno', 'opc', 'SelGrupos'));
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function ValAlumnos()
    {

        $exit = "no";
        $ident = request()->get('idAlumn');
        $Alumnos = \App\Alumnos::BuscarAlumVal($ident);
        if ($Alumnos) {
            if ($Alumnos->ident_alumno != $ident) {
                $Alum = \App\Alumnos::BuscarIdAlum(request()->get('id'));
                if ($Alum) {
                    $exit = "si";
                }
            }
        } else {
            $Alum = \App\Alumnos::BuscarIdAlum(request()->get('id'));
            if ($Alum) {
                $exit = "si";
            }
        }

        if (request()->ajax()) {
            return response()->json([
                'exit' => $exit,
            ]);
        }
    }

    public function consultar($id)
    {
        $bandera = "Menu4";
        $opc = "Consulta";
        if (Auth::check()) {
            $Alumno = \App\Alumnos::BuscarAlum($id);
            $Cole = \App\Colegios::InfColeg(Session::get('IdColegio'));
            $ParGrupos = \App\ParGrupos::LisGrupos($Cole->num_cursos, $Cole->cant_grupos);

            $SelGrupos = "";

            foreach ($ParGrupos as $ParGrup) {
                $SelGrupos .= "<option value='$ParGrup->id' ";
                if ($Alumno->grupo == $ParGrup->id) {
                    $SelGrupos .= "selected";
                }
                $SelGrupos .= ">" . strtoupper($ParGrup->descripcion) . "</option>";
            }

            return view('Alumnos.Consultar', compact('bandera', 'Alumno', 'opc', 'SelGrupos'));
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function BuscarAlumnos()
    {

        if (Auth::check()) {
            $Grado = request()->get('Grado');
            $Grupo = request()->get('Grupo');
            $Jornada = request()->get('Jornada');

            $Alumno = \App\Alumnos::BuscarListAlumnos($Grado, $Grupo, $Jornada);

            if (request()->ajax()) {
                return response()->json([
                    'Alumnos' => $Alumno,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function AdministrarAlumnos()
    {
        if (Auth::check()) {
            $Datos = request()->all();
            if ($Datos['Opc'] == "Desvincular") {
                $Alumno = \App\Alumnos::Desvincular($Datos);
                if (request()->ajax()) {
                    return response()->json([
                        'Mensaje' => 'OK',
                        'Alumnos' => $Alumno,
                    ]);
                }
            } elseif ($Datos['Opc'] == "Promover") {
                //Verificar cursos
                $Grado = request()->get('GradProv');
                $Grupo = request()->get('GrupProv');
                $Jornada = request()->get('JornProv');

                $Alumno = \App\Alumnos::BuscarListAlumnos($Grado, $Grupo, $Jornada);

                if ($Alumno->count() > 0) {
                    if (request()->ajax()) {
                        return response()->json([
                            'Mensaje' => 'Exiten',
                            'Alumnos' => $Alumno,
                        ]);
                    }
                } else {

                    $Alumno = \App\Alumnos::CambiarGradAlumnos($Datos, $Grado, $Grupo, $Jornada);

                    if (request()->ajax()) {
                        return response()->json([
                            'Mensaje' => 'OK',
                            'Alumnos' => $Alumno,
                        ]);
                    }
                }
            } else {
                $Grado = request()->get('GradProv');
                $Grupo = request()->get('GrupProv');
                $Jornada = request()->get('JornProv');

                $Alumno = \App\Alumnos::CambiarGradAlumnos($Datos, $Grado, $Grupo, $Jornada);

                if (request()->ajax()) {
                    return response()->json([
                        'Mensaje' => 'OK',
                        'Alumnos' => $Alumno,
                    ]);
                }
            }
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function modificar($id)
    {
        if (Auth::check()) {

            $data = request()->all();

            $Alumnos = \App\Alumnos::BuscarAlum($id);
            $this->validate(request(), [
                'ident_alumno' => 'required',
                'nombre_alumno' => 'required',
                'apellido_alumno' => 'required',
                'grado_alumno' => 'required',
            ], [
                'ident_alumno.required' => 'El Número de Identificación es Obligatorio',

                'nombre_alumno.required' => 'El Nombre es obligatorio',
                'apellido_alumno.required' => 'El Apellido es Obligatorio',
                'grado_alumno.required' => 'El Grado del Estudiante es Obligatorio',
            ]);
            $data = request()->all();

            if (request()->hasfile('imagen')) {
                foreach (request()->file('imagen') as $file) {
                    $name = rand(1, 100) . '_' . $file->getClientOriginalName();
                    $file->move(public_path() . '/app-assets/images/Img_Estudiantes/', $name);
                    $img = $name;
                }
            } else {
                $img = $data['fotoalumno'];
            }
            $data['img'] = $img;

            $respuesta = \App\Alumnos::modificar($data, $id);
            if ($respuesta) {
                $Log = \App\Log::Guardar('Estudiante Modificado', $id);
                $respuesta = \App\Usuarios::modificarUsuario($data, $Alumnos->usuario_alumno,);
                return redirect('/Alumnos/Gestion')->with('success', 'Datos Guardados');
            } else {
                return redirect('Alumnos/Gestion')->with('error', 'Datos no Guardados');
            }
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function Eliminar()
    {
        $mensaje = "";
        $id = request()->get('id');
        if (Auth::check()) {
            $Alumnos = \App\Alumnos::BuscarAlum($id);
            $estado = "ACTIVO";
            if ($Alumnos->estado_alumno == "ACTIVO") {
                $estado = "ELIMINADO";
            } else {
                $estado = "ACTIVO";
            }
            $respuesta = \App\Alumnos::editarestado($id, $estado);

            if ($respuesta) {
                if ($estado == "ELIMINADO") {
                    $RespUsu = \App\Usuarios::editarestado($Alumnos->usuario_alumno);

                    $Log = \App\Log::Guardar('Estudiante Eliminado', $id);
                    $mensaje = 'Operación Realizada de Manera Exitosa';
                }
            } else {
                $mensaje = 'La Operación no pudo ser Realizada';
            }
            if (request()->ajax()) {
                return response()->json([
                    'estado' => $estado,
                    'mensaje' => $mensaje,
                    'id' => $id,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function ImportarAlumnos()
    {

        if (Auth::check()) {
            $datos = request()->all();
            $ruta = "";
            if (request()->hasfile('FormartoAlumnos')) {
                $prefijo = substr(md5(uniqid(rand())), 0, 6);
                $name = self::sanear_string($prefijo . '_' . request()->file('FormartoAlumnos')->getClientOriginalName());
                $ruta = request()->file('FormartoAlumnos')->move(public_path() . '/app-assets/ArchivosEstudiantes/', $name);
            } else {
                $name = "Nada";
            }

            if ($name != "Nada") {

                $Alumnos = (new FastExcel)->import($ruta);
                $validado = array();
                $flag = true;

                foreach ($Alumnos as $Alum) {
                    //Validar formatos jornada excel
                    if (preg_match('/^(JM|JT|Jn)$/', $Alum['Jornada'])) {
                        array_push($validado, "Formato de Jornada inconrrecto (Jornada Mañana= JM, Jornada Tarde= JT, Jornada Nocturna= JN)");
                        $flag = false;
                    }
                }

                array_push($validado, "esta es otra)");



                if ($flag) {
                    $Alumno = \App\Alumnos::GuardarAlumnoImport($datos, $Alumnos);

                    $Alumno = \App\Alumnos::BuscarListAlumnos($datos["grado_import"], $datos["grupo_import"], $datos["jornada_import"]);

                    if (request()->ajax()) {
                        return response()->json([
                            'Mensaje' => 'OK',
                            'Alumnos' => $Alumno,
                        ]);
                    }
                } else {
                    if (request()->ajax()) {
                        return response()->json([
                            'Mensaje' => 'validado',
                            'validado' => $validado,
                        ]);
                    }
                }
            }
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function sanear_string($string)
    {

        $string = trim($string);

        $string = str_replace(
            array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
            array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
            $string
        );

        $string = str_replace(
            array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
            array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
            $string
        );

        $string = str_replace(
            array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
            array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
            $string
        );

        $string = str_replace(
            array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
            array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
            $string
        );

        $string = str_replace(
            array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
            array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
            $string
        );

        $string = str_replace(
            array('ñ', 'Ñ', 'ç', 'Ç'),
            array('n', 'N', 'c', 'C'),
            $string
        );

        //Esta parte se encarga de eliminar cualquier caracter extraño
        $string = str_replace(
            array(
                "¨", "º", "-", "~", "", "@", "|", "!",
                "·", "$", "%", "&", "/",
                "(", ")", "?", "'", " h¡",
                "¿", "[", "^", "<code>", "]",
                "+", "}", "{", "¨", "´",
                ">", "< ", ";", ",", ":",
                " "
            ),
            '',
            $string
        );

        return $string;
    }
}
