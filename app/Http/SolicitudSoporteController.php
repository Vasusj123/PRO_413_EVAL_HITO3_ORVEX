<?php

namespace App\Http\Controllers;

use App\Models\SolicitudSoporte;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SolicitudSoporteController extends Controller
{
    /** Listado + formulario de registr */
    public function index(): View
    {
        $solicitudes = SolicitudSoporte::orderByDesc('id')->get();

        return view('solicitudes.index', [
            'solicitudes' => $solicitudes,
            'prioridades' => SolicitudSoporte::PRIORIDADES,
            'estados' => SolicitudSoporte::ESTADOS,
        ]);
    }

    /** Registrar una nueva solicitud. El estado inicial siempre es Pendiente */
    public function store(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'solicitante' => ['required', 'string', 'max:150'],
            'departamento' => ['required', 'string', 'max:150'],
            'descripcion' => ['required', 'string', 'max:5000'],
            'prioridad' => ['required', Rule::in(SolicitudSoporte::PRIORIDADES)],
        ], [
            'solicitante.required' => 'El nombre del solicitante es obligatorio.',
            'departamento.required' => 'El departamento es obligatorio.',
            'descripcion.required' => 'La descripcion del problema es obligatoria.',
            'prioridad.required' => 'Selecciona una prioridad.',
            'prioridad.in' => 'La prioridad debe ser Baja, Media o Alta.',
        ]);

        $datos['estado'] = 'Pendiente';

        $solicitud = SolicitudSoporte::create($datos);

        return redirect()
            ->route('solicitudes.index')
            ->with('ok', "Solicitud #{$solicitud->id} registrada correctamente.");
    }

    /** cambiar el estado de una solicitud existente */
    public function updateEstado(Request $request, SolicitudSoporte $solicitud): RedirectResponse
    {
        $datos = $request->validate([
            'estado' => ['required', Rule::in(SolicitudSoporte::ESTADOS)],
        ], [
            'estado.in' => 'El estado debe ser Pendiente, En proceso o Resuelta.',
        ]);

        $solicitud->update($datos);

        return redirect()
            ->route('solicitudes.index')
            ->with('ok', "Solicitud #{$solicitud->id} actualizada a \"{$solicitud->estado}\".");
    }
}
