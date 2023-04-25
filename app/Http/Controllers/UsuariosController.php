<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class UsuariosController extends Controller
{

    public function Inicio()
    {
        return view('Usuario.Login');
    }
    public function Juego1()
    {
        return view('ModuloJ.Juego1');
    }


    public function Gestion()
    {
        $bandera = "Menu4";
        if (Auth::check()) {
            $busqueda = request()->get('txtbusqueda');
            $actual = request()->get('page');
            if ($actual == null || $actual == "") {
                $actual = 1;
            }
            $limit = 10;
            $Usuarios = \App\Usuarios::Gestion($busqueda, $actual, $limit);
            $numero_filas = \App\Usuarios::numero_de_registros(request()->get('txtbusqueda'));
            $paginas = ceil($numero_filas / $limit); //$numero_filas/10;
           
            return view('Usuario.Gestion', compact('bandera', 'numero_filas', 'paginas', 'actual', 'limit', 'busqueda', 'Usuarios'));
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function RegistrosUsuarios()
    {
        $bandera = "Menu4";
        if (Auth::check()) {

            $fechaIni = request()->get('fechaIni');
            $fechaFin = request()->get('fechaFin');
            $Usuario = request()->get('Usuario');

            $actual = request()->get('page');
            if ($actual == null || $actual == "") {
                $actual = 1;
            }

            if (empty($fechaIni)) {
                $fechaIni = date('Y-m-d');
            }

            if (empty($fechaFin)) {
                $fechaFin = date('Y-m-d');
            }

            $limit = 10;

            $RegistUsu = \App\Log::Gestion($fechaIni, $fechaFin, $actual, $limit, $Usuario);

            $numero_filas = \App\Log::numero_de_registros($fechaIni, $fechaFin, $Usuario);

            $Usuarios = \App\Usuarios::todos();

            $select_Usu = "<option value='' selected>Todos Los Usuarios</option>";
            foreach ($Usuarios as $Usu) {
                if ($Usu->id == $Usuario) {
                    $select_Usu .= "<option value='$Usu->id' selected> " . strtoupper($Usu->nombre_usuario) . "</option>";
                } else {
                    $select_Usu .= "<option value='$Usu->id' >" . strtoupper($Usu->nombre_usuario) . "</option>";
                }
            }
            $paginas = ceil($numero_filas / $limit); //$numero_filas/10;

            return view('Usuario.GestionRegistrosUsuarios', compact('bandera', 'numero_filas', 'paginas', 'actual', 'limit', 'Usuario', 'RegistUsu', 'select_Usu', 'fechaIni', 'fechaFin'));
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function Administracion()
    {
        if (Auth::check()) {
            $bandera = "";
            if (Auth::user()->tipo_usuario == "Administrador") {
                $Asignatura = \App\Asignaturas::AsigxUsu(Auth::user()->id, Auth::user()->tipo_usuario, '', '');
                $imgasig = \App\ImgAsignatura::imgAsig();
                Session::put('IDMODULO', '');
                ////////CONSULTAR MÓDULOS TRANSVERSALES
                $Modulos = \App\ModulosTransversales::AsigxUsu(Auth::user()->id, Auth::user()->tipo_usuario, '', '');
                $imgmodulo = \App\ImgModulosTransversales::imgAsig();

                ///MODULOS TRANSVERSALES
            } else if (Auth::user()->tipo_usuario == "Profesor") {
                $Asignatura = \App\Asignaturas::AsigxUsu(Auth::user()->id, Auth::user()->tipo_usuario, '', '');
                Session::put('IDMODULO', '');
                $imgasig = \App\ImgAsignatura::imgAsig();

                $Modulos = \App\ModulosTransversales::AsigxUsu(Auth::user()->id, Auth::user()->tipo_usuario, '', '');
                $imgmodulo = \App\ImgModulosTransversales::imgAsig();

                $InfDoce = \App\Profesores::Buscar(Auth::user()->id);
                Session::put('JORDOCE', $InfDoce->jornada);
                Session::put('IDDOCE', $InfDoce->id);
            } else if (Auth::user()->tipo_usuario == "Estudiante") {
                ////////INF. ASIGANTURAS
                $Alum = \App\Alumnos::Buscar(Auth::user()->id);
                $Asignatura = \App\Asignaturas::AsigxUsu(Auth::user()->grado_usuario, Auth::user()->tipo_usuario, $Alum->grupo, $Alum->jornada);

                Session::put('IDMODULO', '');
                $imgasig = \App\ImgModulos::imgmodulo();

                foreach ($Asignatura as $Asig) {
                    $IdModu = $Asig->id;
                    $Grupos = \App\Grupos::BusIdGrup($IdModu, $Alum->grupo);

                    $infDoc = \App\AsigProf::BuscAsigAsing($IdModu, $Grupos->id, $Alum->jornada);

                    if ($infDoc) {
                        $Temas = \App\Temas::LisTemasProgEst($IdModu, $infDoc->usuario_profesor);
                    } else {
                        $Temas = "0";
                    }

                    $Asig['avance_modulo'] = $Temas;
                }

                ////////INF. MODULOS
                //   $Alum = \App\Alumnos::Buscar(Auth::user()->id);
                $Modulos = \App\ModulosTransversales::AsigxUsu(Auth::user()->grado_usuario, Auth::user()->tipo_usuario, $Alum->grupo, $Alum->jornada);
                Session::put('IDMODULO', '');
                $imgmodulo = \App\ImgGradosModulosTransv::imgmodulo();

                foreach ($Modulos as $Asig) {
                    $IdModu = $Asig->id;

                    $Grupos = \App\GruposModTransv::BusIdGrup($IdModu, $Alum->grupo);
                    $infDoc = \App\ModProf::BuscAsigAsing($IdModu, $Grupos->id, $Alum->jornada);
                    if ($infDoc) {
                        $Temas = \App\TemasModulos::LisTemasProgEst($IdModu, $infDoc->usuario_profesor);
                    } else {
                        $Temas = "0";
                    }

                    $Asig['avance_modulo'] = $Temas;
                }

            }

            Session::put('ZonaJuegoAct', 'no');

            $Log = \App\Log::Guardar('Inicio sesión', '');
            if (Auth::user()->tipo_usuario == "root") {

                $Asignatura = \App\Asignaturas::listarPar();
                $Modulos = \App\ModulosTransversales::listarPar();
                $Colegios = \App\Colegios::Colegios();

                return view('AdministrarParametros', compact('Asignatura', 'Modulos', 'Colegios'));
            } else {
                return view('Administrador', compact('bandera', 'Asignatura', 'imgasig', 'Modulos', 'imgmodulo'));

            }

        } else {
            return redirect('/')->with('error', 'Usuario y Contraseña incorrecta');
        }
    }

    public function GuardarParametros()
    {
        if (Auth::check()) {
            $data = request()->all();
            $Asignatura = \App\Asignaturas::HabiAsig($data);
            $Modulos = \App\ModulosTransversales::HabiAsig($data);
            $Permiso = \App\ConsUrl::UpdatePara($data);

            if (request()->ajax()) {
                return response()->json([
                    'respu' => 'OK',
                ]);
            }

        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function CargarParametros()
    {
        if (Auth::check()) {
            $Permiso = \App\ConsUrl::Consultar();
            $Asignatura = \App\Asignaturas::listarPar();
            $Modulos = \App\ModulosTransversales::listarPar();

            if (request()->ajax()) {
                return response()->json([
                    'Permiso' => $Permiso,
                    'Asignatura' => $Asignatura,
                    'Modulos' => $Modulos,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function Login()
    {
//        dd(request()->all());die;
        $respuesta = \App\Usuarios::login(request()->all());

        Session::put('PanelActivos', '');
        Session::put('PanelNotifica', '');
        Session::put('PanelNotifica', '');
        Session::put('TIPCONT', 'ASI');

        if ($respuesta) {
            if ($respuesta->tipo_usuario == "Profesor") {
                $FotoUsu = \App\Profesores::Buscar($respuesta->id);
                Session::put('ImgUsu', 'Img_Docentes/' . $FotoUsu->foto);
            } else if ($respuesta->tipo_usuario == "Estudiante") {
                $FotoUsu = \App\Alumnos::Buscar($respuesta->id);
                Session::put('ImgUsu', 'Img_Estudiantes/' . $FotoUsu->foto_alumno);
                Session::put('GrupoEst',  $FotoUsu->grupo);
            } else if ($respuesta->tipo_usuario == "root") {
                Session::put('ImgUsu', 'avatar-s-1.png');
            } else {
                Session::put('ImgUsu', 'avatar-s-1.png');
            }

            $Permiso = \App\ConsUrl::ConsulPar('PED');

            $colegios = \App\Colegios::InfColeg($Permiso->colegio);
            Session::put('IdColegio', $Permiso->colegio);
            Session::put('NombreColegio', $colegios->nombre);
            Session::put('EscudoColegio', $colegios->escudo);
            Session::put('UbicacionColegio', $colegios->ubicacion);

            Session::put('PerModE', $Permiso->mod_entre);
            Session::put('PerModJ', $Permiso->mod_juego);
            Session::put('PerLabo', $Permiso->mod_labo);
            Session::put('PerZonL', $Permiso->mod_zona);
            Session::put('PerAsig', $Permiso->mod_asig);
            Session::put('PerModu', $Permiso->mod_modu);

            $Sesiones = \App\sesiones::Guardar(Auth::user()->id);

            return redirect('/Administracion');

        } else {
            $error = "Usuario ó Contraseña Inconrrecta";
            return redirect('/')->with('error', $error);
        }
    }

    public function logout()
    {

        Auth::logout();
        return redirect('/')->with('success', 'Sesión Finalizada');
    }

    public function InfGeneralColeg()
    {
        if (Auth::check()) {
            $IdColeg = request()->get('idColegio');
            $Colegio = \App\Colegios::InfColeg($IdColeg);

            if (request()->ajax()) {
                return response()->json([
                    'nombre' => $Colegio->nombre,
                    'cant_grupos' => $Colegio->cant_grupos,
                    'escudo' => $Colegio->escudo,
                    'habpasw' => $Colegio->habpasw,
                ]);
            }

        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function ActualizarInformacionColeg()
    {
        if (Auth::check()) {
            $data = request()->all();
            $name = "";

            if (request()->hasfile('imagen')) {

                $prefijo = substr(md5(uniqid(rand())), 0, 6);
                $name = $prefijo . '_' . request()->file('imagen')->getClientOriginalName();

                request()->file('imagen')->move(public_path() . '/app-assets/images/Colegios/', $name);

                $data['escudo'] = $name;

            } else {
                $data['escudo'] = $data['escudocoleg'];
            }

            $Colegios = \App\Colegios::UpdateColegios($data);

            Session::put('NombreColegio', $data['nombre']);
            Session::put('EscudoColegio', $data['escudo']);

            if (request()->ajax()) {
                return response()->json([
                    'Resp' => 'Ok',
                    'escudo' => $data['escudo'],
                ]);
            }

        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function perfil()
    {
        if (Auth::check()) {
            if (Auth::user()->tipo_usuario == "Profesor") {
                $Profesores = \App\Profesores::Buscar(Auth::user()->id);
                return view('Usuario.Perfil', compact('Profesores'));
            } else if (Auth::user()->tipo_usuario == "Estudiante") {
                $Alumno = \App\Alumnos::Buscar(Auth::user()->id);
                return view('Usuario.Perfil', compact('Alumno'));
            } else {
//            $Alumno = \App\usu::Buscar(Auth::user()->id);
                return redirect('/Administracion');
            }
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function EstadisticaAdmin()
    {
        if (Auth::check()) {
            $Estudiantes = \App\Alumnos::TotAlumnos();
            $Nest = $Estudiantes->count();
            $Asignaturas = \App\Asignaturas::listar();
            $Nasig = $Asignaturas->count();
            $Profesores = \App\Profesores::Listar();
            $Nprof = $Profesores->count();
            $Temas = \App\Temas::Listar();
            $Ntema = $Temas->count();
            $Asignaturas = \App\Modulos::listar();
            $select_Asig = "<option value='' selected>TODAS LAS ASIGNATURAS</option>";
            foreach ($Asignaturas as $Asig) {

                $select_Asig .= "<option value='$Asig->grado_modulo' >" . strtoupper($Asig->nombre) . " - Grado " . $Asig->grado_modulo . "°</option>";
            }
            return view('Gestion.DatosEstadisticos', compact('Nest', 'Nasig', 'Nprof', 'Ntema', 'select_Asig'));
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function editarperfil()
    {
        if (Auth::check()) {
            $data = request()->all();
            $id = $data['id'];
            if (Auth::user()->tipo_usuario == "Profesor") {
                $Profesores = \App\Profesores::BuscarProf($id);
                if($Profesores->identificacion==$data['identificacion']){
                    $this->validate(request(), [
                        'identificacion' => 'required',
                        'nombre' => 'required',
                        'apellido' => 'required',
                    ], [
                        'identificacion.required' => 'El Número de Identificación es Obligatorio',
                        'nombre.required' => 'El Nombre es obligatorio',
                        'apellido.required' => 'El Apellido es Obligatorio',
                    ]);
                }else{
                    $this->validate(request(), [
                        'identificacion' => 'required|unique:profesores,identificacion,' . $Profesores->id,
                        'nombre' => 'required',
                        'apellido' => 'required',
                    ], [
                        'identificacion.required' => 'El Número de Identificación es Obligatorio',
                        'identificacion.unique' => 'El Número de Identificación ya Existe',
                        'nombre.required' => 'El Nombre es obligatorio',
                        'apellido.required' => 'El Apellido es Obligatorio',
                    ]);
                }

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

                $usuario = \App\Usuarios::GuardarUsuProfPer($data, Auth::user()->id);
                if ($usuario) {
                    $respuesta = \App\Profesores::modificarPerfil($data, $id);
                    return redirect('/perfil')->with('success', 'Datos Guardados');
                } else {
                    return redirect('/perfil')->with('error', 'Datos no Guardados');
                }
            } else {
                $Alumnos = \App\Alumnos::BuscarAlum($id);
                if($Alumnos->ident_alumno==$data['ident_alumno']){
                    $this->validate(request(), [
                        'ident_alumno' => 'required',
                        'nombre_alumno' => 'required',
                        'apellido_alumno' => 'required',
                    ], [
                        'ident_alumno.required' => 'El Número de Identificación es Obligatorio',
                        'nombre_alumno.required' => 'El Nombre es obligatorio',
                        'apellido_alumno.required' => 'El Apellido es Obligatorio',
                    ]);

                }else{
                    $this->validate(request(), [
                        'ident_alumno' => 'required|unique:alumnos,ident_alumno,' . $Alumnos->id,
                        'nombre_alumno' => 'required',
                        'apellido_alumno' => 'required',
                    ], [
                        'ident_alumno.required' => 'El Número de Identificación es Obligatorio',
                        'nombre_alumno.required' => 'El Nombre es obligatorio',
                        'apellido_alumno.required' => 'El Apellido es Obligatorio',
                    ]);

                }

                $usuario = \App\Usuarios::GuardarUsuAluPer($data, Auth::user()->id);
                if (request()->hasfile('imagen')) {
                    foreach (request()->file('imagen') as $file) {
                        $name = rand(1, 100) . '_' . $file->getClientOriginalName();
                        $file->move(public_path() . '/app-assets/images/Img_Estudiantes/', $name);
                        $img = $name;
                    }
                } else {
                    $img = $data['fotoalumno'];
                }
                $data['img'] = $img;
                if ($usuario) {
                    $respuesta = \App\Alumnos::modificarPerf($data, $id);
                    return redirect('/perfil')->with('success', 'Datos Guardados');
                } else {
                    return redirect('/perfil')->with('error', 'Datos no Guardados');
                }
            }
        } else {
            return redirect("/")->with("error", "Su sesion ha terminado");
        }
    }

    public function editar($id)
    {
        $bandera = "Menu4";
        $opc = "editar";
        $Grupos = "";
        if (Auth::check()) {
            $Usuarios = \App\Usuarios::buscar($id);

            return view('Usuario.Editar', compact('bandera', 'Usuarios', 'opc'));
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function ValUsuario()
    {
        $exit = "no";
        $Usuario = \App\Usuarios::BuscarUsu(request()->get('Usu'));

        if ($Usuario) {
            $exit = "si";
        }

        if (request()->ajax()) {
            return response()->json([
                'exit' => $exit,
            ]);
        }
    }

    public function modificar($id)
    {
        if (Auth::check()) {
            $data = request()->all();
            $Usuarios = \App\Usuarios::buscar($id);

            if ($Usuarios->login_usuario == $data["login_usuario"]) {

                if ($data["cambi_passw"] == "SI") {
                    $this->validate(request(), [
                        'nombre_usuario' => 'required',
                        'tipo_usuario' => 'required',
                        'estado_usuario' => 'required',
                        'password' => 'required|confirmed',
                        'password_confirmation' => 'required',

                    ], [
                        'nombre_usuario.required' => 'El Nombre de Usuario es Obligatorio',
                        'tipo_usuario.unique' => 'Seleccione el Tipo de Usuario',
                        'login_usuario.required' => 'El Usuario es Obligatorio',
                        'estado_usuario.required' => 'Seleccione el Estado del Usuario',
                        'password_confirmation.required' => 'El Campo Repetir Contraseña es Obligatorio',
                        'password.confirmed' => 'Las contraseña no Coinciden',
                        'usuario_alumno.unique' => 'El Usuario ya Existe',
                    ]);
                } else {
                    $this->validate(request(), [
                        'nombre_usuario' => 'required',
                        'tipo_usuario' => 'required',
                        'estado_usuario' => 'required',
                    ], [
                        'nombre_usuario.required' => 'El Nombre de Usuario es Obligatorio',
                        'tipo_usuario.unique' => 'Seleccione el Tipo de Usuario',
                        'login_usuario.required' => 'El Usuario es Obligatorio',
                        'estado_usuario.required' => 'Seleccione el Estado del Usuario',
                        'usuario_alumno.unique' => 'El Usuario ya Existe',
                    ]);
                }
            } else {

                if ($data["cambi_passw"] == "SI") {
                    $this->validate(request(), [
                        'nombre_usuario' => 'required',
                        'tipo_usuario' => 'required',
                        'estado_usuario' => 'required',
                        'password' => 'required|confirmed',
                        'password_confirmation' => 'required',
                        'login_usuario' => 'required|unique:users,login_usuario,' . $Usuarios->login_usuario,
                    ], [
                        'nombre_usuario.required' => 'El Nombre de Usuario es Obligatorio',
                        'tipo_usuario.unique' => 'Seleccione el Tipo de Usuario',
                        'login_usuario.required' => 'El Usuario es Obligatorio',
                        'estado_usuario.required' => 'Seleccione el Estado del Usuario',
                        'password_confirmation.required' => 'El Campo Repetir Contraseña es Obligatorio',
                        'password.confirmed' => 'Las contraseña no Coinciden',
                        'login_usuario.unique' => 'El Usuario ya Existe',
                    ]);
                } else {
                    $this->validate(request(), [
                        'nombre_usuario' => 'required',
                        'tipo_usuario' => 'required',
                        'estado_usuario' => 'required',
                        'login_usuario' => 'required|unique:users,login_usuario,' . $Usuarios->login_usuario,
                    ], [
                        'nombre_usuario.required' => 'El Nombre de Usuario es Obligatorio',
                        'tipo_usuario.unique' => 'Seleccione el Tipo de Usuario',
                        'login_usuario.required' => 'El Usuario es Obligatorio',
                        'estado_usuario.required' => 'Seleccione el Estado del Usuario',
                        'login_usuario.unique' => 'El Usuario ya Existe',
                    ]);
                }
            }

            $respuesta = \App\Usuarios::modificar($data, $id);
            if ($respuesta) {
                return redirect('/Usuarios/Gestion')->with('success', 'Datos Guardados');
            } else {
                return redirect('/Usuarios/Gestion')->with('error', 'Datos no Guardados');
            }
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function chat()
    {
        if (Auth::check()) {

            if (Session::get('TIPCONT') == 'ASI') {
                $respuesta = \App\Usuarios::usuariosChat();
            } else {
                $respuesta = \App\Usuarios::usuariosChatMod();
            }

            $alumnos = $respuesta['alumnos'];

            foreach ($alumnos as $key => $val) {
                if ($val->id != Auth::user()->id) {
                    if (Session::get('TIPCONT') == 'ASI') {
                        $ultimacon = \App\Mensajes::ultimacon($val->id);
                    } else {
                        $ultimacon = \App\MensajesModulosChat::ultimacon($val->id);
                    }
                    // dd($ultimacon);die;
                    if ($ultimacon) {
                        $ultimacon->last();
                        foreach ($ultimacon as $ult) {
                            $alumnos[$key]->FECHA = $ult->FECHA;
                            $alumnos[$key]->MENSAJE = $ult->MENSAJE;
                        }
                    } else {
                        $alumnos[$key]->FECHA = "";
                        $alumnos[$key]->MENSAJE = "";
                    }
                }
            }
            $alumnos = $alumnos->sortBy('FECHA')->reverse();
            // dd($alumnos);die;
            $profesores = $respuesta['profesores'];
            foreach ($profesores as $key => $val) {
                if ($val->id != Auth::user()->id) {
                    if (Session::get('TIPCONT') == 'ASI') {
                        $ultimacon = \App\Mensajes::ultimacon($val->id);
                    } else {
                        $ultimacon = \App\MensajesModulosChat::ultimacon($val->id);
                    }
                    if ($ultimacon) {
                        $ultimacon->last();
                        foreach ($ultimacon as $ult) {
                            $profesores[$key]->FECHA = $ult->FECHA;
                            $profesores[$key]->MENSAJE = $ult->MENSAJE;
                        }
                    } else {
                        $profesores[$key]->FECHA = "";
                        $profesores[$key]->MENSAJE = "";
                    }
                }
            }

            $profesores = $profesores->sortBy('FECHA')->reverse();
            return view('Usuario.Chat', compact('alumnos', 'profesores'));
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function guardarchat()
    {
        if (Auth::check()) {
            $data = request()->all();
            if (Session::get('TIPCONT') == 'ASI') {
                $respuesta = \App\Mensajes::guardar($data);

            } else {
                $respuesta = \App\MensajesModulosChat::guardar($data);

            }
            if ($respuesta) {
                $mensaje = "GUARDO";
            } else {
                $mensaje = "NO GUARDO";
            }
            $usuarios = \App\Usuarios::buscar(Auth::user()->id);
            if (request()->ajax()) {
                return response()->json([
                    'mensaje' => $mensaje,
                    'usuario' => $usuarios->nombre_usuario,
                    'id_usuario' => $usuarios->id,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function EstadisticaGeneral()
    {
        if (Auth::check()) {
            $data = request()->all();

            $ExtrEdad = 0;
            $ExtrEdad1 = 0;
            $ExtrEdad2 = 0;
            $ExtrEdad3 = 0;
            $ExtrEdad4 = 0;
            $ExtrEdad5 = 0;
            $ExtrEdad6 = 0;
            $ExtrEdad7 = 0;
            $ExtrEdad8 = 0;
            $ExtrEdad9 = 0;

            $AlumSex = \App\Alumnos::ConsulSex($data);
            $AlumGrad = \App\Alumnos::ConsulGrado($data);
            $AlumEtar = \App\Alumnos::ConsulGrupEta($data);
            $ExtraEdad = \App\Alumnos::ConsulExtraEd($data);
            $NAlum = $ExtraEdad->count();
            foreach ($ExtraEdad as $ExEda) {

                if ($ExEda->grado_alumno == 1 && $ExEda->edad >= 9) {
                    $ExtrEdad++;
                    $ExtrEdad1++;
                }
                if ($ExEda->grado_alumno == 2 && $ExEda->edad >= 10) {
                    $ExtrEdad++;
                    $ExtrEdad2++;
                }
                if ($ExEda->grado_alumno == 3 && $ExEda->edad >= 11) {
                    $ExtrEdad++;
                    $ExtrEdad3++;
                }
                if ($ExEda->grado_alumno == 4 && $ExEda->edad >= 12) {
                    $ExtrEdad++;
                    $ExtrEdad4++;
                }
                if ($ExEda->grado_alumno == 5 && $ExEda->edad >= 13) {
                    $ExtrEdad++;
                    $ExtrEdad5++;
                }
                if ($ExEda->grado_alumno == 6 && $ExEda->edad >= 14) {
                    $ExtrEdad++;
                    $ExtrEdad6++;
                }
                if ($ExEda->grado_alumno == 7 && $ExEda->edad >= 15) {
                    $ExtrEdad++;
                    $ExtrEdad7++;
                }
                if ($ExEda->grado_alumno == 8 && $ExEda->edad >= 16) {
                    $ExtrEdad++;
                    $ExtrEdad8++;
                }
                if ($ExEda->grado_alumno == 9 && $ExEda->edad >= 17) {
                    $ExtrEdad++;
                    $ExtrEdad9++;
                }
            }

            if (request()->ajax()) {
                return response()->json([
                    'AlumSex' => $AlumSex,
                    'AlumGrad' => $AlumGrad,
                    'AlumEtar' => $AlumEtar,
                    'NAlum' => $NAlum,
                    'ExtrEdad' => $ExtrEdad,
                    'ExtrEdad1' => $ExtrEdad1,
                    'ExtrEdad2' => $ExtrEdad2,
                    'ExtrEdad3' => $ExtrEdad3,
                    'ExtrEdad4' => $ExtrEdad4,
                    'ExtrEdad5' => $ExtrEdad5,
                    'ExtrEdad6' => $ExtrEdad6,
                    'ExtrEdad7' => $ExtrEdad7,
                    'ExtrEdad8' => $ExtrEdad8,
                    'ExtrEdad9' => $ExtrEdad9,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function cambiarclave()
    {
        return view('Usuario.CambiarClave');
    }

    public function ConfParametros()
    {
        if (Auth::check()) {

        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }

        return view('ConfParametros.GestionParametros');
    }

    public function camcla()
    {
        if (Auth::check()) {

            $this->validate(request(), [
                'passact' => 'required',
                'password' => 'required|confirmed',
                'password_confirmation' => 'required',
            ], [
                'passact.required' => 'La clave actual es obligatoria',
                'password.required' => 'La nueva clave es obligatoria',
                'password.confirmed' => 'Las claves no coinciden',
            ]);
            // dd(request()->all());die;
            $data = request()->all();
            $respuesta = \App\Usuarios::cambiarclave($data);
            if ($respuesta) {
                Auth::logout();
                return redirect('/')->with('success', 'Clave cambiada de manera exitosa');
            } else {
                return redirect('/cambiarclave')->with('error', 'Clave actual incorrecta');
            }
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function cargar()
    {
        if (Auth::check()) {
            $data = request()->all();
            if (Session::get('TIPCONT') == 'ASI') {
                $mensajes = \App\Mensajes::listar($data);
            } else {
                $mensajes = \App\MensajesModulosChat::listar($data);
            }
            foreach ($mensajes as $men) {
                if ($men->TIPUSU == "Profesor") {
                    $Prof = \App\Profesores::Buscar($men->IDUSU);
                    $men['foto'] = 'Img_Docentes/' . $Prof->foto;
                } else {
                    $Alumno = \App\Alumnos::Buscar($men->IDUSU);
                    $men['foto'] = 'Img_Estudiantes/' . $Alumno->foto_alumno;
                }
            }

            if (request()->ajax()) {
                return response()->json([
                    'mensajes' => $mensajes,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function cargarUsuarios()
    {
        if (Auth::check()) {
            $data = request()->all();
            if (Session::get('TIPCONT') == 'ASI') {
                $respuesta = \App\Usuarios::usuariosChat();
            } else {
                $respuesta = \App\Usuarios::usuariosChatMod();
            }

            $alumnos = $respuesta['alumnos'];
            foreach ($alumnos as $key => $val) {
                if ($val->id != Auth::user()->id) {

                    if (Session::get('TIPCONT') == 'ASI') {
                        $ultimacon = \App\Mensajes::ultimacon($val->id);

                    } else {
                        $ultimacon = \App\MensajesModulosChat::ultimacon($val->id);
                    }

                    if ($ultimacon) {
                        $ultimacon->last();
                        foreach ($ultimacon as $ult) {
                            $alumnos[$key]->FECHA = $ult->FECHA;
                            $alumnos[$key]->MENSAJE = $ult->MENSAJE;
                        }
                    } else {
                        $alumnos[$key]->FECHA = "";
                        $alumnos[$key]->MENSAJE = "";
                    }
                } else {
                    $alumnos[$key]->FECHA = "";
                    $alumnos[$key]->MENSAJE = "";
                }
            }
            $alumnos = $alumnos->sortBy('FECHA')->reverse();
            $profesores = $respuesta['profesores'];
            foreach ($profesores as $key => $val) {
                if ($val->id != Auth::user()->id) {
                    if (Session::get('TIPCONT') == 'ASI') {
                        $ultimacon = \App\Mensajes::ultimacon($val->id);
                    } else {
                        $ultimacon = \App\MensajesModulosChat::ultimacon($val->id);
                    }
                    if ($ultimacon) {
                        $ultimacon->last();
                        foreach ($ultimacon as $ult) {
                            $profesores[$key]->FECHA = $ult->FECHA;
                            $profesores[$key]->MENSAJE = $ult->MENSAJE;
                        }
                    } else {
                        $profesores[$key]->FECHA = "";
                        $profesores[$key]->MENSAJE = "";
                    }
                } else {
                    $profesores[$key]->FECHA = "";
                    $profesores[$key]->MENSAJE = "";
                }
            }
            $profesores = $profesores->sortBy('FECHA')->reverse();

            $ladoAlumnos = "<p class='text-center' style='padding-top: 10px;font-weight: bold;font-size:15px;'>Estudiantes</p>";
            foreach ($alumnos as $alu) {
                $rutaFoto = asset('app-assets/images/Img_Estudiantes/' . $alu->foto_alumno);
                if (Auth::user()->id != $alu->id) {
                    $ladoAlumnos .= "<a href='#' class=' media border-0 btnUsuario' data-id='$alu->id' data-nomusuario='$alu->nombre_usuario'>";
                    $ladoAlumnos .= "<div class='media-left pr-1'>";
                    $ladoAlumnos .= "<span class='avatar avatar-md avatar-online'>";
                    $ladoAlumnos .= "<img class='media-object rounded-circle' src='" . $rutaFoto . "' alt='image'>";
                    $ladoAlumnos .= "</span>";
                    $ladoAlumnos .= "</div>";

                    $ladoAlumnos .= "<div class='media-body w-100' style='margin-top:10px;'>";
                    $ladoAlumnos .= "<h6 class='list-group-item-heading' style='font-size:10px;font-weight: bold;text-transform: capitalize;'> ";
                    $ladoAlumnos .= "<span style='text-transform: capitalize;'>" . $alu->nombre_usuario . "</span>";
                    $ladoAlumnos .= "<p class='list-group-item-text text-muted mb-0'><i class='ft-check primary font-small-2'></i>";
                    $ladoAlumnos .= str_limit($alu->MENSAJE, 15, '...');
                    $ladoAlumnos .= "<span class='font-small-3 float-right primary'>" . $alu->FECHA . "</span>";
                    $ladoAlumnos .= "</p>";
                    $ladoAlumnos .= "</h6>";
                    $ladoAlumnos .= "</div>";
                    $ladoAlumnos .= "</a>";
                }
            }

            $ladoProfesores = "<p class='text-center' style='padding-top: 10px;font-weight: bold;font-size:15px;'>Profesores</p>";
            foreach ($profesores as $alu) {
                $rutaFoto = asset('app-assets/images/Img_Docentes/' . $alu->foto);
                if (Auth::user()->id != $alu->id) {
                    $ladoProfesores .= "<a href='#' class=' media border-0 btnUsuarioPro' data-id='$alu->id' data-nomusuario='$alu->nombre_usuario'>";
                    $ladoProfesores .= "<div class='media-left pr-1'>";
                    $ladoProfesores .= "<span class='avatar avatar-md avatar-online'>";
                    $ladoProfesores .= "<img class='media-object rounded-circle' src='" . $rutaFoto . "' alt='image'>";
                    $ladoProfesores .= "</span>";
                    $ladoProfesores .= "</div>";

                    $ladoProfesores .= "<div class='media-body w-100' style='margin-top:10px;'>";
                    $ladoProfesores .= "<h6 class='list-group-item-heading' style='font-size:10px;font-weight: bold;'> ";
                    $ladoProfesores .= "<span style='text-transform: capitalize;'>" . $alu->nombre_usuario . "</span>";
                    $ladoProfesores .= "<p class='list-group-item-text text-muted mb-0'><i class='ft-check primary font-small-2'></i>";
                    $ladoProfesores .= str_limit($alu->MENSAJE, 15, '...');
                    $ladoProfesores .= "<span class='font-small-3 float-right primary'>" . $alu->FECHA . "</span>";
                    $ladoProfesores .= "</p>";
                    $ladoProfesores .= "</h6>";
                    $ladoProfesores .= "</div>";
                    $ladoProfesores .= "</a>";
                }
            }
            if (request()->ajax()) {
                return response()->json([
                    'alumnos' => $alumnos,
                    'profesores' => $profesores,
                    'ladoAlumnos' => $ladoAlumnos,
                    'ladoProfesores' => $ladoProfesores,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function guardarDifusion()
    {
        if (Auth::check()) {
            $data = request()->all();
            $grupo = Session::get('GrupActual');
            $grado = Session::get('GRADO');
            if (Session::get('TIPCONT') == 'ASI') {
                $alumnos = \App\Mensajes::buscarAlumnos($grado, $grupo);

            } else {
                $alumnos = \App\MensajesModulosChat::buscarAlumnos($grado, $grupo);

            }

            foreach ($alumnos as $alu) {
                if (Auth::user()->id != $alu->id) {
                    $data["id_receptor"] = $alu->id;
                    if (Session::get('TIPCONT') == 'ASI') {
                        $respuesta = \App\Mensajes::guardar($data);

                    } else {
                        $respuesta = \App\MensajesModulosChat::guardar($data);

                    }
                }
            }

            if ($respuesta) {
                $mensaje = "GUARDO";
            } else {
                $mensaje = "NO GUARDO";
            }
            $usuarios = \App\Usuarios::buscar(Auth::user()->id);
            $Prof = \App\Profesores::Buscar($usuarios->id);
            $foto = 'Img_Docentes/' . $Prof->foto;

            if (request()->ajax()) {
                return response()->json([
                    'mensaje' => $mensaje,
                    'usuario' => $usuarios->nombre_usuario,
                    'id_usuario' => $usuarios->id,
                    'foto' => $foto,
                ]);
            }
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function GestionNuevo()
    {
        $bandera = "Menu4";
        $opc = "nuevo";
        $Grupos = "";
        if (Auth::check()) {
            $Usuarios = new \App\Usuarios();

            return view('Usuario.Nuevo', compact('bandera', 'Usuarios', 'opc'));
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function GuardarUsu()
    {
        $bandera = "Menu4";
        if (Auth::check()) {

            $this->validate(request(), [
                'nombre_usuario' => 'required',
                'tipo_usuario' => 'required',
                'estado_usuario' => 'required',
                'password' => 'required|confirmed',
                'password_confirmation' => 'required',
                'login_usuario' => 'required|unique:users,login_usuario',
            ], [
                'nombre_usuario.required' => 'El Nombre de Usuario es Obligatorio',
                'tipo_usuario.unique' => 'Seleccione el Tipo de Usuario',
                'login_usuario.required' => 'El Usuario es Obligatorio',
                'estado_usuario.required' => 'Seleccione el Estado del Usuario',
                'password_confirmation.required' => 'El Campo Repetir Contraseña es Obligatorio',
                'password.confirmed' => 'Las contraseña no Coinciden',
                'usuario_alumno.unique' => 'El Usuario ya Existe',
            ]);
            $data = request()->all();

            $usuario = \App\Usuarios::GuardarUsu($data);
            if ($usuario) {
                return redirect('Usuarios/Gestion')->with('success', 'Datos Guardados');
            } else {
                return redirect('Usuarios/Gestion')->with('error', 'Datos no Guardados');
            }
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function MostrarVideo()
    {
        if (Auth::check()) {
            return view('Gestion.VideosTutoriales');
        } else {
            return redirect("/")->with("error", "Su Sesión ha Terminado");
        }
    }

    public function RestablecerInf()
    {
        if (Auth::check()) {
            $data = request()->all();
            $flag = "no";
            if (request()->has('check_Calif')) {
                $LibroCal = \App\LibroCalificaciones::VaciarRegistros();
                $Intentos = \App\UpdIntEval::VaciarRegistros();
                $ComEval = \App\ComentTemas::VaciarRegistros();
                $PuntPreg = \App\PuntPreg::VaciarRegistros();
                $EvalCompl = \App\RespEvalComp::VaciarRegistros();
                $EvalEnsay = \App\RespEvalEnsay::VaciarRegistros();
                $EvalPregMul = \App\RespMultPreg::VaciarRegistros();
                $EvalTaller = \App\RespEvalTaller::VaciarRegistros();
                $EvalRelac = \App\RespEvalRelacione::VaciarRegistros();
                $EvalVerFal = \App\RespVerFal::VaciarRegistros();
                $Retroalimen = \App\Retroalimentacion::VaciarRegistros();

                $flag = "si";
            }

            if (request()->has("check_Asist")) {
                $Asistencia = \App\Asistencia::VaciarRegistros();
                $AsistRep = \App\Asistencia::VaciarRegistros();

                $flag = "si";

            }

            if (request()->has('check_foros')) {
                $flag = "si";
                $Foros = \App\Foro::VaciarRegistros();
                $ComForo = \App\Comentarios::VaciarRegistros();
            }

            if (request()->has('check_Chats')) {
                $flag = "si";
                $Mensajes = \App\Mensajes::VaciarRegistros();
            }

            if (request()->has('check_Zona')) {
                $flag = "si";
                $ZonaLibr = \App\ZonaLibre::VaciarRegistros();
                $ComZonLib = \App\ComentZona::VaciarRegistros();

            }

            if (request()->ajax()) {
                return response()->json([
                    'flag' => $flag,
                ]);
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
