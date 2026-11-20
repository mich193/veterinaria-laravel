<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Registro - Healthy Pets</title>
  <link rel="stylesheet" href="{{ asset('css/global.css') }}">
  <link rel="stylesheet" href="{{ asset('css/nuevoUS.css') }}">
</head>
<body class="auth-body"> <!--la clase para centrar el formulario en medio de la pantalla y aplicar el fondo-->

    <div class="auth-wrapper">
        <!--Lado izquierdo:diseño y Logo-->
        <div class="auth-sidebar">
            <div class="logo-container">
                <img src="logo-V.png" alt="Logo Healthy Pets" class="logo-img">
                <div class="logo-text">
                    <h2>Healthy Pets</h2>
                    <span>Clínica Veterinaria</span>
                </div>
            </div>
        </div>

        <!-- Lado Derecho: Formulario de Registro -->
        <div class="auth-main">
            <!-- Icono decorativo superior derecho -->
            <div class="top-icon">
                <i class='bx bx-bone'></i> <!--muestra el icono huella o hueso-->
            </div>

            <!-- Tarjeta del Formulario -->
           <div class="auth-card">
                <h2>Registro</h2>
                <p class="auth-subtitle">Crea tu cuenta para acceder al sistema</p>

                @if ($errors->any())
                    <div class="alert-error" style="color:#c0392b;margin-bottom:12px;">
                        {{ $errors->first() }}
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('registro.store') }}" method="POST" class="auth-form">
                    @csrf

                    <div class="input-icon-group">
                        <i class="bx bx-user"></i>
                        <input type="text" name="nombre" value="{{ old('nombre') }}" placeholder="Nombre" required>
                    </div>

                    <div class="input-icon-group">
                        <i class="bx bx-user"></i>
                        <input type="text" name="apellidos" value="{{ old('apellidos') }}" placeholder="Apellidos" required>
                    </div>

                    <div class="input-icon-group">
                        <i class="bx bx-briefcase"></i>
                        <input type="text" name="especialidad" value="{{ old('especialidad') }}" placeholder="Especialidad" required>
                    </div>

                    <div class="input-icon-group">
                        <i class="bx bx-phone"></i>
                        <input type="tel" name="telefono" value="{{ old('telefono') }}" placeholder="Teléfono (10 dígitos)"
                               inputmode="numeric" pattern="[0-9]{10}" minlength="10" maxlength="10"
                               title="El teléfono debe tener exactamente 10 dígitos"
                               oninput="this.value = this.value.replace(/\D/g, '').slice(0, 10)" required>
                    </div>

                    <div class="input-icon-group">
                        <i class="bx bx-envelope"></i>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="Correo electrónico" required>
                    </div>

                    <div class="input-icon-group">
                        <i class="bx bx-lock-alt"></i>
                        <input type="password" name="password" placeholder="Contraseña" required>
                    </div>

                    <div class="input-icon-group">
                        <i class="bx bx-lock-alt"></i>
                        <input type="password" name="password_confirmation" placeholder="Confirmar contraseña" required>
                    </div>

                    <button type="submit" class="btn-register">Registrarse</button>
                </form>

                <div class="auth-footer">
                    <p>¿Ya tienes cuenta? <a href="{{ route('login') }}">Iniciar sesión</a></p>
                </div>
            </div>
    </div>

</body>
</html>