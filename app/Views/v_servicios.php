<?= $this->extend("plantillas/layout2zonas"); ?>

<?= $this->section("title") ?>
    Servicios disponibles
<?= $this->endSection(); ?>  

<?= $this->section("principal"); ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Servicios Disponibles</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .ticket-card {
            border-left: 5px dashed #0d6efd;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .ticket-header {
            background-color: #0d6efd;
            color: white;
            padding: 10px;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            font-weight: bold;
            text-align: center;
        }
        .ticket-body {
            padding: 15px;
            background-color: #f8f9fa;
        }
        .info-table td {
            padding: 8px;
        }
        .ticket-footer {
            text-align: center;
            padding: 15px;
            background-color: #f8f9fa;
            border-bottom-left-radius: 10px;
            border-bottom-right-radius: 10px;
        }

        .ticket-footer a {
            display: inline-block;
            padding: 8px 20px;
            background-color: #0d6efd;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: all 0.3s ease;
            font-weight: bold;
        }

        .ticket-footer a:hover {
            background-color: #0b5ed7;
            transform: translateY(-2px);
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>
    <div class="container my-5">
        
        <!-- Título y tabla de información del viaje (como antes) -->
        <h4 class="text-center my-4 text-primary">🚌 Servicios posibles para la IDA</h4>
        <table class="table table-bordered info-table text-center">
            <tr class="table-primary">
                <td>Fecha:</td>
                <td><strong><i><?= $fecha_ida ?></i></strong></td>
                <td>Salida:</td>
                <td><strong><i><?= $ciudadOrg ?></i></strong></td>
                <td>Llegada:</td>
                <td><strong><i><?= $ciudadDes ?></i></strong></td>
            </tr>
        </table>

        <?= form_open('', ['method' => 'post', 'id' => 'formServicios']); ?>
        <div class="row">
            <?php if ($servicios_ida != null): ?>
                <?php $esPrimero = true; ?>
                <?php foreach ($servicios_ida as $servicio): ?>
                    <div class="col-md-6">
                        <div class="card ticket-card shadow-sm">
                            <div class="ticket-header">
                                Servicio <?= date('H:i', strtotime($servicio['hora_salida'])) ?> → <?= date('H:i', strtotime($servicio['hora_llegada'])) ?>
                            </div>
                            <div class="ticket-body">
                                <div class="form-check mb-2">
                                    <?php
                                        $radioData = [
                                            'class' => 'form-check-input',
                                            'name' => 'servicioSel',  // Cambiado a un solo radio
                                            'value' => $servicio['id_ruta'],
                                            'data-precio' => $servicio['precio'],
                                            'checked' => ($esPrimero ?? false),  // Verifica si es el primer servicio
                                        ];
                                        if (!$servicio['hayPlazas']) {
                                            $radioData['disabled'] = 'disabled';
                                        }
                                        echo form_radio($radioData);
                                    ?>
                                    <label class="form-check-label">Seleccionar este servicio</label>
                                </div>
                                <p><strong>Precio:</strong> <?= $servicio['precio'] ?>€</p>
                                <p><strong>Plazas libres:</strong> <?= $servicio['hayPlazas'] ? $servicio['plazas_libres'] : 'No disponible' ?></p>
                            </div>
                        </div>
                    </div>
                    <?php $esPrimero = false; ?>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center text-danger">🚫 No hay destinos disponibles.</p>
            <?php endif; ?>
        </div>

        <!-- Sección para la vuelta, si no es solo ida -->
        <?php if (session()->get('soloIda') == false): ?>
            <h4 class="text-center my-4 text-primary">🚌 Servicios posibles para la VUELTA</h4>
            <table class="table table-bordered info-table text-center">
                <tr class="table-primary">
                    <td>Fecha:</td>
                    <td><strong><i><?= $fecha_vuelta ?></i></strong></td>
                    <td>Salida:</td>
                    <td><strong><i><?= $ciudadDes ?></i></strong></td>
                    <td>Llegada:</td>
                    <td><strong><i><?= $ciudadOrg ?></i></strong></td>
                </tr>
            </table>

            <div class="row">
                <?php if ($servicios_vuelta != null): ?>
                    <?php $esPrimeroVuelta = true; ?>
                    <?php foreach ($servicios_vuelta as $servicio): ?>
                        <div class="col-md-6">
                            <div class="card ticket-card shadow-sm">
                                <div class="ticket-header">
                                    Servicio <?= date('H:i', strtotime($servicio['hora_salida'])) ?> → <?= date('H:i', strtotime($servicio['hora_llegada'])) ?>
                                </div>
                                <div class="ticket-body">
                                    <div class="form-check mb-2">
                                        <?php
                                            $radioVueltaData = [
                                                'class' => 'form-check-input',
                                                'name' => 'servicioVueltaSel',  // Cambiado a un solo radio
                                                'value' => $servicio['id_ruta'],
                                                'data-precio' => $servicio['precio'],
                                                'checked' => ($esPrimeroVuelta ?? false),  // Verifica si es el primer servicio
                                            ];
                                            if (!$servicio['hayPlazas']) {
                                                $radioVueltaData['disabled'] = 'disabled';
                                            }
                                            echo form_radio($radioVueltaData);
                                        ?>
                                        <label class="form-check-label">Seleccionar este servicio</label>
                                    </div>
                                    <p><strong>Precio:</strong> <?= $servicio['precio'] ?>€</p>
                                    <p><strong>Plazas libres:</strong> <?= $servicio['hayPlazas'] ? $servicio['plazas_libres'] : 'No disponible' ?></p>
                                </div>
                            </div>
                        </div>
                        <?php $esPrimeroVuelta = false; ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-center text-danger">⚠️ ¡LO SENTIMOS! No hay servicios de vuelta en estas fechas.</p>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php
            $asientoAleatorio = session()->get('asientoAleatorio');
            $soloIda = session()->get('soloIda');

            // Lógica para mostrar mensaje o botón
            $mostrarBoton = false;
            if ($soloIda) {
                $mostrarBoton = !empty($servicios_ida);
            } else {
                $mostrarBoton = !empty($servicios_ida) && !empty($servicios_vuelta);
            }

            // Lógica para texto y acción del botón
            if ($asientoAleatorio) {
                $botonTexto = 'Revisar Compra';
                $botonAction = base_url('reserva/revisarCompra');
            } else {
                $botonTexto = 'Elegir Asiento';
                $botonAction = base_url('reserva/elegirAsiento');
            }
        ?>

        <?php if ($mostrarBoton): ?>
            <div class="text-center mt-4">
                <?= anchor("/reserva", "VOLVER ATRÁS", ['class' => 'btn btn-primary btn-lg px-4']); ?>
                <?= form_input([
                    'type' => 'submit',
                    'name' => 'comprar',
                    'value' => $botonTexto,
                    'class' => 'btn btn-success btn-lg px-4',
                    'formaction' => $botonAction
                ]); ?>
            </div>
        <?php else: ?>
            <div class="alert alert-warning text-center mt-4">
                🚫 No hay servicios disponibles en las fechas seleccionadas.<br>
                <strong>Por favor, prueba a elegir otra fecha.</strong>
                <br>
                <br>
                <?= anchor("/reserva", "VOLVER ATRÁS", ['class' => 'btn btn-primary btn-lg px-4']); ?>
            </div>
        <?php endif; ?>
        <?= form_close(); ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?= $this->endSection(); ?>