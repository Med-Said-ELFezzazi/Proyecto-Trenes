<?= $this->section("title") ?>
    Tu opinión
<?= $this->endSection(); ?>

<h1 class="text-center">ULTIMAS RESERVAS COMPLETADAS</h1>
<!-- Error  -->
<?php if (isset($msgErrOpin)): ?>
    <div class="alert alert-danger text-center" role="alert">
        <?= $msgErrOpin ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php endif; ?>

<!-- Opinion guardado bien -->
<?php if (isset($msgInfoOpi)): ?>
    <div class="alert alert-success text-center" role="alert">
        <?= $msgInfoOpi ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php endif; ?>



<?php if(isset($msgNoReservas)): ?>
    <?= $msgNoReservas; ?>
<?php else: ?>
    <?= form_open(site_url('/opinion/add'), ['method' => 'post']); ?>
    <table class="table table-striped table-bordered text-center">
        <thead>
            <tr>
                <th></th>
                <th>Nº ruta</th>
                <th>Origen</th>
                <th>Destino</th>
                <th>Hora llegada</th>
                <th>Tren</th>
            </tr>
        </thead>
        <tbody>
            <?php 
                foreach($datosOpinion as $reserva): ?>
                <tr>
                    <td>
                        <?= form_input(['name' => 'reservasSel[]',
                                        'type' => 'checkbox',
                                        'value' => $reserva['id_ticket']]);
                        ?>
                    </td>
                    <td><?= $reserva['id_ruta']; ?></td>
                    <td><?= $reserva['cOrigen']; ?></td>
                    <td><?= $reserva['cDestino']; ?></td>
                    <td><?= $reserva['hLlegada']; ?></td>
                    <td><img src="<?= base_url('/images/trenes/'.$reserva['imagen']); ?>" height="150px" width="250px" /></td>
                </tr>

            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="container" style="max-width: 600px; margin: auto;">
        <div class="form-group text-center">
            <label for="opinion">Opinión</label>
            <?php
                echo form_textarea(['name' => 'opinion', 
                            'placeholder' => 'Introduce tu opinión',
                            'class' => 'form-control']);
                echo '<br>';
                echo form_input(['name' => 'guardarOpinion',
                                'type' => 'submit',
                                'value' => 'Guardar opinión',
                                'class' => 'btn btn-primary']);
            ?>
        </div>
    </div>
    <?= form_close(); ?>

<?php endif; ?>