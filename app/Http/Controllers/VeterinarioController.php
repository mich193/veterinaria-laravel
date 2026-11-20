<?php

namespace App\Http\Controllers;

use App\Models\Veterinario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class VeterinarioController extends Controller
{
    public function create()
{
    return view('nuevoUS');
}
    public function store(Request $request)
{
    $data = $request->validate([
        'nombre' => ['required', 'string', 'max:100'],
        'apellidos' => ['required', 'string', 'max:100'],
        'especialidad' => ['required', 'string', 'max:100'],
        'telefono' => ['required', 'digits:10'],
        'email' => ['required', 'email', 'max:150', 'unique:veterinario,email'],
        'password' => ['required', 'string', 'min:6', 'confirmed'],
    ], [
        'telefono.required' => 'El teléfono es obligatorio.',
        'telefono.digits' => 'El teléfono debe tener exactamente 10 dígitos (solo números).',
        'email.unique' => 'Ya existe una cuenta con ese correo.',
        'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
        'password.confirmed' => 'Las contraseñas no coinciden.',
    ]);

    DB::table('veterinario')->insert([
        'nombre' => $data['nombre'],
        'apellidos' => $data['apellidos'],
        'especialidad' => $data['especialidad'],
        'telefono' => $data['telefono'],
        'email' => $data['email'],
        'password' => Hash::make($data['password']),
    ]);

    return redirect()
        ->route('login')
        ->with('success', 'Veterinario registrado correctamente. Ya puedes iniciar sesión.');
}
   
}
