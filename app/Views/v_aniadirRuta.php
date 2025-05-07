<?= $this->extend("plantillas/layout2zonas"); ?>


<?= $this->section("title") ?>
    Añadir nueva ruta
<?= $this->endSection(); ?>

<?= $this->section("principal"); ?>

    <h1 class="text-center">Añadir Nueva Ruta</h1>

    <div class="container mt-5">

        <?= form_open(base_url('admin/rutas/aniadirRuta')) ?>

            <div class="form-group">
                <?= form_label('Número de Serie:', 'num_serie') ?>
                <?= form_dropdown('num_serie', $opcionesNumSerie, set_value('num_serie'), ['id' => 'num_serie', 'required' => true, 'class' => 'form-control']) ?>
            </div>

            <div class="form-group">
                <?= form_label('Origen:', 'origen') ?>
                <div class="d-flex align-items-center gap-2">
                    <?= form_dropdown('origen', $opcionesCiudades, set_value('origen'), ['id' => 'origen', 'class' => 'form-control mr-3']) ?>
                    <button type="button" id="addOrigen" class="btn btn-primary btn-sm">Añadir Ciudad</button>
                </div>
                <div id="origenInputContainer" class="mt-2" style="display: none;">
                    <?= form_input(['type' => 'text', 'name' => 'nuevo_origen', 'id' => 'nuevo_origen', 'class' => 'form-control', 'placeholder' => 'Introduce una nueva ciudad para el origen']) ?>
                </div>
            </div>

            <div class="form-group">
                <?= form_label('Destino:', 'destino') ?>
                <div class="d-flex align-items-center gap-2">
                    <?= form_dropdown('destino', $opcionesCiudades, set_value('destino'), ['id' => 'destino', 'class' => 'form-control mr-3']) ?>
                    <button type="button" id="addDestino" class="btn btn-primary btn-sm">Añadir Ciudad</button>
                </div>
                <div id="destinoInputContainer" class="mt-2" style="display: none;">
                    <?= form_input(['type' => 'text', 'name' => 'nuevo_destino', 'id' => 'nuevo_destino', 'class' => 'form-control', 'placeholder' => 'Introduce una nueva ciudad para el destino']) ?>
                </div>
            </div>

            <div class="form-group">
                <?= form_label('Hora de Salida (YYYY-MM-DD HH:mm:ss):', 'hora_salida') ?>
                <?= form_input(['type' => 'datetime', 'name' => 'hora_salida', 'id' => 'hora_salida', 'value' => set_value('hora_salida'), 'required' => true, 'class' => 'form-control']) ?>
            </div>

            <div class="form-group">
                <?= form_label('Hora de Llegada (YYYY-MM-DD HH:mm:ss):', 'hora_llegada') ?>
                <?= form_input(['type' => 'datetime', 'name' => 'hora_llegada', 'id' => 'hora_llegada', 'value' => set_value('hora_llegada'), 'required' => true, 'class' => 'form-control']) ?>
            </div>
            
            <div class="form-group">
                <?= form_label('Tarifa:', 'tarifa') ?>
                <?= form_input(['type' => 'number', 'step' => '1', 'name' => 'tarifa', 'id' => 'tarifa', 'value' => set_value('tarifa'), 'required' => true, 'class' => 'form-control']) ?>
            </div>

            <div class="form-group">
                <?= form_label('Fecha:', 'fecha') ?>
                <?= form_input(['type' => 'date', 'name' => 'fecha', 'id' => 'fecha', 'value' => set_value('fecha'), 'required' => true, 'class' => 'form-control']) ?>
            </div>

            <div class="text-center">
                <?= form_submit('submitNuevaRuta', 'Añadir Ruta', ['class' => 'btn btn-success']) ?>
                <a href="<?= base_url('admin/rutas') ?>" class="btn btn-secondary">Volver</a>
            </div><br>

        <?= form_close() ?>

        <?php if (isset($validation)): ?>
            <div class="alert alert-danger">
                <?php if (is_array($validation)): ?>
                    <ul>
                        <?php foreach ($validation as $error): ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <?= $validation->listErrors() ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>

    </div>

    <script>
        document.getElementById('addOrigen').addEventListener('click', function () {
            const container = document.getElementById('origenInputContainer');
            container.style.display = container.style.display === 'none' ? 'block' : 'none';
        });

        document.getElementById('addDestino').addEventListener('click', function () {
            const container = document.getElementById('destinoInputContainer');
            container.style.display = container.style.display === 'none' ? 'block' : 'none';
        });
    </script>

<?= $this->endSection(); ?>