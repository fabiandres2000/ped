<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Areas extends Model
{
      protected $table = 'areas';
    protected $fillable = [
        'nombre_area',
        'descripcion',
        'estado'
    ];

    public static function Guardar($data) {

        return Areas::create([
                    'nombre_area' => $data['nombre_area'],
                    'descripcion' => $data['descripcion'],
                    'estado' => 'ACTIVO'
        ]);
    }

    public static function modificar($data, $id) {
        $respuesta = Areas::where(['id' => $id])->update([
            'nombre_area' => $data['nombre_area'],
            'descripcion' => $data['descripcion']
        ]);
        return $respuesta;
    }

    public static function Gestion() {
   
        $respuesta = Areas::where('estado', 'ACTIVO')
        ->get();
        return $respuesta;
    }
    
    public static function listar() {
        $Areas = Areas::where('estado', "ACTIVO")
                ->get();
        return $Areas;
    }

    public static function BuscarArea($id) {
        return Areas::findOrFail($id);
    }

    public static function editarestado($id, $estado) {
        $Respuesta = Areas::where('id', $id)->update([
            'estado' => $estado
        ]);
        return $Respuesta;
    }
    
}
