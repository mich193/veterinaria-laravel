<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CitaController extends Controller
{
    // GET /api/citas (soporta filtros por query params: ?estado=...&veterinario_asignado=...)
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'estado'               => 'sometimes|string|max:50',
            'veterinario_asignado' => 'sometimes|integer',
            'paciente'             => 'sometimes|integer',
        ]);

        // Retorno temprano con 400 si el filtro tiene formato inválido
        if ($validator->fails()) {
            return response()->json([
                'error' => 'Parámetros de filtro inválidos',
                'detalles' => $validator->errors()
            ], 400);
        }

        $query = Cita::with(['veterinario', 'mascota', 'servicioInfo']);

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('veterinario_asignado')) {
            $query->where('veterinario_asignado', $request->veterinario_asignado);
        }

        if ($request->filled('paciente')) {
            $query->where('paciente', $request->paciente);
        }

        return response()->json($query->get(), 200);
    }
     
    public function create()
    {
        $mascotas = DB::table('mascota_paciente')->get();
        $servicios = DB::table('servicio')->get();
        $veterinarios = DB::table('veterinario')->where('rol', 'doctor')->get();

        return view('index', compact('mascotas', 'servicios', 'veterinarios'));
    }

    public function mostrarCitas()
    {
        $citas = DB::table('citas')
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
                'veterinario.nombre as vet_nombre',
                'veterinario.apellidos as vet_apellidos'
            )
            ->orderBy('citas.fecha_hora', 'asc')
            ->get();

        return view('citas', compact('citas'));
    }
    // GET /api/citas/resumen
    public function resumen()
    {
        $total = Cita::count();
        $porEstado = Cita::selectRaw('estado, COUNT(*) as total')
            ->groupBy('estado')
            ->get();

        return response()->json([
            'total_citas' => $total,
            'citas_por_estado' => $porEstado
        ], 200);
    }

    // GET /api/citas/{id}
    public function show($id)
    {
        // Retorno temprano con 400 si el ID no es numérico
        if (!is_numeric($id)) {
            return response()->json(['error' => 'El ID debe ser numérico'], 400);
        }

        $cita = Cita::with(['veterinario', 'mascota', 'servicioInfo'])->find($id);

        if (!$cita) {
            return response()->json(['error' => 'Cita no encontrada'], 404);
        }

        return response()->json($cita, 200);
    }

   public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'fecha' => ['required', 'date'],
        'hora' => ['required', 'string'],
        'veterinario_asignado' => ['required', 'integer', 'exists:veterinario,id_veterinario'],
        'paciente' => ['required', 'integer', 'exists:mascota_paciente,id_mascota'],
        'servicio' => ['required', 'integer', 'exists:servicio,id_servicio'],
        'estado' => ['nullable', 'string', 'max:50'],
    ]);

    if ($validator->fails()) {
        return back()
            ->withErrors($validator)
            ->withInput();
    }

    $data = $validator->validated();
    $horaFormateada = strlen($data['hora']) === 5 ? $data['hora'] . ':00' : $data['hora'];

    $cita = Cita::create([
        'fecha_hora' => $data['fecha'] . ' ' . $horaFormateada,
        'veterinario_asignado' => $data['veterinario_asignado'],
        'paciente' => $data['paciente'],
        'servicio' => $data['servicio'],
        'estado' => $data['estado'] ?? 'programada',
    ]);

    return redirect()
        ->route('citaex')
        ->with('cita_creada', 'Cita #' . $cita->id_cita . ' registrada correctamente.');
}
    // PUT /api/citas/{id}
    public function update(Request $request, $id)
    {
        if (!is_numeric($id)) {
            return response()->json(['error' => 'El ID debe ser numérico'], 400);
        }

        $cita = Cita::find($id);

        if (!$cita) {
            return response()->json(['error' => 'Cita no encontrada'], 404);
        }

        $validator = Validator::make($request->all(), [
            'fecha_hora'           => 'sometimes|date',
            'veterinario_asignado' => 'sometimes|exists:veterinario,id_veterinario',
            'paciente'             => 'sometimes|exists:mascota_paciente,id_mascota',
            'servicio'             => 'sometimes|exists:servicio,id_servicio',
            'estado'               => 'sometimes|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Datos de actualización inválidos',
                'detalles' => $validator->errors()
            ], 400);
        }

        $cita->update($validator->validated());

        return response()->json($cita, 200);
    }

    // DELETE /api/citas/{id} -> Devuelve el elemento eliminado
    public function destroy($id)
    {
        if (!is_numeric($id)) {
            return response()->json(['error' => 'El ID debe ser numérico'], 400);
        }

        $cita = Cita::find($id);

        if (!$cita) {
            return response()->json(['error' => 'Cita no encontrada'], 404);
        }

        $cita->delete();

        return response()->json($cita, 200);
    }
}
