<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Colegios extends Model
{
    protected $table = 'colegios';
    protected $fillable = [
        'nombre',
        'ubicacion',
        'num_cursos',
        'estado',
        'escudo',
        'habpasw',
        'cant_grupos',

    ];

    public static function InfColeg($col)
    {
        $Cole = Colegios::where('id', $col)
            ->first();
        return $Cole;
    }

    public static function Colegios()
    {
        $Cole = Colegios::get();
        return $Cole;
    }

    public static function UpdateColegios($data)
    {
        $respuesta = Colegios::where(['id' => $data['Colegio']])->update([
            'nombre' => $data['nombre'],
            'cant_grupos' => $data['CantGrup'],
            'habpasw' => $data['HabCont'],
            'escudo' => $data['escudo'],
        ]);
        return $respuesta;

    }
}
