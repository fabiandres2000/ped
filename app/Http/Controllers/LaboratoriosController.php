<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LaboratoriosController extends Controller
{

    public function GestionLaboratorios()
    {
        $bandera = "Menu4";
        if (Auth::check()) {
            $busqueda = request()->get('txtbusqueda');
            $nombre = request()->get('nombre');

            $actual = request()->get('page');
            if ($actual == null || $actual == "") {
                $actual = 1;
            }
            $limit = 10;
            $Laboratorios = \App\Laboratorios::Gestion($busqueda, $actual, $limit, $nombre);

            $Asignaturas = \App\Laboratorios::listar();

            $select_Asig = "<option value='' selected>TODAS LAS ASIGNATURAS</option>";
            foreach ($Asignaturas as $Asig) {
                if ($Asig->id == $nombre) {
                    $select_Asig .= "<option value='$Asig->id' selected> " . strtoupper($Asig->nombre) . " - Grado " . $Asig->grado_modulo . "°</option>";
                } else {
                    $select_Asig .= "<option value='$Asig->id' >" . strtoupper($Asig->nombre) . " - Grado " . $Asig->grado_modulo . "°</option>";
                }
            }

            $numero_filas = \App\Laboratorios::numero_de_registros(request()->get('txtbusqueda'), $nombre);
            $paginas = ceil($numero_filas / $limit); //$numero_filas/10;

            return view('Laboratorios.GestionLaboratorios', compact('bandera', 'numero_filas', 'paginas', 'actual', 'limit', 'busqueda', 'nombre', 'Laboratorios', 'nombre', 'select_Asig'));
        } else {
            return redirect("/")->with("error", "Su sesion ha terminado");
        }
    }

    public function NuevoLaboratorio()
    {
        $bandera = "Menu4";
        if (Auth::check()) {
            $Laboratorio = new \App\Laboratorios();
            $Asigna = \App\Modulos::listar();
            return view('Laboratorios.NuevoLaboratorio', compact('bandera', 'Laboratorio', 'Asigna'));
        } else {
            return redirect("/")->with("error", "Su sesion ha terminado");
        }
    }

    public function GuardarLaboratorio()
    {
        $datos = request()->all();

        if (Auth::check()) {
            $this->validate(request(), [
                'modulo' => 'required',
                'periodo' => 'required',
                'unidad' => 'required',
                'titulo' => 'required',
                'objetivo' => 'required',
            ], [
                'modulo.required' => 'Debe Seleccionar la Asignatura',
                'periodo.required' => 'Debe Seleccionar el Periodo',
                'unidad.required' => 'Seleccione la Unidad',
                'titulo.required' => 'Ingrese el Título del Laboratorio',
                'objetivo.required' => 'Ingrese el Objetivo General del Laboratorio',
            ]);

            $Proc = \App\Laboratorios::Guardar($datos);
            if ($Proc) {
                $datos['labo_id'] = $Proc->id;

                if (request()->hasfile('VideoProceso')) {
                    foreach (request()->file('VideoProceso') as $file) {
                        $prefijo = substr(md5(uniqid(rand())), 0, 6);
                        $name = self::sanear_string($prefijo . '_' . $file->getClientOriginalName());
                        $file->move(public_path() . '/app-assets/Contenido_Laboratorio/', $name);
                        $archOr[] = $file->getClientOriginalName();
                        $arch[] = $name;
                    }
                    $datos['VideoProcOr'] = $archOr;
                    $datos['VideoProc'] = $arch;
                }

                $ContProc = \App\ProcedLaboratorios::Guardar($datos);

                if ($Proc) {
                    return redirect('Laboratorios/GestionLaboratorios')->with('success', 'Datos Guardados');
                } else {
                    return redirect('Laboratorios/GestionLaboratorios')->with('error', 'Datos no Guardados');
                }
            } else {
                return redirect('Laboratorios/GestionLaboratorios')->with('error', 'Datos no Guardados');
            }
            $Log = \App\Log::Guardar('Laboratorio Guardado', $Proc->id);
        } else {
            return redirect("/")->with("error", "Su sesion ha terminado");
        }
    }

    public function EditarLaboratorio($id)
    {
        $bandera = "Menu4";
        $opc = "editar";
        if (Auth::check()) {

            $Laboratorio = \App\Laboratorios::BuscarLab($id);

            $Asigna = \App\Modulos::listar();

            return view('Laboratorios.EditarLabo', compact('bandera', 'Laboratorio', 'Asigna', 'opc'));
        } else {
            return redirect("/")->with("error", "Su sesion ha terminado");
        }
    }

    public function InfLaboratorio()
    {
        $IdLabo = request()->get('IdLabo');
        if (Auth::check()) {
            $DesLabo = \App\Laboratorios::BuscarLab($IdLabo);
            $ProcLabo = \App\ProcedLaboratorios::BuscarProc($IdLabo);

            if (request()->ajax()) {
                return response()->json([
                    'DesLabo' => $DesLabo,
                    'ProcLabo' => $ProcLabo,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su sesion ha terminado");
        }
    }

    public function ListLaboratorios($id)
    {
        if (Auth::check()) {
            $DesLabo = \App\Laboratorios::ListLab($id);
            return view('Laboratorios.ListaLaboratorios', compact('DesLabo'));

        } else {
            return redirect("/")->with("error", "Su sesion ha terminado");
        }
    }

    public function ModificarLaboratorio($id)
    {
        $datos = request()->all();
        if (Auth::check()) {
            $this->validate(request(), [
                'modulo' => 'required',
                'periodo' => 'required',
                'unidad' => 'required',
                'titulo' => 'required',
                'objetivo' => 'required',
            ], [
                'modulo.required' => 'Debe Seleccionar la Asignatura',
                'periodo.required' => 'Debe Seleccionar el Periodo',
                'unidad.required' => 'Seleccione la Unidad',
                'titulo.required' => 'Ingrese el Título del Laboratorio',
                'objetivo.required' => 'Ingrese el Objetivo General del Laboratorio',
            ]);

            $datos['labo_id'] = $id;

            $Proc = \App\Laboratorios::Modificar($datos, $id);

            if ($Proc) {
                if (request()->hasfile('VideoProceso')) {
                    foreach (request()->file('VideoProceso') as $file) {
                        $prefijo = substr(md5(uniqid(rand())), 0, 6);
                        $name = self::sanear_string($prefijo . '_' . $file->getClientOriginalName());
                        $file->move(public_path() . '/app-assets/Contenido_Laboratorio/', $name);
                        $archOr[] = $file->getClientOriginalName();
                        $arch[] = $name;
                    }
                    $datos['VideoProcOr'] = $archOr;
                    $datos['VideoProc'] = $arch;
                }

                $ContProc = \App\ProcedLaboratorios::Modificar($datos);

                if ($Proc) {
                    return redirect('Laboratorios/GestionLaboratorios')->with('success', 'Datos Guardados');
                } else {
                    return redirect('Laboratorios/GestionLaboratorios')->with('error', 'Datos no Guardados');
                }
            } else {
                return redirect('Laboratorios/GestionLaboratorios')->with('error', 'Datos no Guardados');
            }
            $Log = \App\Log::Guardar('Laboratorio Guardado', $id);
        } else {
            return redirect("/")->with("error", "Su sesion ha terminado");
        }
    }

    public function MostLaboratorios()
    {
        if (Auth::check()) {
            $idunidad = request()->get('id');
            $TitUnidad = \App\Unidades::TitUnidades($idunidad);
            $Laboratorios = \App\Laboratorios::ListLaboTemas($idunidad);
            $Sesiones = \App\sesiones::Guardar(Auth::user()->id);
            $PanelActivos = self::PanelActivos();
            $PanelNotiEval = self::PanelNotiEval();

            if (request()->ajax()) {
                return response()->json([

                    'TitUnidad' => $TitUnidad,
                    'Laboratorios' => $Laboratorios,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su sesion ha terminado");
        }
    }

    public function MostDetLaboratorios()
    {
        if (Auth::check()) {
            $idLab = request()->get('idLabo');
            $Laboratorios = \App\Laboratorios::BuscarLab($idLab);

            $ProcLabo = \App\ProcedLaboratorios::BuscarProc($idLab);
            $EvalLabo = \App\Evaluacion::ListEval($idLab, 'L');
            $Sesiones = \App\sesiones::Guardar(Auth::user()->id);
            $PanelActivos = self::PanelActivos();
            $PanelNotiEval = self::PanelNotiEval();

            if (request()->ajax()) {
                return response()->json([
                    'Laboratorios' => $Laboratorios,
                    'ProcLabo' => $ProcLabo,
                    'EvalLabo' => $EvalLabo,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su sesion ha terminado");
        }
    }

    public function EliminarLabo()
    {
        $mensaje = "";
        $id = request()->get('id');
        if (Auth::check()) {
            $Labo = \App\Laboratorios::BuscarLab($id);
            $estado = "ACTIVO";
            if ($Labo->estado == "ACTIVO") {
                $estado = "ELIMINADO";
            } else {
                $estado = "ACTIVO";
            }
            $respuesta = \App\Laboratorios::editarestado($id, $estado);

            if ($respuesta) {
                if ($estado == "ELIMINADO") {
                    $Log = \App\Log::Guardar('Laboratorio Eliminado', $id);
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
            return redirect("/")->with("error", "Su sesion ha terminado");
        }
    }

    public function GestionAsigEvaluacion($id)
    {
        $bandera = "Menu4";
        if (Auth::check()) {
            $Evaluaciones = \App\Evaluacion::ListEval($id, 'L');

            return view('Laboratorios.GestionEvaluaciones', compact('bandera', 'Evaluaciones', 'id'));
        } else {
            return redirect("/")->with("error", "Su sesion ha terminado");
        }
    }

    public function AsigEvaluacion($id)
    {
        $bandera = "Menu4";
        $trEval = '';

        if (Auth::check()) {
            $Tema = \App\Laboratorios::BuscarLab($id);

            $Eval = new \App\EvaluacionLabo();
            $Asigna = \App\Modulos::listar();
            return view('Laboratorios.AsigEvaluacion', compact('bandera', 'Tema', 'Asigna', 'Eval'));
        } else {
            return redirect("/")->with("error", "Su sesion ha terminado");
        }
    }

    ///////////////////GUARDAR EVALUACIÓN
    public function guardarEvaluacion()
    {
        if (Auth::check()) {
            $datos = request()->all();

            $idEval = "";
            $calxdoc = "NO";

            if ($datos['Id_Eval'] === null) {
                $ContEval = \App\Evaluacion::Guardar($datos, $calxdoc);
                $idEval = $ContEval->id;

            } else {
                $idEval = request()->get('Id_Eval');
                $ContEval = \App\Evaluacion::ModifEval($datos, $idEval, $calxdoc);
            }

            if ($datos['Tipreguntas'] === "PREGENSAY") {
                if ($datos['id-pregensay'] === null) {
                    $ContPregEnsayo = \App\EvalPregEnsay::Guardar($datos, $idEval);
                    $ConsEval = \App\CosEval::Guardar($datos, $idEval, $ContPregEnsayo->id);
                } else {
                    $ContPregEnsayo = \App\EvalPregEnsay::ModifPreg($datos);
                    $ContPregEnsayo = \App\EvalPregEnsay::consulPregEnsay($datos['id-pregensay']);
                }

                $ConsEval = \App\CosEval::GrupPreg($idEval);
                foreach ($ConsEval as $Preg) {
                    if ($Preg->tipo == "PREGENSAY" || $Preg->tipo == "COMPLETE" || $Preg->tipo == "TALLER") {
                        $calxdoc = "SI";
                    }
                }
                $ModCalxdoc = \App\Evaluacion::ModifEvalCalxDoc($idEval, $calxdoc);
                $Log = \App\Log::Guardar('Evaluación Modificada', $idEval);

                if (request()->ajax()) {
                    return response()->json([
                        'idEval' => $idEval,
                        'ContPregEnsayo' => $ContPregEnsayo,
                    ]);
                }
            } else if ($datos['Tipreguntas'] === "COMPLETE") {
                $datos['cb_Opciones'] = implode(',', $datos['cb_Opciones']);
                if ($datos['id-pregcomplete'] === null) {
                    $ContPreComplete = \App\EvalPregComplete::Guardar($datos, $idEval);
                    $ConsEval = \App\CosEval::Guardar($datos, $idEval, $ContPreComplete->id);

                } else {
                    $ContPreComplete = \App\EvalPregComplete::Modificar($datos);
                    $ContPreComplete = \App\EvalPregComplete::ConsultComplete($datos['id-pregcomplete']);
                }

                $ConsEval = \App\CosEval::GrupPreg($idEval);
                foreach ($ConsEval as $Preg) {
                    if ($Preg->tipo == "PREGENSAY" || $Preg->tipo == "COMPLETE" || $Preg->tipo == "TALLER") {
                        $calxdoc = "SI";
                    }
                }

                $ModCalxdoc = \App\Evaluacion::ModifEvalCalxDoc($idEval, $calxdoc);
                $Log = \App\Log::Guardar('Evaluación Modificada', $idEval);

                if (request()->ajax()) {
                    return response()->json([
                        'idEval' => $idEval,
                        'ContPreComplete' => $ContPreComplete,
                    ]);
                }
            } else if ($datos['Tipreguntas'] === "OPCMULT") {
                if ($datos['IdpreguntaMul'] === null) {
                    $PregOpcMul = \App\PregOpcMul::Guardar($datos['PreMulResp'], $datos['puntaje'], $idEval);
                    if ($PregOpcMul) {
                        $ConsEval = \App\CosEval::Guardar($datos, $idEval, $PregOpcMul->id);
                        $OpciPregMul = \App\OpcPregMul::Guardar($datos, $PregOpcMul->id, $idEval);
                        $OpciPregMul = \App\OpcPregMul::ConsulGrupOpc($PregOpcMul->id, $idEval);
                    }
                } else {
                    $PregOpcMul = \App\PregOpcMul::ModiPreMul($datos['PreMulResp'], $datos['puntaje'], $datos['IdpreguntaMul'], $idEval);
                    $PregOpcMul = \App\PregOpcMul::ConsulPreg($datos['IdpreguntaMul']);
                    if ($PregOpcMul) {
                        $OpciPregMul = \App\OpcPregMul::ModOpcPreg($datos, $idEval);
                        $OpciPregMul = \App\OpcPregMul::ConsulGrupOpc($datos['IdpreguntaMul'], $idEval);
                    }

                }
                $ConsEval = \App\CosEval::GrupPreg($idEval);

                foreach ($ConsEval as $Preg) {
                    if ($Preg->tipo == "PREGENSAY" || $Preg->tipo == "COMPLETE" || $Preg->tipo == "TALLER") {
                        $calxdoc = "SI";
                    }
                }

                $ModCalxdoc = \App\Evaluacion::ModifEvalCalxDoc($idEval, $calxdoc);
                $Log = \App\Log::Guardar('Evaluación Modificada', $idEval);

                if (request()->ajax()) {
                    return response()->json([
                        'idEval' => $idEval,
                        'PregOpcMul' => $PregOpcMul,
                        'OpciPregMul' => $OpciPregMul,
                    ]);
                }
            } else if ($datos['Tipreguntas'] === "VERFAL") {
                if ($datos['id-pregverfal'] === null) {
                    $ContPregVerFal = \App\EvalVerFal::Guardar($datos, $idEval);
                    $ConsEval = \App\CosEval::Guardar($datos, $idEval, $ContPregVerFal->id);

                } else {
                    $ContPregVerFal = \App\EvalVerFal::ModifPreg($datos);
                    $ContPregVerFal = \App\EvalVerFal::ConVerFal($datos['id-pregverfal']);
                }

                $ConsEval = \App\CosEval::GrupPreg($idEval);
                foreach ($ConsEval as $Preg) {
                    if ($Preg->tipo == "PREGENSAY" || $Preg->tipo == "COMPLETE" || $Preg->tipo == "TALLER") {
                        $calxdoc = "SI";
                    }
                }

                $ModCalxdoc = \App\Evaluacion::ModifEvalCalxDoc($idEval, $calxdoc);
                $Log = \App\Log::Guardar('Evaluación Modificada', $idEval);

                if (request()->ajax()) {
                    return response()->json([
                        'idEval' => $idEval,
                        'ContPregVerFal' => $ContPregVerFal,
                    ]);
                }
            } else if ($datos['Tipreguntas'] === "RELACIONE") {
                if ($datos['id-relacione'] === null) {
                    $PregRel = \App\PregRelacione::Guardar($datos, $idEval);
                    $ConsEval = \App\CosEval::Guardar($datos, $idEval, $PregRel->id);

                    $ContIndicaciones = \App\EvalRelacione::Guardar($datos, $PregRel->id, $idEval);
                    $ContPregRespuest = \App\EvalRelacioneOpc::Guardar($datos, $PregRel->id, $idEval);
                    $PregRelIndi = \App\EvalRelacione::PregRelDef($PregRel->id);
                    $PregRelResp = \App\EvalRelacioneOpc::PregRelOpc($PregRel->id);
                } else {

                    $PregRel = \App\PregRelacione::Modificar($datos);
                    $ContIndicaciones = \App\EvalRelacione::Modificar($datos, $datos['id-relacione'], $idEval);
                    $ContPregRespuest = \App\EvalRelacioneOpc::Modificar($datos, $datos['id-relacione'], $idEval);

                    $PregRel = \App\PregRelacione::ConRela($datos['id-relacione']);

                    $PregRelIndi = \App\EvalRelacione::PregRelDef($datos['id-relacione']);
                    $PregRelResp = \App\EvalRelacioneOpc::PregRelOpc($datos['id-relacione']);

                }

                $ConsEval = \App\CosEval::GrupPreg($idEval);
                foreach ($ConsEval as $Preg) {
                    if ($Preg->tipo == "PREGENSAY" || $Preg->tipo == "COMPLETE" || $Preg->tipo == "TALLER") {
                        $calxdoc = "SI";
                    }
                }

                $ModCalxdoc = \App\Evaluacion::ModifEvalCalxDoc($idEval, $calxdoc);
                $Log = \App\Log::Guardar('Evaluación Modificada', $idEval);

                if (request()->ajax()) {
                    return response()->json([
                        'idEval' => $idEval,
                        'PregRel' => $PregRel,
                        'PregRelIndi' => $PregRelIndi,
                        'PregRelResp' => $PregRelResp,
                    ]);
                }
            } else if ($datos['Tipreguntas'] === "TALLER") {
                if (request()->hasfile('archiTaller')) {
                    $prefijo = substr(md5(uniqid(rand())), 0, 6);
                    $name = self::sanear_string($prefijo . '_' . request()->file('archiTaller')->getClientOriginalName());
                    request()->file('archiTaller')->move(public_path() . '/app-assets/Archivos_EvaluacionTaller/', $name);
                }
                $datos['archivo'] = $name;
                if ($datos['id-taller'] === null) {
                    $ContPregTaller = \App\EvalTaller::GuardarTallerArc($datos, $idEval);
                    $ConsEval = \App\CosEval::Guardar($datos, $idEval, $ContPregTaller->id);
                } else {
                    $ContPregEnsayo = \App\EvalTaller::ModifPreg($datos);
                    $ContPregTaller = \App\EvalTaller::PregTaller($datos['id-taller']);
                }

                $ConsEval = \App\CosEval::GrupPreg($idEval);
                foreach ($ConsEval as $Preg) {
                    if ($Preg->tipo == "PREGENSAY" || $Preg->tipo == "COMPLETE" || $Preg->tipo == "TALLER") {
                        $calxdoc = "SI";
                    }
                }

                $ModCalxdoc = \App\Evaluacion::ModifEvalCalxDoc($idEval, $calxdoc);
                $Log = \App\Log::Guardar('Evaluación Modificada', $idEval);

                if (request()->ajax()) {
                    return response()->json([
                        'idEval' => $idEval,
                        'ContPregTaller' => $ContPregTaller,
                    ]);
                }
            }

        } else {
            return redirect("/")->with("error", "Su sesion ha terminado");
        }
    }

    public function EditarEval($id)
    {
        $bandera = "Menu4";
        $opc = "editar";
        if (Auth::check()) {
            $Eval = \App\Evaluacion::BusEval($id);
            $Tema = \App\Laboratorios::BuscarLab($Eval->contenido);
            $Asigna = \App\Modulos::listar();
            return view('Laboratorios.EditarEval', compact('bandera', 'Tema', 'Asigna', 'opc', 'Eval'));
        } else {
            return redirect("/")->with("error", "Su sesion ha terminado");
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
                                                <img src="' . asset('app-assets/images/Img_Estudiantes/' . $Alu->foto) . '" alt="avatar"><i></i></span>
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
            $AlumEval = \App\LibroCalificaciones::BuscEvalPend($Alu->usuario);
//            dd($AlumEval);die();
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

    public function EliminarEval()
    {
        $mensaje = "";
        $icon = "";
        $id = request()->get('id');
        if (Auth::check()) {
            $Eval = \App\Evaluacion::BusEval($id);
            $Elib = \App\LibroCalificaciones::BusEval($id, 'L');
            if ($Elib) {
                $estado = "ACTIVO";
                $mensaje = 'La Evaluación no puede ser Eliminada, ya que ha sido resuelta por algun  Estudiante';
                $icon = 'warning';
            } else {
                $estado = "ACTIVO";
                if ($Eval->estado == "ACTIVO") {
                    $estado = "ELIMINADO";
                } else {
                    $estado = "ACTIVO";
                }
                $respuesta = \App\Evaluacion::editarestado($id, $estado);
                if ($respuesta) {
                    if ($estado == "ELIMINADO") {
                        $Log = \App\Log::Guardar('Evaluación Eliminada', $id);
                        $mensaje = 'Operación Realizada de Manera Exitosa';
                        $icon = 'success';
                    }
                } else {
                    $mensaje = 'La Operación no pudo ser Realizada';
                }
            }

            if (request()->ajax()) {
                return response()->json([
                    'estado' => $estado,
                    'mensaje' => $mensaje,
                    'id' => $id,
                    'icon' => $icon,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su sesion ha terminado");
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
                " ",
            ),
            '',
            $string
        );

        return $string;
    }
}
