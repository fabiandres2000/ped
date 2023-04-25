<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ForoController extends Controller
{

    public function Gestion()
    {
        if (Auth::check()) {

            $busqueda = request()->get('txtbusqueda');
            $actual = request()->get('page');
            if ($actual == null || $actual == "") {
                $actual = 1;
            }
            $limit = 5;
            if (Session::get('TIPCONT') == 'ASI') {
                $Foros = \App\Foro::Gestion($busqueda, $actual, $limit);
                $numero_filas = \App\Foro::numero_de_registros(request()->get('txtbusqueda'));
            } else {
                $Foros = \App\ForoModulos::Gestion($busqueda, $actual, $limit);
                $numero_filas = \App\ForoModulos::numero_de_registros(request()->get('txtbusqueda'));
            }

            $paginas = ceil($numero_filas / $limit); //$numero_filas/10;
            $PanelActivos = self::PanelActivos();
            return view('Foro.Gestion', compact('numero_filas', 'paginas', 'actual', 'limit', 'busqueda', 'Foros'));
        }
    }

    public function Nuevo()
    {
        if (Auth::check()) {
            if (Session::get('TIPCONT') == 'ASI') {
                $Foro = new \App\Foro();
            } else {
                $Foro = new \App\ForoModulos();
            }

            return view('Foro.Nuevo', compact('Foro'));
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

    public function Guardar()
    {
        if (Auth::check()) {
            $this->validate(request(), [
                'titulo' => 'required|unique:foro,titulo',
                'contenido' => 'required',
            ], [
                'titulo.required' => 'El Titulo es Obligatorio',
                'titulo.unique' => 'El Titulo ya Existe',
                'contenido.required' => 'El Contenido es obligatorio',
            ]);
            $data = request()->all();
            if (Session::get('TIPCONT') == 'ASI') {
                $respuesta = \App\Foro::Guardar($data);

            } else {
                $respuesta = \App\ForoModulos::Guardar($data);

            }

            if ($respuesta) {
                $Log = \App\Log::Guardar('Foro Guardado', $respuesta->id);
                return redirect('Foro/Gestion')->with('success', 'Datos Guardados');
            } else {
                return redirect('Foro/Gestion')->with('error', 'Datos no Guardados');
            }
        } else {
            return redirect("/")->with("error", "Su sesion ha terminado");
        }
    }

    public function editar($id)
    {
        if (Auth::check()) {
            if (Session::get('TIPCONT') == 'ASI') {
                $Foro = \App\Foro::BuscarForo($id);
            } else {
                $Foro = \App\ForoModulos::BuscarForo($id);
            }
            return view('Foro.Editar', compact('Foro'));
        } else {
            return redirect("/")->with("error", "Su sesion ha terminado");
        }
    }

    public function modificar($id)
    {
        if (Auth::check()) {
            $Foro = \App\Foro::BuscarForo($id);
            $this->validate(request(), [
                'titulo' => 'required|unique:foro,titulo,' . $Foro->id,
                'contenido' => 'required',
            ], [
                'titulo.required' => 'El Titulo es Obligatorio',
                'titulo.unique' => 'El Titulo ya Existe',
                'contenido.required' => 'El contenido es obligatorio',
            ]);
            $data = request()->all();
            if (Session::get('TIPCONT') == 'ASI') {
                $respuesta = \App\Foro::modificar($data, $id);
            } else {
                $respuesta = \App\ForoModulos::modificar($data, $id);
            }
            if ($respuesta) {
                $Log = \App\Log::Guardar('Foro Modificado', $id);
                return redirect('/Foro/Gestion')->with('success', 'Datos Guardados');
            } else {
                return redirect('Foro/Gestion')->with('error', 'Datos no Guardados');
            }
        } else {
            return redirect("/")->with("error", "Su sesion ha terminado");
        }
    }

    public function Eliminar()
    {
        $mensaje = "";
        $id = request()->get('id');
        if (Auth::check()) {
            if (Session::get('TIPCONT') == 'ASI') {
                $Foro = \App\Foro::BuscarForo($id);
                $estado = "ACTIVO";
                if ($Foro->estado_foro == "ACTIVO") {
                    $estado = "ELIMINADO";
                } else {
                    $estado = "ACTIVO";
                }
                $respuesta = \App\Foro::editarestado($id, $estado);
            } else {
                $Foro = \App\ForoModulos::BuscarForo($id);
                $estado = "ACTIVO";
                if ($Foro->estado_foro == "ACTIVO") {
                    $estado = "ELIMINADO";
                } else {
                    $estado = "ACTIVO";
                }
                $respuesta = \App\ForoModulos::editarestado($id, $estado);
            }

            if ($respuesta) {
                if ($estado == "ELIMINADO") {
                    $Log = \App\Log::Guardar('Foro Eliminado', $id);
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

    public function ConsulContenido()
    {
        $mensaje = "";
        $id = request()->get('idf');
        if (Auth::check()) {
            $Foro = \App\Foro::BuscarForo($id);

            if (request()->ajax()) {
                return response()->json([
                    'contenido' => $Foro->contenido,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su sesion ha terminado");
        }
    }

    public function Comentarios($id)
    {
        if (Auth::check()) {
            if (Session::get('TIPCONT') == 'ASI') {
                $Foro = \App\Foro::BuscarForo($id);

                $id_foro = $id;

                $Asignatura = \App\Modulos::Desmodulo($Foro->id_asig);
                $Comentarios = \App\Comentarios::listar($id);
            } else {
                $Foro = \App\ForoModulos::BuscarForo($id);

                $id_foro = $id;

                $Asignatura = \App\GradosModulos::Desmodulo($Foro->id_asig);
                $Comentarios = \App\ComentariosForoMod::listar($id);
            }

            return view('Foro.Comentarios', compact('Foro', 'Asignatura', 'id_foro', 'Comentarios'));
        } else {
            return redirect("/")->with("error", "Su sesion ha terminado");
        }
    }

    public function GuardarComentarios()
    {
        if (Auth::check()) {
            $this->validate(request(), [
                'comentario' => 'required',
            ], [
                'comentario.required' => 'El Comentario es Obligatorio',
            ]);
            $data = request()->all();
            if (Session::get('TIPCONT') == 'ASI') {
                $respuesta = \App\Comentarios::Guardar($data);

            } else {
                $respuesta = \App\ComentariosForoMod::Guardar($data);

            }

            if ($respuesta) {
                return redirect('Foro/Comentarios/' . $data["id_foro"])->with('success', 'Datos Guardados');
            } else {
                return redirect('Foro/Comentarios/' . $data["id_foro"])->with('error', 'Datos no Guardados');
            }
        }
    }

}
