<?= $this->extend("plantillas/layout2zonas"); ?>

<?= $this->section("principal"); ?>

    <h1 class="text-center">Modificar Ruta</h1>

    <?php if (isset($ruta)): ?>
        <table class="table table-striped table-bordered text-center">
            <?= form_open(base_url('admin/rutas/modificar/' . $ruta->id_ruta)) ?>
                <tr>
                    <th><?= form_label('Número de Serie:', 'num_serie') ?></th>
                    <th><?= form_label('Origen:', 'origen') ?></th>
                    <th><?= form_label('Destino:', 'destino') ?></th>
                    <th><?= form_label('Hora de Salida:', 'hora_salida') ?></th>
                    <th><?= form_label('Hora de Llegada:', 'hora_llegada') ?></th>
                    <th><?= form_label('Tarifa:', 'tarifa') ?></th>
                    <th><?= form_label('Fecha:', 'fecha') ?></th>
                    <th></th>
                    <th></th>
                </tr>
                <tr>
                    <td>
                        <?= form_input([
                            'type' => 'text',
                            'name' => 'num_serie',
                            'id' => 'num_serie',
                            'value' => set_value('num_serie', esc($ruta->num_serie)),
                            'required' => true,
                        ]) ?>
                    </td>
                    <td>
                        <?= form_input([
                            'type' => 'text',
                            'name' => 'origen',
                            'id' => 'origen',
                            'value' => set_value('origen', esc($ruta->origen)),
                            'required' => true,
                        ]) ?>
                    </td>
                    <td>
                        <?= form_input([
                            'type' => 'text',
                            'name' => 'destino',
                            'id' => 'destino',
                            'value' => set_value('destino', esc($ruta->destino)),
                            'required' => true,
                        ]) ?>
                    </td>
                    <td>
                        <?= form_input([
                            'type' => 'time',
                            'name' => 'hora_salida',
                            'id' => 'hora_salida',
                            'value' => set_value('hora_salida', esc($ruta->hora_salida)),
                            'required' => true,
                        ]) ?>
                    </td>
                    <td>
                        <?= form_input([
                            'type' => 'time',
                            'name' => 'hora_llegada',
                            'id' => 'hora_llegada',
                            'value' => set_value('hora_llegada', esc($ruta->hora_llegada)),
                            'required' => true,
                        ]) ?>
                    </td>
                    <td>
                        <?= form_input([
                            'type' => 'number',
                            'step' => '0.01',
                            'name' => 'tarifa',
                            'id' => 'tarifa',
                            'value' => set_value('tarifa', esc($ruta->tarifa)),
                            'required' => true,
                        ]) ?>
                    </td>
                    <td>
                        <?= form_input([
                            'type' => 'date',
                            'name' => 'fecha',
                            'id' => 'fecha',
                            'value' => set_value('fecha', esc($ruta->fecha)),
                            'required' => true,
                        ]) ?>
                    </td>
                    <td>
                        <?= form_submit('submitModificarRutas', 'Guardar Cambios', ['class' => 'btn btn-success']) ?>
                    </td>
                    <td>
                        <a href="<?= base_url('admin/rutas') ?>" class="btn btn-danger">Cancelar</a>
                    </td>
                </tr>
            <?= form_close() ?>
        </table>
    <?php else: ?>
        <p>No se encontró la ruta solicitada.</p>
    <?php endif; ?>

    <?php if (session()->getFlashdata('success')): ?>
        <div style="color: green;"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div style="color: red;"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

<?= $this->endSection(); ?>