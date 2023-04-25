<?php
use Illuminate\Support\Facades\Route;

//Route::get('/Contenido/Contenido', function () {
//    return view('Contenido.Contenido');
//});

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/', 'UsuariosController@Inicio');
Route::post('/Login', 'UsuariosController@Login');
Route::get('/Administracion', 'UsuariosController@Administracion');
Route::get('/logout', 'UsuariosController@logout');
Route::get('/perfil', 'UsuariosController@perfil');
Route::post('/editarperfil', 'UsuariosController@editarperfil');
Route::get('/chat', 'UsuariosController@chat');
Route::get('/cambiarclave', 'UsuariosController@cambiarclave');
Route::post('/camcla', 'UsuariosController@camcla');

/////GESTION DE USUARIOS
Route::get('/Usuarios/Gestion/', 'UsuariosController@Gestion');
Route::get('/Usuarios/Nuevo', 'UsuariosController@GestionNuevo');
Route::post('/Usuarios/guardar', 'UsuariosController@GuardarUsu');
Route::get('/Usuarios/Editar/{id}', 'UsuariosController@editar');
Route::put('/Usuarios/modificar/{id}', 'UsuariosController@modificar');
Route::post('/Usuarios/ValUsuario', 'UsuariosController@ValUsuario');
Route::get('/MostrarVideo', 'UsuariosController@MostrarVideo');
Route::get('/Usuarios/RegistrosUsuarios', 'UsuariosController@RegistrosUsuarios');



/////GESTION DE CONTENIDO
Route::get('/Contenido/Contenido/{id}', 'ContenidoController@ContenidoPrograma');
Route::get('/Contenido/Presentacion/{id}', 'ContenidoController@PresentacionPrograma');
Route::get('/Contenido/ZonaLibre/{id}', 'ContenidoController@ZonaLibre');
Route::get('/Notificaciones/ComentEvaluacion', 'ContenidoController@VisuCometarios');
Route::post('/Notificaciones/ComentEvaluacion2', 'ContenidoController@VisuCometarios2');
Route::post('/Contenido/CargaCursos', 'ContenidoController@CargarCursos');
Route::post('/cambiar/Presetancion', 'ContenidoController@CambiarPresentacion');
Route::post('/cambiar/Grupo', 'ContenidoController@CambiarGrupo');
Route::post('/cambiar/Contenido', 'ContenidoController@CambiarContenido');
Route::post('/cambiar/ContenidoDocumento', 'ContenidoController@CambiarContenidoModal');
Route::post('/cambiar/ContenidoDocumentoLibre', 'ContenidoController@CambiarContenidoModaZonaLibre');
Route::post('/cambiar/ContenidoDidactico', 'ContenidoController@CambiarContenidoDidactico');
Route::post('/cambiar/ContenidoLink', 'ContenidoController@CambiarLinkModal');
Route::post('/cambiar/ContenidoArch', 'ContenidoController@CambiarArchModal');
Route::post('/cambiar/ContenidoVideo', 'ContenidoController@CambiarVideoModal');
Route::post('/cambiar/ContenidoArchZona', 'ContenidoController@CambiarArchModalZona');
Route::post('/cambiar/ContenidoEva', 'ContenidoController@CambiarEvalModal');
Route::post('/cambiar/ContenidoAct', 'ContenidoController@CambiarAct');
Route::post('/cambiar/ContenidoAnim', 'ContenidoController@CambiarAnim');
Route::post('/Guardar/Comentario', 'ContenidoController@GuardarComent');
Route::post('/Guardar/ComentarioDoce', 'ContenidoController@GuardarComentDoce');
Route::post('/Consultar/Comentario', 'ContenidoController@ConusulComent');
Route::post('/Consultar/ComentarioDoce', 'ContenidoController@ConusulComentDoce');
Route::post('/cambiar/vistoContenido', 'ContenidoController@vistoContenido');
Route::post('/cambiar/habilContenido', 'ContenidoController@HabiliContenido');
Route::post('/cambiar/MostContenido', 'ContenidoController@MostContenido');
Route::post('/Asignaturas/consulPregAlumno', 'ContenidoController@consulPregAlumno');
Route::post('/Guardar/RespEvaluaciones', 'ContenidoController@GuardarRespEvaluaciones');
Route::post('/Guardar/OrdenTemas', 'ContenidoController@OrdenTemas');


Route::post('/Guardar/Prueba', 'ContenidoController@GuardarRespEvalPrueba');

Route::post('/Guardar/RespEvalDida', 'ContenidoController@GuardarRespEvalDida');
Route::post('/Guardar/RespEvalMult', 'ContenidoController@GuardarRespEvalMul');
Route::post('/Guardar/RespEvalGrupPreg', 'ContenidoController@GuardarRespEvalGruPreg');
Route::post('/Guardar/RespEvalTall', 'ContenidoController@GuardarRespTall');
Route::post('/Guardar/RespEvalVerFal', 'ContenidoController@GuardarRespVerFal');
Route::post('/Guardar/RespEvalVerFal', 'ContenidoController@GuardarRespVerFal');
Route::post('/Guardar/RespEvalComplete', 'ContenidoController@GuardarRespComplete');
Route::post('/Guardar/RespEvalRel', 'ContenidoController@GuardarRespRelacione');
Route::post('/Guardar/RespEvalTaller', 'ContenidoController@GuardarRespTaller');

////CALIFICACIONES
Route::get('/Calificaciones/TableroCalificaciones/{id}', 'CalificaionesController@GestionCalificaciones');
Route::get('/Calificaciones/LibroCalificaciones/{id}', 'CalificaionesController@GestionLibroCalificaciones');
Route::get('/Calificaciones/EvaluarAlumnos/{id}', 'CalificaionesController@EvalAlumnos');
Route::get('/Calificaciones/CalifAlumno/{id}', 'CalificaionesController@CalifAlumno');
Route::get('/Calificaciones/PonderacionNotas/{id}', 'CalificaionesController@PonderacionNotas');
Route::post('/Calificaciones/VerEvaluacion', 'CalificaionesController@VerEvaluacion');
Route::post('/Calificaciones/ListCalifEva', 'CalificaionesController@ListCalifEva');
Route::post('/Calificaciones/ListCalifEva2', 'CalificaionesController@ListCalifEva2');
Route::post('/Consultar/RespEval', 'CalificaionesController@ConsulContEval');
Route::post('/Consultar/ListCalif', 'CalificaionesController@ConsListEval');
Route::post('/Guardar/CalEvaluacion', 'CalificaionesController@GuardarCalEval');
Route::post('/Guardar/GuardarPuntPreg', 'CalificaionesController@GuardarPuntPreg');
Route::post('/Guardar/CalfEvalGrupPreg', 'CalificaionesController@GuardarCalEvalGrupPreg');
Route::post('/Calificaciones/ListUnidades', 'CalificaionesController@ListUnidades');
Route::post('/Calificaciones/ListTemas', 'CalificaionesController@ListTemas');
Route::post('/Calificaciones/ListEval', 'CalificaionesController@ListEval');
Route::post('/Guardar/PorcPer', 'CalificaionesController@PorcPer');
Route::post('/Guardar/PorcUni', 'CalificaionesController@PorcUni');
Route::post('/Guardar/PorcTema', 'CalificaionesController@PorcTema');
Route::post('/Guardar/PorcEval', 'CalificaionesController@PorcEval');
Route::post('/Calificaciones/consulPregAlumno', 'CalificaionesController@consulPregAlumno');
Route::post('/Calificaciones/ConsulRetroalimentacion', 'CalificaionesController@ConsulRetroalimentacion');
Route::post('/Calificaciones/VerRespAlumno', 'CalificaionesController@VerRespAlumno');

////ALUMNOS
Route::get('/Alumnos/Gestion', 'AlumnosController@GestionAlumnos');
Route::get('/Alumnos/Nuevo', 'AlumnosController@GestionNuevo');
Route::post('/Alumnos/guardar', 'AlumnosController@Guardar');
Route::get('/Alumnos/Editar/{id}', 'AlumnosController@editar');
Route::get('/Alumnos/Consultar/{id}', 'AlumnosController@consultar');
Route::get('/Alumnos/Administrar', 'AlumnosController@Administrar');
Route::put('/Alumnos/modificar/{id}', 'AlumnosController@modificar');
Route::post('/Alumnos/Eliminar', 'AlumnosController@Eliminar');
Route::post('/Alumnos/BuscarAlumnos', 'AlumnosController@BuscarAlumnos');
Route::post('/Alumnos/AdministrarAlumnos', 'AlumnosController@AdministrarAlumnos');
Route::post('/Alumnos/ValAlumnos', 'AlumnosController@ValAlumnos');
Route::post('/Alumnos/ImportarAlumnos', 'AlumnosController@ImportarAlumnos');


////ASIGNATURAS
Route::get('/Asignaturas/GestionAsignaturas', 'AsignaturaController@GestionAsignaturas');
Route::get('/Asignaturas/GestionGrado', 'AsignaturaController@GestionGrado');
Route::get('/Asignaturas/NuevaAsig', 'AsignaturaController@NuevaAsig');
Route::get('/Asignaturas/NuevoModulo', 'AsignaturaController@NuevoModulo');
Route::post('/Asignaturas/guardarAsignatura', 'AsignaturaController@GuardarAsig');
Route::post('/Asignaturas/guardarAsignaturas', 'AsignaturaController@GuardarAsignaturas');
Route::get('/Asignaturas/EditarAsig/{id}', 'AsignaturaController@editar');
Route::get('/Asignaturas/EditarAsignaturas/{id}', 'AsignaturaController@EditarAsignatura');
Route::get('/Asignaturas/Consultar/{id}', 'AsignaturaController@consultarAsig');
Route::put('/Asignaturas/ModificarAsig/{id}', 'AsignaturaController@modificarAsig');
Route::put('/Asignaturas/ModificarAsignaturas/{id}', 'AsignaturaController@ModificarAsignaturas');
Route::post('/Asignaturas/Eliminar', 'AsignaturaController@EliminarAsig');
Route::post('/Asignaturas/EliminarAsig', 'AsignaturaController@EliminarAsignaturas');
Route::post('/DelImgMod/DelImgModulo', 'AsignaturaController@DelImgMod');
Route::post('/DelImgMod/DelImgAsig', 'AsignaturaController@DelImgAsig');
Route::post('/DelPerMod/DelPerModulo', 'AsignaturaController@DelPerMod');
Route::post('/Grupos/DelGruposAsig', 'AsignaturaController@DelGrupoAsig');
/////UNIDAD
Route::get('Asignaturas/GestionUnid', 'AsignaturaController@GestionUnidades');
Route::get('/Asignaturas/NuevaUnidad', 'AsignaturaController@GestionNuevaUnidad');
Route::post('/Asignaturas/guardarUnidad', 'AsignaturaController@guardarUnidad');
Route::post('/Asignaturas/CargarUnidadesReasignar', 'AsignaturaController@CargarUnidadesReasignar');
Route::post('/Asignaturas/ReasignarUnidades', 'AsignaturaController@ReasignarUnidades');
Route::get('/Asignaturas/EditarUnidad/{id}', 'AsignaturaController@editarUnidad');
Route::put('/Asignaturas/ModificarUnidad/{id}', 'AsignaturaController@modificarUnidad');
Route::post('/Asignaturas/EliminarUnidad', 'AsignaturaController@EliminarUnidad');
Route::get('/Asignaturas/ConsultarUnidad/{id}', 'AsignaturaController@consultarUnidad');


/////TEMAS
Route::get('/Asignaturas/GestionTem', 'AsignaturaController@GestionTemas');
Route::get('/Asignaturas/NuevoTema', 'AsignaturaController@GestionNuevoTema');
Route::post('/Asignaturas/guardarTemas', 'AsignaturaController@guardarCont');
Route::get('/Asignaturas/EditarTema/{id}', 'AsignaturaController@editarTema');
Route::put('/Asignaturas/ModificarTema/{id}', 'AsignaturaController@ModificarTema');
Route::post('/Asignaturas/ElimnarTema', 'AsignaturaController@EliminarTema');
Route::post('/Asignaturas/consulTemaPDF', 'ContenidoController@ConsultarTemaPDF');
Route::post('/Asignaturas/CargarTemasReasignar', 'AsignaturaController@CargarTemasReasignar');
Route::post('/Asignaturas/ReasignarTemas', 'AsignaturaController@ReasignarTemas');


//////////ZONA LIBRE
Route::get('/Asignaturas/ZonaLibre', 'ZonaLibre@GestionZonaLibre');
Route::get('/Asignaturas/NuevaZona', 'ZonaLibre@NuevaZonaLibre');
Route::post('/Asignaturas/guardarTemasZonaLibre', 'ZonaLibre@guardarZonaLibre');
Route::post('/Consultar/ContenidoAnimZonaLibre', 'ContenidoController@CambiarAnimZonaLibre');
Route::post('/Consultar/ContenidoLinkZonaLibre', 'ContenidoController@CambiarLinkZonaLibre');
Route::get('/Asignaturas/EditarTemaLibre/{id}', 'ZonaLibre@editarTemaLibre');
Route::post('/BuscarInf/DocumentosZonaLibre', 'ZonaLibre@InfDocumentosZonaLibre');
Route::post('/BuscarInf/ComentariosZonaLibre', 'ZonaLibre@InfComentariosZonaLibre');
Route::post('/BuscarInf/LinksZonaLibre', 'ZonaLibre@InfLinksZonaLibre');
Route::post('/BuscarInf/VideosZonaLibre', 'ZonaLibre@InfVideoZonaLibre');
Route::post('/Asignaturas/ElimnarTemaZona', 'ZonaLibre@EliminarTemaZona');
Route::put('/Asignaturas/ModificarZonaLibre/{id}', 'ZonaLibre@ModificarZonaLibre');
Route::post('/Guardar/ComentarioZona', 'ContenidoController@GuardarComentZona');
Route::post('/Consultar/ComentarioZona', 'ContenidoController@ConusulComentZona');
Route::post('/Consultar/ContZonaLibre', 'ContenidoController@ZonaLibreDoce');
Route::post('/ZonaLibre/DelArchivosVideo', 'ZonaLibre@DelArchivosVideo');


/////////GESTIONAR EVALUACIONES TEMAS
Route::get('/Asignaturas/AsigEvaluacion/{id}', 'AsignaturaController@AsigEvaluacion');
Route::get('/Asignaturas/GestionAsigEvaluacion/{id}', 'AsignaturaController@GestionAsigEvaluacion');
Route::post('/Asignaturas/guardarEvaluacion', 'AsignaturaController@guardarEvaluacion');
Route::get('/Asignaturas/EditarEvaluacion/{id}', 'AsignaturaController@EditarEval');
Route::put('/Asignaturas/ModificarEva/{id}', 'AsignaturaController@ModificarEva');
Route::post('/Asignaturas/EliminarEval', 'AsignaturaController@EliminarEval');
Route::post('/Asignaturas/consulEvalPreg', 'AsignaturaController@consulEvalPreg');
Route::post('/Asignaturas/ElimnarPreg', 'AsignaturaController@ElimnarPreg');
Route::post('/Guardar/VideoEval', 'AsignaturaController@VideoEval');
Route::post('/Asignaturas/GuardarEvalFin', 'AsignaturaController@GuardarEvalFin');
Route::post('/Asignaturas/CargarEvaluacion', 'AsignaturaController@ConsulEval');
Route::post('/Asignaturas/CargarEvalReasignar', 'AsignaturaController@CargarEvalReasignar');
Route::post('/Asignaturas/ReasignarEval', 'AsignaturaController@ReasignarEval');


///////GESTIONAR LABORATORIOS
Route::get('/Laboratorios/GestionLaboratorios/', 'LaboratoriosController@GestionLaboratorios');
Route::get('/Laboratorios/NuevoLaboratorio', 'LaboratoriosController@NuevoLaboratorio');
Route::post('/Laboratorios/GuardarLaboratorio', 'LaboratoriosController@GuardarLaboratorio');
Route::get('/Laboratorios/EditarLaboratorio/{id}', 'LaboratoriosController@EditarLaboratorio');
Route::post('/Laboratorios/BuscaInfLaboratorio', 'LaboratoriosController@InfLaboratorio');
Route::put('/Laboratorios/ModificarLabo/{id}', 'LaboratoriosController@ModificarLaboratorio');
Route::post('/Laboratorios/ElimnarLabo', 'LaboratoriosController@EliminarLabo');

Route::get('/Laboratorios/GestionAsigEvaluacion/{id}', 'LaboratoriosController@GestionAsigEvaluacion');
Route::get('/Laboratorios/AsigEvaluacion/{id}', 'LaboratoriosController@AsigEvaluacion');
Route::get('/Laboratorios/ListLaboratorios/{id}', 'LaboratoriosController@ListLaboratorios');
Route::post('/Laboratorios/MostLaboratorios', 'LaboratoriosController@MostLaboratorios');
Route::post('/Laboratorios/MostDetLaboratorios', 'LaboratoriosController@MostDetLaboratorios');
Route::post('/Laboratorios/ContenidoEva', 'ContenidoController@CambiarEvalModal');

/////////GESTIONAR EVALUACIONES LABORATORIO
Route::post('/Laboratorios/GuardarEvaluacion', 'LaboratoriosController@GuardarEvaluacion');
Route::get('/Laboratorios/EditarEvaluacion/{id}', 'LaboratoriosController@EditarEval');
Route::put('/Laboratorios/ModificarEva/{id}', 'LaboratoriosController@ModificarEva');
Route::post('/Laboratorios/EliminarEval', 'LaboratoriosController@EliminarEval');

////////////

Route::post('/cambiar/Periodos', 'AsignaturaController@CambiarPeriodo');
Route::post('/cambiar/Periodos2', 'AsignaturaController@CambiarPeriodo2');
Route::post('/cambiar/Unidad', 'AsignaturaController@CambiarUnidad');
Route::post('/cambiar/docentes', 'AsignaturaController@cambiarCompDocentes');
Route::post('/cambiar/docentesEdit', 'AsignaturaController@cambiarCompDocentesEdit');
Route::post('/cambiar/Temas', 'AsignaturaController@CambiarTema');
Route::post('/cambiar/Evalulacione', 'AsignaturaController@CambiarEvaluaciones');
Route::post('/cambiar/Unidad2', 'AsignaturaController@CambiarUnidad2');
Route::post('/cambiar/Asignaturas', 'AsignaturaController@CambiarAsignaturas');
Route::post('/cambiar/Asignatura2', 'AsignaturaController@CambiarAsignaturas2');
Route::post('/BuscarInf/Documentos', 'AsignaturaController@InfDocumentos');
Route::post('/BuscarInf/DocumentosDida', 'AsignaturaController@InfDocumentosDida');
Route::post('/BuscarInf/Archivos', 'AsignaturaController@InfArchivos');
Route::post('/DelArcTema/DelArchivos', 'AsignaturaController@DelArchivos');
Route::post('/DelArcTaller/DelArchivosTaller', 'AsignaturaController@DelArchivosTaller');
Route::post('/DelAnimTema/DelAnimacion', 'AsignaturaController@DelAnimacion');
Route::post('/BuscarInf/Links', 'AsignaturaController@InfLinks');
Route::post('/BuscarInf/Eval', 'AsignaturaController@InfEval');
Route::post('/DelAnimTema/DelVideo', 'AsignaturaController@DelArchivoVideo');


////PROFESORES
Route::get('/Profesores/Gestion', 'ProfesoresController@GestionProfesores');
Route::get('/Profesores/Nuevo', 'ProfesoresController@GestionNuevo');
Route::post('/Profesores/guardar', 'ProfesoresController@Guardar');
Route::post('/Profesores/guardarAsigProf', 'ProfesoresController@GuardarAsigProf');
Route::post('/Profesores/guardarModProf', 'ProfesoresController@GuardarModProf');
Route::get('/Profesores/Editar/{id}', 'ProfesoresController@editar');
Route::get('/Profesores/AddAsignatura/{id}', 'ProfesoresController@AddAsig');
Route::get('/Profesores/AddModulos/{id}', 'ProfesoresController@AddMod');
Route::put('/Profesores/modificar/{id}', 'ProfesoresController@modificar');
Route::get('/Profesores/Consultar/{id}', 'ProfesoresController@consultar');
Route::post('/Profesores/Eliminar', 'ProfesoresController@Eliminar');
Route::post('/Profesores/ListarGrados', 'ProfesoresController@ListarGrados');
Route::post('/Profesores/ListarGradosMod', 'ProfesoresController@ListarGradosMod');
Route::post('/Profesores/ListarGrupos', 'ProfesoresController@ListarGrupos');
Route::post('/Profesores/ListarGruposMod', 'ProfesoresController@ListarGruposMod');
Route::post('/Profesores/VerifAsigAsig', 'ProfesoresController@VerifAsigAsign');
Route::post('/Profesores/VerifAsigMod', 'ProfesoresController@VerifAsigMod');
Route::post('/Profesores/ValProfesor', 'ProfesoresController@ValProfesor');
Route::post('/Profesores/CargaAcademica', 'ProfesoresController@CargaAcademica');
Route::post('/Profesores/DelASigDocente', 'ProfesoresController@DelASigDocente');
Route::post('/Profesores/DelModDocente', 'ProfesoresController@DelModDocente');

////////////CHAT
Route::post('/guardarchat', 'UsuariosController@guardarchat');
Route::post('/cargar', 'UsuariosController@cargar');
Route::post('/cargarUsuarios', 'UsuariosController@cargarUsuarios');
Route::post('/guardarDifusion', 'UsuariosController@guardarDifusion');

///////////FOROS
Route::get('/Foro/Gestion', 'ForoController@Gestion');
Route::get('/Foro/Nuevo', 'ForoController@Nuevo');
Route::post('/Foro/guardar', 'ForoController@Guardar');
Route::get('/Foro/Editar/{id}', 'ForoController@editar');
Route::put('/Foro/modificar/{id}', 'ForoController@modificar');
Route::post('/Foro/Eliminar', 'ForoController@Eliminar');
Route::get('/Foro/Comentarios/{id}', 'ForoController@Comentarios');
Route::post('/Comentarios/guardar', 'ForoController@GuardarComentarios');
Route::post('/Foros/ConsulContenido', 'ForoController@ConsulContenido');


/////ASISTENCIA
Route::get('/Profesores/asistencia', 'ProfesoresController@asistencia');
Route::get('/Profesores/asistenciaMod', 'ProfesoresController@asistenciaMod');
Route::post('/Asistencia/guardar', 'ProfesoresController@GuardarAsistencia');
Route::post('/Consultar/Asitencia', 'ProfesoresController@ConsutarAsistencia');
Route::post('/Asistencia/ListAsitencia', 'ProfesoresController@ListAsitencia');


/////ESTADISTICAS
Route::get('/Gestion/EstadisticasAdmin', 'UsuariosController@EstadisticaAdmin');
Route::post('/Gestion/InfGeneral', 'UsuariosController@EstadisticaGeneral');


///////GESTIONAR MODULOS
Route::get('/Modulos/GestionModulos', 'ModulosController@GestionModulos');
Route::get('/Modulos/NuevoModulo', 'ModulosController@NuevoModulo');
Route::post('/Modulos/GuardarModulos', 'ModulosController@GuardarModulo');
Route::get('/Modulos/EditarModulo/{id}', 'ModulosController@EditarModulos');
Route::put('/Modulos/ModificarModulos/{id}', 'ModulosController@ModificarModulos');
Route::post('/DelImgModTransv/DelImgModulo', 'ModulosController@DelImgModulo');
Route::post('/Modulos/EliminarModulo', 'ModulosController@EliminarModTransv');

//////GESTIONAR GRADOS MODULOS TRANSVERSALES
Route::get('/Modulos/GestionGradosModTransv', 'ModulosController@GestionGradosModTransv');
Route::get('/Modulos/NuevoGrado', 'ModulosController@NuevoGradoModTransv');
Route::post('/Modulos/GuardarGradoModTransv', 'ModulosController@GuardarGradoModTransv');
Route::get('/Modulos/EditarGradModTransv/{id}', 'ModulosController@EditarGradModTransv');
Route::put('/Modulos/ModificarGradModTransv/{id}', 'ModulosController@ModificarGradModTransv');
Route::post('/DelImgModTransv/DelImgModuloGrado', 'ModulosController@DelImgModuloGrado');

Route::post('/DelPerModTransv/DelPerModulo', 'ModulosController@DelPerModTransv');
Route::post('/Modulos/EliminarGradModTransv', 'ModulosController@EliminarGradModTransv');
Route::post('/Grupos/DelGruposMod', 'ModulosController@DelGruposMod');

///////////GESTIONAR UNIDADES MODULOS TRANSVERSALES
Route::get('/Modulos/GestionUnid', 'ModulosController@GestionUnidades');
Route::get('/Modulos/NuevaUnidad', 'ModulosController@GestionNuevaUnidad');
Route::post('/Modulos/guardarUnidad', 'ModulosController@guardarUnidad');
Route::get('/Modulos/EditarUnidad/{id}', 'ModulosController@editarUnidad');
Route::put('/Modulos/ModificarUnidad/{id}', 'ModulosController@modificarUnidad');
Route::post('/Modulos/EliminarUnidad', 'ModulosController@EliminarUnidad');
Route::get('/Modulos/ConsultarUnidad/{id}', 'ModulosController@consultarUnidad');
Route::post('/cambiar/PeriodosModulos', 'ModulosController@CambiarPeriodo');
Route::post('/cambiar/PeriodosUnidades2', 'ModulosController@CambiarPeriodo2');
Route::post('/Modulos/CargarUnidadesReasignar', 'ModulosController@CargarUnidadesReasignar');
Route::post('/Modulos/ReasignarUnidades', 'ModulosController@ReasignarUnidades');


/////////////GESTIONAR TEMAS MÓDULOS TRANSVERSALES
Route::get('/Modulos/GestionTem', 'ModulosController@GestionTemas');
Route::get('/Modulos/NuevoTema', 'ModulosController@GestionNuevoTema');
Route::post('/Modulos/guardarTemas', 'ModulosController@guardarCont');
Route::get('/Modulos/EditarTema/{id}', 'ModulosController@editarTema');
Route::put('/Modulos/ModificarTema/{id}', 'ModulosController@ModificarTema');
Route::post('/Modulos/ElimnarTema', 'ModulosController@EliminarTema');
Route::post('/cambiar/UnidadModulos', 'ModulosController@CambiarUnidad');
Route::post('/cambiar/Unidad2Modulos', 'ModulosController@CambiarUnidad2');
Route::post('/BuscarInf/DocumentosModulos', 'ModulosController@InfDocumentos');
Route::post('/BuscarInf/ArchivosModulos', 'ModulosController@InfArchivos');
Route::post('/BuscarInf/DocumentosDidaModu', 'ModulosController@InfDocumentosDida');

Route::post('/DelArcTema/DelArchivosModulos', 'ModulosController@DelArchivos');
Route::post('/DelAnimTema/DelAnimacionModulos', 'ModulosController@DelAnimacion');
Route::post('/BuscarInf/LinksModulos', 'ModulosController@InfLinks');

//buscar infromación de temas compartidos
Route::post('/cambiar/docentesMod', 'ModulosController@cambiarCompDocentes');
Route::post('/cambiar/docentesEditMod', 'ModulosController@cambiarCompDocentesEdit');

Route::post('/Modulos/consulTemaPDF', 'ContenidoModuloController@ConsultarTemaPDF');
Route::post('/Modulos/CargarTemasReasignar', 'ModulosController@CargarTemasReasignar');
Route::post('/Modulos/ReasignarTemas', 'ModulosController@ReasignarTemas');


////////////GESTIONAR EVALUACIONES MODULOS TRANSVERSALES
Route::get('/Modulos/AsigEvaluacion/{id}', 'ModulosController@AsigEvaluacion');
Route::get('/Modulos/GestionAsigEvaluacion/{id}', 'ModulosController@GestionAsigEvaluacion');
Route::post('/Modulos/guardarEvaluacion', 'ModulosController@guardarEvaluacion');
Route::get('/Modulos/EditarEvaluacion/{id}', 'ModulosController@EditarEval');
Route::put('/Modulos/ModificarEva/{id}', 'ModulosController@ModificarEva');
Route::post('/Modulos/EliminarEval', 'ModulosController@EliminarEval');
Route::post('/DelAnimTema/DelVideoModu', 'ModulosController@DelArchivoVideo');
Route::post('/Modulos/CargarEvalReasignar', 'ModulosController@CargarEvalReasignar');
Route::post('/Modulos/ReasignarEval', 'ModulosController@ReasignarEval');


/////GESTIÓN CONTENIDO MODULOS TRANSVERSALES
Route::post('/Contenido/CargaCursosMod', 'ContenidoModuloController@CargarCursos');
Route::get('/Contenido/ContenidoMod/{id}', 'ContenidoModuloController@ContenidoPrograma');
Route::get('/Contenido/PresentacionMod/{id}', 'ContenidoModuloController@PresentacionPrograma');
Route::post('/cambiar/PresetancionMod', 'ContenidoModuloController@CambiarPresentacion');
Route::post('/cambiar/GrupoMod', 'ContenidoModuloController@CambiarGrupo');
Route::post('/cambiar/ContenidoMod', 'ContenidoModuloController@CambiarContenido');
Route::post('/cambiar/ContenidoDocumentoMod', 'ContenidoModuloController@CambiarContenidoModal');
Route::post('/cambiar/ContenidoActMod', 'ContenidoModuloController@CambiarAct');
Route::post('/cambiar/ContenidoAnimMod', 'ContenidoModuloController@CambiarAnim');
Route::post('/cambiar/vistoContenidoMod', 'ContenidoModuloController@vistoContenido');
Route::post('/cambiar/habilContenidoMod', 'ContenidoModuloController@HabiliContenido');
Route::post('/cambiar/MostContenidoMod', 'ContenidoModuloController@MostContenido');
Route::post('/cambiar/ContenidoVideoMod', 'ContenidoModuloController@CambiarVideoModal');
Route::post('/ModuloTV/ContenidoEva', 'ContenidoModuloController@CambiarEvalModal');
Route::post('/ModuloTv/RespEvaluaciones', 'ContenidoModuloController@GuardarRespEvaluaciones');
Route::post('/Guardar/OrdenTemasModulos', 'ContenidoModuloController@OrdenTemas');


////////// GESTIONAR MODULO E ///////////////////////////////////////

////Gestionar asignaturas
Route::get('/ModuloE/GestionAsignaturas', 'ModuloEController@GestionAsignatura');
Route::get('/ModuloE/NuevaAsignatura', 'ModuloEController@NuevaAsignatura');
Route::post('/ModuloE/GuardarAsignaturas', 'ModuloEController@GuardarAsignaturas');
Route::get('/ModuloE/EditarAsignatura/{id}', 'ModuloEController@EditarAsignatura');
Route::put('/ModuloE/ModificarAsignaturas/{id}', 'ModuloEController@ModificarAsignatura');
Route::post('/ModuloE/EliminarAsignatura', 'ModuloEController@EliminarAsignaturas');
Route::post('/ModuloE/CargcompetenciasAsig', 'ModuloEController@CargcompetenciasAsig');
Route::post('/ModuloE/CargInfAsig', 'ModuloEController@CargInfAsig');


////Gestionar competencias
Route::get('/ModuloE/GestionCompetencia', 'ModuloEController@GestionCompetencia');
Route::get('/ModuloE/NuevaCompetencia', 'ModuloEController@NuevaCompetencia');
Route::post('/ModuloE/GuardarCompetencia', 'ModuloEController@GuardarCompetencia');
Route::get('/ModuloE/EditarCompetencia/{id}', 'ModuloEController@EditarCompetencia');
Route::put('/ModuloE/ModificarCompetencia/{id}', 'ModuloEController@ModificarCompetencia');
Route::post('/ModuloE/EliminarCompetencia', 'ModuloEController@EliminarCompetencia');

/////////Gestionar Componentes
Route::get('/ModuloE/GestionComponentes', 'ModuloEController@GestionComponentes');
Route::get('/ModuloE/NuevoComponente', 'ModuloEController@NuevoComponente');
Route::post('/ModuloE/GuardarComponente', 'ModuloEController@GuardarComponente');
Route::get('/ModuloE/EditarComponente/{id}', 'ModuloEController@EditarComponente');
Route::put('/ModuloE/ModificarComponente/{id}', 'ModuloEController@ModificarComponente');
Route::post('/ModuloE/EliminarComponentes', 'ModuloEController@EliminarComponentes');


////////Gestionar Banco de Preguntas
Route::get('/ModuloE/GestionBancoPreguntas', 'ModuloEController@GestionBancoPreguntas');
Route::get('/ModuloE/NuevaPregunta', 'ModuloEController@NuevaPregunta');
Route::post('/ModuloE/CargTemas', 'ModuloEController@CargTemas');
Route::post('/ModuloE/CargPartes', 'ModuloEController@CargPartes');
Route::post('/ModuloE/Cargcompe_compo', 'ModuloEController@Cargcompe_compo');
Route::post('/ModuloE/GuardarPreguntas', 'ModuloEController@GuardarPreguntas');
Route::post('/ModuloE/ConsulPregME', 'ModuloEController@ConsulPregME');
Route::post('/ModuloE/GuardarBancoPregFin', 'ModuloEController@GuardarEvalFinBanc');
Route::post('/ModuloE/ElimnarPreg', 'ModuloEController@ElimnarPregSimu');
Route::get('/ModuloE/EditarPregBanco/{id}', 'ModuloEController@EditarBancoPreg');
Route::post('/ModuloE/CargarPreguntas', 'ModuloEController@ConsulPreguntas');
Route::post('/ModuloE/ConsulPreguntas', 'ModuloEController@ConsulPreguntasPractiquemos');
Route::post('/ModuloE/EliminarBancoPregunta', 'ModuloEController@EliminarBancoPregunta');


////Gestionar temas
Route::get('/ModuloE/GestionTema', 'ModuloEController@GestionTema');
Route::get('/ModuloE/NuevoTema', 'ModuloEController@NuevoTema');
Route::post('/ModuloE/GuardarTemas', 'ModuloEController@GuardarTemas');
Route::get('/ModuloE/EditarTema/{id}', 'ModuloEController@EditarTema');
Route::post('/ModuloE/BuncInfContenido', 'ModuloEController@InfContenido');
Route::put('/ModuloE/ModificarTema/{id}', 'ModuloEController@ModificarTema');
Route::post('/DelImgModE/DelImgModuloE', 'ModuloEController@DelImgModuloE');
Route::post('/ModuloE/EliminarTema', 'ModuloEController@EliminarTema');
Route::post('/DelvidModE/DelVidModuloE', 'ModuloEController@DelVidModuloE');
Route::post('/ModuloE/ConsulAnimaModE', 'ModuloEController@ConsulAnimaModE');
Route::post('/ModuloE/CargarCompTema', 'ModuloEController@CargarCompTema');


////Gestionar Practicas Módulo E
Route::get('/ModuloE/ConsulPrePract/{id}', 'ModuloEController@GestionPracticas');
Route::get('/ModuloE/NuevaPractica/{id}', 'ModuloEController@NuevaPractica');
Route::post('/ModuloE/CargTemasPractica', 'ModuloEController@CargTemasPractica');
Route::post('/ModuloE/guardarEvaluacion', 'ModuloEController@guardarEvaluacion');
Route::post('/ModuloE/consulEvalPreg', 'ModuloEController@consulEvalPreg');
Route::post('/ModuloE/ElimnarPreg', 'ModuloEController@ElimnarPreg');
Route::post('/ModuloE/ElimnarPregBancoPreg', 'ModuloEController@ElimnarPregBancoPreg');
Route::post('/ModuloE/GuardarEvalFin', 'ModuloEController@GuardarEvalFin');
Route::post('/ModuloE/CargarEvaluacion', 'ModuloEController@ConsulEval');
Route::get('/ModuloE/EditarPregPract/{id}', 'ModuloEController@EditarEval');
Route::post('/ModuloE/EliminarEval', 'ModuloEController@EliminarEval');
Route::post('/ModuloE/ComponetesAsignatura', 'ModuloEController@ComponetesAsignatura');



////// Contenido módulo
Route::get('/ModuloE/CargarContModuloE', 'ModuloEController@CargarContModuloE');
Route::get('/ModuloE/CargarAsigContModuloE/{id}', 'ModuloEController@CargarAsigContModuloE');
Route::get('/ModuloE/CargarSimuContModuloE', 'ModuloEController@CargarSimuContModuloE');
Route::post('/ModuloE/CargarTemasModuloE', 'ModuloEController@CargarTemasModuloE');
Route::post('/ModuloE/CargaDetTemasModuloE', 'ModuloEController@CargaDetTemasModuloE');

//// contenido Practica
Route::post('/ModuloE/ContenidoPrueba', 'ModuloEController@ContenidoPrueba');
Route::post('/ModuloE/CargarPracticas', 'ModuloEController@CargarPracticas');
Route::post('/ModuloE/ContenidoEva', 'ModuloEController@CambiarEvalModal');
Route::post('/ModuloE/consulPregAlumno', 'ModuloEController@consulPregAlumno');
Route::post('/ModuloE/consulPregAlumnoSimu', 'ModuloEController@consulPregAlumnoSimu');
Route::post('/ModuloE/RespEvaluaciones', 'ModuloEController@GuardarRespEvaluaciones');
Route::post('/ModuloE/RespSimulacro', 'ModuloEController@RespSimulacro');
Route::post('/ModuloE/GuardarSesionTiempo', 'ModuloEController@GuardarSesionTiempo');


/////// Gestionar Simulacros
Route::get('/ModuloE/GestionSimulacros', 'ModuloEController@GestionSimulacros');
Route::get('/ModuloE/NuevoSimulacro', 'ModuloEController@NuevoSimulacro');
Route::post('/ModuloE/GuardarSimulacro', 'ModuloEController@GuardarSimulacro');
Route::post('/ModuloE/GuardarSesionsimulacro', 'ModuloEController@GuardarSesionsimulacro');
Route::post('/ModuloE/GuardarDetaSesion', 'ModuloEController@GuardarDetaSesion');
Route::post('/ModuloE/ModificarDetaSesion', 'ModuloEController@ModificarDetaSesion');
Route::post('/ModuloE/GuardarSesionEstudiante', 'ModuloEController@GuardarSesionEstudiante');

Route::get('/ModuloE/EditarSimulacro/{id}', 'ModuloEController@EditarSimulacro');
Route::put('/ModuloE/ModificarSimu/{id}', 'ModuloEController@ModificarSimu');
Route::post('/ModuloE/CargAreas', 'ModuloEController@CargAreas');
Route::post('/ModuloE/GuardarAreaSimu', 'ModuloEController@GuardarAreaSimu');
Route::post('/ModuloE/GenerPregArea', 'ModuloEController@GenerPregArea');
Route::post('/ModuloE/GuardarAreaSimuPreg', 'ModuloEController@GuardarAreaSimuPreg');
Route::post('/ModuloE/CargAreasxSesiones', 'ModuloEController@CargAreasxSesiones');
Route::post('/ModuloE/Cargcompetencias', 'ModuloEController@Cargcompetencias');
Route::post('/ModuloE/CargInfAreaSesion', 'ModuloEController@CargInfAreaSesion');
Route::post('/ModuloE/EliminarSimulacro', 'ModuloEController@EliminarSimulacro');
Route::post('/ModuloE/ConsultarSesionesSimulacros', 'ModuloEController@ConsultarSesionesSimulacros');
Route::post('/ModuloE/EliminarAreaSesion', 'ModuloEController@EliminarAreaSesion');
Route::post('/ModuloE/EliminarSesion', 'ModuloEController@EliminarSesion');
Route::post('/ModuloE/ConsultarSimulacros', 'ModuloEController@ConsultarSimulacros');
Route::post('/ModuloE/ConsultarSesiones', 'ModuloEController@ConsultarSesiones');
Route::post('/ModuloE/ConsultarAreasxSesion', 'ModuloEController@ConsultarAreasxSesion');
Route::post('/ModuloE/ConsultarPreguntasAreas', 'ModuloEController@ConsultarPreguntasAreas');
Route::post('/ModuloE/EliminarPregSesionArea', 'ModuloEController@EliminarPregSesionArea');
Route::post('/ModuloE/CargaPregCompexCompo', 'ModuloEController@CargaPregCompexCompo');
Route::post('/ModuloE/PreguntasxBanco', 'ModuloEController@PreguntasxBanco');


////Áreas
Route::get('/Asignaturas/GestionAreas', 'AsignaturaController@GestionAreas');
Route::get('/Asignaturas/NuevaArea', 'AsignaturaController@NuevaArea');
Route::post('/Asignaturas/guardarArea', 'AsignaturaController@GuardarArea');
Route::get('/Asignaturas/EditarArea/{id}', 'AsignaturaController@EditarArea');
Route::put('/Asignaturas/ModificarArea/{id}', 'AsignaturaController@ModificarArea');
Route::post('/Asignaturas/EliminarArea', 'AsignaturaController@EliminarArea');


/////Configurar Parametros
Route::get('/ConfParametros/ConfParametros', 'UsuariosController@ConfParametros');
Route::post('/ConfParametros/RestablecerInf', 'UsuariosController@RestablecerInf');
Route::post('/Parametros/GuardarParametros', 'UsuariosController@GuardarParametros');
Route::post('/Parametros/CargarParametros', 'UsuariosController@CargarParametros');
Route::post('/ConfParametros/InfGeneralColeg', 'UsuariosController@InfGeneralColeg');
Route::post('/ConfParametros/ActualizarInformacionColeg', 'UsuariosController@ActualizarInformacionColeg');


////MODULO DE JUEGO
Route::get('/Contenido/ZonaPlay/{alu}', 'ContenidoController@ZonaPlay');



