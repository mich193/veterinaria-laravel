<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Healthy Pets - Cita Agendada</title>
    <!-- Hojas de estilo -->
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
    <link rel="stylesheet" href="{{ asset('css/citaex.css') }}">
    <!-- Iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<div class="app-container">
    <!-- Barra Lateral (Sidebar) -->
    <aside class="sidebar">
        <div class="sidebar-logo">
            <div class="paw-icon-top"><i class="fa-solid fa-paw"></i></div>
            <div>
                <h2>Healthy Pets</h2>
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
    <a href="{{ route('citas') }}" class="nav-item">
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
        <!-- Barra Superior -->
        <header class="top-header">
            <div class="user-profile">
                <i class='fa-regular fa-user'></i> Usuario
            </div>
        </header>

        <!-- Tarjeta de Confirmación de Cita -->
        <div class="success-container">
            <div class="success-card">
                
                <!-- Círculo de Verificación con icono -->
                <div class="check-circle">
                    <i class="fa-solid fa-check"></i>
                </div>

               <div class="cita-exitosa-card">
                <h2>¡Cita agendada con éxito!</h2>

    @if (session('cita_creada'))
        <p class="mensaje-exito">{{ session('cita_creada') }}</p>
    @elseif (session('success'))
        <p class="mensaje-exito">{{ session('success') }}</p>
    @endif

    @if ($ultimaCita)
        <div class="resumen-cita">
            <p><strong>Paciente:</strong> {{ $ultimaCita->mascota_nombre }} ({{ $ultimaCita->especie }})</p>
            @if ($ultimaCita->dueno_nombre)
                <p><strong>Dueño:</strong> {{ $ultimaCita->dueno_nombre }} {{ $ultimaCita->dueno_apellidos }}</p>
            @endif
            <p><strong>Servicio:</strong> {{ $ultimaCita->nombre_servicio }}</p>
            <p><strong>Veterinario:</strong> Dr(a). {{ $ultimaCita->vet_nombre }} {{ $ultimaCita->vet_apellidos }}</p>
            <p><strong>Fecha y Hora:</strong> {{ \Carbon\Carbon::parse($ultimaCita->fecha_hora)->format('d/m/Y h:i A') }}</p>
            <p><strong>Estado:</strong> {{ ucfirst($ultimaCita->estado) }}</p>
        </div>
    @endif

    <div class="acciones-cita">
        <a href="{{ route('citas') }}" class="btn-primary">Ver mis citas</a>
        <a href="{{ route('home') }}" class="btn-secondary">Agendar otra cita</a>
        <a href="{{ route('agenda') }}" class="btn-secondary">Ir a la agenda</a>
    </div>
</div>
    </main>
</div>

</body>
</html>