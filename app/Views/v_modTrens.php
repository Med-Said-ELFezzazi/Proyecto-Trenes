
<?= $this->section("title") ?>
    Modificar datos del tren
<?= $this->endSection(); ?>

 <h1 class="text-center">Modificar datos Tren</h1>

     <!-- Msj erro/confirmación -->
    <?php
       if (isset($msgInfoModTren)) {
          echo '<div class="alert alert-success text-center" role="alert">';
             echo $msgInfoModTren;
             echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
                echo '<span aria-hidden="true">&times;</span>';
             echo '</button>';
          echo '</div>';

       }
       if (isset($msgErrModTren)) {
          echo '<div class="alert alert-danger text-center" role="alert">';
             echo $msgErrModTren;
             echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
                echo '<span aria-hidden="true">&times;</span>';
             echo '</button>';
          echo '</div>';
       }

       // Repoblación de campos en caso de error
       $capacidad = $_POST['capacidad'] ?? $trenMod->capacidad;
       $modelo = $_POST['modelo'] ?? $trenMod->modelo;
       $vagones = $_POST['vagones'] ?? $trenMod->vagones;
    ?>

    <?= form_open(current_url(), ['method' => 'post', 'enctype' => 'multipart/form-data']); ?>

    <div class="card mb-3" style="border-width: 2px;">
        <div class="row no-gutters">
            <div class="col-md-4">
                <?php if($trenMod->imagen == 'sinImg.png'): ?>
                    <div class="text-center">
                        <b>Tren sin imagen</b>
                    </div>
                <?php endif; ?>
                <img src="<?= base_url('/images/trenes/' . $trenMod->imagen); ?>" class="card-img" alt="Imagen del tren">
            </div>
            <div class="col-md-8">
                <div class="card-body">
                    <div class="form-group">
                        <?php
                            echo form_label('Subir nueva imagen', 'imagen', ['class' => 'form-label']);
                            echo form_upload([
                                'name' => 'imagen',
                                'class' => 'form-control',
                                'accept' => '.jpg,.jpeg,.png,.gif'
                            ]);
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div>
        <div class="form-group">
            <?php
                echo form_label('Número de serie', 'num_serie', ['class' => 'form-label']);
                echo form_input(['name' => 'num_serie',
                'type' => 'text',
                'value' => $trenMod->num_serie,
                'class' => 'form-control',
                'disabled' => 'disabled'
                ]);
            ?>
        </div>

        <div class="form-group">
            <?php 
                echo form_label('Modelo', 'modelo'); 
                echo form_input(['type' => 'text',
                                'name' => 'modelo',
                                'value' => $modelo,
                                'class' => 'form-control']); 
            ?>
        </div>

        <div class="form-group">
            <?php 
                echo form_label('vagones', 'vagones'); 
                echo form_input(['type' => 'text',
                                'name' => 'vagones',
                                'value' => $vagones,
                                'class' => 'form-control']); 
            ?>
        </div>

        <div class="form-group">
            <?php 
                echo form_label('Capacidad', 'capacidad'); 
                echo form_input(['type' => 'number',
                                'name' => 'capacidad',
                                'value' => $capacidad,
                                'min' => 5,
                                'class' => 'form-control']); 
            ?>
        </div>
                
        <div class="text-center">
            <?php 
                echo form_input(['name' => 'actualizarTren',
                                'type' => 'submit',
                                'value' => 'Actualizar Tren',
                                'class' => 'btn btn-primary']); 
            ?>
            <a href="<?= site_url('/admin/trenes'); ?>" class="btn btn-secondary">Volver</a>
        </div>
    </div>
    <?= form_close(); ?>

        
 </body>
 </html>