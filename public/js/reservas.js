// Función que muestra/oculta el campo fecha según el estado del checkbox
function toggleFechaVuelta() {
    const checkbox = document.getElementById('soloIda');
    const fechaVuelta = document.getElementById('fecha_vuelta');

    if (checkbox.checked) {
        fechaVuelta.style.display = 'none'; // Ocultar
    } else {
        fechaVuelta.style.display = ''; // Mostrar
    }
}

document.addEventListener('DOMContentLoaded', function () {
    console.log("Cargando js");
    const fechaIda = document.getElementById('fecha_ida');
    const fechaVuelta = document.getElementById('fecha_vuelta');

    if (fechaIda && fechaVuelta) {
        // Al cargar, establece el mínimo de fecha de vuelta según la de ida
        fechaVuelta.min = fechaIda.value;

        // Cuando cambia la fecha de ida, actualiza el mínimo de fecha de vuelta
        fechaIda.addEventListener('change', function () {
            fechaVuelta.min = fechaIda.value;
            // Si la fecha de vuelta es anterior a la de ida, la borra
            if (fechaVuelta.value && fechaVuelta.value < fechaIda.value) {
                fechaVuelta.value = '';
            }
        });
    }
});


// Ejecutar al cargar la página
document.addEventListener('DOMContentLoaded', function () {
    const checkbox = document.getElementById('soloIda');
    checkbox.addEventListener('change', toggleFechaVuelta);
    toggleFechaVuelta();
});