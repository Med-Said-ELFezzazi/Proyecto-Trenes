<?php if(session()->getFlashdata('error')): ?>
    <div class="alert alert-danger">
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>

<?= $this->section("title") ?>
    Comprar billetes
<?= $this->endSection(); ?>  

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            .info-table {
                width: 705px;
                margin: 0 auto;
                text-align: center;
                background-color: blue;
                color: white;
                font-family: Arial, sans-serif;
                font-size: 0.8rem;
                border-radius: 6px;
            }
            .ser-table {
                width: 705px;
                margin: 0 auto;
                text-align: center;
                border: 3px solid blue;
                /* Quitar el borde de arriba */
                border-top: none;
                /* border de td de latbala */
                border-collapse: collapse;
            }
            .info-table td {
                padding: 5px;
            }
        </style>
    </head>
    <body>
        <div class="container my-5">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header bg-primary text-white text-center">
                            <h3 class="mb-0">Reserva tu Viaje</h3>
                        </div>
                        <div class="card-body">
                            <?= form_open(site_url().'/reserva', ['method' => 'post']); ?>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Fecha de Ida</label>
                                    <?php
                                        $fecha_actual = date('Y-m-d');
                                        $fechaIdaSel = $_POST['fecha_ida'] ?? $fecha_actual;
                                        echo form_input([
                                            'type' => 'date',
                                            'id' => 'fecha_ida',
                                            'name' => 'fecha_ida',
                                            'class' => 'form-control',
                                            'value' => $fechaIdaSel,
                                            'min' => $fecha_actual
                                        ]);
                                    ?>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Fecha de Vuelta</label>
                                    <?php
                                        $fechaVueltaSel = $_POST['fecha_vuelta'] ?? $fecha_actual;
                                        echo form_input([
                                            'type' => 'date',
                                            'id' => 'fecha_vuelta',
                                            'name' => 'fecha_vuelta',
                                            'class' => 'form-control',
                                            'value' => $fechaVueltaSel,
                                            'min' => $fecha_actual
                                        ]);
                                    ?>
                                    <div class="form-check mt-2">
                                        <?php
                                        // checkbox por defecto checkeado
                                            echo form_input([
                                                'type' => 'checkbox',
                                                'name' => 'soloIda',
                                                'id' => 'soloIda',
                                                'class' => 'form-check-input',
                                                'checked' => 'checked'
                                            ]);                                                
                                        ?>
                                        <label class="form-check-label" >Solo ida</label>
                                    </div>
                                </div>
                            </div>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label"> Origen</label>
                                        <?php 
                                            $ciudadesOrg = ['' => '---'] + $ciudadesOrg;
                                            echo form_dropdown(
                                                'origen',
                                                $ciudadesOrg,
                                                $_POST['origen'] ?? null,
                                                [
                                                    'class' => 'form-select',
                                                    'onchange' => 'this.form.submit()'
                                                ]
                                            );                                 
                                        ?>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Destino</label>
                                        <?php                                      
                                        if(isset($origenSeleccionado) && $origenSeleccionado!=null){
                                            echo form_dropdown('destino', $ciudadesDes, $_POST['destino'] ?? null, [
                                                'class' => 'form-select'
                                            ]);  
                                        }else{
                                            echo form_dropdown('destino', ['' => 'Selecciona un origen primero'], '', [
                                                'class' => 'form-select',
                                                'disabled' => 'disabled'
                                            ]); 
                                        } 
                                                                       
                                        ?>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Nº billetes</label>
                                        <?php
                                            echo form_input([
                                                'type' => 'number',
                                                'name' => 'Numbilletes',
                                                'class' => 'form-control',
                                                'min' => '1',
                                                'value' => 1
                                            ]);
                                        ?>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Número de Asiento <i>'Considera de elegir asiento aleatorio 
                                            al comprar más de un billete sino se realiza la compra de solo 1 viaje' (Elegir esta opción costará 5€ por persona)</i></label>
                                        <div class="form-check mt-2">
                                            <?php
                                                echo form_input([
                                                    'type' => 'checkbox',
                                                    'name' => 'asientoAleatorio',
                                                    'id' => 'asientoAleatorio',
                                                    'class' => 'form-check-input',
                                                    'checked' => 'checked'
                                                ]);                                                
                                            ?>
                                            <label class="form-check-label" >Asignar asiento aleatorio</label>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                    echo form_input([
                                        'type' => 'submit',
                                        'name' => 'verServicios',
                                        'value' => 'Ver Servicios',
                                        'class' => 'btn btn-primary',
                                        'formaction' => base_url('reserva/servicios')
                                    ]);
                                ?>
                            <?= form_close(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="<?= base_url('/js/reservas.js'); ?>"></script>
    </body>
</html>