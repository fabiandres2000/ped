<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ContenidoModuloController extends Controller
{

////////////////////////////////CARGAR CURSOS
    public function CargarCursos()
    {
        if (Auth::check()) {
            $contenido = '';
            $idAsig = request()->get('idAsig');
            Session::put('IDASIGNA', $idAsig);

            $InfAsig = \App\ModulosTransversales::InfAsig($idAsig);
           

            $Modulos = \App\GradosModulos::ListModulos($idAsig);
            
            $imgmodulo = \App\ImgGradosModulosTransv::imgmodulo();
            
            $active = "active";
            $Sesiones = \App\sesiones::Guardar(Auth::user()->id);
;
            foreach ($Modulos as $Asig) {

                $Temas = \App\TemasModulos::LisTemasProg($Asig->id);
               
                $contenido .= '<div class="col-xl-4 col-lg-4 col-md-12"  onclick="" style="cursor: pointer;" >
                <div class="card hvr-grow-shadow" >
                    <div class="card-content border-success">
                        <div id="carousel-example" class="carousel slide"  data-ride="carousel">
                            <div class="carousel-inner" role="listbox">';

                $active = "active";

                foreach ($imgmodulo as $img) {
                    if ($Asig->id == $img->modulo_img) {
                        $contenido .= '  <div class="carousel-item ' . $active . '">
                                    <img src="' . asset('app-assets/images/Img_GradosModTransv/' . $img->url_img) . '" style="height: 200px; width: 350px;" class="img-fluid" alt="First slide">
                                </div>';

                        $active = "";
                    }
                }

                $contenido .= ' </div>
                            <a class="left carousel-control" href="#carousel-example" role="button" class="img-fluid" data-slide="prev">
                                <span class="icon-prev" aria-hidden="true"></span>
                                <span class="sr-only">Previous</span>
                            </a>
                            <a class="right carousel-control" href="#carousel-example" role="button" data-slide="next">
                                <span class="icon-next" aria-hidden="true"></span>
                                <span class="sr-only">Next</span>
                            </a>
                        </div>
                        <div class="card-body">
                        <h1 class="card-title" style="font-size:16px;">' . $Asig->nombre . ' Grado ' . $Asig->grado_modulo . '°' . '</h1>
                        </div>
                        <div class="insights px-2">
                        <div>
                                <span class="text-info h3">' . $Temas . '%</span>
                                <span class="float-right">Completado</span>
                            </div>
                            <div class="progress progress-md mt-1 mb-0">
                                <div class="progress-bar bg-info" role="progressbar" style="width: ' . $Temas . '%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                        <div class="card-footer border-top-blue-grey border-top-lighten-5 text-muted m-1">
                            <a href="' . url('/Contenido/PresentacionMod/' . $Asig->id) . '"  class="btn btn-success mr-1 mb-1">Entrar</a>
                        </div>
                        </div>
                        </div>
                    </div>';
            }

            if (request()->ajax()) {
                return response()->json([
                    'contenido' => $contenido,
                    'NomAsig' => $InfAsig->nombre,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su sesion ha terminado");
        }
    }

    public function GuardarRespEvaluaciones()
    {
        if (Auth::check()) {
            $datos = request()->all();

            $fecha = date('Y-m-d  H:i:s');

            if ($datos['TipPregunta'] == "PREGENSAY") {
                $InfPreg = \App\EvalPregEnsay::consulPregEnsay($datos['Pregunta']);
                $InfEval = \App\Evaluacion::DatosEvalMod($datos['IdEvaluacion'], 'IFEVAL');
                $Respuesta = \App\RespEvalEnsay::Guardar($InfPreg, $datos, $fecha);
                $InfEval['OriEva'] = "Estudiante";
                $Sesiones = \App\sesiones::Guardar(Auth::user()->id);

            } else if ($datos['TipPregunta'] == "COMPLETE") {

                $InfPreg = \App\EvalPregComplete::ConsultComplete($datos['Pregunta']);
                $InfEval = \App\Evaluacion::DatosEvalMod($datos['IdEvaluacion'], 'IFEVAL');
                $Respuesta = \App\RespEvalComp::Guardar($InfPreg, $datos, $fecha);
                $InfEval['OriEva'] = "Estudiante";
                $Sesiones = \App\sesiones::Guardar(Auth::user()->id);

            } else if ($datos['TipPregunta'] == "OPCMULT") {
                $Respuesta = \App\RespMultPreg::Guardar($datos, $fecha);
                $Sesiones = \App\sesiones::Guardar(Auth::user()->id);
                $InfEval = \App\Evaluacion::DatosEvalMod($datos['IdEvaluacion'], 'IFEVAL');

            } else if ($datos['TipPregunta'] == "VERFAL") {
                $Respuesta = \App\RespVerFal::Guardar($datos, $fecha);

                $Sesiones = \App\sesiones::Guardar(Auth::user()->id);
                $InfEval = \App\Evaluacion::DatosEvalMod($datos['IdEvaluacion'], 'IFEVAL');
                $InfEval['OriEva'] = "Estudiante";

            } else if ($datos['TipPregunta'] == "RELACIONE") {
                $Respuesta = \App\RespEvalRelacione::Guardar($datos, $fecha);
                $Sesiones = \App\sesiones::Guardar(Auth::user()->id);
                $InfEval = \App\Evaluacion::DatosEvalMod($datos['IdEvaluacion'], 'IFEVAL');
                $InfEval['OriEva'] = "Estudiante";

            } else if ($datos['TipPregunta'] == "TALLER") {

                if (request()->hasfile('archiTaller')) {
                    $prefijo = substr(md5(uniqid(rand())), 0, 6);
                    $name = self::sanear_string($prefijo . '_' . request()->file('archiTaller')->getClientOriginalName());
                    request()->file('archiTaller')->move(public_path() . '/app-assets/Archivos_EvalTaller_Resp/', $name);
                } else {
                    $name = $datos['NArchivo'];
                }

                $InfPreg = \App\EvalTaller::PregTaller($datos['Pregunta']);
                $Respuesta = \App\RespEvalTaller::Guardar($InfPreg, $name, $fecha);
                $Sesiones = \App\sesiones::Guardar(Auth::user()->id);
                $InfEval = \App\Evaluacion::DatosEvalMod($datos['IdEvaluacion'], 'IFEVAL');
                $InfEval['OriEva'] = "Estudiante";

            }

            if ($datos['nPregunta'] === "Ultima") {
                $LibroCalif = \App\LibroCalificaciones::Guardar($datos, $Respuesta['RegViejo'], $Respuesta['RegNuevo'], $InfEval, $fecha);
                $Intentos = \App\UpdIntEval::Guardar($datos['IdEvaluacion']);
                $InfEval = \App\Evaluacion::DatosEvalMod($datos['IdEvaluacion'], 'IFEVALFIN');

                $Log = \App\Log::Guardar('Evaluación Desarrollada', $datos['IdEvaluacion']);

                if ($Respuesta) {
                    if (request()->ajax()) {
                        return response()->json([
                            'Resp' => 'guardada',
                            'Libro' => $LibroCalif,
                            'InfEval' => $InfEval,
                        ]);
                    }
                }

            } else {
                $LibroCalif = \App\LibroCalificaciones::Guardar($datos, $Respuesta['RegViejo'], $Respuesta['RegNuevo'], $InfEval, $fecha);

                if ($Respuesta) {
                    if (request()->ajax()) {
                        return response()->json([
                            'Resp' => 'guardada',
                        ]);
                    }
                }
            }

        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function CambiarEvalModal()
    {
        if (Auth::check()) {
            $idTema = request()->get('id_tema');
            $DesEva = \App\Evaluacion::DesEval($idTema);
            $DatEva = \App\Evaluacion::DatosEvalMod($DesEva->id, 'INFALUM');
            $Sesiones = \App\sesiones::Guardar(Auth::user()->id);
            $PanelActivos = self::PanelActivos();
            $PanelNotiEval = self::PanelNotiEval();

            if ($DatEva == null) {
                $intreal = 0;
            } else {
                $intreal = $DatEva->int_realizados;
            }

            $titulo = $DesEva->titulo;
            $tipeval = $DesEva->tip_evaluacion;
            $id_eval = $DesEva->id;
            $intentos_perm = $DesEva->intentos_perm;
            $punt_max = $DesEva->punt_max;
            $calif_usando = $DesEva->calif_usando;
            $enunciado = $DesEva->enunciado;

            $conversa = $DesEva->hab_conversacion;
            $tiempo = $DesEva->tiempo;
            $hab_tiempo = $DesEva->hab_tiempo;
            $intentos_real = $intreal;
            $perfil = Auth::user()->tipo_usuario;

            $ideva = $DesEva->id;

            $Log = \App\Log::Guardar('Visualizacion de Evaluación', $ideva);

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
                    'int_perm' => $intentos_perm,
                    'int_realizados' => $intentos_real,
                    'enunciado' => $enunciado,
                    'conversa' => $conversa,
                    'tiempo' => $tiempo,
                    'hab_tiempo' => $hab_tiempo,
                    'perfil' => $perfil,
                    'Evaluacion' => $DesEva,
                    'PregEval' => $PregEval->shuffle(),
                    'VideoEval' => $video,
                    'idvideo' => $id,

                ]);
            }

        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function ConsultarTemaPDF()
    {

        if (Auth::check()) {
            $idTem = request()->get('idTem');

            $DeTema = \App\DesarrollTemaModulos::Destemas($idTem, 'NO');
            $PanelActivos = self::PanelActivos();
            $PanelNotiEval = self::PanelNotiEval();
            if (request()->ajax()) {
                return response()->json([
                    'DeTema' => $DeTema,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su sesion ha terminado");
        }
    }

    public function CambiarVideoModal()
    {
        if (Auth::check()) {
            $ActIni = "n";
            $Produc = "n";
            $Animac = "n";
            $idTema = request()->get('id_tema');

            $DatCont = \App\TemasModulos::BuscarTema($idTema);
            $DesVid = \App\ContDidacticoModulos::BuscarTema($idTema, 'NO');
            $infEval = \App\Evaluacion::ListEval($idTema, 'M');

            $Log = \App\Log::Guardar('Visualización de Tema', $idTema);
            foreach ($infEval as $Eval) {
                if ($Eval->clasificacion == "ACTINI") {
                    $ActIni = 's';
                }

                if ($Eval->clasificacion == "PRODUC") {
                    $Produc = 's';
                }
            }

            if ($DatCont->hab_cont_didact == "SI") {
                $Animac = "s";
            }

            $Sesiones = \App\sesiones::Guardar(Auth::user()->id);
            $PanelActivos = self::PanelActivos();
            $PanelNotiEval = self::PanelNotiEval();
            if (request()->ajax()) {
                return response()->json([
                    'DatCont' => $DatCont,
                    'DesVid' => $DesVid,
                    'ActIni' => $ActIni,
                    'Produc' => $Produc,
                    'Animac' => $Animac,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function PresentacionPrograma($id)
    {
        if (Auth::check()) {
            $GruposDoc = '';
            Session::put('IDMODULO', $id);
            Session::put('TIPCONT', 'MOD');

            $Desmodulo = \App\GradosModulos::Desmodulo($id);
            $desc = $Desmodulo->nombre . ' Grado ' . $Desmodulo->grado_modulo . '°';

            if (Auth::user()->tipo_usuario == "Profesor") {
                $GruposDoc = \App\ModProf::GruposxDoc();
            } else if (Auth::user()->tipo_usuario == "Estudiante") {
                $DatAlumno = \App\Alumnos::Buscar(Auth::user()->id);

                Session::put('IDGRUPO', $DatAlumno->grupo);
                Session::put('JORNADA', $DatAlumno->jornada);

                $DatGrup = \App\GruposModTransv::Buscar($id);
                Session::put('GRUPO', $DatGrup->id);
                $DatDoce = \App\ModProf::BuscDat($id);
                if ($DatDoce) {
                    Session::put('DOCENTE', $DatDoce->id);
                    Session::put('USUDOCENTE', $DatDoce->usuario_profesor);
                } else {
                    return redirect('Administracion')->with('error', $desc . ' no ha sido Asignada a ningun Docente.');
                }
            }

            Session::put('GRADO', $Desmodulo->grado_modulo);

            if ($Desmodulo->grado_modulo > 5) {
                $UrlReal = \App\ConsUrl::ConsulUrl("PED");
                Session::put('URL', $UrlReal->url . '/app-assets');
            } else {
                $UrlReal = \App\ConsUrl::ConsulUrl("PED-KID");
                Session::put('URL', $UrlReal->url . '/app-assets');
            }

            $UrlDocu = \App\UrlDocuMod::ConsDocumentos($id);

            if ($UrlDocu) {
                Session::put('DesCont', $UrlDocu->url_contenido);
                Session::put('DesProg', $UrlDocu->url_programacion);
            } else {
                Session::put('DesCont', 'NO');
                Session::put('DesProg', 'NO');
            }

            session(['des' => $desc]);
            return view('ContenidoMod.Presentacion', compact('id', 'Desmodulo', 'GruposDoc'));
        } else {
            return redirect("/")->with("error", "Su sesion ha terminado");
        }
    }

    public function CambiarPresentacion()
    {
        if (Auth::check()) {
            $idmod = request()->get('id');
            $presentacion = \App\GradosModulos::BuscarAsig($idmod);
            $Sesiones = \App\sesiones::Guardar(Auth::user()->id);

            if (request()->ajax()) {
                return response()->json([
                    'presentacion' => $presentacion,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su sesion ha terminado");
        }
    }

    public function CambiarGrupo()
    {
        if (Auth::check()) {
            $idgrupo = request()->get('idGrupo');
            Session::put('GrupActual', $idgrupo);
            $PanelActivos = self::PanelActivos();
            $PanelNotiEval = self::PanelNotiEval();
            $UrlDocu = \App\UrlDocuMod::ConsDocumentos(Session::get('IDMODULO'));

            if ($UrlDocu) {
                Session::put('DesCont', $UrlDocu->url_contenido);
                Session::put('DesProg', $UrlDocu->url_programacion);
            } else {
                Session::put('DesCont', 'NO');
                Session::put('DesProg', 'NO');
            }
            if (request()->ajax()) {
                return response()->json([
                    'Resp' => "ok",
                    'PanelActivos' => $PanelActivos,
                    'PanelNotiEval' => $PanelNotiEval,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su sesion ha terminado");
        }
    }

    public function ContenidoPrograma($id)
    {
        if (Auth::check()) {
            Session::put('IDMODULO', $id);
            $Periodo = \App\PeriodosModTransv::periodo($id);
            $unidad = \App\UnidadesModulos::unidad($id);
            $temas = \App\TemasModulos::LisTemas($id);

            $Sesiones = \App\sesiones::Guardar(Auth::user()->id);
            $PanelActivos = self::PanelActivos();

            $PanelNotiEval = self::PanelNotiEval();

            $Log = \App\Log::Guardar('Visualiazación de Contenido', $id);

            return view('ContenidoMod.Contenido', compact('Periodo', 'unidad', 'temas'));
        } else {
            return redirect("/")->with("error", "Su sesion ha terminado");
        }
    }

    public function CambiarContenido()
    {
        if (Auth::check()) {
            $idunidad = request()->get('id');
            $TitUnidad = \App\UnidadesModulos::TitUnidades($idunidad);
            $Temas = \App\TemasModulos::temas($idunidad);
            $Sesiones = \App\sesiones::Guardar(Auth::user()->id);
            $PanelActivos = self::PanelActivos();
            $PanelNotiEval = self::PanelNotiEval();

            if (request()->ajax()) {
                return response()->json([
                    'TitUnidad' => $TitUnidad,
                    'Temas' => $Temas,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su sesion ha terminado");
        }
    }

    public function CambiarContenidoModal()
    {
        if (Auth::check()) {
            $ActIni = "n";
            $Produc = "n";
            $Animac = "n";
            $idTema = request()->get('id_tema');
            $DatCont = \App\TemasModulos::BuscarTema($idTema);
            $DesaTema = \App\DesarrollTemaModulos::Destemas($idTema, 'NO');
            $infEval = \App\Evaluacion::ListEval($idTema, 'M');

            $Log = \App\Log::Guardar('Visualización de Tema Módulo Transversales', $idTema);
            foreach ($infEval as $Eval) {
                if ($Eval->clasificacion == "ACTINI") {
                    $ActIni = 's';
                }

                if ($Eval->clasificacion == "PRODUC") {
                    $Produc = 's';
                }
            }

            if ($DatCont->hab_cont_didact == "SI") {
                $Animac = "s";
            }

            $Sesiones = \App\sesiones::Guardar(Auth::user()->id);
            $PanelActivos = self::PanelActivos();
            $PanelNotiEval = self::PanelNotiEval();
            if (request()->ajax()) {
                return response()->json([
                    'DesaTema' => $DesaTema,
                    'ActIni' => $ActIni,
                    'Produc' => $Produc,
                    'Animac' => $Animac,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su sesion ha terminado");
        }
    }

    public function CambiarAct()
    {
        if (Auth::check()) {
            $id = request()->get('Tema');
            $Clasf = request()->get('clasf');
            $Temas = \App\TemasModulos::BuscarTema($id);
            $Eval = \App\Evaluacion::ListEvalxClasif($id, $Clasf, 'M');
            $Sesiones = \App\sesiones::Guardar(Auth::user()->id);
            if (request()->ajax()) {
                return response()->json([
                    'Eval' => $Eval,
                    'TitTemas' => $Temas->titu_contenido,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su sesion ha terminado");
        }
    }

    public function CambiarAnim()
    {
        if (Auth::check()) {
            $IdTema = request()->get('TemaAni');
            $DesAnimaciones = \App\ContDidacticoModulos::BuscarTema($IdTema, 'NO');
            $Temas = \App\TemasModulos::BuscarTema($IdTema);
            $Sesiones = \App\sesiones::Guardar(Auth::user()->id);
            if (request()->ajax()) {
                return response()->json([
                    'DesAnim' => $DesAnimaciones,
                    'TitTema' => $Temas->titu_contenido,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su sesion ha terminado");
        }
    }

    public function vistoContenido()
    {
        if (Auth::check()) {
            $id = request()->get('id');
            $estado = request()->get('estado');

            $TemasDocentes = \App\TemasDocenteModulos::Guardar($id, $estado);
            if ($estado == "SI") {
                $Log = \App\Log::Guardar('Cambio de estado de Visualizacion a Visto ', $id);
            } else {
                $Log = \App\Log::Guardar('Cambio de estado de Visualizacion a  Visto ', $id);
            }

            if ($TemasDocentes) {
                $mensaje = "SI";
            } else {
                $mensaje = "NO";
            }
            if (request()->ajax()) {
                return response()->json([
                    'estado' => $estado,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su sesion ha terminado");
        }
    }

    public function HabiliContenido()
    {
        if (Auth::check()) {
            $Hab = request()->get('idhabi');
            $Tem = request()->get('idTema');

            $cambio = \App\TemasDocenteModulos::GuardarHabi($Tem, $Hab);
            if ($Hab == "Habi") {
                $Log = \App\Log::Guardar('Cambio a estado del tema Habilitado', $Tem);
            } else {
                $Log = \App\Log::Guardar('Cambio a estado del Tema Desabilitado ', $Tem);
            }

            if ($cambio) {
                $mensaje = "SI";
            } else {
                $mensaje = "NO";
            }
            if (request()->ajax()) {
                return response()->json([
                    'mensaje' => $mensaje,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su sesion ha terminado");
        }
    }
    
    
    public function OrdenTemas()
    {
        if (auth::check()) {
            $datos = request()->all();
            
            $cambioOrd = \App\OrdenTemasModulos::OrdenTemas($datos);
            if (request()->ajax()) {
                return response()->json([
                    'mensaje' => "ok"
                ]);
            }

        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }

    }
    
    public function MostContenido()
    {
        if (Auth::check()) {
            $Most = request()->get('idMost');
            $Tem = request()->get('idTema');
            $cambio = \App\TemasDocenteModulos::GuardarMost($Tem, $Most);

            if ($Most == "Habi") {
                $Log = \App\Log::Guardar('Cambio a estado del tema Mostrar', $Tem);
            } else {
                $Log = \App\Log::Guardar('Cambio a estado del Tema Ocultar ', $Tem);
            }

            if ($cambio) {
                $mensaje = "SI";
            } else {
                $mensaje = "NO";
            }
            if (request()->ajax()) {
                return response()->json([
                    'mensaje' => $mensaje,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function PanelActivos()
    {
        $UsuAct = 0;
        $listUsu = "";
        $listUsuNoCo = "";
        $Alumnos = \App\Profesores::alumnosMod();

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
        $Alumnos = \App\Profesores::alumnosMod();
        foreach ($Alumnos as $Alu) {
            $AlumNotif = \App\ComentTemas::ConsultarDoce($Alu->usuario, 'M');
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
            $AlumEval = \App\LibroCalificaciones::BuscEvalPendMod($Alu->usuario, 'M');

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

    public function sanear_string($string)
    {

        $string = trim($string);

        $string = str_replace(
            array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'), array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'), $string
        );

        $string = str_replace(
            array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'), array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'), $string
        );

        $string = str_replace(
            array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'), array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'), $string
        );

        $string = str_replace(
            array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'), array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'), $string
        );

        $string = str_replace(
            array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'), array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'), $string
        );

        $string = str_replace(
            array('ñ', 'Ñ', 'ç', 'Ç'), array('n', 'N', 'c', 'C'), $string
        );

        //Esta parte se encarga de eliminar cualquier caracter extraño
        $string = str_replace(
            array("¨", "º", "-", "~", "", "@", "|", "!",
                "·", "$", "%", "&", "/",
                "(", ")", "?", "'", " h¡",
                "¿", "[", "^", "<code>", "]",
                "+", "}", "{", "¨", "´",
                ">", "< ", ";", ",", ":",
                " "), '', $string
        );

        return $string;
    }

}
