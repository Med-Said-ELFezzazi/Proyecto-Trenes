<div class="d-flex justify-content-between">
    <div class="p-3 mr-5" style="background-color:rgb(13, 151, 244);">
        <div class="boxHorariosHome MT20">
            <div class="contCampos" style="padding: 5px;">
                <h2 style="color: white;" class="text-center">Filtro</h2>
                <?= form_open(current_url(), ['method' => 'post']) ?>

                <!-- Fecha -->
                <p>
                    <?= form_label('Fecha:', '', ['style' => 'color:white; font-size: 18px;']) ?>
                    <?= form_input([
                        'type' => 'date',
                        'name' => 'fecha',
                        'id' => 'fecha',
                        'value' => $fechaSeleccionada,
                        'min' => date('Y-m-d'),
                        'max' => date('Y-m-d', strtotime('+1 month')),
                        'class' => 'form-control'
                    ]) ?>
                </p>
                
                <!-- Origen -->
                <p>
                    <?= form_label('Origen:', '', ['style' => 'color:white; font-size: 18px;']) ?>
                    <?= form_dropdown('origenSel', 
                        ['0' => 'Seleccione origen'] + array_column($ciudadesOrg, 'origen', 'origen'),
                        $ciudadOrgSel,
                        ['id' => 'origenSel', 'class' => 'form-control', 'onchange' => 'this.form.submit()']
                    ) ?>
                </p>

                <!-- Destino -->
                <p>
                    <?= form_label('Destino:', '', ['style' => 'color:white; font-size: 18px;']) ?>
                    <?= form_dropdown('destinoSel',
                        ['0' => 'Seleccione destino'] + array_column($destinosPorOrigen, 'destino', 'destino'),
                        $ciudadDesSel,
                        ['id' => 'destinoSel', 'class' => 'form-control']
                    ) ?>
                </p>
                <br>

                <!-- Botones -->
                <div class="d-flex justify-content-between">
                    <?= form_submit('consultar', 'Consultar horarios', ['class' => 'btn btn-light mr-3']) ?>
                    <?= form_submit('limpiar', 'Limpiar', ['class' => 'btn btn-secondary']) ?>
                </div>

                <?= form_close() ?>
            </div>
        </div>
    </div>

    <!-- Resultados -->
    <div>
        <?php if (!empty($msgError)): ?>
            <div class="mt-3 alert alert-danger">
                <strong>ERROR:</strong><br><?= $msgError; ?>
            </div>
        <?php elseif (!empty($datosRutas)): ?>
            <h2 class="titSeccion">Resultados</h2>
            <?php if ($vieneDelFiltro): ?>
                <strong>Resultados encontrados para <?= date('d/m/Y', strtotime($fechaSeleccionada)) ?>:</strong>
            <?php endif; ?>            
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Origen</th>
                        <th>Fecha</th>
                        <th>Salida</th>
                        <th>Destino</th>
                        <th>Llegada</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($datosRutas as $ruta): ?>
                        <tr>
                            <td><?= $ruta->origen ?></td>
                            <td><?= date('d/m/Y', strtotime($ruta->fecha)) ?></td>
                            <td><?= date('H:i', strtotime($ruta->hora_salida)) ?></td>
                            <td><?= $ruta->destino ?></td>
                            <td><?= date('H:i', strtotime($ruta->hora_llegada)) ?></td>
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
