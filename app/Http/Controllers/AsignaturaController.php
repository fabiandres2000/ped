<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AsignaturaController extends Controller
{

    public function GestionAreas()
    {
        $bandera = "Menu4";
        if (Auth::check()) {

            $Areas = \App\Areas::Gestion();

            return view('Asignaturas.GestionAreas', compact('bandera', 'Areas'));
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function NuevaArea()
    {
        $bandera = "Menu4";
        $opc = "nueva";
        if (Auth::check()) {
            $Area = new \App\Areas();

            return view('Asignaturas.NuevaArea', compact('bandera', 'Area', 'opc'));
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function GuardarArea()
    {
        $bandera = "Menu4";

        if (Auth::check()) {

            $this->validate(request(), [
                'nombre_area' => 'required',
            ], [
                'nombre_area.required' => 'Debe Ingresar el Nombre del Área ',
            ]);
            $data = request()->all();

            $Area = \App\Areas::Guardar($data);
            if ($Area) {
                $Log = \App\Log::Guardar('Área Guardada', $Area->id);
                return redirect('Asignaturas/GestionAreas')->with('success', 'Datos Guardados');
            } else {
                return redirect('Asignaturas/GestionAreas')->with('error', 'Datos no Guardados');
            }
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    
    public function cambiarCompDocentes() {
        $idAsig = request()->get('id2');
        if (Auth::check()) {
            $Docentes = \App\AsigProf::listaProf($idAsig);

            if (request()->ajax()) {
                return response()->json([
                            'Docentes' => $Docentes
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su sesion ha terminado");
        }
    }

    
    public function cambiarCompDocentesEdit() {
        $idMod = request()->get('idMod');
        $idTem = request()->get('idTem');

        if (Auth::check()) {
            $Docentes = \App\AsigProf::listaEditProf($idMod,$idTem);

            if (request()->ajax()) {
                return response()->json([
                            'Docentes' => $Docentes
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su sesion ha terminado");
        }
    }

    public function ModificarArea($id)
    {
        if (Auth::check()) {

            $this->validate(request(), [
                'nombre_area' => 'required',
            ], [
                'nombre_area.required' => 'Debe Ingresar el Nombre del Área ',
            ]);
            $data = request()->all();

            $Area = \App\Areas::modificar($data, $id);
            if ($Area) {
                $Log = \App\Log::Guardar('Área Modificada', $id);
                return redirect('Asignaturas/GestionAreas')->with('success', 'Datos Guardados');
            } else {
                return redirect('Asignaturas/GestionAreas')->with('error', 'Datos no Guardados');
            }
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function EditarArea($id)
    {
        $bandera = "Menu4";
        $opc = "editar";
        $tr_img = "";

        if (Auth::check()) {
            $Area = \App\Areas::BuscarArea($id);

            return view('Asignaturas.EditarAreas', compact('bandera', 'opc', 'Area'));
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function EliminarArea()
    {
        $mensaje = "";
        $id = request()->get('id');
        if (Auth::check()) {

            $Asig = \App\Asignaturas::listarxare($id);

            if ($Asig->count() > 0) {
                $estado = "NO ELIMINADO";
                $mensaje = 'La Operación no pudo ser Realizada, El Área tiene Asignaturas Asignadas.';
            } else {
                $Area = \App\Areas::BuscarArea($id);
                $estado = "ACTIVO";
                if ($Area->estado == "ACTIVO") {
                    $estado = "ELIMINADO";
                } else {
                    $estado = "ACTIVO";
                }
                $respuesta = \App\Areas::editarestado($id, $estado);

                if ($respuesta) {
                    if ($estado == "ELIMINADO") {
                        $Log = \App\Log::Guardar('Área Eliminada', $id);
                        $mensaje = 'Operación Realizada de Manera Exitosa';
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
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function GestionAsignaturas()
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
            $Asignatura = \App\Asignaturas::Gestion($busqueda, $actual, $limit, $nombre);
            $numero_filas = \App\Asignaturas::numero_de_registros(request()->get('txtbusqueda'), $nombre);

            $paginas = ceil($numero_filas / $limit); //$numero_filas/10;

            return view('Asignaturas.GestionAsignaturas', compact('bandera', 'numero_filas', 'paginas', 'actual', 'limit', 'busqueda', 'Asignatura', 'nombre'));
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function GestionGrado()
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
            $Asignatura = \App\Modulos::Gestion($busqueda, $actual, $limit, $nombre);
            $numero_filas = \App\Modulos::numero_de_registros(request()->get('txtbusqueda'), $nombre);
            $ListAsig = \App\Asignaturas::listar();

            $select_Asig = "<option value='' selected>TODAS LAS ASIGNATURAS</option>";
            foreach ($ListAsig as $Asig) {
                if ($Asig->id == $nombre) {
                    $select_Asig .= "<option value='$Asig->id' selected> " . strtoupper($Asig->nombre) . "</option>";
                } else {
                    $select_Asig .= "<option value='$Asig->id' >" . strtoupper($Asig->nombre) . "</option>";
                }
            }
            $paginas = ceil($numero_filas / $limit); //$numero_filas/10;
            return view('Asignaturas.GestionAsig', compact('bandera', 'numero_filas', 'paginas', 'actual', 'limit', 'busqueda', 'Asignatura', 'select_Asig', 'nombre'));
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function GestionUnidades()
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
            $Unidades = \App\Unidades::Gestion($busqueda, $actual, $limit, $nombre);
            $numero_filas = \App\Unidades::numero_de_registros(request()->get('txtbusqueda'), $nombre);
            $numero_filas = $numero_filas->count();
            $paginas = ceil($numero_filas / $limit); //$numero_filas/10;
            $Asignaturas = \App\Modulos::listar();
            $Docentes = \App\Profesores::Listar();

            $select_docente = "<option value='' selected>Seleccione el Docente</option>";

            $select_Asig = "<option value='' selected>TODAS LAS ASIGNATURAS</option>";
            foreach ($Docentes as $doce) {
                $select_docente .= "<option value='$doce->usuario_profesor'> " . strtoupper($doce->nombre . " " . $doce->apellido) . "</option>";
            }

            $select_Asig = "<option value='' selected>TODAS LAS ASIGNATURAS</option>";
            foreach ($Asignaturas as $Asig) {
                if ($Asig->id == $nombre) {
                    $select_Asig .= "<option value='$Asig->id' selected> " . strtoupper($Asig->nombre) . " - Grado " . $Asig->grado_modulo . "°</option>";
                } else {
                    $select_Asig .= "<option value='$Asig->id' >" . strtoupper($Asig->nombre) . " - Grado " . $Asig->grado_modulo . "°</option>";
                }
            }

            return view('Asignaturas.GestionUnidad', compact('bandera', 'numero_filas', 'paginas', 'actual', 'limit', 'busqueda', 'Unidades', 'select_Asig', 'nombre', 'select_docente'));
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function GestionAsigEvaluacion($id)
    {
        $bandera = "Menu4";
        if (Auth::check()) {
            $Evaluaciones = \App\Evaluacion::ListEval($id, 'C');
            $DesTema = \App\Temas::BuscarTema($id);
            $titTema = $DesTema->titu_contenido;
            $Docentes = \App\Profesores::Listar();

            $select_docente = "<option value='' selected>Seleccione el Docente</option>";

            $select_Asig = "<option value='' selected>TODAS LAS ASIGNATURAS</option>";
            foreach ($Docentes as $doce) {
                $select_docente .= "<option value='$doce->usuario_profesor'> " . strtoupper($doce->nombre . " " . $doce->apellido) . "</option>";
            }
            return view('Asignaturas.GestionEvaluaciones', compact('bandera', 'Evaluaciones', 'titTema', 'id', 'select_docente'));
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function CargarUnidadesReasignar()
    {
        $doce = request()->get('doce');
        $Unidades = \App\Unidades::listarUnidadesxDocente($doce);

        if (request()->ajax()) {
            return response()->json([
                'Unidades' => $Unidades,
            ]);
        }
    }
    public function CargarTemasReasignar()
    {
        $doce = request()->get('doce');
        $Temas = \App\Temas::listarTemasxDocente($doce);

        if (request()->ajax()) {
            return response()->json([
                'Temas' => $Temas,
            ]);
        }
    }
    public function CargarEvalReasignar()
    {
        $doce = request()->get('doce');
        $Temas = \App\Evaluacion::listarEvalxDocente($doce, 'C');

        if (request()->ajax()) {
            return response()->json([
                'Temas' => $Temas,
            ]);
        }
    }

    public function ReasignarUnidades()
    {
        $data = request()->all();

        $VerAsig = \App\Unidades::VerificarAsig($data);
        $Estado = "NO";
        if ($VerAsig == "si") {
            $Unidades = \App\Unidades::ReasignarAsig($data);
            $Mensaje = "Operación Realizada de Manera Exitosa";
            $Estado = "SI";
        } else {
            $Mensaje = "Este Docente no tiene asignada la asignatura a la cual pertenece una de las unidades que está tratando de Reasignar, Por favor Realice la asignación de la asignatura correspondiente antes de realizar este proceso.";
            $Estado = "NO";
        }

        if (request()->ajax()) {
            return response()->json([
                'Mensaje' => $Mensaje,
                "Estado" => $Estado,
            ]);
        }
    }

    public function ReasignarTemas()
    {
        $data = request()->all();

        $VerAsig = \App\Temas::VerificarAsig($data);
        $Estado = "NO";
        if ($VerAsig == "si") {
            $Temas = \App\Temas::ReasignarAsig($data);
            $Mensaje = "Operación Realizada de Manera Exitosa";
            $Estado = "SI";
        } else {
            $Mensaje = "Este Docente no tiene asignada la asignatura a la cual pertenece los Temas que está tratando de Reasignar, Por favor Realice la asignación de la asignatura correspondiente antes de realizar este proceso.";
            $Estado = "NO";
        }

        if (request()->ajax()) {
            return response()->json([
                'Mensaje' => $Mensaje,
                "Estado" => $Estado,
            ]);
        }
    }

    public function ReasignarEval()
    {
        $data = request()->all();

        $VerAsig = \App\Evaluacion::VerificarAsig($data);
        $Estado = "NO";
        if ($VerAsig == "si") {
            $Temas = \App\Evaluacion::ReasignarAsig($data);
            $Mensaje = "Operación Realizada de Manera Exitosa";
            $Estado = "SI";
        } else {
            $Mensaje = "Este Docente no tiene asignada la asignatura a la cual pertenece(n) la(s) Evaluación(es) que está tratando de Reasignar, Por favor Realice la asignación de la asignatura correspondiente antes de realizar este proceso.";
            $Estado = "NO";
        }

        if (request()->ajax()) {
            return response()->json([
                'Mensaje' => $Mensaje,
                "Estado" => $Estado,
            ]);
        }
    }

    public function GestionTemas()
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
            $Temas = \App\Temas::Gestion($busqueda, $actual, $limit, $nombre);
            $Asignaturas = \App\Modulos::listar();
            $Docentes = \App\Profesores::Listar();

            $select_docente = "<option value='' selected>Seleccione el Docente</option>";

            $select_Asig = "<option value='' selected>TODAS LAS ASIGNATURAS</option>";
            foreach ($Docentes as $doce) {
                $select_docente .= "<option value='$doce->usuario_profesor'> " . strtoupper($doce->nombre . " " . $doce->apellido) . "</option>";
            }

            $select_Asig = "<option value='' selected>TODAS LAS ASIGNATURAS</option>";
            foreach ($Asignaturas as $Asig) {
                if ($Asig->id == $nombre) {
                    $select_Asig .= "<option value='$Asig->id' selected> " . strtoupper($Asig->nombre) . " - Grado " . $Asig->grado_modulo . "°</option>";
                } else {
                    $select_Asig .= "<option value='$Asig->id' >" . strtoupper($Asig->nombre) . " - Grado " . $Asig->grado_modulo . "°</option>";
                }
            }

            $numero_filas = \App\Temas::numero_de_registros(request()->get('txtbusqueda'), $nombre);

            $paginas = ceil($numero_filas->count() / $limit); //$numero_filas/10;

            return view('Asignaturas.GestionTemas', compact('bandera', 'numero_filas', 'paginas', 'actual', 'limit', 'busqueda', 'nombre', 'Temas', 'nombre', 'select_Asig', 'select_docente'));
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function NuevoModulo()
    {
        $bandera = "Menu4";
        $opc = "nueva";
        $i = 1;
        $j = 1;
        $trPer = '';
        $Cursos = '';
        if (Auth::check()) {
            $Areas = \App\Areas::listar();

            $Asigna = \App\Asignaturas::listar();
            $Cole = \App\Colegios::InfColeg(Session::get('IdColegio'));
            $Modulo = new \App\Modulos();
            $ParGrupos = \App\ParGrupos::LisGrupos($Cole->num_cursos, $Cole->cant_grupos);

            $SelGrupos = "";
            foreach ($ParGrupos as $Grup) {
                $SelGrupos .= "<option value='$Grup->id' >" . strtoupper($Grup->descripcion) . "</option>";
            }

            return view('Asignaturas.NuevaAsig', compact('bandera', 'Modulo', 'Asigna', 'opc', 'trPer', 'i', 'SelGrupos', 'Areas'));
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function NuevaAsig()
    {
        $bandera = "Menu4";
        $opc = "nueva";
        if (Auth::check()) {
            $Asig = new \App\Asignaturas();
            $Areas = \App\Areas::listar();

            return view('Asignaturas.NuevaAsignatura', compact('bandera', 'Asig', 'opc', 'Areas'));
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function GestionNuevoTema()
    {
        $bandera = "Menu4";
        $opc = "nuevo";
        if (Auth::check()) {
            $Tema = new \App\Temas();
            $Asigna = \App\Modulos::listar();
            return view('Asignaturas.NuevoTema', compact('bandera', 'Tema', 'Asigna', 'opc'));
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function GestionPrueba()
    {
        $bandera = "Menu4";
        if (Auth::check()) {
            $Tema = new \App\Temas();
            $Asigna = \App\Modulos::listar();
            return view('Asignaturas.Prueba', compact('bandera', 'Tema', 'Asigna'));
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function GestionNuevaUnidad()
    {
        $idAsig = request()->get('id');
        $bandera = "Menu4";
        $opc = "nueva";
        if (Auth::check()) {
            $unid = new \App\Unidades();
            $Asigna = \App\Modulos::listar();

            return view('Asignaturas.NuevaUnidad', compact('bandera', 'unid', 'Asigna', 'opc'));
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function CambiarPeriodo()
    {
        $idAsig = request()->get('id');
        if (Auth::check()) {
            $Periodo = \App\Periodos::listar($idAsig);
            //            dd($Periodo);die();
            $select_Periodo = "<option value='' selected>Seleccione</option>";
            foreach ($Periodo as $Perio) {
                $select_Periodo .= "<option value='$Perio->id' >" . strtoupper($Perio->des_periodo) . "</option>";
            }
            if (request()->ajax()) {
                return response()->json([
                    'select_Periodo' => $select_Periodo,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function consulEvalPreg()
    {
        $IdPreg = request()->get('Pregunta');
        $TipPreg = request()->get('TipPregunta');

        if (Auth::check()) {
            if ($TipPreg == "PREGENSAY") {
                $PregEnsayo = \App\EvalPregEnsay::consulPregEnsay($IdPreg);
                if (request()->ajax()) {
                    return response()->json([
                        'PregEnsayo' => $PregEnsayo,
                    ]);
                }
            } else if ($TipPreg == "COMPLETE") {
                $PregComple = \App\EvalPregComplete::ConsultComplete($IdPreg);
                if (request()->ajax()) {
                    return response()->json([
                        'PregComple' => $PregComple,
                    ]);
                }
            } else if ($TipPreg == "OPCMULT") {
                $PregMult = \App\PregOpcMul::ConsulPreg($IdPreg);
                $OpciMult = \App\OpcPregMul::ConsulGrupOpcPreg($IdPreg);
                if (request()->ajax()) {
                    return response()->json([
                        'PregMult' => $PregMult,
                        'OpciMult' => $OpciMult,
                    ]);
                }
            } else if ($TipPreg == "VERFAL") {
                $PregVerFal = \App\EvalVerFal::ConVerFal($IdPreg);
                if (request()->ajax()) {
                    return response()->json([
                        'PregVerFal' => $PregVerFal,
                    ]);
                }
            } else if ($TipPreg == "RELACIONE") {
                $PregRelacione = \App\PregRelacione::ConRela($IdPreg);
                $PregRelIndi = \App\EvalRelacione::PregRelDef($IdPreg);
                $PregRelResp = \App\EvalRelacioneOpc::PregRelOpc($IdPreg);
                $PregRelRespAdd = \App\EvalRelacioneOpc::PregRelOpcAdd($IdPreg);

                if (request()->ajax()) {
                    return response()->json([
                        'PregRelacione' => $PregRelacione,
                        'PregRelIndi' => $PregRelIndi,
                        'PregRelResp' => $PregRelResp,
                        'PregRelRespAdd' => $PregRelRespAdd,

                    ]);
                }
            } else if ($TipPreg == "TALLER") {
                $PregTaller = \App\EvalTaller::PregTaller($IdPreg);
                if (request()->ajax()) {
                    return response()->json([
                        'PregTaller' => $PregTaller,
                    ]);
                }
            }
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function ConsulEval()
    {
        if (Auth::check()) {
            $ideva = request()->get('ideva');

            $Evaluacion = \App\Evaluacion::BusEval($ideva);

            $PregEval = \App\CosEval::GrupPreg($ideva);
            /////CONUSLTAR PREGUNTA ENSAYO
            $PregEnsayo = \App\EvalPregEnsay::consulPregEnsayAll($ideva);

            ///////CONSULTAR PREGUNTA COMPLETE
            $PregComple = \App\EvalPregComplete::ConsultCompleteAll($ideva);

            ///////CONSULTAR PREGUNTA OPCION MULTIPLE COMPLE
            $PregMult = \App\PregOpcMul::ConsulPregAll($ideva);
            $OpciMult = \App\OpcPregMul::ConsulGrupOpcPregAll($ideva);

            ///////CONSULTAR PREGUNTA VERDADERO Y FALSO
            $PregVerFal = \App\EvalVerFal::VerFal($ideva);

            ///////CONSULTAR PREGUNTA RELACIONE
            $PregRelacione = \App\PregRelacione::ConRelaAll($ideva);
            $PregRelIndi = \App\EvalRelacione::PregRelDefAll($ideva);
            $PregRelResp = \App\EvalRelacioneOpc::PregRelOpcAll($ideva);
            $PregRelRespAdd = \App\EvalRelacioneOpc::PregRelOpcaddAll($ideva);

            /////////CONSULTAR TALLER
            $PregTaller = \App\EvalTaller::PregTallerAll($ideva);

            /////CONSULTAR VIDEO
            $VideoEval = \App\EvalPregDidact::PregDida($ideva);
            $video = "no";
            $id = "no";
            if ($VideoEval) {
                $video = $VideoEval->cont_didactico;
                $id = $VideoEval->id;
            }

            if (request()->ajax()) {
                return response()->json([
                    'Evaluacion' => $Evaluacion,
                    'PregEval' => $PregEval,
                    'PregEnsayo' => $PregEnsayo,
                    'PregComple' => $PregComple,
                    'PregMult' => $PregMult,
                    'OpciMult' => $OpciMult,
                    'PregVerFal' => $PregVerFal,
                    'PregRelacione' => $PregRelacione,
                    'PregRelIndi' => $PregRelIndi,
                    'PregRelResp' => $PregRelResp,
                    'PregRelRespAdd' => $PregRelRespAdd,
                    'PregTaller' => $PregTaller,
                    'VideoEval' => $video,
                    'idvideo' => $id,

                ]);
            }
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function CambiarAsignaturas()
    {
        $idArea = request()->get('id');
        if (Auth::check()) {
            $Asig = \App\Asignaturas::listarxare($idArea);
            $select_Asignaturas = "<option value='' selected>Seleccione...</option>";
            foreach ($Asig as $Asi) {
                $select_Asignaturas .= "<option value='$Asi->id' >" . strtoupper($Asi->nombre) . "</option>";
            }
            if (request()->ajax()) {
                return response()->json([
                    'select_Asignaturas' => $select_Asignaturas,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function CambiarAsignaturas2()
    {
        $idArea = request()->get('idarea');
        $idasig = request()->get('idasig');

        if (Auth::check()) {
            $Asig = \App\Asignaturas::listarxare($idArea);
            $select_Asignaturas = "<option value='' selected>Seleccione...</option>";
            foreach ($Asig as $Asi) {
                if ($Asi->id == $idasig) {
                    $select_Asignaturas .= "<option value='$Asi->id' selected> " . strtoupper($Asi->nombre) . "</option>";
                } else {
                    $select_Asignaturas .= "<option value='$Asi->id' >" . strtoupper($Asi->nombre) . "</option>";
                }
            }
            if (request()->ajax()) {
                return response()->json([
                    'select_Asignaturas' => $select_Asignaturas,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function CambiarPeriodo2()
    {
        $idAsig = request()->get('id');
        $idPer = request()->get('idPer');

        if (Auth::check()) {
            $Periodo = \App\Periodos::listar($idAsig);

            $select_Periodo = "<option value='' selected>Seleccione</option>";
            foreach ($Periodo as $Perio) {

                if ($Perio->id == $idPer) {

                    $select_Periodo .= "<option value='$Perio->id' selected> " . strtoupper($Perio->des_periodo) . "</option>";
                } else {
                    $select_Periodo .= "<option value='$Perio->id' >" . strtoupper($Perio->des_periodo) . "</option>";
                }
            }
            if (request()->ajax()) {
                return response()->json([
                    'select_Periodo' => $select_Periodo,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function CambiarUnidad()
    {
        $idPeri = request()->get('id');
        if (Auth::check()) {
            if (Session::get('TIPCONT') == 'ASI') {
                $Unidades = \App\Unidades::listar($idPeri);
            } else {
                $Unidades = \App\UnidadesModulos::listar($idPeri);
            }
            $select_Unidades = "<option value='' selected>Todas</option>";
            foreach ($Unidades as $Uni) {
                $select_Unidades .= "<option value='$Uni->id' >" . mb_strtoupper($Uni->nom_unidad . ' -- ' . $Uni->des_unidad) . "</option>";
            }
            if (request()->ajax()) {
                return response()->json([
                    'select_Unidades' => $select_Unidades,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function CambiarTema()
    {
        $idUni = request()->get('idUni');
        if (Auth::check()) {
            if (Session::get('TIPCONT') == 'ASI') {
                if (request()->get('evalCal') == "T") {
                    $Temas = \App\Temas::temas($idUni);
                } else {
                    $Temas = \App\Laboratorios::temas($idUni);
                }
            } else {
                $Temas = \App\TemasModulos::temas($idUni);
            }

            $Select_Temas = "<option value='' selected>Todos</option>";
            foreach ($Temas as $Tem) {
                $Select_Temas .= "<option value='$Tem->id' >" . strtoupper($Tem->titu_contenido) . "</option>";
            }

            if (request()->ajax()) {
                return response()->json([
                    'Select_Temas' => $Select_Temas,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function CambiarEvaluaciones()
    {
        $idTem = request()->get('idTem');
        if (Auth::check()) {
            if (Session::get('TIPCONT') == 'ASI') {
                if (request()->get('evalCal') == "T") {
                    $Evaluaciones = \App\Evaluacion::ListEvalCal($idTem);
                } else {
                    $Evaluaciones = \App\Evaluacion::ListEvalCalLab($idTem);
                }
            } else {
                $Evaluaciones = \App\Evaluacion::ListEvalCalMod($idTem);
            }
            $tr_Eval = "";
            $clasif = "";
            foreach ($Evaluaciones as $Eval) {
                $tr_Eval .= '<tr data-id="tr_' . $Eval->id . '" id="alumno' . $Eval->id . '">
                    <td class="text-truncate" style="text-transform: capitalize;">' . mb_strtolower($Eval->titulo) . '</td>';
                if ($Eval->clasificacion == "ACTINI") {
                    $clasif = "ACTIVIDAD DE INICIO";
                } else {
                    $clasif = "PRODUCCIÓN";
                }
                $tr_Eval .= '<td class="text-truncate">' . $clasif . '</td>';
                $tr_Eval .= '<td class="text-truncate">' . $Eval->titu_contenido . '</td>';

                $tr_Eval .= '<td class="text-truncate">
                <button type="button" data-toggle="tooltip"  data-original-title="Revisar Evaluación" data-animation="false" onclick="$.VerEval(' . $Eval->id . ');" class="btn btn-icon btn-pure primary"><i style="font-size: 30px;" class="ft-eye"></i></button>
                    </td>
                </tr>';
            }
            if (request()->ajax()) {
                return response()->json([
                    'tr_Eval' => $tr_Eval,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function CambiarUnidad2()
    {
        $idPeri = request()->get('idPer');
        $idUnid = request()->get('idUnid');
        if (Auth::check()) {
            $Unidades = \App\Unidades::listar($idPeri);
            $select_Unidades = "<option value=''>Seleccione</option>";
            foreach ($Unidades as $Uni) {
                if ($Uni->id == $idUnid) {
                    $select_Unidades .= "<option value='$Uni->id' selected> " . strtoupper($Uni->nom_unidad . ' - ' . $Uni->des_unidad) . "</option>";
                } else {
                    $select_Unidades .= "<option value='$Uni->id' >" . strtoupper($Uni->nom_unidad . ' - ' . $Uni->des_unidad) . "</option>";
                }
            }
            if (request()->ajax()) {
                return response()->json([
                    'select_Unidades' => $select_Unidades,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function InfDocumentos()
    {
        $IdTema = request()->get('IdTema');
        if (Auth::check()) {
            $DesTema = \App\DesarrollTema::BuscarTema($IdTema, 'NO');

            $Animaciones = \App\ContDidactico::BuscarTema($IdTema, 'NO');
            $content = $DesTema->cont_documento;
            if (request()->ajax()) {
                return response()->json([
                    'DesTema' => $DesTema,
                    'Animaciones' => $Animaciones,
                    'content' => $content,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function InfDocumentosDida()
    {
        $IdTema = request()->get('IdTema');
        if (Auth::check()) {
            $DesTema = \App\ContDidactico::BuscarTema($IdTema, 'NO');

            if (request()->ajax()) {
                return response()->json([
                    'DesTema' => $DesTema,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function InfArchivos()
    {
        $IdTema = request()->get('IdTema');
        if (Auth::check()) {
            $DatArchivos = \App\SubirArcTema::BuscarArchi($IdTema);
            if (request()->ajax()) {
                return response()->json([
                    'Archivos' => $DatArchivos,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function InfLinks()
    {
        $IdTema = request()->get('IdTema');
        if (Auth::check()) {
            $DatLink = \App\DesarrolloLink::DesLink($IdTema, 'NO');
            if (request()->ajax()) {
                return response()->json([
                    'Links' => $DatLink,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function DelArchivoVideo()
    {

        $mensaje = "";
        $estado = "no";
        $id = request()->get('id');
        if (Auth::check()) {
            $respuesta = \App\ContDidactico::EliminarCont($id);
            if ($respuesta) {
                $Log = \App\Log::Guardar('Video Eliminado', $id);
                $mensaje = 'Operación Realizada de Manera Exitosa';
                $estado = "ok";
            } else {
                $mensaje = 'La Operación no pudo ser Realizada';
            }
            if (request()->ajax()) {
                return response()->json([
                    'id' => $id,
                    'mensaje' => $mensaje,
                    'estado' => $estado,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function InfEval()
    {
        $IdTema = request()->get('IdTema');
        $RutAn = "";
        if (Auth::check()) {
            $Dat = \App\Evaluacion::DesEval($IdTema);
            if ($Dat->animacion == "SI") {
                $Animacion = \App\EvalPregDidact::PregDida($Dat->id);
                $RutAn = $Animacion->cont_didactico;
            }

            if ($Dat->tip_evaluacion == "GRUPREGUNTA") {
                $Pregutas = \App\EvalGrupPreg::GrupPreg($Dat->id);
                if (request()->ajax()) {
                    return response()->json([
                        'Resp' => $Pregutas,
                        'id_eval' => $Dat->id,
                        'titulo' => $Dat->titulo,
                        'enunciado' => $Dat->enunciado,
                        'hab_conversacion' => $Dat->hab_conversacion,
                        'intentos_perm' => $Dat->intentos_perm,
                        'calif_usando' => $Dat->calif_usando,
                        'punt_max' => $Dat->punt_max,
                        'animacion' => $Dat->animacion,
                        'tiempo' => $Dat->tiempo,
                        'RutAn' => $RutAn,
                    ]);
                }
            } else if ($Dat->tip_evaluacion == "PREGENSAY") {
                $PregEnsy = \App\EvalPregEnsay::PregEnsay($Dat->id);
                $Preguta = $PregEnsy->pregunta;
                if (request()->ajax()) {
                    return response()->json([
                        'Resp' => $Preguta,
                        'id_eval' => $Dat->id,
                        'titulo' => $Dat->titulo,
                        'enunciado' => $Dat->enunciado,
                        'hab_conversacion' => $Dat->hab_conversacion,
                        'intentos_perm' => $Dat->intentos_perm,
                        'calif_usando' => $Dat->calif_usando,
                        'punt_max' => $Dat->punt_max,
                        'animacion' => $Dat->animacion,
                        'tiempo' => $Dat->tiempo,
                        'RutAn' => $RutAn,
                    ]);
                }
            } else if ($Dat->tip_evaluacion == "COMPLETE") {
                $PregComple = \App\EvalPregComplete::PregComplete($Dat->id);
                $opciones = $PregComple->opciones;
                $parrafo = $PregComple->parrafo;
                if (request()->ajax()) {
                    return response()->json([
                        'opciones' => $opciones,
                        'parrafo' => $parrafo,
                        'id_eval' => $Dat->id,
                        'titulo' => $Dat->titulo,
                        'enunciado' => $Dat->enunciado,
                        'hab_conversacion' => $Dat->hab_conversacion,
                        'intentos_perm' => $Dat->intentos_perm,
                        'calif_usando' => $Dat->calif_usando,
                        'punt_max' => $Dat->punt_max,
                        'animacion' => $Dat->animacion,
                        'tiempo' => $Dat->tiempo,
                        'RutAn' => $RutAn,
                    ]);
                }
            } else if ($Dat->tip_evaluacion == "RELACIONE") {
                $PregRelDef = \App\EvalRelacione::PregRelDef($Dat->id);
                $PregRelOpc = \App\EvalRelacioneOpc::PregRelOpc($Dat->id);

                if (request()->ajax()) {
                    return response()->json([
                        'PregRelDef' => $PregRelDef,
                        'PregRelOpc' => $PregRelOpc,
                        'id_eval' => $Dat->id,
                        'titulo' => $Dat->titulo,
                        'enunciado' => $Dat->enunciado,
                        'hab_conversacion' => $Dat->hab_conversacion,
                        'intentos_perm' => $Dat->intentos_perm,
                        'calif_usando' => $Dat->calif_usando,
                        'punt_max' => $Dat->punt_max,
                        'animacion' => $Dat->animacion,
                        'tiempo' => $Dat->tiempo,
                        'RutAn' => $RutAn,
                    ]);
                }
            } else if ($Dat->tip_evaluacion == "TALLER") {
                $PregTaller = \App\EvalTaller::PregTaller($Dat->id);

                if (request()->ajax()) {
                    return response()->json([
                        'PregTaller' => $PregTaller,
                        'id_eval' => $Dat->id,
                        'titulo' => $Dat->titulo,
                        'enunciado' => $Dat->enunciado,
                        'hab_conversacion' => $Dat->hab_conversacion,
                        'intentos_perm' => $Dat->intentos_perm,
                        'calif_usando' => $Dat->calif_usando,
                        'punt_max' => $Dat->punt_max,
                        'animacion' => $Dat->animacion,
                        'tiempo' => $Dat->tiempo,
                        'RutAn' => $RutAn,
                    ]);
                }
            } else if ($Dat->tip_evaluacion == "DIDACTICO") {
                $PregEnsy = \App\EvalPregDidact::PregDida($Dat->id);
                $Preguta = $PregEnsy->pregunta;
                $ArcDidac = $PregEnsy->cont_didactico;
                if (request()->ajax()) {
                    return response()->json([
                        'Resp' => $Preguta,
                        'ArchDidac' => $ArcDidac,
                        'id_eval' => $Dat->id,
                        'titulo' => $Dat->titulo,
                        'enunciado' => $Dat->enunciado,
                        'hab_conversacion' => $Dat->hab_conversacion,
                        'intentos_perm' => $Dat->intentos_perm,
                        'calif_usando' => $Dat->calif_usando,
                        'punt_max' => $Dat->punt_max,
                        'animacion' => $Dat->animacion,
                        'tiempo' => $Dat->tiempo,
                        'RutAn' => $RutAn,
                    ]);
                }
            } else if ($Dat->tip_evaluacion == "OPCMULT") {
                //                dd($Dat->id);die;
                $PregOpcMul = \App\PregOpcMul::GrupPreg($Dat->id);

                $OpcMul = \App\OpcPregMul::GrupOpc2($Dat->id);

                //                dd($OpcMul);die();
                if (request()->ajax()) {
                    return response()->json([
                        'Preg' => $PregOpcMul,
                        'Opc' => $OpcMul,
                        'id_eval' => $Dat->id,
                        'titulo' => $Dat->titulo,
                        'enunciado' => $Dat->enunciado,
                        'hab_conversacion' => $Dat->hab_conversacion,
                        'intentos_perm' => $Dat->intentos_perm,
                        'calif_usando' => $Dat->calif_usando,
                        'punt_max' => $Dat->punt_max,
                        'animacion' => $Dat->animacion,
                        'tiempo' => $Dat->tiempo,
                        'RutAn' => $RutAn,
                    ]);
                }
            } else {
                $Pregutas = \App\EvalVerFal::VerFal($Dat->id);

                if (request()->ajax()) {
                    return response()->json([
                        'Resp' => $Pregutas,
                        'id_eval' => $Dat->id,
                        'titulo' => $Dat->titulo,
                        'enunciado' => $Dat->enunciado,
                        'hab_conversacion' => $Dat->hab_conversacion,
                        'intentos_perm' => $Dat->intentos_perm,
                        'calif_usando' => $Dat->calif_usando,
                        'punt_max' => $Dat->punt_max,
                        'animacion' => $Dat->animacion,
                        'tiempo' => $Dat->tiempo,
                        'RutAn' => $RutAn,
                    ]);
                }
            }
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function GuardarAsig()
    {
        $bandera = "Menu4";

        if (Auth::check()) {

            $this->validate(request(), [
                'area' => 'required',
                'nombre' => 'required',
                'grado_modulo' => 'required',
                'grupos' => 'required',
            ], [
                'area.required' => 'Debe Seleccionar el Área',
                'nombre.required' => 'Debe Seleccionar la Asignatura',
                'grado_modulo.required' => 'Debe Seleccionar el Grado',
                'grupos.required' => 'Debe Seleccionar Los Grupos para el Grado Seleccionado',
            ]);
            $data = request()->all();

            if (request()->hasfile('imagen')) {
                foreach (request()->file('imagen') as $file) {
                    $prefijo = substr(md5(uniqid(rand())), 0, 6);
                    $name = self::sanear_string($prefijo . '_' . $file->getClientOriginalName());
                    $file->move(public_path() . '/app-assets/images/Img_Modulos/', $name);
                    $img[] = $name;
                }
                $data['img'] = $img;
            }
            $Asig = \App\Modulos::Guardar($data);
            if ($Asig) {
                $data['modulo_id'] = $Asig->id;
                $Grupos = \App\Grupos::Guardar($data);
                if ($Grupos) {
                    $periodos = \App\Periodos::Guardar($data);
                    if ($periodos) {
                        $ImgMod = \App\ImgModulos::Guardar($data);
                        if ($ImgMod) {
                            $Log = \App\Log::Guardar('Grado de Asignatura Guardado', $Asig->id);
                            return redirect('Asignaturas/GestionGrado')->with('success', 'Datos Guardados');
                        } else {
                            return redirect('Asignaturas/GestionGrado')->with('error', 'Datos no Guardados');
                        }
                    } else {
                        return redirect('Asignaturas/GestionGrado')->with('error', 'Datos no Guardados');
                    }
                } else {
                    return redirect('Asignaturas/GestionGrado')->with('error', 'Datos no Guardados');
                }
            } else {
                return redirect('Asignaturas/GestionGrado')->with('error', 'Datos no Guardados');
            }
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function GuardarAsignaturas()
    {
        $bandera = "Menu4";

        if (Auth::check()) {

            $this->validate(request(), [
                'area' => 'required',
                'nombre' => 'required',
            ], [
                'area.required' => 'Debe Seleccionar el Área',
                'nombre.required' => 'Debe Ingresar la Asignatura',
            ]);
            $data = request()->all();

            if (request()->hasfile('imagen')) {
                foreach (request()->file('imagen') as $file) {
                    $prefijo = substr(md5(uniqid(rand())), 0, 6);
                    $name = self::sanear_string($prefijo . '_' . $file->getClientOriginalName());
                    $file->move(public_path() . '/app-assets/images/Img_Asinaturas/', $name);
                    $img[] = $name;
                }
                $data['img'] = $img;
            }
            $Asig = \App\Asignaturas::Guardar($data);
            if ($Asig) {
                $data['asig_id'] = $Asig->id;
                $ImgAsig = \App\ImgAsignatura::Guardar($data);

                if ($ImgAsig) {
                    $Log = \App\Log::Guardar('Asignatura Guardada', $Asig->id);
                    return redirect('Asignaturas/GestionAsignaturas')->with('success', 'Datos Guardados');
                } else {
                    return redirect('Asignaturas/GestionAsignaturas')->with('error', 'Datos no Guardados');
                }
            } else {
                return redirect('Asignaturas/GestionAsignaturas')->with('error', 'Datos no Guardados');
            }
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function editar($id)
    {
        $bandera = "Menu4";
        $opc = "editar";
        $trPer = '';
        $tr_img = '';
        $Cursos = '';

        if (Auth::check()) {
            $Areas = \App\Areas::listar();
            $Modulo = \App\Modulos::BuscarAsig($id);
            $Asigna = \App\Asignaturas::listar();
            $Cole = \App\Colegios::InfColeg(Session::get('IdColegio'));

            $Perio = \App\Periodos::listar($id);
            $Grupos = \App\Grupos::listar($id);
            $Imagenes = \App\ImgModulos::ListImg($id);

            $ParGrupos = \App\ParGrupos::LisGrupos($Cole->num_cursos, $Cole->cant_grupos);

            $SelGrupos = "";

            foreach ($ParGrupos as $ParGrup) {
                $SelGrupos .= "<option value='$ParGrup->id' ";
                foreach ($Grupos as $Grup) {
                    if ($Grup->grupo == $ParGrup->id) {
                        $SelGrupos .= "selected";
                    }
                }
                $SelGrupos .= ">" . strtoupper($ParGrup->descripcion) . "</option>";
            }

            $i = 1;
            foreach ($Perio as $PR) {
                $trPer .= '<tr id="tr_' . $i . '">
                    <td class="text-truncate">' . $PR->des_periodo . '</td><input type="hidden" id="txtperi' . $i . '" name="txtperi[]"  value="' . $PR->des_periodo . '"><input type="hidden" id="txtidperi' . $i . '" name="txtidperi[]"  value="' . $PR->id . '">
                    <td class="text-truncate" id="td_porc' . $i . '">' . $PR->avance_perido . '</td><input type="hidden" class="PorcPer" id="txtporc' . $i . '" name="txtporc[]"  value="' . $PR->avance_perido . '">
                    <td class="text-truncate">
                    <a onclick="$.EditPer(' . $i . ')" class="btn btn-info btn-sm  text-white"  title="Editar"><i class="fa fa-edit font-medium-3" aria-hidden="true"></i></a>&nbsp;
                    <a onclick="$.DelPer(this.id)" id="Per_' . $i . '" data-id="' . $PR->id . '" data-origen="VIEJO" class="btn btn-danger btn-sm btnQuitarPer text-white"  title="Eliminar"><i class="fa fa-trash-o font-medium-3" aria-hidden="true"></i></a>&nbsp;

                    </td>
                </tr>';
                $i++;
            }
            $j = 1;
            foreach ($Imagenes as $Img) {
                $tr_img .= '<tr id="trImg_' . $Img->id . '">
                    <td class="text-truncate">' . $j . '</td>
                    <td class="text-truncate">' . $Img->url_img . '</td>
                    <td class="text-truncate">
                    <a onclick="$.DelImg(' . $Img->id . ')" class="btn btn-danger btn-sm btnQuitar text-white"  title="Eliminar"><i class="fa fa-trash-o font-medium-3" aria-hidden="true"></i></a>&nbsp;
                     <a onclick="$.MostImg(this.id)" id="' . $Img->id . '"  data-archivo="' . $Img->url_img . '" class="btn btn-primary btn-sm btnVer text-white"  title="Ver"><i class="fa fa-search font-medium-3" aria-hidden="true"></i></a>&nbsp;
                </td>
                </tr>';
                $j++;
            }

            return view('Asignaturas.EditarAsig', compact('bandera', 'Modulo', 'Asigna', 'trPer', 'tr_img', 'i', 'opc', 'SelGrupos', 'Areas'));
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function EditarAsignatura($id)
    {
        $bandera = "Menu4";
        $opc = "editar";
        $tr_img = "";

        if (Auth::check()) {
            $Areas = \App\Areas::listar();
            $Asig = \App\Asignaturas::BuscarAsig($id);

            $Imagenes = \App\ImgAsignatura::ListImg($id);

            $j = 1;
            foreach ($Imagenes as $Img) {
                $tr_img .= '<tr id="trImg_' . $Img->id . '">
                    <td class="text-truncate">' . $j . '</td>
                    <td class="text-truncate">' . $Img->url_img . '</td>
                    <td class="text-truncate">
                    <a onclick="$.DelImg(' . $Img->id . ')" class="btn btn-danger btn-sm btnQuitar text-white"  title="Eliminar"><i class="fa fa-trash-o font-medium-3" aria-hidden="true"></i></a>&nbsp;
                     <a onclick="$.MostImg(this.id)" id="' . $Img->id . '"  data-archivo="' . $Img->url_img . '" class="btn btn-primary btn-sm btnVer text-white"  title="Ver"><i class="fa fa-search font-medium-3" aria-hidden="true"></i></a>&nbsp;
                </td>
                </tr>';
                $j++;
            }

            return view('Asignaturas.EditarAsignaturas', compact('bandera', 'Asig', 'tr_img', 'opc', 'Areas'));
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function editarTema($id)
    {
        $bandera = "Menu4";
        $opc = "editar";
        if (Auth::check()) {

            $Tema = \App\Temas::BuscarTema($id);
            $Asigna = \App\Modulos::listar();

            return view('Asignaturas.EditarTema', compact('bandera', 'Tema', 'Asigna', 'opc'));
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function EditarEval($id)
    {
        $bandera = "Menu4";
        $opc = "editar";
        if (Auth::check()) {
            $Eval = \App\Evaluacion::BusEval($id);
            $Tema = \App\Temas::BuscarTema($Eval->contenido);
            $Asigna = \App\Modulos::listar();
            return view('Asignaturas.EditarEval', compact('bandera', 'Tema', 'Asigna', 'opc', 'Eval'));
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function modificarAsig($id)
    {
        if (Auth::check()) {
            $Asig = \App\Modulos::BuscarAsig($id);
            $this->validate(request(), [
                'area' => 'required',
                'nombre' => 'required',
                'grado_modulo' => 'required',
            ], [
                'area.required' => 'Debe Seleccionar el Área',
                'nombre.required' => 'Debe Seleccionar la Asignatura',
                'grado_modulo.required' => 'Debe Seleccionar el Grado',
            ]);
            $data = request()->all();

            $data['modulo_id'] = $id;

            if (request()->hasfile('imagen')) {

                foreach (request()->file('imagen') as $file) {
                    $name = rand(1, 100) . '_' . $file->getClientOriginalName();
                    $file->move(public_path() . '/app-assets/images/Img_Modulos/', $name);
                    $img[] = $name;
                }
                $data['img'] = $img;
            }

            $respuesta = \App\Modulos::modificar($data, $id);
            if ($respuesta) {
                $Grupos = \App\Grupos::Guardar($data);
                if ($Grupos) {
                    $periodos = \App\Periodos::Guardar($data);
                    if ($periodos) {
                        if (request()->hasfile('imagen')) {
                            $ImgMod = \App\ImgModulos::Guardar($data);
                            if ($ImgMod) {
                                $Log = \App\Log::Guardar('Grado de Asignatura Modificado', $id);
                                return redirect('Asignaturas/GestionGrado')->with('success', 'Datos Guardados');
                            } else {
                                return redirect('Asignaturas/GestionGrado')->with('error', 'Datos no Guardados');
                            }
                        } else {
                            return redirect('Asignaturas/GestionGrado')->with('success', 'Datos Guardados');
                        }
                    } else {
                        return redirect('Asignaturas/GestionGrado')->with('error', 'Datos no Guardados');
                    }
                } else {
                    return redirect('Asignaturas/GestionGrado')->with('error', 'Datos no Guardados');
                }
            } else {
                return redirect('Asignaturas/GestionGrado')->with('error', 'Datos no Guardados');
            }
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function ModificarAsignaturas($id)
    {
        if (Auth::check()) {
            $Asig = \App\Modulos::BuscarAsig($id);
            $this->validate(request(), [
                'area' => 'required',
                'nombre' => 'required',
            ], [
                'area.required' => 'Debe Seleccionar el Área',
                'nombre.required' => 'Debe Ingresar el Nombre de la Asignatura',
            ]);
            $data = request()->all();
            $data['asig_id'] = $id;

            if (request()->hasfile('imagen')) {

                foreach (request()->file('imagen') as $file) {
                    $name = rand(1, 100) . '_' . $file->getClientOriginalName();
                    $file->move(public_path() . '/app-assets/images/Img_Asinaturas/', $name);
                    $img[] = $name;
                }
                $data['img'] = $img;
            }

            $respuesta = \App\Asignaturas::modificar($data, $id);
            if ($respuesta) {
                if (request()->hasfile('imagen')) {
                    $ImgMod = \App\ImgAsignatura::Guardar($data);
                    if ($ImgMod) {
                        $Log = \App\Log::Guardar('Asignatura Modificada', $id);
                        return redirect('Asignaturas/GestionAsignaturas')->with('success', 'Datos Guardados');
                    } else {
                        return redirect('Asignaturas/GestionAsignaturas')->with('error', 'Datos no Guardados');
                    }
                } else {
                    $Log = \App\Log::Guardar('Asignatura Modificada', $id);
                    return redirect('Asignaturas/GestionAsignaturas')->with('success', 'Datos Guardados');
                }
            } else {
                return redirect('Asignaturas/GestionAsignaturas')->with('error', 'Datos no Guardados');
            }
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function consultarAsig($id)
    {
        $bandera = "Menu4";
        $opc = "Consulta";
        $trPer = '';
        $tr_img = '';

        if (Auth::check()) {
            $Asig = \App\Asignaturas::BuscarAsig($id);
            $Perio = \App\Periodos::listar($id);
            $Imagenes = \App\ImgModulos::ListImg($id);

            $i = 1;
            foreach ($Perio as $PR) {
                $trPer .= '<tr id="tr_' . $i . '">
                    <td class="text-truncate">' . $PR->des_periodo . '</td><input type="hidden" id="txtperi' . $i . '" name="txtperi[]"  value="' . $PR->des_periodo . '">
                    <td class="text-truncate">' . $PR->avance_perido . '</td><input type="hidden" id="txtporc' . $i . '" name="txtporc[]"  value="' . $PR->avance_perido . '">

                </tr>';
                $i++;
            }
            $j = 1;
            foreach ($Imagenes as $Img) {
                $tr_img .= '<tr id="trImg_' . $Img->id . '">
                    <td class="text-truncate">' . $j . '</td>
                    <td class="text-truncate">' . $Img->url_img . '</td>
                    <td class="text-truncate">
                     <a onclick="$.MostImg(this.id)" id="' . $Img->id . '"  data-archivo="' . $Img->url_img . '" class="btn btn-primary btn-sm btnVer text-white"  title="Ver"><i class="fa fa-search font-medium-3" aria-hidden="true"></i></a>&nbsp;
                </td>
                </tr>';
                $j++;
            }

            return view('Asignaturas.ConsultarAsig', compact('bandera', 'Asig', 'trPer', 'tr_img', 'i', 'opc'));
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function consultarUnidad($id)
    {
        $bandera = "Menu4";
        $opc = "Consulta";

        if (Auth::check()) {
            $unid = \App\Unidades::BuscarUnidad($id);
            $Asigna = \App\Modulos::listar();
            return view('Asignaturas.ConsultarUnidad', compact('bandera', 'unid', 'Asigna', 'opc'));
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function EliminarBancoPregunta()
    {
        $mensaje = "";
        $id = request()->get('id');
        if (Auth::check()) {
            $Asig = \App\BancoPregModuloE::BuscarAsig($id);
            $estado = "ACTIVO";
            if ($Asig->estado_modulo == "ACTIVO") {
                $estado = "ELIMINADO";
            } else {
                $estado = "ACTIVO";
            }
            $respuesta = \App\Modulos::editarestado($id, $estado);

            if ($respuesta) {
                if ($estado == "ELIMINADO") {
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

    public function EliminarAsignaturas()
    {
        $mensaje = "";
        $id = request()->get('id');
        if (Auth::check()) {

            $Verif = \App\Asignaturas::VerfDel($id);
            if ($Verif->count() > 0) {
                $mensaje = 'Este Contenido es Propio de la Plataforma,  No puede ser Eliminado...';
                $estado = "SINPERMISO";
            } else {
                $Grado = \App\Modulos::ListarxAsig($id);
                if ($Grado->count() > 0) {
                    $estado = "NO ELIMINADO";
                    $mensaje = 'La Operación no pudo ser Realizada, La Asignatura tiene grados asignados.';
                } else {
                    $Asig = \App\Asignaturas::BuscarAsig($id);
                    $estado = "ACTIVO";
                    if ($Asig->estado == "ACTIVO") {
                        $estado = "ELIMINADO";
                    } else {
                        $estado = "ACTIVO";
                    }

                    $respuesta = \App\Asignaturas::editarestado($id, $estado);

                    if ($respuesta) {
                        if ($estado == "ELIMINADO") {
                            $Log = \App\Log::Guardar('Asignatura Eliminada', $id);
                            $mensaje = 'Operación Realizada de Manera Exitosa';
                        }
                    } else {
                        $mensaje = 'La Operación no pudo ser Realizada';
                    }
                }
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

    public function EliminarAsig()
    {
        $mensaje = "";
        $id = request()->get('id');
        if (Auth::check()) {
            $Verif = \App\Modulos::VerfDel($id);

            if ($Verif->count() > 0) {
                $mensaje = 'Este Contenido es Propio de la Plataforma,  No puede ser Eliminado...';
                $estado = "SINPERMISO";
            } else {
                $Asig = \App\Modulos::BuscarAsig($id);
                $Per = \App\Periodos::listarVerf($id);

                if ($Per->count() > 0) {
                    $estado = "NO ELIMINADO";
                    $mensaje = 'No es posible Eliminar este Grado. Tiene Periodos Relacionados con Unidades Asignadas.';
                } else {
                    $estado = "ACTIVO";
                    if ($Asig->estado_modulo == "ACTIVO") {
                        $estado = "ELIMINADO";
                    } else {
                        $estado = "ACTIVO";
                    }
                    $respuesta = \App\Modulos::editarestado($id, $estado);

                    if ($respuesta) {
                        if ($estado == "ELIMINADO") {
                            $Log = \App\Log::Guardar('Grado de Asignatura Eliminado', $id);

                            $mensaje = 'Operación Realizada de Manera Exitosa';
                        }
                    } else {
                        $mensaje = 'La Operación no pudo ser Realizada';
                    }
                }
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

    public function EliminarUnidad()
    {
        $mensaje = ""; 
        $id = request()->get('id');
        $opc = "NT";
        if (Auth::check()) {
            $Verf = \App\Unidades::VerfDel($id);
            if ($Verf->count() > 0) {
                $mensaje = 'Este Contenido es Propio de la Plataforma,  No puede ser Eliminado...';
                $estado = "SINPERMISO";
                $opc = "VU";
            } else {
                
                $UnidTem = \App\Temas::BuscarUnidadxTema($id);
                $Unid = \App\Unidades::BuscarUnidad($id);
                if ($Unid->docente == "" && Auth::user()->tipo_usuario == 'Profesor') {
                    $estado = "NO ELIMINADO";
                    $opc = "NO";
                    $mensaje = 'Esta Unidad solo puede ser eliminada desde un perfil Administrador';
                } else {

                    if ($UnidTem->count() > 0) {
                        $estado = "ACTIVO";
                        $opc = "TR";
                        $mensaje = 'No se Puede Eliminar la Unidad, Porque esta relacionada a un Tema, Verifique...';
                    } else {

                        $estado = "ACTIVO";

                        if ($Unid->estado == "ACTIVO") {
                            $estado = "ELIMINADO";
                        } else {
                            $estado = "ACTIVO";
                        }

                        $respuesta = \App\Unidades::editarestado($id, $estado);

                        if ($respuesta) {
                            $Log = \App\Log::Guardar('Unidad Eliminada', $id);
                            if ($estado == "ELIMINADO") {

                                $mensaje = 'Operación Realizada de Manera Exitosa';
                            }
                        } else {
                            $mensaje = 'La Operación no pudo ser Realizada';
                        }
                    }
                }
            }



            if (request()->ajax()) {
                return response()->json([
                    'estado' => $estado,
                    'mensaje' => $mensaje,
                    'opc' => $opc,
                    'id' => $id,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function EliminarTema()
    {

        $mensaje = "";
        $id = request()->get('id');
        if (Auth::check()) {
            $Verf = \App\Temas::VerfDel($id);

            if($Verf->count() > 0) {
                $estado = "SINPERMISO";
                $mensaje = 'Este Contenido es Propio de la Plataforma,  No puede ser Eliminado...';
            }else{
                $Tema = \App\Temas::BuscarTema($id);

                if ($Tema->docente == "" && Auth::user()->tipo_usuario == 'Profesor') {
                    $estado = "NO";
                    $mensaje = 'Este Tema solo puede ser eliminado desde un perfil Administrador';
                } else {
                    $TemaxEval = \App\Evaluacion::BusEvalxtema($id, 'C');
    
                    if ($TemaxEval->count() > 0) {
                        $estado = "NO";
                        $mensaje = 'La Operación no pudo ser Realizada, El tema tiene evaluaciones relacionadas';
                    } else {
    
                        $estado = "ACTIVO";
                        if ($Tema->estado == "ACTIVO") {
                            $estado = "ELIMINADO";
                        } else {
                            $estado = "ACTIVO";
                        }
    
                        $respuesta = \App\Temas::editarestado($id, $estado);
    
                        if ($respuesta) {
                            if ($estado == "ELIMINADO") {
                                $Log = \App\Log::Guardar('Tema Eliminado', $id);
                                $mensaje = 'Operación Realizada de Manera Exitosa';
                            }
                        } else {
                            $mensaje = 'La Operación no pudo ser Realizada';
                        }
                    }
                }
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

    public function ElimnarPreg()
    {
        $mensaje = "";
        $id = request()->get('id');
        $tip = request()->get('tip');
        $idEval = request()->get('ideval');
        if (Auth::check()) {

            if ($tip == "PREGENSAY") {
                $respuesta = \App\EvalPregEnsay::DelPreg($id);
                $delcons = \App\CosEval::DelPreg($id);
            } else if ($tip == "COMPLETE") {
                $respuesta = \App\EvalPregComplete::DelPreg($id);
                $delcons = \App\CosEval::DelPreg($id);
            } else if ($tip == "OPCMULT") {
                $respuesta = \App\PregOpcMul::DelPregunta((int) $id);
                $respuesta = \App\OpcPregMul::DelOpciones($id);
                $delcons = \App\CosEval::DelPreg($id);
            } else if ($tip == "VERFAL") {
                $respuesta = \App\EvalVerFal::DelPreg($id);
                $delcons = \App\CosEval::DelPreg($id);
            } else if ($tip == "RELACIONE") {
                $respuesta = \App\PregRelacione::DelPreg((int) $id);
                $respuesta = \App\EvalRelacione::DelPreg($id);
                $respuesta = \App\EvalRelacioneOpc::DelPreg($id);
                $delcons = \App\CosEval::DelPreg($id);
            } else if ($tip == "TALLER") {
                $respuesta = \App\EvalTaller::EliminarArch($id);
                $delcons = \App\CosEval::DelPreg($id);
            } else {
                $respuesta = \App\EvalPregDidact::EliminarVideo($id);
                if ($respuesta) {
                    $respuesta = \App\Evaluacion::editarvideo($id, "NO");
                }
            }
            $calxdoc = "NO";

            $ConsEval = \App\CosEval::GrupPreg($idEval);

            foreach ($ConsEval as $Preg) {

                if ($Preg->tipo == "PREGENSAY" || $Preg->tipo == "COMPLETE" || $Preg->tipo == "TALLER") {

                    $calxdoc = "SI";
                }
            }

            $ModCalxdoc = \App\Evaluacion::ModifEvalCalxDoc($idEval, $calxdoc);

            if ($respuesta) {
                $Log = \App\Log::Guardar('Pregunta Eliminada', $id);
                $mensaje = 'Operación Realizada de Manera Exitosa';
            } else {
                $mensaje = 'La Operación no pudo ser Realizada';
            }
            if (request()->ajax()) {
                return response()->json([
                    'mensaje' => $mensaje,
                    'id' => $id,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function EliminarEval()
    {
        $mensaje = "";
        $icon = "";
        $id = request()->get('id');
    


        if (Auth::check()) {
            $Verf = \App\Evaluacion::VerfDel($id);

            if($Verf->count() > 0) {
                $estado = "SINPERMISO";
                $mensaje = 'Este Contenido es Propio de la Plataforma,  No puede ser Eliminado...';
            }else{
            $Eval = \App\Evaluacion::BusEval($id);

            if ($Eval->docente == "" && Auth::user()->tipo_usuario == 'Profesor') {
                $estado = "NO";
                $mensaje = 'Esta Evaluación solo puede ser eliminada desde un perfil Administrador';
                $icon = 'warning';
            } else {
                $Elib = \App\LibroCalificaciones::BusEvalDel($id);
                if ($Elib) {
                    $estado = "ACTIVO";
                    $mensaje = 'La Evaluación no puede ser Eliminada, ya que ha sido resuelta por un Estudiante';
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
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function DelImgMod()
    {

        $mensaje = "";
        $estado = "no";
        $id = request()->get('id');
        if (Auth::check()) {
            $respuesta = \App\ImgModulos::EliminarImg($id);
            if ($respuesta) {
                $mensaje = 'Operación Realizada de Manera Exitosa';
                $estado = "ok";
            } else {
                $mensaje = 'La Operación no pudo ser Realizada';
            }
            if (request()->ajax()) {
                return response()->json([
                    'id' => $id,
                    'mensaje' => $mensaje,
                    'estado' => $estado,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function DelImgAsig()
    {

        $mensaje = "";
        $estado = "no";
        $id = request()->get('id');
        if (Auth::check()) {
            $respuesta = \App\ImgAsignatura::EliminarImg($id);
            if ($respuesta) {
                $mensaje = 'Operación Realizada de Manera Exitosa';
                $estado = "ok";
            } else {
                $mensaje = 'La Operación no pudo ser Realizada';
            }
            if (request()->ajax()) {
                return response()->json([
                    'id' => $id,
                    'mensaje' => $mensaje,
                    'estado' => $estado,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function DelGrupoAsig()
    {
        if (Auth::check()) {
            $mensaje = "";
            $estado = "no";
            $id = request()->get('id');
            $idMod = request()->get('idMod');

            $Grupo = \App\Grupos::ConsultarGrupo($id, $idMod);
            $respAsig = \App\AsigProf::ConsultarGrupo($Grupo->id);
            if ($respAsig > 0) {
                $mensaje = 'No se puede Eliminar este grupo, ya que ha sido asignado a un Docente';
                $estado = "asig";
            } else {
                $respuesta = \App\Grupos::EliminarGrrupo($id, $idMod);
                if ($respuesta) {
                    $mensaje = 'Grupo Eliminado Correctamente';
                    $estado = "ok";
                } else {
                    $mensaje = 'La Operación no pudo ser Realizada';
                }
            }
            if (request()->ajax()) {
                return response()->json([
                    'mensaje' => $mensaje,
                    'estado' => $estado,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function DelPerMod()
    {

        $mensaje = "";
        $estado = "no";
        $id = request()->get('id');
        if (Auth::check()) {
            $Uniades = \App\Unidades::listarUniPerd($id);
            if ($Uniades->count() > 0) {
                $mensaje = 'No se pudo Realizar la Operación, Este Periodo tiene Uniades Relacionadas.';
                $estado = "no";
            } else {
                $respuesta = \App\Periodos::editarestado($id);
                if ($respuesta) {
                    $Log = \App\Log::Guardar('Periodo Eliminado', $id);
                    $mensaje = 'Operación Realizada de Manera Exitosa';
                    $estado = "ok";
                } else {
                    $mensaje = 'La Operación no pudo ser Realizada';
                }
            }
            if (request()->ajax()) {
                return response()->json([
                    'id' => $id,
                    'mensaje' => $mensaje,
                    'estado' => $estado,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function DelArchivos()
    {

        $mensaje = "";
        $estado = "no";
        $id = request()->get('id');
        if (Auth::check()) {
            $respuesta = \App\SubirArcTema::EliminarArch($id);
            if ($respuesta) {
                $mensaje = 'Operación Realizada de Manera Exitosa';
                $estado = "ok";
            } else {
                $mensaje = 'La Operación no pudo ser Realizada';
            }
            if (request()->ajax()) {
                return response()->json([
                    'id' => $id,
                    'mensaje' => $mensaje,
                    'estado' => $estado,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function DelAnimacion()
    {

        $mensaje = "";
        $estado = "no";
        $id = request()->get('id');
        if (Auth::check()) {
            $respuesta = \App\ContDidactico::EliminarCont($id);
            if ($respuesta) {
                $mensaje = 'Operación Realizada de Manera Exitosa';
                $estado = "ok";
            } else {
                $mensaje = 'La Operación no pudo ser Realizada';
            }
            if (request()->ajax()) {
                return response()->json([
                    'id' => $id,
                    'mensaje' => $mensaje,
                    'estado' => $estado,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function DelArchivosTaller()
    {

        $mensaje = "";
        $estado = "no";
        $id = request()->get('id');
        if (Auth::check()) {
            $respuesta = \App\EvalTaller::EliminarArch($id);
            if ($respuesta) {
                $mensaje = 'Operación Realizada de Manera Exitosa';
                $estado = "ok";
            } else {
                $mensaje = 'La Operación no pudo ser Realizada';
            }
            if (request()->ajax()) {
                return response()->json([
                    'id' => $id,
                    'mensaje' => $mensaje,
                    'estado' => $estado,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function guardarUnidad()
    {
        $bandera = "Menu4";

        if (Auth::check()) {
            $this->validate(request(), [
                'periodo' => 'required',
                'modulo' => 'required',
                'nom_unidad' => 'required',
            ], [
                'modulo.required' => 'Debe Seleccionar la Asinatura',
                'periodo.required' => 'Debe Seleccionar el Periodo',
                'nom_unidad.required' => 'Seleccione el numero de la Unidad',
            ]);
            $data = request()->all();
            $Unid = \App\Unidades::Guardar($data);
            if ($Unid) {
                $Log = \App\Log::Guardar('Unidad Guardada', $Unid->id);
                return redirect('Asignaturas/GestionUnid')->with('success', 'Datos Guardados');
            } else {
                return redirect('Asignaturas/GestionUnid')->with('error', 'Datos no Guardados');
            }
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function editarUnidad($id)
    {
        $bandera = "Menu4";
        $opc = "editar";
        $trPer = '';

        if (Auth::check()) {

            $unid = \App\Unidades::BuscarUnidad($id);
            $Asigna = \App\Modulos::listar();
            return view('Asignaturas.EditarUnidad', compact('bandera', 'unid', 'opc', 'Asigna'));
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function AsigEvaluacion($id)
    {
        $bandera = "Menu4";
        $trEval = '';
        $opc = "nuevo";

        if (Auth::check()) {
            $Tema = \App\Temas::BuscarTema($id);

            $Eval = new \App\Evaluacion();
            $Asigna = \App\Modulos::listar();
            return view('Asignaturas.AsigEvaluacion', compact('bandera', 'Tema', 'Asigna', 'Eval', 'opc'));
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function modificarUnidad($id)
    {
        if (Auth::check()) {
            $this->validate(request(), [
                'periodo' => 'required',
                'modulo' => 'required',
                'nom_unidad' => 'required',
            ], [
                'modulo.required' => 'Debe Seleccionar la Asinatura',
                'periodo.required' => 'Debe Seleccionar el Periodo',
                'nom_unidad.required' => 'Seleccione el numero de la Unidad',
            ]);
            $data = request()->all();
            $respuesta = \App\Unidades::modificar($data, $id);
            if ($respuesta) {
                $Log = \App\Log::Guardar('Unidad Modificada', $id);
                return redirect('Asignaturas/GestionUnid')->with('success', 'Datos Guardados');
            } else {
                return redirect('Asignaturas/GestionUnid')->with('error', 'Datos no Guardados');
            }
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function guardarCont()
    {
        if (Auth::check()) {
            $this->validate(request(), [
                'modulo' => 'required',
                'periodo' => 'required',
                'unidad' => 'required',
                'tip_contenido' => 'required',
                'titu_contenido' => 'required',
                'objetivo_general' => 'required',
            ], [
                'modulo.required' => 'Debe Seleccionar la Asignatura',
                'periodo.required' => 'Debe Seleccionar el Periodo',
                'unidad.required' => 'Seleccione el numero de la Unidad',
                'tip_contenido.required' => 'Seleccione el tipo de contenido',
                'titu_contenido.required' => 'Ingrese el Título del Contenido',
                'objetivo_general.required' => 'Ingrese el Objetivo General del Tema',
            ]);
            $datos = request()->all();
            $datos['ZL'] = 'NO';

            if ($datos['tip_contenido'] === "DOCUMENTO") {
                $Tem = \App\Temas::GuardarTipCont($datos);
                if ($Tem) {
                    $datos['tema_id'] = $Tem->id;
                    if (request()->hasfile('archididatico')) {
                        foreach (request()->file('archididatico') as $file) {
                            $prefijo = substr(md5(uniqid(rand())), 0, 6);
                            $name = self::sanear_string($prefijo . '_' . $file->getClientOriginalName());
                            $Titu = $file->getClientOriginalName();
                            $file->move(public_path() . '/app-assets/Contenido_Didactico/', $name);
                            $arch[] = $name;
                            $archTit[] = $Titu;
                        }
                        $datos['archi'] = $arch;
                        $datos['TituAnim'] = $archTit;
                    }

                    $ContTema = \App\DesarrollTema::GuardarContTema($datos);
                    if (request()->hasfile('archididatico')) {
                        $ContTemaDidac = \App\ContDidactico::GuardarContDidctico($datos);
                    }
                    $CompartirTemaDoc = \App\TemasDocentes::GuardarCompTema($datos);

                    if ($ContTema) {
                        $Log = \App\Log::Guardar('Tema Guardado', $Tem->id);
                        return redirect('Asignaturas/GestionTem')->with('success', 'Datos Guardados');
                    } else {
                        return redirect('Asignaturas/GestionTem')->with('error', 'Datos no Guardados');
                    }
                } else {
                    return redirect('Asignaturas/GestionTem')->with('error', 'Datos no Guardados');
                }
            } else if ($datos['tip_contenido'] === "CONTENIDO DIDACTICO") {

                $Tem = \App\Temas::GuardarTipCont($datos);
                if ($Tem) {
                    $datos['tema_id'] = $Tem->id;
                    if (request()->hasfile('archiVideo')) {
                        foreach (request()->file('archiVideo') as $file) {
                            $prefijo = substr(md5(uniqid(rand())), 0, 6);
                            $name = self::sanear_string($prefijo . '_' . $file->getClientOriginalName());
                            $Titu = $file->getClientOriginalName();
                            $file->move(public_path() . '/app-assets/Contenido_Didactico/', $name);
                            $arch[] = $name;
                            $archTit[] = $Titu;
                        }
                        $datos['archi'] = $arch;
                        $datos['TituAnim'] = $archTit;
                    }

                    $ContTemaDidac = \App\ContDidactico::GuardarContDidctico($datos);
                    $CompartirTemaDoc = \App\TemasDocentes::GuardarCompTema($datos);

                    if ($ContTemaDidac) {
                        $Log = \App\Log::Guardar('Tema Guardado', $Tem->id);
                        return redirect('Asignaturas/GestionTem')->with('success', 'Datos Guardados');
                    } else {
                        return redirect('Asignaturas/GestionTem')->with('error', 'Datos no Guardados');
                    }
                } else {
                    return redirect('Asignaturas/GestionTem')->with('error', 'Datos no Guardados');
                }
            } else if ($datos['tip_contenido'] === "ARCHIVO") {

                $Tem = \App\Temas::GuardarTipCont($datos);
                if ($Tem) {
                    $datos['tema_id'] = $Tem->id;

                    if (request()->hasfile('archi')) {

                        foreach (request()->file('archi') as $file) {
                            $name = $file->getClientOriginalName();
                            $file->move(public_path() . '/app-assets/Archivos_Contenidos/', $name);
                            $arch[] = $name;
                        }
                    }
                    $datos['archi'] = $arch;
                    $ContTemaArc = \App\SubirArcTema::GuardarArchCont($datos);
                    $CompartirTemaDoc = \App\TemasDocentes::GuardarCompTema($datos);

                    if ($ContTemaArc) {
                        $Log = \App\Log::Guardar('Tema Guardado', $Tem->id);
                        return redirect('Asignaturas/GestionTem')->with('success', 'Datos Guardados');
                    } else {
                        return redirect('Asignaturas/GestionTem')->with('error', 'Datos no Guardados');
                    }
                } else {
                    return redirect('Asignaturas/GestionTem')->with('error', 'Datos no Guardados');
                }
            } else if ($datos['tip_contenido'] === "LINK") {
                $Tem = \App\Temas::GuardarTipCont($datos);
                if ($Tem) {
                    $datos['tema_id'] = $Tem->id;

                    $ContTemaLink = \App\DesarrolloLink::Guardar($datos);
                    $CompartirTemaDoc = \App\TemasDocentes::GuardarCompTema($datos);

                    if ($ContTemaLink) {
                        $Log = \App\Log::Guardar('Tema Guardado', $Tem->id);
                        return redirect('Asignaturas/GestionTem')->with('success', 'Datos Guardados');
                    } else {
                        return redirect('Asignaturas/GestionTem')->with('error', 'Datos no Guardados');
                    }
                } else {
                    return redirect('Asignaturas/GestionTem')->with('error', 'Datos no Guardados');
                }
            }

           
            


        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
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
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function GuardarEvalFin()
    {
        if (Auth::check()) {
            $datos = request()->all();

            $idEval = "";
            $idEval = request()->get('Id_Eval');
            $ContEval = \App\Evaluacion::ModifEvalFin($datos, $idEval);
            if ($ContEval) {
                if (request()->ajax()) {
                    return response()->json([
                        'idEval' => $idEval,
                    ]);
                }
            }

            $Log = \App\Log::Guardar('Evaluación Modificada', $idEval);
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function VideoEval()
    {
        if (Auth::check()) {
            $datos = request()->all();
            $datos['animacion'] = "SI";

            $idEval = "";
            $calxdoc = "NO";
            if ($datos['Id_Eval'] === null) {
                $ContEval = \App\Evaluacion::Guardar($datos, $calxdoc);
                $idEval = $ContEval->id;
            } else {
                $idEval = request()->get('Id_Eval');
                $ContEval = \App\Evaluacion::ModifEval($datos, $idEval, $calxdoc);
            }

            $ConsEval = \App\CosEval::GrupPreg($idEval);

            foreach ($ConsEval as $Preg) {
                if ($Preg->tipo == "PREGENSAY" || $Preg->tipo == "COMPLETE" || $Preg->tipo == "TALLER") {
                    $calxdoc = "SI";
                }
            }
            $ModCalxdoc = \App\Evaluacion::ModifEvalCalxDoc($idEval, $calxdoc);

            if (request()->hasfile('archiVideo')) {
                $prefijo = substr(md5(uniqid(rand())), 0, 6);
                $name = self::sanear_string($prefijo . '_' . request()->file('archiVideo')->getClientOriginalName());
                request()->file('archiVideo')->move(public_path() . '/app-assets/Evaluacion_PregDidact/', $name);
                $datos['archivo'] = $name;
                if ($datos['id-video'] === null) {
                    $EvPDidact = \App\EvalPregDidact::Guardar($datos, $idEval);
                } else {
                    $EvPDidact = \App\EvalPregDidact::Modificar($datos, $idEval);
                    $EvPDidact = \App\EvalPregDidact::PregDida($idEval);
                }
            }

            if (request()->ajax()) {
                return response()->json([
                    'idEval' => $idEval,
                    'EvPDidact' => $EvPDidact,
                ]);
            }
        }
    }

    public function ModificarTema($id)
    {
        if (Auth::check()) {
            $this->validate(request(), [
                'modulo' => 'required',
                'periodo' => 'required',
                'unidad' => 'required',
                'tip_contenido' => 'required',
            ], [
                'modulo.required' => 'Debe Seleccionar la Asinatura',
                'periodo.required' => 'Debe Seleccionar el Periodo',
                'unidad.required' => 'Seleccione el numero de la Unidad',
                'tip_contenido.required' => 'Seleccione el tipo de contenido',
            ]);

            $datos = request()->all();

            $datos['tema_id'] = $id;
            $datos['ZL'] = 'NO';

            if ($datos['tip_contenido'] === "DOCUMENTO") {

                $Tem = \App\Temas::Modificar($datos, $id);

                if (request()->hasfile('archididatico')) {
                    foreach (request()->file('archididatico') as $file) {
                        $prefijo = substr(md5(uniqid(rand())), 0, 6);
                        $name = self::sanear_string($prefijo . '_' . $file->getClientOriginalName());
                        $Titu = $file->getClientOriginalName();
                        $file->move(public_path() . '/app-assets/Contenido_Didactico/', $name);
                        $arch[] = $name;
                        $archTit[] = $Titu;
                    }
                    $datos['archi'] = $arch;
                    $datos['TituAnim'] = $archTit;
                }
                $CompartirTemaDoc = \App\TemasDocentes::GuardarCompTema($datos);

                if ($Tem) {
                    $ContTema = \App\DesarrollTema::Modificar($datos, $id);
                    if (request()->hasfile('archididatico')) {
                        $ContTemaDidac = \App\ContDidactico::modificar($datos, $id);
                    }
                  


                    if ($ContTema) {
                        $Log = \App\Log::Guardar('Tema Modificado', $id);
                        return redirect('Asignaturas/GestionTem')->with('success', 'Datos Guardados');
                    } else {
                        return redirect('Asignaturas/GestionTem')->with('error', 'Datos no Guardados');
                    }
                } else {
                    return redirect('Asignaturas/GestionTem')->with('error', 'Datos no Guardados');
                }
            } else if ($datos['tip_contenido'] === "CONTENIDO DIDACTICO") {

                $Tem = \App\Temas::Modificar($datos, $id);
                $CompartirTemaDoc = \App\TemasDocentes::GuardarCompTema($datos);

                if ($Tem) {
                    $datos['tema_id'] = $id;
                    if (request()->hasfile('archiVideo')) {
                        foreach (request()->file('archiVideo') as $file) {
                            $prefijo = substr(md5(uniqid(rand())), 0, 6);
                            $name = self::sanear_string($prefijo . '_' . $file->getClientOriginalName());
                            $Titu = $file->getClientOriginalName();
                            $file->move(public_path() . '/app-assets/Contenido_Didactico/', $name);
                            $arch[] = $name;
                            $archTit[] = $Titu;
                        }
                        $datos['archi'] = $arch;
                        $datos['TituAnim'] = $archTit;

                        $ContTemaDidac = \App\ContDidactico::modificar($datos, $id);
                        if ($ContTemaDidac) {
                            $Log = \App\Log::Guardar('Tema Modificado', $id);
                            return redirect('Asignaturas/GestionTem')->with('success', 'Datos Guardados');
                        } else {
                            return redirect('Asignaturas/GestionTem')->with('error', 'Datos no Guardados');
                        }
                    } else {
                        $Log = \App\Log::Guardar('Tema Modificado', $id);
                        return redirect('Asignaturas/GestionTem')->with('success', 'Datos Guardados');
                    }
                } else {
                    return redirect('Asignaturas/GestionTem')->with('error', 'Datos no Guardados');
                }
            } else if ($datos['tip_contenido'] === "ARCHIVO") {
                $Tem = \App\Temas::Modificar($datos, $id);
                $CompartirTemaDoc = \App\TemasDocentes::GuardarCompTema($datos);

                if (request()->hasfile('archi')) {

                    foreach (request()->file('archi') as $file) {
                        $name = $file->getClientOriginalName();
                        $file->move(public_path() . '/app-assets/Archivos_Contenidos/', $name);
                        $arch[] = $name;
                    }
                    $datos['archi'] = $arch;
                    $ContTemaArc = \App\SubirArcTema::GuardarArchCont($datos);

                    if ($ContTemaArc) {
                        $Log = \App\Log::Guardar('Tema Modificado', $id);
                        return redirect('Asignaturas/GestionTem')->with('success', 'Datos Guardados');
                    } else {
                        return redirect('Asignaturas/GestionTem')->with('error', 'Datos no Guardados');
                    }
                } else {
                    $Log = \App\Log::Guardar('Tema Modificado', $id);
                    return redirect('Asignaturas/GestionTem')->with('success', 'Datos Guardados');
                }
            } else if ($datos['tip_contenido'] === "LINK") {
                $Tem = \App\Temas::Modificar($datos, $id);
                $CompartirTemaDoc = \App\TemasDocentes::GuardarCompTema($datos);

                if ($Tem) {

                    $ContTemaLink = \App\DesarrolloLink::Modificar($datos, $id);

                    if ($ContTemaLink) {
                        $Log = \App\Log::Guardar('Tema Modificado', $id);
                        return redirect('Asignaturas/GestionTem')->with('success', 'Datos Guardados');
                    } else {
                        return redirect('Asignaturas/GestionTem')->with('error', 'Datos no Guardados');
                    }
                } else {
                    return redirect('Asignaturas/GestionTem')->with('error', 'Datos no Guardados');
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
