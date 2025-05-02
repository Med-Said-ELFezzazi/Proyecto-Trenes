<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Elegir Asiento</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Añade aquí tus estilos CSS adicionales -->
    <style>
    .vagon {
        border: 2px solid #0d6efd;
        border-radius: 15px;
        padding: 20px;
        margin-bottom: 30px;
        background: #f8f9fa;
        box-shadow: 0 2px 8px rgba(0,0,0,0.07);
        width: 50%;
    }

    .asientos-tren {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
    }

    .fila-asientos {
        display: flex;
        justify-content: flex-start;
        margin-bottom: 10px;
    }

    .asiento-tren {
        margin: 0 6px;
        position: relative;
    }

    .asiento-hueco {
        visibility: hidden;
    }

    .asiento-tren input[type="checkbox"] {
        display: none;
    }

    .asiento-tren label {
        display: block;
        width: 32px;
        height: 32px;
        background: #e0e0e0;
        border-radius: 6px;
        border: 2px solid #bbb;
        text-align: center;
        line-height: 32px;
        font-weight: bold;
        cursor: pointer;
        transition: background 0.2s, border 0.2s;
    }

    .asiento-tren input[type="checkbox"]:checked + label {
        background: #4caf50;
        border-color: #388e3c;
        color: #fff;
    }

    .asiento-tren input[type="checkbox"]:disabled + label {
        background: #ccc;
        border-color: #aaa;
        color: #888;
        cursor: not-allowed;
    }

    .pasillo {
        height: 18px;
        width: 100%;
        background: repeating-linear-gradient(
            90deg, #fff 0, #fff 10px, #ccc 10px, #ccc 20px
        );
        margin: 10px 0 10px 0;
        border-radius: 6px;
    }

    .asiento-ocupado {
        background: #ccc !important;
        color: #888 !important;
        border-color: #aaa !important;
        cursor: not-allowed !important;
    }
    </style>
</head>

<body>
    <div class="container my-5">
        <h2>Elige tus asientos</h2>

        <?= form_open(base_url('reserva/revisarCompra')); ?>
        <input type="hidden" id="numBilletes" value="<?= $numBilletes ?>">
        <input type="hidden" name="servicioSel" value="<?= $idRutaIda ?>">
        <?php if ($idRutaVuelta) : ?>
            <input type="hidden" name="servicioVueltaSel" value="<?= $idRutaVuelta ?>">
        <?php endif; ?>

        <h3>Ida - Asientos</h3>
        <?php foreach ($asientos_ida as $vagon => $asientosVagon): ?>
            <div class="vagon mb-4">
                <h4>Vagón <?= $vagon ?></h4>
                <div class="asientos-tren">
                    <?php
                    $totalAsientos = count($asientosVagon);
                    $filas = 4;
                    $columnas = ceil($totalAsientos / $filas);
                    $asientoIndex = 0;
                    for ($fila = 0; $fila < $filas; $fila++):
                    ?>
                        <?php if ($fila == 2): // Pasillo después de la segunda fila ?>
                            <div class="pasillo"></div>
                        <?php endif; ?>
                        <div class="fila-asientos">
                            <?php
                            // Calcula cuántos asientos quedan por colocar
                            $asientosRestantes = $totalAsientos - $asientoIndex;
                            // Si es la última fila y sobran asientos, solo coloca los que quedan
                            $asientosEnEstaFila = ($asientosRestantes < $columnas) ? $asientosRestantes : $columnas;
                            for ($col = 0; $col < $asientosEnEstaFila; $col++, $asientoIndex++):
                                $asiento = $asientosVagon[$asientoIndex];
                            ?>
                                <div class="asiento-tren">
                                    <input type="checkbox" class="asiento-checkbox-ida"
                                        id="asiento_ida_<?= $asiento['numero'] ?>"
                                        name="asientos_ida[]" value="<?= $asiento['numero'] ?>"
                                        <?= !empty($asiento['ocupado']) ? 'disabled' : '' ?>>
                                        <label for="asiento_ida_<?= $asiento['numero'] ?>"
                                            class="<?= !empty($asiento['ocupado']) ? 'asiento-ocupado' : '' ?>">
                                            <?= $asiento['numero'] ?>
                                        </label>
                                </div>
                            <?php endfor; ?>
                            <?php
                            // Si es la última fila y hay menos asientos que columnas, añade huecos a la derecha
                            if ($asientosEnEstaFila < $columnas):
                                for ($hueco = 0; $hueco < $columnas - $asientosEnEstaFila; $hueco++): ?>
                                    <div class="asiento-tren asiento-hueco"></div>
                                <?php endfor;
                            endif;
                            ?>
                        </div>
                    <?php endfor; ?>
                </div>
            </div>
        <?php endforeach; ?>


        <?php if ($idRutaVuelta) : ?>
            <h3>Tren de Vuelta - Asientos</h3>
            <?php foreach ($asientos_vuelta as $vagon => $asientosVagon): ?>
                <div class="vagon mb-4">
                    <h4>Vagón <?= $vagon ?></h4>
                    <div class="asientos-tren">
                        <?php
                        $totalAsientos = count($asientosVagon);
                        $filas = 4;
                        $columnas = ceil($totalAsientos / $filas);
                        $asientoIndex = 0;
                        for ($fila = 0; $fila < $filas; $fila++):
                        ?>
                            <?php if ($fila == 2): // Pasillo después de la segunda fila ?>
                                <div class="pasillo"></div>
                            <?php endif; ?>
                            <div class="fila-asientos">
                                <?php
                                // Calcula cuántos asientos quedan por colocar
                                $asientosRestantes = $totalAsientos - $asientoIndex;
                                // Si es la última fila y sobran asientos, solo coloca los que quedan
                                $asientosEnEstaFila = ($asientosRestantes < $columnas) ? $asientosRestantes : $columnas;
                                for ($col = 0; $col < $asientosEnEstaFila; $col++, $asientoIndex++):
                                    $asiento = $asientosVagon[$asientoIndex];
                                ?>
                                    <div class="asiento-tren">
                                        <input type="checkbox" class="asiento-checkbox-vuelta"
                                            id="asiento_vuelta_<?= $asiento['numero'] ?>"
                                            name="asientos_vuelta[]" value="<?= $asiento['numero'] ?>">
                                        <label for="asiento_vuelta_<?= $asiento['numero'] ?>"><?= $asiento['numero'] ?></label>
                                    </div>
                                <?php endfor; ?>
                                <?php
                                // Si es la última fila y hay menos asientos que columnas, añade huecos a la derecha
                                if ($asientosEnEstaFila < $columnas):
                                    for ($hueco = 0; $hueco < $columnas - $asientosEnEstaFila; $hueco++): ?>
                                        <div class="asiento-tren asiento-hueco"></div>
                                    <?php endfor;
                                endif;
                                ?>
                            </div>
                        <?php endfor; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <button type="submit" class="btn btn-primary">Revisar Compra</button>
        <?= form_close(); ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        let numBilletes = parseInt(document.getElementById('numBilletes').value);

        function gestionLimiteCheckboxes(selector) {
            let checkboxes = Array.from(document.querySelectorAll(selector));
            let checkedOrder = [];

            checkboxes.forEach(function(cb) {
                cb.addEventListener('change', function() {
                    if (cb.checked) {
                        checkedOrder.push(cb);
                        if (checkedOrder.length > numBilletes) {
                            // Desmarcar el primero que se marcó
                            let primero = checkedOrder.shift();
                            primero.checked = false;
                        }
                    } else {
                        // Si se desmarca manualmente, quitarlo del array
                        checkedOrder = checkedOrder.filter(c => c !== cb);
                    }
                });
            });
        }

        // Limitar en IDA
        gestionLimiteCheckboxes('.asiento-checkbox-ida');

        // Limitar en VUELTA (si existen)
        if (document.querySelectorAll('.asiento-checkbox-vuelta').length > 0) {
            gestionLimiteCheckboxes('.asiento-checkbox-vuelta');
        }
    });
    </script>
</body>
</html>
