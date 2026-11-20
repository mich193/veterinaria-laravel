<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inicio - Healthy Pets</title>
  <link rel="stylesheet" href="{{ asset('css/global.css') }}">
  <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
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
    <!-- Botón Inicio (Casita) -> Se mantiene en el inicio -->
    <a href="{{ route('dashboard') }}" class="nav-item active">
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

    <!-- Botón Agenda -> Redirige a la agenda -->
    <a href="{{ route('agenda') }}" class="nav-item">
        <i class="fa-solid fa-calendar-week"></i> <span>Agenda</span>
    </a>
      </nav>
    </aside>

    <!-- Contenido Principal -->
    <main class="inicio-contenido">
      <div class="inicio-panel">

        <!-- Encabezado -->
        <header class="inicio-header">
          <div>
            <h1>Un gran día para cuidar{{ $usuario ? ', ' . $usuario->nombre : '' }}.</h1>
            <p>Así va tu clínica hoy. Cada patita cuenta.</p>
          </div>

          <div class="inicio-acciones">
            <span class="fecha-chip">
              <i class="fa-regular fa-calendar"></i> {{ ucfirst($ahora->translatedFormat('l, j \d\e F')) }}
            </span>

            <a href="{{ route('nuevo.usuario') }}" class="btn-agregar-usuario">
              <i class="fa-solid fa-user-plus"></i> Agregar usuario
            </a>

            <!-- Menú de usuario (cerrar sesión) -->
            <div class="menu-usuario">
              <button type="button" id="btn-usuario" class="circulo-usuario" title="Mi cuenta">
                @if ($usuario)
                  {{ mb_substr($usuario->nombre, 0, 1) }}{{ mb_substr($usuario->apellidos, 0, 1) }}
                @else
                  <i class="fa-solid fa-user"></i>
                @endif
              </button>

              <div id="menu-usuario-opciones" class="menu-usuario-opciones">
                <div class="menu-usuario-datos">
                  @if ($usuario)
                    <strong>{{ $usuario->nombre }} {{ $usuario->apellidos }}</strong>
                    <span>{{ $usuario->email }}</span>
                  @else
                    <strong>Invitado</strong>
                  @endif
                </div>
                <form action="{{ route('logout') }}" method="POST">
                  @csrf
                  <button type="submit" class="btn-cerrar-sesion">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i> Cerrar sesión
                  </button>
                </form>
              </div>
            </div>
          </div>
        </header>

        <!-- Tarjetas de resumen -->
        <section class="tarjetas-resumen">
          <div class="tarjeta-resumen">
            <div>
              <span class="tarjeta-titulo">Citas de hoy</span>
              <div class="tarjeta-numero">{{ str_pad($citasHoy, 2, '0', STR_PAD_LEFT) }} <small>citas</small></div>
              <span class="tarjeta-detalle">{{ $citasPorAtender }} por atender</span>
            </div>
            <span class="tarjeta-icono icono-rosa"><i class="fa-regular fa-calendar-check"></i></span>
          </div>

          <div class="tarjeta-resumen">
            <div>
              <span class="tarjeta-titulo">Citas en espera</span>
              <div class="tarjeta-numero">{{ str_pad($citasEnEspera, 2, '0', STR_PAD_LEFT) }} <small>pacientes</small></div>
              <span class="tarjeta-detalle">
                @if ($proximaCita)
                  @if (\Carbon\Carbon::parse($proximaCita->fecha_hora)->isToday())
                    Próxima cita · {{ \Carbon\Carbon::parse($proximaCita->fecha_hora)->format('H:i') }}
                  @else
                    Próxima cita · {{ \Carbon\Carbon::parse($proximaCita->fecha_hora)->format('d/m H:i') }}
                  @endif
                @else
                  Sin citas próximas
                @endif
              </span>
            </div>
            <span class="tarjeta-icono icono-naranja"><i class="fa-regular fa-clock"></i></span>
          </div>

          <div class="tarjeta-resumen">
            <div>
              <span class="tarjeta-titulo">Doctores de hoy</span>
              <div class="tarjeta-numero">{{ str_pad(count($doctores), 2, '0', STR_PAD_LEFT) }} <small>doctores</small></div>
              <span class="tarjeta-detalle">Equipo disponible</span>
            </div>
            <span class="tarjeta-icono icono-morado"><i class="fa-solid fa-user-doctor"></i></span>
          </div>
        </section>

        <!-- Banner de bienvenida -->
        <section class="tarjeta-bienvenida">
          <div class="bienvenida-texto">
            <span class="bienvenida-frase">Hecho con amor, para ellos</span>
            <h2>¡Bienvenido a Healthy Pets!</h2>
            <p>Pequeñas patitas, grandes cuidados.<br>Todo lo que necesitas para hacer su día mejor.</p>
          </div>
          <div class="bienvenida-imagen">
            <!-- Si se guarda una foto en public/img/bienvenida.png se muestra esa, si no el logo -->
            @if (file_exists(public_path('img/bienvenida.png')))
              <img src="{{ asset('img/bienvenida.png') }}" alt="Mascotas Healthy Pets">
            @else
              <img src="{{ asset('logo-V.png') }}" alt="Logo Healthy Pets">
            @endif
          </div>
        </section>

        <!-- Doctores de hoy -->
        <section class="doctores-hoy">
          <div class="doctores-encabezado">
            <div>
              <h3>Doctores de hoy</h3>
              <p>Un gran equipo, un mismo corazón.</p>
            </div>
            <span class="tarjeta-icono icono-rosa chico"><i class="fa-solid fa-stethoscope"></i></span>
          </div>

          <div class="doctores-lista">
            @forelse ($doctores as $doctor)
              <div class="doctor-tarjeta">
                <div class="doctor-datos">
                  <span class="doctor-iniciales">{{ mb_substr($doctor->nombre, 0, 1) }}{{ mb_substr($doctor->apellidos, 0, 1) }}</span>
                  <div class="doctor-nombre">
                    <strong>{{ $doctor->titulo }} {{ $doctor->nombre }} {{ $doctor->apellidos }}</strong>
                    <span>{{ $doctor->especialidad }}</span>
                  </div>
                  <div class="doctor-turno">
                    @if ($doctor->hora_entrada && $ahora->format('H:i:s') >= $doctor->hora_entrada && $ahora->format('H:i:s') < $doctor->hora_salida)
                      <span class="en-turno">● En turno</span>
                    @else
                      <span class="fuera-turno">● Fuera de turno</span>
                    @endif
                    <small>{{ substr($doctor->hora_entrada, 0, 5) }} – {{ substr($doctor->hora_salida, 0, 5) }}</small>
                  </div>
                </div>

                <div class="doctor-siguiente">
                  @if ($doctor->siguiente_cita)
                    <i class="fa-solid fa-paw"></i>
                    <span>Siguiente: <b>{{ \Carbon\Carbon::parse($doctor->siguiente_cita->fecha_hora)->format('H:i') }}</b>
                      · {{ $doctor->siguiente_cita->mascota_nombre }} ({{ $doctor->siguiente_cita->nombre_servicio }})</span>
                  @elseif ($doctor->citas_hoy > 0)
                    <i class="fa-regular fa-circle-check"></i>
                    <span>{{ $doctor->citas_hoy }} citas atendidas hoy</span>
                  @else
                    <i class="fa-regular fa-face-smile"></i>
                    <span>Agenda libre por ahora</span>
                  @endif
                </div>
              </div>
            @empty
              <p class="sin-doctores">Aún no hay doctores registrados.</p>
            @endforelse
          </div>
        </section>

        <!-- Pie -->
        <footer class="inicio-pie">
          <span><b>Healthy Pets</b> · Donde su bienestar es lo primero.</span>
          <span>Cada cuidado hace la diferencia <i class="fa-regular fa-heart"></i></span>
        </footer>

      </div>
    </main>
  </div>

  <script>
    // Abrir y cerrar el menú del usuario
    const btnUsuario = document.getElementById('btn-usuario');
    const menuUsuario = document.getElementById('menu-usuario-opciones');

    if (btnUsuario) {
      btnUsuario.addEventListener('click', (evento) => {
        evento.stopPropagation();
        menuUsuario.classList.toggle('abierto');
      });

      // si se da clic en otra parte de la pantalla se cierra
      document.addEventListener('click', (evento) => {
        if (!menuUsuario.contains(evento.target)) {
          menuUsuario.classList.remove('abierto');
        }
      });
    }
  </script>
</body>
</html>
