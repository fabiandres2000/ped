<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ModuloEController extends Controller
{
    public function GestionAsignatura()
    {
        if (Auth::check()) {
            $busqueda = request()->get('txtbusqueda');
            $nombre = request()->get('nombre');
            $actual = request()->get('page');
            if ($actual == null || $actual == "") {
                $actual = 1;
            }
            $limit = 10;
            $Asignatura = \App\AsignaturasModuloE::Gestion($busqueda, $actual, $limit, $nombre);
            $numero_filas = \App\AsignaturasModuloE::numero_de_registros(request()->get('txtbusqueda'), $nombre);
            $ListAreas = \App\AreasModE::listar();

            $select_Areas = "<option value='' selected>TODAS LAS ÁREAS</option>";
            foreach ($ListAreas as $Area) {
                if ($Area->id == $nombre) {
                    $select_Areas .= "<option value='$Area->id' selected> " . strtoupper($Area->nombre_area) . "</option>";
                } else {
                    $select_Areas .= "<option value='$Area->id' >" . strtoupper($Area->nombre_area) . "</option>";
                }
            }

            $paginas = ceil($numero_filas / $limit); //$numero_filas/10;
            return view('ModuloE.GestionAsignaturas', compact('numero_filas', 'paginas', 'actual', 'limit', 'busqueda', 'Asignatura', 'select_Areas'));
        } else {
            return redirect("/")->with("error", "Su sesión ha terminado");
        }
    }

    public function NuevaAsignatura()
    {
        $opc = "nuevo";
        $icompe = 1;
        $icompo = 1;

        $trComp = "";
        $trComponentes = "";
        if (Auth::check()) {
            $Asig = new \App\AsignaturasModuloE();
            $Docentes = \App\Profesores::Listar();
            $Areas = \App\AreasModE::listar();

            return view('ModuloE.NuevaAsignatura', compact('Asig', 'opc', 'icompe', 'trComp', 'Areas', 'trComponentes', 'icompo', 'Docentes'));
        } else {
            return redirect("/")->with("error", "Su sesión ha terminado");
        }
    }

    public function CargcompetenciasAsig()
    {
        $Grado = request()->get('Grado');
        $Compo = \App\ComponentesModuloE::listarxGrado($Grado);
        $Compe = \App\CompetenciasModuloE::listarxGrado($Grado);

        $select_compe = "<option value='' selected>Seleccione la Competencia</option>";
        foreach ($Compe as $Com) {
            $select_compe .= "<option value='$Com->id' >" . strtoupper($Com->nombre) . "</option>";
        }

        $select_compo = "<option value='' selected>Seleccione el Componente</option>";
        foreach ($Compo as $Comp) {
            $select_compo .= "<option value='$Comp->id' >" . strtoupper($Comp->nombre) . "</option>";
        }

        if (request()->ajax()) {
            return response()->json([
                'select_compe' => $select_compe,
                'select_compo' => $select_compo,
            ]);
        }
    }

    public function GuardarAsignaturas()
    {
        if (Auth::check()) {

            $this->validate(request(), [
                'nombre' => 'required',
                'area' => 'required',
                'grado' => 'required',
            ], [
                'nombre.required' => 'Debe Ingresar el nombre de la Asignatura',
                'area.required' => 'Debe Seleccionar el Area',
                'grado.required' => 'Debe Seleccionar el Grado',
            ]);
            $data = request()->all();

            if (request()->hasfile('imagen')) {
                foreach (request()->file('imagen') as $file) {
                    $name = rand(1, 100) . '_' . $file->getClientOriginalName();
                    $file->move(public_path() . '/app-assets/images/Img_ModuloE/', $name);
                    $img = $name;
                }
            }

            $data['img'] = $img;

            $Asig = \App\AsignaturasModuloE::Guardar($data);
            if ($Asig) {
                $Compe = \App\AsigCompmduloE::Guardar($data, $Asig->id);
                $Compo = \App\AsigComponentemduloE::Guardar($data, $Asig->id);
                return redirect('ModuloE/GestionAsignaturas')->with('success', 'Datos Guardados');
            } else {
                return redirect('ModuloE/GestionAsignaturas')->with('error', 'Datos no Guardados');
            }
        } else {
            return redirect("/")->with("error", "Su sesión ha terminado");
        }
    }

    public function EditarAsignatura($id)
    {
        $opc = "editar";
        $trComp = "";
        $trComponentes = "";

        if (Auth::check()) {
            $Asig = \App\AsignaturasModuloE::BuscarAsig($id);
            $Competencia = \App\CompetenciasModuloE::listar();
            $Componentes = \App\ComponentesModuloE::listar();
            $ListComp = \App\AsigCompmduloE::Listarxasig($id);
            $ListCompo = \App\AsigComponentemduloE::Listarxasig($id);
            $Areas = \App\AreasModE::listar();
            $Docentes = \App\Profesores::Listar();

            $icompe = 1;
            foreach ($ListComp as $Comp) {
                $trComp .= '<tr id="tr_Compe_' . $icompe . '">
                    <td class="text-truncate">' . $Comp->nombre . '</td><input type="hidden" id="txtcomp' . $icompe . '" name="txtComp[]"  value="' . $Comp->id . '">
                    <td class="text-truncate">
                    <a onclick="$.DelComp(' . $icompe . ')" class="btn btn-danger btn-sm btnQuitarCompe text-white"  title="Eliminar"><i class="fa fa-trash-o font-medium-3" aria-hidden="true"></i></a>&nbsp;
                    </td>
                </tr>';
                $icompe++;
            }

            $icompo = 1;
            foreach ($ListCompo as $Compo) {
                $trComponentes .= '<tr id="tr_Compo_' . $icompo . '">
                    <td class="text-truncate">' . $Compo->nombre . '</td><input type="hidden" id="txtcomponente' . $icompo . '" name="txtComponentes[]"  value="' . $Compo->id . '">
                    <td class="text-truncate">
                    <a onclick="$.DelCompo(' . $icompo . ')" class="btn btn-danger btn-sm btnQuitarCompo text-white"  title="Eliminar"><i class="fa fa-trash-o font-medium-3" aria-hidden="true"></i></a>&nbsp;
                    </td>
                </tr>';
                $icompo++;
            }
            return view('ModuloE.EditarAsignaturas', compact('Asig', 'opc', 'Competencia', 'trComp', 'icompe', 'Areas', 'Componentes', 'icompo', 'trComponentes', 'Docentes'));
        } else {
            return redirect("/")->with("error", "Su sesión ha terminado");
        }
    }

    public function ModificarAsignatura($id)
    {
        if (Auth::check()) {
            $this->validate(request(), [
                'nombre' => 'required',
                'grado' => 'required',
                'docente' => 'required',
            ], [
                'nombre.required' => 'Debe Ingresar el nombre de la Asignatura',
                'grado.required' => 'Debe Seleccionar el Grado',
                'docente.required' => 'Debe Seleccionar el Docente Encargado',
            ]);
            $data = request()->all();

            if (request()->hasfile('imagen')) {
                foreach (request()->file('imagen') as $file) {
                    $name = rand(1, 100) . '_' . $file->getClientOriginalName();
                    $file->move(public_path() . '/app-assets/images/Img_ModuloE/', $name);
                    $img = $name;
                }
            } else {
                $img = $data['img_asig'];
            }
            $data['img'] = $img;

            $Asig = \App\AsignaturasModuloE::modificar($data, $id);
            if ($Asig) {
                $Compe = \App\AsigCompmduloE::modificar($data, $id);
                $Compo = \App\AsigComponentemduloE::modificar($data, $id);
                return redirect('ModuloE/GestionAsignaturas')->with('success', 'Datos Guardados');
            } else {
                return redirect('ModuloE/GestionAsignaturas')->with('error', 'Datos no Guardados');
            }
        } else {
            return redirect("/")->with("error", "Su sesión ha terminado");
        }
    }

    public function EliminarAsignaturas()
    {
        $mensaje = "";
        $id = request()->get('id');
        if (Auth::check()) {
            $Asig = \App\AsignaturasModuloE::BuscarAsig($id);
            $estado = "ACTIVO";
            if ($Asig->estado == "ACTIVO") {
                $estado = "ELIMINADO";
            } else {
                $estado = "ACTIVO";
            }

            $respuesta = \App\AsignaturasModuloE::editarestado($id, $estado);

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
            return redirect("/")->with("error", "Su sesión ha terminado");
        }
    }

    public function GestionCompetencia()
    {
        if (Auth::check()) {
            $busqueda = request()->get('txtbusqueda');
            $actual = request()->get('page');
            if ($actual == null || $actual == "") {
                $actual = 1;
            }
            $limit = 10;
            $Competencias = \App\CompetenciasModuloE::Gestion($busqueda, $actual, $limit);
            $numero_filas = \App\CompetenciasModuloE::numero_de_registros(request()->get('txtbusqueda'));

            $paginas = ceil($numero_filas / $limit); //$numero_filas/10;
            return view('ModuloE.GestionCompetencia', compact('numero_filas', 'paginas', 'actual', 'limit', 'busqueda', 'Competencias'));
        } else {
            return redirect("/")->with("error", "Su sesión ha terminado");
        }
    }

    public function NuevaCompetencia()
    {
        $opc = "nuevo";
        if (Auth::check()) {
            $Comp = new \App\CompetenciasModuloE();

            return view('ModuloE.NuevaCompetencia', compact('Comp', 'opc'));
        } else {
            return redirect("/")->with("error", "Su sesión ha terminado");
        }
    }

    public function GuardarCompetencia()
    {
        if (Auth::check()) {

            $this->validate(request(), [
                'nombre' => 'required',
            ], [
                'nombre.required' => 'Debe Ingresar el nombre de la Competencia',
            ]);
            $data = request()->all();

            $Asig = \App\CompetenciasModuloE::Guardar($data);
            if ($Asig) {
                return redirect('ModuloE/GestionCompetencia')->with('success', 'Datos Guardados');
            } else {
                return redirect('ModuloE/GestionCompetencia')->with('error', 'Datos no Guardados');
            }
        } else {
            return redirect("/")->with("error", "Su sesión ha terminado");
        }
    }

    public function EditarCompetencia($id)
    {
        $opc = "editar";

        if (Auth::check()) {
            $Comp = \App\CompetenciasModuloE::BuscarAsig($id);
            return view('ModuloE.EditarCompetencia', compact('Comp', 'opc'));
        } else {
            return redirect("/")->with("error", "Su sesión ha terminado");
        }
    }

    public function ModificarCompetencia($id)
    {
        if (Auth::check()) {
            $this->validate(request(), [
                'nombre' => 'required',
            ], [
                'nombre.required' => 'Debe Ingresar el nombre de la Competencia',
            ]);
            $data = request()->all();

            $Asig = \App\CompetenciasModuloE::modificar($data, $id);
            if ($Asig) {
                return redirect('ModuloE/GestionCompetencia')->with('success', 'Datos Guardados');
            } else {
                return redirect('ModuloE/GestionCompetencia')->with('error', 'Datos no Guardados');
            }
        } else {
            return redirect("/")->with("error", "Su sesión ha terminado");
        }
    }

    public function EliminarCompetencia()
    {
        $mensaje = "";
        $id = request()->get('id');
        if (Auth::check()) {
            $Asig = \App\CompetenciasModuloE::BuscarAsig($id);
            $estado = "ACTIVO";
            if ($Asig->estado == "ACTIVO") {
                $estado = "ELIMINADO";
            } else {
                $estado = "ACTIVO";
            }

            $respuesta = \App\CompetenciasModuloE::editarestado($id, $estado);

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
            return redirect("/")->with("error", "Su sesión ha terminado");
        }
    }

    public function GestionTema()
    {
        if (Auth::check()) {
            $busqueda = request()->get('txtbusqueda');
            $nombre = request()->get('nombre');
            $actual = request()->get('page');
            if ($actual == null || $actual == "") {
                $actual = 1;
            }
            $limit = 10;
            $Temas = \App\TemasModuloE::Gestion($busqueda, $actual, $limit, $nombre);
            $numero_filas = \App\TemasModuloE::numero_de_registros(request()->get('txtbusqueda'), $nombre);
            $ListAsig = \App\AsignaturasModuloE::listar();

            $select_Asig = "<option value='' selected>TODAS LAS ASIGNATURAS</option>";
            foreach ($ListAsig as $Asig) {
                if ($Asig->id == $nombre) {
                    $select_Asig .= "<option value='$Asig->id' selected> " . strtoupper($Asig->nombre . ' Grado ' . $Asig->grado . '°') . "</option>";
                } else {
                    $select_Asig .= "<option value='$Asig->id' >" . strtoupper($Asig->nombre . ' Grado ' . $Asig->grado . '°') . "</option>";
                }
            }

            $paginas = ceil($numero_filas / $limit); //$numero_filas/10;
            return view('ModuloE.GestionTemas', compact('numero_filas', 'paginas', 'actual', 'limit', 'busqueda', 'Temas', 'select_Asig'));
        } else {
            return redirect("/")->with("error", "Su sesión ha terminado");
        }
    }

    public function NuevoTema()
    {
        $opc = "nuevo";
        $tr_img = "";
        if (Auth::check()) {
            $Tema = new \App\TemasModuloE();
            $Asignaturas = \App\AsignaturasModuloE::listar();
            $Componentes = \App\ComponentesModuloE::listar();

            return view('ModuloE.NuevoTema', compact('Tema', 'opc', 'Asignaturas', 'Componentes', 'tr_img'));
        } else {
            return redirect("/")->with("error", "Su sesión ha terminado");
        }
    }

    public function GuardarTemas()
    {
        if (Auth::check()) {
            $data = request()->all();
            $anima = "";
            if ($data['animacion'] === "SI") {
                if (request()->hasfile('archididatico')) {
                    foreach (request()->file('archididatico') as $file) {
                        $prefijo = substr(md5(uniqid(rand())), 0, 6);
                        $name = self::sanear_string($prefijo . '_' . $file->getClientOriginalName());
                        $Titu = $file->getClientOriginalName();
                        $file->move(public_path() . '/app-assets/Contenido_Didactico_ME/', $name);
                        $arch[] = $name;
                        $archTit[] = $Titu;
                    }
                    $data['archi'] = $arch;
                    $data['TituAnim'] = $archTit;
                }
            }

            $Tem = \App\TemasModuloE::Guardar($data);
            if ($Tem) {
                $data['tema_id'] = $Tem->id;

                if (request()->hasfile('archididatico')) {
                    $ContTemaDidac = \App\ContDidacticoME::GuardarContDidctico($data);
                }

                if ($data['tipo_contenido'] == "DOCUMENTO") {
                    $Documento = \App\TemasModuloE_Doc::Guardar($data);
                } elseif ($data['tipo_contenido'] == "IMAGEN") {
                    if (request()->hasfile('ImageFile')) {
                        foreach (request()->file('ImageFile') as $file) {
                            $prefijo = substr(md5(uniqid(rand())), 0, 6);
                            $name = self::sanear_string($prefijo . '_' . $file->getClientOriginalName());
                            $file->move(public_path() . '/app-assets/images/Imagen_Tema_ModuloE/', $name);
                            $img[] = $name;
                        }
                        $data['ImgTema'] = $img;
                    }

                    $Imagen = \App\TemasModuloE_Img::Guardar($data);
                } else {
                    if (request()->hasfile('VideoFile')) {
                        foreach (request()->file('VideoFile') as $file) {
                            $prefijo = substr(md5(uniqid(rand())), 0, 6);
                            $name = self::sanear_string($prefijo . '_' . $file->getClientOriginalName());
                            $file->move(public_path() . '/app-assets/Video_Tema_ModuloE/', $name);
                            $Vid = $name;
                        }
                    }
                    $data['Vid'] = $Vid;
                    $Video = \App\TemasModuloE_Vid::Guardar($data);
                }

                return redirect('ModuloE/GestionTema')->with('success', 'Datos Guardados');
            } else {
                return redirect('ModuloE/GestionTema')->with('error', 'Datos no Guardados');
            }
        } else {
            return redirect("/")->with("error", "Su sesión ha terminado");
        }
    }

    public function EditarTema($id)
    {
        $opc = "editar";
        $tr_Vid = "";

        if (Auth::check()) {
            $Tema = \App\TemasModuloE::BuscarTem($id);

            if ($Tema->animacion == "SI") {
                $Video = \App\ContDidacticoME::ConsultarContDidctico($id);
                $j = 1;
                foreach ($Video as $Vid) {
                    $tr_Vid .= '<tr id="trVid_' . $Vid->id . '">
                    <td class="text-truncate">' . $j . '</td>
                    <td class="text-truncate">' . $Vid->cont_didactico . '</td>
                    <td class="text-truncate">
                    <a onclick="$.MostVide(this.id)" id="' . $Vid->id . '"  data-archivo="' . $Vid->cont_didactico . '" class="btn btn-primary btn-sm btnVer text-white"  title="Ver"><i class="fa fa-search font-medium-3" aria-hidden="true"></i></a>&nbsp;
                    <a onclick="$.DelVide(' . $Vid->id . ')" class="btn btn-danger btn-sm btnQuitarVid text-white"  title="Eliminar"><i class="fa fa-trash-o font-medium-3" aria-hidden="true"></i></a>&nbsp;
                </td>
                </tr>';
                    $j++;
                }
            }

            $Asignaturas = \App\AsignaturasModuloE::listar();

            return view('ModuloE.EditarTema', compact('Tema', 'opc', 'Asignaturas', 'tr_Vid'));
        } else {
            return redirect("/")->with("error", "Su sesión ha terminado");
        }
    }

    public function InfContenido()
    {
        $IdTema = request()->get('IdTema');
        $TipCon = request()->get('TipCon');

        if (Auth::check()) {

            if ($TipCon == "DOCUMENTO") {
                $DesCont = \App\TemasModuloE_Doc::BuscarTema($IdTema);
            } else if ($TipCon == "IMAGEN") {
                $DesCont = \App\TemasModuloE_Img::BuscarTema($IdTema);
            } else {
                $DesCont = \App\TemasModuloE_Vid::BuscarTema($IdTema);
            }

            if (request()->ajax()) {
                return response()->json([
                    'DesCont' => $DesCont,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su sesión ha terminado");
        }
    }

    public function DelImgModuloE()
    {

        $mensaje = "";
        $estado = "no";
        $id = request()->get('id');
        if (Auth::check()) {
            $respuesta = \App\TemasModuloE_Img::EliminarImg($id);
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

    public function DelVidModuloE()
    {

        $mensaje = "";
        $estado = "no";
        $id = request()->get('id');
        if (Auth::check()) {
            $respuesta = \App\ContDidacticoME::ElimninarVideo($id);
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

    public function ConsulAnimaModE()
    {
        if (Auth::check()) {
            $IdTema = request()->get('TemaAni');
            $DesAnimaciones = \App\ContDidacticoME::ConsultarContDidctico($IdTema);
            $Temas = \App\TemasModuloE::BuscarTem($IdTema);
            $Sesiones = \App\sesiones::Guardar(Auth::user()->id);
            if (request()->ajax()) {
                return response()->json([
                    'DesAnim' => $DesAnimaciones,
                    'TitTema' => $Temas->titulo,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function ModificarTema($id)
    {
        if (Auth::check()) {
            $data = request()->all();

            if ($data['animacion'] === "SI") {
                if (request()->hasfile('archididatico')) {
                    foreach (request()->file('archididatico') as $file) {
                        $prefijo = substr(md5(uniqid(rand())), 0, 6);
                        $name = self::sanear_string($prefijo . '_' . $file->getClientOriginalName());
                        $Titu = $file->getClientOriginalName();
                        $file->move(public_path() . '/app-assets/Contenido_Didactico_ME/', $name);
                        $arch[] = $name;
                        $archTit[] = $Titu;
                    }
                    $data['archi'] = $arch;
                    $data['TituAnim'] = $archTit;
                }
            }

            $Tem = \App\TemasModuloE::modificar($data, $id);
            if ($Tem) {

                if (request()->hasfile('archididatico')) {
                    $ContTemaDidac = \App\ContDidacticoME::GuardarContDidctico($data);
                }

                if ($data['tipo_contenido'] == "DOCUMENTO") {
                    $Documento = \App\TemasModuloE_Doc::modificar($data, $id);
                } elseif ($data['tipo_contenido'] == "IMAGEN") {
                    if (request()->hasfile('ImageFile')) {
                        foreach (request()->file('ImageFile') as $file) {
                            $prefijo = substr(md5(uniqid(rand())), 0, 6);
                            $name = self::sanear_string($prefijo . '_' . $file->getClientOriginalName());
                            $file->move(public_path() . '/app-assets/images/Imagen_Tema_ModuloE/', $name);
                            $img[] = $name;
                        }
                        $data['ImgTema'] = $img;
                        $Imagen = \App\TemasModuloE_Img::Guardar($data);
                    }
                } else {
                    if (request()->hasfile('VideoFile')) {
                        foreach (request()->file('VideoFile') as $file) {
                            $prefijo = substr(md5(uniqid(rand())), 0, 6);
                            $name = self::sanear_string($prefijo . '_' . $file->getClientOriginalName());
                            $file->move(public_path() . '/app-assets/Video_Tema_ModuloE/', $name);
                            $Vid = $name;
                        }
                    } else {
                        $Vid = $data['tema_vid'];
                    }
                    $data['Vid'] = $Vid;
                    $Video = \App\TemasModuloE_Vid::Guardar($data);
                }
                return redirect('ModuloE/GestionTema')->with('success', 'Datos Guardados');
            } else {
                return redirect('ModuloE/GestionTema')->with('error', 'Datos no Guardados');
            }
        } else {
            return redirect("/")->with("error", "Su sesión ha terminado");
        }
    }

    public function EliminarTema()
    {
        $mensaje = "";
        $id = request()->get('id');
        if (Auth::check()) {
            $Tema = \App\TemasModuloE::BuscarTem($id);
            $estado = "ACTIVO";
            if ($Tema->estado == "ACTIVO") {
                $estado = "ELIMINADO";
            } else {
                $estado = "ACTIVO";
            }
            $respuesta = \App\TemasModuloE::editarestado($id, $estado);

            if ($respuesta) {
                if ($estado == "ELIMINADO") {
                    if ($Tema->tipo_contenido === "DOCUMENTO") {
                        $respuesta = \App\TemasModuloE_Doc::ElimnarRegistros($id);
                    } else if ($Tema->tipo_contenido === "IMAGEN") {
                        $respuesta = \App\TemasModuloE_Img::ElimnarRegistros($id);
                    } else {
                        $respuesta = \App\TemasModuloE_Vid::ElimnarRegistros($id);
                    }
                    $Log = \App\Log::Guardar('Tema Eliminado', $id);
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

    public function GestionComponentes()
    {
        if (Auth::check()) {
            $busqueda = request()->get('txtbusqueda');
            $actual = request()->get('page');
            if ($actual == null || $actual == "") {
                $actual = 1;
            }
            $limit = 10;
            $Componentes = \App\ComponentesModuloE::Gestion($busqueda, $actual, $limit);
            $numero_filas = \App\ComponentesModuloE::numero_de_registros(request()->get('txtbusqueda'));

            $paginas = ceil($numero_filas / $limit); //$numero_filas/10;
            return view('ModuloE.GestionComponentes', compact('numero_filas', 'paginas', 'actual', 'limit', 'busqueda', 'Componentes'));
        } else {
            return redirect("/")->with("error", "Su sesión ha terminado");
        }
    }

    public function NuevoComponente()
    {
        $opc = "nuevo";
        if (Auth::check()) {
            $Comp = new \App\ComponentesModuloE();

            return view('ModuloE.NuevoComponente', compact('Comp', 'opc'));
        } else {
            return redirect("/")->with("error", "Su sesión ha terminado");
        }
    }

    public function GuardarComponente()
    {
        if (Auth::check()) {

            $this->validate(request(), [
                'nombre' => 'required',
            ], [
                'nombre.required' => 'Debe Ingresar el nombre del componente',
            ]);
            $data = request()->all();

            $Asig = \App\ComponentesModuloE::Guardar($data);
            if ($Asig) {
                return redirect('ModuloE/GestionComponentes')->with('success', 'Datos Guardados');
            } else {
                return redirect('ModuloE/GestionComponentes')->with('error', 'Datos no Guardados');
            }
        } else {
            return redirect("/")->with("error", "Su sesión ha terminado");
        }
    }

    public function EditarComponente($id)
    {
        $opc = "editar";

        if (Auth::check()) {
            $Comp = \App\ComponentesModuloE::BuscarAsig($id);

            return view('ModuloE.EditarComponente', compact('Comp', 'opc'));
        } else {
            return redirect("/")->with("error", "Su sesión ha terminado");
        }
    }

    public function ModificarComponente($id)
    {
        if (Auth::check()) {
            $this->validate(request(), [
                'nombre' => 'required',
            ], [
                'nombre.required' => 'Debe Ingresar el nombre del Componente',
            ]);
            $data = request()->all();

            $Asig = \App\ComponentesModuloE::modificar($data, $id);
            if ($Asig) {
                return redirect('ModuloE/GestionComponentes')->with('success', 'Datos Guardados');
            } else {
                return redirect('ModuloE/GestionComponentes')->with('error', 'Datos no Guardados');
            }
        } else {
            return redirect("/")->with("error", "Su sesión ha terminado");
        }
    }

    public function EliminarComponentes()
    {
        $mensaje = "";
        $id = request()->get('id');
        if (Auth::check()) {
            $Asig = \App\ComponentesModuloE::BuscarAsig($id);
            $estado = "ACTIVO";
            if ($Asig->estado == "ACTIVO") {
                $estado = "ELIMINADO";
            } else {
                $estado = "ACTIVO";
            }

            $respuesta = \App\ComponentesModuloE::editarestado($id, $estado);

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
            return redirect("/")->with("error", "Su sesión ha terminado");
        }
    }

    public function EliminarAreaSesion()
    {

        if (Auth::check()) {
            $id = request()->get('IdSes');
            $IdSesGen = request()->get('IdSesGen');
            $status = "success";

            $SesionArea = \App\SessionArea::Eliminar($id);

            if ($SesionArea) {
                $CompArea = \App\CompAreaSession::Eliminar($id);

                if ($CompArea) {
                    $PregArea = \App\ModE_PreguntAreas::Eliminar($id);

                    if ($PregArea) {
                        $status = "success";
                        $AreSim = self::UpdateTotregSesion($IdSesGen);
                    } else {
                        $status = "error";
                    }
                } else {
                    $status = "error";
                }
            } else {
                $status = "error";
            }

            if (request()->ajax()) {
                return response()->json([
                    'Resp' => $status,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function EliminarSesion()
    {
        if (Auth::check()) {

            $id = request()->get('IdSes');
            $status = "success";

            $SesionSimulacro = \App\DetaSesionesSimul::Eliminar($id);
            if ($SesionSimulacro) {
                $SesionArea = \App\SessionArea::EliminarSesion($id);
                if ($SesionArea) {
                    $CompArea = \App\CompAreaSession::EliminarSesion($id);
                    if ($CompArea) {
                        $PregArea = \App\ModE_PreguntAreas::EliminarSesion($id);
                        if ($PregArea) {
                            $status = "success";
                        } else {
                            $status = "error";
                        }
                    } else {
                        $status = "error";
                    }
                } else {
                    $status = "error";
                }
            } else {
                $status = "error";
            }

            if (request()->ajax()) {
                return response()->json([
                    'Resp' => $status,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function EliminarSimulacro()
    {
        $mensaje = "";
        $id = request()->get('id');
        if (Auth::check()) {
            $Simu = \App\Simulacros::BuscarSimu($id);
            $estado = "ACTIVO";
            if ($Simu->estado == "ACTIVO") {
                $estado = "ELIMINADO";
            } else {
                $estado = "ACTIVO";
            }

            $respuesta = \App\Simulacros::editarestado($id, $estado);

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
            return redirect("/")->with("error", "Su sesión ha terminado");
        }
    }

    public function GestionBancoPreguntas()
    {
        if (Auth::check()) {
            $busqueda = request()->get('nombre');
            $nombre = request()->get('componente');
            $actual = request()->get('page');
            if ($actual == null || $actual == "") {
                $actual = 1;
            }
            $limit = 10;

            $Preguntas = \App\BancoPregModuloE::Gestion($busqueda, $actual, $limit, $nombre);
            $numero_filas = \App\BancoPregModuloE::numero_de_registros(request()->get('nombre'), $nombre);
            $ListAsig = \App\AsignaturasModuloE::listar();
            $ListCom = \App\AsigComponentemduloE::Listarxasig($busqueda);

            $select_Asig = "<option value='' selected>TODAS LAS ASIGNATURAS</option>";
            foreach ($ListAsig as $Asig) {
                if ($Asig->id === intval($busqueda)) {
                    $select_Asig .= "<option value='$Asig->id' selected> " . strtoupper($Asig->nombre . ' Grado ' . $Asig->grado . '°') . "</option>";
                } else {
                    $select_Asig .= "<option value='$Asig->id' >" . strtoupper($Asig->nombre . ' Grado ' . $Asig->grado . '°') . "</option>";
                }
            }

            $select_Comp = "<option value='' selected>TODOS LOS COMPONENTES</option>";
            if (!empty($busqueda)) {
                foreach ($ListCom as $Comp) {
                    if ($Comp->id == $nombre) {
                        $select_Comp .= "<option value='$Comp->id' selected> " . strtoupper($Comp->nombre) . "</option>";
                    } else {
                        $select_Comp .= "<option value='$Comp->id' >" . strtoupper($Comp->nombre) . "</option>";
                    }
                }
            }

            $paginas = ceil($numero_filas / $limit); //$numero_filas/10;
            return view('ModuloE.GestionBancoPreg', compact('numero_filas', 'paginas', 'actual', 'limit', 'busqueda', 'Preguntas', 'select_Asig', 'select_Comp'));
        } else {
            return redirect("/")->with("error", "Su sesión ha terminado");
        }
    }

    public function NuevaPregunta()
    {
        $opc = "nuevo";
        if (Auth::check()) {
            $Preg = new \App\BancoPregModuloE();
            $Asignatura = \App\AsignaturasModuloE::listar();

            return view('ModuloE.NuevaPreguntas', compact('Preg', 'opc', 'Asignatura'));
        } else {
            return redirect("/")->with("error", "Su sesión ha terminado");
        }
    }

    public function CargTemas()
    {
        $idAsig = request()->get('id');

        $Partes = "";
        $Flag = "";
        if (Auth::check()) {
            $Temas = \App\TemasModuloE::listarxAssig($idAsig);
            $select_temas = "<option value='' selected>Seleccione</option>";
            foreach ($Temas as $Tem) {
                $select_temas .= "<option value='$Tem->id' >" . strtoupper($Tem->titulo) . "</option>";
            }

            $Area = \App\AsignaturasModuloE::BuscarAsig($idAsig);

            if ($Area->area == 5) {
                $Flag = "si";
                $Partes = \App\PartesModuloE::LisPartes();
            }

            if (request()->ajax()) {
                return response()->json([
                    'select_temas' => $select_temas,
                    'Flag' => $Flag,
                    'Partes' => $Partes,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su sesión ha terminado");
        }
    }

    public function CargarCompTema()
    {

        if (Auth::check()) {
            $idAsig = request()->get('asig');
            $Compo = \App\AsigComponentemduloE::Listarxasig($idAsig);

            $select_compo = "<option value='' selected>Seleccione el Componente</option>";
            foreach ($Compo as $Comp) {
                $select_compo .= "<option value='$Comp->id' >" . strtoupper($Comp->nombre) . "</option>";
            }

            if (request()->ajax()) {
                return response()->json([
                    'select_compo' => $select_compo,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su sesión ha terminado");
        }
    }

    public function CargPartes()
    {
        $idAsig = request()->get('idArea');

        $Partes = "";
        $Flag = "";
        if (Auth::check()) {

            $Area = \App\AsignaturasModuloE::BuscarAsig($idAsig);

            if ($Area->area == 5) {
                $Flag = "si";
                $Partes = \App\PartesModuloE::LisPartes();
            }

            if (request()->ajax()) {
                return response()->json([
                    'Partes' => $Partes,
                    'Flag' => $Flag,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su sesión ha terminado");
        }
    }

    public function Cargcompe_compo()
    {
        $idAsig = request()->get('id');
        if (Auth::check()) {
            $Compe = \App\AsigCompmduloE::Listarxasig($idAsig);
            $Compo = \App\AsigComponentemduloE::Listarxasig($idAsig);

            $select_compe = "<option value='' selected>Seleccione la Competencia</option>";
            foreach ($Compe as $Comp) {
                $select_compe .= "<option value='$Comp->id' >" . strtoupper($Comp->nombre) . "</option>";
            }

            $select_compo = "<option value='' selected>Seleccione el Componente</option>";
            foreach ($Compo as $Comp) {
                $select_compo .= "<option value='$Comp->id' >" . strtoupper($Comp->nombre) . "</option>";
            }

            if (request()->ajax()) {
                return response()->json([
                    'select_compe' => $select_compe,
                    'select_compo' => $select_compo,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su sesión ha Terminado");
        }
    }

    ///////////////////GUARDAR BANCO PREGUNTA
    public function GuardarPreguntas()
    {
        if (Auth::check()) {
            $datos = request()->all();

            $idPreg = "";

            if ($datos['Tipreguntas'] == "PARTE 1") {

                if ($datos['preg_id'] === null) {
                    $ContEval = \App\BancoPregModuloE::Guardar($datos);
                    $idPreg = $ContEval->id;
                } else {
                    $idPreg = request()->get('preg_id');
                    $ContEval = \App\BancoPregModuloE::ModifEval($datos, $idPreg);
                }

                if ($datos['IdpreguntaPart1'] === null) {
                    $palabras = implode(",", $datos['cb_Opciones']);
                    $PregOpcMul = \App\PregOpcMulMe::Guardar($palabras, $datos['competencia'], $datos['componente'], $idPreg);

                    if ($PregOpcMul) {
                        $PreguntasParte1 = \App\PreguntasParte1::Guardar($datos, $PregOpcMul->id, $idPreg);
                        $Preguntas = \App\PreguntasParte1::ConsultarPreg($PregOpcMul->id);
                    }
                } else {

                    //     $Parte1 = \App\Parte1::Modificar($datos, $idPreg, $datos['IdpreguntaPart1']);

                    $palabras = implode(",", $datos['cb_Opciones']);
                    $PregOpcMul = \App\PregOpcMulMe::ModiPreMul($palabras, $datos['competencia'], $datos['componente'], $datos['IdpreguntaPart1'], $idPreg);
                    $PregOpcMul = \App\PregOpcMulMe::ConsulPreg($datos['IdpreguntaPart1']);

                    $PreguntasParte1 = \App\PreguntasParte1::Modificar($datos, $datos['IdpreguntaPart1']);
                    $Preguntas = \App\PreguntasParte1::ConsultarPreg($datos['IdpreguntaPart1']);
                }

                $Compe = \App\CompetenciasModuloE::BuscarAsig($datos['competencia']);
                $Compo = \App\ComponentesModuloE::BuscarAsig($datos['componente']);
                $DesCompe = $Compe->nombre;
                $DesCompo = $Compo->nombre;

                $Log = \App\Log::Guardar('Banco de Pregunta Modificado', $idPreg);

                if (request()->ajax()) {
                    return response()->json([
                        'idEval' => $idPreg,
                        'PregOpcMul' => $PregOpcMul,
                        'Preguntas' => $Preguntas,
                        'DesCompe' => $DesCompe,
                        'DesCompo' => $DesCompo,

                    ]);
                }
            } else if ($datos['Tipreguntas'] == "PARTE 2") {

                if ($datos['preg_id'] === null) {
                    $ContEval = \App\BancoPregModuloE::Guardar($datos);
                    $idPreg = $ContEval->id;
                } else {
                    $idPreg = request()->get('preg_id');
                    $ContEval = \App\BancoPregModuloE::ModifEval($datos, $idPreg);
                }

                if ($datos['IdpreguntaPart2'] === null) {
                    $PregOpcMul = \App\PregOpcMulMe::Guardar($datos['PreMulResp'], $datos['competencia'], $datos['componente'], $idPreg);
                    if ($PregOpcMul) {
                        $ConsEval = \App\CosPregModuloE::Guardar($datos, $idPreg, $PregOpcMul->id);
                        $OpciPregMul = \App\OpcPregMulModuloE::Guardar($datos, $PregOpcMul->id, $idPreg);
                        $OpciPregMul = \App\OpcPregMulModuloE::ConsulGrupOpc($PregOpcMul->id, $idPreg);
                    }
                } else {
                    $PregOpcMul = \App\PregOpcMulMe::ModiPreMul($datos['PreMulResp'], $datos['competencia'], $datos['componente'], $datos['IdpreguntaPart2'], $idPreg);
                    $PregOpcMul = \App\PregOpcMulMe::ConsulPreg($datos['IdpreguntaPart2']);
                    if ($PregOpcMul) {
                        $OpciPregMul = \App\OpcPregMulModuloE::ModOpcPreg($datos, $idPreg, $datos['IdpreguntaPart2']);
                        $OpciPregMul = \App\OpcPregMulModuloE::ConsulGrupOpc($datos['IdpreguntaPart2'], $idPreg);
                    }
                }
                $Compe = \App\CompetenciasModuloE::BuscarAsig($datos['competencia']);
                $Compo = \App\ComponentesModuloE::BuscarAsig($datos['componente']);
                $PregOpcMul['DesCompe'] = $Compe->nombre;
                $PregOpcMul['DesCompo'] = $Compo->nombre;

                $Log = \App\Log::Guardar('Banco de Pregunta Modificado', $idPreg);

                if (request()->ajax()) {
                    return response()->json([
                        'idEval' => $idPreg,
                        'PregOpcMul' => $PregOpcMul,
                        'OpciPregMul' => $OpciPregMul,
                    ]);
                }
            } else if ($datos['Tipreguntas'] == "PARTE 3") {

                if ($datos['preg_id'] === null) {
                    $ContEval = \App\BancoPregModuloE::Guardar($datos);
                    $idPreg = $ContEval->id;
                } else {
                    $idPreg = request()->get('preg_id');
                    $ContEval = \App\BancoPregModuloE::ModifEval($datos, $idPreg);
                    $ContEval = \App\BancoPregModuloE::BusPregBanc($idPreg);
                }

                if ($datos['IdpreguntaPart3'] === null) {
                    $PregOpcMul = \App\PregOpcMulMe::Guardar($datos['PreMulResp'], $datos['competencia'], $datos['componente'], $idPreg);
                    if ($PregOpcMul) {
                        $ConsEval = \App\CosPregModuloE::Guardar($datos, $idPreg, $PregOpcMul->id);
                        $OpciPregMul = \App\OpcPregMulModuloE::Guardar($datos, $PregOpcMul->id, $idPreg);
                        $OpciPregMul = \App\OpcPregMulModuloE::ConsulGrupOpc($PregOpcMul->id, $idPreg);
                    }
                } else {
                    $PregOpcMul = \App\PregOpcMulMe::ModiPreMul($datos['PreMulResp'], $datos['competencia'], $datos['componente'], $datos['IdpreguntaPart3'], $idPreg);
                    $PregOpcMul = \App\PregOpcMulMe::ConsulPreg($datos['IdpreguntaPart3']);
                    if ($PregOpcMul) {
                        $OpciPregMul = \App\OpcPregMulModuloE::ModOpcPreg($datos, $idPreg, $datos['IdpreguntaPart3']);
                        $OpciPregMul = \App\OpcPregMulModuloE::ConsulGrupOpc($datos['IdpreguntaPart3'], $idPreg);
                    }
                }
                $Compe = \App\CompetenciasModuloE::BuscarAsig($datos['competencia']);
                $Compo = \App\ComponentesModuloE::BuscarAsig($datos['componente']);
                $PregOpcMul['DesCompe'] = $Compe->nombre;
                $PregOpcMul['DesCompo'] = $Compo->nombre;

                $Log = \App\Log::Guardar('Banco de Pregunta Modificado', $idPreg);

                if (request()->ajax()) {
                    return response()->json([
                        'idEval' => $idPreg,
                        'ContEval' => $ContEval,
                        'PregOpcMul' => $PregOpcMul,
                        'OpciPregMul' => $OpciPregMul,
                    ]);
                }
            } else if ($datos['Tipreguntas'] == "PARTE 4") {

                if ($datos['preg_id'] === null) {
                    $ContEval = \App\BancoPregModuloE::Guardar($datos);
                    $idPreg = $ContEval->id;
                } else {
                    $idPreg = request()->get('preg_id');
                    $ContEval = \App\BancoPregModuloE::ModifEval($datos, $idPreg);
                    $ContEval = \App\BancoPregModuloE::BusPregBanc($idPreg);
                }

                if ($datos['IdpreguntaPart4'] === null) {
                    $PregOpcMul = \App\PregOpcMulMe::Guardar($datos['PreMulResp'], $datos['competencia'], $datos['componente'], $idPreg);
                    if ($PregOpcMul) {
                        $ConsEval = \App\CosPregModuloE::Guardar($datos, $idPreg, $PregOpcMul->id);
                        $OpciPregMul = \App\OpcPregMulModuloE::Guardar($datos, $PregOpcMul->id, $idPreg);
                        $OpciPregMul = \App\OpcPregMulModuloE::ConsulGrupOpc($PregOpcMul->id, $idPreg);
                    }
                } else {
                    $PregOpcMul = \App\PregOpcMulMe::ModiPreMul($datos['PreMulResp'], $datos['competencia'], $datos['componente'], $datos['IdpreguntaPart4'], $idPreg);
                    $PregOpcMul = \App\PregOpcMulMe::ConsulPreg($datos['IdpreguntaPart4']);
                    if ($PregOpcMul) {
                        $OpciPregMul = \App\OpcPregMulModuloE::ModOpcPreg($datos, $idPreg, $datos['IdpreguntaPart4']);
                        $OpciPregMul = \App\OpcPregMulModuloE::ConsulGrupOpc($datos['IdpreguntaPart4'], $idPreg);
                    }
                }
                $Compe = \App\CompetenciasModuloE::BuscarAsig($datos['competencia']);
                $Compo = \App\ComponentesModuloE::BuscarAsig($datos['componente']);
                $PregOpcMul['DesCompe'] = $Compe->nombre;
                $PregOpcMul['DesCompo'] = $Compo->nombre;

                $Log = \App\Log::Guardar('Banco de Pregunta Modificado', $idPreg);

                if (request()->ajax()) {
                    return response()->json([
                        'idEval' => $idPreg,
                        'ContEval' => $ContEval,
                        'PregOpcMul' => $PregOpcMul,
                        'OpciPregMul' => $OpciPregMul,
                    ]);
                }
            } else if ($datos['Tipreguntas'] == "PARTE 5") {

                if ($datos['preg_id'] === null) {
                    $ContEval = \App\BancoPregModuloE::Guardar($datos);
                    $idPreg = $ContEval->id;
                } else {
                    $idPreg = request()->get('preg_id');
                    $ContEval = \App\BancoPregModuloE::ModifEval($datos, $idPreg);
                    $ContEval = \App\BancoPregModuloE::BusPregBanc($idPreg);
                }

                if ($datos['IdpreguntaPart5'] === null) {
                    $PregOpcMul = \App\PregOpcMulMe::Guardar($datos['PreMulResp'], $datos['competencia'], $datos['componente'], $idPreg);
                    if ($PregOpcMul) {
                        $ConsEval = \App\CosPregModuloE::Guardar($datos, $idPreg, $PregOpcMul->id);
                        $OpciPregMul = \App\OpcPregMulModuloE::Guardar($datos, $PregOpcMul->id, $idPreg);
                        $OpciPregMul = \App\OpcPregMulModuloE::ConsulGrupOpc($PregOpcMul->id, $idPreg);
                    }
                } else {
                    $PregOpcMul = \App\PregOpcMulMe::ModiPreMul($datos['PreMulResp'], $datos['competencia'], $datos['componente'], $datos['IdpreguntaPart5'], $idPreg);
                    $PregOpcMul = \App\PregOpcMulMe::ConsulPreg($datos['IdpreguntaPart5']);
                    if ($PregOpcMul) {
                        $OpciPregMul = \App\OpcPregMulModuloE::ModOpcPreg($datos, $idPreg, $datos['IdpreguntaPart5']);
                        $OpciPregMul = \App\OpcPregMulModuloE::ConsulGrupOpc($datos['IdpreguntaPart5'], $idPreg);
                    }
                }
                $Compe = \App\CompetenciasModuloE::BuscarAsig($datos['competencia']);
                $Compo = \App\ComponentesModuloE::BuscarAsig($datos['componente']);
                $PregOpcMul['DesCompe'] = $Compe->nombre;
                $PregOpcMul['DesCompo'] = $Compo->nombre;

                $Log = \App\Log::Guardar('Banco de Pregunta Modificado', $idPreg);

                if (request()->ajax()) {
                    return response()->json([
                        'idEval' => $idPreg,
                        'ContEval' => $ContEval,
                        'PregOpcMul' => $PregOpcMul,
                        'OpciPregMul' => $OpciPregMul,
                    ]);
                }
            } else if ($datos['Tipreguntas'] == "PARTE 6") {

                if ($datos['preg_id'] === null) {
                    $ContEval = \App\BancoPregModuloE::Guardar($datos);
                    $idPreg = $ContEval->id;
                } else {
                    $idPreg = request()->get('preg_id');
                    $ContEval = \App\BancoPregModuloE::ModifEval($datos, $idPreg);
                    $ContEval = \App\BancoPregModuloE::BusPregBanc($idPreg);
                }

                if ($datos['IdpreguntaPart6'] === null) {
                    $PregOpcMul = \App\PregOpcMulMe::Guardar($datos['PreMulResp'], $datos['competencia'], $datos['componente'], $idPreg);
                    if ($PregOpcMul) {
                        $ConsEval = \App\CosPregModuloE::Guardar($datos, $idPreg, $PregOpcMul->id);
                        $OpciPregMul = \App\OpcPregMulModuloE::Guardar($datos, $PregOpcMul->id, $idPreg);
                        $OpciPregMul = \App\OpcPregMulModuloE::ConsulGrupOpc($PregOpcMul->id, $idPreg);
                    }
                } else {
                    $PregOpcMul = \App\PregOpcMulMe::ModiPreMul($datos['PreMulResp'], $datos['competencia'], $datos['componente'], $datos['IdpreguntaPart6'], $idPreg);
                    $PregOpcMul = \App\PregOpcMulMe::ConsulPreg($datos['IdpreguntaPart6']);
                    if ($PregOpcMul) {
                        $OpciPregMul = \App\OpcPregMulModuloE::ModOpcPreg($datos, $idPreg, $datos['IdpreguntaPart6']);
                        $OpciPregMul = \App\OpcPregMulModuloE::ConsulGrupOpc($datos['IdpreguntaPart6'], $idPreg);
                    }
                }
                $Compe = \App\CompetenciasModuloE::BuscarAsig($datos['competencia']);
                $Compo = \App\ComponentesModuloE::BuscarAsig($datos['componente']);
                $PregOpcMul['DesCompe'] = $Compe->nombre;
                $PregOpcMul['DesCompo'] = $Compo->nombre;

                $Log = \App\Log::Guardar('Banco de Pregunta Modificado', $idPreg);

                if (request()->ajax()) {
                    return response()->json([
                        'idEval' => $idPreg,
                        'ContEval' => $ContEval,
                        'PregOpcMul' => $PregOpcMul,
                        'OpciPregMul' => $OpciPregMul,
                    ]);
                }
            } else if ($datos['Tipreguntas'] == "PARTE 7") {

                if ($datos['preg_id'] === null) {
                    $ContEval = \App\BancoPregModuloE::Guardar($datos);
                    $idPreg = $ContEval->id;
                } else {
                    $idPreg = request()->get('preg_id');
                    $ContEval = \App\BancoPregModuloE::ModifEval($datos, $idPreg);
                    $ContEval = \App\BancoPregModuloE::BusPregBanc($idPreg);
                }

                if ($datos['IdpreguntaPart7'] === null) {
                    $PregOpcMul = \App\PregOpcMulMe::Guardar($datos['PreMulResp'], $datos['competencia'], $datos['componente'], $idPreg);
                    if ($PregOpcMul) {
                        $ConsEval = \App\CosPregModuloE::Guardar($datos, $idPreg, $PregOpcMul->id);
                        $OpciPregMul = \App\OpcPregMulModuloE::Guardar($datos, $PregOpcMul->id, $idPreg);
                        $OpciPregMul = \App\OpcPregMulModuloE::ConsulGrupOpc($PregOpcMul->id, $idPreg);
                    }
                } else {
                    $PregOpcMul = \App\PregOpcMulMe::ModiPreMul($datos['PreMulResp'], $datos['competencia'], $datos['componente'], $datos['IdpreguntaPart7'], $idPreg);
                    $PregOpcMul = \App\PregOpcMulMe::ConsulPreg($datos['IdpreguntaPart7']);
                    if ($PregOpcMul) {
                        $OpciPregMul = \App\OpcPregMulModuloE::ModOpcPreg($datos, $idPreg, $datos['IdpreguntaPart7']);
                        $OpciPregMul = \App\OpcPregMulModuloE::ConsulGrupOpc($datos['IdpreguntaPart7'], $idPreg);
                    }
                }
                $Compe = \App\CompetenciasModuloE::BuscarAsig($datos['competencia']);
                $Compo = \App\ComponentesModuloE::BuscarAsig($datos['componente']);
                $PregOpcMul['DesCompe'] = $Compe->nombre;
                $PregOpcMul['DesCompo'] = $Compo->nombre;

                $Log = \App\Log::Guardar('Banco de Pregunta Modificado', $idPreg);

                if (request()->ajax()) {
                    return response()->json([
                        'idEval' => $idPreg,
                        'ContEval' => $ContEval,
                        'PregOpcMul' => $PregOpcMul,
                        'OpciPregMul' => $OpciPregMul,
                    ]);
                }
            } else {
                if ($datos['preg_id'] === null) {
                    $ContEval = \App\BancoPregModuloE::Guardar($datos);
                    $idPreg = $ContEval->id;
                } else {
                    $idPreg = request()->get('preg_id');
                    $ContEval = \App\BancoPregModuloE::ModifEval($datos, $idPreg);
                }

                if ($datos['IdpreguntaMul'] === null) {
                    $PregOpcMul = \App\PregOpcMulMe::Guardar($datos['PreMulResp'], $datos['competencia'], $datos['componente'], $idPreg);
                    if ($PregOpcMul) {
                        $ConsEval = \App\CosPregModuloE::Guardar($datos, $idPreg, $PregOpcMul->id);
                        $OpciPregMul = \App\OpcPregMulModuloE::Guardar($datos, $PregOpcMul->id, $idPreg);
                        $OpciPregMul = \App\OpcPregMulModuloE::ConsulGrupOpc($PregOpcMul->id, $idPreg);
                    }
                } else {
                    $PregOpcMul = \App\PregOpcMulMe::ModiPreMul($datos['PreMulResp'], $datos['competencia'], $datos['componente'], $datos['IdpreguntaMul'], $idPreg);
                    $PregOpcMul = \App\PregOpcMulMe::ConsulPreg($datos['IdpreguntaMul']);
                    if ($PregOpcMul) {
                        $OpciPregMul = \App\OpcPregMulModuloE::ModOpcPreg($datos, $idPreg, $datos['IdpreguntaMul']);
                        $OpciPregMul = \App\OpcPregMulModuloE::ConsulGrupOpc($datos['IdpreguntaMul'], $idPreg);
                    }
                }
                $Compe = \App\CompetenciasModuloE::BuscarAsig($datos['competencia']);
                $Compo = \App\ComponentesModuloE::BuscarAsig($datos['componente']);
                $PregOpcMul['DesCompe'] = $Compe->nombre;
                $PregOpcMul['DesCompo'] = $Compo->nombre;

                $Log = \App\Log::Guardar('Banco de Pregunta Modificado', $idPreg);

                if (request()->ajax()) {
                    return response()->json([
                        'idEval' => $idPreg,
                        'PregOpcMul' => $PregOpcMul,
                        'OpciPregMul' => $OpciPregMul,
                    ]);
                }
            }
        } else {
            return redirect("/")->with("error", "Su sesión ha terminado");
        }
    }

    public function ConsulPregME()
    {

        if (Auth::check()) {
            $IdPreg = request()->get('Pregunta');
            $TipPreg = request()->get('TipoPregunta');

            if ($TipPreg == "PARTE 1") {

                $Parte1 = \App\PregOpcMulMe::ConsulPreg($IdPreg);
                $Preguntas = \App\PreguntasParte1::ConsultarPreg($IdPreg);
                $Partes = \App\PartesModuloE::BuscParte($TipPreg);

                if (request()->ajax()) {
                    return response()->json([
                        'Parte1' => $Parte1,
                        'Preguntas' => $Preguntas,
                        'Partes' => $Partes,
                    ]);
                }
            } else if ($TipPreg == "Parte 2") {
                $PregMult = \App\PregOpcMulMe::ConsulPreg($IdPreg);
                $OpciMult = \App\OpcPregMulModuloE::ConsulGrupOpcPreg($IdPreg);
                if (request()->ajax()) {
                    return response()->json([
                        'PregMult' => $PregMult,
                        'OpciMult' => $OpciMult,
                    ]);
                }
            } else if ($TipPreg == "Parte 3") {
                $Banco = \App\BancoPregModuloE::BusPregBanc($IdPreg);
                $PregMult = \App\PregOpcMulMe::ConsulPreg($IdPreg);
                $OpciMult = \App\OpcPregMulModuloE::ConsulGrupOpcPreg($IdPreg);
                if (request()->ajax()) {
                    return response()->json([
                        'PregMult' => $PregMult,
                        'OpciMult' => $OpciMult,
                        'bancVonv' => $Banco->enunc_preg,
                    ]);
                }
            } else if ($TipPreg == "Parte 4") {
                $Banco = \App\BancoPregModuloE::BusPregBanc($IdPreg);
                $PregMult = \App\PregOpcMulMe::ConsulPreg($IdPreg);
                $OpciMult = \App\OpcPregMulModuloE::ConsulGrupOpcPreg($IdPreg);
                if (request()->ajax()) {
                    return response()->json([
                        'PregMult' => $PregMult,
                        'OpciMult' => $OpciMult,
                        'bancVonv' => $Banco->enunc_preg,
                    ]);
                }
            } else if ($TipPreg == "Parte 5") {
                $Banco = \App\BancoPregModuloE::BusPregBanc($IdPreg);
                $PregMult = \App\PregOpcMulMe::ConsulPreg($IdPreg);
                $OpciMult = \App\OpcPregMulModuloE::ConsulGrupOpcPreg($IdPreg);
                if (request()->ajax()) {
                    return response()->json([
                        'PregMult' => $PregMult,
                        'OpciMult' => $OpciMult,
                        'bancVonv' => $Banco->enunc_preg,
                    ]);
                }
            } else if ($TipPreg == "Parte 6") {
                $Banco = \App\BancoPregModuloE::BusPregBanc($IdPreg);
                $PregMult = \App\PregOpcMulMe::ConsulPreg($IdPreg);
                $OpciMult = \App\OpcPregMulModuloE::ConsulGrupOpcPreg($IdPreg);
                if (request()->ajax()) {
                    return response()->json([
                        'PregMult' => $PregMult,
                        'OpciMult' => $OpciMult,
                        'bancVonv' => $Banco->enunc_preg,
                    ]);
                }
            } else if ($TipPreg == "Parte 7") {
                $Banco = \App\BancoPregModuloE::BusPregBanc($IdPreg);
                $PregMult = \App\PregOpcMulMe::ConsulPreg($IdPreg);
                $OpciMult = \App\OpcPregMulModuloE::ConsulGrupOpcPreg($IdPreg);
                if (request()->ajax()) {
                    return response()->json([
                        'PregMult' => $PregMult,
                        'OpciMult' => $OpciMult,
                        'bancVonv' => $Banco->enunc_preg,
                    ]);
                }
            } else {
                $PregMult = \App\PregOpcMulMe::ConsulPreg($IdPreg);
                $OpciMult = \App\OpcPregMulModuloE::ConsulGrupOpcPreg($IdPreg);
                if (request()->ajax()) {
                    return response()->json([
                        'PregMult' => $PregMult,
                        'OpciMult' => $OpciMult,
                    ]);
                }
            }
        } else {
            return redirect("/")->with("error", "Su sesión ha terminado");
        }
    }

    public function ElimnarPregSimu()
    {
        $mensaje = "";
        $id = request()->get('id');
        $TipPregu = request()->get('TipPregu');

        if (Auth::check()) {

            if ($TipPregu == "PARTE 1") {
                $respuesta = \App\Parte1::DelPreg((int) $id);
                $respuesta = \App\PreguntasParte1::DelPreg((int) $id);
            } else {

                $respuesta = \App\PregOpcMulMe::DelPregunta((int) $id);
                $respuesta = \App\OpcPregMulModuloE::DelOpciones($id);
                $delcons = \App\CosPregModuloE::DelPreg($id);
            }

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
            return redirect("/")->with("error", "Su sesión ha terminado");
        }
    }

    public function EditarBancoPreg($id)
    {

        $bandera = "Menu4";
        $opc = "editar";
        if (Auth::check()) {
            $Preg = \App\BancoPregModuloE::BusPregBanc($id);
            $Asignatura = \App\AsignaturasModuloE::listar();
            return view('ModuloE.EditarPreguntas', compact('Preg', 'Asignatura', 'opc'));
        } else {
            return redirect("/")->with("error", "Su sesión ha terminado");
        }
    }

    public function PreguntasxBanco()
    {
        $idbanc = request()->get('idBancoPreg');
        $Banco = \App\BancoPregModuloE::BusPregBanc($idbanc);


        if ($Banco->tipo_pregunta == "PARTE 1") {
            $PregMult = \App\PregOpcMulMe::ConsulPregBan($idbanc);
            if ($PregMult) {
                $Preguntas = \App\PreguntasParte1::ConsultarPreg($PregMult->id);
                if (request()->ajax()) {
                    return response()->json([
                        'Banco' => $Banco,
                        'Parte1' => $PregMult,
                        'Preguntas' => $Preguntas,
                    ]);
                }
            }
        } else {
            $PregBanc = \App\CosPregModuloE::GrupPreg($idbanc);

            ///////CONSULTAR PREGUNTA OPCION MULTIPLE COMPLE
            $PregMult = \App\PregOpcMulMe::ConsulPregAll($idbanc);
            $OpciMult = \App\OpcPregMulModuloE::ConsulGrupOpcPregAll($idbanc);

            if (request()->ajax()) {
                return response()->json([
                    'Banco' => $Banco,
                    'PregBanc' => $PregBanc,
                    'PregMult' => $PregMult,
                    'OpciMult' => $OpciMult
                ]);
            }
        }
    }

    public function ConsulPreguntas()
    {
        if (Auth::check()) {
            $idbanc = request()->get('idbanc');
            $Banco = \App\BancoPregModuloE::BusPregBanc($idbanc);

            if ($Banco->tipo_pregunta == "PARTE 1") {

                //  $Parte1 = \App\Parte1::ConsultarPreg($Banco->id);
                $PregMult = \App\PregOpcMulMe::ConsulPregBan($idbanc);

                if ($PregMult) {
                    $Preguntas = \App\PreguntasParte1::ConsultarPreg($PregMult->id);
                    $Partes = \App\PartesModuloE::BuscParte($Banco->tipo_pregunta);
                    $Compe = \App\CompetenciasModuloE::BuscarAsig($PregMult['competencia']);
                    $Compo = \App\ComponentesModuloE::BuscarAsig($PregMult['componente']);
                    $DesCompe = $Compe->nombre;
                    $DesCompo = $Compo->nombre;

                    if (request()->ajax()) {
                        return response()->json([
                            'Banco' => $Banco,
                            'Partes' => $Partes,
                            'Parte1' => $PregMult,
                            'Preguntas' => $Preguntas,
                            'DesCompe' => $DesCompe,
                            'DesCompo' => $DesCompo,
                        ]);
                    }
                } else {
                    if (request()->ajax()) {
                        return response()->json([
                            'Banco' => $Banco,
                        ]);
                    }
                }
            } else if ($Banco->tipo_pregunta == "PARTE 2") {

                $PregBanc = \App\CosPregModuloE::GrupPreg($idbanc);
                $Partes = \App\PartesModuloE::BuscParte($Banco->tipo_pregunta);

                ///////CONSULTAR PREGUNTA OPCION MULTIPLE COMPLE
                $PregMult = \App\PregOpcMulMe::ConsulPregAll($idbanc);
                $OpciMult = \App\OpcPregMulModuloE::ConsulGrupOpcPregAll($idbanc);

                if (request()->ajax()) {
                    return response()->json([
                        'Banco' => $Banco,
                        'PregBanc' => $PregBanc,
                        'PregMult' => $PregMult,
                        'OpciMult' => $OpciMult,
                        'Partes' => $Partes,
                    ]);
                }
            } else if ($Banco->tipo_pregunta == "PARTE 3") {

                $PregBanc = \App\CosPregModuloE::GrupPreg($idbanc);
                $Partes = \App\PartesModuloE::BuscParte($Banco->tipo_pregunta);

                ///////CONSULTAR PREGUNTA OPCION MULTIPLE COMPLE
                $PregMult = \App\PregOpcMulMe::ConsulPregAll($idbanc);
                $OpciMult = \App\OpcPregMulModuloE::ConsulGrupOpcPregAll($idbanc);

                if (request()->ajax()) {
                    return response()->json([
                        'Banco' => $Banco,
                        'PregBanc' => $PregBanc,
                        'PregMult' => $PregMult,
                        'OpciMult' => $OpciMult,
                        'Partes' => $Partes,
                    ]);
                }
            } else if ($Banco->tipo_pregunta == "PARTE 4") {

                $PregBanc = \App\CosPregModuloE::GrupPreg($idbanc);
                $Partes = \App\PartesModuloE::BuscParte($Banco->tipo_pregunta);

                ///////CONSULTAR PREGUNTA OPCION MULTIPLE COMPLE
                $PregMult = \App\PregOpcMulMe::ConsulPregAll($idbanc);
                $OpciMult = \App\OpcPregMulModuloE::ConsulGrupOpcPregAll($idbanc);

                if (request()->ajax()) {
                    return response()->json([
                        'Banco' => $Banco,
                        'PregBanc' => $PregBanc,
                        'PregMult' => $PregMult,
                        'OpciMult' => $OpciMult,
                        'Partes' => $Partes,
                    ]);
                }
            } else if ($Banco->tipo_pregunta == "PARTE 5") {

                $PregBanc = \App\CosPregModuloE::GrupPreg($idbanc);
                $Partes = \App\PartesModuloE::BuscParte($Banco->tipo_pregunta);

                ///////CONSULTAR PREGUNTA OPCION MULTIPLE COMPLE
                $PregMult = \App\PregOpcMulMe::ConsulPregAll($idbanc);
                $OpciMult = \App\OpcPregMulModuloE::ConsulGrupOpcPregAll($idbanc);

                if (request()->ajax()) {
                    return response()->json([
                        'Banco' => $Banco,
                        'PregBanc' => $PregBanc,
                        'PregMult' => $PregMult,
                        'OpciMult' => $OpciMult,
                        'Partes' => $Partes,
                    ]);
                }
            } else if ($Banco->tipo_pregunta == "PARTE 6") {

                $PregBanc = \App\CosPregModuloE::GrupPreg($idbanc);
                $Partes = \App\PartesModuloE::BuscParte($Banco->tipo_pregunta);

                ///////CONSULTAR PREGUNTA OPCION MULTIPLE COMPLE
                $PregMult = \App\PregOpcMulMe::ConsulPregAll($idbanc);
                $OpciMult = \App\OpcPregMulModuloE::ConsulGrupOpcPregAll($idbanc);

                if (request()->ajax()) {
                    return response()->json([
                        'Banco' => $Banco,
                        'PregBanc' => $PregBanc,
                        'PregMult' => $PregMult,
                        'OpciMult' => $OpciMult,
                        'Partes' => $Partes,
                    ]);
                }
            } else if ($Banco->tipo_pregunta == "PARTE 7") {

                $PregBanc = \App\CosPregModuloE::GrupPreg($idbanc);
                $Partes = \App\PartesModuloE::BuscParte($Banco->tipo_pregunta);

                ///////CONSULTAR PREGUNTA OPCION MULTIPLE COMPLE
                $PregMult = \App\PregOpcMulMe::ConsulPregAll($idbanc);
                $OpciMult = \App\OpcPregMulModuloE::ConsulGrupOpcPregAll($idbanc);

                if (request()->ajax()) {
                    return response()->json([
                        'Banco' => $Banco,
                        'PregBanc' => $PregBanc,
                        'PregMult' => $PregMult,
                        'OpciMult' => $OpciMult,
                        'Partes' => $Partes,
                    ]);
                }
            } else {
                $PregBanc = \App\CosPregModuloE::GrupPreg($idbanc);

                ///////CONSULTAR PREGUNTA OPCION MULTIPLE COMPLE
                $PregMult = \App\PregOpcMulMe::ConsulPregAll($idbanc);
                $OpciMult = \App\OpcPregMulModuloE::ConsulGrupOpcPregAll($idbanc);

                if (request()->ajax()) {
                    return response()->json([
                        'Banco' => $Banco,
                        'PregBanc' => $PregBanc,
                        'PregMult' => $PregMult,
                        'OpciMult' => $OpciMult,
                    ]);
                }
            }
        } else {
            return redirect("/")->with("error", "Su sesión ha terminado");
        }
    }

    public function CargarAsigContModuloE()
    {
        if (Auth::check()) {
            $bandera = "";
         
            if (Auth::user()->tipo_usuario == "Administrador") {
            } else if (Auth::user()->tipo_usuario == "Profesor") {
            } else if (Auth::user()->tipo_usuario == "Estudiante") {
                ////////INF. ASIGANTURAS

                $Asignatura = \App\AsignaturasModuloE::AsigxUsu(Auth::user()->grado_usuario, Auth::user()->tipo_usuario);
                Session::put('IDASIG', '');
            }

            $Log = \App\Log::Guardar('Cargar Asignaturas Módulo E', '');

            return view('ModuloE.PresentacionTemasModuloE', compact('bandera', 'Asignatura'));
        } else {
            return redirect("/")->with("error", "Su sesión ha terminado");
        }
    }
    public function CargarSimuContModuloE()
    {
      
        if (Auth::check()) {
            $bandera = "";
            return view('ModuloE.PresentacionSimulacrosModuloE', compact('bandera'));
        } else {
            return redirect("/")->with("error", "Su sesión ha terminado");
        }
    }

    public function CargarContModuloE()
    {
        if (Auth::check()) {
            $bandera = "";
            return view('ModuloE.PresentacionAsignaturas', compact('bandera'));
        } else {
            return redirect("/")->with("error", "Su sesión ha terminado");
        }
    }

    public function GuardarRespEvaluaciones()
    {
        if (Auth::check()) {
            $datos = request()->all();

            $fecha = date('Y-m-d  H:i:s');

            if ($datos['TipPregunta'] == "PREGENSAY") {
                $InfPreg = \App\EvalPregEnsay::consulPregEnsay($datos['Pregunta']);
                $InfEval = \App\Evaluacion::DatosEvalME($datos['IdEvaluacion'], 'IFEVAL');
                $Respuesta = \App\RespEvalEnsay::Guardar($InfPreg, $datos, $fecha);

                $InfEval['OriEva'] = "Estudiante";
                $Sesiones = \App\sesiones::Guardar(Auth::user()->id);
            } else if ($datos['TipPregunta'] == "COMPLETE") {

                $InfPreg = \App\EvalPregComplete::ConsultComplete($datos['Pregunta']);
                $InfEval = \App\Evaluacion::DatosEvalME($datos['IdEvaluacion'], 'IFEVAL');
                $Respuesta = \App\RespEvalComp::Guardar($InfPreg, $datos, $fecha);
                $InfEval['OriEva'] = "Estudiante";
                $Sesiones = \App\sesiones::Guardar(Auth::user()->id);
            } else if ($datos['TipPregunta'] == "OPCMULT") {
                $Respuesta = \App\RespMultPreg::Guardar($datos, $fecha);
                $Sesiones = \App\sesiones::Guardar(Auth::user()->id);
                $InfEval = \App\Evaluacion::DatosEvalME($datos['IdEvaluacion'], 'IFEVAL');
            } else if ($datos['TipPregunta'] == "VERFAL") {
                $Respuesta = \App\RespVerFal::Guardar($datos, $fecha);

                $Sesiones = \App\sesiones::Guardar(Auth::user()->id);
                $InfEval = \App\Evaluacion::DatosEvalME($datos['IdEvaluacion'], 'IFEVAL');
                $InfEval['OriEva'] = "Estudiante";
            } else if ($datos['TipPregunta'] == "RELACIONE") {
                $Respuesta = \App\RespEvalRelacione::Guardar($datos, $fecha);
                $Sesiones = \App\sesiones::Guardar(Auth::user()->id);
                $InfEval = \App\Evaluacion::DatosEvalME($datos['IdEvaluacion'], 'IFEVAL');
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
                $InfEval = \App\Evaluacion::DatosEvalME($datos['IdEvaluacion'], 'IFEVAL');
                $InfEval['OriEva'] = "Estudiante";
            }

            if ($datos['nPregunta'] === "Ultima") {
                $LibroCalif = \App\LibroCalificaciones::Guardar($datos, $Respuesta['RegViejo'], $Respuesta['RegNuevo'], $InfEval, $fecha);
                $Intentos = \App\UpdIntEval::Guardar($datos['IdEvaluacion']);
                $InfEval = \App\Evaluacion::DatosEvalME($datos['IdEvaluacion'], 'IFEVALFIN');

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
    public function RespSimulacro()
    {
        if (Auth::check()) {
            $datos = request()->all();

            $fecha = date('Y-m-d  H:i:s');

            $Respuesta = \App\RespMultPregMEPruebaSimulacro::Guardar($datos, $fecha);

            $Sesiones = \App\sesiones::Guardar(Auth::user()->id);

            if ($datos['PosPreg'] === "Ultima") {
                $LibroCalif = \App\LibroPruebaModuloE::Guardar($datos, $Respuesta['RegViejo'], $Respuesta['RegNuevo'], $fecha);

                $Log = \App\Log::Guardar('Pregunta Desarrollada Simulacro', $datos['idSimulacro']);

                $Sesion = \App\DetaSesionesSimul::ConsultarSesion($datos['IdSesion']);
                $SesAre = \App\SessionArea::ConsultarAreasSesion($datos['IdSesion']);

                if (request()->ajax()) {
                    return response()->json([
                        'SesAre' => $SesAre,
                        'Sesion' => $Sesion,
                    ]);
                }
            } else {
                $LibroCalif = \App\LibroPruebaModuloE::Guardar($datos, $Respuesta['RegViejo'], $Respuesta['RegNuevo'], $fecha);

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
    public function consulPregAlumnoSimu()
    {
        if (Auth::check()) {
            $IdPreg = request()->get('Pregunta');
            $PregMult = \App\PregOpcMulMe::ConsulPreg($IdPreg);
            $OpciMult = \App\OpcPregMulModuloE::ConsulGrupOpcPreg($IdPreg);
            $RespPregMul = \App\OpcPregMulModuloE::BuscOpcRespPrueba($IdPreg, Auth::user()->id);

            if (request()->ajax()) {
                return response()->json([
                    'PregMult' => $PregMult,
                    'OpciMult' => $OpciMult,
                    'RespPregMul' => $RespPregMul,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function EliminarBancoPregunta()
    {
        $mensaje = "";
        $id = request()->get('id');
        if (Auth::check()) {
            $BanPreg = \App\BancoPregModuloE::BusPregBanc($id);
            $estado = "ACTIVO";
            if ($BanPreg->estado == "ACTIVO") {
                $estado = "ELIMINADO";
            } else {
                $estado = "ACTIVO";
            }
            $respuesta = \App\BancoPregModuloE::DelBancoPreg($id, $estado);

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

    public function CargarTemasModuloE()
    {
        if (Auth::check()) {
            $idAsig = request()->get('idAsig');

            $Temas = \App\TemasModuloE::listarxAssig($idAsig);
            $InfAsig = \App\AsignaturasModuloE::BuscarAsig($idAsig);
            Session::put('DOCENTE', $InfAsig->docente);
            if (request()->ajax()) {
                return response()->json([
                    'Temas' => $Temas
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su sesión ha terminado");
        }
    }

    public function CargaDetTemasModuloE()
    {
        if (Auth::check()) {
            $idTema = request()->get('idTem');
            $TipCont = request()->get('TipCont');
            $detTema = "";
            $Tema = \App\TemasModuloE::BuscarTem($idTema);
            if ($TipCont === "DOC") {
                $TemasDet = \App\TemasModuloE_Doc::BuscarTema($idTema);
            } else if ($TipCont === "IMG") {
                $TemasDet = \App\TemasModuloE_Img::BuscarTema($idTema);
                $detTema = $TemasDet->first();
                $detTema = $detTema->imagen;
            } else {
                $TemasDet = \App\TemasModuloE_Vid::BuscarTema($idTema);
            }

            $Practicas = \App\Evaluacion::ListEval($idTema, 'ME');

            if (request()->ajax()) {
                return response()->json([
                    'TemasDet' => $TemasDet,
                    'Tema' => $Tema,
                    'npractica' => count($Practicas),
                    'primeImg' => $detTema,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su sesión ha terminado");
        }
    }

    public function CargarPracticas()
    {
        if (Auth::check()) {
            $id = request()->get('Tema');
            $Clasf = request()->get('clasf');

            $Temas = \App\TemasModuloE::BuscarTem($id);

            $Eval = \App\Evaluacion::ListEvalxClasif($id, $Clasf, 'ME');

            $Sesiones = \App\sesiones::Guardar(Auth::user()->id);
            if (request()->ajax()) {
                return response()->json([
                    'Eval' => $Eval,
                    'TitTemas' => $Temas->titulo,
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
                    'PregEval' => $PregEval,
                    'VideoEval' => $video,
                    'idvideo' => $id,

                ]);
            }
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function GestionPracticas($id)
    {
        if (Auth::check()) {

            $Evaluaciones = \App\Evaluacion::ListEval($id, 'ME');
            $DesTema = \App\TemasModuloE::BuscarTem($id);
            $titTema = $DesTema->titulo;

            return view('ModuloE.GestionPracticasTemas', compact('Evaluaciones', 'titTema', 'id'));
        } else {
            return redirect("/")->with("error", "Su sesión ha terminado");
        }
    }

    public function EliminarEval()
    {
        $mensaje = "";
        $icon = "";
        $id = request()->get('id');
        if (Auth::check()) {
            $Eval = \App\Evaluacion::BusEval($id);

            if ($Eval->docente == "" && Auth::user()->tipo_usuario == 'Profesor') {
                $estado = "NO";
                $mensaje = 'Esta Practiaa solo puede ser eliminada desde un perfil Administrador';
                $icon = 'warning';
            } else {
                $Elib = \App\LibroCalificaciones::BusEvalDel($id);
                if ($Elib) {
                    $estado = "ACTIVO";
                    $mensaje = 'La Practiaa no puede ser Eliminada, ya que ha sido resuelta por un Estudiante';
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

    public function NuevaPractica($id)
    {

        $opc = "nuevo";

        if (Auth::check()) {
            $Tema = \App\TemasModuloE::BuscarTem($id);

            $Eval = new \App\Evaluacion();
            $Asignatura = \App\AsignaturasModuloE::listar();

            return view('ModuloE.NuevaPractica', compact('Tema', 'Asignatura', 'Eval', 'opc'));
        } else {
            return redirect("/")->with("error", "Su sesión ha terminado");
        }
    }

    public function CargTemasPractica()
    {

        if (Auth::check()) {
            $idAsig = request()->get('id');
            $idtem = request()->get('idtem');
            $Temas = \App\TemasModuloE::listarxAssig($idAsig);

            $select_temas = "<option value='' selected>Seleccione</option>";
            foreach ($Temas as $Tem) {

                if ($Tem->id == $idtem) {

                    $select_temas .= "<option value='$Tem->id' selected> " . strtoupper($Tem->titulo) . "</option>";
                } else {
                    $select_temas .= "<option value='$Tem->id' >" . strtoupper($Tem->titulo) . "</option>";
                }
            }
            if (request()->ajax()) {
                return response()->json([
                    'select_Tema' => $select_temas,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su sesión ha terminado");
        }
    }

    public function ComponetesAsignatura()
    {

        if (Auth::check()) {
            $idAsig = request()->get('idAsig');

            $Compo = \App\AsigComponentemduloE::Listarxasig($idAsig);

            $select_comp = "<option value='' selected>Seleccione el Componente</option>";
            foreach ($Compo as $Comp) {
                $select_comp .= "<option value='$Comp->id' >" . strtoupper($Comp->nombre) . "</option>";
            }

            if (request()->ajax()) {
                return response()->json([
                    'select_comp' => $select_comp,
                ]);
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
    public function GuardarEvalFinBanc()
    {
        if (Auth::check()) {
            $datos = request()->all();

            $idEval = "";
            $idEval = request()->get('preg_id');
            $ContEval = \App\BancoPregModuloE::ModifEvalFin($datos, $idEval);
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

    public function EditarEval($id)
    {
        $bandera = "Menu4";
        $opc = "editar";
        if (Auth::check()) {
            $Eval = \App\Evaluacion::BusEval($id);
            $Tema = \App\TemasModuloE::BuscarTem($Eval->contenido);
            $Asignatura = \App\AsignaturasModuloE::listar();
            return view('ModuloE.EditarPractica', compact('bandera', 'Tema', 'Asignatura', 'opc', 'Eval'));
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

    public function ElimnarPregBancoPreg()
    {
        $mensaje = "";
        $id = request()->get('id');
        $tip = request()->get('TipPregu');
        if (Auth::check()) {

            if ($tip == "PARTE1") {
                $respuesta = \App\PregOpcMulMe::DelPregunta((int) $id);
                $respuesta = \App\PreguntasParte1::DelPreg((int) $id);
            } else {
                $respuesta = \App\PregOpcMulMe::DelPregunta((int) $id);
                $respuesta = \App\OpcPregMulModuloE::DelOpciones($id);
                $delcons = \App\CosPregModuloE::DelPreg($id);
            }

            if ($respuesta) {
                $Log = \App\Log::Guardar('Pregunta Eliminada Banco de Preguntas', $id);
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

    public function ContenidoPrueba()
    {

        if (Auth::check()) {

            $ListPreguntas = '';
            $ConsPre = 0;
            $Preg = 1;
            $idTema = request()->get('id_tema');
            $Tema = \App\TemasModuloE::BuscarTem($idTema);
            $Practicas = \App\TemasPracticas::BuscarPracticas($idTema);
            foreach ($Practicas as $Prac) {
                $Preguntas = \App\BancoPregModuloE::BuscarPregPract($Prac->pregunta);

                foreach ($Preguntas as $Pregu) {
                    $ListPreguntas .= ' <h6>Pregunta</h6>' .
                        '         <fieldset>' .
                        '              <div class="row p-1">' .
                        '   <div  style="width: 100%" class="bs-callout-primary callout-border-right callout-bordered callout-transparent p-1" >' .
                        '              <div class="row" >' .
                        '<div class="col-md-12" id="Enunciado">' . $Pregu->enunciado . '</div>' .
                        '<input type="hidden" id="id-pregunta' .
                        $ConsPre . '"  value="' . $Pregu->id .
                        '" />' .
                        '      <div class="col-md-9"><h4 class="primary">Pregunta ' .
                        $Preg . '</h4></div>' .
                        '      <div class="col-md-12" id="Pregunta' .
                        $ConsPre . '">' .
                        '           </div>    ' .
                        '           </div>    ' .
                        '           </div>    ' .
                        '             </div>' .
                        '        </fieldset>';
                    $ConsPre++;
                    $Preg++;
                }
            }

            if (request()->ajax()) {
                return response()->json([
                    'Tema' => $Tema,
                    'Practica' => $Practicas,
                    'ListPreguntas' => $ListPreguntas,
                    'ConsPre' => $ConsPre,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su sesión ha terminado");
        }
    }

    public function GestionSimulacros()
    {
        if (Auth::check()) {
            $busqueda = request()->get('txtbusqueda');
            $actual = request()->get('page');
            if ($actual == null || $actual == "") {
                $actual = 1;
            }
            $limit = 10;

            $Simulacros = \App\Simulacros::Gestion($busqueda, $actual, $limit);
            $numero_filas = \App\Simulacros::numero_de_registros(request()->get('txtbusqueda'));
            $paginas = ceil($numero_filas / $limit);

            return view('ModuloE.GestionSimulacro', compact('numero_filas', 'paginas', 'actual', 'limit', 'busqueda', 'Simulacros'));
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function NuevoSimulacro()
    {
        $opc = "nuevo";
        if (Auth::check()) {
            $Simu = new \App\Simulacros();

            return view('ModuloE.NuevoSimulacro', compact('Simu', 'opc'));
        } else {
            return redirect("/")->with("error", "Su sesión ha Terminado");
        }
    }

    public function GuardarSimulacro()
    {
        if (Auth::check()) {
            $datos = request()->all();
            if ($datos['OpcSimul'] == "new") {
                $Simulacro = \App\Simulacros::Guardar($datos);
                if (request()->ajax()) {
                    return response()->json([
                        'Simu' => $Simulacro->id,
                    ]);
                }
            } else {
                $Simulacro = \App\Simulacros::modificar($datos);
                if (request()->ajax()) {
                    return response()->json([
                        'Simu' => $datos['Id_Simu'],
                    ]);
                }
            }
        } else {
            return redirect("/")->with("error", "Su sesión ha Terminado");
        }
    }

    public function GuardarSesionsimulacro()
    {
        if (Auth::check()) {
            $datos = request()->all();
            $Simulacro = \App\Simulacros::Guardar($datos);
            if (request()->ajax()) {
                return response()->json([
                    'Simu' => $Simulacro->id,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su sesión ha Terminado");
        }
    }

    public function GuardarDetaSesion()
    {
        if (Auth::check()) {
            $datos = request()->all();
            $DetaSesion = \App\DetaSesionesSimul::Guardar($datos);
            if (request()->ajax()) {
                return response()->json([
                    'DetaSesion' => $DetaSesion,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su sesión ha Terminado");
        }
    }

    public function GuardarSesionTiempo()
    {
        if (Auth::check()) {
            $datos = request()->all();
            $Sesion = \App\SesionAlumnos::Editar($datos);


            $areas = \App\LibroPruebaModuloE::GuardarInfTiempo($datos);
        } else {
            return redirect("/")->with("error", "Su sesión ha Terminado");
        }
    }

    public function GuardarSesionEstudiante()
    {
        if (Auth::check()) {
            $datos = request()->all();
            $DetaSesion = \App\SesionAlumnos::Editar($datos);

            //   $areaSesion = \App\SesionAlumnos::Guardar($datos);
            if (request()->ajax()) {
                return response()->json([
                    'DetaSesion' => $DetaSesion,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su sesión ha Terminado");
        }
    }

    public function ModificarDetaSesion()
    {
        if (Auth::check()) {
            $idSesion = request()->get('idSesion');
            $desc = request()->get('descSesion');
            $tiempo = request()->get('tiempoSesion');
            $DetaSesion = \App\DetaSesionesSimul::ModificaDatos($idSesion, $desc, $tiempo);
            if (request()->ajax()) {
                return response()->json([
                    'DetaSesion' => $DetaSesion,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su sesión ha Terminado");
        }
    }

    public function EditarSimulacro($id)
    {
        $opc = "editar";
        if (Auth::check()) {
            $Simu = \App\Simulacros::BuscarSimu($id);

            return view('ModuloE.EditarSimulacro', compact('Simu', 'opc'));
        } else {
            return redirect("/")->with("error", "Su Sesión  ha Terminado");
        }
    }

    public function CargAreas()
    {
        if (Auth::check()) {
            $Area = \App\AreasModE::listar();
            $select_Area = "<option value='' selected>Seleccione el Área</option>";
            foreach ($Area as $Ar) {
                $select_Area .= "<option value='$Ar->id' >" . strtoupper($Ar->nombre_area) . "</option>";
            }
            if (request()->ajax()) {
                return response()->json([
                    'select_Area' => $select_Area,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su sesión ha Terminado");
        }
    }

    public function Cargcompetencias()
    {
        if (Auth::check()) {
            $idArea = request()->get('idArea');
            $grado = request()->get('GradoP');
            $Compe = \App\CompetenciasModuloE::Compexareas($idArea, $grado);
            $Compo = \App\ComponentesModuloE::Compoxareas($idArea, $grado);

            $select_compe = "<option value='' selected>Seleccione la Competencia</option>";
            foreach ($Compe as $Com) {
                $select_compe .= "<option value='$Com->id' >" . strtoupper($Com->nombre) . "</option>";
            }

            $select_compo = "<option value='' selected>Seleccione el Componente</option>";
            foreach ($Compo as $Comp) {
                $select_compo .= "<option value='$Comp->id' >" . strtoupper($Comp->nombre) . "</option>";
            }

            if (request()->ajax()) {
                return response()->json([
                    'select_compe' => $select_compe,
                    'select_compo' => $select_compo,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su sesión ha Terminado");
        }
    }

    public function CargAreasxSesiones()
    {
        if (Auth::check()) {
            $datos = request()->all();
            $idSesion = $datos['idSesion'];
            $DetaSesion = \App\DetaSesionesSimul::ConsultarSesion($idSesion);
            $Areas = \App\SessionArea::ConsultarAreasxSesiones($idSesion);
            $trAreas = '';
            $i = 1;

            foreach ($Areas as $Ar) {
                $trAreas .= '<tr id="tr_Area' . $Ar->id . '">
                    <td class="text-truncate">' . $Ar->nombre_area . '</td><input type="hidden" id="txtidperi' . $i . '" name="txtidArea[]"  value="' . $Ar->id . '">
                    <td class="text-truncate" >' . $Ar->npreguntas . '</td>
                    <td class="text-truncate">
                    <a onclick="$.EditAreaSesion(' . $Ar->id . ')" class="btn btn-info btn-sm btnQuitar text-white"  title="Editar"><i class="fa fa-edit font-medium-3" aria-hidden="true"></i></a>&nbsp;
                    <a onclick="$.DelAreaSesion(' . $Ar->id . ')" class="btn btn-danger btn-sm btnQuitar text-white"  title="Eliminar"><i class="fa fa-trash-o font-medium-3" aria-hidden="true"></i></a>&nbsp;

                    </td>
                </tr>';
                $i++;
            }

            if (request()->ajax()) {
                return response()->json([
                    'trAreas' => $trAreas,
                    'DetaSesion' => $DetaSesion,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su sesión ha Terminado");
        }
    }

    public function GuardarAreaSimu()
    {
        if (Auth::check()) {
            $datos = request()->all();
            $SesAre = \App\SessionArea::Guardar($datos);
            $CompAre = \App\CompAreaSession::Guardar($datos, $SesAre->id);

            if ($SesAre) {
                if (request()->ajax()) {
                    return response()->json([
                        'Resul' => "Guardado",
                    ]);
                }
            }
        } else {
            return redirect('/')->with('error', 'Su sesión ha Terminado');
        }
    }

    public function GuardarAreaSimuPreg()
    {

        if (Auth::check()) {
            $datos = request()->all();
            $idSesion = $datos['IdSesion'];


            if ($datos['OpcGuardado'] == "Guardar") {
                $SesAre = \App\SessionArea::Guardar($datos);

                $idSesionAre = $SesAre->id;

                $CompAre = \App\CompAreaSession::Guardar($datos, $idSesionAre);
                $PregArea = \App\ModE_PreguntAreas::Guardar($datos, $idSesionAre);
            } else {

                $SesAre = \App\SessionArea::Modificar($datos);

                $CompAre = \App\CompAreaSession::Modificar($datos, $idSesion);

                $PregArea = \App\ModE_PreguntAreas::Modificar($datos, $idSesion);
            }

            $AreSim = self::UpdateTotregSesion($datos['IdSesionGen']);

            if ($SesAre) {
                if (request()->ajax()) {
                    return response()->json([
                        'Resul' => "Guardado",
                        'AreSim' => $AreSim,
                    ]);
                }
            }
        } else {
            return redirect('/')->with('error', 'Su sesión ha Terminado');
        }
    }

    public function ConsultarSimulacros()
    {

        if (Auth::check()) {

            $Simualacros = \App\Simulacros::CargarSimulacros();
            for ($i = 0; $i < count($Simualacros); $i++) {
                $DetaSesionxsimulacro = \App\DetaSesionesSimul::ConsultarSesiones($Simualacros[$i]['id']);
                $Simualacros[$i]['SesionesxSimulacro'] = $DetaSesionxsimulacro;
            }

            if (request()->ajax()) {
                return response()->json([
                    'Simualacros' => $Simualacros,
                ]);
            }
        } else {
            return redirect('/')->with('error', 'Su sesión ha Terminado');
        }
    }

    public function ConsultarSesiones()
    {

        if (Auth::check()) {

            $idSimu = request()->get('idSimu');
            $Simulacro = \App\Simulacros::BuscarSimuxEstu($idSimu);
            $Sesiones = \App\DetaSesionesSimul::ConsultarSesiones($idSimu);

            for ($i = 0; $i < $Sesiones->count(); $i++) {
                $DetaSesionAreas = \App\SessionArea::Consultar($Sesiones[$i]['id']);
                $Sesiones[$i]['AreasxSesiones'] = $DetaSesionAreas;
            }

            if (request()->ajax()) {
                return response()->json([
                    'Simulacro' => $Simulacro,
                    'Sesiones' => $Sesiones,
                ]);
            }
        } else {
            return redirect('/')->with('error', 'Su sesión ha Terminado');
        }
    }

    public function UpdateTotregSesion($idses)
    {
        $AreSim = \App\SessionArea::Consultar($idses);

        $TotPreg = 0;
        foreach ($AreSim as $Are) {
            $TotPreg = $TotPreg + $Are->npreguntas;
        }

        $EditSecion = \App\DetaSesionesSimul::Modificar($idses, $TotPreg);
        return $AreSim;
    }

    public function CargInfAreaSesion()
    {

        if (Auth::check()) {
            $idAreaSes = request()->get('idAreSes');
            $SesAre = \App\SessionArea::ConsultarInf($idAreaSes);
            $CompAre = \App\CompAreaSession::ConsultarInf($idAreaSes);

            if ($SesAre->area == "5") {
                $PregArea = \App\ModE_PreguntAreas::ConsultarInfIngles($idAreaSes);
            } else {
                $PregArea = \App\ModE_PreguntAreas::ConsultarInf($idAreaSes);
            }



            $Preguntas = self::OrganizarPreguntas($PregArea, $SesAre->area);

            if (request()->ajax()) {
                return response()->json([
                    'SesAre' => $SesAre,
                    'CompAre' => $CompAre,
                    'PregArea' => $Preguntas,
                ]);
            }
        } else {
            return redirect('/')->with('error', 'Su Sesión ha Terminado');
        }
    }

    public function ConsultarPreguntasAreas()
    {

        if (Auth::check()) {
            $datos = request()->all();

            $idArea = $datos['idAreaSesion'];
            $Sesion = \App\SesionAlumnos::Consultar($datos);

            if ($Sesion->count() == 0) {
                $Sesion = \App\SesionAlumnos::Guardar($datos);
            }

            $areaxsesion = \App\SessionArea::ConsultarInf($idArea);

            $PregArea = \App\ModE_PreguntAreas::ConsultarInf($idArea);
            if (request()->ajax()) {
                return response()->json([
                    'PregArea' => $PregArea,
                    'areaxsesion' => $areaxsesion,

                ]);
            }
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function ConsultarAreasxSesion()
    {

        if (Auth::check()) {
            $idAreaSes = request()->get('idSesi');
            $Sesion = \App\DetaSesionesSimul::ConsultarSesion($idAreaSes);

            $SesAre = \App\SessionArea::ConsultarAreasSesion($idAreaSes);

            if (request()->ajax()) {
                return response()->json([
                    'SesAre' => $SesAre,
                    'Sesion' => $Sesion,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function ConsultarSesionesSimulacros()
    {
        $IdSimu = request()->get('IdSimu');
        $InfAreas = array(); //creamos un array

        if (Auth::check()) {
            $DetaSesion = \App\DetaSesionesSimul::ConsultarSesiones($IdSimu);
            for ($i = 0; $i < count($DetaSesion); $i++) {
                $DetaSesionxAreas = \App\SessionArea::ConsultarAreasxSesiones($DetaSesion[$i]['id']);
                $DetaSesion[$i]['DetAreasxSEsiones'] = $DetaSesionxAreas;
            }

            if (request()->ajax()) {
                return response()->json([
                    'DetaSesion' => $DetaSesion,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su sesión ha Terminado");
        }
    }

    public function EliminarPregSesionArea()
    {
        if (Auth::check()) {
            $idSes = request()->get('IdSes');
            $IdSesArea = request()->get('IdSesArea');

            //  $SesionArea = \App\SessionArea::Eliminar($IdSesArea);
            $PregArea = \App\ModE_PreguntAreas::EliminarPreg($IdSesArea);


            if (request()->ajax()) {
                return response()->json([
                    'SesionArea' => $PregArea
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function GenerPregArea()
    {
        if (Auth::check()) {
            $datos = request()->all();

            $SesAre = \App\PregOpcMulMe::GenPregAle($datos);
            if ($SesAre) {
                $Preguntas = self::OrganizarPreguntas($SesAre, '');
                if ($SesAre) {
                    if (request()->ajax()) {
                        return response()->json([
                            'Preguntas' => $Preguntas,
                        ]);
                    }
                }
            }
        } else {
            return redirect('/')->with('error', 'Su sesión ha Terminado');
        }
    }

    public function CargaPregCompexCompo()
    {
        if (Auth::check()) {
            $compexcomp = explode("-", request()->get('compexcomp'));
            $SesAre = \App\PregOpcMulMe::BuscaPregCompexCompo($compexcomp[0], $compexcomp[1]);
            if ($SesAre) {
                if (request()->ajax()) {
                    return response()->json([
                        'Preguntas' => $SesAre,
                    ]);
                }
            }
        } else {
            return redirect('/')->with('error', 'Su sesión ha Terminado');
        }
    }

    public function OrganizarPreguntas($SesAre, $area)
    {
        $Preguntas = '';
        $conse = 1;

        foreach ($SesAre as $index => $Preg) {

            if ($area == "5") {
                if ($Preg->tipo_pregunta == "PARTE 1") {
                    $PregMult = \App\PregOpcMulMe::ConsulPregBan($Preg->banco);

                    $PreEnunciado = "CUAL PALABRA CONCUERDA CON LA DESCRIPCIÓN DE LA FRASE?";
                    $Preguntas .= '<div  class="bs-callout-primary callout-border-right callout-bordered callout-transparent p-1 mt-3">'
                        . '<h4 class="primary">' . $PreEnunciado . '!</h4>'
                        . $PregMult->pregunta
                        . '</div>';

                    $OpcPreg = \App\PreguntasParte1::ConsultarPreg($PregMult->id);

                    foreach ($OpcPreg as $opc) {
                        $Preguntas .= "<div class='row'>";
                        $Preguntas .= "<div class='col-12'>";
                        $Preguntas .= ' <div class="bs-callout-success callout-border-right callout-bordered callout-transparent mt-1 p-1">'
                            . '<h4 class="success">Pregunta ' . $conse . '</h4><input type="hidden" name="Preguntas[]" value="' . $Preg->id . '" />'
                            . '<input type="hidden" name="PregBancoId[]" value="' .  $Preg->banco . '" />'
                            . '<input type="hidden" name="PregTipPreg[]" value="' .  $Preg->tipo_pregunta  . '" />';
                        $Preguntas .= "<div class='col-6'>";
                        $Preguntas .= ' <label for="input-15"><b>Pregunta:</b> ' . $opc->pregunta . '</label></fieldset>';
                        $Preguntas .= "</div>";
                        $Preguntas .= "<div class='col-6'>";
                        $Preguntas .= ' <label for="input-15"><b>Respuesta:</b> ' . $opc->respuesta . '</label></fieldset>';
                        $Preguntas .= "</div>";
                        $Preguntas .= "</div>";
                        $Preguntas .= "</div>";
                        $Preguntas .= "</div>";
                        $conse++;
                    }
                } else {

                    $PregMult = \App\PregOpcMulMe::ConsulPregAll($Preg->banco);



                    $PreEnunciado = "RESPONDA LA SIGUIENTE PREGUNTA SEGUN EL SIGUIENTE ENUNCIADO";
                    $Preguntas .= '<div  class="bs-callout-primary callout-border-right callout-bordered callout-transparent p-1 mt-3">'
                        . '<h4 class="primary">' . $PreEnunciado . '!</h4>'
                        . $Preg->enunciado
                        . '</div>';
                    foreach ($PregMult as $preg) {
                        $Preguntas .= ' <div class="bs-callout-success callout-border-right callout-bordered callout-transparent mt-1 p-1">'
                            . '<h4 class="success">Pregunta ' . $conse . '</h4><input type="hidden" name="Preguntas[]" value="' . $preg->id . '" />'
                            . '<input type="hidden" name="PregBancoId[]" value="' .  $Preg->banco . '" />'
                            . '<input type="hidden" name="PregTipPreg[]" value="' .  $Preg->tipo_pregunta  . '" />'
                            . $Preg->pregunta;
                        $OpcPreg = \App\OpcPregMulModuloE::BuscOpcSimu($preg->id);
                        $Preguntas .= '  <ul class="list-group icheck-task">';
                        foreach ($OpcPreg as $opc) {
                            $disable = "disabled";
                            if ($opc->correcta == "si") {
                                $disable = "disabled checked";
                            }

                            $Preguntas .= '<fieldset><input type="checkbox" ' . $disable . ' id="input-15" > <label for="input-15">' . $opc->opciones . '</label></fieldset>';
                        }
                        $conse++;
                        $Preguntas .= ' </ul></div>';
                    }

                   
                }
            } else {

                $PreEnunciado = "RESPONDA LA SIGUIENTE PREGUNTA SEGUN EL SIGUIENTE ENUNCIADO";
                $Preguntas .= '<div  class="bs-callout-primary callout-border-right callout-bordered callout-transparent p-1 mt-3">'
                    . '<h4 class="primary">' . $PreEnunciado . '!</h4>'
                    . $Preg->enunciado
                    . '</div>';

                $Preguntas .= ' <div class="bs-callout-success callout-border-right callout-bordered callout-transparent mt-1 p-1">'
                    . '<h4 class="success">Pregunta ' . $conse . '</h4><input type="hidden" name="Preguntas[]" value="' . $Preg->id . '" />'
                    . '<input type="hidden" name="PregBancoId[]" value="' .  $Preg->banco . '" />'
                    . '<input type="hidden" name="PregTipPreg[]" value="' .  $Preg->tipo_pregunta  . '" />'
                    . $Preg->pregunta;
                $OpcPreg = \App\OpcPregMulModuloE::BuscOpcSimu($Preg->id);
                $Preguntas .= '  <ul class="list-group icheck-task">';
                foreach ($OpcPreg as $opc) {
                    $disable = "disabled";
                    if ($opc->correcta == "si") {
                        $disable = "disabled checked";
                    }

                    $Preguntas .= '<fieldset><input type="checkbox" ' . $disable . ' id="input-15" > <label for="input-15">' . $opc->opciones . '</label></fieldset>';
                }

                $Preguntas .= ' </ul></div>';
                $conse++;
            }
        }

        return $Preguntas;
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
            $AlumNotif = \App\ComentTemas::ConsultarDoce($Alu->usuario, 'ME');
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
