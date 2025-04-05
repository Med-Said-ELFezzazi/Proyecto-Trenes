<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Bienvenidos</title>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
        <div class="container mt-5">
            <div class="row">
                <div class="col-md-8 offset-md-2 text-center">
                    <?php if (session()->get('admin')): ?>
                        <h1>Bienvenido <b style="color: green;"><?= session()->get('admin'); ?></b></h1>
                        <p class="lead">Aqui tienes todo lo que puedes hacer</p>
                    <?php else: ?>
                        <h1>Bienvenido de nuevo <b style="color: blue;">'<?= session()->get('cliente')->nombre; ?>'</b></h1>
                        <!-- <p class="lead">Tu compañero de confianza para un transporte cómodo y seguro.</p> -->
                    <?php endif; ?>
                </div>
            </div>


            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100">
                        <?php if (session()->get('admin')): ?>
                            <!-- Administraciñon de trenes -->
                            <img src="<?= base_url('/images/tren.png')?>" class="card-img-top" style="height: 150px;">
                            <div class="card-body">
                                <h3 class="card-title">Administración de trenes</h3>
                                <p class="card-text">
                                    Gestión de Vehículos de Servicio Público
                                </p>
                                <a href="<?= site_url('/admin/trenes'); ?>" class="btn btn-primary">Ver trenes</a>
                            </div>
                            <?php else: ?>
                                <!-- Reserva de Billetes -->
                            <img src="<?= base_url('/images/reservaOnline.png')?>" class="card-img-top" style="height: 150px;">
                            <div class="card-body">
                                <h3 class="card-title">Reserva de Billetes</h3>
                                <p class="card-text">
                                    Reserva tus billetes de manera fácil/rápida
                                </p>
                                <a href="<?= site_url('/reserva'); ?>" class="btn btn-primary">Reservar Ahora</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card h-100">
                        <?php if (session()->get('admin')): ?>
                            <!-- Gestion de rutas -->
                            <img src="<?= base_url('/images/gestionRutas.png') ?>" class="card-img-top" style="height: 150px;">
                            <div class="card-body">
                                <h3 class="card-title">Gestión de Rutas</h3>
                                <p class="card-text">
                                    Administra las rutas de servicios: (crea, edita o elimina)
                                </p>
                                <a href="<?= site_url('/admin/rutas'); ?>" class="btn btn-primary">Gestionar Rutas</a>
                            </div>
                            <?php else: ?>
                                <!-- Consulta de Horarios -->
                            <img src="<?= base_url('/images/rutas.png')?>" class="card-img-top" style="height: 150px;">
                            <div class="card-body">
                                <h3 class="card-title">Horarios y Rutas</h3>
                                <p class="card-text">
                                    Accede a toda la información de viajes
                                </p>
                                <a href="<?= site_url('/lineasHorarios'); ?>" class="btn btn-primary">Ver Horarios</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card h-100">
                        <?php if (session()->get('admin')): ?>
                            <!-- Gestion de averias -->          
                            <img src="<?= base_url('/images/averias.png')?>" class="card-img-top" style="height: 150px;">
                            <div class="card-body">
                                <h3 class="card-title">Gestión de averías</h3>
                                <p class="card-text">
                                    Visualizar y gestionar las averías (alta/baja)
                                </p>
                                <a href="<?= site_url('/admin/averias'); ?>" class="btn btn-primary">Gestión averías</a>
                            </div>
                        <?php else: ?>
                            <!-- Tarifas -->
                            <img src="<?= base_url('/images/precio.png')?>" class="card-img-top" style="height: 150px;">
                            <div class="card-body">
                                <h3 class="card-title">Tarifas</h3>
                                <p class="card-text">
                                    Consulta las tarifas de todas la lineas
                                </p>
                                <a href="<?= site_url('/tarifas'); ?>" class="btn btn-primary">Consultar Tarifas</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </div>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    </body>
</html>