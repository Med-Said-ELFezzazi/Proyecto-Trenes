<?= $this->extend("plantillas/layout2zonas"); ?>

<?php 
    // Asignamos el título dependiendo del contenido
    if (isset($ciudadesOrg)) {
        $pageTitle = "Horarios";
    } elseif (isset($datosTarifas)) {
        $pageTitle = "Tarifas";
    } else {
        $pageTitle = "Visitante";
    }
?>

<?= $this->section("title") ?>
    <?= $pageTitle ?>
<?= $this->endSection(); ?>  

<?= $this->section("principal"); ?>

    <div class="row">
        <!-- Lineas y horiarios -->
        <?php if (isset($ciudadesOrg)): ?>
            <?php echo view('v_horarios'); ?>
        <!-- Tarifas -->
        <?php elseif (isset($datosTarifas)): ?>
            <?php echo view('v_tarifas'); ?>
        <!-- Instrucciones/Bienvenida -->
        <?php else: ?>
            <div class="container mt-5">
                <div class="row">
                    <div class="col-md-8 offset-md-2 text-center">
                        <h1 class="display-4">Bienvenido</h1>
                        <p class="lead"><b>Hola!</b> Estas en modo visitante 'sin sesión' <br>
                            Puedes consultar los horarios, rutas y tarifas de trenes.
                            </p>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

<?= $this->endSection(); ?>