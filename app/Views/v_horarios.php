<div class="row">
    <div class="col-md-3" style="background-color:rgb(13, 151, 244);">
        <div class="boxHorariosHome MT20">
            <div class="contCampos">
                <?php
                    echo form_open(current_url(), ['method' => 'post']);
                ?>

                <!-- Fecha -->
                <p>
                    <h5 style="color: white;">Fecha</h5>
                    <?php
                        $fecha_actual = date('Y-m-d');
                        $fecha_max = date('Y-m-d', strtotime($fecha_actual . ' + 1 month'));
                        $fechaSeleccionada = $_POST['fecha'] ?? $fecha_actual; 
                        echo form_input([
                            'type' => 'date',
                            'name' => 'fecha',
                            'id' => 'fecha',
                            'value' => $fechaSeleccionada,
                            'min' => $fecha_actual,
                            'max' => $fecha_max,
                            'class' => 'form-control'
                        ]);
                    ?>
                </p>
                
                <!-- Origin -->
                <h5 style="color: white;">Origen</h5>
                <div class="custom-select">
                    <?php   
                        $origenOptions = [
                            '0' => 'Seleccione origen',
                        ];
                        foreach ($ciudadesOrg as $ciudad) {
                            $origenOptions[$ciudad->origen] = $ciudad->origen;
                        }
                        $origenSel = $_POST['origenSel'] ?? '0';
                        echo form_dropdown('origenSel', $origenOptions, $origenSel, [
                            'id' => 'origenSel',
                            'class' => 'select',
                        ]);
                    ?> 
                </div>
                <br><br>

                <!-- Destino -->
                <h5 style="color: white;">Destino</h5>
                <div class="custom-select">
                    <?php
                    $destinoOptions = [
                        '0' => 'Seleccione destino'
                    ];
                    foreach ($ciudadesDes as $ciudad) {
                        $destinoOptions[$ciudad->destino] = $ciudad->destino;
                    }
                    $destinoSel = $_POST['destinoSel'] ?? '0';
                    echo form_dropdown('destinoSel', $destinoOptions, $destinoSel, [
                        'id' => 'destinoSel',
                        'class' => 'select',
                    ]);
                    ?>
                </div>
                <br><br>

                <?php
                    // Botón enviar
                    echo form_submit('consultar', 'Consultar horarios', ['class' => 'btn btn-primary']);
                    echo form_close();
                ?>
            </div>
            <!-- <div class="clearfix"></div> -->
        </div>
    </div>

    <!-- Al click submit -->
    <div class="col-md-9">
        <?php
            $msgError = '';
            if (!empty($_POST['consultar'])) {
                $fechaSeleccionada = $_POST['fecha'] ?? date('Y-m-d');
                $ciudadOrgSel = $_POST['origenSel'] ?? '';
                $ciudadDesSel = $_POST['destinoSel'] ?? '';

                // Validar selección de origen y destino
                if ($ciudadOrgSel == '0' || $ciudadOrgSel == '') {
                    $msgError .= "Seleccione ciudad de origen!<br>";
                }
                if ($ciudadDesSel == '0' || $ciudadDesSel == '') {
                    $msgError .= "Seleccione ciudad de destino!<br>";
                }

                // Mostrar resultados
                if (empty($msgError)) {
                    $origenNombre = isset($origenOptions[$ciudadOrgSel]) ? strtoupper($origenOptions[$ciudadOrgSel]) : '';
                    $destinoNombre = isset($destinoOptions[$ciudadDesSel]) ? strtoupper($destinoOptions[$ciudadDesSel]) : '';
                    
                    echo '<h2 class="titSeccion">Resultados</h2>';
                    echo '<div>';
                        if (count($datosRutas) == 0) {
                            echo '<strong><span style="visibility: hidden;">Este texto es invisible pero ocupa espacio Lorem Ipsum is simply dummy text of the printing and typesetting industry.</span></strong>';
                            echo '<div class="alert alert-warning">No hay rutas disponibles con los datos seleccionados!</div>';

                        } else {
                            $fechaFormateada = date('d', strtotime($fechaSeleccionada))."/".ucfirst(mb_strtolower(date('M', strtotime($fechaSeleccionada))))."/".date('Y', strtotime($fechaSeleccionada));                    
                            echo '<strong>Resultados encontrados en la fecha: '.$fechaFormateada.'  '.$origenNombre.' - '.$destinoNombre.'<span style="visibility: hidden;">Este texto es invisible pero ocupa espacio Lorem Ipsum is simply dummy text of the printing and typesetting industry.</span></strong>';
                            echo '<table class="table table-striped table-bordered">';
                                echo '<thead>';
                                    echo '<tr>';
                                        echo '<th>Origen</th>';
                                        echo '<th>Salida</th>';
                                        echo '<th>Destino</th>';
                                        echo '<th>Llegada</th>';
                                    echo '</tr>';
                                echo '</thead>';
                                foreach($datosRutas as $ruta) {
                                    echo '<tr>';
                                        echo '<td>'.$ruta->origen.'</td>';
                                        echo '<td>'.date('H:i', strtotime($ruta->hora_salida)).'</td>';
                                        echo '<td>'.$ruta->destino.'</td>';
                                        echo '<td>'.date('H:i', strtotime($ruta->hora_llegada)).'</td>';
                                    echo '</tr>';
                                }                        
                            echo '</table>';
                        }
                    echo '</div>';                    
                } else {
                    // Mostrar msg de error
                    echo '<span style="visibility: hidden;">Este texto es invisible pero ocupa espacio Lorem Ipsum is simply dummy text of the printing and typesetting.</span>';
                    echo '<div class="mt-3 alert alert-danger">';
                        echo '<strong>ERROR:</strong><br>'.$msgError;
                    echo '</div>';
                }
            } else {
                echo '<h2 class="titSeccion">Consulta de horarios</h2>';
                echo '<div>';
                    echo '<p>Para realizar una consulta sobre una línea seleccione, en primer lugar, la fecha en la que realizará el recorrido. Pulsando en el icono cuadrado bajo la palabra "fecha", aparecerán en pantalla los calendarios correspondientes al mes en curso y al siguiente. Seleccione la fecha que desee simplemente pulsando sobre ella.</p>';
                    echo '<p class="MT20">A continuación, seleccione la localidad de origen en el menú desplegable y, para finalizar, seleccione, del mismo modo, la localidad de destino.</p>';
                echo '</div>';
            }
        ?>

    </div>

</div>