<?= $this->extend("plantillas/layout2zonas"); ?>

<?= $this->section("title") ?>
    Cancelar reserva
<?= $this->endSection(); ?>
    
<?= $this->section("principal"); ?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
    <body>
        <?php if (isset($reservasSeleccionadas)) { ?>
            <h1 style='display:flex; justify-content:center; color:green;'>Operación realizada correctament</h1>
            <div style="display: flex; flex-direction: column; align-items: center; margin-top: 20px;">
                <img src='<?= base_url('/images/cancelacion.jpg')?>' 
                    alt='Imagen cancelación'
                    style='width: 20%; height: 70%; border-radius: 26px; margin:20px; margin-bottom:30px;'>

                <b><i style="margin-top: 10px;">Recibirás un correo de confirmación de su cancelación</i></b>
                <a href="<?= site_url('/misViajes'); ?>" class="btn btn-secondary" 
                    style="width: auto; padding: 10px 20px; margin-top:20px; font-size: 1em; border-radius: 5px; text-decoration: none; text-align: center;">
                    Volver
                </a>
            </div>

        <?php } else { ?>            
            <h1 style='display:flex; justify-content:center;'>Cancelación de <?= count($reservas) == 1 ? "la reserva " : "las reservas " ?> </h1>
            <h4 style='display:flex; justify-content:center; color:blue; padding: 10px;'><?= $ruta['origen']; ?> -> <?= $ruta['destino']; ?></h4>    

            <div style="background-color: #f8f9fa; border: 1px solid #dee2e6; border-radius: 8px; padding: 20px; max-width: 600px; margin: 20px auto; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                <?= form_open(site_url('/cancelReserva'), ['id' => 'formCancel', 'method' => 'post', 'style' => 'display:flex; flex-direction:column; align-items:center;']) ?>

                <p style="font-size: 1.2em; font-weight: bold; margin-bottom: 15px;">Selecciona las reservas que deseas cancelar:</p>
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
                    
                    <button type="submit" class="btn btn-danger" style="width: auto; padding: 10px 20px; margin-left:30px; margin-right:110px; font-size: 1em; border-radius: 5px;">
                        Cancelar Reservas Seleccionadas
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
                            alert('Por favor, selecciona al menos una reserva para cancelar.');
                        }
                    });
                });
            </script>


        <?php }?>
    
    </body>
</html>


<?= $this->endSection(); ?>