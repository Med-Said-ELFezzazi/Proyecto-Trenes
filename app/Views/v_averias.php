
<?= $this->section("title") ?>
    Lista de averías
<?= $this->endSection(); ?>

<div class="row">
    <!-- Filtros -->
    <h1 class="text-center">Lista de averías</h1>
    <!-- Botón para cargar el formulario de añadir averia -->
    <div class="col-12 mb-2">
        <?= form_open(current_url(), ['method' => 'post']); ?>
            <?= form_hidden('mostrarForm', '1'); ?>
            <?= form_input(['type' => 'submit', 'name' => 'mostrarForm', 'value' => 'Añadir Nueva Avería', 'class' => 'btn btn-primary float-left', 'style' => 'color: white;']); ?>
        <?= form_close(); ?>
    </div>
    <!-- msg info de eliminacion -->
     <?php
        if (isset($eliminacionAveria)) {
            if ($eliminacionAveria) {
                echo '<div class="alert alert-success text-center" role="alert">';
                    echo 'La avería ha sido eliminada correctamente';
                    echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
                        echo '<span aria-hidden="true">&times;</span>';
                    echo '</button>';
                echo '</div>';
            } else {
                echo '<div class="alert alert-danger text-center" role="alert">';
                    echo 'Error al eliminar la avería de la BD!';
                    echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
                        echo '<span aria-hidden="true">&times;</span>';
                    echo '</button>';
                echo '</div>';
            }
        }
    ?>

    <div class="col-md-3" style="background-color:rgb(13, 151, 244);">
        <div class="boxHorariosHome MT20">
            <div class="contCampos" style="padding: 5px;">
                <h2 style="color: white;" class="text-center">Filtro</h2>
                <hr>
                <?= form_open(current_url(), ['method' => 'post']); ?>
                <p>
                    <?= form_label('Número de serie:', '', ['style' => 'color:white; font-size: 18px;']) ?>
                    <?php 
                        $numSerieSel = $_POST['numSerieAveria'] ?? ''; 
                        echo form_input(['type' => 'text', 'name' => 'numSerieAveria', 'value' => $numSerieSel, 'class' => 'form-control']);
                    ?>
                </p>
                <p>
                    <?= form_label('Fecha:', '', ['style' => 'color:white; font-size: 18px;']) ?>
                    <?php
                        $fechaSel = $_POST['fechaAveria'] ?? '';
                        echo form_input(['type' => 'date', 'name' => 'fechaAveria', 'value' => $fechaSel, 'class' => 'form-control']);
                    ?>
                </p>
                <p>
                    <?= form_label('Coste:', '', ['style' => 'color:white; font-size: 18px;']) ?>
                    <br>
                    <?php
                        $costeMinSel = $_POST['costeMinAveria'] ?? '';
                        $costeMaxSel = $_POST['costeMaxAveria'] ?? '';
                        echo form_input(['type' => 'number', 'name' => 'costeMinAveria', 'min' => '0', 'value' => $costeMinSel, 'placeholder' => 'Mín', 'style' => 'display: inline-block; width: 45%;', 'class' => 'form-control']);
                        echo '<b style="color: white;"> - </b>';
                        echo form_input(['type' => 'number', 'name' => 'costeMaxAveria', 'min' => '0', 'value' => $costeMaxSel, 'placeholder' => 'Máx', 'style' => 'display: inline-block; width: 45%;', 'class' => 'form-control']);
                    ?>
                </p>
                <p>
                    <?= form_label('Estado de reparación:', '', ['style' => 'color:white; font-size: 18px;']) ?>
                    <br>
                    <?php
                        $estadoAveria = $_POST['estadoAveria'] ?? 2;

                        echo form_radio(['name' => 'estadoAveria', 'value' => 1, 'checked' => $estadoAveria == 1]);
                        echo form_label('Reparado', 'reparado', ['style' => 'color: white; margin-Right: 10px']);
                    
                        echo form_radio(['name' => 'estadoAveria', 'value' => 0, 'checked' => $estadoAveria == 0]);
                        echo form_label('Averiado', 'averiado', ['style' => 'color: white; margin-Right: 10px']);

                        echo form_radio(['name' => 'estadoAveria', 'value' => 2, 'checked' => $estadoAveria == 2]);
                        echo form_label('Ambos', 'ambos', ['style' => 'color: white; margin-Right: 10px']);
                    ?>
                </p>
                <div class="text-center">
                    <?= form_submit('aplicarFiltros', 'Aplicar Filtros', ['class' => 'btn btn-light']) ?>
                    <a href="<?= current_url() ?>" class="btn btn-secondary">Limpiar Filtros</a>
                </div>
                <?= form_close(); ?>
            </div>
        </div>
    </div>

    <!-- Tabla -->
    <div class="col-md-9">
        <table class="table table-striped table-bordered text-center">
            <thead>
                <tr>
                    <th>Nº</th>
                    <th>Número de serie</th>
                    <th>Descripción</th>
                    <th>Fecha y hora</th>
                    <th>Coste</th>
                    <th>Reparada</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    // Mostrar datos filtrados o todos los datos
                    $averias = isset($datosFiltrados) ? $datosFiltrados : $datosAverias;
                    foreach($averias as $averia) :
                ?>
                    <tr>
                        <td>
                            <?= $averia->id_averia ?>
                        </td>
                        <td>
                            <?= $averia->num_serie ?>
                        </td>
                        <td>
                            <?= $averia->descripcion ?>
                        </td>
                        <td>
                            <?= date('d/m/Y H:i', strtotime($averia->fecha)) ?>
                        </td>
                        <td>
                            <?= $averia->coste . '€' ?>
                        </td>
                        <td>
                            <?php if ($averia->reparada): ?>
                                <img src="<?= base_url('/images/reparada.png') ?>" alt="Reparada" width="30" height="30">
                            <?php else: ?>
                                <img src="<?= base_url('/images/averiada.png') ?>" alt="Averiada" width="30" height="30">
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="<?= site_url("/admin/averias/modificar/" . $averia->id_averia) ?>" class="btn btn-warning">Editar</a>
                        </td>
                        <td>
                            <?= form_open(current_url(), ['method' => 'post']); ?>
                                <?= form_hidden('id_averiaBorrar', $averia->id_averia); ?>
                                <?= form_input(['type' => 'submit', 'value' => 'Eliminar', 'class' => 'btn btn-danger']); ?>
                            <?= form_close(); ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>