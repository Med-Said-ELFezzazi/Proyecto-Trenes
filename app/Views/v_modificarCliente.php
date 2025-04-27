<?= $this->extend("plantillas/layout2zonas"); ?>


<?= $this->section("title") ?>
    Modificar datos personales
<?= $this->endSection(); ?>

<?= $this->section("principal"); ?>

            <!-- Mostrar msg info -->                
            <?php if ($msg != ""): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?= $msg; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            <!-- Mostrar msg error -->                
            <?php if ($msgErr != ""): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= $msgErr; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>


            <div class="container auth-container">
                <h1 style="display:flex; justify-content:center;">Modificar tus datos</h1>

                <!-- <div class="tab-pane fade" id="register" role="tabpanel" aria-labelledby="register-tab"> -->
                <div id="register" role="tabpanel" aria-labelledby="register-tab">
                    <?= form_open(site_url('/modificarCliente')) ?>   <!--Se queda en la misma ruta -->
                    <!--Nombre-->
                    <div class="mb-3">
                        <label for="registroNom" class="form-label">Nombre completo</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user form-icon"></i></span>
                            <?php
                                // Repoblar el campo con el valor con datos del obj que se envió
                                echo form_input(['type' => 'text',
                                            'name' => 'modNom',
                                            'id' => 'registroNom',
                                            'class' => 'form-control',
                                            'value' => $clienteObj->nombre,
                                            'placeholder' => 'Introduce tu nombre']);         
                            ?>
                        </div>
                    </div>
                    <!--Email -->
                    <div class="mb-3">
                        <label for="registroEmail" class="form-label">Correo electrónico</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-envelope form-icon"></i></span>
                            <?php
                                echo form_input(['type' => 'email',
                                'name' => 'modEmail',
                                'id' => 'registroEmail',
                                'class' => 'form-control',
                                'value' => $clienteObj->email,
                                'placeholder' => 'Introduce tu correo',
                                'required' => true]);
                            ?>
                        </div>
                    </div>
                    <!-- telefono-->
                    <div class="mb-3">
                        <label for="registroTele" class="form-label">Número de telefono</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-phone form-icon"></i></span>
                            <?php
                                // Repoblar el campo con el valor que se envió
                                echo form_input(['type' => 'number',
                                            'name' => 'modTele',
                                            'id' => 'registroTele',
                                            'class' => 'form-control',
                                            'value' => $clienteObj->telefono,
                                            'placeholder' => 'Introduce tu Número']); 
                            ?>
                        </div>
                    </div>
                    <!-- Password-->
                    <div class="mb-3">
                        <label for="registroPwd" class="form-label">Cambiar contraseña</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock form-icon"></i></span>
                            <?php
                                echo form_input(['type' => 'password',
                                            'name' => 'modPwd',
                                            'id' => 'registroPwd',
                                            'class' => 'form-control',
                                            'maxlength' => 8,
                                            'placeholder' => 'Nueva contraseña']); 
                            ?>
                        </div>
                    </div>
                    <div class="d-grid">
                        <?= form_submit([
                                'name'  => 'submitModificar',
                                'value' => 'Modificar datos',
                                'class' => 'btn btn-success',
                            ]);
                        ?>
                    </div>

                    <?php form_close(); ?>
                </div>
            </div>
        </body>
    </html>

<?= $this->endSection(); ?>