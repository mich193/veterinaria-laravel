<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Healthy Pets - Mis Citas</title>
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
    <link rel="stylesheet" href="{{ asset('css/citas.css') }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<div class="app-container">
    <!-- Barra Lateral -->
    <aside class="sidebar">
        <div class="sidebar-logo">
        <div class="paw-icon-top"><i class="fa-solid fa-paw"></i></div>
            <div>
                <h1>Healthy Pets</h1>
                <span>Clínica Veterinaria</span>
            </div>
        </div>

       <nav class="sidebar-nav">
    <!-- Botón Inicio (Casita) -> Redirige al Inicio -->
    <a href="{{ route('dashboard') }}" class="nav-item">
        <i class="fa-solid fa-house"></i> <span>Inicio</span>
    </a>

    <!-- Botón Agendar cita (Calendario) -> Redirige al index -->
    <a href="{{ route('home') }}" class="nav-item">
        <i class="fa-regular fa-calendar-days"></i> <span>Agendar cita</span>
    </a>

    <!-- Botón Mis citas -> Redirige a la vista de citas -->
    <a href="{{ route('citas') }}" class="nav-item active">
        <i class="fa-solid fa-list-check"></i> <span>Mis citas</span>
    </a>

    <!-- Botón Agenda -> Se mantiene en la agenda -->
    <a href="{{ route('agenda') }}" class="nav-item">
        <i class="fa-solid fa-calendar-week"></i> <span>Agenda</span>
    </a>

      </nav>
    </aside>

    <!-- Contenido Principal -->
    <main class="main-content">
        <header class="top-header">
            <div class="user-profile"><span>👤 Usuario</span></div>
        </header>

        <div class="form-container-wrapper" style="padding: 40px;">
        <h2>Mis Citas Registradas</h2>
        <p>Aquí podrás visualizar el historial de las citas de tus mascotas.</p>

        @if(session('success'))
            <div style="background: #e6f4ea; color: #137333; padding: 12px; border-radius: 8px; margin-top: 12px;">
                {{ session('success') }}
            </div>
        @endif

        <!-- Contenedor del listado -->
        <div id="lista-citas" style="background: white; padding: 20px; border-radius: 12px; margin-top: 20px;">
            @forelse($citas as $cita)
                <div class="cita-card" style="border-bottom: 1px solid #eee; padding: 12px 0;">
                    <h3>Paciente: {{ $cita->mascota_nombre }} ({{ $cita->especie }})</h3>
                    <p><strong>Servicio:</strong> {{ $cita->nombre_servicio }}</p>
                    <p><strong>Veterinario:</strong> Dr(a). {{ $cita->vet_nombre }} {{ $cita->vet_apellidos }}</p>
                    <p><strong>Fecha y Hora:</strong> {{ \Carbon\Carbon::parse($cita->fecha_hora)->format('d/m/Y h:i A') }}</p>
                    <p><strong>Estado:</strong> {{ ucfirst($cita->estado) }}</p>

                    <form action="{{ route('citas.destroy', $cita->id_cita) }}" method="POST" onsubmit="return confirm('¿Estás seguro de cancelar esta cita?');" style="margin-top: 8px;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-cancelar">Cancelar Cita</button>
                    </form>
                </div>
            @empty
                <p>No hay citas registradas todavía.</p>
            @endforelse
        </div>
    </div>
    </main>
</div>
</body>
</html>

