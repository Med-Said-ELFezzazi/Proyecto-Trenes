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
                <?= form_input(['type' => 'text', 'name' => 'origen', 'id' => 'origen', 'value' => set_value('origen'), 'required' => true, 'class' => 'form-control']) ?>
            </div>

            <div class="form-group">
                <?= form_label('Destino:', 'destino') ?>
                <?= form_input(['type' => 'text', 'name' => 'destino', 'id' => 'destino', 'value' => set_value('destino'), 'required' => true, 'class' => 'form-control']) ?>
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

<?= $this->endSection(); ?>