<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Agendar Cita - Healthy Pets</title>
  <link rel="stylesheet" href="{{ asset('css/global.css') }}">
  <link rel="stylesheet" href="{{ asset('css/index.css') }}">

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
    <a href="{{ route('home') }}" class="nav-item active">
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
    <header class="top-header">
        <a href="{{ route('nuevo.usuario') }}" class="btn-agregar-usuario">
            <i class="fa-solid fa-user-plus"></i> Agregar usuario
        </a>
    </header>

      <div class="form-container-wrapper">
        <div class="form-header">
          <h2>Agendar tu Cita</h2>
          <p>Completa los datos para registrar una nueva cita</p>
        </div>

        <div class="cita-card">
         <form action="{{ route('citas.store') }}" method="POST" class="formulario-grid">
    @csrf
      
            <div class="form-group">
              <label for="dueno">Nombre del dueño</label>
              <input type="text" id="dueno" placeholder="Nombre completo" required>
            </div>
            <div class="form-group">
              <label for="motivo">Motivo de la consulta</label>
              <select id="motivo" required>
                <option value="" disabled selected>Selecciona el motivo</option>
                <option value="urgencia">Urgencia</option>
                <option value="general">General</option>
                <option value="vacunacion">Vacunación</option>
              </select>
              <!--No olvidar agregar una opcion de dsiponibilidad de horario de cada doctor-->
            </div>
            <div class="form-group">
              <label for="mascota">Nombre de la mascota</label>
              <input type="text" id="mascota" placeholder="Nombre" required>
            </div>
            <div class="form-group">
              <label for="fecha">Fecha</label>
              <input type="date" id="fecha" required>
            </div>
            <div class="form-group">
              <label for="especie">Especie</label>
              <select id="especie" required>
                <option value="" disabled selected>Selecciona la especie</option>
                <option value="perro">Perro</option>
                <option value="gato">Gato</option>
                <option value="otro">Otro</option>
              </select>
            </div>
            <div class="form-group">
              <label for="hora">Hora</label>
              <input type="time" id="hora" required>
            </div>
            <div class="form-group">
              <label for="raza">Raza</label>
              <select id="raza" required>
                <option value="" disabled selected>Selecciona la raza</option>
                <option value="golden">Golden Retriever</option>
                <option value="mestizo">Mestizo</option>
                <option value="siames">Siamés</option>
                <option value="otro">Otra</option>
              </select>
            </div>
            <div class="form-actions">
              <button type="submit" class="btn-submit">Agendar Cita</button>
            </div>
          </form>
        </div>
        <div id="lista-citas-dinamica" style="margin-top: 20px;"></div>
      </div>
    </main>
  </div>

  <script>
    const boton = document.querySelector('.formulario-grid button[type="submit"]');

    if (boton) {
      boton.addEventListener('click', (evento) => {
        evento.preventDefault();

        const dueno = document.getElementById('dueno').value;
        const motivo = document.getElementById('motivo').value;
        const mascota = document.getElementById('mascota').value;
        const fecha = document.getElementById('fecha').value;
        const especie = document.getElementById('especie').value;
        const hora = document.getElementById('hora').value;
        const raza = document.getElementById('raza').value;

        if (!dueno || !mascota || !fecha || !hora) {
          alert("Por favor, llena los campos obligatorios principales.");
          return;
        }

        const nuevaCita = { dueno, motivo, mascota, fecha, especie, hora, raza };
        const listaCitas = JSON.parse(localStorage.getItem('citasGuardadas')) || [];
        listaCitas.push(nuevaCita);
        localStorage.setItem('citasGuardadas', JSON.stringify(listaCitas));

        window.location.href = 'citaex.html';
      });
    }
  </script>
</body>
</html>