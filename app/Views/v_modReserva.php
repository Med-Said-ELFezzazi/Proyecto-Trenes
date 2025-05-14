<?= $this->extend("plantillas/layout2zonas"); ?>

<?= $this->section("title") ?>
    Modificar reserva
<?= $this->endSection(); ?>

<?= $this->section("principal"); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

</head>
<body>
    <?php
     if (isset($reservasModificadas)) {
        echo "<h1 style='color:green; display:flex; justify-content:center;'>" . $reservasModificadas . "</h1>";
    ?>
        <div style="text-align: center; margin-top: 20px;">
            <img src="<?php echo base_url('/images/aprobado.png') ?>" alt="Aprobado" style="width: 150px; max-width: 100%; margin-bottom: 15px;">
            <p style="font-size: 1.2em; color: green; font-weight: bold;">¡Datos modificados con éxito!</p>
            <p style="font-size: 1em; color: #555;">En breve recibirás un correo de confirmación con los nuevos datos del viaje</p>
        </div>
        <div class="text-center">
            <a href="<?= site_url('/misViajes'); ?>" class="btn btn-secondary" 
                style="width: auto; padding: 10px 20px; margin-top:20px; font-size: 1em; border-radius: 5px; text-decoration: none; text-align: center;">
                Volver
            </a>
        </div>
    <?php } ?>
    
    <?php if (isset($reservasSeleccionadas)) { ?>
        <h1 class="text-center fw-bold mt-4 text-primary">Viajes Disponibles</h1>

        <h4 class="text-center text-white bg-primary rounded-pill py-2 px-3 shadow-sm">
            <?= $rutaMod['origen']; ?> <i class="fas fa-arrow-right mx-2"></i> <?= $rutaMod['destino']; ?>
        </h4>

        <p class="text-center text-muted fst-italic mt-3 mb-4">
            ''Si el asiento seleccionado ya está reservado, se asignará otro aleatorio''
        </p>

        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8" >
                    <?php foreach($viajesDisponibles as $viaje): ?>
                        <div class="card border-0 rounded-4 shadow-lg p-3 mb-4 ">
                            <div class="card-body d-flex flex-column align-items-center text-center">
                                <p class="mb-2 fs-5 text-primary">
                                    <i class="fas fa-clock me-2"></i>
                                    <strong>Hora de salida:</strong> <?= date('H:i', strtotime($viaje->hora_salida)) ?>
                                </p>
                                <p class="mb-2 fs-5 text-success">
                                    <i class="fas fa-hourglass-end me-2"></i>
                                    <strong>Hora de llegada:</strong> <?= date('H:i', strtotime($viaje->hora_llegada)) ?>
                                </p>
                                <p class="mb-0 fs-5 text-warning">
                                    <i class="fas fa-calendar-alt me-2"></i>
                                    <strong>Fecha:</strong> <?= $viaje->fecha ?>
                                </p>
                            </div>

                            <div class="text-center mt-3">
                                <?= form_open(site_url('/modificarReserva'), ['method' => 'post']) ?>
                                    <?= form_hidden('idRutaSele', $viaje->id_ruta); ?>
                                    <?= form_submit([
                                        'name' => 'newRuta',
                                        'type' => 'submit',
                                        'value' => 'Seleccionar viaje',
                                        'class' => 'btn btn-primary btn-sm px-4 py-2 shadow-sm fs-6'
                                    ]); ?>
                                <?= form_close(); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <div class="text-center">
                        <a href="<?= site_url('/misViajes'); ?>" class="btn btn-secondary" 
                            style="width: auto; padding: 10px 20px; margin-top:20px; font-size: 1em; border-radius: 5px; text-decoration: none; text-align: center;">
                            Volver
                        </a>
                    </div>
                </div>
            </div>
        </div>




        <?php } else if (isset($reservas)) { ?>            
            <h1 style='display:flex; justify-content:center;'>Modificación de <?= count($reservas) == 1 ? "la reserva " : "las reservas " ?> </h1>
            <h4 style='display:flex; justify-content:center; color:blue; padding: 10px;'><?= $ruta['origen']; ?> -> <?= $ruta['destino']; ?></h4>    

            <div style="background-color: #f8f9fa; border: 1px solid #dee2e6; border-radius: 8px; padding: 20px; max-width: 600px; margin: 20px auto; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                <?= form_open(site_url('/modificarReserva'), ['id' => 'formCancel', 'method' => 'post', 'style' => 'display:flex; flex-direction:column; align-items:center;']) ?>

                <input type="hidden" name="reservasObjs" value='<?= json_encode($reservas) ?>'>
                <input type="hidden" name="rutaObjs" value='<?= json_encode($ruta) ?>'>

                <p style="font-size: 1.2em; font-weight: bold; margin-bottom: 15px;">Selecciona las reservas que deseas modificar:</p>
                <?php 
                    foreach ($reservas as $reserva) {
                    echo "<label style='margin-bottom: 10px; font-size: 1em;'>";
                        echo form_input(['name' => 'reservas[]',
                            'type' => 'checkbox',
                            'value' => $reserva['id_ticket']]);
                        echo "<b>Reserva: </b>" . $reserva['id_ticket'] . " con asiento <b>Nº". $reserva['num_asiento']. "</b>" ;
                    echo "</label>";
                    }
                    
                    if (count($reservas) > 1) {
                    echo "<label style='margin-bottom: 10px; font-size: 1em;'>";
                        echo form_input(['name' => 'reservas[]',
                                'type' => 'checkbox',
                                'value' => "todos"]);
                        echo "<b>TODOS</b>";
                    echo "</label>";
                    }
                ?>

                <div>
                    <a href="<?= site_url('/misViajes'); ?>" class="btn btn-secondary" style="width: auto; margin-right:40px; padding: 10px 20px; font-size: 1em; border-radius: 5px; text-decoration: none; text-align: center;">
                        Volver
                    </a>
                    
                    <button type="submit" class="btn btn-warning" style="width: auto; padding: 10px 20px; margin-left:30px; margin-right:110px; font-size: 1em; border-radius: 5px;">
                        Modificar Reservas Seleccionadas
                    </button>

                </div>
                <?= form_close(); ?>
            </div>


            <script>
                document.addEventListener("DOMContentLoaded", function () {
                    const form = document.getElementById('formCancel');
                    const checkboxes = document.querySelectorAll('input[name="reservas[]"]');
                    const checkboxTodos = Array.from(checkboxes).find(cb => cb.value === "todos");

                    if (checkboxTodos) {
                        checkboxTodos.addEventListener('change', function () {
                            const marcar = this.checked;
                            checkboxes.forEach(cb => {
                                if (cb !== checkboxTodos) {
                                    cb.checked = marcar;
                                }
                            });
                        });
                    }

                    form.addEventListener('submit', function (e) {
                        const algunoMarcado = Array.from(checkboxes).some(cb => cb.checked && cb.value !== "todos");
                        if (!algunoMarcado) {
                            e.preventDefault();
                            alert('Por favor, selecciona al menos una reserva para modificar.');
                        }
                    });
                });
            </script>


        <?php }?>    

    </body>
</html>

<?= $this->endSection(); ?>