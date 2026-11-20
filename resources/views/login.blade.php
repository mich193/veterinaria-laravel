<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Iniciar Sesión - Healthy Pets</title>
  <link rel="stylesheet" href="{{ asset('css/global.css') }}">
  <link rel="stylesheet" href="{{ asset('css/login.css') }}">
  <!-- Iconos de FontAwesome y Boxicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

  <div class="login-container">
    <!-- Lado Izquierdo: Branding y Logo -->
    <div class="login-brand">
      <div class="logo-area">
        <div class="paw-icon-top">
          <i class="fa-solid fa-paw"></i>
        </div>
        <h1>Healthy Pets Clinica Veterinaria</h1>
        <p class="subtitulo-brand">¡Bienvenidos!</p>
      </div>
      <div class="ilustracion-mascotas">
        <img src="logo-V.png" alt="Veterinaria Healthy Pets" class="mi-imagen-login">
      </div>
    </div>

    <!-- Lado Derecho: Tarjeta con Formulario de Login -->
    <div class="login-card">
      <h2>Iniciar sesión</h2>
      @if (session('success'))
                <div class="alert-success">
                    {{ session('success') }}
                </div>
            @endif
      @if ($errors->any())
        <div class="alert-error" style="color:#c0392b;margin-bottom:12px;">
          {{ $errors->first() }}
        </div>
      @endif
      <form action="{{ route('login.submit') }}" method="POST" class="login-form">
        <!-- Campo Correo (con el que te registraste) -->
         @csrf
        <div class="input-icon-group">
          <i class="fa-regular fa-envelope icon"></i>
          <input type="email" name="email" value="{{ old('email') }}" placeholder="Correo electrónico" required>
        </div>

        <!-- Campo Contraseña -->
        <div class="input-icon-group">
          <i class="fa-solid fa-lock icon"></i>
          <input type="password" name="password" placeholder="Contraseña" required>
          <i class="fa-regular fa-eye toggle-password"></i>
        </div>

        <!-- Opciones adicionales: Recordar usuario y Contraseña -->
        <div class="login-options">
          <label>
            <input type="checkbox" name="remember"> Recordar usuario
          </label>
          <a href="#" class="forgot-link">¿Olvidaste tu contraseña?</a>
        </div>

        <!-- Botón Iniciar Sesión -->
        <button type="submit" class="btn-primary">Iniciar sesión</button>
        
        <div class="divider"></div>

       <a href="{{ route('nuevo.usuario') }}">Registrarse</a>
      </form>
    </div>
  </div>

</body>
</html>