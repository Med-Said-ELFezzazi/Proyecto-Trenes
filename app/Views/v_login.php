<?= $this->extend("plantillas/layout2zonas"); ?>

<?= $this->section("principal"); ?>

    <!DOCTYPE html>
    <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Login y registro</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
            <link rel="stylesheet" href="<?= base_url('css/styleAut.css'); ?>">
        </head>
        <body>
            <!-- Las vistas no tienen acceso directo a session (se hace asi o pasando el dato en el controlador) -->
            <?php if (session()->get('dniCliente')):?>
                <!-- Cambiar el enlace de autenticacion a home -->
                <script>
                    window.location.href = "<?= site_url('/home'); ?>";
                </script>
            <?php else: ?>
                <div class="container auth-container">
                    <!-- Msg error -->
                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= session()->getFlashdata('error') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <!-- Msg confirmación -->
                    <?php if (session()->getFlashdata('confirmacion')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?= session()->getFlashdata('confirmacion') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>


                    <ul class="nav nav-tabs justify-content-center" id="authTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="login-tab" data-bs-toggle="tab" data-bs-target="#login" type="button" role="tab">Login</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="register-tab" data-bs-toggle="tab" data-bs-target="#register" type="button" role="tab">Registrar</button>
                        </li>
                    </ul>

                    <div class="tab-content mt-4" id="authTabsContent">
                        <!-- Login Form -->
                        <div class="tab-pane fade show active" id="login" role="tabpanel" aria-labelledby="login-tab">
                            <?= form_open(site_url('autenticacion'), ['method' => 'post']) ?>
                                <div class="mb-3">
                                    <label for="loginEmail" class="form-label">Correo electrónico</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-envelope form-icon"></i></span>
                                        <?php 
                                            // Repoblar el campo con el valor que se envió
                                            $valor = session()->getFlashdata('email') ?: "";
                                            echo form_input(['type' => 'email',
                                                        'name' => 'loginEmail',
                                                        'id' => 'loginEmail',
                                                        'class' => 'form-control',
                                                        'placeholder' => 'Introduce tu correo',
                                                        'value' => $valor])            
                                        ?>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="loginPassword" class="form-label">Contraseña</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-lock form-icon"></i></span>
                                        <?php 
                                            // Repoblar el campo con el valor que se envió
                                            $valor = session()->getFlashdata('pwd') ?: "";
                                            echo form_input(['type' => 'password',
                                                        'name' => 'loginPassword',
                                                        'id' => 'loginPassword',
                                                        'class' => 'form-control',
                                                        'value' => $valor,
                                                        'maxlength' => 8,
                                                        'placeholder' => 'Introduce tu contraseña'])          
                                        ?>
                                    </div>
                                </div>
                                <div class="d-grid">
                                    <?= form_submit([
                                                'name'  => 'submitLogin',
                                                'value' => 'Login',
                                                'class' => 'btn btn-primary',
                                            ]);?>
                                </div>

                            <?php form_close(); ?>

                            <div class="form-switch mt-3">
                                <span>¿Aún no tienes cuenta? <a href="#" onclick="document.getElementById('register-tab').click();">Registrate aquí</a></span>
                            </div>
                        </div>

                        <!-- Registración Form -->
                        <div class="tab-pane fade" id="register" role="tabpanel" aria-labelledby="register-tab">
                            <?= form_open(site_url('autenticacion')) ?>
                                <!-- DNI -->
                                <div class="mb-3">
                                    <label for="registroDni" class="form-label">DNI</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-id-card form-icon"></i></span>
                                        <?php
                                            // Repoblar el campo con el valor que se envió
                                            $valor = session()->getFlashdata('dni') ?: "";
                                            echo form_input(['type' => 'text',
                                                        'name' => 'registroDni',
                                                        'id' => 'registroDni',
                                                        'class' => 'form-control',
                                                        'value' => $valor,
                                                        'placeholder' => 'Introduce tu DNI']); 
                                        ?>
                                    </div>
                                </div>
                                <!--Nombre-->
                                <div class="mb-3">
                                    <label for="registroNom" class="form-label">Nombre completo</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-user form-icon"></i></span>
                                        <?php
                                            // Repoblar el campo con el valor que se envió
                                            $valor = session()->getFlashdata('nombre') ?: "";
                                            echo form_input(['type' => 'text',
                                                        'name' => 'registroNom',
                                                        'id' => 'registroNom',
                                                        'class' => 'form-control',
                                                        'value' => $valor,
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
                                            // Repoblar el campo con el valor que se envió
                                            $valor = session()->getFlashdata('email') ?: "";
                                            echo form_input(['type' => 'email',
                                            'name' => 'registroEmail',
                                            'id' => 'registroEmail',
                                            'class' => 'form-control',
                                            'value' => $valor,
                                            'placeholder' => 'Introduce tu correo']);
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
                                            $valor = session()->getFlashdata('tele') ?: "";
                                            echo form_input(['type' => 'number',
                                                        'name' => 'registroTele',
                                                        'id' => 'registroTele',
                                                        'class' => 'form-control',
                                                        'value' => $valor,
                                                        'placeholder' => 'Introduce tu Número']); 
                                        ?>
                                    </div>
                                </div>
                                <!-- Password-->
                                <div class="mb-3">
                                    <label for="registroPwd" class="form-label">Contraseña</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-lock form-icon"></i></span>
                                        <?php
                                            // Repoblar el campo con el valor que se envió
                                            $valor = session()->getFlashdata('pwd') ?: "";
                                            echo form_input(['type' => 'password',
                                                        'name' => 'registroPwd',
                                                        'id' => 'registroPwd',
                                                        'class' => 'form-control',
                                                        'value' => $valor,
                                                        'maxlength' => 8,
                                                        'placeholder' => 'Crea una contraseña']); 
                                        ?>
                                    </div>
                                </div>
                                <div class="d-grid">
                                    <?= form_submit([
                                            'name'  => 'submitRegistrar',
                                            'value' => 'Registrate',
                                            'class' => 'btn btn-success',
                                        ]);
                                    ?>
                                </div>

                            <?php form_close(); ?>




                            <div class="form-switch mt-3">
                                <span>¿Ya tienes una cuenta? <a href="" onclick="document.getElementById('login-tab').click();">Login aquí</a></span>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            

            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
            <!-- <script src="<= base_url('js/navLoginReg.js') ?>"></script>   Logica que hace al recargar se queda en el mismo tab 'login/registro' -->
                                                                                <!--Guardando tab activo en localstorage 'NO VA' -->
        </body>
    </html>

<?= $this->endSection(); ?>