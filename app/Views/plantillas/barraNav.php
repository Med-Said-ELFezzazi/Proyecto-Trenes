<div id="content" class="p-4 p-md-5">

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">

            <button type="button" id="sidebarCollapse" class="btn btn-primary">
            <i class="fa fa-bars"></i>
            <span class="sr-only">Toggle Menu</span>
            </button>
            <button class="btn btn-dark d-inline-block d-lg-none ml-auto" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fa fa-bars"></i>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="nav navbar-nav ml-auto">   <!-- ml-->
            <!-- Cliente logeado -->
            <?php if (session()->get('dniCliente')):?>
                <li class="nav-item active">
                    <a class="nav-link" href="<?= site_url('/home'); ?>">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= site_url('/lineasHorarios'); ?>">Líneas y horarios</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= site_url('/tarifas'); ?>">Tarifas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= site_url('/reserva'); ?>">Comprar billetes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= site_url('/opinion'); ?>">Tu opinión</a>
                </li>

            <!-- Admin logeado -->
            <?php elseif (session()->get('admin')): ?>
                <li class="nav-item active">
                    <a class="nav-link" href="<?= site_url('/admin/home'); ?>">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= site_url('/admin/trenes'); ?>">Administración de trenes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= site_url('/admin/rutas'); ?>">Gestión de Rutas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= site_url('/admin/averias'); ?>">Gestión averías</a>
                </li>
            <?php else: ?>
                <!-- El caso de que no sin session -->
                <?php
                    // Obtener el ultimo segmento de la url
                    $ultimoSegmento = explode('/', current_url());
                    $ultimoSegmento = end($ultimoSegmento);            
                    $validSegments = ['visitante', 'lineasHorarios', 'tarifas'];
                    if (in_array($ultimoSegmento, $validSegments)) {
                        // el visitante puede consultar viajes/horario,tarifas
                        // Click sobre horarios
                        echo "<li class='nav-item active'>";
                            $urlHorarios = current_url();
                            if (strpos($urlHorarios, 'lineasHorarios') !== false) {
                                $urlHorarios = $urlHorarios;
                            } elseif(strpos($urlHorarios, 'tarifas') !== false) {
                                $urlHorarios = str_replace('tarifas', 'lineasHorarios', $urlHorarios);
                            } else {
                                $urlHorarios = $urlHorarios."/lineasHorarios";
                            }
                            echo "<a class='nav-link' href='".$urlHorarios."'>Líneas y horarios</a>";
                        echo "</li>";

                        // Click sobre tarifas
                        echo "<li class='nav-item active'>";
                            // Cambiar lineasHorarios por tarifas
                            $urlTarifas = current_url();
                            if (strpos($urlTarifas, 'lineasHorarios') !== false) {
                                $urlTarifas = str_replace('lineasHorarios', 'tarifas', $urlTarifas);
                            } elseif(strpos($urlTarifas, 'tarifas') !== false) {
                                $urlTarifas = $urlTarifas;
                            } else {
                                $urlTarifas = $urlTarifas."/tarifas";
                            }
                            
                            // Mostrar el enlace de tarifas con la URL modificada
                            echo "<a class='nav-link' href='".$urlTarifas."'>Tarifas</a>";
                        echo "</li>";
                    }        
                ?>
            <?php endif; ?>

            </ul>
            </div>
        </div>
    </nav>
    
<!-- </div> -->