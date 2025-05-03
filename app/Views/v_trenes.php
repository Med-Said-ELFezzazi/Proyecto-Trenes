<?php
// msj de error 'Añadir'
if (isset($_POST['aniadirTren'])) {
    if (isset($msgErrorTren)) {
        echo '<div class="alert alert-danger" role="alert">
                ' . $msgErrorTren . '
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"
                <span aria-hidden="true">&times;</span></button>
            </div>';
    }

    // msg de confirmación 'Añadir'
    if (isset($msgMatriExito)) {
        echo '<div class="alert alert-success" role="alert">
                ' . $msgMatriExito . '
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
            </div>';
    }
}

?>

<?= $this->section("title") ?>
    Información de trenes
<?= $this->endSection(); ?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script>
        document.addEventListener('DOMContentLoaded', function() {

        // Función cambia entre la vista de listar infos y añadir nuevo tren
        function cambiarVista() {
            // Obtener el texto del button Añadir nuevo tren
            let btnAniadirTren = document.getElementById('btnAniadirTren');
            let textoBtn = btnAniadirTren.textContent;

            if (textoBtn == 'Añadir nuevo tren') {
                // Cambio del texto titulo
                document.getElementById('titulo-vista').textContent = 'Añadir nuevo tren';
                // Cambio el texto del button
                btnAniadirTren.textContent = 'Listar datos';
                // Cambio la vista al form
                document.getElementById('trenInfo').classList.add('d-none');
                document.getElementById('nuevoTrenForm').classList.remove('d-none');
            } else {
                // Cambio del texto titulo
                document.getElementById('titulo-vista').textContent = 'Información de trenes';
                // Cambio el texto del button
                btnAniadirTren.textContent = 'Añadir nuevo tren';
                // Cambio la vista a la info
                document.getElementById('nuevoTrenForm').classList.add('d-none');
                document.getElementById('trenInfo').classList.remove('d-none');
            }
        }

        let btnAniadirTren = document.getElementById('btnAniadirTren');
        btnAniadirTren.addEventListener('click', cambiarVista);
        });

    </script>

</head>

<body>
    <div class="container mt-5">
        <h1 class="text-center" id="titulo-vista">Información de trenes</h1>

        <!-- Button para pasar a añadir un tren nuevo -->
        <button class="btn btn-primary mb-3" id="btnAniadirTren">Añadir nuevo tren</button>

        <!-- msj de error/confirmacion al borrar y al modificar -->
        <?php
        if (isset($_POST['borrarTren'])) {
            if (isset($eliminacionExisto)) {
                // Eliminacion correcta
                echo '<div class="alert alert-success" role="alert">';
                echo 'El tren ha sido eliminado correctamente';
                echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>';
                echo '</div>';
            }
            if (isset($msgErrorEliTren)) {
                // Eliminacion incorrecta
                echo '<div class="alert alert-danger" role="alert">';
                echo $msgErrorEliTren;
                echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>';
                echo '</div>';
            }
        }
        ?>

        <!-- Tabla de infos de trenes -->
        <div id="trenInfo">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Imagen</th>
                        <th>Numero de serie</th>
                        <th>Modelo</th>
                        <th>Vagones</th>
                        <th>Capacidad</th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($datosTrenes as $tren): ?>
                        <tr data-num_serie="<?= $tren->num_serie; ?>">
                            <td>
                                <?php $rutaImg = base_url('/images/trenes/' . $tren->imagen); ?>
                                <img src="<?= $rutaImg ?>" alt="Tren Image" class="img-fluid" style="width: 120px; height: auto;">
                            </td>
                            <?= form_open(current_url('/mod'), ['method' => 'post', 'class' => 'tren-form']) ?>
                            <td>
                                <?= $tren->num_serie; ?>
                            </td>
                            <td data-field="modelo">
                                <?= $tren->modelo; ?>
                            </td>
                            <td data-field="vagones">
                                <?= $tren->vagones; ?>
                            </td>
                            <td data-field="capacidad">
                                <?= $tren->capacidad; ?>
                            </td>
                            <td>
                                <!-- Borrar -->
                                <?= form_hidden('capacidad', $tren->capacidad); ?>
                                <?= form_hidden('modelo', $tren->modelo); ?>
                                <?= form_hidden('numSerie', $tren->num_serie); ?>

                                <a href="<?php echo current_url() . '/mod/' . $tren->num_serie; ?>" class="btn btn-warning">Editar</a>                               
                            </td>
                            <td>
                                <?= form_input(['name' => 'borrarTren', 'type' => 'submit', 'class' => 'btn btn-danger btn-sm', 'value' => 'Borrar']); ?>
                            </td>
                            <?= form_close(); ?>
                        </tr>
                    <?php endforeach; ?>

                </tbody>
            </table>
        </div>

        <!-- Formulario para añadir nuevo tren -->
        <div id="nuevoTrenForm" class="d-none">
            <h3 class="text-center text-success">Rellena los siguientes datos</h3>
            <?= form_open(site_url('/admin/trenes'), ['method' => 'post', 'enctype' => 'multipart/form-data']) ?>
                <div class="form-group">
                    <label for="imagen">Imagen:</label>
                    <?= form_upload(['name' => 'imagen', 'class' => 'form-control', 'accept' => '.jpg,.jpeg,.png,.gif']) ?>
                </div>
                <div class="form-group">
                    <label for="numSerie">Numero de serie:</label>
                    <?= form_input(['type' => 'text', 'name' => 'numSerie', 'id' => 'numSerie', 'class' => 'form-control', 'required' => 'required']) ?>
                </div>
                <div class="form-group">
                    <label for="modelo">Modelo:</label>
                    <?= form_input(['type' => 'text', 'name' => 'modelo', 'id' => 'modelo', 'class' => 'form-control', 'required' => 'required']) ?>
                </div>
                <div class="form-group">
                    <label for="vagon">Vagones:</label>
                    <?= form_input(['type' => 'text', 'name' => 'vagones', 'id' => 'vagones', 'class' => 'form-control', 'required' => 'required']) ?>
                </div>
                <div class="form-group">
                    <label for="capacidad">Capacidad:</label>
                    <?= form_input(['type' => 'number', 'name' => 'capacidad', 'id' => 'capacidad', 'class' => 'form-control', 'min' => 5, 'required' => 'required']) ?>
                </div>
                <?= form_input(['type' => 'submit', 'name' => 'aniadirTren', 'value' => 'Guardar datos', 'class' => 'btn btn-success']) ?>
            <?= form_close(); ?>
        </div>
    </div>
</body>

</html>