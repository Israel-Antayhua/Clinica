// Función para confirmar la eliminación de una cita
function confirmarEliminacion(evento) {
    var respuesta = confirm("¿Estás completamente seguro de que deseas eliminar esta cita médica?");
    if (!respuesta) {
        // Si el usuario presiona "Cancelar", evitamos que el enlace funcione
        evento.preventDefault();
    }
}

// ==========================================
// FILTRO DE BUSQUEDA EN TIEMPO REAL
// ==========================================
document.addEventListener("DOMContentLoaded", function () {
    var inputBuscador = document.getElementById("buscador");
    
    // Verificamos si el buscador existe en la pantalla actual (solo aparece para el admin)
    if (inputBuscador) {
        inputBuscador.addEventListener("keyup", function () {
            var filtro = inputBuscador.value.toLowerCase();
            // Seleccionamos todas las filas del cuerpo de la tabla
            var filas = document.querySelectorAll(".tabla-contenedor tbody tr");

            filas.forEach(function (filas) {
                // Evaluamos el texto de la primera columna (que contiene el nombre del paciente)
                var celdaPaciente = filas.getElementsByTagName("td")[0];
                
                if (celdaPaciente) {
                    var nombrePaciente = celdaPaciente.textContent || celdaPaciente.innerText;
                    
                    // Si el nombre contiene lo que escribió el usuario, mostramos la fila, si no, la ocultamos
                    if (nombrePaciente.toLowerCase().indexOf(filtro) > -1) {
                        filas.style.display = "";
                    } else {
                        filas.style.display = "none";
                    }
                }
            });
        });
    }
});

// ==========================================
// CONTROL DE PASARELA DE PAGOS (HU-05)
// ==========================================
function abrirModalPago(idCita, montoCita) {
    // Buscamos el modal y los campos internos
    var modal = document.getElementById("modal-pasarela");
    var inputId = document.getElementById("modal-cita-id");
    var inputMonto = document.getElementById("modal-monto-visible");

    if (modal && inputId && inputMonto) {
        inputId.value = idCita;                      // Seteamos el ID interno para mandarlo a pagar.php
        inputMonto.value = "S/ " + montoCita;       // Mostramos el monto de S/ 90.00 de manera estética
        modal.style.display = "flex";               // Cambiamos de hidden (oculto) a flex para mostrarlo centrado
    }
}

function cerrarModalPago() {
    var modal = document.getElementById("modal-pasarela");
    if (modal) {
        modal.style.display = "none";               // Volvemos a ocultar el modal
    }
}