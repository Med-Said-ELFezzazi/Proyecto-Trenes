<?= $this->extend("plantillas/layout2zonas"); ?>

<?= $this->section("principal"); ?>

    <!DOCTYPE html>
    <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Home</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

        </head>
        <body>
            <?php 
                // Mapeo de condiciones a vistas
                $vistas = [
                    'v_reserva' => isset($ciudadesOrg) || isset($ciudadesDes) || isset($servicios) || isset($error) || isset($origenSeleccionado),
                    'v_compra' => isset($compraOk) && isset($emailOk),
                    'v_trenes' => isset($datosTrenes),
                    'v_modTrens' => isset($trenMod),
                    'v_averias' => isset($datosAverias) || isset($datosFiltrados),
                    'v_modAveria' => isset($averia),
                    'v_altaAveria' => isset($matriculas),
                    'v_rutas' => isset($datosRutas) || isset($datosFiltradosRutas),
                    'v_modRuta' => isset($rutaAmodificar),
                    'v_altaRuta' => isset($matriculasPaRutas),
                    'vistaTemp' => isset($tsting),
                    'v_opinion' => isset($datosOpinion),
                    'v_bienvenida' => true          // Vista por defecto 'dentro la vist v_bienvenida va la ligica de admin y cliente'
                ];

                // Buscando la primera vista que sea true
                foreach ($vistas as $view => $condition) {
                    if ($condition) {
                        echo view($view);
                        break;
                    }
                }
            ?>

            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>                                                                                <!--Guardando tab activo en localstorage 'NO VA' -->
        </body>
    </html>

<?= $this->endSection(); ?>