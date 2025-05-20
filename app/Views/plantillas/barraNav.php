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

            <!-- Solo mostrar el menú si no estamos en la página de login o registro -->
            <?php
            $currentUrl = current_url();
            // Verificar si estamos en las páginas de login o registro
            if (strpos($currentUrl, '/autenticacion') === false) :
            ?>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="nav navbar-nav ml-auto">
                        <?php
                        // Definir rutas y etiquetas según el tipo de usuario
                        if (session()->get('dniCliente')) {
                            $links = [
                                '/home' => 'Home',
                                '/lineasHorarios' => 'Líneas y horarios',
                                '/tarifas' => 'Tarifas',
                                '/reserva' => 'Comprar billetes',
                                '/opinion' => 'Tu opinión',
                            ];
                        } elseif (session()->get('admin')) {
                            $links = [
                                '/admin/home' => 'Home',
                                '/admin/trenes' => 'Administración de trenes',
                                '/admin/rutas' => 'Gestión de Rutas',
                                '/admin/averias' => 'Gestión de averías',
                            ];
                        } else {
                            // Si no hay usuario autenticado, mostrar solo enlaces de visita pública
                            $links = [
                                '/lineasHorarios' => 'Líneas y horarios',
                                '/tarifas' => 'Tarifas',
                            ];
                        }

                        // Generar los enlaces de navegación
                        foreach ($links as $url => $label) {
                            // Verificar si la URL actual coincide con el enlace
                            $isActive = strpos($currentUrl, $url) !== false ? 'active' : '';
                            echo "<li class='nav-item $isActive'><a class='nav-link' href='" . site_url($url) . "'>$label</a></li>";
                        }
                        ?>
                    </ul>
                </div>
            <?php endif; ?>
        </div>
    </nav>
    
<!-- </div> -->