<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ModulosController extends Controller
{
    public function GestionModulos()
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
            $Modulos = \App\ModulosTransversales::Gestion($busqueda, $actual, $limit, $nombre);
            $numero_filas = \App\ModulosTransversales::numero_de_registros(request()->get('txtbusqueda'), $nombre);

            $paginas = ceil($numero_filas / $limit); //$numero_filas/10;
            return view('Modulos.GestionModulos', compact('bandera', 'numero_filas', 'paginas', 'actual', 'limit', 'busqueda', 'Modulos'));
        } else {
            return redirect("/")->with("error", "Su sesion ha terminado");
        }
    }

    public function NuevoModulo()
    {
        $bandera = "Menu4";
        $opc = "nueva";

        if (Auth::check()) {
            $Modulo = new \App\ModulosTransversales();

            return view('Modulos.NuevoModulo', compact('bandera', 'Modulo', 'opc'));
        } else {
            return redirect("/")->with("error", "Su sesion ha terminado");
        }
    }

        
    public function cambiarCompDocentes() {
        $idAsig = request()->get('id2');
        if (Auth::check()) {
            $Docentes = \App\ModProf::listaProf($idAsig);

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
            $Docentes = \App\ModProf::listaEditProf($idMod,$idTem);

            if (request()->ajax()) {
                return response()->json([
                            'Docentes' => $Docentes
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su sesion ha terminado");
        }
    }

    public function GuardarModulo()
    {
        if (Auth::check()) {

            $this->validate(request(), [
                'nombre' => 'required',
            ], [
                'nombre.required' => 'Debe Ingresar el nombre del módulo',
            ]);
            $data = request()->all();

            if (request()->hasfile('imagen')) {
                foreach (request()->file('imagen') as $file) {
                    $prefijo = substr(md5(uniqid(rand())), 0, 6);
                    $name = self::sanear_string($prefijo . '_' . $file->getClientOriginalName());
                    $file->move(public_path() . '/app-assets/images/Img_ModulosTransv/', $name);
                    $img[] = $name;
                }
                $data['img'] = $img;
            }
            $Asig = \App\ModulosTransversales::Guardar($data);
            if ($Asig) {
                $data['asig_id'] = $Asig->id;
                $ImgAsig = \App\ImgModulosTransversales::Guardar($data);

                if ($ImgAsig) {
                    $Log = \App\Log::Guardar('Módulo Transversal Guardado', $Asig->id);
                    return redirect('Modulos/GestionModulos')->with('success', 'Datos Guardados');
                } else {
                    return redirect('Modulos/GestionModulos')->with('error', 'Datos no Guardados');
                }
            } else {
                return redirect('Modulos/GestionModulos')->with('error', 'Datos no Guardados');
            }
        } else {
            return redirect("/")->with("error", "Su sesion ha terminado");
        }
    }

    public function DelGruposMod()
    {
        if (Auth::check()) {
            $mensaje = "";
            $estado = "no";
            $id = request()->get('id');
            $idMod = request()->get('idMod');

            $Grupo = \App\GruposModTransv::ConsultarGrupo($id, $idMod);
            $respAsig = \App\ModProf::ConsultarGrupo($Grupo->id);
            if ($respAsig > 0) {
                $mensaje = 'No se puede Eliminar este grupo, ya que ha sido asignado a un Docente';
                $estado = "asig";
            } else {
                $respuesta = \App\GruposModTransv::EliminarGrrupo($id, $idMod);
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

    public function EditarModulos($id)
    {
        $bandera = "Menu4";
        $opc = "editar";
        $tr_img = "";

        if (Auth::check()) {
            $Modulo = \App\ModulosTransversales::BuscarAsig($id);
            $Imagenes = \App\ImgModulosTransversales::ListImg($id);

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

            return view('Modulos.EditarModulos', compact('bandera', 'Modulo', 'tr_img', 'opc'));
        } else {
            return redirect("/")->with("error", "Su sesion ha terminado");
        }
    }

    public function ModificarModulos($id)
    {
        if (Auth::check()) {
            $Asig = \App\Modulos::BuscarAsig($id);
            $this->validate(request(), [
                'nombre' => 'required',
            ], [
                'nombre.required' => 'Debe Ingresar el nombre del Módulo',
            ]);
            $data = request()->all();
            $data['asig_id'] = $id;

            if (request()->hasfile('imagen')) {

                foreach (request()->file('imagen') as $file) {
                    $name = rand(1, 100) . '_' . $file->getClientOriginalName();
                    $file->move(public_path() . '/app-assets/images/Img_ModulosTransv/', $name);
                    $img[] = $name;
                }
                $data['img'] = $img;
            }

            $respuesta = \App\ModulosTransversales::modificar($data, $id);
            if ($respuesta) {
                if (request()->hasfile('imagen')) {
                    $ImgMod = \App\ImgModulosTransversales::Guardar($data);
                    if ($ImgMod) {
                        $Log = \App\Log::Guardar('Módulo Transversal Editado', $id);
                        return redirect('Modulos/GestionModulos')->with('success', 'Datos Guardados');
                    } else {
                        return redirect('Modulos/GestionModulos')->with('error', 'Datos no Guardados');
                    }
                } else {
                    $Log = \App\Log::Guardar('Módulo Transversal Editado', $id);
                    return redirect('Modulos/GestionModulos')->with('success', 'Datos Guardados');
                }
            } else {
                return redirect('Modulos/GestionModulos')->with('error', 'Datos no Guardados');
            }
        } else {
            return redirect("/")->with("error", "Su sesion ha terminado");
        }
    }

    public function DelImgModulo()
    {

        $mensaje = "";
        $estado = "no";
        $id = request()->get('id');
        if (Auth::check()) {
            $respuesta = \App\ImgModulosTransversales::EliminarImg($id);
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
            return redirect("/")->with("error", "Su sesion ha terminado");
        }
    }

    public function DelImgModuloGrado()
    {

        $mensaje = "";
        $estado = "no";
        $id = request()->get('id');
        if (Auth::check()) {
            $respuesta = \App\ImgGradosModulosTransv::EliminarImg($id);
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
            return redirect("/")->with("error", "Su sesion ha terminado");
        }
    }

    public function EliminarModTransv()
    {
        $mensaje = "";
        $id = request()->get('id');
        if (Auth::check()) {
            //VErifico si esta permitido eliminar el contenido seleccionado
            $Verf = \App\ModulosTransversales::VerfDel($id);

            if ($Verf->count() > 0) {
                $estado = "SINPERMISO";
                $mensaje = 'Este Contenido es Propio de la Plataforma,  No puede ser Eliminado...';
            } else {

                //verfico si el registro seleccionado tiene registros relacionados    
                $Grado = \App\GradosModulos::ListarxAsig($id);

                if ($Grado->count() > 0) {
                    $estado = "NO ELIMINADO";
                    $mensaje = 'La Operación no pudo ser Realizada, El Módulo Transversal tiene Grados Asignados.';
                } else {

                    $Asig = \App\ModulosTransversales::BuscarAsig($id);

                    $estado = "ACTIVO";
                    if ($Asig->estado == "ACTIVO") {

                        $estado = "ELIMINADO";
                    } else {
                        $estado = "ACTIVO";
                    }

                    $respuesta = \App\ModulosTransversales::editarestado($id, $estado);

                    if ($respuesta) {
                        if ($estado == "ELIMINADO") {
                            $Log = \App\Log::Guardar('Módulo Transversal Eliminado', $id);
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
            return redirect("/")->with("error", "Su sesion ha terminado");
        }
    }

    public function GestionGradosModTransv()
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
            $Asignatura = \App\GradosModulos::Gestion($busqueda, $actual, $limit, $nombre);
            $numero_filas = \App\GradosModulos::numero_de_registros(request()->get('txtbusqueda'), $nombre);
            $ListAsig = \App\ModulosTransversales::listar();

            $select_Asig = "<option value='' selected>TODOS LOS MODULOS</option>";
            foreach ($ListAsig as $Asig) {
                if ($Asig->id == $nombre) {
                    $select_Asig .= "<option value='$Asig->id' selected> " . strtoupper($Asig->nombre) . "</option>";
                } else {
                    $select_Asig .= "<option value='$Asig->id' >" . strtoupper($Asig->nombre) . "</option>";
                }
            }
            $paginas = ceil($numero_filas / $limit); //$numero_filas/10;
            return view('Modulos.GestionGradoModTransv', compact('bandera', 'numero_filas', 'paginas', 'actual', 'limit', 'busqueda', 'Asignatura', 'select_Asig', 'nombre'));
        } else {
            return redirect("/")->with("error", "Su sesion ha terminado");
        }
    }

    public function NuevoGradoModTransv()
    {
        $bandera = "Menu4";
        $opc = "nueva";
        $i = 1;
        $j = 1;
        $trPer = '';
        $Cursos = '';
        if (Auth::check()) {

            $Modulos = \App\ModulosTransversales::listar();
            $Cole = \App\Colegios::InfColeg(Session::get('IdColegio'));
            $Grados = new \App\GradosModulos();
            $ParGrupos = \App\ParGrupos::LisGrupos($Cole->num_cursos, $Cole->cant_grupos);

            $SelGrupos = "";
            foreach ($ParGrupos as $Grup) {
                $SelGrupos .= "<option value='$Grup->id' >" . strtoupper($Grup->descripcion) . "</option>";
            }

            return view('Modulos.NuevoGradModTransv', compact('bandera', 'Modulos', 'Grados', 'opc', 'trPer', 'i', 'SelGrupos'));
        } else {
            return redirect("/")->with("error", "Su sesion ha terminado");
        }
    }

    public function GuardarGradoModTransv()
    {

        $bandera = "Menu4";

        if (Auth::check()) {

            $this->validate(request(), [
                'modulo' => 'required',
                'grado_modulo' => 'required',
                'grupos' => 'required',
            ], [
                'modulo.required' => 'Debe Seleccionar el Módulo',
                'grado_modulo.required' => 'Debe Seleccionar el Grado',
                'grupos.required' => 'Debe Seleccionar Los Grupos para el Grado Seleccionado',
            ]);
            $data = request()->all();

            if (request()->hasfile('imagen')) {
                foreach (request()->file('imagen') as $file) {
                    $prefijo = substr(md5(uniqid(rand())), 0, 6);
                    $name = self::sanear_string($prefijo . '_' . $file->getClientOriginalName());
                    $file->move(public_path() . '/app-assets/images/Img_GradosModTransv/', $name);
                    $img[] = $name;
                }
                $data['img'] = $img;
            }
            $Asig = \App\GradosModulos::Guardar($data);
            if ($Asig) {
                $data['modulo_id'] = $Asig->id;
                $Grupos = \App\GruposModTransv::Guardar($data);
                if ($Grupos) {
                    $periodos = \App\PeriodosModTransv::Guardar($data);
                    if ($periodos) {
                        $ImgMod = \App\ImgGradosModulosTransv::Guardar($data);
                        if ($ImgMod) {
                            $Log = \App\Log::Guardar('Grado de Módulo Transversal Guardado', $Asig->id);
                            return redirect('Modulos/GestionGradosModTransv')->with('success', 'Datos Guardados');
                        } else {
                            return redirect('Modulos/GestionGradosModTransv')->with('error', 'Datos no Guardados');
                        }
                    } else {
                        return redirect('Modulos/GestionGradosModTransv')->with('error', 'Datos no Guardados');
                    }
                } else {
                    return redirect('Modulos/GestionGradosModTransv')->with('error', 'Datos no Guardados');
                }
            } else {
                return redirect('Modulos/GestionGradosModTransv')->with('error', 'Datos no Guardados');
            }
        } else {
            return redirect("/")->with("error", "Su sesion ha terminado");
        }
    }

    public function EditarGradModTransv($id)
    {

        $bandera = "Menu4";
        $opc = "editar";
        $trPer = '';
        $tr_img = '';
        $Cursos = '';

        if (Auth::check()) {
            $Areas = \App\Areas::listar();
            $Grados = \App\GradosModulos::BuscarAsig($id);
            $Modulos = \App\ModulosTransversales::listar();
            $Cole = \App\Colegios::InfColeg(Session::get('IdColegio'));

            $Perio = \App\PeriodosModTransv::listar($id);
            $Grupos = \App\GruposModTransv::listar($id);
            $Imagenes = \App\ImgGradosModulosTransv::ListImg($id);

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
                    <td class="text-truncate">' . $PR->des_periodo . '</td><input type="hidden" id="txtperi' . $i . '" name="txtperi[]" class="txtperi"   value="' . $PR->des_periodo . '"><input type="hidden" id="txtidperi' . $i . '" name="txtidperi[]"  value="' . $PR->id . '">
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

            return view('Modulos.EditarGradModTransv', compact('bandera', 'Grados', 'Modulos', 'trPer', 'tr_img', 'i', 'opc', 'SelGrupos', 'Areas'));
        } else {
            return redirect("/")->with("error", "Su sesion ha terminado");
        }
    }

    public function DelImgModTransv()
    {

        $mensaje = "";
        $estado = "no";
        $id = request()->get('id');
        if (Auth::check()) {
            $respuesta = \App\ImgGradosModulosTransv::EliminarImg($id);
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
            return redirect("/")->with("error", "Su sesion ha terminado");
        }
    }

    public function DelPerModTransv()
    {

        $mensaje = "";
        $estado = "no";
        $id = request()->get('id');
        if (Auth::check()) {

            $Periodos = \App\UnidadesModulos::UnidadesxPeriodo($id);

            if ($Periodos->count() > 0) {
                $mensaje = 'La Operación no pudo ser Realizada, Exixten Uniades Asignadas a este Periodo';
                $estado = "no";
            } else {
                $respuesta = \App\PeriodosModTransv::editarestado($id);
                if ($respuesta) {
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
            return redirect("/")->with("error", "Su sesion ha terminado");
        }
    }

    public function ModificarGradModTransv($id)
    {

        if (Auth::check()) {

            $this->validate(request(), [
                'modulo' => 'required',
                'grado_modulo' => 'required',
            ], [
                'modulo.required' => 'Debe Seleccionar el Módulo',
                'grado_modulo.required' => 'Debe Seleccionar el Grado',
            ]);
            $data = request()->all();
            $data['modulo_id'] = $id;

            if (request()->hasfile('imagen')) {

                foreach (request()->file('imagen') as $file) {
                    $name = rand(1, 100) . '_' . $file->getClientOriginalName();
                    $file->move(public_path() . '/app-assets/images/Img_GradosModTransv/', $name);
                    $img[] = $name;
                }
                $data['img'] = $img;
            }

            $respuesta = \App\GradosModulos::modificar($data, $id);
            if ($respuesta) {
                $Grupos = \App\GruposModTransv::Guardar($data);
                if ($Grupos) {
                    $periodos = \App\PeriodosModTransv::Guardar($data);
                    if ($periodos) {
                        if (request()->hasfile('imagen')) {
                            $ImgMod = \App\ImgGradosModulosTransv::Guardar($data);
                            if ($ImgMod) {
                                $Log = \App\Log::Guardar('Grado de Módulo Transversal Editado', $id);
                                return redirect('Modulos/GestionGradosModTransv')->with('success', 'Datos Guardados');
                            } else {
                                return redirect('Modulos/GestionGradosModTransv')->with('error', 'Datos no Guardados');
                            }
                        } else {
                            $Log = \App\Log::Guardar('Grado de Módulo Transversal Editado', $id);

                            return redirect('Modulos/GestionGradosModTransv')->with('success', 'Datos Guardados');
                        }
                    } else {
                        return redirect('Modulos/GestionGradosModTransv')->with('error', 'Datos no Guardados');
                    }
                } else {
                    return redirect('Modulos/GestionGradosModTransv')->with('error', 'Datos no Guardados');
                }
            } else {
                return redirect('Modulos/GestionGradosModTransv')->with('error', 'Datos no Guardados');
            }
        } else {
            return redirect("/")->with("error", "Su sesion ha terminado");
        }
    }

    public function EliminarGradModTransv()
    {

        $mensaje = "";
        $id = request()->get('id');
        if (Auth::check()) {

            //VErifico si esta permitido eliminar el contenido seleccionado
            $Verf = \App\GradosModulos::VerfDel($id);
            if ($Verf->count() > 0) {
                $mensaje = 'Este Contenido es Propio de la Plataforma,  No puede ser Eliminado...';
                $estado = "SINPERMISO";
            } else {
                $Peridos = \App\PeriodosModTransv::listarVerf($id);

                if ($Peridos->count() > 0) {

                    $estado = "NO ELIMINADO";
                    $mensaje = 'No es posible Eliminar este Grado. Tiene Periodos Relacionados con Unidades Asignadas.';
                } else {

                    $Asig = \App\GradosModulos::BuscarAsig($id);
                    $estado = "ACTIVO";
                    if ($Asig->estado_modulo == "ACTIVO") {
                        $estado = "ELIMINADO";
                    } else {
                        $estado = "ACTIVO";
                    }
                    $respuesta = \App\GradosModulos::editarestado($id, $estado);

                    if ($respuesta) {
                        if ($estado == "ELIMINADO") {
                            $Log = \App\Log::Guardar('Grado de Módulo Transversal Eliminado', $id);

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
            return redirect("/")->with("error", "Su sesion ha terminado");
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
            $Unidades = \App\UnidadesModulos::Gestion($busqueda, $actual, $limit, $nombre);
            $numero_filas = \App\UnidadesModulos::numero_de_registros(request()->get('txtbusqueda'), $nombre);
            $paginas = ceil($numero_filas / $limit); //$numero_filas/10;
            $Asignaturas = \App\GradosModulos::listar();
            $Docentes = \App\Profesores::Listar();
            $select_docente = "<option value='' selected>Seleccione el Docente</option>";

            foreach ($Docentes as $doce) {
                $select_docente .= "<option value='$doce->usuario_profesor'> " . strtoupper($doce->nombre . " " . $doce->apellido) . "</option>";
            }

            $select_Asig = "<option value='' selected>TODOS LOS MÓDULOS</option>";
            foreach ($Asignaturas as $Asig) {
                if ($Asig->id == $nombre) {
                    $select_Asig .= "<option value='$Asig->id' selected> " . strtoupper($Asig->nombre) . " - Grado " . $Asig->grado_modulo . "°</option>";
                } else {
                    $select_Asig .= "<option value='$Asig->id' >" . strtoupper($Asig->nombre) . " - Grado " . $Asig->grado_modulo . "°</option>";
                }
            }

            return view('Modulos.GestionUnidad', compact('bandera', 'numero_filas', 'paginas', 'actual', 'limit', 'busqueda', 'Unidades', 'select_Asig', 'nombre', 'select_docente'));
        } else {
            return redirect("/")->with("error", "Su sesion ha terminado");
        }
    }

    public function GestionNuevaUnidad()
    {
        $idAsig = request()->get('id');
        $bandera = "Menu4";
        $opc = "nueva";
        if (Auth::check()) {
            $unid = new \App\UnidadesModulos();
            $Asigna = \App\GradosModulos::listar();

            return view('Modulos.NuevaUnidad', compact('bandera', 'unid', 'Asigna', 'opc'));
        } else {
            return redirect("/")->with("error", "Su sesion ha terminado");
        }
    }

    public function CambiarPeriodo()
    {
        $idAsig = request()->get('id');
        if (Auth::check()) {
            $Periodo = \App\PeriodosModTransv::listar($idAsig);
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
            return redirect("/")->with("error", "Su sesion ha terminado");
        }
    }

    public function ReasignarUnidades()
    {
        $data = request()->all();

        $VerAsig = \App\UnidadesModulos::VerificarAsig($data);
        $Estado = "NO";
        if ($VerAsig == "si") {
            $Unidades = \App\UnidadesModulos::ReasignarAsig($data);
            $Mensaje = "Operación Realizada de Manera Exitosa";
            $Estado = "SI";
        } else {
            $Mensaje = "Este Docente no tiene asignado el Módulo al cual pertenece una de las unidades que está tratando de Reasignar, Por favor Realice la asignación del Módulo correspondiente antes de realizar este proceso.";
            $Estado = "NO";
        }

        if (request()->ajax()) {
            return response()->json([
                'Mensaje' => $Mensaje,
                "Estado" => $Estado,
            ]);
        }
    }

    public function CargarEvalReasignar()
    {
        $doce = request()->get('doce');
        $Temas = \App\Evaluacion::listarEvalxDocenteModu($doce);

        if (request()->ajax()) {
            return response()->json([
                'Temas' => $Temas,
            ]);
        }
    }

    public function ReasignarEval()
    {
        $data = request()->all();

        $VerAsig = \App\Evaluacion::VerificarModu($data);
        $Estado = "NO";
        if ($VerAsig == "si") {
            $Temas = \App\Evaluacion::ReasignarAsig($data);
            $Mensaje = "Operación Realizada de Manera Exitosa";
            $Estado = "SI";
        } else {
            $Mensaje = "Este Docente no tiene asignado el Módulo al cual pertenece(n) la(s) Evaluación(es) que está tratando de Reasignar, Por favor Realice la asignación del Módulo correspondiente antes de realizar este proceso.";
            $Estado = "NO";
        }

        if (request()->ajax()) {
            return response()->json([
                'Mensaje' => $Mensaje,
                "Estado" => $Estado,
            ]);
        }
    }

    public function CargarTemasReasignar()
    {
        $doce = request()->get('doce');
        $Temas = \App\TemasModulos::listarTemasxDocente($doce);

        if (request()->ajax()) {
            return response()->json([
                'Temas' => $Temas,
            ]);
        }
    }

    public function ReasignarTemas()
    {
        $data = request()->all();

        $VerAsig = \App\TemasModulos::VerificarAsig($data);

        $Estado = "NO";
        if ($VerAsig == "si") {
            $Temas = \App\TemasModulos::ReasignarAsig($data);
            $Mensaje = "Operación Realizada de Manera Exitosa";
            $Estado = "SI";
        } else {
            $Mensaje = "Este Docente no tiene asignado el Módulo al cual pertenece los Temas que está tratando de Reasignar, Por favor Realice la asignación del Módulo correspondiente antes de realizar este proceso.";
            $Estado = "NO";
        }

        if (request()->ajax()) {
            return response()->json([
                'Mensaje' => $Mensaje,
                "Estado" => $Estado,
            ]);
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
                'modulo.required' => 'Debe Seleccionar el Módulo',
                'periodo.required' => 'Debe Seleccionar el Periodo',
                'nom_unidad.required' => 'Seleccione el numero de la Unidad',
            ]);
            $data = request()->all();
            $Unid = \App\UnidadesModulos::Guardar($data);
            if ($Unid) {
                $Log = \App\Log::Guardar('Unidad de Módulos Guardada', $Unid->id);
                return redirect('Modulos/GestionUnid')->with('success', 'Datos Guardados');
            } else {
                return redirect('Modulos/GestionUnid')->with('error', 'Datos no Guardados');
            }
        } else {
            return redirect("/")->with("error", "Su sesion ha terminado");
        }
    }

    public function editarUnidad($id)
    {
        $bandera = "Menu4";
        $opc = "editar";
        $trPer = '';

        if (Auth::check()) {

            $unid = \App\UnidadesModulos::BuscarUnidad($id);
            $Asigna = \App\GradosModulos::listar();
            return view('Modulos.EditarUnidad', compact('bandera', 'unid', 'opc', 'Asigna'));
        } else {
            return redirect("/")->with("error", "Su sesion ha terminado");
        }
    }

    public function consultarUnidad($id)
    {
        $bandera = "Menu4";
        $opc = "Consulta";

        if (Auth::check()) {
            $unid = \App\UnidadesModulos::BuscarUnidad($id);
            $Asigna = \App\GradosModulos::listar();
            return view('Modulos.ConsultarUnidad', compact('bandera', 'unid', 'Asigna', 'opc'));
        } else {
            return redirect("/")->with("error", "Su sesion ha terminado");
        }
    }

    public function CambiarPeriodo2()
    {
        $idAsig = request()->get('id');
        $idPer = request()->get('idPer');

        if (Auth::check()) {
            $Periodo = \App\PeriodosModTransv::listar($idAsig);

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
            return redirect("/")->with("error", "Su sesion ha terminado");
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
            $respuesta = \App\UnidadesModulos::modificar($data, $id);
            if ($respuesta) {
                $Log = \App\Log::Guardar('Unidad de Módulo Modificada', $id);
                return redirect('Modulos/GestionUnid')->with('success', 'Datos Guardados');
            } else {
                return redirect('Modulos/GestionUnid')->with('error', 'Datos no Guardados');
            }
        } else {
            return redirect("/")->with("error", "Su sesion ha terminado");
        }
    }

    public function EliminarUnidad()
    {
        $mensaje = "";
        $id = request()->get('id');
        if (Auth::check()) {

            //Verifico si esta permitido eliminar el contenido seleccionado
            $Verf = \App\UnidadesModulos::VerfDel($id);
            if ($Verf->count() > 0) {
                $mensaje = 'Este Contenido es Propio de la Plataforma,  No puede ser Eliminado...';
                $estado = "SINPERMISO";
                $opc = "VU";
            } else {

                $opc = "NT";
                $UnidTem = \App\TemasModulos::BuscarUnidadTema($id);
                $Unid = \App\UnidadesModulos::BuscarUnidad($id);

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

                        $respuesta = \App\UnidadesModulos::editarestado($id, $estado);

                        if ($respuesta) {
                            if ($estado == "ELIMINADO") {
                                $Log = \App\Log::Guardar('Unidad de Módulo Eliminada', $id);
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
            return redirect("/")->with("error", "Su sesion ha terminado");
        }
    }

    public function InfDocumentosDida()
    {
        $IdTema = request()->get('IdTema');
        if (Auth::check()) {
            $DesTema = \App\ContDidacticoModulos::BuscarTema($IdTema, 'NO');

            if (request()->ajax()) {

                return response()->json([
                    'DesTema' => $DesTema,
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
            $respuesta = \App\ContDidacticoModulos::EliminarCont($id);
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
            $Temas = \App\TemasModulos::Gestion($busqueda, $actual, $limit, $nombre);
            $Asignaturas = \App\GradosModulos::listar();

            $Docentes = \App\Profesores::Listar();

            $select_docente = "<option value='' selected>Seleccione el Docente</option>";

            $select_Asig = "<option value='' selected>TODAS LAS ASIGNATURAS</option>";
            foreach ($Docentes as $doce) {
                $select_docente .= "<option value='$doce->usuario_profesor'> " . strtoupper($doce->nombre . " " . $doce->apellido) . "</option>";
            }

            $select_Asig = "<option value='' selected>TODOS LOS MÓDULOS</option>";
            foreach ($Asignaturas as $Asig) {
                if ($Asig->id == $nombre) {
                    $select_Asig .= "<option value='$Asig->id' selected> " . strtoupper($Asig->nombre) . " - Grado " . $Asig->grado_modulo . "°</option>";
                } else {
                    $select_Asig .= "<option value='$Asig->id' >" . strtoupper($Asig->nombre) . " - Grado " . $Asig->grado_modulo . "°</option>";
                }
            }

            $numero_filas = \App\TemasModulos::numero_de_registros(request()->get('txtbusqueda'), $nombre);
            $paginas = ceil($numero_filas / $limit); //$numero_filas/10;

            return view('Modulos.GestionTemas', compact('bandera', 'numero_filas', 'paginas', 'actual', 'limit', 'busqueda', 'nombre', 'Temas', 'nombre', 'select_Asig', 'select_docente'));
        } else {
            return redirect("/")->with("error", "Su sesion ha terminado");
        }
    }

    public function GestionNuevoTema()
    {
        $bandera = "Menu4";
        $opc = "nueva";
        if (Auth::check()) {
            $Tema = new \App\TemasModulos();
            $Asigna = \App\GradosModulos::listar();
            return view('Modulos.NuevoTema', compact('bandera', 'Tema', 'Asigna', 'opc'));
        } else {
            return redirect("/")->with("error", "Su sesion ha terminado");
        }
    }

    public function CambiarUnidad()
    {
        $idPeri = request()->get('id');
        if (Auth::check()) {
            $Unidades = \App\UnidadesModulos::listar($idPeri);
            //                        dd($Unidades);die();
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
            return redirect("/")->with("error", "Su sesion ha terminado");
        }
    }

    public function CargarUnidadesReasignar()
    {
        $doce = request()->get('doce');
        $Unidades = \App\UnidadesModulos::listarUnidadesxDocente($doce);

        if (request()->ajax()) {
            return response()->json([
                'Unidades' => $Unidades,
            ]);
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
                'modulo.required' => 'Debe Seleccionar el Módulo',
                'periodo.required' => 'Debe Seleccionar el Periodo',
                'unidad.required' => 'Seleccione el numero de la Unidad',
                'tip_contenido.required' => 'Seleccione el tipo de contenido',
                'titu_contenido.required' => 'Ingrese el Título del Contenido',
                'objetivo_general.required' => 'Ingrese el Objetivo General del Tema',
            ]);
            $datos = request()->all();
            $datos['ZL'] = 'NO';

            if ($datos['tip_contenido'] === "DOCUMENTO") {
                $Tem = \App\TemasModulos::GuardarTipCont($datos);
                if ($Tem) {
                    $datos['tema_id'] = $Tem->id;
                    if (request()->hasfile('archididatico')) {
                        foreach (request()->file('archididatico') as $file) {
                            $prefijo = substr(md5(uniqid(rand())), 0, 6);
                            $name = self::sanear_string($prefijo . '_' . $file->getClientOriginalName());
                            $Titu = $file->getClientOriginalName();
                            $file->move(public_path() . '/app-assets/Contenido_DidacticoModulos/', $name);
                            $arch[] = $name;
                            $archTit[] = $Titu;
                        }
                        $datos['archi'] = $arch;
                        $datos['TituAnim'] = $archTit;
                    }
                    $CompartirTemaDoc = \App\TemasDocenteModulos::GuardarCompTema($datos);
                    $ContTema = \App\DesarrollTemaModulos::GuardarContTema($datos);
                    if (request()->hasfile('archididatico')) {
                        $ContTemaDidac = \App\ContDidacticoModulos::GuardarContDidctico($datos);
                    }
                    if ($ContTema) {
                        $Log = \App\Log::Guardar('Tema de Módulo Guardado', $Tem->id);
                        return redirect('Modulos/GestionTem')->with('success', 'Datos Guardados');
                    } else {
                        return redirect('Modulos/GestionTem')->with('error', 'Datos no Guardados');
                    }
                } else {
                    return redirect('Modulos/GestionTem')->with('error', 'Datos no Guardados');
                }
                $Log = \App\Log::Guardar('Tema Guardado', $Tem->id);
            } else if ($datos['tip_contenido'] === "ARCHIVO") {

                $Tem = \App\TemasModulos::GuardarTipCont($datos);
                
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
                    $ContTemaArc = \App\SubirArcTemaModulos::GuardarArchCont($datos);
                    $CompartirTemaDoc = \App\TemasDocenteModulos::GuardarCompTema($datos);
                    if ($ContTemaArc) {
                        $Log = \App\Log::Guardar('Tema de Módulo Guardado', $Tem->id);
                        return redirect('Modulos/GestionTem')->with('success', 'Datos Guardados');
                    } else {
                        return redirect('Modulos/GestionTem')->with('error', 'Datos no Guardados');
                    }
                } else {
                    return redirect('Modulos/GestionTem')->with('error', 'Datos no Guardados');
                }
            } else if ($datos['tip_contenido'] === "CONTENIDO DIDACTICO") {

                $Tem = \App\TemasModulos::GuardarTipCont($datos);
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

                    $ContTemaDidac = \App\ContDidacticoModulos::GuardarContDidctico($datos);
                    $CompartirTemaDoc = \App\TemasDocenteModulos::GuardarCompTema($datos);
                    if ($ContTemaDidac) {
                        $Log = \App\Log::Guardar('Tema de Módulo Guardado', $Tem->id);
                        return redirect('Modulos/GestionTem')->with('success', 'Datos Guardados');
                    } else {
                        return redirect('Modulos/GestionTem')->with('error', 'Datos no Guardados');
                    }
                } else {
                    return redirect('Modulos/GestionTem')->with('error', 'Datos no Guardados');
                }
            } else if ($datos['tip_contenido'] === "LINK") {
                $Tem = \App\TemasModulos::GuardarTipCont($datos);
                if ($Tem) {
                    $datos['tema_id'] = $Tem->id;

                    $ContTemaLink = \App\DesarrolloLinkModulos::Guardar($datos);
                    $CompartirTemaDoc = \App\TemasDocenteModulos::GuardarCompTema($datos);
                    if ($ContTemaLink) {
                        $Log = \App\Log::Guardar('Tema de Módulo Guardado', $Tem->id);
                        return redirect('Modulos/GestionTem')->with('success', 'Datos Guardados');
                    } else {
                        return redirect('Modulos/GestionTem')->with('error', 'Datos no Guardados');
                    }
                } else {
                    return redirect('Modulos/GestionTem')->with('error', 'Datos no Guardados');
                }
                $Log = \App\Log::Guardar('Tema Guardado', $Tem->id);
            }
        } else {
            return redirect("/")->with("error", "Su sesion ha terminado");
        }
    }

    public function editarTema($id)
    {
        $bandera = "Menu4";
        $opc = "editar";
        if (Auth::check()) {

            $Tema = \App\TemasModulos::BuscarTema($id);
            $Asigna = \App\GradosModulos::listar();

            return view('Modulos.EditarTema', compact('bandera', 'Tema', 'Asigna', 'opc'));
        } else {
            return redirect("/")->with("error", "Su sesion ha terminado");
        }
    }

    public function CambiarUnidad2()
    {
        $idPeri = request()->get('idPer');
        $idUnid = request()->get('idUnid');
        if (Auth::check()) {
            $Unidades = \App\UnidadesModulos::listar($idPeri);
            //            dd($Periodo);die();
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
            return redirect("/")->with("error", "Su sesion ha terminado");
        }
    }

    public function InfDocumentos()
    {
        $IdTema = request()->get('IdTema');
        if (Auth::check()) {
            $DesTema = \App\DesarrollTemaModulos::BuscarTema($IdTema, 'NO');

            $Animaciones = \App\ContDidacticoModulos::BuscarTema($IdTema, 'NO');
            $content = $DesTema->cont_documento;
            if (request()->ajax()) {
                return response()->json([
                    'DesTema' => $DesTema,
                    'Animaciones' => $Animaciones,
                    'content' => $content,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su sesion ha terminado");
        }
    }

    public function InfArchivos()
    {
        $IdTema = request()->get('IdTema');
        if (Auth::check()) {
            $DatArchivos = \App\SubirArcTemaModulos::BuscarArchi($IdTema);
            if (request()->ajax()) {
                return response()->json([
                    'Archivos' => $DatArchivos,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su sesion ha terminado");
        }
    }

    public function DelArchivos()
    {

        $mensaje = "";
        $estado = "no";
        $id = request()->get('id');
        if (Auth::check()) {
            $respuesta = \App\SubirArcTemaModulos::EliminarArch($id);
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
            return redirect("/")->with("error", "Su sesion ha terminado");
        }
    }

    public function DelAnimacion()
    {

        $mensaje = "";
        $estado = "no";
        $id = request()->get('id');
        if (Auth::check()) {
            $respuesta = \App\ContDidacticoModulos::EliminarCont($id);
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
            return redirect("/")->with("error", "Su sesion ha terminado");
        }
    }
    public function InfLinks()
    {
        $IdTema = request()->get('IdTema');
        if (Auth::check()) {
            $DatLink = \App\DesarrolloLinkModulos::DesLink($IdTema, 'NO');
            if (request()->ajax()) {
                return response()->json([
                    'Links' => $DatLink,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su sesion ha terminado");
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

                $Tem = \App\TemasModulos::Modificar($datos, $id);
                $CompartirTemaDoc = \App\TemasDocenteModulos::GuardarCompTema($datos);
                if (request()->hasfile('archididatico')) {
                    foreach (request()->file('archididatico') as $file) {
                        $prefijo = substr(md5(uniqid(rand())), 0, 6);
                        $name = self::sanear_string($prefijo . '_' . $file->getClientOriginalName());
                        $Titu = $file->getClientOriginalName();
                        $file->move(public_path() . '/app-assets/Contenido_DidacticoModulos/', $name);
                        $arch[] = $name;
                        $archTit[] = $Titu;
                    }
                    $datos['archi'] = $arch;
                    $datos['TituAnim'] = $archTit;
                }

                if ($Tem) {
                    $ContTema = \App\DesarrollTemaModulos::Modificar($datos, $id);
                    if (request()->hasfile('archididatico')) {
                        $ContTemaDidac = \App\ContDidacticoModulos::modificar($datos, $id);
                    }

                    if ($ContTema) {
                        $Log = \App\Log::Guardar('Tema de Módulo Editado', $id);
                        return redirect('Modulos/GestionTem')->with('success', 'Datos Guardados');
                    } else {
                        return redirect('Modulos/GestionTem')->with('error', 'Datos no Guardados');
                    }
                } else {
                    return redirect('Modulos/GestionTem')->with('error', 'Datos no Guardados');
                }
            } else if ($datos['tip_contenido'] === "ARCHIVO") {
                $Tem = \App\TemasModulos::Modificar($datos, $id);
                $CompartirTemaDoc = \App\TemasDocenteModulos::GuardarCompTema($datos);
                if (request()->hasfile('archi')) {

                    foreach (request()->file('archi') as $file) {
                        $name = $file->getClientOriginalName();
                        $file->move(public_path() . '/app-assets/Archivos_Contenidos/', $name);
                        $arch[] = $name;
                    }
                    $datos['archi'] = $arch;
                    $ContTemaArc = \App\SubirArcTemaModulos::GuardarArchCont($datos);
                    if ($ContTemaArc) {
                        $Log = \App\Log::Guardar('Tema de Módulo Editado', $id);
                        return redirect('Modulos/GestionTem')->with('success', 'Datos Guardados');
                    } else {
                        return redirect('Modulos/GestionTem')->with('error', 'Datos no Guardados');
                    }
                } else {
                    return redirect('Modulos/GestionTem')->with('success', 'Datos Guardados');
                }
            } else if ($datos['tip_contenido'] === "CONTENIDO DIDACTICO") {

                $Tem = \App\TemasModulos::Modificar($datos, $id);
                $CompartirTemaDoc = \App\TemasDocenteModulos::GuardarCompTema($datos);
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

                        $ContTemaDidac = \App\ContDidacticoModulos::modificar($datos, $id);
                        if ($ContTemaDidac) {
                            $Log = \App\Log::Guardar('Tema de Módulo Editado', $id);
                            return redirect('Modulos/GestionTem')->with('success', 'Datos Guardados');
                        } else {
                            return redirect('Modulos/GestionTem')->with('error', 'Datos no Guardados');
                        }
                    } else {
                        return redirect('Modulos/GestionTem')->with('success', 'Datos Guardados');
                    }
                } else {
                    return redirect('Modulos/GestionTem')->with('error', 'Datos no Guardados');
                }
            } else if ($datos['tip_contenido'] === "LINK") {
                $Tem = \App\Temas::Modificar($datos, $id);
                $CompartirTemaDoc = \App\TemasDocenteModulos::GuardarCompTema($datos);
                if ($Tem) {

                    $ContTemaLink = \App\DesarrolloLinkModulos::Modificar($datos, $id);

                    if ($ContTemaLink) {
                        $Log = \App\Log::Guardar('Tema de Módulo Editado', $id);
                        return redirect('Modulos/GestionTem')->with('success', 'Datos Guardados');
                    } else {
                        return redirect('Modulos/GestionTem')->with('error', 'Datos no Guardados');
                    }
                } else {
                    return redirect('Asignaturas/GestionTem')->with('error', 'Datos no Guardados');
                }
            }
        } else {
            return redirect("/")->with("error", "Su sesion ha terminado");
        }
    }

    public function EliminarTema()
    {
        $mensaje = "";

        $id = request()->get('id');
        if (Auth::check()) {

            $Verf = \App\TemasModulos::VerfDel($id);

            if($Verf->count() > 0) {
                $estado = "SINPERMISO";
                $mensaje = 'Este Contenido es Propio de la Plataforma,  No puede ser Eliminado...';
            }else{

            $Tema = \App\TemasModulos::BuscarTema($id);
            if ($Tema->docente == "" && Auth::user()->tipo_usuario == 'Profesor') {
                $estado = "NO";
                $mensaje = 'Este Tema solo puede ser eliminado desde un perfil Administrador';
            } else {

                $TemaxTema = \App\Evaluacion::BusEvalxtema($id, 'M');

                if ($TemaxTema->count() > 0) {
                    $estado = "NO ELIMINADO";
                    $mensaje = 'La Operación no pudo ser Realizada, El tema tiene evaluaciones relacionadas';
                } else {
                    $estado = "ACTIVO";
                    if ($Tema->estado == "ACTIVO") {
                        $estado = "ELIMINADO";
                    } else {
                        $estado = "ACTIVO";
                    }
                    $respuesta = \App\TemasModulos::editarestado($id, $estado);

                    if ($respuesta) {
                        if ($estado == "ELIMINADO") {
                            $Log = \App\Log::Guardar('Tema de Módulo Eliminado', $id);
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
            return redirect("/")->with("error", "Su sesion ha terminado");
        }
    }

    public function GestionAsigEvaluacion($id)
    {
        $bandera = "Menu4";
        if (Auth::check()) {
            $Evaluaciones = \App\Evaluacion::ListEval($id, 'M');
            $DesTema = \App\TemasModulos::BuscarTema($id);
            $titTema = $DesTema->titu_contenido;

            $Docentes = \App\Profesores::Listar();
            $select_docente = "<option value='' selected>Seleccione el Docente</option>";

            foreach ($Docentes as $doce) {
                $select_docente .= "<option value='$doce->usuario_profesor'> " . strtoupper($doce->nombre . " " . $doce->apellido) . "</option>";
            }

            return view('Modulos.GestionEvaluaciones', compact('bandera', 'Evaluaciones', 'titTema', 'id', 'select_docente'));
        } else {
            return redirect("/")->with("error", "Su sesion ha terminado");
        }
    }

    public function AsigEvaluacion($id)
    {
        $bandera = "Menu4";
        $trEval = '';
        $opc = "nuevo";

        if (Auth::check()) {
            $Tema = \App\TemasModulos::BuscarTema($id);

            $Eval = new \App\Evaluacion();
            $Asigna = \App\GradosModulos::listar();
            return view('Modulos.AsigEvaluacion', compact('bandera', 'Tema', 'Asigna', 'Eval', 'opc'));
        } else {
            return redirect("/")->with("error", "Su sesion ha terminado");
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
                $Log = \App\Log::Guardar('Evaluación Módulo Modificada', $idEval);

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
                $Log = \App\Log::Guardar('Evaluación Módulo Modificada', $idEval);

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
                $Log = \App\Log::Guardar('Evaluación Módulo Modificada', $idEval);

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
                $Log = \App\Log::Guardar('Evaluación Módulo Modificada', $idEval);

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
                $Log = \App\Log::Guardar('Evaluación Módulo Modificada', $idEval);

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
                $Log = \App\Log::Guardar('Evaluación Módulo Modificada', $idEval);

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
            $Tema = \App\TemasModulos::BuscarTema($Eval->contenido);
            $Asigna = \App\GradosModulos::listar();
            return view('Modulos.EditarEval', compact('bandera', 'Tema', 'Asigna', 'opc', 'Eval'));
        } else {
            return redirect("/")->with("error", "Su sesion ha terminado");
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
            $Elib = \App\LibroCalificaciones::BusEvalDel($id);
            if ($Eval->docente == "" && Auth::user()->tipo_usuario == 'Profesor') {
                $estado = "NO";
                $mensaje = 'Esta Evaluación solo puede ser eliminada desde un perfil Administrador';
                $icon = 'warning';
            } else {

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
                            $Log = \App\Log::Guardar('Evaluación Módulo Eliminada', $id);
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
