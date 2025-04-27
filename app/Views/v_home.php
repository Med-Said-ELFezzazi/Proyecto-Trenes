<?= $this->extend("plantillas/layout2zonas"); ?>

<?= $this->section("principal"); ?>

    <?php 
        // Mapeo de condiciones a vistas
        $vistas = [
            'v_reserva' => isset($ciudadesOrg) && isset($ciudadesDes) || isset($servicios) || isset($msgError),
            'v_compra' => isset($compraOk) && isset($emailOk),
            'v_trenes' => isset($datosTrenes),
            'v_modTrens' => isset($trenMod),
            'v_averias' => isset($datosAverias) || isset($datosFiltrados),
            'v_modAveria' => isset($averia),
            'v_altaAveria' => isset($numsSeries),
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

<?= $this->endSection(); ?>