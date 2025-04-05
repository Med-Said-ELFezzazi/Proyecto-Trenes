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
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trenes</title>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // Función cambia entre la vista de listar infos y añadir nuevo tren
            function cambiarVista() {
                // Obtener el texto del button Añadir nuevo tren
                let btnAniadirTren = document.getElementById('btnAniadirTren');
                let textoBtn = btnAniadirTren.textContent;

                if (textoBtn == 'Añadir nuevo Tren') {
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
        
        document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.btn-editar').forEach(function(btnEditar) {
            btnEditar.addEventListener('click', function() {
                const fila = this.closest('tr'); // Fila actual
                const form = fila.querySelector('.tren-form'); // Formulario de la fila
                const editando = fila.classList.contains('editando'); // Verificar modo edición

                if (!editando) {
                    // Cambiar texto de la fila a inputs
                    fila.querySelectorAll('[data-field]').forEach(function(campo) {
                        const nomInput = campo.getAttribute('data-field');
                        const tipo = nomInput === 'modelo' ? 'text' : 'number';
                        const value = campo.textContent.trim();

                        // Crear un nuevo input y agregarlo al campo
                        const input = document.createElement('input');
                        input.type = tipo;
                        input.name = nomInput;
                        input.value = value;
                        input.className = 'form-control';

                        // Reemplazar el contenido del campo con el nuevo input
                        campo.innerHTML = '';
                        campo.appendChild(input);
                    });

                    // Cambiar botón a "Guardar"
                    this.textContent = 'Guardar';
                    fila.classList.add('editando');
                } else {
                    // Validar los campos antes de enviar el formulario
                    const inputs = fila.querySelectorAll('input');
                    let valido = true;

                    inputs.forEach(function(input) {
                        if (!input.value.trim()) {
                            valido = false;
                            alert(`El campo ${input.name} no puede estar vacío.`);
                        }
                    });

                    if (valido) {
                        // Actualizar los campos ocultos con los nuevos valores
                        inputs.forEach(function(input) {
                            let hiddenInput = form.querySelector(`input[type="hidden"][name="${input.name}"]`);
                            if (!hiddenInput) {
                                // Crear un campo hidden si no existe
                                hiddenInput = document.createElement('input');
                                hiddenInput.type = 'hidden';
                                hiddenInput.name = input.name;
                                form.appendChild(hiddenInput);
                            }
                            hiddenInput.value = input.value; // Actualizar el valor del campo oculto
                        });

                        // Añadir atributo name al botón
                        this.setAttribute('name', 'btnModificar');

                        // Enviar el formulario al hacer clic en "Guardar"
                        form.submit();
                    }
                }
            });
        });
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
                                    <span aria-hidden="true">&times;</span></button>';
                echo '</div>';
            }
            if (isset($msgErrorEliTren)) {
                // Eliminacion incorrecta
                echo '<div class="alert alert-danger" role="alert">';
                echo $msgErrorEliTren;
                echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span></button>';
                echo '</div>';
            }
        }

        if (isset($_POST['btnModificar'])) {
            echo $mod;
        }
        var_dump($_POST); // Muestra los datos enviados por el formulario

        ?>

        <!-- Tabla de infos de trenes -->
        <div id="trenInfo">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Imagen</th>
                        <th>num_serie</th>
                        <th>Capacidad</th>
                        <th>Modelo</th>
                        <th colspan="2">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($datosTrenes as $tren): ?>
                        <tr data-num_serie="<?= $tren->num_serie; ?>">
                            <td>
                                <?php $rutaImg = base_url('images/trenes/' . $tren->imagen); ?>
                                <img src="<?= $rutaImg ?>" alt="Tren Image" class="img-fluid" style="width: 100px; height: auto;">
                            </td>
                            <?= form_open(current_url('/mod'), ['method' => 'post', 'class' => 'tren-form']) ?>
                            <td><?= $tren->num_serie; ?></td>
                            <td data-field="capacidad"><?= $tren->capacidad; ?></td>
                            <td data-field="modelo"><?= $tren->modelo; ?></td>
                            <td>
                                <button type="button" class="btn btn-warning btn-sm btn-editar">Editar</button>
                            </td>
                            <td>
                                <!-- Borrar -->
                                <?= form_hidden('capacidad', $tren->capacidad); ?>
                                <?= form_hidden('modelo', $tren->modelo); ?>


                                <?= form_hidden('num_serie', $tren->num_serie); ?>
                                <?= form_input([
                                    'name' => 'borrarTren',
                                    'type' => 'submit',
                                    'class' => 'btn btn-danger btn-sm',
                                    'value' => 'Borrar'
                                ]); ?>
                            </td>
                            <?= form_close(); ?>
                        </tr>
                    <?php endforeach; ?>

                </tbody>
            </table>
        </div>

        <!-- Formulario para añadir nuevo tren -->
        <div id="nuevotrenForm" class="d-none">
            <h3 class="text-center text-success">Rellena los siguientes datos</h3>
            <?= form_open(site_url('/admin/trenes'), ['method' => 'post', 'enctype' => 'multipart/form-data']) ?>
            <div class="form-group">
                <label for="imagen">Imagen</label>
                <?php
                echo form_upload([
                    'name' => 'imagen',
                    'class' => 'form-control',
                    'accept' => '.jpg,.jpeg,.png,.gif'
                ]);
                // El accept solo da sugerencias en el html
                ?>
            </div>
            <div class="form-group">
                <label for="num_serie">Num_serie</label>
                <?php
                echo form_input([
                    'type' => 'text',
                    'name' => 'num_serie',
                    'id' => 'num_serie',
                    'class' => 'form-control',
                    'required' => 'required'
                ]);
                ?>
            </div>
            <div class="form-group">
                <label for="capacidad">Capacidad</label>
                <?php
                echo form_input([
                    'type' => 'number',
                    'name' => 'capacidad',
                    'id' => 'capacidad',
                    'class' => 'form-control',
                    'min' => 5,
                    'required' => 'required'
                ]);
                ?>
            </div>
            <div class="form-group">
                <label for="modelo">Modelo</label>
                <?php
                echo form_input([
                    'type' => 'text',
                    'name' => 'modelo',
                    'id' => 'modelo',
                    'class' => 'form-control',
                    'required' => 'required'
                ]);
                ?>
            </div>
            <?= form_input([
                'type' => 'submit',
                'name' => 'aniadirTren',
                'value' => 'Guardar datos',
                'class' => 'btn btn-success'
            ]) ?>

            <?= form_close(); ?>
        </div>
    </div>
</body>

</html>