<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registros RoboRage</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        h1 { text-align: center; margin-bottom: 4px; }
        .subtitle { text-align: center; color: #666; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ccc; padding: 6px 8px; text-align: left; }
        th { background-color: #1a1a2e; color: #fff; }
        tr:nth-child(even) { background-color: #f4f4f4; }
        .badge {
            display: inline-block; padding: 2px 8px; border-radius: 4px;
            font-size: 10px; font-weight: bold; color: #fff;
        }
        .badge-paid { background-color: #27ae60; }
        .badge-pending { background-color: #e67e22; }
    </style>
</head>
<body>
    <h1>Registros RoboRage</h1>
    <p class="subtitle">Generado el {{ now()->format('d/m/Y H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Equipo</th>
                <th>Robot</th>
                <th>Institución</th>
                <th>Email Personal</th>
                <th>Email Institucional</th>
                <th>Categoría</th>
                <th>Subcategoría</th>
                <th>Código</th>
                <th>Monto</th>
                <th>Estado Pago</th>
            </tr>
        </thead>
        <tbody>
            @forelse($registers as $i => $register)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $register->team_name }}</td>
                    <td>{{ $register->robot_name }}</td>
                    <td>{{ $register->institution }}</td>
                    <td>{{ $register->personal_email }}</td>
                    <td>{{ $register->institutional_email }}</td>
                    <td>{{ $register->category->name ?? '—' }}</td>
                    <td>{{ $register->subcategory->name ?? '—' }}</td>
                    <td>{{ $register->registration_code }}</td>
                    <td>${{ number_format($register->amount, 2) }}</td>
                    <td>
                        <span class="badge {{ $register->payment_status === 'paid' ? 'badge-paid' : 'badge-pending' }}">
                            {{ $register->payment_status === 'paid' ? 'Pagado' : 'Pendiente' }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="11" style="text-align:center;">No hay registros.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
