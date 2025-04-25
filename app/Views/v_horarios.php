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
                        echo form_input([
                            'type' => 'date',
                            'name' => 'fecha',
                            'id' => 'fecha',
                            'value' => $fechaSeleccionada,
                            'min' => date('Y-m-d'),
                            'max' => date('Y-m-d', strtotime('+1 month')),
                            'class' => 'form-control'
                        ]);
                    ?>
                </p>
                
                <!-- Origen -->
                <h5 style="color: white;">Origen</h5>
                <div class="custom-select">
                    <?php   
                        echo form_dropdown('origenSel',
                            ['0' => 'Seleccione origen'] + array_column($ciudadesOrg, 'origen', 'origen'),
                            $ciudadOrgSel,
                            ['id' => 'origenSel', 'class' => 'select', 'onchange' => 'this.form.submit()']
                        );
                    ?> 
                </div>
                <br><br>

                <!-- Destino -->
                <h5 style="color: white;">Destino</h5>
                <div class="custom-select">
                    <?php
                        echo form_dropdown('destinoSel', 
                            ['0' => 'Seleccione destino'] + array_column($destinosPorOrigen, 'destino', 'destino'), 
                            $ciudadDesSel, 
                            ['id' => 'destinoSel', 'class' => 'select']
                        );
                    ?>
                </div>
                <br><br>

                <?php
                    // Botón enviar
                    echo form_submit('consultar', 'Consultar horarios', ['class' => 'btn btn-primary']);
                    echo form_close();
                ?>
            </div>
        </div>
    </div>

    <!-- Resultados -->
    <div class="col-md-9">
        <?php if (!empty($msgError)): ?>
            <div class="mt-3 alert alert-danger">
                <strong>ERROR:</strong><br><?= $msgError; ?>
            </div>
        <?php elseif ($datosRutas): ?>
            <h2 class="titSeccion">Resultados</h2>
            <strong>Resultados encontrados para <?= date('d/m/Y', strtotime($fechaSeleccionada)) ?>:</strong>
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Origen</th>
                        <th>Salida</th>
                        <th>Destino</th>
                        <th>Llegada</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($datosRutas as $ruta): ?>
                        <tr>
                            <td><?= $ruta->origen; ?></td>
                            <td><?= date('H:i', strtotime($ruta->hora_salida)); ?></td>
                            <td><?= $ruta->destino; ?></td>
                            <td><?= date('H:i', strtotime($ruta->hora_llegada)); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <h2 class="titSeccion">Consulta de horarios</h2>
            <p>No existen viajes con esos datos...</p>
        <?php endif; ?>
    </div>
</div>