<?= $this->extend("plantillas/layout2zonas"); ?>

<?= $this->section("title") ?>
    Gestión de rutas
<?= $this->endSection(); ?>

<?= $this->section("principal"); ?>

    <h1 class="text-center">Gestión de Rutas</h1>

    <div>
        <a href="<?= base_url('admin/rutas/aniadirRuta') ?>" class="btn btn-primary">Añadir Nueva Ruta</a>
    </div>
    <br>

    <div class="d-flex justify-content-between">
        <div class="col-md-3" style="background-color:rgb(13, 151, 244);">
            <div class="boxHorariosHome MT20">
                <div class="contCampos" style="padding: 5px;">
                    <h2 style="color: white;" class="text-center">Filtro</h2>
                    <hr>
                    <?= form_open(current_url(), ['method' => 'post']); ?>
                        <p>
                            <?= form_label('Origen:', 'origen', ['style' => 'color:white; font-size: 18px;']) ?>
                            <?= form_input(['type' => 'text', 'name' => 'origen', 'id' => 'origen', 'value' => set_value('origen'), 'class' => 'form-control']) ?>
                        </p>
                        <p>
                            <?= form_label('Destino:', 'destino', ['style' => 'color:white; font-size: 18px;']) ?>
                            <?= form_input(['type' => 'text', 'name' => 'destino', 'id' => 'destino', 'value' => set_value('destino'), 'class' => 'form-control']) ?>
                        </p>
                        <p>
                            <?= form_label('Fecha:', 'fecha', ['style' => 'color:white; font-size: 18px;']) ?>
                            <?= form_input(['type' => 'date', 'name' => 'fecha', 'id' => 'fecha', 'value' => set_value('fecha'), 'class' => 'form-control']) ?>
                        </p>
                        <p>
                            <?= form_label('Número de Serie:', 'num_serie', ['style' => 'color:white; font-size: 18px;']) ?>
                            <?= form_input(['type' => 'text', 'name' => 'num_serie', 'id' => 'num_serie', 'value' => set_value('num_serie'), 'class' => 'form-control']) ?>
                        </p>
                        <div class="row">
                            <div class="col">
                                <?= form_label('Precio mínimo:', 'precio_min', ['style' => 'color:white; font-size: 18px;']) ?>
                                <?= form_input(['type' => 'number', 'name' => 'precio_min', 'id' => 'precio_min', 'value' => set_value('precio_min'), 'step' => '1', 'min' => $precioMinimo, 'max' => $precioMaximo, 'class' => 'form-control']) ?>
                            </div>
                            <div class="col">
                                <?= form_label('Precio máximo:', 'precio_max', ['style' => 'color:white; font-size: 18px;']) ?>
                                <?= form_input(['type' => 'number', 'name' => 'precio_max', 'id' => 'precio_max', 'value' => set_value('precio_max'), 'step' => '1', 'min' => $precioMinimo, 'max' => $precioMaximo, 'class' => 'form-control' ]) ?>
                            </div>
                        </div>
                        <br>
                        <div class="text-center">
                            <?= form_submit('filtrar', 'Aplicar Filtros', ['class' => 'btn btn-light']) ?>
                            <a href="<?= current_url() ?>" class="btn btn-secondary">Limpiar Filtros</a>
                        </div>
                    <?= form_close(); ?>
                </div>
            </div>
        </div>

        <table border="1" class="table table-striped table-bordered text-center ml-3">
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
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($rutas)): ?>
                    <?php foreach ($rutas as $ruta): ?>
                        <?php 
                            $hoy = date('Y-m-d');
                            $esPasada = $ruta->fecha < $hoy;
                        ?>
                        <tr>
                            <td>
                                <?= $ruta->id_ruta ?>
                            </td>
                            <td>
                                <?= $ruta->num_serie ?>
                            </td>
                            <td>
                                <?= $ruta->origen ?>
                            </td>
                            <td>
                                <?= $ruta->destino ?>
                            </td>
                            <td>
                                <?= $ruta->hora_salida ?>
                            </td>
                            <td>
                                <?= $ruta->hora_llegada ?>
                            </td>
                            <td>
                                <?= $ruta->tarifa ?>
                            </td>
                            <td>
                                <?= $ruta->fecha ?>
                            </td>
                            <td>
                                <?php if ($esPasada): ?>
                                    <button class="btn btn-secondary" disabled>Editar</button>
                                <?php else: ?>
                                    <a href="<?= base_url('admin/rutas/modificar/' . $ruta->id_ruta) ?>" class="btn btn-warning">Editar</a>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="<?= base_url('admin/rutas/eliminar/' . $ruta->id_ruta) ?>" class="btn btn-danger" onclick="return confirm('¿Estás seguro de eliminar esta ruta?')">Eliminar</a>
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
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div style="color: green;"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div style="color: red;"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

<?= $this->endSection(); ?>