<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ProfesoresController extends Controller
{

    public function GestionProfesores()
    {
        $bandera = "Menu4";
        if (Auth::check()) {

            $busqueda = request()->get('txtbusqueda');
            $actual = request()->get('page');
            if ($actual == null || $actual == "") {
                $actual = 1;
            }
            $limit = 5;
            $Profesores = \App\Profesores::Gestion($busqueda, $actual, $limit);
//            dd($Profesores);die();
            $numero_filas = \App\Profesores::numero_de_registros(request()->get('txtbusqueda'));
            $paginas = ceil($numero_filas / $limit); //$numero_filas/10;

            $Docentes = \App\Profesores::Listar();

            $select_docente = "<option value=''>Todos los Docentes</option>";

            foreach ($Docentes as $doce) {
                $select_docente .= "<option value='$doce->usuario_profesor'> " . strtoupper($doce->nombre . " " . $doce->apellido) . "</option>";
            }

            return view('Profesores.Gestion', compact('bandera', 'numero_filas', 'paginas', 'actual', 'limit', 'busqueda', 'Profesores', 'select_docente'));
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function GestionNuevo()
    {
        $bandera = "Menu4";
        $opc = "nuevo";
        if (Auth::check()) {
            $Profesores = new \App\Profesores();
            return view('Profesores.Nuevo', compact('bandera', 'Profesores', 'opc'));
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function AddAsig($id)
    {
        $bandera = "Menu4";
        if (Auth::check()) {
            $trAsig = '';
            $AsigProf = \App\AsigProf::listar($id);

            $DatProf = \App\Profesores::Buscar($id);

            $i = 1;
            foreach ($AsigProf as $AP) {
                $trAsig .= '<tr id="Fila_Asig' . $i . '">
                    <td class="text-truncate">' . $i . '</td>
                    <td class="text-truncate">' . $AP->nombre . ' Grado ' . $AP->grado_modulo . '° ' . $AP->descripcion
                . '</td><input type="hidden" id="Asig' . $i . '" name="txtasig[]"  value="' . $AP->asignatura . '">'
                . '</td><input type="hidden" id="grado' . $i . '" name="txtgrado[]"  value="' . $AP->grado . '">'
                . '</td><input type="hidden" id="grupo' . $i . '" class="grupo" name="txtgrupo[]"  value="' . $AP->grupo . '">
                    <td class="text-truncate">
                        <a onclick="$.DelAsig(' . $i . ')"  title="Eliminar" class="btn  btn-outline-warning btn-sm"><i class="fa fa-trash"></i></a>
                    </td>
                </tr>';
                $i++;
            }

            $Asigna = \App\Asignaturas::listar();
            $select_Asignaturas = "<option value='' selected>Seleccione</option>";
            foreach ($Asigna as $Asig) {
                $select_Asignaturas .= "<option value='$Asig->id' >" . strtoupper($Asig->nombre) . "</option>";
            }
            return view('Profesores.AddAsignatura', compact('bandera', 'select_Asignaturas', 'trAsig', 'i', 'id', 'DatProf'));
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function ListAsitencia()
    {
        $fecha = request()->get('textfecha');
        $InfAsist = array(); //creamos un array
        if (Auth::check()) {

            $DiasMes = date('t', strtotime($fecha));
            $alumnos = \App\Profesores::alumnos();

            foreach ($alumnos as $key => $Alum) {
                for ($i = 1; $i <= $DiasMes; $i++) {
                    $asistencia = \App\Asistencia::listarAsistenciasMes($Alum->IDALUMNO, $Alum->IDMODULO, $fecha, $i);
                    if ($asistencia) {

                        $InfAsist[] = array(
                            "Alumno" => $Alum->nombre_alumno . ' ' . $Alum->apellido_alumno,
                            "ValorAsis" => $asistencia->valor,
                            "dia" => "Día " . $i,
                        );
                    } else {
                        $InfAsist[] = array(
                            "Alumno" => $Alum->nombre_alumno . ' ' . $Alum->apellido_alumno,
                            "ValorAsis" => '*',
                            "dia" => "Día " . $i,
                        );
                    }

                }

            }

            $tablaAsit = '';
            $cons = 1;
            $ValAsit = "";

            for ($i = 0; $i < count($InfAsist); $i++) {

                if (!empty($InfAsist[$i - 1]['Alumno'])) {

                    if ($InfAsist[$i - 1]['Alumno'] == $InfAsist[$i]['Alumno']) {
                        if ($InfAsist[$i]["ValorAsis"] == "100%") {
                            $ValAsit = 'Presente';
                        } elseif ($InfAsist[$i]["ValorAsis"] == "0%") {
                            $ValAsit = 'No Asistio';
                        } elseif ($InfAsist[$i]["ValorAsis"] == "50%") {
                            $ValAsit = 'Llego Tarde';
                        } elseif ($InfAsist[$i]["ValorAsis"] == "--") {
                            $ValAsit = 'Excusa';
                        } elseif ($InfAsist[$i]["ValorAsis"] == "*") {
                            $ValAsit = '';
                        }

                        $tablaAsit .= '<td>' . $ValAsit . '</td>';
                    } else {
                        $tablaAsit .= '</tr><tr>
                        <td>' . $cons . '</td>
                        <td>' . $InfAsist[$i]["Alumno"] . '</td>';
                        if ($InfAsist[$i]["ValorAsis"] == "100%") {
                            $ValAsit = 'Presente';
                        } elseif ($InfAsist[$i]["ValorAsis"] == "0%") {
                            $ValAsit = 'No Asistio';
                        } elseif ($InfAsist[$i]["ValorAsis"] == "50%") {
                            $ValAsit = 'Llego Tarde';
                        } elseif ($InfAsist[$i]["ValorAsis"] == "--") {
                            $ValAsit = 'Excusa';
                        } elseif ($InfAsist[$i]["ValorAsis"] == "*") {
                            $ValAsit = '';

                        }

                        $tablaAsit .= '<td>' . $ValAsit . '</td>';
                        $cons++;

                    }
                    if (empty($InfAsist[$i + 1]['Alumno'])) {
                        $tablaAsit .= '</tr>';
                    }

                } else {
                    $tablaAsit .= '<tr>
                <td>' . $cons . '</td>
                <td>' . $InfAsist[$i]["Alumno"] . '</td>';

                    if ($InfAsist[$i]["ValorAsis"] == "100%") {
                        $ValAsit = 'Presente';
                    } elseif ($InfAsist[$i]["ValorAsis"] == "0%") {
                        $ValAsit = 'No Asistio';
                    } elseif ($InfAsist[$i]["ValorAsis"] == "50%") {
                        $ValAsit = 'Llego Tarde';
                    } elseif ($InfAsist[$i]["ValorAsis"] == "--") {
                        $ValAsit = 'Excusa';
                    } elseif ($InfAsist[$i]["ValorAsis"] == "*") {
                        $ValAsit = '';

                    }

                    $tablaAsit .= '<td>' . $ValAsit . '</td>';

                    $cons++;
                }

            }

            if (request()->ajax()) {
                return response()->json([
                    'ListAsistencia' => $InfAsist,
                    'tablaAsit' => $tablaAsit,
                    'DiasMes' => $DiasMes,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su sesion ha terminado");
        }
    }

    public function AddMod($id)
    {
        $bandera = "Menu4";
        if (Auth::check()) {
            $trAsig = '';
            $AsigProf = \App\ModProf::listar($id);
            $DatProf = \App\Profesores::Buscar($id);

            $i = 1;
            foreach ($AsigProf as $AP) {
                $trAsig .= '<tr id="Fila_Asig' . $i . '">
                    <td class="text-truncate">' . $i . '</td>
                    <td class="text-truncate">' . $AP->nombre . ' Grado ' . $AP->grado_modulo . '° ' . $AP->descripcion
                . '</td><input type="hidden" id="Asig' . $i . '" name="txtasig[]"  value="' . $AP->asignatura . '">'
                . '</td><input type="hidden" id="grado' . $i . '" name="txtgrado[]"  value="' . $AP->grado . '">'
                . '</td><input type="hidden" id="grupo' . $i . '" class="grupo" name="txtgrupo[]"  value="' . $AP->grupo . '">
                    <td class="text-truncate">
                        <a onclick="$.DelAsig(' . $i . ')" data-toggle="tooltip" title="Eliminar" class="btn btn-icon btn-outline-warning btn-social-icon btn-sm"><i class="fa fa-trash"></i></a>
                    </td>
                </tr>';
                $i++;
            }

            $Asigna = \App\ModulosTransversales::listar();
            $select_Asignaturas = "<option value='' selected>Seleccione</option>";
            foreach ($Asigna as $Asig) {
                $select_Asignaturas .= "<option value='$Asig->id' >" . strtoupper($Asig->nombre) . "</option>";
            }
            return view('Profesores.AddModulos', compact('bandera', 'select_Asignaturas', 'trAsig', 'i', 'id', 'DatProf'));
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function ListarGrados()
    {
        $idAsig = request()->get('idAsig');
        if (Auth::check()) {
            $Grados = \App\Modulos::ListarxAsig($idAsig);
            $Select_Grados = "<option value='' selected>Seleccione</option>";
            foreach ($Grados as $Grad) {
                $Select_Grados .= "<option value='$Grad->id' >Grado " . strtoupper($Grad->grado_modulo) . "°</option>";
            }
            if (request()->ajax()) {
                return response()->json([
                    'Select_Grados' => $Select_Grados,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function CargaAcademica()
    {
        $docente = request()->get('doce');
        if (Auth::check()) {

            if ($docente == "") {
                $Docentes = \App\Profesores::Listar();
                $tablaAsit = "";
                $jornada = "";
                if ($Docentes) {
                    foreach ($Docentes as $doce) {

                        if ($doce->jornada == "JM") {
                            $jornada = "Jornada Mañana";
                        } else if ($doce->jornada == "") {
                            $jornada = "Jornada Tarde";
                        } else {
                            $jornada = "Jornada Nocturna";
                        }
                        $tablaAsit .= '<div class="bs-callout-blue callout-border-right callout-bordered callout-transparent p-1 mb-1">'
                        . '<h4 style="color:#000000; weight: bold;" class="card-title">' . $doce->nombre . ' ' . $doce->apellido . ' - ' . $jornada . '</h4>';
                        $tablaAsit .= '  <div class="modal-body">';

                        $CarAsig = \App\AsigProf::listar($doce->usuario_profesor);

                        $tablaAsit .= '<h5 ><i class="fa fa-arrow-right"></i> Asignaturas Asignadas</h5>'
                            . '<ul class="pl-2">';

                        if ($CarAsig->count() > 0) {
                            foreach ($CarAsig as $Asi) {
                                $tablaAsit .= ' <li>' . $Asi->nombre . ' Grado ' . $Asi->grado_modulo . '° ' . $Asi->descripcion . '</li>';
                            }
                        } else {
                            $tablaAsit .= ' <li>--NO TIENE ASIGNATURAS ASIGNADAS--</li>';

                        }

                        $tablaAsit .= '</ul>';

                        $tablaAsit .= '<hr><h5><i class="fa fa-arrow-right"></i> Módulos Asignados</h5>'
                            . '<ul class="pl-2">';

                        $CarMod = \App\ModProf::listar($doce->usuario_profesor);
                        if ($CarMod->count() > 0) {
                            foreach ($CarMod as $Mod) {
                                $tablaAsit .= ' <li>' . $Mod->nombre . ' Grado ' . $Mod->grado_modulo . '° ' . $Mod->descripcion . '</li>';
                            }
                        } else {
                            $tablaAsit .= ' <li>--NO TIENE MÓDULOS ASIGNADOS--</li>';
                        }

                        $tablaAsit .= '</ul>';

                        $tablaAsit .= '</div></div>';
                    }

                }
            } else {

                $Docentes = \App\Profesores::BuscarProfFoto($docente);

                $tablaAsit = "";
                $jornada = "";
                if ($Docentes) {
                    if ($Docentes->jornada == "JM") {
                        $jornada = "Jornada Mañana";
                    } else if ($Docentes->jornada == "") {
                        $jornada = "Jornada Tarde";
                    } else {
                        $jornada = "Jornada Nocturna";
                    }
                    $tablaAsit .= '<div class="bs-callout-blue callout-border-right callout-bordered callout-transparent p-1 mb-1">'
                    . '<h4 style="color:#000000; weight: bold;" class="card-title">' . $Docentes->nombre . ' ' . $Docentes->apellido . ' - ' . $jornada . '</h4>';
                    $tablaAsit .= '  <div class="modal-body">';

                    $CarAsig = \App\AsigProf::listar($Docentes->usuario_profesor);

                    $tablaAsit .= '<h5 ><i class="fa fa-arrow-right"></i> Asignaturas Asignadas</h5>'
                        . '<ul class="pl-2">';

                    if ($CarAsig->count() > 0) {
                        foreach ($CarAsig as $Asi) {
                            $tablaAsit .= ' <li>' . $Asi->nombre . ' Grado ' . $Asi->grado_modulo . '° ' . $Asi->descripcion . '</li>';
                        }
                    } else {
                        $tablaAsit .= ' <li>--NO TIENE ASIGNATURAS ASIGNADAS--</li>';

                    }

                    $tablaAsit .= '</ul>';

                    if (Session::get('PerModu') == 'si') {

                        $tablaAsit .= '<hr><h5><i class="fa fa-arrow-right"></i> Módulos Asignados</h5>'
                            . '<ul class="pl-2">';

                        $CarMod = \App\ModProf::listar($Docentes->usuario_profesor);
                        if ($CarMod->count() > 0) {
                            foreach ($CarMod as $Mod) {
                                $tablaAsit .= ' <li>' . $Mod->nombre . ' Grado ' . $Mod->grado_modulo . '° ' . $Mod->descripcion . '</li>';
                            }
                        } else {
                            $tablaAsit .= ' <li>--NO TIENE MÓDULOS ASIGNADOS--</li>';
                        }

                        $tablaAsit .= '</ul>';
                    }

                    $tablaAsit .= '</div></div>';
                }

            }

            if (request()->ajax()) {
                return response()->json([
                    'tableAsig' => $tablaAsit,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function ListarGradosMod()
    {
        $idAsig = request()->get('idAsig');
        if (Auth::check()) {
            $Grados = \App\GradosModulos::ListarxAsig($idAsig);
            $Select_Grados = "<option value='' selected>Seleccione</option>";
            foreach ($Grados as $Grad) {
                $Select_Grados .= "<option value='$Grad->id' >Grado " . strtoupper($Grad->grado_modulo) . "°</option>";
            }
            if (request()->ajax()) {
                return response()->json([
                    'Select_Grados' => $Select_Grados,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function ListarGrupos()
    {
        $idGrado = request()->get('idGrado');
        if (Auth::check()) {
            $Grados = \App\Grupos::listar($idGrado);
            $Select_Grupos = "<option value='' selected>Seleccione</option>";
            foreach ($Grados as $Grad) {
                $Select_Grupos .= "<option value='$Grad->id' >" . strtoupper($Grad->descripcion) . "</option>";
            }
            if (request()->ajax()) {
                return response()->json([
                    'Select_Grupos' => $Select_Grupos,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function ListarGruposMod()
    {
        $idGrado = request()->get('idGrado');
        if (Auth::check()) {
            $Grados = \App\GruposModTransv::listar($idGrado);
            $Select_Grupos = "<option value='' selected>Seleccione</option>";
            foreach ($Grados as $Grad) {
                $Select_Grupos .= "<option value='$Grad->id' >" . strtoupper($Grad->descripcion) . "</option>";
            }
            if (request()->ajax()) {
                return response()->json([
                    'Select_Grupos' => $Select_Grupos,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function VerifAsigAsign()
    {
        $idGrado = request()->get('idGrado2');
        $idGrupo = request()->get('IdGrupo2');
        $jornada = request()->get('jorna');
      

        if ($jornada == "Jornada Mañana") {
            $jornada = "JM";
        } else if ($jornada == "Jornada Tarde") {
            $jornada = "JT";
        } else {
            $jornada = "JN";
        }
        
     
        if (Auth::check()) {
            $Resp = \App\AsigProf::BuscAsigAsing($idGrado, $idGrupo, $jornada);
            
            $docente = "";
            $exit = "no";
            if ($Resp) {
                $docente = $Resp->nombre . ' ' . $Resp->apellido . ' - ' . $Resp->Jorna;
                $exit = "si";
            } else {

            }

            if (request()->ajax()) {
                return response()->json([
                    'docente' => $docente,
                    'exit' => $exit,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function ValProfesor()
    {
        $exit = "no";
        $ident = request()->get('id');
        $Profesores = \App\Profesores::BuscarProfVal(request()->get('idProf'));
        if ($Profesores) {
            if ($Profesores->identificacion != $ident) {
                $Prof = \App\Profesores::BuscarIdProf(request()->get('id'));
                if ($Prof) {
                    $exit = "si";
                }
            }
        } else {
            $Prof = \App\Profesores::BuscarIdProf(request()->get('id'));
            if ($Prof) {
                $exit = "si";
            }

        }

        if (request()->ajax()) {
            return response()->json([
                'exit' => $exit,
            ]);
        }
    }

    public function VerifAsigMod()
    {
        $idGrado = request()->get('idGrado2');
        $idGrupo = request()->get('IdGrupo2');
        $jornada = request()->get('jorna');
        if ($jornada == "Jornada Mañana") {
            $jornada = "JM";
        } else if ($jornada == "Jornada Tarde") {
            $jornada = "JT";
        } else {
            $jornada = "JN";
        }

        if (Auth::check()) {
            $Resp = \App\ModProf::BuscAsigAsing($idGrado, $idGrupo, $jornada);
            $docente = "";
            $exit = "no";
            if ($Resp) {
                $docente = $Resp->nombre . ' ' . $Resp->apellido . ' - ' . $Resp->Jorna;
                $exit = "si";
            } else {

            }

            if (request()->ajax()) {
                return response()->json([
                    'docente' => $docente,
                    'exit' => $exit,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function ConsutarAsistencia()
    {
        $IdAlumno = request()->get('IdAlumno');
        $Txt_Fecha = request()->get('Txt_Fecha');
        $OrAs = request()->get('OrAs');
        if (Auth::check()) {
            $Asiste = \App\Asistencia::listar($IdAlumno, $Txt_Fecha, $OrAs);

            if (request()->ajax()) {
                return response()->json([
                    'Asiste' => $Asiste,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }
    public function DelASigDocente()
    {
        $id_asignacion = request()->get('id_asignacion');
        $id_pro = request()->get('id_pro');
        $profe_jor = request()->get('profe_jor');
        if (Auth::check()) {
            $Asiste = \App\AsigProf::EliminarAsignacion($id_asignacion, $id_pro, $profe_jor);

            if (request()->ajax()) {
                return response()->json([
                    'mensaje' => "Asignatura desvinculada Exitosamente",
                    'estado' => "ok",
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }


    public function DelModDocente()
    {
        $id_asignacion = request()->get('id_asignacion');
        $id_pro = request()->get('id_pro');
        $profe_jor = request()->get('profe_jor');
        if (Auth::check()) {
            $Asiste = \App\ModProf::EliminarAsignacion($id_asignacion, $id_pro, $profe_jor);

            if (request()->ajax()) {
                return response()->json([
                    'mensaje' => "Módulo desvinculado Exitosamente",
                    'estado' => "ok",
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function GuardarAsigProf()
    {
        $bandera = "Menu4";
        $data = request()->all();
//        dd($data);die();
        if (isset($data["txtasig"])) {
            $AsiProf = \App\AsigProf::Guardar($data);
            if ($AsiProf) {
                return redirect('Profesores/AddAsignatura/' . $data['profe_id'])->with('success', 'Datos Guardados');
            } else {
                return redirect('Profesores/AddAsignatura/' . $data['profe_id'])->with('error', 'Datos no Guardados');
            }
        } else {
            return redirect('Profesores/AddAsignatura/' . $data['profe_id'])->with('error', 'No hay datos para Guardar. Verifique...');
        }
    }

    public function GuardarModProf()
    {
        $bandera = "Menu4";
        $data = request()->all();
//        dd($data);die();
        if (isset($data["txtasig"])) {
            $AsiProf = \App\ModProf::Guardar($data);
            if ($AsiProf) {
                return redirect('Profesores/AddModulos/' . $data['profe_id'])->with('success', 'Datos Guardados');
            } else {
                return redirect('Profesores/AddModulos/' . $data['profe_id'])->with('error', 'Datos no Guardados');
            }
        } else {
            return redirect('Profesores/AddModulos/' . $data['profe_id'])->with('error', 'No hay datos para Guardar. Verifique...');
        }
    }

    public function Guardar()
    {
        $bandera = "Menu4";
        if (Auth::check()) {
//            dd(request()->all());die();
            $this->validate(request(), [
                'identificacion' => 'required',
                'nombre' => 'required',
                'apellido' => 'required',
                'jornada' => 'required',
                'password' => 'required|confirmed',
                'password_confirmation' => 'required',
                'usuario_profesor' => 'required|unique:profesores,usuario_profesor',
            ], [
                'identificacion.required' => 'El Número de Identificación es Obligatorio',
                'nombre.required' => 'El Nombre es Obligatorio',
                'apellido.required' => 'El Apellido es Obligatorio',
                'jornada.required' => 'Debe Seleccionar la Jornada a la que pertenece el Docente',
                'usuario_profesor.required' => 'El Usuario es Obligatorio',
                'password.required' => 'La Contraseña es Obligatoria',
                'password_confirmation.required' => 'El Campo Repetir Contraseña es Obligatorio',
                'password.confirmed' => 'Las Contraseña no Coinciden',
                'usuario_profesor.unique' => 'El Usuario ya Existe',
            ]);
            $data = request()->all();

            $img = "defaul_profesor.jpg";

            if (request()->hasfile('imagen')) {
                foreach (request()->file('imagen') as $file) {
                    $name = rand(1, 100) . '_' . $file->getClientOriginalName();
                    $file->move(public_path() . '/app-assets/images/Img_Docentes/', $name);
                    $img = $name;
                }
            }
            $data['img'] = $img;

            $usuario = \App\Usuarios::GuardarUsuProf($data);
            $data['usuario_profesor'] = $usuario->id;
            if ($usuario) {
                $Prof = \App\Profesores::Guardar($data);
                if ($Prof) {
                    $Log = \App\Log::Guardar('Docente Guardado', $Prof->id);
                    return redirect('Profesores/Gestion')->with('success', 'Datos Guardados');
                } else {
                    return redirect('Profesores/Gestion')->with('error', 'Datos no Guardados');
                }
            } else {
                return redirect('Profesores/Gestion')->with('error', 'Datos no Guardados');
            }
        }
    }

    public function editar($id)
    {
        $bandera = "Menu4";
        $opc = "editar";
        if (Auth::check()) {
            $Profesores = \App\Profesores::BuscarProf($id);

            return view('Profesores.Editar', compact('bandera', 'Profesores', 'opc'));
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function consultar($id)
    {
        $bandera = "Menu4";
        $opc = "Consulta";
        if (Auth::check()) {
            $Profesores = \App\Profesores::BuscarProf($id);

            return view('Profesores.Consultar', compact('bandera', 'Profesores', 'opc'));
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function modificar($id)
    {
        if (Auth::check()) {
            $Profesores = \App\Profesores::BuscarProf($id);
            $this->validate(request(), [
                'identificacion' => 'required|unique:profesores,identificacion,' . $Profesores->id,
                'nombre' => 'required',
                'apellido' => 'required',
                'jornada' => 'required',
            ], [
                'identificacion.required' => 'El Número de Identificación es Obligatorio',
                'identificacion.unique' => 'El Número de Identificación ya Existe',
                'nombre.required' => 'El Nombre es obligatorio',
                'apellido.required' => 'El Apellido es Obligatorio',
                'jornada.required' => 'Debe Seleccionar la Jornada a la que pertenece el Docente',
            ]);

            $data = request()->all();
            if (request()->hasfile('imagen')) {
                foreach (request()->file('imagen') as $file) {
                    $name = rand(1, 100) . '_' . $file->getClientOriginalName();
                    $file->move(public_path() . '/app-assets/images/Img_Docentes/', $name);
                    $img = $name;
                }
            } else {
                $img = $data['fotodoce'];
            }
            $data['img'] = $img;

            $respuesta = \App\Profesores::modificar($data, $id);
            if ($respuesta) {
                $Log = \App\Log::Guardar('Docente Modificado', $id);
                return redirect('/Profesores/Gestion')->with('success', 'Datos Guardados');
            } else {
                return redirect('Profesores/Gestion')->with('error', 'Datos no Guardados');
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
            $Profesores = \App\Profesores::BuscarProf($id);
            $estado = "ACTIVO";
            if ($Profesores->estado == "ACTIVO") {
                $estado = "ELIMINADO";
            } else {
                $estado = "ACTIVO";
            }

            $respuesta = \App\Profesores::editarestado($id, $estado);



            if ($respuesta) {
                if ($estado == "ELIMINADO") {
                    $RespUsu = \App\Usuarios::editarestado($Profesores->usuario_profesor);
                    $delAsigAsig = \App\AsigProf::DelAsignacion($Profesores->usuario_profesor);
                    $delAsigMod = \App\ModProf::DelAsignacion($Profesores->usuario_profesor);

                    $Log = \App\Log::Guardar('Docente Eliminado', $id);
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

    public function asistencia()
    {
        if (Auth::check()) {
            $fecha = request()->get('fecha');
            if (empty($fecha)) {
                $fecha = date('Y-m-d');
            }
            $ori = "C";
            $alumnos = \App\Profesores::alumnos();

            foreach ($alumnos as $key => $val) {
                $asistencia = \App\Asistencia::listarAsistencias($val->IDALUMNO, $val->IDMODULO, $fecha, 'C');
                if ($asistencia) {
                    $alumnos[$key]->FECHA = $asistencia->fecha;
                    $alumnos[$key]->VALOR = $asistencia->valor;
                } else {
                    $alumnos[$key]->FECHA = "NADA";
                    $alumnos[$key]->VALOR = "NADA";
                }
            }
            // dd($alumnos);die;
            $PanelActivos = self::PanelActivos();
            $Log = \App\Log::Guardar('Verifico Asistencia', '');
            return view('Profesores.Asistencia', compact('alumnos', 'fecha', 'ori'));
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function asistenciaMod()
    {

        if (Auth::check()) {
            $fecha = request()->get('fecha');
            if (empty($fecha)) {
                $fecha = date('Y-m-d');
            }
            $ori = "M";
            $alumnos = \App\Profesores::alumnosMod();
            foreach ($alumnos as $key => $val) {
                $asistencia = \App\Asistencia::listarAsistencias($val->IDALUMNO, $val->IDMODULO, $fecha, 'M');
                if ($asistencia) {
                    $alumnos[$key]->FECHA = $asistencia->fecha;
                    $alumnos[$key]->VALOR = $asistencia->valor;
                } else {
                    $alumnos[$key]->FECHA = "NADA";
                    $alumnos[$key]->VALOR = "NADA";
                }
            }
            // dd($alumnos);die;
            $PanelActivos = self::PanelActivos();
            $Log = \App\Log::Guardar('Verifico Asistencia', '');
            return view('Profesores.Asistencia', compact('alumnos', 'fecha', 'ori'));
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function PanelActivos()
    {
        $UsuAct = 0;
        $listUsu = "";
        $listUsuNoCo = "";
        $Alumnos = \App\Profesores::alumnos();
        date_default_timezone_set('America/Bogota');
        foreach ($Alumnos as $Alu) {
            $AlumnosAct = \App\sesiones::ConsActivos($Alu->usuario);
            if ($AlumnosAct) {
                $Ultima_Con = $AlumnosAct->hora;

                $HoraAct = date('Y-m-d H:i:s');
                $minutos = (strtotime($Ultima_Con) - strtotime($HoraAct)) / 60;
                $minutos = abs($minutos);
                $minutos = floor($minutos);

                if ($minutos < 15) {
                    $listUsu .= ' <a href="javascript:void(0)">
                                    <div class="media">
                                        <div class="media-left">
                                            <span class="avatar avatar-sm avatar-online rounded-circle">
                                                <img src="' . asset('app-assets/images/Img_Estudiantes/' . $Alu->foto_alumno) . '" alt="avatar"><i></i></span>
                                        </div>
                                        <div class="media-body">
                                            <h6 style="text-transform: capitalize;"  class="media-heading">' . $Alu->nombre_alumno . ' ' . $Alu->apellido_alumno . '</h6>
                                            <p class="notification-text font-small-3 text-muted">Ultima conexión: ' . date('d/m/Y h:i:s A', strtotime($Ultima_Con)) . '</p>

                                        </div>
                                    </div>
                                </a>';
                    $UsuAct++;

                } else {
                    $listUsuNoCo .= ' <a href="javascript:void(0)">
                                    <div class="media">
                                        <div class="media-left">
                                            <span class="avatar avatar-sm avatar-busy rounded-circle">
                                                <img src="' . asset('app-assets/images/Img_Estudiantes/' . $Alu->foto_alumno) . '" alt="avatar"><i></i></span>
                                        </div>
                                        <div class="media-body">
                                            <h6 style="text-transform: capitalize;" class="media-heading">' . $Alu->apellido_alumno . ' ' . $Alu->nombre_alumno . '</h6>
                                            <p class="notification-text font-small-3 text-muted">Ultima conexión: ' . $Ultima_Con . '</p>

                                        </div>
                                    </div>
                                </a>';
                }
            } else {
                $listUsuNoCo .= ' <a href="javascript:void(0)">
                                    <div class="media">
                                        <div class="media-left">
                                            <span class="avatar avatar-sm avatar-busy rounded-circle">
                                                <img src="' . asset('app-assets/images/Img_Estudiantes/' . $Alu->foto_alumno) . '" alt="avatar"><i></i></span>
                                        </div>
                                        <div class="media-body">
                                            <h6 style="text-transform: capitalize;" class="media-heading">' . $Alu->apellido_alumno . ' ' . $Alu->nombre_alumno . '</h6>
                                            <p class="notification-text font-small-3 text-muted">Ultima conexión: Nunca</p>

                                        </div>
                                    </div>
                                </a>';
            }
        }

        $PanelActivos = '<li class="dropdown dropdown-notification nav-item">
                        <a class="nav-link nav-link-label" href="#" data-toggle="dropdown"><i class="ficon ft-users"></i>
                            <span class="badge badge-pill badge-default badge-success badge-default badge-up">' . $UsuAct . '</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-media dropdown-menu-right">
                            <li class="dropdown-menu-header">
                                <h6  class="dropdown-header m-0">
                                    <span class="grey darken-2">Usuarios Activos</span>
                                    <span class="notification-tag badge badge-default badge-success float-right m-0">' . $UsuAct . ' Activos</span>
                                </h6>
                            </li>
                            <li class="scrollable-container media-list">
                            ' . $listUsu . '
                            ' . $listUsuNoCo . '
                            </li>
                        </ul>
                    </li>';
        Session::put('PanelActivos', $PanelActivos);

        return $PanelActivos;
    }

    public function PanelNotiEval()
    {
        $NumNoti = 0;
        $lisNoti = "";
        $lisEval = "";
        $listUsuNoCo = "";
        $Alumnos = \App\Profesores::alumnos();
        foreach ($Alumnos as $Alu) {
            $AlumNotif = \App\ComentTemas::ConsultarDoce($Alu->usuario, 'C');
            if ($AlumNotif) {
                $lisNoti = ' <a  href="' . url('/Notificaciones/ComentEvaluacion') . '">
                    <div class="media">
                      <div class="media-left align-self-center"><i class="ft-message-square icon-bg-circle bg-cyan"></i></div>
                      <div class="media-body">
                        <h6 class="media-heading">Comentario a Evaluación</h6>
                        <p class="notification-text font-small-3 text-muted" style="text-transform: capitalize;">' . mb_strtolower($AlumNotif->titulo) . '</p>
                      </div>
                    </div>
                  </a>';
                $NumNoti++;
            }
            $AlumEval = \App\LibroCalificaciones::BuscEvalPend($Alu->usuario, 'C');

            if ($AlumEval) {

                foreach ($AlumEval as $AEval) {
                    $lisEval .= '<a href="' . url('/Calificaciones/EvaluarAlumnos/' . $AEval->evaluacion) . '">
                    <div class="media">
                      <div class="media-left align-self-center"><i class="ft-award icon-bg-circle bg-red bg-darken-1"></i></div>
                      <div class="media-body">
                        <h6 class="media-heading red darken-1">Calificar Evaluación</h6>
                        <p class="notification-text font-small-3 text-muted" style="text-transform: capitalize;">' . mb_strtolower($AEval->titulo) . '</p>

                      </div>
                    </div>
                  </a>';

                    $NumNoti++;
                }
            }
        }

        $PanelNotifica = '<li class="dropdown dropdown-notification nav-item">
              <a class="nav-link nav-link-label" href="#" data-toggle="dropdown"><i class="ficon ft-bell"></i>
                <span class="badge badge-pill badge-default badge-danger badge-default badge-up">' . $NumNoti . '</span>
              </a>
              <ul class="dropdown-menu dropdown-menu-media dropdown-menu-right">
                <li class="dropdown-menu-header">
                  <h6 class="dropdown-header m-0">
                    <span class="grey darken-2">Notificaciones</span>
                    <span class="notification-tag badge badge-default badge-danger float-right m-0">' . $NumNoti . ' Nuevas</span>
                  </h6>
                </li>
                <li class="scrollable-container media-list">
                  ' . $lisNoti . '
                  ' . $lisEval . '
                  </li>
              </ul>
            </li>';
        Session::put('PanelNotifica', $PanelNotifica);

        return $PanelNotifica;
    }

    public function GuardarAsistencia()
    {
        if (Auth::check()) {
            $data = request()->all();

            $asistencia = \App\Asistencia::GuardarAsistencia($data);
            if ($asistencia) {
                $mensaje = "Guardado";
            } else {
                $mensaje = "No Guardado";
            }
            if (request()->ajax()) {
                return response()->json([
                    'asistencia' => $asistencia,
                    'mensaje' => $mensaje,
                ]);
            }
        }
    }

}
