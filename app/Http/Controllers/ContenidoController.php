<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ContenidoController extends Controller
{

    public function CambiarContenido()
    {
        if (Auth::check()) {
            $idunidad = request()->get('id');
            $TitUnidad = \App\Unidades::TitUnidades($idunidad);
            $Temas = \App\Temas::temas($idunidad);
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
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function CambiarAct()
    {
        if (Auth::check()) {
            $id = request()->get('Tema');
            $Clasf = request()->get('clasf');
            $Temas = \App\Temas::BuscarTema($id);

            $Eval = \App\Evaluacion::ListEvalxClasif($id, $Clasf, 'C');
            $Sesiones = \App\sesiones::Guardar(Auth::user()->id);
            if (request()->ajax()) {
                return response()->json([
                    'Eval' => $Eval,
                    'TitTemas' => $Temas->titu_contenido,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function CambiarAnim()
    {
        if (Auth::check()) {
            $IdTema = request()->get('TemaAni');
            $DesAnimaciones = \App\ContDidactico::BuscarTema($IdTema, 'NO');
            $Temas = \App\Temas::BuscarTema($IdTema);
            $Sesiones = \App\sesiones::Guardar(Auth::user()->id);
            if (request()->ajax()) {
                return response()->json([
                    'DesAnim' => $DesAnimaciones,
                    'TitTema' => $Temas->titu_contenido,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function CambiarAnimZonaLibre()
    {
        if (Auth::check()) {
            $IdTema = request()->get('TemaAni');
            $Temas = \App\ZonaLibre::BuscarTema($IdTema);

            if ($Temas->tip_video == "LINK") {
                $DesAnimaciones = \App\DesarrolloLink::DesLink($IdTema, 'SI');
            } else {
                $DesAnimaciones = \App\ContDidactico::BuscarTema($IdTema, 'SI');
            }

            $Sesiones = \App\sesiones::Guardar(Auth::user()->id);
            if (request()->ajax()) {
                return response()->json([
                    'DesAnim' => $DesAnimaciones,
                    'TitTema' => $Temas->titu_contenido,
                    'tip_video' => $Temas->tip_video,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function CambiarLinkZonaLibre()
    {
        if (Auth::check()) {
            $IdTema = request()->get('TemaAni');
            $Temas = \App\ZonaLibre::BuscarTema($IdTema);

            $DesLink = \App\DesarrolloLink::DesLink($IdTema, 'SI');

            $Sesiones = \App\sesiones::Guardar(Auth::user()->id);
            if (request()->ajax()) {
                return response()->json([
                    'DesLink' => $DesLink,
                    'TitTema' => $Temas->titu_contenido,
                    'tip_video' => $Temas->tip_video,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function ConusulComentZona()
    {
        if (Auth::check()) {

            if (Auth::user()->tipo_usuario == "Profesor") {
                $grado = request()->get('ComGrado');
                $grupo = request()->get('ComGrupo');
                $jorna = request()->get('ComJorn');
                $RespComen = \App\ComentZona::Consultar2($grado, $grupo, $jorna);
                for ($i = 0; $i < count($RespComen); $i++) {

                    if ($RespComen[$i]['tipo_usuario'] == "Profesor") {
                        $RespFot = \App\Profesores::BuscarProfFoto($RespComen[$i]['usuario']);
                        $RespComen[$i]->foto = $RespFot->foto;
                    } else {
                        $RespFot = \App\Alumnos::BuscarAlumFoto($RespComen[$i]['usuario']);
                        $RespComen[$i]->foto = $RespFot->foto_alumno;
                    }

                }

            } else {

                $Alumno = \App\Alumnos::Buscar(Auth::user()->id);
                $RespComen = \App\ComentZona::Consultar2Est($Alumno->grado_alumno, $Alumno->grupo, $Alumno->jornada);

                for ($i = 0; $i < count($RespComen); $i++) {

                    if ($RespComen[$i]['tipo_usuario'] == "Profesor") {
                        $RespFot = \App\Profesores::BuscarProfFoto($RespComen[$i]['usuario']);
                        $RespComen[$i]->foto = $RespFot->foto;
                    } else {
                        $RespFot = \App\Alumnos::BuscarAlumFoto($RespComen[$i]['usuario']);
                        $RespComen[$i]->foto = $RespFot->foto_alumno;
                    }

                }

            }

            $Sesiones = \App\sesiones::Guardar(Auth::user()->id);

            if (request()->ajax()) {
                return response()->json([
                    'RespComen' => $RespComen,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function CambiarContenidoModal()
    {
        if (Auth::check()) {
            $ActIni = "n";
            $Produc = "n";
            $Animac = "n";
            $idTema = request()->get('id_tema');
            $DatCont = \App\Temas::BuscarTema($idTema);
            $DesaTema = \App\DesarrollTema::Destemas($idTema, 'NO');
            $infEval = \App\Evaluacion::ListEval($idTema, 'C');

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
                    'DesaTema' => $DesaTema,
                    'ActIni' => $ActIni,
                    'Produc' => $Produc,
                    'Animac' => $Animac,
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

            $DeTema = \App\DesarrollTema::Destemas($idTem, 'NO');
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

    public function CambiarContenidoModaZonaLibre()
    {
        if (Auth::check()) {
            $idTema = request()->get('id_tema');
            $DesaTema = \App\DesarrollTema::Destemas($idTema, 'SI');

            $Sesiones = \App\sesiones::Guardar(Auth::user()->id);
            $PanelActivos = self::PanelActivos();
            $PanelNotiEval = self::PanelNotiEval();
            if (request()->ajax()) {
                return response()->json([
                    'DesaTema' => $DesaTema,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function CambiarContenidoDidactico()
    {
        if (Auth::check()) {
            $ActIni = "n";
            $Produc = "n";
            $idTema = request()->get('id_tema');
            $DesaTema = \App\ContDidactico::BuscarTema($idTema, 'SI');

            $infEval = \App\Evaluacion::ListEval($idTema, 'C');
            foreach ($infEval as $Eval) {
                if ($Eval->clasificacion == "ACTINI") {
                    $ActIni = 's';
                }
                if ($Eval->clasificacion == "PRODUC") {
                    $Produc = 's';
                }
            }
            $Sesiones = \App\sesiones::Guardar(Auth::user()->id);

            if (request()->ajax()) {
                return response()->json([
                    'DesaTema' => $DesaTema,
                    'ActIni' => $ActIni,
                    'Produc' => $Produc,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function GuardarComent()
    {
        if (Auth::check()) {
            $idEval = request()->get('idEvalComent');
            $Coment = request()->get('Coment');
            $Docente = request()->get('idDoce');
            $Comet = \App\ComentTemas::Guardar($idEval, $Coment, $Docente);
            $Sesiones = \App\sesiones::Guardar(Auth::user()->id);

            if (request()->ajax()) {
                return response()->json([
                    'Comet' => $Comet,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function GuardarComentDoce()
    {
        if (Auth::check()) {
            $idEval = request()->get('idEvalComent');
            $Coment = request()->get('Coment');
            $Docente = request()->get('idDoce');
            $Alumno = request()->get('idAlu');
            $Comet = \App\ComentTemas::GuardarDoce($idEval, $Coment, $Docente, $Alumno);
            $Sesiones = \App\sesiones::Guardar(Auth::user()->id);

            if (request()->ajax()) {
                return response()->json([
                    'Comet' => $Comet,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function GuardarComentZona()
    {
        if (Auth::check()) {
            $Mensaje = request()->get('Mensaje');
            if (Auth::user()->tipo_usuario == "Profesor") {
                $grado = request()->get('ComGrado');
                $grupo = request()->get('ComGrupo');
                $jorna = request()->get('ComJorn');
                $Comet = \App\ComentZona::Guardar($Mensaje, $grado, $grupo, $jorna, Auth::user()->id);
                $RespComen = \App\ComentZona::Consultar($grado, $grupo, $jorna);
                for ($i = 0; $i < count($RespComen); $i++) {

                    if ($RespComen[$i]['tipo_usuario'] == "Profesor") {
                        $RespFot = \App\Profesores::BuscarProfFoto($RespComen[$i]['usuario']);
                        $RespComen[$i]->foto = $RespFot->foto;
                    } else {
                        $RespFot = \App\Alumnos::BuscarAlumFoto($RespComen[$i]['usuario']);
                        $RespComen[$i]->foto = $RespFot->foto_alumno;
                    }

                }

            } else {
                $Alumno = \App\Alumnos::Buscar(Auth::user()->id);
                $Comet = \App\ComentZona::Guardar($Mensaje, $Alumno->grado_alumno, $Alumno->grupo, $Alumno->jornada, request()->get('usuprof'));
                $RespComen = \App\ComentZona::Consultar2Est($Alumno->grado_alumno, $Alumno->grupo, $Alumno->jornada);
                for ($i = 0; $i < count($RespComen); $i++) {

                    if ($RespComen[$i]['tipo_usuario'] == "Profesor") {
                        $RespFot = \App\Profesores::BuscarProfFoto($RespComen[$i]['usuario']);
                        $RespComen[$i]->foto = $RespFot->foto;
                    } else {
                        $RespFot = \App\Alumnos::BuscarAlumFoto($RespComen[$i]['usuario']);
                        $RespComen[$i]->foto = $RespFot->foto_alumno;
                    }

                }
            }

            $Sesiones = \App\sesiones::Guardar(Auth::user()->id);

            if (request()->ajax()) {
                return response()->json([
                    'RespComen' => $RespComen,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function GuardarRespEvaluaciones()
    {
        if (Auth::check()) {
            $datos = request()->all();

            $fecha = date('Y-m-d  H:i:s');

            if ($datos['TipPregunta'] == "PREGENSAY") {
                $InfPreg = \App\EvalPregEnsay::consulPregEnsay($datos['Pregunta']);
                $InfEval = \App\Evaluacion::DatosEvla($datos['IdEvaluacion'], 'IFEVAL');
                $Respuesta = \App\RespEvalEnsay::Guardar($InfPreg, $datos, $fecha);
                $InfEval['OriEva'] = "Estudiante";
                $Sesiones = \App\sesiones::Guardar(Auth::user()->id);

            } else if ($datos['TipPregunta'] == "COMPLETE") {

                $InfPreg = \App\EvalPregComplete::ConsultComplete($datos['Pregunta']);
                $InfEval = \App\Evaluacion::DatosEvla($datos['IdEvaluacion'], 'IFEVAL');
                $Respuesta = \App\RespEvalComp::Guardar($InfPreg, $datos, $fecha);
                $InfEval['OriEva'] = "Estudiante";
                $Sesiones = \App\sesiones::Guardar(Auth::user()->id);

            } else if ($datos['TipPregunta'] == "OPCMULT") {
                $Respuesta = \App\RespMultPreg::Guardar($datos, $fecha);
                $Sesiones = \App\sesiones::Guardar(Auth::user()->id);
                $InfEval = \App\Evaluacion::DatosEvla($datos['IdEvaluacion'], 'IFEVAL');

            } else if ($datos['TipPregunta'] == "VERFAL") {
                $Respuesta = \App\RespVerFal::Guardar($datos, $fecha);

                $Sesiones = \App\sesiones::Guardar(Auth::user()->id);
                $InfEval = \App\Evaluacion::DatosEvla($datos['IdEvaluacion'], 'IFEVAL');
                $InfEval['OriEva'] = "Estudiante";

            } else if ($datos['TipPregunta'] == "RELACIONE") {
                $Respuesta = \App\RespEvalRelacione::Guardar($datos, $fecha);
                $Sesiones = \App\sesiones::Guardar(Auth::user()->id);
                $InfEval = \App\Evaluacion::DatosEvla($datos['IdEvaluacion'], 'IFEVAL');
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
                $InfEval = \App\Evaluacion::DatosEvla($datos['IdEvaluacion'], 'IFEVAL');
                $InfEval['OriEva'] = "Estudiante";

            }

            if ($datos['nPregunta'] === "Ultima") {
                $LibroCalif = \App\LibroCalificaciones::Guardar($datos, $Respuesta['RegViejo'], $Respuesta['RegNuevo'], $InfEval, $fecha);
                $Intentos = \App\UpdIntEval::Guardar($datos['IdEvaluacion']);
                $InfEval = \App\Evaluacion::DatosEvla($datos['IdEvaluacion'], 'IFEVALFIN');

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

    public function ConusulComent()
    {
        if (Auth::check()) {
            $idEval = request()->get('idEvalComent2');
            $idDoc = request()->get('idDoce2');
            $Comet = \App\ComentTemas::Consultar($idEval, $idDoc);
            $Sesiones = \App\sesiones::Guardar(Auth::user()->id);
            if (request()->ajax()) {
                return response()->json([
                    'Comet' => $Comet,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function ConusulComentDoce()
    {
        if (Auth::check()) {
            $idAlum = request()->get('idAlum');
            $idDoce = request()->get('idDoce');
            $idEvalComent = request()->get('idEvalComent');
            $Comet = \App\ComentTemas::ConsultarDetDoce($idEvalComent, $idDoce, $idAlum);
            $CometVist = \App\ComentTemas::UpdatVistNotDoce($idEvalComent, $idDoce, $idAlum);
            $Sesiones = \App\sesiones::Guardar(Auth::user()->id);
            if (request()->ajax()) {
                return response()->json([
                    'Comet' => $Comet,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function CambiarLinkModal()
    {
        if (Auth::check()) {
            $idTema = request()->get('id_tema');
            $DesaLink = \App\DesarrolloLink::DesLink($idTema, 'NO');
            $Sesiones = \App\sesiones::Guardar(Auth::user()->id);

            if (request()->ajax()) {
                return response()->json([
                    'DesaLink' => $DesaLink,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

////////////////////////////////CARGAR CURSOS
    public function CargarCursos()
    {
        if (Auth::check()) {
            $contenido = '';
            $idAsig = request()->get('idAsig');
            Session::put('IDASIGNA', $idAsig);

            $InfAsig = \App\Asignaturas::InfAsig($idAsig);

            $Modulos = \App\Modulos::ListModulos($idAsig);
       
            $imgmodulo = \App\ImgModulos::imgmodulo();
            $active = "active";
            $Sesiones = \App\sesiones::Guardar(Auth::user()->id);
            $PanelActivos = self::PanelActivos();
            $PanelNotiEval = self::PanelNotiEval();

            foreach ($Modulos as $Asig) {

                $Temas = \App\Temas::LisTemasProg($Asig->id);
              
                $contenido .= '<div class="col-xl-4 col-lg-4 col-md-12"  onclick="" style="cursor: pointer;" >
                    <div class="card hvr-grow-shadow" >
                        <div class="card-content border-success">
                            <div id="carousel-example" class="carousel slide"  data-ride="carousel">
                                <div class="carousel-inner" role="listbox">';

                $active = "active";

                foreach ($imgmodulo as $img) {
                    if ($Asig->id == $img->modulo_img) {
                        $contenido .= '  <div class="carousel-item ' . $active . '">
                                        <img src="' . asset('app-assets/images/Img_Modulos/' . $img->url_img) . '" stylej="height: 200px; width: 350px;" class="img-fluid" alt="First slide">
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
                                <a href="' . url('/Contenido/Presentacion/' . $Asig->id) . '"  class="btn btn-success mr-1 mb-1">Entrar</a>
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
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function PresentacionPrograma($id)
    {
        if (Auth::check()) {
            $GruposDoc = '';
            Session::put('IDMODULO', $id);
            Session::put('TIPCONT', 'ASI');
            $Desmodulo = \App\Modulos::Desmodulo($id);
            $desc = $Desmodulo->nombre . ' Grado ' . $Desmodulo->grado_modulo . '°';

            if (Auth::user()->tipo_usuario == "Profesor") {
                $GruposDoc = \App\AsigProf::GruposxDoc();
            } else if (Auth::user()->tipo_usuario == "Estudiante") {
                $DatAlumno = \App\Alumnos::Buscar(Auth::user()->id);

                Session::put('IDGRUPO', $DatAlumno->grupo);
                Session::put('JORNADA', $DatAlumno->jornada);

                $DatGrup = \App\Grupos::Buscar($id);
                Session::put('GRUPO', $DatGrup->id);
                $DatDoce = \App\AsigProf::BuscDat($id);
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

            $DataLaboratorio = \App\Laboratorios::BuscarLaborarios($id);
            Session::put('CANT_LABORATORIOS', $DataLaboratorio->count());

            $UrlDocu = \App\UrlDocu::ConsDocumentos($id);

            if ($UrlDocu) {
                Session::put('DesCont', $UrlDocu->url_contenido);
                Session::put('DesProg', $UrlDocu->url_programacion);
            } else {
                Session::put('DesCont', 'NO');
                Session::put('DesProg', 'NO');
            }

            session(['des' => $desc]);
            return view('Contenido.Presentacion', compact('id', 'Desmodulo', 'GruposDoc'));
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function ContenidoPrograma($id)
    {
        if (Auth::check()) {
            Session::put('IDMODULO', $id);
            $Periodo = \App\Periodos::periodo($id);
            $unidad = \App\Unidades::unidad($id);
            $temas = \App\Temas::LisTemas($id);

            $Sesiones = \App\sesiones::Guardar(Auth::user()->id);
            $PanelActivos = self::PanelActivos();
            $PanelNotiEval = self::PanelNotiEval();

            $Log = \App\Log::Guardar('Visualiazación de Contenido', $id);

            return view('Contenido.Contenido', compact('Periodo', 'unidad', 'temas'));
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function VisuCometarios()
    {
        if (Auth::check()) {
            $lisNoti = '';
            $class = '';
            $Alumnos = \App\Profesores::alumnos();
            $cont = 1;
            foreach ($Alumnos as $Alu) {
                $AlumNotif = \App\ComentTemas::ConsultarDoce2($Alu->usuario, 'C');
                if ($AlumNotif) {

                    if ($AlumNotif->visto == "NO") {
                        $class = 'bs-callout-primary';
                    } else {
                        $class = 'bs-callout-danger';
                    }

                    $lisNoti .= '<div class="' . $class . ' callout-border-left p-1" data-item-id="' . $AlumNotif->evaluacion . '/' . $AlumNotif->alumno . '/' . $AlumNotif->docente . '" id="Coment' . $cont . '"  style="cursor:pointer;" onclick="$.RespNotEval(this.id);">
                    <strong style="text-transform: capitalize;">' . mb_strtolower($AlumNotif->titulo) . '</strong>
                    <p>' . $AlumNotif->comentario . '</p>
                    <small>
                    <time class="media-meta text-muted" style="text-transform: capitalize;">' . $AlumNotif->nombre_usuario . '</time>
                        </small>
                    </div>';
                }
                $cont++;
            }

            $Sesiones = \App\sesiones::Guardar(Auth::user()->id);
            $PanelActivos = self::PanelActivos();
            $PanelNotiEval = self::PanelNotiEval();

            return view('Profesores.ComentariosEvalAsig', compact('lisNoti'));
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function VisuCometarios2()
    {
        if (Auth::check()) {
            $lisNoti = '';
            $class = '';
            $Alumnos = \App\Profesores::alumnos();
            $cont = 1;
            foreach ($Alumnos as $Alu) {
                $AlumNotif = \App\ComentTemas::ConsultarDoce2($Alu->usuario);
                if ($AlumNotif) {
                    if ($AlumNotif->visto == "NO") {
                        $class = 'bs-callout-primary';
                    } else {
                        $class = 'bs-callout-danger';
                    }

                    $lisNoti .= '<div class="' . $class . ' callout-border-left p-1" data-item-id="' . $AlumNotif->evaluacion . '/' . $AlumNotif->alumno . '/' . $AlumNotif->docente . '" id="Coment' . $cont . '"  style="cursor:pointer;" onclick="$.RespNotEval(this.id);">
                    <strong style="text-transform: capitalize;">' . mb_strtolower($AlumNotif->titulo) . '</strong>
                    <p>' . $AlumNotif->comentario . '</p>
                    <small>
                    <time class="media-meta text-muted" style="text-transform: capitalize;">' . $AlumNotif->nombre_usuario . '</time>
                        </small>
                    </div>';
                }
                $cont++;
            }

            $Sesiones = \App\sesiones::Guardar(Auth::user()->id);
            $PanelActivos = self::PanelActivos();
            $PanelNotiEval = self::PanelNotiEval();

            if (request()->ajax()) {
                return response()->json([
                    'lisNoti' => $lisNoti,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function ZonaLibre($id)
    {
        if (Auth::check()) {
            $SelGrupos = "";
            $DatDoce = "";
            $Comentarios = "";
            if (Auth::user()->tipo_usuario == "Estudiante") {
                $Alumno = \App\Alumnos::Buscar($id);

                $temas = \App\ZonaLibre::LisTemasEst($Alumno->grado_alumno, $Alumno->grupo, $Alumno->jornada);

                if (count($temas) > 0) {
                    $DetTema = $temas->last();
                    $Comentarios = \App\DesarroComentario::LisComentarioEst($Alumno->grado_alumno, $Alumno->grupo, $Alumno->jornada);
                    $DatDoce = \App\Profesores::Buscar($DetTema->docente);
                }
            } else {

                $grado = request()->get('idgrado');
                $grupo = request()->get('idgrupo');
                $jorna = request()->get('idjorna');
                $temas = \App\ZonaLibre::LisTemas(Auth::user()->id, $grado, $grupo, $jorna);

                $Comentarios = \App\DesarroComentario::LisComentario();
                $DatDoce = \App\Profesores::Buscar(Auth::user()->id);
                $Cole = \App\Colegios::InfColeg(Session::get('IdColegio'));

                $ParGrupos = \App\ParGrupos::LisGrupos($Cole->num_cursos, $Cole->cant_grupos);

                foreach ($ParGrupos as $Grup) {
                    $SelGrupos .= "<option value='$Grup->id' >" . strtoupper($Grup->descripcion) . "</option>";
                }
            }

            Session::put('CURSO', $id);

            $TamTem = 0;
            $TamArc = 0;
            $TamVid = 0;
            $TamLin = 0;

            foreach ($temas as $Tem) {
                if ($Tem->tip_contenido == "DOCUMENTO") {
                    $TamTem++;
                }
                if ($Tem->tip_contenido == "VIDEOS") {
                    $TamVid++;
                }
                if ($Tem->tip_contenido == "ARCHIVO") {
                    $TamArc++;
                }
                if ($Tem->tip_contenido == "LINK") {
                    $TamLin++;
                }
            }

            $Sesiones = \App\sesiones::Guardar(Auth::user()->id);
            $PanelActivos = self::PanelActivos();
            $PanelNotiEval = self::PanelNotiEval();

            return view('Contenido.Zonalibre', compact('temas', 'DatDoce', 'Comentarios', 'TamTem', 'TamVid', 'TamArc', 'TamLin', 'SelGrupos'));
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function ZonaLibreDoce()
    {
        if (Auth::check()) {
            $SelGrupos = "";

            $grado = request()->get('idgrado');
            $grupo = request()->get('idgrupo');
            $jorna = request()->get('idjorna');
            $temas = \App\ZonaLibre::LisTemas(Auth::user()->id, $grado, $grupo, $jorna);

            $Comentarios = \App\DesarroComentario::LisComentario();
            $DatDoce = \App\Profesores::Buscar(Auth::user()->id);
            $Cole = \App\Colegios::InfColeg(Session::get('IdColegio'));

            $ParGrupos = \App\ParGrupos::LisGrupos($Cole->num_cursos, $Cole->cant_grupos);

            foreach ($ParGrupos as $Grup) {
                $SelGrupos .= "<option value='$Grup->id' >" . strtoupper($Grup->descripcion) . "</option>";
            }

            $TamTem = 0;
            $TamArc = 0;
            $TamVid = 0;
            $TamLin = 0;

            foreach ($temas as $Tem) {
                if ($Tem->tip_contenido == "DOCUMENTO") {
                    $TamTem++;
                }
                if ($Tem->tip_contenido == "VIDEOS") {
                    $TamVid++;
                }
                if ($Tem->tip_contenido == "ARCHIVO") {
                    $TamArc++;
                }
                if ($Tem->tip_contenido == "LINK") {
                    $TamLin++;
                }
            }

            $Sesiones = \App\sesiones::Guardar(Auth::user()->id);
            $PanelActivos = self::PanelActivos();
            $PanelNotiEval = self::PanelNotiEval();

            $j = 1;
            $anuncios = "";
            $contenidos = "";
            $ClaseCom = array("overlay-danger overlay-lighten-2", "overlay-warning", " overlay-blue", "overlay-yellow");

            ///// CARAGRA ANUNCIOS ///////////
            $anuncios .= '    <div class="card-content collapse show">
            <div class="card-body">';

            foreach ($temas as $Tem) {
                if ($Tem->tip_contenido == "ANUNCIO") {
                    foreach ($Comentarios as $Coment) {
                        $color = $ClaseCom[array_rand($ClaseCom)];
                        if ($j == 1) {
                            $anuncios .= '<div class="row match-height">
                                    <div class="col-12 mt-1 mb-1">
                                        <h4 class="text-uppercase">ANUNCIOS DEL DOCENTE.</h4>
                                    </div>';
                        }

                        if ($Tem->id == $Coment->contenido) {
                            $anuncios .= '<div class="col-xl-3 col-lg-6 col-sm-12">
                                    <div class="card border-0 box-shadow-0" style="height: 181.617px;">
                                        <div class="card-content">
                                            <img class="card-img img-fluid" src="' . asset('app-assets/images/backgrounds/bg_coment.jpg') . '" alt="Card image">
                                            <div class="card-img-overlay ' . $color . '">
                                                <h4 class="card-title">' . $Coment->titulo . '</h4>
                                                <p class="card-text">' . $Coment->cont_comentario . '</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>';
                        }

                        $j++;
                        $anuncios .= '</div>';
                    }
                }
            }

            $anuncios .= '</div></div>';

            ////////////////// CAGAR CONTENIDO TEMATICO /////////////////

            $contenidos .= '    <div class="card-content collapse show">
            <div class="card-body">';

            $j = 1;
            $margin = "";
            $ClasDiv = 'col-xl-12';
            if ($TamTem > 1) {
                $ClasDiv = 'col-xl-6';
            }

            $contenidos .= '<div class="row">';
            foreach ($temas as $Tem) {
                if ($Tem->tip_contenido == "DOCUMENTO") {

                    if ($j == 1) {

                        $contenidos .= '<div class="col-12 mt-1 mb-1">
                        <h4 class="text-uppercase">CONTENIDO TEMATICO.</h4>
                    </div>';
                        $margin = "";
                    } else {
                        $margin = "pb-2";
                    }

                    $contenidos .= '  <div class="' . $ClasDiv . ' col-md-12 ' . $margin . '"
                    style="cursor: pointer;"
                    onclick="$.MostConteDoc(' . $Tem->id . ');">
                    <div
                        class="bs-callout-primary callout-transparent callout-bordered">
                        <div class="media align-items-stretch">
                            <div
                                class="d-flex align-items-center bg-primary position-relative callout-arrow-left p-2">
                                <i
                                    class="icon-book-open fa-lg white font-medium-5"></i>
                            </div>
                            <div class="media-body p-1">
                                <strong>' . $Tem->titu_contenido . '</strong>

                            </div>
                        </div>
                    </div>


                </div>';
                    $j++;
                }

            }

            $contenidos .= '</div></div></div>';

            ////////////////// CAGAR CONTENIDO  ARCHIVOS/////////////////
            $contenidos .= '<div class="card-content collapse show">
            <div class="card-body">';
            $j = 1;
            $margin = "";
            $ClasDiv = 'col-xl-12';
            if ($TamTem > 1) {
                $ClasDiv = 'col-xl-6';
            }

            $contenidos .= '<div class="row">';
            foreach ($temas as $Tem) {
                if ($Tem->tip_contenido == "ARCHIVO") {

                    if ($j == 1) {

                        $contenidos .= '<div class="col-12 mt-1 mb-1">
                        <h4 class="text-uppercase">CONTENIDO ARCHIVOS.</h4>
                    </div>';
                        $margin = "";
                    } else {
                        $margin = "pb-2";
                    }

                    $contenidos .= '  <div class="' . $ClasDiv . ' col-md-12 ' . $margin . '"
                    style="cursor: pointer;"
                    onclick="$.MostConteArchivo(' . $Tem->id . ');">
                    <div
                        class="bs-callout-primary callout-transparent callout-bordered">
                        <div class="media align-items-stretch">
                            <div
                                class="d-flex align-items-center bg-primary position-relative callout-arrow-left p-2">
                                <i
                                    class="icon-docs fa-lg white font-medium-5"></i>
                            </div>
                            <div class="media-body p-1">
                                <strong>' . $Tem->titu_contenido . '</strong>

                            </div>
                        </div>
                    </div>


                </div>';
                    $j++;
                }

            }

            $contenidos .= '</div></div></div>';

            ////////////////// CAGAR CONTENIDO  ARCHIVOS/////////////////

            $contenidos .= '<div class="card-content collapse show">
            <div class="card-body">';
            $j = 1;
            $margin = "";
            $ClasDiv = 'col-xl-12';
            if ($TamTem > 1) {
                $ClasDiv = 'col-xl-6';
            }

            $contenidos .= '<div class="row">';
            foreach ($temas as $Tem) {
                if ($Tem->tip_contenido == "VIDEOS") {

                    if ($j == 1) {

                        $contenidos .= '<div class="col-12 mt-1 mb-1">
                        <h4 class="text-uppercase">CONTENIDO VIDEOS.</h4>
                    </div>';
                        $margin = "";
                    } else {
                        $margin = "pb-2";
                    }

                    $contenidos .= '  <div class="' . $ClasDiv . ' col-md-12 ' . $margin . '"
                    style="cursor: pointer;"
                    onclick="$.AbrirAnimaciones(' . $Tem->id . ');">
                    <div
                        class="bs-callout-primary callout-transparent callout-bordered">
                        <div class="media align-items-stretch">
                            <div
                                class="d-flex align-items-center bg-primary position-relative callout-arrow-left p-2">
                                <i
                                    class="icon-social-youtube fa-lg white font-medium-5"></i>
                            </div>
                            <div class="media-body p-1">
                                <strong>' . $Tem->titu_contenido . '</strong>

                            </div>
                        </div>
                    </div>


                </div>';
                    $j++;
                }

            }

            $contenidos .= '</div></div></div>';

            ////////////////// CAGAR CONTENIDO  LINKS/////////////////

            $contenidos .= '<div class="card-content collapse show">
                <div class="card-body">';
            $j = 1;
            $margin = "";
            $ClasDiv = 'col-xl-12';
            if ($TamTem > 1) {
                $ClasDiv = 'col-xl-6';
            }

            $contenidos .= '<div class="row">';
            foreach ($temas as $Tem) {
                if ($Tem->tip_contenido == "LINKS") {

                    if ($j == 1) {

                        $contenidos .= '<div class="col-12 mt-1 mb-1">
                            <h4 class="text-uppercase">CONTENIDO LINKS.</h4>
                        </div>';
                        $margin = "pb-2";
                    } else {
                        $margin = "pb-2";
                    }

                    $contenidos .= '  <div class="' . $ClasDiv . ' col-md-12 ' . $margin . '"
                        style="cursor: pointer;"
                        onclick="$.AbrirLink(' . $Tem->id . ');">
                        <div
                            class="bs-callout-primary callout-transparent callout-bordered">
                            <div class="media align-items-stretch">
                                <div
                                    class="d-flex align-items-center bg-primary position-relative callout-arrow-left p-2">
                                    <i
                                        class="icon-link fa-lg white font-medium-5"></i>
                                </div>
                                <div class="media-body p-1">
                                    <strong>' . $Tem->titu_contenido . '</strong>

                                </div>
                            </div>
                        </div>


                    </div>';
                    $j++;
                }

            }

            $contenidos .= '</div></div></div>';

            if (request()->ajax()) {
                return response()->json([
                    'anuncios' => $anuncios,
                    'contenidos' => $contenidos,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function CambiarPresentacion()
    {
        if (Auth::check()) {
            $idmod = request()->get('id');
            $presentacion = \App\Modulos::BuscarAsig($idmod);
            $Sesiones = \App\sesiones::Guardar(Auth::user()->id);

            if (request()->ajax()) {
                return response()->json([
                    'presentacion' => $presentacion,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function CambiarGrupo()
    {
        if (Auth::check()) {
            $idgrupo = request()->get('idGrupo');
            Session::put('GrupActual', $idgrupo);
            $PanelActivos = self::PanelActivos();
            $PanelNotiEval = self::PanelNotiEval();
            $UrlDocu = \App\UrlDocu::ConsDocumentos(Session::get('IDMODULO'));

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

    public function CambiarArchModal()
    {
        if (Auth::check()) {
            $ActIni = "n";
            $Produc = "n";
            $Animac = "n";
            $idTema = request()->get('id_tema');

            $DatCont = \App\Temas::BuscarTema($idTema);
            $DesArch = \App\SubirArcTema::DesArch($idTema, 'NO');
            $infEval = \App\Evaluacion::ListEval($idTema, 'C');

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
                    'DesArch' => $DesArch,
                    'ActIni' => $ActIni,
                    'Produc' => $Produc,
                    'Animac' => $Animac,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function CambiarVideoModal()
    {
        if (Auth::check()) {
            $ActIni = "n";
            $Produc = "n";
            $Animac = "n";
            $idTema = request()->get('id_tema');

            $DatCont = \App\Temas::BuscarTema($idTema);
            $DesVid = \App\ContDidactico::BuscarTema($idTema, 'NO');
            $infEval = \App\Evaluacion::ListEval($idTema, 'C');

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

    public function CambiarArchModalZona()
    {
        if (Auth::check()) {
            $idTema = request()->get('id_tema');
            $DesArch = \App\SubirArcTema::DesArch($idTema, 'SI');
            $Sesiones = \App\sesiones::Guardar(Auth::user()->id);
            if (request()->ajax()) {
                return response()->json([
                    'DesArch' => $DesArch,
                ]);
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
            $DatEva = \App\Evaluacion::DatosEvla($DesEva->id, 'INFALUM');
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

    public function consulPregAlumno()
    {
        if (Auth::check()) {
            $IdPreg = request()->get('Pregunta');
            $TipPreg = request()->get('TipPregunta');

            if ($TipPreg == "PREGENSAY") {
                $PregEnsayo = \App\EvalPregEnsay::consulPregEnsay($IdPreg);
                $RespPregEnsayo = \App\RespEvalEnsay::DesResp($IdPreg, Auth::user()->id);
                if (request()->ajax()) {
                    return response()->json([
                        'PregEnsayo' => $PregEnsayo,
                        'RespPregEnsayo' => $RespPregEnsayo,
                    ]);
                }
            } else if ($TipPreg == "COMPLETE") {
                $PregComple = \App\EvalPregComplete::ConsultComplete($IdPreg);
                $RespPregComple = \App\RespEvalComp::DesResp($IdPreg, Auth::user()->id);
                if (request()->ajax()) {
                    return response()->json([
                        'PregComple' => $PregComple,
                        'RespPregComple' => $RespPregComple,
                    ]);
                }
            } else if ($TipPreg == "OPCMULT") {
                $PregMult = \App\PregOpcMul::ConsulPreg($IdPreg);
                $OpciMult = \App\OpcPregMul::ConsulGrupOpcPreg($IdPreg);
                $RespPregMul = \App\OpcPregMul::BuscOpcResp($IdPreg, Auth::user()->id);

                if (request()->ajax()) {
                    return response()->json([
                        'PregMult' => $PregMult,
                        'OpciMult' => $OpciMult,
                        'RespPregMul' => $RespPregMul,
                    ]);
                }
            } else if ($TipPreg == "VERFAL") {
                $PregVerFal = \App\EvalVerFal::ConVerFal($IdPreg);
                $RespPregVerFal = \App\EvalVerFal::VerFalResp($IdPreg, Auth::user()->id);
                if (request()->ajax()) {
                    return response()->json([
                        'PregVerFal' => $PregVerFal,
                        'RespPregVerFal' => $RespPregVerFal,
                    ]);
                }
            } else if ($TipPreg == "RELACIONE") {
                $PregRelacione = \App\PregRelacione::ConRela($IdPreg);
                $PregRelIndi = \App\EvalRelacione::PregRelDef($IdPreg);
                $PregRelResp = \App\EvalRelacioneOpc::PregRelOpc($IdPreg);
                $PregRelRespAdd = \App\EvalRelacioneOpc::PregRelOpcAdd($IdPreg);

                $RespPregRelacione = \App\RespEvalRelacione::RelacResp($IdPreg, Auth::user()->id);

                if (request()->ajax()) {
                    return response()->json([
                        'PregRelacione' => $PregRelacione,
                        'PregRelIndi' => $PregRelIndi,
                        'PregRelResp' => $PregRelResp,
                        'PregRelRespAdd' => $PregRelRespAdd,
                        'RespPregRelacione' => $RespPregRelacione,

                    ]);
                }
            } else if ($TipPreg == "TALLER") {
                $PregTaller = \App\EvalTaller::PregTaller($IdPreg);
                $RespPregTaller = \App\RespEvalTaller::RespEvalTallerAlum($IdPreg, Auth::user()->id);
                if (request()->ajax()) {
                    return response()->json([
                        'PregTaller' => $PregTaller,
                        'RespPregTaller' => $RespPregTaller,
                    ]);
                }
            }

        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }

    }

    public function vistoContenido()
    {
        if (Auth::check()) {
            $id = request()->get('id');
            $estado = request()->get('estado');

            $TemasDocentes = \App\TemasDocentes::Guardar($id, $estado);
            if ($estado == "SI") {
                $Log = \App\Log::Guardar('Cambio de estado de Visualizacion a Visto ', $id);
            } else {
                $Log = \App\Log::Guardar('Cambio de estado de Visualizacion no  Visto ', $id);
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
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function HabiliContenido()
    {
        if (Auth::check()) {
            $Hab = request()->get('idhabi');
            $Tem = request()->get('idTema');
            $cambio = \App\TemasDocentes::GuardarHabi($Tem, $Hab);

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
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function OrdenTemas()
    {
        if (auth::check()) {
            $datos = request()->all();
            
            $cambioOrd = \App\OrdenTemas::OrdenTemas($datos);
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
            $cambio = \App\TemasDocentes::GuardarMost($Tem, $Most);

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

    public function ZonaPlay($Alum)
    {
        if (Auth::check()) {
        $Alumno = \App\Alumnos::Buscar($Alum);
        $NAlumno = $Alumno->nombre_alumno ." ".$Alumno->apellido_alumno;
        $Gradoalumno = $Alumno->grado_alumno;
        Session::put('ZonaJuegoAct', 'si');
        return view('ZonaPlay.Principal', compact( 'NAlumno', 'Gradoalumno'));
        }else{
            return redirect("/");

        }

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
