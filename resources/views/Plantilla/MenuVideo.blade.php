<div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">
    <div class="main-menu-content">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
            <li class=" navigation-header">
                <span>General</span><i class=" ft-minus" data-toggle="tooltip" data-placement="right"
                    data-original-title="General"></i>
            </li>
            @if (Auth::user()->tipo_usuario == 'Administrador')
                <li id="Men_Tablero" class=" nav-item"><a href="{{ url('/MostrarVideo') }}"><i
                            class="fa fa-home"></i><span class="menu-title" data-i18n="">Visualizar Contenido</span></a>
                </li>
                <li id="MenuDoce" class=" nav-item"><a href="#"><i class="ft-grid"></i><span
                            class="menu-title" data-i18n="">Gestionar Docentes</span></a>
                    <ul class="menu-content">
                        <li id="CrearDoce"><a class="menu-item" onclick="$.MostVideo('CrearDoce');"><i
                                    class="ft-video"></i>Crear Docentes</a>
                        </li>
                        <li id="AsigAsig"><a class="menu-item" onclick="$.MostVideo('AsigDoce');"><i
                                    class="ft-video"></i>Asignar Asignaturas a Docentes</a>
                        </li>

                    </ul>
                </li>

                <li id="MenuEst" class=" nav-item"><a href="#"><i class="ft-grid"></i><span
                            class="menu-title" data-i18n="">Gestionar Estudiantes</span></a>
                    <ul class="menu-content">
                        <li id="CrearEst"><a class="menu-item" onclick="$.MostVideo('CrearEst');"><i
                                    class="ft-video"></i>Crear Estudiante</a>
                        </li>
                        <li id="AdmEst"><a class="menu-item" onclick="$.MostVideo('AdminEst');"><i
                                    class="ft-video"></i>Administrar Estudiantes</a>
                        </li>
                        <li id="ImpEst"><a class="menu-item" onclick="$.MostVideo('ImpEst');"><i
                                    class="ft-video"></i>Importar Estudiantes</a></li>
                    </ul>
                </li>

                <li id="MenuAsig" class=" nav-item"><a href="#"><i class="ft-grid"></i><span
                            class="menu-title" data-i18n="">Gestionar Asignaturas</span></a>
                    <ul class="menu-content">
                        <li id="CrearArea"><a class="menu-item" onclick="$.MostVideo('CrearArea');"><i
                                    class="ft-video"></i>Crear Areas</a>
                        </li>
                        <li id="CrearAsig"><a class="menu-item" onclick="$.MostVideo('CrearAsig');"><i
                                    class="ft-video"></i>Crear Asignaturas</a>
                        </li>
                        <li id="CrearGrado"><a class="menu-item" onclick="$.MostVideo('CrearGrado');"><i
                                    class="ft-video"></i>Crear Grados x Asignaturas</a></li>
                        <li id="CrearUnidades"><a class="menu-item" onclick="$.MostVideo('CrearUnidades');"><i
                                    class="ft-video"></i>Crear Unidades</a></li>
                        <li id="CrearTemas"><a class="menu-item" onclick="$.MostVideo('CrearTemas');"><i
                                    class="ft-video"></i>Crear Temas</a></li>
                        <li id="CrearEval"><a class="menu-item" onclick="$.MostVideo('CrearEval');"><i
                                    class="ft-video"></i>Crear Evaluaciones</a></li>
                    </ul>
                </li>

                <li id="MenuUsu" class=" nav-item"><a href="#"><i class="ft-grid"></i><span
                            class="menu-title" data-i18n="">Gestionar Usuarios</span></a>
                    <ul class="menu-content">
                        <li id="CrearUsu"><a class="menu-item" onclick="$.MostVideo('CrearUsu');"><i
                                    class="ft-video"></i>Crear y Editar Usuarios</a>
                        </li>
                    </ul>
                </li>
            @endif

            @if (Auth::user()->tipo_usuario == 'Profesor')
                <li id="VerContenido"><a class="menu-item" onclick="$.MostVideo('VerContenido');"><i
                            class="ft-video"></i>Ver Contenido</a>
                </li>
                <li id="VerZona"><a class="menu-item" onclick="$.MostVideo('VerZona');"><i
                            class="ft-video"></i>Zona Libre</a>
                </li>
                <li id="Asistencia"><a class="menu-item" onclick="$.MostVideo('Asistencia');"><i
                            class="ft-video"></i>Asistencia</a>
                </li>
                <li id="Foros"><a class="menu-item" onclick="$.MostVideo('Foros');"><i
                            class="ft-video"></i>Gestionar Foros</a>
                </li>
                <li id="Chats"><a class="menu-item" onclick="$.MostVideo('Chats');"><i
                            class="ft-video"></i>Gestionar Chats</a>
                </li>
                <li id="MenuCal" class=" nav-item"><a href="#"><i class="ft-grid"></i><span
                            class="menu-title" data-i18n="">Gestionar Calificaciones</span></a>
                        <ul class="menu-content">
                        <li id="EvalEst"><a class="menu-item" onclick="$.MostVideo('EvalEst');"><i
                                    class="ft-video"></i>Evaluar Estudiante</a>
                        </li>
                        <li id="LibroCal"><a class="menu-item" onclick="$.MostVideo('LibroCal');"><i
                                    class="ft-video"></i>Libro de Calificaciones</a>
                        </li>
                    </ul>
                </li>
                <li id="MenuEst" class=" nav-item"><a href="#"><i class="ft-grid"></i><span
                            class="menu-title" data-i18n="">Gestionar Estudiantes</span></a>
                    <ul class="menu-content">
                        <li id="CrearEst"><a class="menu-item" onclick="$.MostVideo('CrearEst');"><i
                                    class="ft-video"></i>Crear Estudiante</a>
                        </li>
                    </ul>
                </li>

                <li id="MenuAsig" class=" nav-item"><a href="#"><i class="ft-grid"></i><span
                            class="menu-title" data-i18n="">Gestionar Asignaturas</span></a>
                    <ul class="menu-content">
                        <li id="CrearGrado"><a class="menu-item" onclick="$.MostVideo('CrearGrado');"><i
                                    class="ft-video"></i>Crear Grados x Asignaturas</a></li>
                        <li id="CrearUnidades"><a class="menu-item" onclick="$.MostVideo('CrearUnidades');"><i
                                    class="ft-video"></i>Crear Unidades</a></li>
                        <li id="CrearTemas"><a class="menu-item" onclick="$.MostVideo('CrearTemas');"><i
                                    class="ft-video"></i>Crear Temas</a></li>
                        <li id="CrearEval"><a class="menu-item" onclick="$.MostVideo('CrearEval');"><i
                                    class="ft-video"></i>Crear Evaluaciones</a></li>
                    </ul>
                </li>
            @endif
        </ul>
        </li>
        </ul>
    </div>
</div>
