<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro {{ $register->registration_code }}</title>
    <style>
        body { font-family: sans-serif; font-size: 13px; color: #333; margin: 40px; }
        h1 { text-align: center; margin-bottom: 4px; }
        .subtitle { text-align: center; color: #666; margin-bottom: 30px; }
        .card {
            border: 1px solid #ddd; border-radius: 8px; padding: 24px;
            max-width: 500px; margin: 0 auto;
        }
        .row { display: flex; margin-bottom: 10px; }
        .label { font-weight: bold; width: 160px; color: #555; }
        .value { flex: 1; }
        .badge {
            display: inline-block; padding: 3px 10px; border-radius: 4px;
            font-size: 11px; font-weight: bold; color: #fff;
        }
        .badge-paid { background-color: #27ae60; }
        .badge-pending { background-color: #e67e22; }
        .footer { text-align: center; color: #999; font-size: 10px; margin-top: 40px; }
    </style>
</head>
<body>
    <h1>Comprobante de Registro</h1>
    <p class="subtitle">RoboRage</p>

    <div class="card">
        <div class="row">
            <span class="label">Código:</span>
            <span class="value">{{ $register->registration_code }}</span>
        </div>
        <div class="row">
            <span class="label">Equipo:</span>
            <span class="value">{{ $register->team_name }}</span>
        </div>
        <div class="row">
            <span class="label">Robot:</span>
            <span class="value">{{ $register->robot_name }}</span>
        </div>
        <div class="row">
            <span class="label">Nivel Educativo:</span>
            <span class="value">{{ $register->education_level }}</span>
        </div>
        <div class="row">
            <span class="label">Institución:</span>
            <span class="value">{{ $register->institution }}</span>
        </div>
        <div class="row">
            <span class="label">Email Personal:</span>
            <span class="value">{{ $register->personal_email }}</span>
        </div>
        <div class="row">
            <span class="label">Email Institucional:</span>
            <span class="value">{{ $register->institutional_email }}</span>
        </div>
        <div class="row">
            <span class="label">Categoría:</span>
            <span class="value">{{ $register->category->name ?? '—' }}</span>
        </div>
        <div class="row">
            <span class="label">Subcategoría:</span>
            <span class="value">{{ $register->subcategory->name ?? '—' }}</span>
        </div>
        <div class="row">
            <span class="label">Monto:</span>
            <span class="value">${{ number_format($register->amount, 2) }}</span>
        </div>
        <div class="row">
            <span class="label">Estado de Pago:</span>
            <span class="value">
                <span class="badge {{ $register->payment_status === 'paid' ? 'badge-paid' : 'badge-pending' }}">
                    {{ $register->payment_status === 'paid' ? 'Pagado' : 'Pendiente' }}
                </span>
            </span>
        </div>
        @if($register->payment_date)
        <div class="row">
            <span class="label">Fecha de Pago:</span>
            <span class="value">{{ $register->payment_date }}</span>
        </div>
        @endif
        @if($register->comments)
        <div class="row">
            <span class="label">Comentarios:</span>
            <span class="value">{{ $register->comments }}</span>
        </div>
        @endif
    </div>

    <p class="footer">Generado el {{ now()->format('d/m/Y H:i') }}</p>
</body>
</html>
