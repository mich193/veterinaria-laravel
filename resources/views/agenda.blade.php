<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mi agenda - Healthy Pets</title>
  <link rel="stylesheet" href="{{ asset('css/global.css') }}">
  <link rel="stylesheet" href="{{ asset('css/agenda.css') }}">
  <!-- Iconos -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
  <div class="app-container">
    <!-- Barra Lateral -->
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
    <a href="{{ route('agenda') }}" class="nav-item active">
        <i class="fa-solid fa-calendar-week"></i> <span>Agenda</span>
    </a>

      </nav>
    </aside>

    <!-- Contenido Principal -->
    <main class="main-content">

      <!-- Cabecera Superior -->
      <header class="agenda-top-header">
        <div>
          <h1>Mi agenda</h1>
          <p>¡El cuidado y bienestar de las mascotas es nuestra prioridad!</p>
        </div>
      </header>

      <!-- Tarjetas de Estadísticas -->
      <div class="stats-row">
        <div class="stat-card">
          <div class="stat-icon"><i class="fa-regular fa-calendar-check"></i></div>
          <div class="stat-info">
            <h3 id="stat-citas-hoy">{{ $citasHoy }} citas hoy</h3>
            @if ($citasHoy == 0)
              <span>Sin atenciones registradas</span>
            @else
              <span>{{ $citasPorAtender }} por atender</span>
            @endif
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon"><i class="fa-solid fa-dog"></i></div>
          <div class="stat-info">
            <h3 id="stat-en-espera">{{ $citasEnEspera }} en espera</h3>
            @if ($citasEnEspera == 0)
              <span>Recepción vacía</span>
            @else
              <span>Pacientes con cita programada</span>
            @endif
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon"><i class="fa-regular fa-clock"></i></div>
          <div class="stat-info">
            <h3>Horas de consulta</h3>
            @if ($jornadaInicio && $jornadaFin)
              <span>Jornada de {{ substr($jornadaInicio, 0, 5) }} a {{ substr($jornadaFin, 0, 5) }}</span>
            @else
              <span>Sin horario registrado</span>
            @endif
          </div>
        </div>
      </div>

      <!-- Sección Principal de Agenda -->
      <div class="right-agenda-column full-width">
        <div class="agenda-toolbar">
          <div class="agenda-title-date">
            <h2>Mi agenda</h2>
            <span id="mes-actual-label">{{ ucfirst($fecha->translatedFormat('F Y')) }}</span>
          </div>

          <!-- Botones Día / Semana / Mes -->
          <div class="agenda-views">
            <a href="{{ route('agenda', ['fecha' => $fecha->toDateString(), 'vista' => 'dia', 'estado' => $estado]) }}" class="view-btn {{ $vista == 'dia' ? 'active' : '' }}">Día</a>
            <a href="{{ route('agenda', ['fecha' => $fecha->toDateString(), 'vista' => 'semana', 'estado' => $estado]) }}" class="view-btn {{ $vista == 'semana' ? 'active' : '' }}">Semana</a>
            <a href="{{ route('agenda', ['fecha' => $fecha->toDateString(), 'vista' => 'mes', 'estado' => $estado]) }}" class="view-btn {{ $vista == 'mes' ? 'active' : '' }}">Mes</a>
          </div>
        </div>

        <!-- Selector de Días de la Semana -->
        <div class="days-selector">
          <a href="{{ route('agenda', ['fecha' => $anterior->toDateString(), 'vista' => $vista, 'estado' => $estado]) }}" class="arrow-btn" title="Anterior">
            <i class="fa-solid fa-chevron-left"></i>
          </a>

          @foreach ($dias as $dia)
            <a href="{{ route('agenda', ['fecha' => $dia->toDateString(), 'vista' => 'dia', 'estado' => $estado]) }}"
               class="day-item {{ $dia->isSameDay($fecha) ? 'active' : '' }} {{ $dia->isToday() ? 'today' : '' }}">
              <small>{{ ucfirst(rtrim($dia->translatedFormat('D'), '.')) }}</small>
              <b>{{ $dia->format('d') }}</b>
            </a>
          @endforeach

          <a href="{{ route('agenda', ['fecha' => $siguiente->toDateString(), 'vista' => $vista, 'estado' => $estado]) }}" class="arrow-btn" title="Siguiente">
            <i class="fa-solid fa-chevron-right"></i>
          </a>
        </div>

        <!-- Sub-cabecera del día / semana / mes seleccionado -->
        <div class="day-sub-header">
          <div>
            @if ($vista == 'mes')
              <strong>{{ ucfirst($fecha->translatedFormat('F \d\e Y')) }}</strong>
            @elseif ($vista == 'semana')
              <strong>Semana del {{ $inicio->format('d') }} al {{ $fin->translatedFormat('d \d\e F') }}</strong>
            @else
              <strong>{{ ucfirst($fecha->translatedFormat('l, d \d\e F')) }}</strong>
            @endif

            @if ($vista == 'dia' && $fecha->isToday())
              <span class="badge badge-today">Hoy</span>
            @elseif ($vista == 'dia')
              <a href="{{ route('agenda') }}" class="link-hoy">Ir a hoy</a>
            @endif
          </div>

          <div class="agenda-filters">
            <span id="contador-citas-dia">{{ $citas->count() }} {{ $citas->count() == 1 ? 'cita' : 'citas' }}</span>

            <!-- Filtro por estado -->
            <form action="{{ route('agenda') }}" method="GET" class="filter-form">
              <input type="hidden" name="fecha" value="{{ $fecha->toDateString() }}">
              <input type="hidden" name="vista" value="{{ $vista }}">
              <i class="fa-solid fa-sliders"></i>
              <select name="estado" id="filtro-estado" class="filter-dropdown-btn">
                <option value="">Todos los estados</option>
                @foreach ($estados as $opcion)
                  <option value="{{ $opcion }}" {{ $estado == $opcion ? 'selected' : '' }}>{{ ucfirst($opcion) }}</option>
                @endforeach
              </select>
            </form>
          </div>
        </div>

        <!-- LISTADO DINÁMICO DE CITAS -->
        <div id="contenedor-agenda-dinamica" class="agenda-list">
          @forelse ($citas as $cita)
            <div class="agenda-card" style="background: #fff; border: 1px solid #e5e7eb; border-radius: 10px; padding: 14px;">
              <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 6px;">
                <strong>{{ \Carbon\Carbon::parse($cita->fecha_hora)->format('d/m/Y h:i A') }}</strong>
                <span class="status-badge {{ $cita->estado == 'atendida' ? 'atendida' : ($cita->estado == 'cancelada' ? 'por-confirmar' : 'en-espera') }}">{{ ucfirst($cita->estado) }}</span>
              </div>
              <p style="margin: 6px 0 0;">
                <strong>Paciente:</strong> {{ $cita->mascota_nombre }} ({{ $cita->especie }}) —
                <strong>Servicio:</strong> {{ $cita->nombre_servicio }}
              </p>
              <small style="color: #6b7280;">
                Veterinario: {{ $cita->vet_titulo ?? 'Dr(a).' }} {{ $cita->vet_nombre }} {{ $cita->vet_apellidos }}
              </small>
            </div>
          @empty
            <div class="empty-state-agenda" style="text-align: center; padding: 40px; color: #888;">
              <i class="fa-regular fa-calendar-xmark" style="font-size: 3rem; margin-bottom: 10px; color: #b5838d;"></i>
              @if ($vista == 'mes')
                <p>No hay citas programadas para este mes.</p>
              @elseif ($vista == 'semana')
                <p>No hay citas programadas para esta semana.</p>
              @else
                <p>No hay citas programadas para este día.</p>
              @endif
            </div>
          @endforelse
        </div>

        <!-- Pie de Agenda -->
        <div class="agenda-footer-bar">
          <span><i class="fa-regular fa-clock"></i> Próximo horario disponible:
            <strong id="proximo-horario">
              @if ($proximoHorario)
                @if ($proximoHorario->isToday())
                  Hoy {{ $proximoHorario->format('H:i') }}
                @elseif ($proximoHorario->isTomorrow())
                  Mañana {{ $proximoHorario->format('H:i') }}
                @else
                  {{ $proximoHorario->format('d/m H:i') }}
                @endif
              @else
                --:--
              @endif
            </strong>
          </span>
          <a href="{{ route('home') }}" class="link-disponibilidad">Agendar en ese horario <i class="fa-solid fa-arrow-right"></i></a>
        </div>

      </div>

    </main>
  </div>

  <script>
    // Al cambiar el filtro de estado se recarga la agenda con ese filtro
    const filtroEstado = document.getElementById('filtro-estado');

    if (filtroEstado) {
      filtroEstado.addEventListener('change', () => {
        filtroEstado.form.submit();
      });
    }
  </script>
</body>
</html>
