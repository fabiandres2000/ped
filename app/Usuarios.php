<?php

namespace App;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class Usuarios extends Model
{

    protected $table = 'users';
    protected $fillable = [
        'nombre_usuario',
        'login_usuario',
        'pasword_usuario',
        'tipo_usuario',
        'email_usuario',
        'estado_usuario',
        'grado_usuario',
    ];

    public static function Gestion($busqueda, $pagina, $limit)
    {
        if ($pagina == "1") {
            $offset = 0;
        } else {
            $pagina--;
            $offset = $pagina * $limit;
        }
    
        if (!empty($busqueda)) {
            $respuesta = Usuarios::where('estado_usuario', 'ACTIVO')
                ->where(function ($query) use ($busqueda) {
                    $query->where('login_usuario', 'LIKE', '%' . $busqueda . '%')
                        ->orWhere('nombre_usuario', 'LIKE', '%' . $busqueda . '%');
                })
                ->select('users.*')
                ->orderBy('nombre_usuario', 'ASC')
                ->limit($limit)->offset($offset);
        } else {
            $respuesta = Usuarios::where('estado_usuario', 'ACTIVO')
                ->select('users.*')
                ->orderBy('nombre_usuario', 'ASC')
                ->limit($limit)->offset($offset);
        }
     
        return $respuesta->get();
    }

    public static function numero_de_registros($busqueda)
    {
        if (!empty($busqueda)) {
            $respuesta = Usuarios::where('estado_usuario', 'ACTIVO')
                ->where(function ($query) use ($busqueda) {
                    $query->where('login_usuario', 'LIKE', '%' . $busqueda . '%')
                        ->orWhere('nombre_usuario', 'LIKE', '%' . $busqueda . '%');
                })
                ->orderBy('nombre_usuario', 'ASC');
        } else {
            $respuesta = Usuarios::where('estado_usuario', 'ACTIVO')
                ->orderBy('nombre_usuario', 'ASC');
        }
        return $respuesta->count();
    }

    public static function login($request)
    {
        
        $usuario = Usuarios::where('email_usuario', $request['login_usuario'])->orWhere('login_usuario', $request['login_usuario'])->where('estado_usuario', 'ACTIVO')->first();
    
        if ($usuario && \Hash::check($request['pasword_usuario'], $usuario->pasword_usuario)) {
            auth()->loginUsingId($usuario->id);
            return $usuario;
        }
        return false;
    }

    public static function modificarUsuario($data, $Usu)
    {

        $respuesta = Usuarios::where(['id' => $Usu])->update([
            'nombre_usuario' => $data['nombre_alumno'] . ' ' . $data['apellido_alumno'],
            'email_usuario' => $data['email_alumno'],
            'grado_usuario' => $data['grado_alumno'],
        ]);
        return $respuesta;
    }

    public static function Guardar($data)
    {

        return Usuarios::create([
            'nombre_usuario' => $data['nombre_alumno'] . ' ' . $data['apellido_alumno'],
            'login_usuario' => $data['usuario_alumno'],
            'pasword_usuario' => bcrypt($data['password']),
            'tipo_usuario' => $data['tipo_usuario'],
            'email_usuario' => $data['email_alumno'],
            'grado_usuario' => $data['grado_alumno'],
            'estado_usuario' => 'ACTIVO',
        ]);

    }

    public static function GuardarUsu($data)
    {

        return Usuarios::create([
            'nombre_usuario' => $data['nombre_usuario'],
            'login_usuario' => $data['login_usuario'],
            'pasword_usuario' => bcrypt($data['password']),
            'tipo_usuario' => $data['tipo_usuario'],
            'email_usuario' => $data['email_usuario'],
            'estado_usuario' => $data['estado_usuario'],
        ]);

    }

    public static function modificar($data, $id)
    {
        $respuesta = Usuarios::where(['id' => $id])->update([
            'nombre_usuario' => $data['nombre_usuario'],
            'login_usuario' => $data['login_usuario'],
            'pasword_usuario' => bcrypt($data['password']),
            'tipo_usuario' => $data['tipo_usuario'],
            'email_usuario' => $data['email_usuario'],
            'estado_usuario' => $data['estado_usuario'],
        ]);
        return $respuesta;
    }

    public static function editarestado($id)
    {
        $respuesta = Usuarios::where(['id' => $id])->update([
            'estado_usuario' => "ELIMINADO",
        ]);
        return $respuesta;
    }
    public static function GuardarUsuProf($data)
    {

        return Usuarios::create([
            'nombre_usuario' => $data['nombre'] . ' ' . $data['apellido'],
            'login_usuario' => $data['usuario_profesor'],
            'pasword_usuario' => bcrypt($data['password']),
            'tipo_usuario' => $data['tipo_usuario'],
            'email_usuario' => $data['email'],
            'estado_usuario' => 'ACTIVO',
        ]);
    }

    public static function todos()
    {
        return Usuarios::where('estado_usuario', 'ACTIVO')
            ->get();
    }

    public static function cambiarclave($data)
    {
        $password = $data['passact'];
        $check_password = Usuarios::where(['id' => Auth::user()->id])->first();
        if (Hash::check($password, $check_password->pasword_usuario)) {
            $new_pasword = bcrypt($data['password']);
            $id = Auth::user()->id;
            Usuarios::where('id', $id)->update(['pasword_usuario' => $new_pasword]);
            return true;
        } else {
            return false;
        }
    }

    public static function GuardarUsuProfPer($data, $id)
    {
        return Usuarios::where(['id' => $id])->update([
            'nombre_usuario' => $data['nombre'] . ' ' . $data['apellido'],
            'login_usuario' => $data['login_usuario'],
            'email_usuario' => $data['email'],
        ]);
    }

    public static function GuardarUsuAluPer($data, $id)
    {
        return Usuarios::where(['id' => $id])->update([
            'nombre_usuario' => $data['nombre_alumno'] . ' ' . $data['apellido_alumno'],
            'login_usuario' => $data['login_usuario'],
            'email_usuario' => $data['email_alumno'],
        ]);
    }

    public static function buscar($id)
    {
        $usuarios = Usuarios::where('id', $id)->first();
        return $usuarios;
    }

    public static function BuscarUsu($Usu)
    {

        $usuarios = Usuarios::where('login_usuario', $Usu)
        ->where('estado', 'ACTIVO')
        ->first();
        return $usuarios;
    }

    public static function usuariosChat()
    {
        if (Auth::user()->tipo_usuario == "Estudiante") {
            $alumnos = Usuarios::where('estado_usuario', 'ACTIVO')
                ->join('alumnos', 'users.id', 'alumnos.usuario_alumno')
                ->where('tipo_usuario', 'Estudiante')
                ->where('grado_usuario', Auth::user()->grado_usuario)
                ->select('users.id', 'users.nombre_usuario', 'alumnos.foto_alumno')
                ->get();
            $profesores = Usuarios::join('asig_prof', 'users.id', 'asig_prof.profesor')
                ->join('modulos', 'modulos.id', 'asig_prof.grado')
                ->join('profesores', 'profesores.usuario_profesor', 'asig_prof.profesor')
                ->where('estado_usuario', 'ACTIVO')
                ->where('users.tipo_usuario', 'Profesor')
                ->where('profesores.jornada', Session::get('JORNADA'))
                ->where('modulos.grado_modulo', Auth::user()->grado_usuario)
                ->select('users.id', 'users.nombre_usuario', 'profesores.foto')
                ->groupBy('users.id')
                ->get();
            return compact('alumnos', 'profesores');
        } else {
            $profesores = Usuarios::join('asig_prof', 'users.id', 'asig_prof.profesor')
                ->join('modulos', 'modulos.id', 'asig_prof.grado')
                ->join('profesores', 'profesores.usuario_profesor', 'asig_prof.profesor')
                ->where('estado_usuario', 'ACTIVO')
                ->where('users.tipo_usuario', 'Profesor')
                ->select('users.id', 'users.nombre_usuario', 'profesores.foto')
                ->groupBy('users.id')
                ->get();

            $alumnos = Usuarios::where('estado_usuario', 'ACTIVO')
                ->join('alumnos', 'users.id', 'alumnos.usuario_alumno')
                ->where('alumnos.grupo', Session::get('GrupAct'))
                ->where('alumnos.grado_alumno', Session::get('GRADO'))
                ->where('alumnos.jornada', Session::get('JORDOCE'))
                ->where('tipo_usuario', 'Estudiante')
                ->select('users.id', 'users.nombre_usuario', 'alumnos.foto_alumno')
                ->get();
            return compact('alumnos', 'profesores');
        }
    }

    public static function usuariosChatMod()
    {
        if (Auth::user()->tipo_usuario == "Estudiante") {
            $alumnos = Usuarios::where('estado_usuario', 'ACTIVO')
                ->join('alumnos', 'users.id', 'alumnos.usuario_alumno')
                ->where('tipo_usuario', 'Estudiante')
                ->where('grado_usuario', Auth::user()->grado_usuario)
                ->select('users.id', 'users.nombre_usuario', 'alumnos.foto_alumno')
                ->get();
            $profesores = Usuarios::join('mod_prof', 'users.id', 'mod_prof.profesor')
                ->join('modulos', 'modulos.id', 'mod_prof.grado')
                ->join('profesores', 'profesores.usuario_profesor', 'mod_prof.profesor')
                ->where('estado_usuario', 'ACTIVO')
                ->where('users.tipo_usuario', 'Profesor')
                ->where('profesores.jornada', Session::get('JORNADA'))
                ->where('modulos.grado_modulo', Auth::user()->grado_usuario)
                ->select('users.id', 'users.nombre_usuario', 'profesores.foto')
                ->groupBy('users.id')
                ->get();
            return compact('alumnos', 'profesores');
        } else {
            $profesores = Usuarios::join('mod_prof', 'users.id', 'mod_prof.profesor')
                ->join('modulos', 'modulos.id', 'mod_prof.grado')
                ->join('profesores', 'profesores.usuario_profesor', 'mod_prof.profesor')
                ->where('estado_usuario', 'ACTIVO')
                ->where('users.tipo_usuario', 'Profesor')
                ->select('users.id', 'users.nombre_usuario', 'profesores.foto')
                ->groupBy('users.id')
                ->get();

            $alumnos = Usuarios::where('estado_usuario', 'ACTIVO')
                ->join('alumnos', 'users.id', 'alumnos.usuario_alumno')
                ->where('alumnos.grupo', Session::get('GrupAct'))
                ->where('alumnos.grado_alumno', Session::get('GRADO'))
                ->where('alumnos.jornada', Session::get('JORDOCE'))
                ->where('tipo_usuario', 'Estudiante')
                ->select('users.id', 'users.nombre_usuario', 'alumnos.foto_alumno')
                ->get();
            return compact('alumnos', 'profesores');
        }
    }

}
