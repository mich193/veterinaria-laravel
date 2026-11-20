<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('veterinario', function (Blueprint $table) {
            $table->id('id_veterinario');
            $table->string('nombre', 100);
            $table->string('apellidos', 100);
            $table->string('especialidad', 100)->nullable();
            $table->string('telefono', 30)->nullable();
            $table->string('email', 150)->unique();
            $table->string('password');
        });

        Schema::create('mascota_paciente', function (Blueprint $table) {
            $table->id('id_mascota');
            $table->string('nombre');
            $table->string('especie');
            $table->string('raza')->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->decimal('peso', 8, 2)->nullable();
        });

        Schema::create('dueno_mascota', function (Blueprint $table) {
            $table->id('id_dueno');
            $table->string('nombre');
            $table->string('apellidos');
            $table->string('telefono')->nullable();
            $table->foreignId('id_mascota')->nullable()
                ->constrained('mascota_paciente', 'id_mascota');
        });

        Schema::create('servicio', function (Blueprint $table) {
            $table->id('id_servicio');
            $table->string('nombre_servicio');
            $table->integer('duracion_estimada')->nullable();
        });

        Schema::create('citas', function (Blueprint $table) {
            $table->id('id_cita');
            $table->dateTime('fecha_hora');
            $table->foreignId('veterinario_asignado')
                ->constrained('veterinario', 'id_veterinario');
            $table->foreignId('paciente')
                ->constrained('mascota_paciente', 'id_mascota');
            $table->foreignId('servicio')
                ->constrained('servicio', 'id_servicio');
            $table->string('estado', 50)->default('Pendiente');
        });

        Schema::create('horario', function (Blueprint $table) {
            $table->id('id_horario');
            $table->foreignId('id_veterinario')
                ->constrained('veterinario', 'id_veterinario');
            $table->foreignId('id_cita')->nullable()
                ->constrained('citas', 'id_cita');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('horario');
        Schema::dropIfExists('citas');
        Schema::dropIfExists('servicio');
        Schema::dropIfExists('dueno_mascota');
        Schema::dropIfExists('mascota_paciente');
        Schema::dropIfExists('veterinario');
    }
};
