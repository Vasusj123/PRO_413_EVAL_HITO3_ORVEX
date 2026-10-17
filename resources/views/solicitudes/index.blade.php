<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Soporte Tecnico</title>
    <style>
        :root { --azul:#1f4e8c; --borde:#d9dee5; --fondo:#f4f6f9; }
        * { box-sizing: border-box; }
        body { font-family: system-ui, Arial, sans-serif; background: var(--fondo); margin: 0; color: #1c2430; }
        header { background: var(--azul); color: #fff; padding: 16px 24px; }
        header h1 { margin: 0; font-size: 20px; }
        main { max-width: 1100px; margin: 24px auto; padding: 0 16px; }
        section { background: #fff; border: 1px solid var(--borde); border-radius: 8px; padding: 20px; margin-bottom: 24px; }
        h2 { margin: 0 0 14px; font-size: 17px; }
        label { display: block; font-size: 13px; font-weight: 600; margin-bottom: 4px; }
        input, select, textarea { width: 100%; padding: 8px; border: 1px solid var(--borde); border-radius: 6px; font: inherit; }
        .grid { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 14px; }
        .full { grid-column: 1 / -1; }
        button { background: var(--azul); color: #fff; border: 0; border-radius: 6px; padding: 9px 16px; cursor: pointer; font: inherit; }
        button.chico { padding: 5px 10px; font-size: 13px; }
        .ok { background: #e6f5ea; border: 1px solid #9fd3ad; color: #1d5a2f; padding: 10px 14px; border-radius: 6px; margin-bottom: 16px; }
        .error { background: #fdeaea; border: 1px solid #e3a3a3; color: #8a1f1f; padding: 10px 14px; border-radius: 6px; margin-bottom: 16px; }
        .error ul { margin: 0; padding-left: 18px; }
        table { width: 100%; border-collapse: collapse; font-size: 14px; }
        th, td { text-align: left; padding: 8px; border-bottom: 1px solid var(--borde); vertical-align: top; }
        th { background: #eef2f7; }
        .badge { padding: 2px 8px; border-radius: 10px; font-size: 12px; font-weight: 600; }
        .baja { background:#e3f1e6; color:#1d5a2f; }
        .media { background:#fff3d6; color:#7a5a00; }
        .alta { background:#fde0e0; color:#8a1f1f; }
        .pendiente { background:#eceff3; color:#3b4656; }
        .en-proceso { background:#dfeafb; color:#1f4e8c; }
        .resuelta { background:#e3f1e6; color:#1d5a2f; }
        .estado-form { display: flex; gap: 6px; }
        .estado-form select { width: auto; padding: 4px; }
        @media (max-width: 800px) { .grid { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
<header><h1>Sistema de Registro y Control de Solicitudes de Soporte Tecnico</h1></header>

<main>
    @if (session('ok'))
        <div class="ok">{{ session('ok') }}</div>
    @endif

    @if (session('error'))
        <div class="error">{{ session('error') }}</div>
    @endif

    @if ($errors->any())
        <div class="error">
            <strong>Revisa los siguientes errores:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <section>
        <h2>Nueva solicitud</h2>
        <form method="POST" action="{{ route('solicitudes.store') }}">
            @csrf
            <div class="grid">
                <div>
                    <label for="solicitante">Solicitante</label>
                    <input id="solicitante" name="solicitante" value="{{ old('solicitante') }}" maxlength="150" required>
                </div>
                <div>
                    <label for="departamento">Departamento</label>
                    <input id="departamento" name="departamento" value="{{ old('departamento') }}" maxlength="150" required>
                </div>
                <div>
                    <label for="prioridad">Prioridad</label>
                    <select id="prioridad" name="prioridad" required>
                        <option value="">Selecciona...</option>
                        @foreach ($prioridades as $p)
                            <option value="{{ $p }}" @selected(old('prioridad') === $p)>{{ $p }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="full">
                    <label for="descripcion">Descripcion del problema</label>
                    <textarea id="descripcion" name="descripcion" rows="3" required>{{ old('descripcion') }}</textarea>
                </div>
                <div>
                    <label>Estado inicial</label>
                    <input value="Pendiente" disabled>
                </div>
            </div>
            <p><button type="submit">Registrar solicitud</button></p>
        </form>
    </section>

    <section>
        <h2>Solicitudes registradas ({{ $solicitudes->count() }})</h2>

        {{-- Persona B: aqui va el boton "Enviar a la base de datos de la red local" (POST a solicitudes.enviar) --}}

        @if ($solicitudes->isEmpty())
            <p>Aun no hay solicitudes registradas.</p>
        @else
            <table>
                <thead>
                    <tr>
                        <th>Codigo</th>
                        <th>Solicitante</th>
                        <th>Departamento</th>
                        <th>Descripcion</th>
                        <th>Prioridad</th>
                        <th>Estado</th>
                        <th>Fecha de registro</th>
                        <th>Cambiar estado</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($solicitudes as $s)
                    <tr>
                        <td>{{ $s->id }}</td>
                        <td>{{ $s->solicitante }}</td>
                        <td>{{ $s->departamento }}</td>
                        <td>{{ $s->descripcion }}</td>
                        <td><span class="badge {{ \Illuminate\Support\Str::slug($s->prioridad) }}">{{ $s->prioridad }}</span></td>
                        <td><span class="badge {{ \Illuminate\Support\Str::slug($s->estado) }}">{{ $s->estado }}</span></td>
                        <td>{{ $s->created_at?->format('d/m/Y H:i') }}</td>
                        <td>
                            <form class="estado-form" method="POST" action="{{ route('solicitudes.estado', $s) }}">
                                @csrf
                                @method('PATCH')
                                <select name="estado">
                                    @foreach ($estados as $e)
                                        <option value="{{ $e }}" @selected($s->estado === $e)>{{ $e }}</option>
                                    @endforeach
                                </select>
                                <button class="chico" type="submit">Guardar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </section>
</main>
</body>
</html>
