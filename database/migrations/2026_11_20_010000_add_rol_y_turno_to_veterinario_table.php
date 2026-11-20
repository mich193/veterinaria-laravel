<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('veterinario', function (Blueprint $table) {
            // 'doctor' aparece en "Doctores de hoy"; 'empleado' es quien usa el sistema
            $table->string('rol', 20)->default('empleado');
            $table->string('titulo', 10)->nullable();      // Dr. / Dra.
            $table->time('hora_entrada')->nullable();
            $table->time('hora_salida')->nullable();

            // Los doctores no necesitan cuenta para entrar al sistema
            $table->string('email', 150)->nullable()->change();
            $table->string('password')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('veterinario', function (Blueprint $table) {
            $table->dropColumn(['rol', 'titulo', 'hora_entrada', 'hora_salida']);
        });
    }
};
