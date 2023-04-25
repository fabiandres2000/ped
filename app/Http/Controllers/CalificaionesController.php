<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CalificaionesController extends Controller
{

    public function GestionCalificaciones($id)
    {
        if (Auth::check()) {
            $busqueda = request()->get('txtbusqueda');
            $actual = request()->get('page');
            if ($actual == null || $actual == "") {
                $actual = 1;
            }
            $limit = 10;
            if (Session::get('TIPCONT') == 'ASI') {
                $Eval = \App\Evaluacion::Gestion($busqueda, $actual, $limit, $id);
                $numero_filas = \App\Evaluacion::numero_de_registros(request()->get('txtbusqueda'), $id);
                $Periodo = \App\Periodos::listar(Session::get('IDMODULO'));
            } else {
                $Eval = \App\Evaluacion::GestionMod($busqueda, $actual, $limit, $id);
                $numero_filas = \App\Evaluacion::numero_de_registrosMod(request()->get('txtbusqueda'), $id);
                $Periodo = \App\PeriodosModTransv::listar(Session::get('IDMODULO'));
            }

            $paginas = ceil($numero_filas / $limit); //$numero_filas/10;
            $PanelActivos = self::PanelActivos();
            $PanelNotiEval = self::PanelNotiEval();

            $Select_Peri = "<option value='' selected>Seleccione</option>";
            foreach ($Periodo as $Perio) {
                $Select_Peri .= "<option value='$Perio->id' >" . strtoupper($Perio->des_periodo) . "</option>";
            }

            return view('Calificaciones.GestionCalif', compact('numero_filas', 'paginas', 'actual', 'limit', 'busqueda', 'Eval', 'Select_Peri'));
        } else {
            return redirect("/")->with("error", "Su sesion ha terminado");
        }
    }

    public function PanelNotiEval()
    {
        $NumNoti = 0;
        $lisNoti = "";
        $lisEval = "";
        $listUsuNoCo = "";

        if (Session::get('TIPCONT') == 'ASI') {
            $Alumnos = \App\Profesores::alumnos();
            $or = "C";
        } else {
            $Alumnos = \App\Profesores::alumnosMod();
            $or = "M";
        }

        foreach ($Alumnos as $Alu) {
            $AlumNotif = \App\ComentTemas::ConsultarDoce($Alu->usuario, $or);
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
            if (Session::get('TIPCONT') == 'ASI') {
                $AlumEval = \App\LibroCalificaciones::BuscEvalPend($Alu->usuario);
            } else {
                $AlumEval = \App\LibroCalificaciones::BuscEvalPendMod($Alu->usuario, $or);
            }

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

    public function GestionLibroCalificaciones($id)
    {
        if (Auth::check()) {
            $busqueda = request()->get('txtbusqueda');
            $actual = request()->get('page');
            if ($actual == null || $actual == "") {
                $actual = 1;
            }
            $limit = 5;
            if (Session::get('TIPCONT') == 'ASI') {
                $DetMod = \App\Modulos::Desmodulo($id);
            } else {
                $DetMod = \App\GradosModulos::Desmodulo($id);
            }

            $Eval = \App\Alumnos::GestionAlumnosEval($busqueda, $actual, $limit, $DetMod->grado_modulo);
            $numero_filas = \App\Alumnos::numero_de_registrosAlumnosEval(request()->get('txtbusqueda'), $DetMod->grado_modulo);
            $paginas = ceil($numero_filas / $limit); //$numero_filas/10;
            $PanelActivos = self::PanelActivos();
            $PanelNotiEval = self::PanelNotiEval();

            $Periodo = \App\Periodos::listar(Session::get('IDMODULO'));
            //            dd($Periodo);die();
            $Select_Peri = "<option value='' selected>Todos</option>";
            $Select_Unid = "<option value='' selected>Todas</option>";
            $Select_Tem = "<option value='' selected>Todos</option>";
            foreach ($Periodo as $Perio) {
                $Select_Peri .= "<option value='$Perio->id' >" . strtoupper($Perio->des_periodo) . "</option>";
            }

            return view('Calificaciones.GestionLibroCalif', compact('numero_filas', 'paginas', 'actual', 'limit', 'busqueda', 'Eval', 'Select_Peri', 'Select_Unid', 'Select_Tem'));
        } else {
            return redirect("/")->with("error", "Su sesion ha terminado");
        }
    }

    public function PonderacionNotas($id)
    {
        if (Auth::check()) {
            $tponde = "Manual";
            $PanelActivos = self::PanelActivos();
            $PanelNotiEval = self::PanelNotiEval();

            $Periodo = \App\Periodos::listarPerPond($id);
            $CantReg = $Periodo->count();
            $infpe = $Periodo->last();
            $tponde = $infpe->tponde;
            return view('Calificaciones.PonderacionNotas', compact('Periodo', 'CantReg', 'tponde'));
        } else {
            return redirect("/")->with("error", "Su sesion ha terminado");
        }
    }

    public function ListUnidades()
    {
        $idPeri = request()->get('idPer');
        if (Auth::check()) {
            $tponde = "Manual";
            $Periodo = \App\Periodos::BuscarPerido($idPeri);
            $Unidades = \App\Unidades::listarPond($idPeri);
            $CantReg = $Unidades->count();
            $infpe = $Unidades->last();
            $tponde = $infpe->tponde;

            if (request()->ajax()) {
                return response()->json([
                    'Unidades' => $Unidades,
                    'DesPeriodo' => $Periodo->des_periodo,
                    'CantReg' => $CantReg,
                    'tponde' => $tponde,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su sesion ha terminado");
        }
    }

    public function PorcPer()
    {
        $data = request();
        if (Auth::check()) {
            $Periodo = \App\PondPeriodos::Guardar($data);

            if ($Periodo) {
                $Mensaje = "Ponderación a Periodos Guardada";
                $Opc = "1";
            } else {
                $Mensaje = "La Ponderación no pudo ser Guardada";
                $Opc = "0";
            }
            if (request()->ajax()) {
                return response()->json([
                    'Mensaje' => $Mensaje,
                    'Opc' => $Opc,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su sesion ha terminado");
        }
    }

    public function PorcUni()
    {
        $data = request();
        if (Auth::check()) {
            $Periodo = \App\PondUnidades::Guardar($data);

            if ($Periodo) {
                $Mensaje = "Ponderación a Unidades Guardada";
                $Opc = "1";
            } else {
                $Mensaje = "La Ponderación no pudo ser Guardada";
                $Opc = "0";
            }
            if (request()->ajax()) {
                return response()->json([
                    'Mensaje' => $Mensaje,
                    'Opc' => $Opc,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su sesion ha terminado");
        }
    }

    public function PorcTema()
    {
        $data = request();
        if (Auth::check()) {
            $Periodo = \App\PondTemas::Guardar($data);

            if ($Periodo) {
                $Mensaje = "Ponderación a Temas Guardada";
                $Opc = "1";
            } else {
                $Mensaje = "La Ponderación no pudo ser Guardada";
                $Opc = "0";
            }
            if (request()->ajax()) {
                return response()->json([
                    'Mensaje' => $Mensaje,
                    'Opc' => $Opc,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su sesion ha terminado");
        }
    }

    public function PorcEval()
    {
        $data = request();
        if (Auth::check()) {
            $Periodo = \App\PondEval::Guardar($data);

            if ($Periodo) {
                $Mensaje = "Ponderación a Evaluaciones Guardada";
                $Opc = "1";
            } else {
                $Mensaje = "La Ponderación no pudo ser Guardada";
                $Opc = "0";
            }
            if (request()->ajax()) {
                return response()->json([
                    'Mensaje' => $Mensaje,
                    'Opc' => $Opc,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su sesion ha terminado");
        }
    }

    public function ListTemas()
    {
        $idUnidad = request()->get('idUni');
        if (Auth::check()) {
            $Unidades = \App\Unidades::BuscarUnidad($idUnidad);
            $Temas = \App\Temas::listarPond($idUnidad);
            $CantReg = $Temas->count();
            $infpe = $Temas->last();
            $tponde = $infpe->tponde;

            if (request()->ajax()) {
                return response()->json([
                    'Temas' => $Temas,
                    'DesUnidad' => $Unidades->nom_unidad . ' (' . $Unidades->des_unidad . ')',
                    'CantReg' => $CantReg,
                    'tponde' => $tponde,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su sesion ha terminado");
        }
    }

    public function ListEval()
    {
        $idTema = request()->get('idTem');
        if (Auth::check()) {
            $Temas = \App\Temas::BuscarTema($idTema);
            $Eval = \App\Evaluacion::listarPond($idTema);

            $CantReg = $Eval->count();
            $infpe = $Eval->last();
            $tponde = $infpe->tponde;

            if (request()->ajax()) {
                return response()->json([
                    'Eval' => $Eval,
                    'DesTema' => $Temas->titu_contenido,
                    'CantReg' => $CantReg,
                    'tponde' => $tponde,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su sesion ha terminado");
        }
    }

    public function EvalAlumnos($id)
    {

        if (Auth::check()) {

            if (Session::get('TIPCONT') == "ASI") {
                $InfEval = \App\Evaluacion::DatosEvla($id, 'IFEVAL');
            } else {
                $InfEval = \App\Evaluacion::DatosEvalMod($id, 'IFEVAL');
            }

            $Alumno = \App\LibroCalificaciones::Buscar($InfEval);
            $PanelActivos = self::PanelActivos();
            $PanelNotiEval = self::PanelNotiEval();

            return view('Calificaciones.EvaluarAlumnos', compact('Alumno', 'InfEval', 'id'));
        } else {
            return redirect("/")->with("error", "Su sesion ha terminado");
        }
    }

    public function CalifAlumno($id)
    {

        if (Auth::check()) {

            if (Session::get('TIPCONT') == 'ASI') {
                $or = "C";
                $Periodo = \App\Periodos::listar($id);
            } else {
                $or = "M";
                $Periodo = \App\PeriodosModTransv::listar($id);
            }
            $Alumno = \App\LibroCalificaciones::BuscarEvalxAlumn($id, $or);
            $PanelActivos = self::PanelActivos();
            $PanelNotiEval = self::PanelNotiEval();

            $Select_Peri = "<option value='' selected>Todos</option>";
            $Select_Unid = "<option value='' selected>Todas</option>";
            $Select_Tem = "<option value='' selected>Todos</option>";
            foreach ($Periodo as $Perio) {
                $Select_Peri .= "<option value='$Perio->id' >" . strtoupper($Perio->des_periodo) . "</option>";
            }

            return view('Calificaciones.CalificacionesAlumno', compact('Alumno', 'Select_Peri', 'Select_Unid', 'Select_Tem'));
        } else {
            return redirect("/")->with("error", "Su sesion ha terminado");
        }
    }

    public function VerEvaluacion()
    {

        if (Auth::check()) {

            $ideva = request()->get('idEval');

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

            $PanelActivos = self::PanelActivos();
            $PanelNotiEval = self::PanelNotiEval();

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
            return redirect("/")->with("error", "Su sesion ha terminado");
        }
    }

    public function ListCalifEva()
    {

        if (Auth::check()) {
            $id = request()->get('idAlumno');
            $Dat = \App\LibroCalificaciones::DatosEvalAlumno($id);
            $PanelActivos = self::PanelActivos();
            $PanelNotiEval = self::PanelNotiEval();
            if (request()->ajax()) {
                return response()->json([
                    'Dat' => $Dat,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su sesion ha terminado");
        }
    }

    public function ListCalifEva2()
    {

        if (Auth::check()) {
            $id = request()->get('idAlumno2');
            $idTem = request()->get('idTem');
            $idPer = request()->get('idPer');
            $idUni = request()->get('idUnid');
            $Eval = request()->get('evalCal');

            $Dat = \App\LibroCalificaciones::DatosEvalAlumno2($id, $idTem, $idPer, $idUni, $Eval);
            $PanelActivos = self::PanelActivos();
            $PanelNotiEval = self::PanelNotiEval();
            if (request()->ajax()) {
                return response()->json([
                    'Dat' => $Dat,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su sesion ha terminado");
        }
    }

    public function ConsListEval()
    {
        if (Auth::check()) {
            $id = request()->get('idEval');
            if (Session::get('TIPCONT') == 'ASI') {
                $InfEval = \App\Evaluacion::DatosEvla($id, 'IFEVAL');

            }else{
                $InfEval = \App\Evaluacion::DatosEvalMod($id, 'IFEVAL');

            }

            $Alumno = \App\LibroCalificaciones::Buscar($InfEval);

            if (request()->ajax()) {
                return response()->json([
                    'InfEval' => $InfEval,
                    'Alumno' => $Alumno,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su sesion ha terminado");
        }
    }

    public function ConsulContEval()
    {
        $PanelActivos = self::PanelActivos();
        $PanelNotiEval = self::PanelNotiEval();
        if (Auth::check()) {
            $id = request()->get('idRespEval');

            $DesEva = \App\LibroCalificaciones::BusDetLib($id);

            $ideva = $DesEva->evaluacion;

            $titulo = $DesEva->titulo;
            $intentos = \App\UpdIntEval::ConsulInt($ideva, $DesEva->alumno);
            $enunciado = $DesEva->enunciado;

            $tiempo = $DesEva->tiempo;
            $perfil = Auth::user()->tipo_usuario;

            $Log = \App\Log::Guardar('Calificación de Evaluación', $ideva);

            $PregEval = \App\CosEval::GrupPreg($ideva);

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
                    'titulo' => $titulo,
                    'int_perm' => $intentos->int_realizados,
                    'enunciado' => $enunciado,
                    'tiempo' => $tiempo,
                    'perfil' => $perfil,
                    'Evaluacion' => $DesEva,
                    'PregEval' => $PregEval,
                    'VideoEval' => $video,
                    'idvideo' => $id,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su sesion ha terminado");
        }
    }

    public function consulPregAlumno()
    {
        if (Auth::check()) {
            $IdPreg = request()->get('Pregunta');
            $TipPreg = request()->get('TipPregunta');
            $IdLib = request()->get('IdLibCalif');

            $DesEva = \App\LibroCalificaciones::BusDetLib($IdLib);

            if ($TipPreg == "PREGENSAY") {
                $PregEnsayo = \App\EvalPregEnsay::consulPregEnsay($IdPreg);
                $RespPregEnsayo = \App\RespEvalEnsay::DesResp($IdPreg, $DesEva->alumno);
                $PuntPreg = \App\PuntPreg::ConsulPunt($IdPreg, $DesEva->alumno);
                $Retro = \App\Retroalimentacion::ConsulRetro($IdPreg, $DesEva->alumno);
                if (request()->ajax()) {
                    return response()->json([
                        'PregEnsayo' => $PregEnsayo,
                        'RespPregEnsayo' => $RespPregEnsayo,
                        'PuntAct' => $PuntPreg->puntos,
                        'Retro' => $Retro,
                    ]);
                }
            } else if ($TipPreg == "COMPLETE") {
                $PregComple = \App\EvalPregComplete::ConsultComplete($IdPreg);
                $RespPregComple = \App\RespEvalComp::DesResp($IdPreg, $DesEva->alumno);
                $PuntPreg = \App\PuntPreg::ConsulPunt($IdPreg, $DesEva->alumno);
                $Retro = \App\Retroalimentacion::ConsulRetro($IdPreg, $DesEva->alumno);
                if (request()->ajax()) {
                    return response()->json([
                        'PregComple' => $PregComple,
                        'RespPregComple' => $RespPregComple,
                        'PuntAct' => $PuntPreg->puntos,
                        'Retro' => $Retro,
                    ]);
                }
            } else if ($TipPreg == "OPCMULT") {
                $PregMult = \App\PregOpcMul::ConsulPreg($IdPreg);
                $OpciMult = \App\OpcPregMul::ConsulGrupOpcPreg($IdPreg);
                $RespPregMul = \App\OpcPregMul::BuscOpcResp($IdPreg, $DesEva->alumno);
                $PuntPreg = \App\PuntPreg::ConsulPunt($IdPreg, $DesEva->alumno);
                $Retro = \App\Retroalimentacion::ConsulRetro($IdPreg, $DesEva->alumno);

                if (request()->ajax()) {
                    return response()->json([
                        'PregMult' => $PregMult,
                        'OpciMult' => $OpciMult,
                        'RespPregMul' => $RespPregMul,
                        'PuntAct' => $PuntPreg->puntos,
                        'Retro' => $Retro,
                    ]);
                }
            } else if ($TipPreg == "VERFAL") {
                $PregVerFal = \App\EvalVerFal::ConVerFal($IdPreg);
                $RespPregVerFal = \App\EvalVerFal::VerFalResp($IdPreg, $DesEva->alumno);
                $PuntPreg = \App\PuntPreg::ConsulPunt($IdPreg, $DesEva->alumno);
                $Retro = \App\Retroalimentacion::ConsulRetro($IdPreg, $DesEva->alumno);

                if (request()->ajax()) {
                    return response()->json([
                        'PregVerFal' => $PregVerFal,
                        'RespPregVerFal' => $RespPregVerFal,
                        'PuntAct' => $PuntPreg->puntos,
                        'Retro' => $Retro,
                    ]);
                }
            } else if ($TipPreg == "RELACIONE") {
                $PregRelacione = \App\PregRelacione::ConRela($IdPreg);
                $PregRelIndi = \App\EvalRelacione::PregRelDef($IdPreg);
                $PregRelResp = \App\EvalRelacioneOpc::PregRelOpc($IdPreg);
                $PregRelRespAdd = \App\EvalRelacioneOpc::PregRelOpcAdd($IdPreg);

                $RespPregRelacione = \App\RespEvalRelacione::RelacResp($IdPreg, $DesEva->alumno);
                $PuntPreg = \App\PuntPreg::ConsulPunt($IdPreg, $DesEva->alumno);
                $Retro = \App\Retroalimentacion::ConsulRetro($IdPreg, $DesEva->alumno);

                if (request()->ajax()) {
                    return response()->json([
                        'PregRelacione' => $PregRelacione,
                        'PregRelIndi' => $PregRelIndi,
                        'PregRelResp' => $PregRelResp,
                        'PregRelRespAdd' => $PregRelRespAdd,
                        'RespPregRelacione' => $RespPregRelacione,
                        'PuntAct' => $PuntPreg->puntos,
                        'Retro' => $Retro,

                    ]);
                }
            } else if ($TipPreg == "TALLER") {
                $PregTaller = \App\EvalTaller::PregTaller($IdPreg);
                $RespPregTaller = \App\RespEvalTaller::RespEvalTallerAlum($IdPreg, $DesEva->alumno);
                $PuntPreg = \App\PuntPreg::ConsulPunt($IdPreg, $DesEva->alumno);
                $Retro = \App\Retroalimentacion::ConsulRetro($IdPreg, $DesEva->alumno);
                if (request()->ajax()) {
                    return response()->json([
                        'PregTaller' => $PregTaller,
                        'RespPregTaller' => $RespPregTaller,
                        'PuntAct' => $PuntPreg->puntos,
                        'Retro' => $Retro,
                    ]);
                }
            }
        } else {
            return redirect("/")->with("error", "Su sesion ha terminado");
        }
    }

    public function GuardarPuntPreg()
    {
        if (Auth::check()) {

            $Alumno = request()->get('IdAlum');
            $Eval = request()->get('IdEvaluacion');
            $Pregunta = request()->get('Pregunta');
            $Puntaje = request()->get('Puntaje');
            $PMax = request()->get('PMax');
            $NPreg = request()->get('nPregunta');
            $Retroalimentacion = request()->get('Resptroalimentacion');

            $PunPreg = \App\PuntPreg::UpdatePuntPreg($Eval, $Pregunta, $Alumno, $Puntaje);

            $PuntEval = \App\PuntPreg::ConsulPuntEval($Eval, $Alumno);
            $Puntaje = 0;

            foreach ($PuntEval as $punt) {
                $Puntaje = $Puntaje + $punt->puntos;
            }

            $LibroCalif = \App\LibroCalificaciones::UpdatePunt($Eval, $Alumno, $Puntaje, $PMax, $NPreg);

            $Retro = \App\Retroalimentacion::Guardar($Eval, $Pregunta, $Alumno, $Retroalimentacion);

            if ($NPreg === "Ultima") {
                if(Session::get('TIPCONT') == 'ASI'){
                    $InfEval = \App\Evaluacion::DatosEvla($Eval, 'IFEVAL');
                }else{
                $InfEval = \App\Evaluacion::DatosEvalMod($Eval, 'IFEVAL');
                
                }

                $Alumno = \App\LibroCalificaciones::Buscar($InfEval);

                if (request()->ajax()) {
                    return response()->json([
                        'Alumno' => $Alumno,
                    ]);
                }
            }
        } else {
            return redirect("/")->with("error", "Su sesion ha terminado");
        }
    }

    public function GuardarCalEval()
    {

        if (Auth::check()) {
            $id = request()->get('idRespEval');
            $calif = request()->get('calif');
            $califVis = request()->get('califVis');
            $Eva = request()->get('Evalu');
            $Flag = "no";
            $fecha = date('Y-m-d  H:i:s');
            $InfEval = \App\Evaluacion::DatosEvla($Eva, 'IFEVAL');
            if ($InfEval->tip_evaluacion == "PREGENSAY") {
                $Resp = \App\RespEvalEnsay::GuarEvalCal($id, $calif, $califVis);
            } else if ($InfEval->tip_evaluacion == "DIDACTICO") {
                $Resp = \App\RespEvalDida::GuarEvalCal($id, $calif, $califVis);
            } else if ($InfEval->tip_evaluacion == "COMPLETE") {
                $Resp = \App\RespEvalComp::GuarEvalCal($id, $calif, $califVis);
            } else if ($InfEval->tip_evaluacion == "TALLER") {
                $Resp = \App\RespEvalTaller::GuarEvalCal($id, $calif, $califVis);
            }

            $LibroCalif = \App\LibroCalificaciones::Guardar($Eva, $Resp, $InfEval, $fecha, Session::get('IDDOCE'));
            $Alumno = \App\LibroCalificaciones::Buscar($InfEval);
            if ($Resp) {
                $Flag = "si";
            }

            $Log = \App\Log::Guardar('Calificación de Evaluación Guardada', $Eva);

            if (request()->ajax()) {
                return response()->json([
                    'Alumno' => $Alumno,
                    'PuntMax' => $InfEval->punt_max,
                    'Flag' => $Flag,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su sesion ha terminado");
        }
    }

    public function GuardarCalEvalGrupPreg()
    {
        if (Auth::check()) {
            $datos = request()->all();
            $Eva = request()->get('ideval');

            $fecha = date('Y-m-d  H:i:s');
            $InfEval = \App\Evaluacion::DatosEvla($Eva, 'IFEVAL');
            $Resp = \App\RespGrupPreg::RegistCalf($datos);
            $InfEval['OriEva'] = "Profesor";
            $Resp['CalfVis'] = request()->get('CalfVis');
            $Resp['Calf'] = request()->get('Calf');
            $LibroCalif = \App\LibroCalificaciones::Guardar($Eva, $Resp, $InfEval, $fecha, Session::get('IDDOCE'));
            $Alumno = \App\LibroCalificaciones::Buscar($InfEval);

            if (request()->ajax()) {
                return response()->json([
                    'Alumno' => $Alumno,
                    'PuntMax' => $InfEval->punt_max,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su sesion ha terminado");
        }
    }

    public function ConsulRetroalimentacion()
    {
        if (Auth::check()) {

            $Eva = request()->get('idEvalRetro');

            $Retroalimentacion = \App\PuntPreg::ConsulRetro($Eva);

            foreach ($Retroalimentacion as $Retro) {

                if ($Retro->tipo == "PREGENSAY") {

                    $PuntMax = \App\EvalPregEnsay::consulPregEnsay($Retro->pregunta);

                    $puntmax = $PuntMax->puntaje;

                    $promPunt = ($Retro->puntos / $puntmax) * 100;

                    $Retro->promPunt = $promPunt;
                } else if ($Retro->tipo == "COMPLETE") {
                    $PuntMax = \App\EvalPregComplete::ConsultComplete($Retro->pregunta);
                    $puntmax = $PuntMax->puntaje;
                    $promPunt = ($Retro->puntos / $puntmax) * 100;
                    $Retro->promPunt = $promPunt;
                } else if ($Retro->tipo == "TALLER") {
                    $PuntMax = \App\EvalTaller::PregTaller($Retro->pregunta);

                    $puntmax = $PuntMax->puntaje;
                    $promPunt = ($Retro->puntos / $puntmax) * 100;
                    $Retro->promPunt = $promPunt;
                } else {
                    if ($Retro->puntos > 0) {
                        $Retro->promPunt = 100;
                    } else {
                        $Retro->promPunt = 0;
                    }
                }
            }

            if (request()->ajax()) {
                return response()->json([
                    'Retro' => $Retroalimentacion,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su sesion ha terminado");
        }
    }

    public function VerRespAlumno()
    {
        if (Auth::check()) {

            $IdPreg = request()->get('PreguntaResp');
            $Eval = request()->get('idEvaVerResp');
            $Pregunta = \App\CosEval::ConsulPreg($IdPreg, $Eval);

            if ($Pregunta->tipo == "PREGENSAY") {
                $PregEnsayo = \App\EvalPregEnsay::consulPregEnsay($IdPreg);
                $RespPregEnsayo = \App\RespEvalEnsay::DesResp($IdPreg, Auth::user()->id);
                if (request()->ajax()) {
                    return response()->json([
                        'PregEnsayo' => $PregEnsayo,
                        'RespPregEnsayo' => $RespPregEnsayo,
                        'tipo' => $Pregunta->tipo,
                        'retro' => $Pregunta->retro,

                    ]);
                }
            } else if ($Pregunta->tipo == "COMPLETE") {
                $PregComple = \App\EvalPregComplete::ConsultComplete($IdPreg);
                $RespPregComple = \App\RespEvalComp::DesResp($IdPreg, Auth::user()->id);

                if (request()->ajax()) {
                    return response()->json([
                        'PregComple' => $PregComple,
                        'RespPregComple' => $RespPregComple,
                        'tipo' => $Pregunta->tipo,
                        'retro' => $Pregunta->retro,

                    ]);
                }
            } else if ($Pregunta->tipo == "OPCMULT") {
                $PregMult = \App\PregOpcMul::ConsulPreg($IdPreg);
                $OpciMult = \App\OpcPregMul::ConsulGrupOpcPreg($IdPreg);
                $RespPregMul = \App\OpcPregMul::BuscOpcResp($IdPreg, Auth::user()->id);

                if (request()->ajax()) {
                    return response()->json([
                        'PregMult' => $PregMult,
                        'OpciMult' => $OpciMult,
                        'RespPregMul' => $RespPregMul,
                        'tipo' => $Pregunta->tipo,
                        'retro' => $Pregunta->retro,

                    ]);
                }
            } else if ($Pregunta->tipo == "VERFAL") {
                $PregVerFal = \App\EvalVerFal::ConVerFal($IdPreg);
                $RespPregVerFal = \App\EvalVerFal::VerFalResp($IdPreg, Auth::user()->id);

                if (request()->ajax()) {
                    return response()->json([
                        'PregVerFal' => $PregVerFal,
                        'RespPregVerFal' => $RespPregVerFal,
                        'tipo' => $Pregunta->tipo,
                        'retro' => $Pregunta->retro,

                    ]);
                }
            } else if ($Pregunta->tipo == "RELACIONE") {
                $PregRelacione = \App\PregRelacione::ConRela($IdPreg);
                $PregRelIndi = \App\EvalRelacione::PregRelDef($IdPreg);
                $RespPregRelacione = \App\RespEvalRelacione::RelacRespEtt($IdPreg, Auth::user()->id);

                if (request()->ajax()) {
                    return response()->json([
                        'PregRelacione' => $PregRelacione,
                        'PregRelIndi' => $PregRelIndi,
                        'RespPregRelacione' => $RespPregRelacione,
                        'tipo' => $Pregunta->tipo,
                        'retro' => $Pregunta->retro,

                    ]);
                }
            } else if ($Pregunta->tipo == "TALLER") {
                $PregTaller = \App\EvalTaller::PregTaller($IdPreg);
                $RespPregTaller = \App\RespEvalTaller::RespEvalTallerAlum($IdPreg, Auth::user()->id);
                if (request()->ajax()) {
                    return response()->json([
                        'PregTaller' => $PregTaller,
                        'RespPregTaller' => $RespPregTaller,
                        'tipo' => $Pregunta->tipo,
                        'retro' => $Pregunta->retro,

                    ]);
                }
            }
        } else {
            return redirect("/")->with("error", "Su sesion ha terminado");
        }
    }
}
