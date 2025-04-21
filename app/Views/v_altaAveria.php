<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alta averia</title>
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
<h1 class="text-center">Añadir nueva avería</h1>
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
                    '0' => 'Seleccione número de serie',
                ];
                foreach ($numsSeries as $num) {
                    $opcionesNumsSeries[$num->num_serie] = $num->num_serie;
                }
                $numSerieSel = $_POST['numSerieSel'] ?? '0';
                echo form_dropdown('numSerieSel', $opcionesNumsSeries, $numSerieSel, [
                                    'class' => 'form-control'
                                ]);    
            ?>
        </div>
        
        <div class="form-group">
            <?php 
                echo form_label('Descripción de la avería', 'descripcion');
                echo form_input(['name' => 'descripcion',
                                'type' => 'text',
                                'class' => 'form-control',
                                'value' => $desc,
                                'placeHolder' => 'Introduce una descripción breve']);
            ?>
        </div>
        
        <div class="form-group">
            <?php 
                echo form_label('Fecha y Hora', 'fecha'); 
                echo form_input(['type' => 'datetime-local',
                                'name' => 'fecha',
                                'value' => $fecha,
                                'class' => 'form-control']); 
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
                                'value' => $cost,
                                'class' => 'form-control']); 
            ?>
        </div>
        <div class="form-group">
            <?php echo form_label('Reparada', 'reparada'); ?>
            <!-- SI -->
            <div class="form-check">
                <?php 
                    echo form_radio([
                    'name' => 'reparadaAveria',
                    'value' => 1,
                    'class' => 'form-check-input',
                    'checked' => ($reparada === '1')
                    ]);
                    echo form_label('Sí', 'reparada_si', ['class' => 'form-check-label']);
                ?>
            </div>
            <!-- NO -->
            <div class="form-check">
                <?php 
                    echo form_radio([
                    'name' => 'reparadaAveria',
                    'value' => 0,
                    'class' => 'form-check-input',
                    'checked' => ($reparada === '0')
                    ]);
                    echo form_label('NO', 'reparada_no', ['class' => 'form-check-label']); 
                ?>
            </div>
        </div>
        
        <div class="text-center">
            <?php 
                echo form_input([
                                'name' => 'GuardarAveria',
                                'type' => 'submit',
                                'value' => 'Guardar',
                                'class' => 'btn btn-primary']); 

            ?>
            <a href="<?= site_url('/admin/averias'); ?>" class="btn btn-secondary">Volver</a>
        </div>
        
        <?php echo form_close(); ?>
    </div>

</body>
</html>