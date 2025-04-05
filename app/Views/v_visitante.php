<?= $this->extend("plantillas/layout2zonas"); ?>

<?= $this->section("principal"); ?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Visitante</title>
    </head>

    <body>
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
                            <h1 class="display-4">Bienvenidos</h1>
                            <p class="lead"><b>Hola!</b> Estas en modo visitante 'sin sesión' <br>
                                Puedes consultar los horarios, rutas y tarifas de trenes.
                                </p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </body>

</html>

<?= $this->endSection(); ?>