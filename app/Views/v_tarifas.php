<h1>Tarifas</h1>

<div class="container">
        <?php
            $arrCiudadesExistentes = [];
            foreach ($datosTarifas as $index => $viaje) {
                if (!in_array($viaje->ciudad_origin, $arrCiudadesExistentes)) {
                    // Cerrar la tabla anterior si existe
                    if (!empty($arrCiudadesExistentes)) {
                        echo "</tbody>";
                        echo "</table>";
                    }
                    // Añadir la ciudad al array
                    array_push($arrCiudadesExistentes, $viaje->ciudad_origin);
                    // Poner el titulo
                    echo "<h3>Salidas de " . $viaje->ciudad_origin . "</h3>";
                    echo '<table class="table">';
                        echo '<thead>';
                            echo '<tr>';
                                echo '<th class="headerTH">Recorrido</th>';
                                echo '<th class="headerTH">Tarifa</th>';
                                echo '<th class="headerTH">Reservar</th>';
                            echo '</tr>';
                        echo "</thead>";
                    echo '<tbody>';
                }

                // Poner datos tarifas
                echo "<tr>";
                    echo "<td class='casillas'>";
                        echo $viaje->ciudad_origin . " → " . $viaje->ciudad_destino;
                    echo "</td>";
        
                    echo "<td class='casillas'>";
                        echo $viaje->tarifa . " €";
                    echo "</td>";
        
                    echo "<td class='casillas'>";
                    $url = '';
                    if (!session()->get('dniCliente')) {
                        // Submit que lleva a la autenticación 
                        $url .= '/autenticacion';
                    } else {
                        // Submit que lleva para reservar
                        $url .= '/reserva';
                    }
                        echo form_open($url, ['method' => 'post']);                            
                        echo form_input([
                                'type' => 'submit',
                                'class' => 'btn btn-primary',
                                'value' => 'Reservar',
                                'style' => 'background-color: #4CAF50; border: none;'
                            ]);
                        echo form_close();
                    echo "</td>";
                echo "</tr>";
                
                // Cerrar la tabla si es la última iteración
                if ($index == count($datosTarifas) - 1) {
                    echo "</tbody>";
                    echo "</table>";
                }
            }
        ?>
</div>