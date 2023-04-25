<nav class="header-navbar navbar-expand-md navbar navbar-with-menu fixed-top navbar-dark navbar-border">
    <div class="navbar-wrapper">
        <div class="navbar-header">
            <ul class="nav navbar-nav flex-row">
                <li class="nav-item mobile-menu d-md-none mr-auto"><a class="nav-link nav-menu-main menu-toggle hidden-xs"
                        href="#"><i class="ft-menu font-large-1"></i></a></li>
                <li class="nav-item" style="padding: 0.2rem;">
                    <a class="navbar-brand">
                        <img class="brand-logo" alt="stack admin logo"
                            src="{{ asset('app-assets/images/logo/stack-logo.png') }}">
                    </a>
                </li>
                <li class="nav-item d-md-none">
                    <a class="nav-link open-navbar-container" data-toggle="collapse" data-target="#navbar-mobile"><i
                            class="fa fa-ellipsis-v"></i></a>
                </li>
            </ul>
        </div>
        <div class="navbar-container content">
            <div class="collapse navbar-collapse" id="navbar-mobile">
                <ul class="nav navbar-nav mr-auto float-left">
                    <div class="media" style="padding: 0.2rem;">
                        <a href="#">
                          <img class="media-object rounded-circle" src="{{ asset('app-assets/images/Colegios/'.Session::get('EscudoColegio')) }}" alt="Generic placeholder image" style="width: 60px;height: 60px; padding: 5px;">
                        </a>
                        <div class="media-body" style="padding-top: 0.8rem; color: #fff">
                          <h5 class="media-heading" style="margin-bottom: 0px;">{{ Session::get('NombreColegio') }}</h5>
                          {{Session::get('UbicacionColegio')}}
                        </div>
                      </div>
                </ul>
                <ul class="nav navbar-nav float-right">
                    @if (Auth::user()->tipo_usuario == 'Profesor' || Auth::user()->tipo_usuario == 'Administrador')
                        <li class="dropdown dropdown-notification nav-item" style="padding-right: 10px;">
                            <a class="nav-link nav-link-label" href="#" data-toggle="dropdown"><i
                                    class="ficon ft-paperclip"></i>Documentos
                            </a>
                            <ul class="dropdown-menu dropdown-menu-media dropdown-menu-right">
                                <li class="dropdown-menu-header">
                                    <h6 class="dropdown-header m-0">
                                        <span class="grey darken-2">Archivos</span>

                                    </h6>
                                </li>
                                <li class="scrollable-container media-list">
                                    @if (Auth::user()->tipo_usuario == 'Administrador')
                                        <a href="{{ asset('/app-assets/Archivos_Docente/Manual_de_Usuario_Admin_PEDIGITAL.pdf') }}"
                                            target="_blank">
                                        @else
                                            <a href="{{ asset('/app-assets/Archivos_Docente/Manual_de_Usuario_PEDIGITAL.pdf') }}"
                                                target="_blank">
                                    @endif
                                    <div class="media">
                                        <div class="media-left align-self-center"><i
                                                class="ft-download-cloud icon-bg-circle bg-cyan"></i></div>
                                        <div class="media-body">
                                            <h6 class="media-heading">Manual de Usuario</h6>

                                        </div>
                                    </div>
                                    </a>
                                    @if (Session::get('IDMODULO') != '')
                                        @if (Session::get('DesProg') == 'NO')
                                            <a href="#" target="_blank">
                                            @else
                                                <a href="{{ asset('/app-assets/Archivos_Docente/' . Session::get('DesProg')) }}"
                                                    target="_blank">
                                        @endif
                                        <div class="media">
                                            <div class="media-left align-self-center"><i
                                                    class="ft-download-cloud icon-bg-circle bg-red bg-darken-1"></i>
                                            </div>
                                            <div class="media-body">
                                                @if (Session::get('DesProg') == 'NO')
                                                    <h6 class="media-heading red darken-1">Programación (Sin Archivo)
                                                    </h6>
                                                @else
                                                    <h6 class="media-heading red darken-1">Programación</h6>
                                                @endif
                                            </div>
                                        </div>
                                        </a>
                                        @if (Session::get('DesCont') == 'NO')
                                            <a href="#" target="_blank">
                                            @else
                                                <a href="{{ asset('/app-assets/Archivos_Docente/' . Session::get('DesCont')) }}"
                                                    target="_blank">
                                        @endif
                                        <div class="media">
                                            <div class="media-left align-self-center"><i
                                                    class="ft-download-cloud icon-bg-circle bg-yellow bg-darken-3"></i>
                                            </div>
                                            <div class="media-body">
                                                @if (Session::get('DesCont') == 'NO')
                                                    <h6 class="media-heading yellow darken-3">Contenido Tematico (Sin
                                                        Archivo)</h6>
                                                @else
                                                    <h6 class="media-heading yellow darken-3">Contenido Tematico</h6>
                                                @endif

                                            </div>
                                        </div>
                                        </a>
                                    @endif

                                </li>

                                <li class="dropdown-menu-header">
                                    <h6 class="dropdown-header m-0">
                                        <span class="grey darken-2">
                                            Videos
                                        </span>

                                    </h6>
                                </li>
                                <li class="scrollable-container media-list">
                                    <a href="{{ url('/MostrarVideo') }}" target="_blank">
                                        <div class="media">
                                            <div class="media-left align-self-center"><i
                                                    class="ft-video icon-bg-circle bg-red bg-darken-1"></i></div>
                                            <div class="media-body">
                                                <h6 class="media-heading"></h6>Video Tutoriales

                                            </div>
                                        </div>
                                    </a>
                                </li>

                            </ul>
                        </li>


                        @if (Session::get('GrupActual') != '')
                            <div id="AlumActivos">
                                {!! Session::get('PanelActivos') !!}

                            </div>
                            <div id="ComentEval">
                                {!! Session::get('PanelNotifica') !!}

                            </div>
                        @endif

                    @endif
                    @if (Auth::user()->tipo_usuario == 'Estudiante')
                        <li class="dropdown dropdown-notification nav-item" style="padding-right: 10px;">
                            <a class="nav-link nav-link-label" href="#" data-toggle="dropdown"><i
                                    class="ficon ft-paperclip"></i>Documentos
                            </a>

                            <ul class="dropdown-menu dropdown-menu-media dropdown-menu-right">
                                <li class="dropdown-menu-header">
                                    <h6 class="dropdown-header m-0">
                                        <span class="grey darken-2">Archivos</span>

                                    </h6>
                                </li>
                                <li class="scrollable-container media-list">

                                    <a href="{{ asset('/app-assets/Archivos_Docente/Manual_de_Usuario_Estudiante.pdf') }}"
                                        target="_blank">

                                        <div class="media">
                                            <div class="media-left align-self-center"><i
                                                    class="ft-download-cloud icon-bg-circle bg-cyan"></i></div>
                                            <div class="media-body">
                                                <h6 class="media-heading">Manual de Usuario</h6>

                                            </div>
                                        </div>
                                    </a>
                                </li>
                            </ul>

                        </li>
                    @endif

                    <li class="dropdown dropdown-user nav-item">
                        <a class="dropdown-toggle nav-link dropdown-user-link" href="#" data-toggle="dropdown">
                            <span class="avatar avatar-online">
                                <img src="{{ asset('app-assets/images/' . Session::get('ImgUsu')) }}"
                                    alt="avatar"><i></i></span>
                            <span class="user-name"
                                style="text-transform: capitalize;">{{ Auth::user()->nombre_usuario }}</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            @if (Auth::user()->tipo_usuario != 'Administrador')
                                <a class="dropdown-item" href="{{ url('/perfil') }}"><i class="ft-user"></i>
                                    Perfil</a>
                            @endif
                            <a class="dropdown-item" href="{{ url('/cambiarclave') }}"><i
                                    class="ft-check-square"></i>
                                Cambiar Clave</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{ url('/logout') }}"><i class="ft-power"></i> Cerrar
                                Sesión</a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>


</nav>
