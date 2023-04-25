@extends('Plantilla.PrincipalZonaPlay')
@section('title', 'Zona Play')
@section('Contenido')
  


    <div class="content-body">
        <input type="hidden" name="grado" id="grado" value="{{ $Gradoalumno }}" />

        <iframe style="margin-top: 67px" id="myIframe" src="{{ asset('juegos/Index.html?grado=') }}{{$Gradoalumno}}" frameborder="0" scrolling="yes" height="678" width="100%" name="demo">
    
        </iframe>
       
    </div>

 

@endsection
@section('scripts')
    <script>
        $(document).ready(function() {

            var valorasistencia = "";
            $("#Men_Inicio").removeClass("active");
            $("#Men_Presentacion").removeClass("active");
            $("#Men_Asistencia").addClass("active open");
          
       
        });
    </script>
@endsection
