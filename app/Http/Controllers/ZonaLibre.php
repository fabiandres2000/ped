<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;

class ZonaLibre extends Controller
{
    public function GestionZonaLibre()
    {
        $bandera = "Menu4";
        if (Auth::check()) {
            $busqueda = request()->get('txtbusqueda');
            $nombre = request()->get('grado');

            $actual = request()->get('page');
            if ($actual == null || $actual == "") {
                $actual = 1;
            }
            $limit = 10;
            $Temas = \App\ZonaLibre::Gestion($busqueda, $actual, $limit, $nombre);
//            $Asignaturas = \App\Modulos::listar();

            $select_grado = "<option value='' selected>TODOS LOS GRADOS</option>";

            for ($i = 1; $i <= 11; $i++) {
                if ($i == $nombre) {
                    $select_grado .= "<option value='$i' selected>Grado " . $i . "°</option>";
                } else {
                    $select_grado .= "<option value='$i'>Grado " . $i . "°</option>";
                }
            }

            $numero_filas = \App\ZonaLibre::numero_de_registros(request()->get('txtbusqueda'), $nombre);
            $paginas = ceil($numero_filas / $limit); //$numero_filas/10;

            return view('ZonaLibre.GestionZonaLibre', compact('bandera', 'numero_filas', 'paginas', 'actual', 'limit', 'busqueda', 'nombre', 'Temas', 'nombre', 'select_grado'));
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function NuevaZonaLibre()
    {
        $bandera = "Menu4";
        $opc = "nueva";
        if (Auth::check()) {
            $Tema = new \App\ZonaLibre();
            $Asigna = \App\Modulos::listar();
            $Eval = new \App\Evaluacion();

            $Cole = \App\Colegios::InfColeg(Session::get('IdColegio'));

            $ParGrupos = \App\ParGrupos::LisGrupos($Cole->num_cursos, $Cole->cant_grupos);

            $SelGrupos = "";
            foreach ($ParGrupos as $Grup) {
                $SelGrupos .= "<option value='$Grup->id' >" . strtoupper($Grup->descripcion) . "</option>";
            }

            return view('ZonaLibre.NuevaZonaLibre', compact('bandera', 'Tema', 'Asigna', 'Eval', 'SelGrupos','opc'));
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function guardarZonaLibre()
    {
        if (Auth::check()) {
            $this->validate(request(), [
                'grado' => 'required',
                'fecha' => 'required',
                'tip_contenido' => 'required',
            ], [
                'grado.required' => 'Debe Seleccionar El Grado',
                'fecha.required' => 'Debe Seleccionar la Fecha de Presentación',
                'tip_contenido.required' => 'Seleccione el tipo de contenido',
            ]);
            $datos = request()->all();
            $datos['ZL'] = 'SI';
            if ($datos['tip_contenido'] === "DOCUMENTO") {
                $Tem = \App\ZonaLibre::GuardarTipCont($datos);
                if ($Tem) {
                    $datos['tema_id'] = $Tem->id;

                    $ContTema = \App\DesarrollTema::GuardarContTema($datos);

                    if ($ContTema) {
                        return redirect('Asignaturas/ZonaLibre')->with('success', 'Datos Guardados');
                    } else {
                        return redirect('Asignaturas/ZonaLibre')->with('error', 'Datos no Guardados');
                    }
                } else {
                    return redirect('Asignaturas/ZonaLibre')->with('error', 'Datos no Guardados');
                }
            } else if ($datos['tip_contenido'] === "ANUNCIO") {
                $Tem = \App\ZonaLibre::GuardarTipCont($datos);
                if ($Tem) {
                    $datos['tema_id'] = $Tem->id;

                    $ContComent = \App\DesarroComentario::Guardar($datos);
                    if ($ContComent) {
                        return redirect('Asignaturas/ZonaLibre')->with('success', 'Datos Guardados');
                    } else {
                        return redirect('Asignaturas/ZonaLibre')->with('error', 'Datos no Guardados');
                    }
                } else {
                    return redirect('Asignaturas/ZonaLibre')->with('error', 'Datos no Guardados');
                }
            } else if ($datos['tip_contenido'] === "ARCHIVO") {

                $Tem = \App\ZonaLibre::GuardarTipCont($datos);

                if ($Tem) {
                    $datos['tema_id'] = $Tem->id;

                    if (request()->hasfile('archi')) {
                        foreach (request()->file('archi') as $file) {
                            $prefijo = substr(md5(uniqid(rand())), 0, 6);
                            $name = self::sanear_string($prefijo . '_' . $file->getClientOriginalName());
                            $file->move(public_path() . '/app-assets/Archivos_Contenidos/', $name);
                            $arch[] = $name;
                        }

                        $datos['archi'] = $arch;
                        $ContTemaArc = \App\SubirArcTema::GuardarArchCont($datos);
                    }

                    if ($ContTemaArc) {
                        return redirect('Asignaturas/ZonaLibre')->with('success', 'Datos Guardados');
                    } else {
                        return redirect('Asignaturas/ZonaLibre')->with('error', 'Datos no Guardados');
                    }
                } else {
                    return redirect('Asignaturas/ZonaLibre')->with('error', 'Datos no Guardados');
                }
            } else if ($datos['tip_contenido'] === "VIDEOS") {

                $Tem = \App\ZonaLibre::GuardarTipCont($datos);
                if ($Tem) {
                    $datos['tema_id'] = $Tem->id;

                    if ($datos['tip_video'] === "ARCHIVO") {
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
                            $ContTemaVideo = \App\ContDidactico::GuardarContDidctico($datos);
                        }
                    } else {
                        $ContTemaVideo = \App\DesarrolloLink::GuardarZN($datos);
                    }

                    if ($ContTemaVideo) {
                        return redirect('Asignaturas/ZonaLibre')->with('success', 'Datos Guardados');
                    } else {
                        return redirect('Asignaturas/ZonaLibre')->with('error', 'Datos no Guardados');
                    }
                } else {
                    return redirect('Asignaturas/ZonaLibre')->with('error', 'Datos no Guardados');
                }
            } else if ($datos['tip_contenido'] === "LINK") {
                $Tem = \App\ZonaLibre::GuardarTipCont($datos);
                if ($Tem) {
                    $datos['tema_id'] = $Tem->id;

                    $ContTemaLink = \App\DesarrolloLink::Guardar($datos);

                    if ($ContTemaLink) {
                        return redirect('Asignaturas/ZonaLibre')->with('success', 'Datos Guardados');
                    } else {
                        return redirect('Asignaturas/ZonaLibre')->with('error', 'Datos no Guardados');
                    }
                } else {
                    return redirect('Asignaturas/GestionTem')->with('error', 'Datos no Guardados');
                }
            }
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function editarTemaLibre($id)
    {
        $bandera = "Menu4";
        $opc = "editar";
        if (Auth::check()) {

            $Tema = \App\ZonaLibre::BuscarTema($id);
            $Asigna = \App\Modulos::listar();
            $Eval = new \App\Evaluacion();

            $Cole = \App\Colegios::InfColeg(Session::get('IdColegio'));
            $ParGrupos = \App\ParGrupos::LisGrupos($Cole->num_cursos, $Cole->cant_grupos);

            $SelGrupos = "";

            foreach ($ParGrupos as $ParGrup) {
                $SelGrupos .= "<option value='$ParGrup->id' ";
                if ($Tema->grupo == $ParGrup->id) {
                    $SelGrupos .= "selected";
                }
                $SelGrupos .= ">" . strtoupper($ParGrup->descripcion) . "</option>";
            }

            return view('ZonaLibre.EditarZona', compact('bandera', 'Tema', 'Asigna', 'opc', 'Eval', 'SelGrupos'));
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function InfDocumentosZonaLibre()
    {
        $IdTema = request()->get('IdTema');
        if (Auth::check()) {
            $DesTema = \App\DesarrollTema::BuscarTema($IdTema, 'SI');
            $content = $DesTema->cont_documento;
            if (request()->ajax()) {
                return response()->json([
                    'DesTema' => $DesTema,
                    'content' => $content,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function InfComentariosZonaLibre()
    {
        $IdTema = request()->get('IdTema');
        if (Auth::check()) {
            $DesTema = \App\DesarroComentario::BuscarTema($IdTema);
            $content = $DesTema->cont_comentario;
            if (request()->ajax()) {
                return response()->json([
                    'DesTema' => $DesTema,
                    'content' => $content,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function InfLinksZonaLibre()
    {
        $IdTema = request()->get('IdTema');

        if (Auth::check()) {
            $DatLink = \App\DesarrolloLink::DesLink($IdTema, 'SI');
            if (request()->ajax()) {
                return response()->json([
                    'Links' => $DatLink,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function InfVideoZonaLibre()
    {
        if (Auth::check()) {
            $IdTema = request()->get('IdTema');
            $Tema = \App\ZonaLibre::BuscarTema($IdTema);
            if ($Tema->tip_video === "ARCHIVO") {
                $ContTemaVideo = \App\ContDidactico::BuscarTema($IdTema, 'SI');
            } else {
                $ContTemaVideo = \App\DesarrolloLink::DesLink($IdTema, 'SI');
            }

            if (request()->ajax()) {
                return response()->json([
                    'TemaVideo' => $ContTemaVideo,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function EliminarTemaZona()
    {
        $mensaje = "";
        $id = request()->get('id');
        if (Auth::check()) {
            $Tema = \App\ZonaLibre::BuscarTema($id);

            $estado = "ACTIVO";
            if ($Tema->estado == "ACTIVO") {
                $estado = "ELIMINADO";
            } else {
                $estado = "ACTIVO";
            }
            $respuesta = \App\ZonaLibre::editarestado($id, $estado);

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

    
    public function DelArchivosVideo()
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


    public function ModificarZonaLibre($id)
    {
        if (Auth::check()) {
            $this->validate(request(), [
                'grado' => 'required',
                'fecha' => 'required',
                'tip_contenido' => 'required',
            ], [
                'grado.required' => 'Debe Seleccionar el Grado',
                'fecha.required' => 'Debe Seleccionar la Fecha de Presentación',
                'tip_contenido.required' => 'Seleccione el tipo de contenido',
            ]);
            $datos = request()->all();
            $datos['ZL'] = 'SI';
            $datos['tema_id'] = $id;
            if ($datos['tip_contenido'] === "DOCUMENTO") {
                $Tem = \App\ZonaLibre::modificar($datos, $id);
                if ($Tem) {
                    $ContTema = \App\DesarrollTema::modificar($datos, $id);

                    if ($ContTema) {
                        return redirect('Asignaturas/ZonaLibre')->with('success', 'Datos Guardados');
                    } else {
                        return redirect('Asignaturas/ZonaLibre')->with('error', 'Datos no Guardados');
                    }
                } else {
                    return redirect('Asignaturas/ZonaLibre')->with('error', 'Datos no Guardados');
                }
            } else if ($datos['tip_contenido'] === "ANUNCIO") {
                $Tem = \App\ZonaLibre::modificar($datos, $id);
                if ($Tem) {
                    $ContComent = \App\DesarroComentario::modificar($datos, $id);
                    if ($ContComent) {
                        return redirect('Asignaturas/ZonaLibre')->with('success', 'Datos Guardados');
                    } else {
                        return redirect('Asignaturas/ZonaLibre')->with('error', 'Datos no Guardados');
                    }
                } else {
                    return redirect('Asignaturas/ZonaLibre')->with('error', 'Datos no Guardados');
                }
            } else if ($datos['tip_contenido'] === "ARCHIVO") {
                $Tem = \App\ZonaLibre::modificar($datos, $id);
                if ($Tem) {
                    if (request()->hasfile('archi')) {
                        foreach (request()->file('archi') as $file) {
                            $prefijo = substr(md5(uniqid(rand())), 0, 6);
                            $name = self::sanear_string($prefijo . '_' . $file->getClientOriginalName());
                            $file->move(public_path() . '/app-assets/Archivos_Contenidos/', $name);
                            $arch[] = $name;
                        }

                        $datos['archi'] = $arch;
                        $ContTemaArc = \App\SubirArcTema::GuardarArchCont($datos);
                    }

                    if ($Tem) {
                        return redirect('Asignaturas/ZonaLibre')->with('success', 'Datos Guardados');
                    } else {
                        return redirect('Asignaturas/ZonaLibre')->with('error', 'Datos no Guardados');
                    }
                } else {
                    return redirect('Asignaturas/ZonaLibre')->with('error', 'Datos no Guardados');
                }
            } else if ($datos['tip_contenido'] === "VIDEOS") {

                $Tem = \App\ZonaLibre::modificar($datos, $id);
                if ($Tem) {
                    if ($datos['tip_video'] === "ARCHIVO") {
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
                            $ContTemaVideo = \App\ContDidactico::GuardarContDidctico($datos);
                        }
                    } else {

                        $ContTemaVideo = \App\DesarrolloLink::GuardarZN($datos);
                    }
                    if ($Tem) {
                        return redirect('Asignaturas/ZonaLibre')->with('success', 'Datos Guardados');
                    } else {
                        return redirect('Asignaturas/ZonaLibre')->with('error', 'Datos no Guardados');
                    }
                } else {
                    return redirect('Asignaturas/ZonaLibre')->with('error', 'Datos no Guardados');
                }
            } else if ($datos['tip_contenido'] === "LINK") {
                $Tem = \App\ZonaLibre::modificar($datos, $id);
                if ($Tem) {
                    $ContTemaLink = \App\DesarrolloLink::Modificar($datos, $id);
                    if ($ContTemaLink) {
                        return redirect('Asignaturas/ZonaLibre')->with('success', 'Datos Guardados');
                    } else {
                        return redirect('Asignaturas/ZonaLibre')->with('error', 'Datos no Guardados');
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
