<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AlumnosEgresados extends Model
{
    protected $table = 'alumnos_egresados';
    protected $fillable = [
        'ident_alumno',
        'nombre_alumno',
        'apellido_alumno',
        'grado_alumno',
        'grupo',
        'sexo_alumno',
        'nacimiento_alumno',
        'direccion_alumno',
        'telefono_alumno',
        'email_alumno',
        'usuario_alumno',
        'estado_alumno',
        'foto_alumno',
        'jornada',
    ];

    
}
