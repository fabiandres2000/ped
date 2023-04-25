<!-- - var navbarShadow = true-->
<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
@include('Plantilla.Head')

<body id="bodyPrincipal" data-open="click"             
    data-menu="vertical-menu" data-col="content-left-sidebar" data-col="2-columns">
    <!-- fixed-top-->
    @include('Plantilla.Cabecera')
    <!-- ////////////////////////////////////////////////////////////////////////////-->
    @if (Session::get('ZonaJuegoAct') == 'no')
    @include('Plantilla.Menu')
    @endif
    <div class="app-content content">
        @yield('CHAT')
      
        <div class="content-wrapper" style="padding: 0px !important">
            @yield('Contenido')
        </div>
    </div>
    <!-- ////////////////////////////////////////////////////////////////////////////-->
    @include('Plantilla.Footer')
    @yield('scripts')
</body>

</html>