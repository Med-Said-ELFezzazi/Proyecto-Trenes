<div>
    <table class="info-table">
        <tr >
            <td>Fecha:</td>
            <td><b><i><?= $_POST['fecha_ida']; ?></i></b></td>
            <td>Salida:</td>
            <td><b><i><?= $_POST['ciudad_origen']; ?></i></b></td>
            <td>Llegada:</td>
            <td><b><i><?= $_POST['ciudad_destino']; ?></i></b></td>
        </tr>
        <tr>
        <td colspan="6"><b>SERVICIOS POSIBLES PARA LA IDA</b></td>
    </table>
    <!-- Tabla de servicios -->
    <table class="ser-table">
        <thead>
            <tr>
                <th>Hora salida</th>
                <th>hora llegada</th>
                <th>Precio</th>
                <th>Plazas libres</th>
            </tr>
        </thead>
        <tbody>
            <?php
                echo form_open(site_url().'/reserva/servicios/compra', ['method' => 'post']);
                $esPrimero = true;  // Para poner el primer radio selected
                foreach ($servicios as $servicio) {
                    echo "<tr>";
                        echo "<td>";
                            echo '<input type="radio" name="servicioSel[]" value="' . $servicio['id_ruta'] . '"';
                            if ($esPrimero) {
                                echo ' checked';
                                $esPrimero = false;
                            }
                            if (!$servicio['hayPlazas']) {
                                echo ' disabled';
                            }
                            echo '>';
                            $esPrimero = false;
                            echo date('H:i', strtotime($servicio['hora_salida']));
                        echo "</td>";
                        echo "<td>" . date('H:i', strtotime($servicio['hora_llegada'])) . "</td>";
                        echo "<td>" . $servicio['precio'] . "€</td>";
                        if (!$servicio['hayPlazas']) { // Si no hay plazas
                            echo "<td>No disponible</td>";
                        } else {
                            echo "<td>" . $servicio['plazas_libres'] . "</td>";

                        }
                    echo "</tr>";
                }
            ?>
        </tbody>
    </table>
    
    <?php
        echo '<div class="text-center mt-3">';
            echo form_input([
                'type' => 'submit',
                'name' => 'comprar',
                'value' => 'Comprar billete',
                'class' => 'btn btn-success'
            ]); 
        echo '</div>';
        echo form_close(); 
    ?>
</div>