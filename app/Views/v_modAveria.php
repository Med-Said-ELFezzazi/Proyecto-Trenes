 <!DOCTYPE html>
 <html lang="en">
 <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modaveria</title>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkbox = document.querySelector('input[name="fechayhora_hoy"]');
            const fecha = document.querySelector('input[name="fecha"]');
            // Comprobar si el checkbox esta checkeado
            if (checkbox.checked) {
                // Deshabilitar
                fecha.disabled = true;
            }
            // Añadir evento para cambiar el estado del campo fecha
            checkbox.addEventListener('change', function() {
                if (checkbox.checked) {
                    fecha.disabled = true;
                } else {
                    fecha.disabled = false;
                }
            });
        });
    </script>
 </head>
 <body>
 <h1 class="text-center">Modificar datos averia</h1>

     <!-- Msj erro/confirmación -->
     <?php
        if (isset($msgInfoAveria)) {
            echo '<div class="alert alert-success text-center" role="alert">';
                echo $msgInfoAveria;
                echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
                    echo '<span aria-hidden="true">&times;</span>';
                echo '</button>';
            echo '</div>';

        }
        if (isset($msgErrorAveria)) {
            echo '<div class="alert alert-danger text-center" role="alert">';
                echo $msgErrorAveria;
                echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
                    echo '<span aria-hidden="true">&times;</span>';
                echo '</button>';
            echo '</div>';
            $numSerieSel = '0';
            $desc = '';
            $fecha = '';
            $cost = '';
            $reparada = null;
        }

        // Repoblación de campos en caso de error en caso inserción existosa limpiar campos
        if (isset($msgErrorAveria)) {
            $numSerieSel = $_POST['numSerieSel'] ?? '0';
            $desc = $_POST['descripcion'] ?? '';
            $fecha = $_POST['fecha'] ?? '';
            $cost = $_POST['costeAveria'] ?? '';
            $reparada = $_POST['reparadaAveria'] ?? null;
        } else {
            $numSerieSel = '0';
            $desc = '';
            $fecha = '';
            $cost = '';
            $reparada = null;
        }
     ?>

    <div>
        <?= form_open(current_url(), ['method' => 'post']); ?>
        <div class="form-group">
            <?php
                echo form_label('Número de serie', 'numSerie', ['class' => 'form-label']); 
                $opcionesNumsSeries = [
                    '0' => 'Seleccione número serie'
                ];
                foreach ($numsSeries as $num) {
                    $opcionesNumsSeries[$num->num_serie] = $num->num_serie;
                }
                echo form_dropdown('numSerieSel', $opcionesNumsSeries, $averia->num_serie, [
                    'class' => 'form-control'
                ]);
            ?>
        </div>

        <div class="form-group">
            <?php
                echo form_label('Descripción', 'descripcion', ['class' => 'form-label']); 
                echo form_input(['name' => 'descripcion',
                                'type' => 'text',
                                'value' => $averia->descripcion,
                                'class' => 'form-control',
                                ]);
            ?>
        </div>

        <div class="form-group">
            <?php
                echo form_label('Fecha', 'fecha', ['class' => 'form-label']); 
                echo form_input(['name' => 'fecha',
                                'type' => 'datetime-local',
                                'value' => $averia->fecha,
                                'class' => 'form-control',
                                ]);
            ?>
        </div>
        <div class="form-group form-check">
            <?php 
                echo form_input(['name' => 'fechayhora_hoy',
                                'type' => 'checkbox',
                                'class' => 'form-check-input']);
                echo form_label('Fecha y hora actual', 'fechaHoraActual',
                                ['class' => 'form-check-label']); 
            ?>
        </div>

        <div class="form-group">
            <?php 
                echo form_label('Coste', 'coste');
                echo form_input(['type' => 'number',
                                'name' => 'costeAveria',
                                'value' => $averia->coste,
                                'class' => 'form-control']); 
            ?>
        </div>

        <div class="form-group">
            <?php 
                echo form_label('Estado', 'reparada', ['class' => 'form-label']);
                echo form_dropdown('reparada', [0 => 'No', 1 => 'Sí'], $averia->reparada, [
                    'class' => 'form-control'
                ]);
            ?>
        </div>
                
        <div class="text-center">
            <?php 
                echo form_input(['name' => 'actualizarAveria',
                                'type' => 'submit',
                                'value' => 'Actualizar avería',
                                'class' => 'btn btn-primary']); 
            ?>
            <a href="<?= site_url('/admin/averias'); ?>" class="btn btn-secondary">Volver</a>
        </div>
    </div>
    <?= form_close(); ?>

        
 </body>
 </html>