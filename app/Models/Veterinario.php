<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Veterinario extends Model
{
    protected $table = 'veterinario';
    protected $primaryKey = 'id_veterinario';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'apellidos',
        'especialidad',
        'telefono',
        'rol',
        'titulo',
        'hora_entrada',
        'hora_salida',
    ];

    public function citas()
    {
        return $this->hasMany(Cita::class, 'veterinario_asignado', 'id_veterinario');
    }

    public function horarios()
    {
        return $this->hasMany(Horario::class, 'id_veterinario', 'id_veterinario');
    }
}
