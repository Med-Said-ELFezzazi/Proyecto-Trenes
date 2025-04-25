<?= $this->extend("plantillas/layout2zonas"); ?>

<?= $this->section("principal"); ?>

    <h1 class="text-center">Gestión de Rutas</h1>

    <table border="1" class="table table-striped table-bordered text-center">
        <thead>
            <tr>
                <th>ID</th>
                <th>Número de Serie</th>
                <th>Origen</th>
                <th>Destino</th>
                <th>Hora de Salida</th>
                <th>Hora de Llegada</th>
                <th>Tarifa</th>
                <th>Fecha</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($rutas)): ?>
                <?php foreach ($rutas as $ruta): ?>
                    <tr>
                        <td><?= esc($ruta->id_ruta) ?></td>
                        <td><?= esc($ruta->num_serie) ?></td>
                        <td><?= esc($ruta->origen) ?></td>
                        <td><?= esc($ruta->destino) ?></td>
                        <td><?= esc($ruta->hora_salida) ?></td>
                        <td><?= esc($ruta->hora_llegada) ?></td>
                        <td><?= esc($ruta->tarifa) ?></td>
                        <td><?= esc($ruta->fecha) ?></td>
                        <td>
                            <a href="<?= base_url('admin/rutas/modificar/' . $ruta->id_ruta) ?>" class="btn btn-warning">Editar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="9">No hay rutas disponibles.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <?php if (session()->getFlashdata('success')): ?>
        <div style="color: green;"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div style="color: red;"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

<?= $this->endSection(); ?>