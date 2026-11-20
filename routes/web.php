<?php

use App\Http\Controllers\CitaController;
use App\Http\Controllers\DuenoMascotaController;
use App\Http\Controllers\HorarioController;
use App\Http\Controllers\VeterinarioController;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

// 1. LOGIN (login.blade.php)
Route::get('/', function () {
    // si ya inició sesión lo mandamos al inicio
    if (session()->has('veterinario_id')) {
        return redirect()->route('dashboard');
    }
    return view('login');
})->name('login');

Route::post('/', function (Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ], [
        'email.required' => 'El correo es obligatorio.',
        'email.email' => 'Escribe un correo válido.',
        'password.required' => 'La contraseña es obligatoria.',
    ]);

    $veterinario = DB::table('veterinario')
        ->where('email', $credentials['email'])
        ->first();

    if ($veterinario && Hash::check($credentials['password'], $veterinario->password)) {
        $request->session()->regenerate();
        session([
            'veterinario_id' => $veterinario->id_veterinario,
            'veterinario_nombre' => $veterinario->nombre,
        ]);

        return redirect()->route('dashboard');
    }

    return back()
        ->withErrors(['email' => 'Correo o contraseña incorrectos.'])
        ->withInput($request->only('email'));
})->name('login.submit');

// cerrar sesión
Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('login');
})->name('logout');

// 2. REGISTRO DE NUEVO USUARIO (nuevoUS.blade.php)
// se deja sin sesión para poder registrarse desde el login
Route::get('/nuevo-usuario', function () {
    return view('nuevoUS');
})->name('nuevo.usuario');
Route::post('/nuevo-usuario', [VeterinarioController::class, 'store'])->name('registro.store');


// ---- De aquí para abajo solo se puede entrar con sesión iniciada ----
Route::middleware('sesion')->group(function () {

// 3. INICIO / DASHBOARD (dashboard.blade.php)
Route::get('/dashboard', function () {
    $hoy = Carbon::today()->toDateString();
    $ahora = Carbon::now();

    // tarjetas de arriba
    $citasHoy = DB::table('citas')
        ->whereDate('fecha_hora', $hoy)
        ->count();

    $citasPorAtender = DB::table('citas')
        ->whereDate('fecha_hora', $hoy)
        ->where('estado', 'programada')
        ->count();

    $citasEnEspera = DB::table('citas')
        ->where('estado', 'programada')
        ->count();

    $proximaCita = DB::table('citas')
        ->where('estado', 'programada')
        ->where('fecha_hora', '>=', $ahora->toDateTimeString())
        ->orderBy('fecha_hora', 'asc')
        ->first();

    // solo los doctores (los empleados no salen aqui)
    $doctores = DB::table('veterinario')
        ->where('rol', 'doctor')
        ->orderBy('hora_entrada', 'asc')
        ->get();

    foreach ($doctores as $doctor) {
        $doctor->citas_hoy = DB::table('citas')
            ->where('veterinario_asignado', $doctor->id_veterinario)
            ->whereDate('fecha_hora', $hoy)
            ->count();

        $doctor->siguiente_cita = DB::table('citas')
            ->join('mascota_paciente', 'citas.paciente', '=', 'mascota_paciente.id_mascota')
            ->join('servicio', 'citas.servicio', '=', 'servicio.id_servicio')
            ->select(
                'citas.fecha_hora',
                'mascota_paciente.nombre as mascota_nombre',
                'servicio.nombre_servicio'
            )
            ->where('citas.veterinario_asignado', $doctor->id_veterinario)
            ->whereDate('citas.fecha_hora', $hoy)
            ->where('citas.fecha_hora', '>=', $ahora->toDateTimeString())
            ->orderBy('citas.fecha_hora', 'asc')
            ->first();
    }

    // usuario que inició sesión (para el menú de arriba)
    $usuario = DB::table('veterinario')
        ->where('id_veterinario', session('veterinario_id'))
        ->first();

    return view('dashboard', compact('citasHoy', 'citasPorAtender', 'citasEnEspera', 'proximaCita', 'doctores', 'usuario', 'ahora'));
})->name('dashboard');

// 4. VISTA PRINCIPAL / AGENDAR CITA (index.blade.php)
Route::get('/index', [CitaController::class, 'create'])->name('home');
Route::post('/citas', [CitaController::class, 'store'])->name('citas.store');

// 5. CONFIRMACIÓN DE CITA EXITOSA (citaex.blade.php)
Route::get('/cita-exitosa', function () {
    $ultimaCita = DB::table('citas')
        ->join('mascota_paciente', 'citas.paciente', '=', 'mascota_paciente.id_mascota')
        ->leftJoin('dueno_mascota', 'mascota_paciente.id_mascota', '=', 'dueno_mascota.id_mascota')
        ->join('servicio', 'citas.servicio', '=', 'servicio.id_servicio')
        ->join('veterinario', 'citas.veterinario_asignado', '=', 'veterinario.id_veterinario')
        ->select(
            'citas.id_cita',
            'citas.fecha_hora',
            'citas.estado',
            'mascota_paciente.nombre as mascota_nombre',
            'mascota_paciente.especie',
            'dueno_mascota.nombre as dueno_nombre',
            'dueno_mascota.apellidos as dueno_apellidos',
            'servicio.nombre_servicio',
            'veterinario.nombre as vet_nombre',
            'veterinario.apellidos as vet_apellidos'
        )
        ->orderBy('citas.id_cita', 'desc')
        ->first();

    return view('citaex', compact('ultimaCita'));
})->name('citaex');

// 6. MIS CITAS (citas.blade.php)
Route::get('/mis-citas', [CitaController::class, 'mostrarCitas'])->name('citas');

// ruta para eliminar/cancelar
Route::delete('/citas/{id}', [CitaController::class, 'destroy'])->name('citas.destroy');

// 7. AGENDA (agenda.blade.php)
// recibe ?fecha=2026-11-19&vista=dia|semana|mes&estado=programada
Route::get('/agenda', function (Request $request) {
    $hoy = Carbon::today()->toDateString();

    // fecha que se está viendo (si no mandan una se usa hoy)
    $fecha = Carbon::today();
    if ($request->filled('fecha') && strtotime($request->fecha)) {
        $fecha = Carbon::parse($request->fecha)->startOfDay();
    }

    // vista: dia, semana o mes
    $vista = $request->vista;
    if ($vista != 'semana' && $vista != 'mes') {
        $vista = 'dia';
    }

    // filtro de estado (vacío = todos)
    $estado = $request->estado;

    // rango de fechas segun la vista
    if ($vista == 'mes') {
        $inicio = $fecha->copy()->startOfMonth();
        $fin = $fecha->copy()->endOfMonth();
        $anterior = $fecha->copy()->subMonth();
        $siguiente = $fecha->copy()->addMonth();
    } elseif ($vista == 'semana') {
        $inicio = $fecha->copy()->startOfWeek();
        $fin = $fecha->copy()->endOfWeek();
        $anterior = $fecha->copy()->subWeek();
        $siguiente = $fecha->copy()->addWeek();
    } else {
        $inicio = $fecha->copy()->startOfDay();
        $fin = $fecha->copy()->endOfDay();
        $anterior = $fecha->copy()->subWeek();
        $siguiente = $fecha->copy()->addWeek();
    }

    // dias de la semana para los botones (lunes a domingo)
    $dias = [];
    $lunes = $fecha->copy()->startOfWeek();
    for ($i = 0; $i < 7; $i++) {
        $dias[] = $lunes->copy()->addDays($i);
    }

    // citas del rango
    $consulta = DB::table('citas')
        ->join('mascota_paciente', 'citas.paciente', '=', 'mascota_paciente.id_mascota')
        ->join('servicio', 'citas.servicio', '=', 'servicio.id_servicio')
        ->join('veterinario', 'citas.veterinario_asignado', '=', 'veterinario.id_veterinario')
        ->select(
            'citas.id_cita',
            'citas.fecha_hora',
            'citas.estado',
            'mascota_paciente.nombre as mascota_nombre',
            'mascota_paciente.especie',
            'servicio.nombre_servicio',
            'veterinario.titulo as vet_titulo',
            'veterinario.nombre as vet_nombre',
            'veterinario.apellidos as vet_apellidos'
        )
        ->whereBetween('citas.fecha_hora', [$inicio->toDateTimeString(), $fin->toDateTimeString()]);

    if ($estado) {
        $consulta->where('citas.estado', $estado);
    }

    $citas = $consulta->orderBy('citas.fecha_hora', 'asc')->get();

    // estados para el filtro
    $estados = DB::table('citas')->select('estado')->distinct()->pluck('estado')->toArray();
    $estados = array_unique(array_merge(['programada', 'atendida', 'cancelada'], $estados));

    // tarjetas de arriba
    $citasHoy = DB::table('citas')
        ->whereDate('fecha_hora', $hoy)
        ->count();

    $citasPorAtender = DB::table('citas')
        ->whereDate('fecha_hora', $hoy)
        ->where('estado', 'programada')
        ->count();

    $citasEnEspera = DB::table('citas')
        ->where('estado', 'programada')
        ->count();

    // horario de la clinica (el doctor que entra mas temprano y el que sale mas tarde)
    $jornadaInicio = DB::table('veterinario')->where('rol', 'doctor')->min('hora_entrada');
    $jornadaFin = DB::table('veterinario')->where('rol', 'doctor')->max('hora_salida');

    // proximo horario disponible: se revisa cada media hora dentro de la jornada
    $proximoHorario = null;
    if ($jornadaInicio && $jornadaFin) {
        $hueco = Carbon::now()->second(0);
        $hueco->minute($hueco->minute < 30 ? 30 : 60);

        for ($i = 0; $i < 336 && !$proximoHorario; $i++) {
            $hora = $hueco->format('H:i:s');

            if ($hora >= $jornadaInicio && $hora < $jornadaFin) {
                $ocupado = DB::table('citas')
                    ->where('fecha_hora', $hueco->format('Y-m-d H:i:s'))
                    ->where('estado', '!=', 'cancelada')
                    ->exists();

                if (!$ocupado) {
                    $proximoHorario = $hueco->copy();
                }
            }

            $hueco->addMinutes(30);
        }
    }

    $horarios = DB::table('horario')->get();

    return view('agenda', compact(
        'citas', 'citasHoy', 'citasPorAtender', 'citasEnEspera', 'horarios',
        'fecha', 'vista', 'estado', 'estados', 'dias', 'inicio', 'fin', 'anterior', 'siguiente',
        'jornadaInicio', 'jornadaFin', 'proximoHorario'
    ));
})->name('agenda');

}); // fin de las rutas con sesión
