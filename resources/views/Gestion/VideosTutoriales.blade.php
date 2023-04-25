@extends('Plantilla.PrincipalVideos')
@section('title', 'Video Tutoriales PEDIGITAL')
@section('Contenido')
    <div class="content-header row">
        <div class="content-header-left col-md-12 col-12 mb-2">
            <h3 class="content-header-title mb-0" id="TituloVideo">Video Presentación </h3>
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item" onclick="$.mostAsig()"><a href="">Video Tutoriales PEDIGITAL</a>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content-body">

        <label id="id_dat"></label>

        <div class="row" id="Div_Asig">
            <div class="col-xl-12 col-lg-12 col-md-12">
                <video style="width: 100%; height: 100%;" id="videoclipAnima" controls
                    data-ruta="{{ asset('/app-assets/VideoTutorial') }}">
                    <source src="" id="mp4videoAnima" type="video/mp4">
                </video>
            </div>
        </div>


    </div>

    {!! Form::open(['url' => '/Contenido/CargaCursos', 'id' => 'formAuxiliar']) !!}
    {!! Form::close() !!}
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {

            $.extend({
                MostVideo: function(vi) {
                    var videoID = 'videoclipAnima';
                    var sourceID = 'mp4videoAnima';

                    switch (vi) {
                        case 'Pres':
                            $("#Men_Tablero").addClass("active");
                            var nomarchi = "Presentacion.mp4";
                            break;
                            case 'CrearDoce':
                            $("#Men_Inicio").removeClass("active");
                            $("#MenuDoce").addClass("has-sub open");
                            $("#CrearDoce").addClass("active");
                            var nomarchi = "RegistrarDocentes.mp4";
                            break;
                            case 'AsigDoce':
                            $("#Men_Inicio").removeClass("active");
                            $("#MenuDoce").addClass("has-sub open");
                            $("#AsigAsig").addClass("active");
                            var nomarchi = "AsignarAsignaturas.mp4";
                            break;
                            case 'CrearEst':
                            $("#Men_Inicio").removeClass("active");
                            $("#MenuEst").addClass("has-sub open");
                            $("#CrearEst").addClass("active");
                            var nomarchi = "RegistrarEstudiantes.mp4";
                            break;
                            case 'AdminEst':
                            $("#Men_Inicio").removeClass("active");
                            $("#MenuEst").addClass("has-sub open");
                            $("#AdmEst").addClass("active");
                            var nomarchi = "AdministrarEstudiantes.mp4";
                            break;
                            case 'ImpEst':
                            $("#Men_Inicio").removeClass("active");
                            $("#MenuEst").addClass("has-sub open");
                            $("#ImpEst").addClass("active");
                            var nomarchi = "ImportarEstudiantes.mp4";
                            break;
                            case 'CrearArea':
                            $("#Men_Inicio").removeClass("active");
                            $("#MenuAsig").addClass("has-sub open");
                            $("#CrearArea").addClass("active");
                            var nomarchi = "Crear_Areas.mp4";
                            break;
                            case 'CrearAsig':
                            $("#Men_Inicio").removeClass("active");
                            $("#MenuAsig").addClass("has-sub open");
                            $("#CrearAsig").addClass("active");
                            var nomarchi = "Crear_Asignaturas.mp4";
                            break;
                            case 'CrearGrado':
                            $("#Men_Inicio").removeClass("active");
                            $("#MenuAsig").addClass("has-sub open");
                            $("#CrearGrado").addClass("active");
                            var nomarchi = "Crear_Grados.mp4";
                            break;
                            case 'CrearUnidades':
                            $("#Men_Inicio").removeClass("active");
                            $("#MenuAsig").addClass("has-sub open");
                            $("#CrearUnidades").addClass("active");
                            var nomarchi = "Crear_Unidad.mp4";
                            break;
                            case 'CrearTemas':
                            $("#Men_Inicio").removeClass("active");
                            $("#MenuAsig").addClass("has-sub open");
                            $("#CrearTemas").addClass("active");
                            var nomarchi = "Crear_Tema.mp4";
                            break;
                            case 'CrearEval':
                            $("#Men_Inicio").removeClass("active");
                            $("#MenuAsig").addClass("has-sub open");
                            $("#CrearEval").addClass("active");
                            var nomarchi = "Crear_evaluacion.mp4";
                            break;
                            case 'CrearUsu':
                            $("#MenuUsu").removeClass("active");
                            $("#CrearUsu").addClass("active");
                            var nomarchi = "GestionUsuario.mp4";
                            break;
                            case 'VerContenido':
                            $("#Men_Inicio").removeClass("active");
                            $("#VerContenido").addClass("active");
                            var nomarchi = "Visualizar_contenido.mp4";print
                            break;
                            case 'VerZona':
                            $("#Men_Inicio").removeClass("active");
                            $("#VerZona").addClass("active");
                            var nomarchi = "Zona_Libre.mp4";
                            break;
                            case 'Asistencia':
                            $("#Men_Inicio").removeClass("active");
                            $("#Asistencia").addClass("active");
                            var nomarchi = "Asistencia.mp4";
                            break;
                            case 'Foros':
                            $("#Men_Inicio").removeClass("active");
                            $("#Foros").addClass("active");
                            var nomarchi = "CrearForo.mp4";
                            break;
                            case 'Chats':
                            $("#Men_Inicio").removeClass("active");
                            $("#Chats").addClass("active");
                            var nomarchi = "Chats.mp4";
                            break;
                            case 'EvalEst':
                            $("#Men_Inicio").removeClass("active");
                            $("#MenuCal").addClass("has-sub open");
                            $("#EvalEst").addClass("active");
                            var nomarchi = "EvaluarAlumno.mp4";
                            break;
                            case 'LibroCal':
                            $("#Men_Inicio").removeClass("active");
                            $("#MenuCal").addClass("has-sub open");
                            $("#LibroCal").addClass("active");
                            var nomarchi = "LibroCalificaciones.mp4";
                            break;
                            default:
                            $("#Men_Tablero").addClass("active");
                            var nomarchi = "Presentacion.mp4";
                    }

                    
                    var newmp4 = $('#videoclipAnima').data("ruta") + "/" + nomarchi;
                    $('#' + sourceID).attr('src', newmp4);
                    $('#' + videoID).get(0).load();
                    $('#' + videoID).get(0).play();





                },

            });
            $.MostVideo("Pres");

        });
    </script>
@endsection
