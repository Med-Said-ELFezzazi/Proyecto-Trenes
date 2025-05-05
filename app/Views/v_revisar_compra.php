<?= $this->extend("plantillas/layout2zonas"); ?>

<?= $this->section("principal"); ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Revisión de Compra</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card-viaje {
            border-left: 6px solid #0d6efd;
            border-radius: 12px;
            margin-bottom: 24px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.07);
        }
        .card-header-viaje {
            background: #0d6efd;
            color: #fff;
            font-weight: bold;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
            padding: 14px;
            font-size: 1.2em;
        }
        .asiento-badge {
            background: #4caf50;
            color: #fff;
            border-radius: 8px;
            padding: 4px 10px;
            margin: 2px;
            font-size: 1em;
            display: inline-block;
        }
        .info-table td {
            padding: 7px 12px;
        }
    </style>
</head>
<body>
<div class="container my-5">
    <h2 class="mb-4 text-primary">📝 Revisión de Compra</h2>

    <div class="mb-4">
        <h5>Información de la reserva:</h5>
        <ul>
            <li><strong>Nº de billetes:</strong> <?= esc($Numbilletes) ?> <?= isset($servicio_vuelta) ? 'ida y vuelta' : 'solo ida' ?></li>
            <li><strong>Precio por elegir asiento:</strong>
                <?= (isset($asientosIda) && !empty($asientosIda)) ? (esc($Numbilletes) * 5) . ' €' : '0 €' ?>
            </li>
        </ul>
    </div>

    <!-- Tarjeta Viaje Ida -->
    <div class="card card-viaje mb-3">
        <div class="card-header card-header-viaje">
            🚆 Viaje Ida
        </div>
        <div class="card-body">
            <table class="table info-table">
                <tr>
                    <td><strong>Origen:</strong></td>
                    <td><?= esc($servicio_ida->origen) ?></td>
                    <td><strong>Destino:</strong></td>
                    <td><?= esc($servicio_ida->destino) ?></td>
                </tr>
                <tr>
                    <td><strong>Fecha salida:</strong></td>
                    <td><?= date('d/m/Y H:i', strtotime($servicio_ida->hora_salida)) ?></td>
                    <td><strong>Precio por billete:</strong></td>
                    <td><?= esc($servicio_ida->tarifa) ?> €</td>
                </tr>
                <?php if (isset($asientosIda) && !empty($asientosIda)): ?>
                <tr>
                    <td><strong>Asientos:</strong></td>
                    <td colspan="3">
                        <?php foreach ($asientosIda as $asiento): ?>
                            <span class="asiento-badge"><?= esc($asiento) ?></span>
                        <?php endforeach; ?>
                    </td>
                </tr>
                <?php endif; ?>
            </table>
        </div>
    </div>

    <!-- Tarjeta Viaje Vuelta (si aplica) -->
    <?php if (isset($servicio_vuelta)): ?>
    <div class="card card-viaje mb-3">
        <div class="card-header card-header-viaje">
            🚆 Viaje Vuelta
        </div>
        <div class="card-body">
            <table class="table info-table">
                <tr>
                    <td><strong>Origen:</strong></td>
                    <td><?= esc($servicio_vuelta->origen) ?></td>
                    <td><strong>Destino:</strong></td>
                    <td><?= esc($servicio_vuelta->destino) ?></td>
                </tr>
                <tr>
                    <td><strong>Fecha salida:</strong></td>
                    <td><?= date('d/m/Y H:i', strtotime($servicio_vuelta->hora_salida)) ?></td>
                    <td><strong>Precio por billete:</strong></td>
                    <td><?= esc($servicio_vuelta->tarifa) ?> €</td>
                </tr>
                <?php if (isset($asientosVuelta) && !empty($asientosVuelta)): ?>
                <tr>
                    <td><strong>Asientos:</strong></td>
                    <td colspan="3">
                        <?php foreach ($asientosVuelta as $asiento): ?>
                            <span class="asiento-badge"><?= esc($asiento) ?></span>
                        <?php endforeach; ?>
                    </td>
                </tr>
                <?php endif; ?>
            </table>
        </div>
    </div>
    <?php endif; ?>

    <!-- Precio total -->
    <div class="alert alert-info text-end">
        <h4>Total a pagar: <?= esc($total) ?> €</h4>
    </div>

    <!-- Botón de confirmar -->
    <?= form_open(base_url('reserva/confirmarCompra')); ?>
        <button type="submit" class="btn btn-success btn-lg px-5">Confirmar Compra</button>
        <?= anchor("/reserva", "VOLVER A RESERVA", ['class' => 'btn btn-primary btn-lg px-5']); ?>
    <?= form_close(); ?>
</div>
</body>
</html>
<?= $this->endSection(); ?>