<?= $this->extend("plantillas/layout2zonas"); ?>

<?= $this->section("title") ?>
    Viajes reservados
<?= $this->endSection(); ?>

<?= $this->section("principal"); ?>

<?php

    $accessKey = 'Ha_OyXgOfxeMeQIzerP4aDu5s7R7gQ-pAmjdh8Z_rVA';

    /**
     * Obtiene la URL de una imagen relacionada con una ciudad desde la API de Unsplash
     *
     * @param string $ciudad El nombre de la ciudad para buscar imágenes relacionadas.
     * @param string $accessKey La clave de acceso (API key) para autenticar la solicitud a Unsplash.
     * @return string La URL de la imagen encontrada o una imagen por defecto si no se encuentra ninguna.
     */
    function obtenerImagenUnsplash($ciudad, $accessKey) {
        $query = urlencode($ciudad);
        $url = "https://api.unsplash.com/search/photos?query={$query}&per_page=1&client_id={$accessKey}";

        $respuesta = file_get_contents($url);

        if ($respuesta !== false) {
            $datos = json_decode($respuesta, true);
            if (!empty($datos['results'][0]['urls']['regular'])) {
                return $datos['results'][0]['urls']['regular'];
            }
        }

        // Imagen por defecto si no se encuentra nada
        return base_url('images/trenes/sinImg.png');
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
 

    </head>
    <body>
        <?php 
            if (count($datos) == 0) {
                echo "<h1 style='display:flex; justify-content:center;'>Todavía no tienes reservas</h1>";
            } else {            
                $agrupadoPorRuta = [];      // Agrupar todas las reservas por ruta => Ruta->reserva1, reserva2, reserva7

                foreach ($datos as $item) {
                    $reserva = $item['reserva'];
                    $ruta = $item['ruta'];
                    
                    $claveRuta = $ruta->id_ruta;

                    if (!isset($agrupadoPorRuta[$claveRuta])) {
                        $agrupadoPorRuta[$claveRuta] = [
                            'ruta' => $ruta,
                            'reservas' => []
                        ];
                    }
                    $agrupadoPorRuta[$claveRuta]['reservas'][] = $reserva;
                }


                $tituloProxMostrado = false;
                $tituloAnteMostrado = false;

                foreach ($agrupadoPorRuta as $grupo) {
                    $ruta = $grupo['ruta'];
                    $reservas = $grupo['reservas'];

                    $imagenCiudadDes = obtenerImagenUnsplash($ruta->destino, $accessKey);

                    $esProxima = ($ruta->fecha > date('Y-m-d')) || ($ruta->fecha == date('Y-m-d') && $ruta->hora_salida > date('H:i:s'));

                    if ($esProxima) {
                        if (!$tituloProxMostrado) {
                            echo "<h1 style='display:flex; justify-content:center;'>Aquí están tus próximas reservas</h1>";
                            $tituloProxMostrado = true;
                        }
                    } else {
                        if (!$tituloAnteMostrado) {
                            echo "<h1 style='display:flex; justify-content:center;'>Sus reservas anteriores</h1>";
                            $tituloAnteMostrado = true;
                        }
                    }

                // Mostrar la tarjeta para esta ruta
                ?>
                <div class="card mb-3" style="border: 1px solid #ddd; border-radius: 8px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                    <div class="card-body d-flex align-items-center justify-content-between" style="background-color: <?= $esProxima ? 'rgb(173, 216, 230)' : 'rgb(241, 154, 154)' ?>;">
                        <img src="<?= $imagenCiudadDes ?>" 
                            alt="Imagen de <?= $ruta->destino ?>" 
                            style="width: 35%; height: 120px; border-radius: 6px; object-fit: cover;" 
                            loading="lazy">

                        <div class="text-center mx-3">
                            <h4 class="card-title mb-2"><?= $ruta->origen ?> a <?= $ruta->destino ?></h4>
                            <p class="card-text mb-0">
                                📅 <?= date('d F', strtotime($ruta->fecha)) ?>
                                <span style="margin: 0 10px; color: #ccc;">|</span>
                                <?php
                                    $idsReservas = array_map(fn($r) => $r->id_ticket, $reservas);
                                    echo count($idsReservas) == 1 ? "<strong>Reserva: </strong>" : "<strong>Reservas: </strong>";
                                    echo implode(', ', $idsReservas);
                                ?>
                            </p>

                            <div class="collapse" id="detalle<?= $ruta->id_ruta ?>">
                                <?php
                                    $asientos = array_map(fn($r) => $r->num_asiento, $reservas);
                                    echo count($idsReservas) == 1 ? "<strong>Asiento: </strong>" : "<strong>Asientos: </strong>";
                                    echo implode(', ', $asientos);
                                ?>
                                <br>
                                <strong>Hora de salida: </strong><?= date('H:i', strtotime($ruta->hora_salida)) ?>
                                <strong>Hora de llegada: </strong><?= date('H:i', strtotime($ruta->hora_llegada)) ?>
                                <br>
                                <strong>Precio: </strong><?= $ruta->tarifa ?>€
                            </div>
                            <br>
                            <?php if ($esProxima): ?>

                                <form action="<?= site_url('/cancelReserva') ?>" method="post" style="display: inline;">
                                    <!-- <input type="hidden" name="ids" value="<= implode(',', $idsReservas) ?>"> -->
                                    <input type="hidden" name="reservasObjs" value='<?= json_encode($reservas) ?>'>
                                    <input type="hidden" name="rutaObjs" value='<?= json_encode($ruta) ?>'>

                                    <button type="submit" class="btn btn-danger" style="width: auto;">
                                        <i class="fas fa-ban"></i>
                                        Cancelar <?php echo count($idsReservas) == 1 ? "reserva" : "reservas" ?> 
                                    </button>
                                </form>

                            <?php endif; ?>
                        </div>
                        <button class="btn btn-primary float-end" data-bs-toggle="collapse" data-bs-target="#detalle<?= $ruta->id_ruta ?>">
                                Ver detalles <i class="fas fa-chevron-down"></i>
                        </button> 


                        <script>
                            // Alternar sobre el texto del boton
                            document.addEventListener('DOMContentLoaded', function () {
                                // Obtiene el elemento de colapso y el boton de alternar
                                const collapseEl = document.getElementById("detalle<?= $ruta->id_ruta ?>");
                                const toggleBtn = document.querySelector('[data-bs-target="#detalle<?= $ruta->id_ruta ?>"]');

                                // Evento cuando el colapso se muestra
                                collapseEl.addEventListener('shown.bs.collapse', function () {
                                    // Cambia el texto del boton para indicar que los detalles estan visibles
                                    toggleBtn.innerHTML = 'Ocultar detalles <i class="fas fa-chevron-up"></i>';
                                });

                                // Evento cuando el colapso se oculta
                                collapseEl.addEventListener('hidden.bs.collapse', function () {
                                    toggleBtn.innerHTML = 'Ver detalles <i class="fas fa-chevron-down"></i>';
                                });
                            });
                        </script>

                    </div>
                </div>
                <?php } }?>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    </body>
</html>
<?= $this->endSection(); ?>