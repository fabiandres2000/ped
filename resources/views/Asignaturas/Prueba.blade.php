<!-- - var navbarShadow = true-->
<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
        <meta name="description" content="Stack admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template with unlimited possibilities.">
        <meta name="keywords" content="admin template, stack admin template, dashboard template, flat admin template, responsive admin template, web app">
        <meta name="author" content="PIXINVENT">
        <title>PEDIGITAL - @yield('title','Plataforma Educativa Digital')</title>
        <link rel="apple-touch-icon" href="{{asset('app-assets/images/ico/apple-icon-120.png')}}">
        <link rel="shortcut icon" type="image/x-icon" href="{{asset('app-assets/images/ico/favicon.ico')}}">
        
            <link href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
        
      <link href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,500i%7COpen+Sans:300,300i,400,400i,600,600i,700,700i"
              rel="stylesheet">
         BEGIN VENDOR CSS
        <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/vendors.css')}}">
     <link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/forms/icheck/icheck.css')}}">
       <!--      <link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/forms/icheck/custom.css')}}">-->
        <link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/charts/morris.css')}}">
        <link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/extensions/unslider.css')}}">
        <link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/weather-icons/climacons.min.css')}}">
        <link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/ui/prism.min.css')}}">
        <link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/extensions/sweetalert.css')}}">
        <link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/forms/selects/select2.min.css')}}">
        <link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/pickers/daterange/daterangepicker.css')}}">
        <link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/pickers/datetime/bootstrap-datetimepicker.css')}}">
        <link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/pickers/pickadate/pickadate.css')}}">
      <!--       END VENDOR CSS
         HOVER-MASTER CSS
        <link rel="stylesheet" type="text/css" href="{{asset('app-assets/Hover-master/css/hover.css')}}">

         HOVER-MASTER CSS
         BEGIN STACK CSS
        <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/app.css')}}">
         END STACK CSS
         BEGIN Page Level CSS
        <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/core/menu/menu-types/vertical-menu.css')}}">
        <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/core/colors/palette-gradient.css')}}">
        <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/core/colors/palette-climacon.css')}}">
        <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/core/colors/palette-gradient.css')}}">
        <link rel="stylesheet" type="text/css" href="{{asset('app-assets/fonts/simple-line-icons/style.min.css')}}">
        <link rel="stylesheet" type="text/css" href="{{asset('assets/css/style.css')}}">
        <link rel="stylesheet" type="text/css" href="{{asset('assets/css/style.css')}}">
        <link rel="stylesheet" type="text/css" href="{{asset('app-assets/fonts/meteocons/style.min.css')}}">
        <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/pages/users.css')}}">
        <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/plugins/forms/wizard.css')}}">
        <link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/forms/toggle/switchery.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/plugins/forms/switch.css') }}">
        <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/pages/chat-application.css') }}">
        <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/summernote.min.css') }}">-->





        <!--<link rel="stylesheet" type="text/css" href="{{asset('assets/css/style.css')}}">-->
        <!-- END Page Level CSS-->
        <!-- BEGIN Custom CSS-->

    </head>

    <body class="vertical-layout vertical-menu 2-columns menu-expanded fixed-navbar" data-open="click"             
          data-menu="vertical-menu" data-col="content-left-sidebar"data-col="2-columns">
        <!-- fixed-top-->

        <!-- ////////////////////////////////////////////////////////////////////////////-->

        <div class="app-content content">

            <div class="content-wrapper">
                <div class="content-header row">
                    <div class="content-header-left col-md-6 col-12 mb-2">
                        <h3 class="content-header-title mb-0">GESTIÓN DE TEMAS</h3>
                        <div class="row breadcrumbs-top">
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{url('/Administracion')}}">Inicio</a>
                                    </li>
                                    <li  class="breadcrumb-item active">Crear Tema
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>     
                </div>

                <div class="content-body">
                    <section id="number-tabs">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">Crear Tema</h4>                    
                                    </div>
                                    <div class="card-content collapse show">
                                        <div class="card-body">

                                            <div class="row">
                                                <div class="col-md-12">
                                                    @if($errors->any())
                                                    <div class="alert alert-danger alert-dismissible show" role="alert" >
                                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                                        <h6 style="font: 16px EXODO;">Por favor corrige los siguientes errores:</h6>
                                                        <ul>
                                                            @foreach($errors->all() as $error)
                                                            <strong style="font: 15px EXODO;"><li>{{ $error }}</li></strong>                                        
                                                            @endforeach
                                                        </ul>
                                                    </div>        
                                                    @endif                    
                                                </div>
                                            </div>

                                            <p class="px-1"></p>

                                            <input type="hidden" class="form-control" name="tema_id" id="tema_id"  value="{{$Tema->id}}"/>
                                            <input type="hidden" class="form-control" id="ConsGrupPreg" value="2"/>
                                            <input type="hidden" class="form-control" id="ConsPregMul" value="1"/>
                                            <input type="hidden" class="form-control" id="ConsVerFal" value="2"/>
                                            <input type="hidden" class="form-control" id="Conslink" value="1"/>
                                            <input type="hidden" class="form-control" id="ConsOpcMul" value="2"/>
                                            <input type="hidden" class="form-control" id="ConsOpcMulPreg" value="2"/>
                                            <input type="hidden" class="form-control" id="Id_Eval" name="Id_Eval" value=""/>
                                            <input type="hidden" class="form-control" id="Nom_Video" name="Nom_Video" value=""/>
                                            <input type="hidden" class="form-control" id="tema_modulo"  value="{{$Tema->modulo}}"/>
                                            <input type="hidden" class="form-control" id="tema_periodo"  value="{{$Tema->periodo}}"/>
                                            <input type="hidden" class="form-control" id="tema_unidad"  value="{{$Tema->unidad}}"/>
                                            <h4 class="form-section"><i class="ft-grid"></i> Datos del Tema</h4>

                                            <div class="modal fade text-left" id="ModConfEval" tabindex="-1" role="dialog" aria-labelledby="myModalLabel15"
                                                 aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content border-success">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">Configuraciones de Evaluación</h4>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="card-body">
                                                                <div class="row">
                                                                    <div class="col-lg-6">
                                                                        <div class="form-group">
                                                                            <label class="form-label" for="porc_modulo">Intentos Permitidos:</label>
                                                                            <select class="form-control select2" style="width: 100%;"  data-placeholder="Seleccione" id="cb_intentosPer" name="cb_intentosPer">
                                                                                <option value="1">1</option>
                                                                                <option value="2">2</option>              
                                                                                <option value="3">3</option>              
                                                                                <option value="4">4</option>              
                                                                                <option value="5">5</option>              
                                                                                <option value="6">6</option>              
                                                                                <option value="7">7</option>              
                                                                                <option value="8">8</option>              
                                                                                <option value="9">9</option>              
                                                                                <option value="10">10</option>              
                                                                                <option value="0">Ilimitado</option>              
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-6">
                                                                        <div class="form-group">
                                                                            <label class="form-label" for="porc_modulo">Calificar Usando:</label>
                                                                            <select class="form-control select2" style="width: 100%;"  data-placeholder="Seleccione" id="cb_CalUsando" name="cb_CalUsando">
                                                                                <option value="Puntos">Puntos</option>
                                                                                <option value="Porcentaje">Porcentaje</option>
                                                                                <option value="Letra">Letra</option>              
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-4">
                                                                        <div class="form-group">
                                                                            <label class="form-label" for="porc_modulo">Puntos Máximos:</label>
                                                                            <input type="text" class="form-control" readonly=""  id="Punt_Max"  value="" name="Punt_Max"/>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" id="btn_salir" class="btn grey btn-outline-success" data-dismiss="modal"><i class="fa fa-check"></i> Aceptar</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="modal fade text-left" id="ModVidelo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel15"
                                                 aria-hidden="true">
                                                <div class="modal-dialog  modal-lg" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-success white">
                                                            <h4 class="modal-title"  style="text-transform: capitalize;" id="titu_temaEva">Contenido Didactico Cargado</h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div id='ListEval'  style="height: 400px; overflow: auto;text-align: center;">
                                                                <video width="640" height="360" id="datruta" controls  data-ruta="{{asset("/app-assets/Contenido_Didactico")}}">
                                                                </video>
                                                            </div>

                                                            <button type="button" id="btn_salir" class="btn grey btn-outline-secondary" data-dismiss="modal"><i class="ft-corner-up-left position-right"></i> Salir</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label" for="periodo">Periodo:</label>
                                                        <select class="form-control select2" onchange='$.CargUnidades(this.value)' data-placeholder="Seleccione" id="periodo" name="periodo">

                                                        </select>
                                                    </div>        
                                                </div>
                                                <div class="col-md-9">
                                                    <div class="form-group">
                                                        <label class="form-label" for="unidad">Unidad:</label>
                                                        <select class="form-control select2" data-placeholder="Seleccione" id='unidad' name="unidad">

                                                        </select>
                                                    </div>        
                                                </div>
                                                
                                                <div id="summernote"><p>Hello Summernote</p></div>
                                           
                                             
                                            </div>

                                            <div class="form-actions right">
                                                <div class="row ">
                                                    <div class="col-md-12 col-lg-12 ">
                                                        <div class="btn-list">

                                                            <button class="btn btn-outline-success" title="Visualizar" onclick="$.Visualizar();" type="button">
                                                                <i class="fa fa-eye"></i> Visualizar
                                                            </button>
                                                            <button class="btn btn-outline-primary" href="#" title="Guardar" type="submit">
                                                                <i class="fa fa-save"></i> Guardar
                                                            </button>
                                                            <a class="btn btn-outline-warning" href="{{ url('/Asignaturas/NuevoTema') }}" title="Cancelar">
                                                                <i class="fa fa-close"></i> Cancelar
                                                            </a>

                                                            <a class="btn btn-outline-dark" href="{{ url('/Asignaturas/GestionTem') }}" title="Volver">
                                                                <i class="fa fa-angle-double-left"></i> Volver
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--end::Form-->
                                            <p class="px-1"></p>
                                        </div>
                                    </div>
                                </div>  
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
        <!-- ////////////////////////////////////////////////////////////////////////////-->
        <footer class="footer footer-static footer-dark navbar-border">
            <p class="clearfix blue-grey lighten-2 text-sm-center mb-0 px-2">
                <span class="float-md-left d-block d-md-inline-block">Copyright &copy;<script>document.write(new Date().getFullYear());</script> <a href="#">PEDIGITAL</a>  <a class="text-bold-800 grey darken-2" href="#"
                                                                                                                                                                               target="_blank"> </a></span>
                <span class="float-md-right d-block d-md-inline-block d-none d-lg-block"></span>
            </p>
        </footer>
        
             <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
        
          <script>
    $(document).ready(function() {
        $('#summernote').summernote();
    });
  </script>
  

        <!-- BEGIN VENDOR JS-->
<!--        <script src="{{asset('app-assets/vendors/js/vendors.min.js')}}" type="text/javascript"></script>
         BEGIN VENDOR JS
         BEGIN PAGE VENDOR JS
        <script src="//maps.googleapis.com/maps/api/js?key=AIzaSyBDkKetQwosod2SZ7ZGCpxuJdxY3kxo5Po" type="text/javascript"></script>
        <script src="{{asset('app-assets/vendors/js/pickers/dateTime/moment-with-locales.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('app-assets/vendors/js/pickers/dateTime/bootstrap-datetimepicker.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('app-assets/vendors/js/pickers/daterange/daterangepicker.js')}}" type="text/javascript"></script>
        <script src="{{asset('app-assets/vendors/js/pickers/pickadate/picker.js')}}" type="text/javascript"></script>
        <script src="{{asset('app-assets/vendors/js/pickers/pickadate/picker.date.js')}}" type="text/javascript"></script>
        <script src="{{asset('app-assets/vendors/js/forms/validation/jquery.validate.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('app-assets/vendors/js/forms/spinner/jquery.bootstrap-touchspin.js')}}" type="text/javascript"></script>
        <script src="{{asset('app-assets/vendors/js/forms/validation/jqBootstrapValidation.js')}}" type="text/javascript"></script>
        <script src="{{asset('app-assets/vendors/js/forms/icheck/icheck.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('app-assets/vendors/js/extensions/jquery.knob.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('app-assets/vendors/js/extensions/unslider-min.js')}}" type="text/javascript"></script>
        <script src="{{asset('app-assets/vendors/js/forms/select/select2.full.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('app-assets/vendors/js/extensions/toastr.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('app-assets/vendors/js/extensions/jquery.steps.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('app-assets/vendors/js/extensions/sweetalert.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('app-assets/vendors/js/pickers/dateTime/bootstrap-datetimepicker.min.js')}}" type="text/javascript"></script>
         END PAGE VENDOR JS
         BEGIN STACK JS
        <script src="{{asset('app-assets/js/core/app-menu.js')}}" type="text/javascript"></script>
        <script src="{{asset('app-assets/js/core/app.js')}}" type="text/javascript"></script>
        <script src="{{asset('app-assets/js/scripts/customizer.js')}}" type="text/javascript"></script>
        <script src="{{asset('app-assets/js/scripts/modal/components-modal.js')}}" type="text/javascript"></script>

         END STACK JS
         BEGIN PAGE LEVEL JS
        <script src="{{asset('app-assets/js/scripts/forms/wizard-steps.js')}}" type="text/javascript"></script>
        <script src="{{asset('app-assets/js/scripts/forms/validation/form-validation.js')}}" type="text/javascript"></script>
        <script src="{{asset('app-assets/js/scripts/forms/checkbox-radio.js')}}" type="text/javascript"></script>
        <script src="{{asset('app-assets/js/scripts/forms/select/form-select2.js')}}" type="text/javascript"></script>
        <script src="{{asset('app-assets/js/scripts/pickers/dateTime/bootstrap-datetime.js')}}" type="text/javascript"></script>
        <script src="{{asset('app-assets/js/scripts/extensions/toastr.js')}}" type="text/javascript"></script>
        <script src="{{asset('assets/js/mensajes.js')}}" type="text/javascript"></script>
        <script src="{{asset('app-assets/js/scripts/extensions/sweet-alerts.js')}}" type="text/javascript"></script>
        <script src="{{asset('app-assets/eosMenu/eosMenu.js')}}" type="text/javascript"></script>
        <script src="{{asset('assets/js/wizard/jquery.smartWizard.js')}}" type="text/javascript"></script>
        <script src="{{asset('assets/js/wizard.js')}}" type="text/javascript"></script>
         END PAGE LEVEL JS

         SUMMERNOTE
        <script  src = "{{asset('app-assets/js/scripts/summernote.js')}}" type="text/javascript" ></script>
        <script src="{{asset('app-assets/js/scripts/summernote.min.js')}}" type="text/javascript"></script>
        <script src="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote.js"></script>


        <script src="{{ asset('app-assets/vendors/js/forms/toggle/bootstrap-checkbox.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('app-assets/vendors/js/forms/toggle/switchery.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('app-assets/js/scripts/forms/switch.js') }}" type="text/javascript"></script>
        <script src="{{ asset('app-assets/js/scripts/pages/chat-application.js') }}" type="text/javascript"></script>-->
    </body>

</html>