<div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">
    <div class="main-menu-content">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
            <li class=" navigation-header">
                <span>General</span><i class=" ft-minus" data-toggle="tooltip" data-placement="right"
                    data-original-title="General"></i>
            </li>
            @if (Auth::user()->tipo_usuario == 'Profesor')
                <li id="Men_Tablero" class=" nav-item"><a href="{{ url('/Administracion') }}"><i
                            class="fa fa-tachometer"></i><span class="menu-title" data-i18n="">Tablero</span></a>
                </li>
                @if (Session::get('PerZonL') == 'si')
                    <li id="Men_ZonaVis" class=" nav-item"><a
                            href="{{ url('/Contenido/ZonaLibre/' . Auth::user()->id) }}"><i
                                class="ft-airplay"></i><span class="menu-title" data-i18n="">Zona
                                Libre</span></a>
                    </li>
                @endif

                @if (Session::get('IDMODULO') != '')
                    @if (Session::get('TIPCONT') == 'ASI')
                        <li id="Men_Presentacion" class="active"><a
                                href="{{ url('/Contenido/Presentacion/' . Session::get('IDMODULO')) }}"><i
                                    class="ft-monitor"></i><span class="menu-title"
                                    data-i18n="">Presentación</span></a>
                        </li>
                        <li id="Men_Contenido"><a
                                href="{{ url('/Contenido/Contenido/' . Session::get('IDMODULO')) }}"><i
                                    class="ft-list"></i><span class="menu-title"
                                    data-i18n="">Contenido</span></a>
                        </li>
                    @else
                        <li id="Men_Presentacion" class="active"><a
                                href="{{ url('/Contenido/PresentacionMod/' . Session::get('IDMODULO')) }}"><i
                                    class="ft-monitor"></i><span class="menu-title"
                                    data-i18n="">Presentación</span></a>
                        </li>
                        <li id="Men_Contenido"><a
                                href="{{ url('/Contenido/ContenidoMod/' . Session::get('IDMODULO')) }}"><i
                                    class="ft-list"></i><span class="menu-title"
                                    data-i18n="">Contenido</span></a>
                        </li>
                    @endif

                    @if (Session::get('PerLabo') == 'si' && Session::get('CANT_LABORATORIOS')>0)

                    <li id="Men_Laboratorios"><a
                            href="{{ url('/Laboratorios/ListLaboratorios/' . Session::get('IDMODULO')) }}"><i
                                class="fa fa-flask"></i><span class="menu-title"
                                data-i18n="">Laboratorios</span></a>
                    </li>
                @endif

                    <li id="Men_Calificaciones" class=" nav-item"><a href="#"><i class="ft-award"></i><span
                                class="menu-title" data-i18n="">Calificaciones</span></a>
                        <ul class="menu-content">
                            <li style="display: none;" id="Men_Calificiones_PondCali"><a class="menu-item"
                                    href="{{ url('/Calificaciones/PonderacionNotas/' . Session::get('IDMODULO')) }}"><i
                                        class="ft-list"></i>Ponderación
                                    de Notas</a>
                            </li>
                            <li id="Men_Calificiones_CalAlumnos"><a class="menu-item"
                                    href="{{ url('/Calificaciones/TableroCalificaciones/' . Session::get('IDMODULO')) }}"><i
                                        class="ft-list"></i>Calificar
                                    Alumnos</a>
                            </li>
                            <li id="Men_Calificiones_LibrrCal"><a class="menu-item"
                                    href="{{ url('/Calificaciones/LibroCalificaciones/' . Session::get('IDMODULO')) }}"><i
                                        class="ft-list"></i>Libro
                                    de Calificaciones</a>
                            </li>
                        </ul>
                    </li>

                    @if (Session::get('TIPCONT') == 'ASI')
                        <li id="Men_Asistencia" class=" nav-item"><a
                                href="{{ url('/Profesores/asistencia') }}"><i class="ft-clipboard"></i><span
                                    class="menu-title" data-i18n="">Asistencia</span></a>
                        </li>
                    @else
                        <li id="Men_Asistencia" class=" nav-item"><a
                                href="{{ url('/Profesores/asistenciaMod') }}"><i class="ft-clipboard"></i><span
                                    class="menu-title" data-i18n="">Asistencia</span></a>
                        </li>
                    @endif

                    <li id="Men_Foros" class=" nav-item"><a href="{{ url('/Foro/Gestion') }}"><i
                                class="fa fa-comments"></i><span class="menu-title" data-i18n="">Foros</span></a>
                    </li>



                    <li id="Men_Chat" class=" nav-item"><a href="{{ url('/chat') }}"><i
                                class="ft-message-square"></i><span class="menu-title" data-i18n="">Chat</span></a>
                    </li>
                    @if (Session::get('PerAsig') == 'si')
                        <li id="Men_Asignaturas" class=" nav-item"><a href="#"><i class="fa fa-book"></i><span
                                    class="menu-title" data-i18n="">Gestionar Asignaturas</span></a>
                            <ul class="menu-content">
                                <li id="Men_Asignaturas_addAdig"><a class="menu-item"
                                        href="{{ url('/Asignaturas/GestionGrado/') }}"><i class="ft-list"></i>
                                        Grados</a>
                                </li>
                                <li id="Men_Asignaturas_addUnid"><a class="menu-item"
                                        href="{{ url('/Asignaturas/GestionUnid/') }}"><i class="ft-list"></i>
                                        Unidades</a>
                                </li>
                                <li id="Men_Asignaturas_addTem"><a class="menu-item"
                                        href="{{ url('/Asignaturas/GestionTem/') }}"><i class="ft-list"></i>
                                        Temas</a>
                                </li>
                            </ul>
                        </li>
                    @endif
                    @if (Session::get('PerModu') == 'si')
                        <li id="Men_Modulos" class=" nav-item"><a href="#"><i class="fa fa-book"></i><span
                                    class="menu-title" data-i18n="">Gestionar Módulos</span></a>
                            <ul class="menu-content">
                                <li id="Men_Modulos_addAdig"><a class="menu-item"
                                        href="{{ url('/Modulos/GestionGradosModTransv/') }}"><i
                                            class="ft-list"></i>
                                        Grados</a>
                                </li>
                                <li id="Men_Modulos_addUnid"><a class="menu-item"
                                        href="{{ url('/Modulos/GestionUnid/') }}"><i class="ft-list"></i>
                                        Unidades</a>
                                </li>
                                <li id="Men_Modulos_addTem"><a class="menu-item"
                                        href="{{ url('/Modulos/GestionTem/') }}"><i class="ft-list"></i>
                                        Temas</a>
                                </li>
                            </ul>
                        </li>
                    @endif
                    @if (Session::get('PerZonL') == 'si')
                        <li id="Men_Zona" class=" nav-item"><a href="{{ url('/Asignaturas/ZonaLibre') }}"><i
                                    class="fa fa-book"></i><span class="menu-title" data-i18n="">Gestionar
                                    Zona Libre</span></a>
                        </li>
                    @endif
                    @if (Session::get('PerLabo') == 'si')
                        <li id="Men_Laboratorios" class=" nav-item"><a
                                href="{{ url('/Laboratorios/GestionLaboratorios/') }}"><i
                                    class="fa fa-flask"></i><span class="menu-title" data-i18n="">Gestionar
                                    Laboratorios</span></a>
                        </li>
                    @endif
                @else
                    @if (Session::get('PerAsig') == 'si')
                        <li id="Men_Asignaturas" class=" nav-item"><a href="#"><i class="fa fa-book"></i><span
                                    class="menu-title" data-i18n="">Gestionar Asignaturas</span></a>
                            <ul class="menu-content">
                                <li id="Men_Asignaturas_addAdig"><a class="menu-item"
                                        href="{{ url('/Asignaturas/GestionGrado/') }}"><i class="ft-list"></i>
                                        Grados</a>
                                </li>
                                <li id="Men_Asignaturas_addUnid"><a clasMen_ModuloEs="menu-item"
                                        href="{{ url('/Asignaturas/GestionUnid/') }}"><i class="ft-list"></i>

                                        Unidades</a>
                                </li>
                                <li id="Men_Asignaturas_addTem"><a class="menu-item"
                                        href="{{ url('/Asignaturas/GestionTem/') }}"><i class="ft-list"></i>

                                        Temas</a>
                                </li>

                            </ul>
                        </li>
                    @endif
                    @if (Session::get('PerModu') == 'si')
                        <li id="Men_Modulos" class=" nav-item"><a href="#"><i class="fa fa-book"></i><span
                                    class="menu-title" data-i18n="">Gestionar Módulos Tv.</span></a>
                            <ul class="menu-content">
                                <li id="Men_Modulos_addAdig"><a class="menu-item"
                                        href="{{ url('/Modulos/GestionGradosModTransv/') }}"><i
                                            class="ft-list"></i>
                                        Grados</a>
                                </li>
                                <li id="Men_Modulos_addUnid"><a class="menu-item"
                                        href="{{ url('/Modulos/GestionUnid/') }}"><i class="ft-list"></i>
                                        Unidades</a>
                                </li>
                                <li id="Men_Modulos_addTem"><a class="menu-item"
                                        href="{{ url('/Modulos/GestionTem/') }}"><i class="ft-list"></i>
                                        Temas</a>
                                </li>
                            </ul>
                        </li>
                    @endif
                    @if (Session::get('PerModE') == 'si')
                        <li id="Men_Modulos_E" class=" nav-item"><a href="#"><i class="fa fa-book"></i><span
                                    class="menu-title" data-i18n="">Gestionar Módulo E</span></a>
                            <ul class="menu-content">
                                <li id="Men_ModulosE_addAdig"><a class="menu-item"
                                        href="{{ url('/ModuloE/GestionAsignaturas/') }}"><i
                                            class="ft-list"></i>
                                        Asignaturas</a>
                                </li>

                                <li id="Men_ModulosE_addTem"><a class="menu-item"
                                        href="{{ url('/ModuloE/GestionTema/') }}"><i class="ft-list"></i>
                                        Temas</a>
                                </li>
                                <li id="Men_ModulosE_addPre"><a class="menu-item"
                                        href="{{ url('/ModuloE/GestionBancoPreguntas/') }}"><i
                                            class="ft-list"></i>
                                        Banco de Preguntas</a>
                                </li>

                                <li id="Men_ModulosE_addComp"><a class="menu-item"
                                        href="{{ url('/ModuloE/GestionCompetencia/') }}"><i
                                            class="ft-list"></i>
                                        Competencias</a>
                                </li>
                                <li id="Men_ModulosE_addComponent"><a class="menu-item"
                                        href="{{ url('/ModuloE/GestionComponentes/') }}"><i
                                            class="ft-list"></i>
                                        Componentes</a>
                                </li>

                                <li id="Men_ModulosE_addSimulacro"><a class="menu-item"
                                        href="{{ url('/ModuloE/GestionSimulacros/') }}"><i
                                            class="ft-list"></i>
                                        Simulacros</a>
                                </li>
                            </ul>
                        </li>
                    @endif
                    @if (Session::get('PerZonL') == 'si')
                        <li id="Men_Zona" class=" nav-item"><a href="{{ url('/Asignaturas/ZonaLibre') }}"><i
                                    class="fa fa-book"></i><span class="menu-title" data-i18n="">Gestionar
                                    Zona Libre</span></a>
                        </li>
                    @endif

                    @if (Session::get('PerLabo') == 'si')
                        <li id="Men_Laboratorios" class=" nav-item"><a
                                href="{{ url('/Laboratorios/GestionLaboratorios/') }}"><i
                                    class="fa fa-flask"></i><span class="menu-title" s data-i18n="">Gestionar
                                    Laboratorios</span></a>
                        </li>
                    @endif


                @endif

                <li id="Men_Estudiantes" class=" nav-item"><a href="{{ url('/Alumnos/Gestion/') }}"><i
                            class="ft-users"></i><span class="menu-title" data-i18n="">Gestionar
                            Estudiantes</span></a>
                </li>

            @endif
            @if (Auth::user()->tipo_usuario == 'Estudiante')
                @if (Session::get('IDMODULO') != '')
                    <li id="Men_Tablero" class=" nav-item"><a href="{{ url('/Administracion') }}"><i
                                class="fa fa-tachometer"></i><span class="menu-title"
                                data-i18n="">Tablero</span></a>
                    </li>
                    @if (Session::get('PerZonL') == 'si')
                        <li id="Men_ZonaVis" class=" nav-item"><a
                                href="{{ url('/Contenido/ZonaLibre/' . Auth::user()->id) }}"><i
                                    class="ft-airplay"></i><span class="menu-title" data-i18n="">Zona
                                    Libre</span></a>
                        </li>
                    @endif

                    @if (Session::get('TIPCONT') == 'ASI')
                        <li id="Men_Presentacion" class="active"><a
                                href="{{ url('/Contenido/Presentacion/' . Session::get('IDMODULO')) }}"><i
                                    class="ft-monitor"></i><span class="menu-title"
                                    data-i18n="">Presentación</span></a>
                        </li>
                        <li id="Men_Contenido"><a
                                href="{{ url('/Contenido/Contenido/' . Session::get('IDMODULO')) }}"><i
                                    class="ft-list"></i><span class="menu-title"
                                    data-i18n="">Contenido</span></a>
                        </li>
                    @else
                        <li id="Men_Presentacion" class="active"><a
                                href="{{ url('/Contenido/PresentacionMod/' . Session::get('IDMODULO')) }}"><i
                                    class="ft-monitor"></i><span class="menu-title"
                                    data-i18n="">Presentación</span></a>
                        </li>
                        <li id="Men_Contenido"><a
                                href="{{ url('/Contenido/ContenidoMod/' . Session::get('IDMODULO')) }}"><i
                                    class="ft-list"></i><span class="menu-title"
                                    data-i18n="">Contenido</span></a>
                        </li>
                    @endif

                  
                    @if (Session::get('PerLabo') == 'si' && Session::get('CANT_LABORATORIOS')>0)

                        <li id="Men_Laboratorios"><a
                                href="{{ url('/Laboratorios/ListLaboratorios/' . Session::get('IDMODULO')) }}"><i
                                    class="fa fa-flask"></i><span class="menu-title"
                                    data-i18n="">Laboratorios</span></a>
                        </li>
                    @endif

                    <li id="Men_Foros" class=" nav-item"><a href="{{ url('/Foro/Gestion') }}"><i
                                class="ft-message-circle"></i><span class="menu-title"
                                data-i18n="">Foros</span></a>
                    </li>
                    <li id="Men_Chat" class=" nav-item"><a href="{{ url('/chat') }}"><i
                                class="ft-message-square"></i><span class="menu-title"
                                data-i18n="">Chat</span></a>
                    </li>

                    <li id="Men_Calificaciones"><a
                            href="{{ url('/Calificaciones/CalifAlumno/' . Session::get('IDMODULO')) }}"><i
                                class="ft-award"></i><span class="menu-title"
                                data-i18n="">Calificaciones</span></a>
                    </li>

                    <li class=" nav-item"><a href="{{ url('/Administracion') }}"><i
                                class="ft-corner-up-left"></i><span class="menu-title"
                                data-i18n="">Atras</span></a>
                    </li>
                @else
                    <li id="Men_Tablero" class=" nav-item"><a href="{{ url('/Administracion') }}"><i
                                class="fa fa-tachometer"></i><span class="menu-title"
                                data-i18n="">Tablero</span></a>
                    </li>
                    <li id="Men_ZonaVis" class=" nav-item"><a
                            href="{{ url('/Contenido/ZonaLibre/' . Auth::user()->id) }}"><i
                                class="ft-airplay"></i><span class="menu-title" data-i18n="">Zona
                                Libre</span></a>
                    </li>
                    @if (Session::get('PerModE') == 'si')
                        <li id="Men_ModuloE" class=" nav-item"><a
                                href="{{ url('/ModuloE/CargarContModuloE') }}"><i
                                    class="fa fa-braille"></i><span class="menu-title" data-i18n="">Módulo E
                                </span></a>
                    @endif
                    @if (Session::get('PerModJ') == 'si')
                        <li id="Men_ModuloJ" class=" nav-item"><a 
                                href="{{url('/Contenido/ZonaPlay/' . Auth::user()->id)}}" target="_blank"><i
                                    class="fa fa-gamepad"></i><span class="menu-title" data-i18n="">Zona Play
                                </span></a>
                    @endif
                @endif
            @endif
            @if (Auth::user()->tipo_usuario == 'Administrador')
                <li id="Men_Inicio" class="active" class=" nav-item"><a
                        href="{{ url('/Administracion') }}"><i class="ft-home"></i><span
                            class="menu-title" data-i18n="">Tablero</span></a>
                </li>
                @if (Session::get('IDMODULO') != '')
                    @if (Session::get('TIPCONT') == 'ASI')
                        <li id="Men_Presentacion" class="active"><a
                                href="{{ url('/Contenido/Presentacion/' . Session::get('IDMODULO')) }}"><i
                                    class="ft-monitor"></i><span class="menu-title"
                                    data-i18n="">Presentación</span></a>
                        </li>
                        <li id="Men_Contenido"><a
                                href="{{ url('/Contenido/Contenido/' . Session::get('IDMODULO')) }}"><i
                                    class="ft-list"></i><span class="menu-title"
                                    data-i18n="">Contenido</span></a>
                        </li>
                    @else
                        <li id="Men_Presentacion" class="active"><a
                                href="{{ url('/Contenido/PresentacionMod/' . Session::get('IDMODULO')) }}"><i
                                    class="ft-monitor"></i><span class="menu-title"
                                    data-i18n="">Presentación</span></a>
                        </li>
                        <li id="Men_Contenido"><a
                                href="{{ url('/Contenido/ContenidoMod/' . Session::get('IDMODULO')) }}"><i
                                    class="ft-list"></i><span class="menu-title"
                                    data-i18n="">Contenido</span></a>
                        </li>
                    @endif
                @endif
             
                @if (Session::get('PerAsig') == 'si')
                    <li id="Men_Asignaturas" class=" nav-item"><a href="#"><i class="fa fa-book"></i><span
                                class="menu-title" data-i18n="">Gestionar Asignaturas</span></a>
                        <ul class="menu-content">
                            <li id="Men_Asignaturas_addAreas"><a class="menu-item"
                                href="{{ url('/Asignaturas/GestionAreas/') }}"><i
                                    class="ft-list"></i>Gestionar Áreas</a>
                        </li>
                            <li id="Men_Asignaturas_addAsignatura"><a class="menu-item"
                                    href="{{ url('/Asignaturas/GestionAsignaturas/') }}"><i
                                        class="ft-list"></i>
                                    Asignaturas</a>
                            </li>
                            <li id="Men_Asignaturas_addAdig"><a class="menu-item"
                                    href="{{ url('/Asignaturas/GestionGrado/') }}"><i class="ft-list"></i>
                                    Grados</a>
                            </li>
                            <li id="Men_Asignaturas_addUnid"><a class="menu-item"
                                    href="{{ url('/Asignaturas/GestionUnid/') }}"><i class="ft-list"></i>
                                    Unidades</a>
                            </li>
                            <li id="Men_Asignaturas_addTem"><a class="menu-item"
                                    href="{{ url('/Asignaturas/GestionTem/') }}"><i class="ft-list"></i>
                                    Temas</a>
                            </li>
                        </ul>
                    </li>
                @endif
                @if (Session::get('PerModu') == 'si')
                    <li id="Men_Modulos" class=" nav-item"><a href="#"><i class="fa fa-book"></i><span
                                class="menu-title" data-i18n="">Gestionar Módulos Tv.</span></a>
                        <ul class="menu-content">
                            <li id="Men_Modulos_addModulo"><a class="menu-item"
                                    href="{{ url('/Modulos/GestionModulos/') }}"><i class="ft-list"></i>
                                    Módulos</a>
                            </li>
                            <li id="Men_Modulos_addAdig"><a class="menu-item"
                                    href="{{ url('/Modulos/GestionGradosModTransv/') }}"><i
                                        class="ft-list"></i>
                                    Grados</a>
                            </li>
                            <li id="Men_Modulos_addUnid"><a class="menu-item"
                                    href="{{ url('/Modulos/GestionUnid/') }}"><i class="ft-list"></i>
                                    Unidades</a>
                            </li>
                            <li id="Men_Modulos_addTem"><a class="menu-item"
                                    href="{{ url('/Modulos/GestionTem/') }}"><i class="ft-list"></i>
                                    Temas</a>
                            </li>
                        </ul>
                    </li>
                @endif
                @if (Session::get('PerModE') == 'si')
                    <li id="Men_Modulos_E" class=" nav-item"><a href="#"><i class="fa fa-book"></i><span
                                class="menu-title" data-i18n="">Gestionar Módulo E</span></a>
                        <ul class="menu-content">
                            <li id="Men_ModulosE_addAdig"><a class="menu-item"
                                    href="{{ url('/ModuloE/GestionAsignaturas/') }}"><i class="ft-list"></i>
                                    Asignaturas</a>
                            </li>

                            <li id="Men_ModulosE_addTem"><a class="menu-item"
                                    href="{{ url('/ModuloE/GestionTema/') }}"><i class="ft-list"></i>
                                    Temas</a>
                            </li>

                            <li id="Men_ModulosE_addPre"><a class="menu-item"
                                    href="{{ url('/ModuloE/GestionBancoPreguntas/') }}"><i
                                        class="ft-list"></i>
                                    Banco de Preguntas</a>
                            </li>
                            <li id="Men_ModulosE_addSimulacro"><a class="menu-item"
                                href="{{ url('/ModuloE/GestionSimulacros/') }}"><i
                                    class="ft-list"></i>
                                Simulacros</a>
                        </li>


                            <li id="Men_ModulosE_addComp"><a class="menu-item"
                                    href="{{ url('/ModuloE/GestionCompetencia/') }}"><i class="ft-list"></i>
                                    Competencias</a>
                            </li>
                            <li id="Men_ModulosE_addComponent"><a class="menu-item"
                                    href="{{ url('/ModuloE/GestionComponentes/') }}"><i class="ft-list"></i>
                                    Componentes</a>
                            </li>
                            
                        </ul>
                    </li>
                @endif
                @if (Session::get('PerLabo') == 'si')
                    <li id="Men_Laboratorios" class=" nav-item"><a
                            href="{{ url('/Laboratorios/GestionLaboratorios/') }}"><i
                                class="fa fa-flask"></i><span class="menu-title" data-i18n="">Gestionar
                                Laboratorios</span></a>
                    </li>
                @endif
                <li id="Men_Profesores" class=" nav-item"><a href="{{ url('/Profesores/Gestion/') }}"><i
                    class="ft-users"></i><span class="menu-title" data-i18n="">Gestionar
                    Docentes</span></a>
        </li>
        <li id="Men_Estudiantes" class=" nav-item"><a href="{{ url('/Alumnos/Gestion/') }}"><i
                    class="ft-users"></i><span class="menu-title" data-i18n="">Gestionar
                    Estudiantes</span></a>
        </li>

     
                <li id="Men_Usuarios" class=" nav-item"><a href="{{ url('/Usuarios/Gestion/') }}"><i
                            class="ft ft-user"></i><span class="menu-title" data-i18n="">Gestionar
                            Usuarios</span></a>
                </li>
                <li id="Men_EstadisticasAdm" class=" nav-item"><a
                    href="{{ url('/Gestion/EstadisticasAdmin/') }}"><i class="ft-bar-chart"></i><span
                        class="menu-title" data-i18n="">Datos Estadisticos</span></a>
            </li>
                <li id="Men_configuracion" class=" nav-item"><a
                        href="{{ url('/ConfParametros/ConfParametros/') }}"><i class="fa fa-wrench"></i><span
                            class="menu-title" data-i18n="">Configurar Parametros
                        </span></a>
                </li>
                <li id="Men_RegUsuarios" class=" nav-item"><a
                    href="{{ url('/Usuarios/RegistrosUsuarios') }}"><i class="fa fa-id-card-o"></i><span
                        class="menu-title" data-i18n="">Registros de Usuarios
                    </span></a>
            </li>


            @endif
        </ul>
    </div>
</div>
