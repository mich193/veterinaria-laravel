<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DoctoresSeeder extends Seeder
{
    public function run(): void
    {
        $doctores = [
            ['titulo' => 'Dra.', 'nombre' => 'Ana',   'apellidos' => 'Martínez', 'especialidad' => 'Medicina general',     'hora_entrada' => '08:00:00', 'hora_salida' => '12:00:00'],
            ['titulo' => 'Dr.',  'nombre' => 'Diego', 'apellidos' => 'Ramírez',  'especialidad' => 'Cirugía veterinaria',  'hora_entrada' => '08:00:00', 'hora_salida' => '14:00:00'],
            ['titulo' => 'Dra.', 'nombre' => 'Sofía', 'apellidos' => 'Castro',   'especialidad' => 'Medicina felina',      'hora_entrada' => '10:00:00', 'hora_salida' => '16:00:00'],
        ];

        foreach ($doctores as $doc) {
            DB::table('veterinario')->updateOrInsert(
                ['nombre' => $doc['nombre'], 'apellidos' => $doc['apellidos'], 'rol' => 'doctor'],
                $doc + ['rol' => 'doctor']
            );
        }
    }
}
