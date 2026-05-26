const panel = document.getElementById("panelFormulario");
const cerrar = document.getElementById("cerrarFormulario");
const listado = document.getElementById("contenedorListado");
const listado1 = document.getElementById("columna1");
const listado2 = document.getElementById("columna2");
const btnHistorial = document.getElementById("btnHistorial");
const btnNuevaCita = document.getElementById("btnNuevaCita");
const contenedorListado = document.getElementById("contenedorListado");
const contenedorHistorial = document.getElementById("contenedorHistorial");
const panelFormulario = document.getElementById("panelFormulario");
const barraAgenda = document.getElementById("barraAgenda");
const filtrosAgenda = document.getElementById("filtrosAgenda");

function toggleAcciones(abrirForm) {
  document.querySelectorAll(".acciones-cita").forEach((el) => {
    el.classList.toggle("d-none", abrirForm);
  });
}

function modoFormulario(activar) {
  document.querySelectorAll(".col-info").forEach((el) => {
    el.classList.toggle("col-lg-12", activar);
    el.classList.toggle("col-lg-9", !activar);
  });

  document.querySelectorAll(".col-acciones").forEach((el) => {
    el.classList.toggle("d-none", activar);
  });
}

function filtrarPorFecha(fecha) {
  document.querySelectorAll(".card-cita").forEach((card) => {
    const cardFecha = card.dataset.fecha;

    if (cardFecha === fecha) {
      card.classList.remove("d-none");
    } else {
      card.classList.add("d-none");
    }
  });
}
document.addEventListener("DOMContentLoaded", () => {
  const input = document.getElementById("filtroFecha");
});
document.getElementById("filtroFecha").addEventListener("change", function () {
  window.location.href = "agenda.php?fecha=" + this.value;
});

const inputFecha = document.getElementById("filtroFecha");

document.getElementById("btnPrevDia").addEventListener("click", () => {
  const input = document.getElementById("filtroFecha");

  let fecha = new Date(input.value);
  fecha.setDate(fecha.getDate() - 1);

  const nuevaFecha = fecha.toISOString().split("T")[0];

  window.location.href = "agenda.php?fecha=" + nuevaFecha;
});

document.getElementById("btnNextDia").addEventListener("click", () => {
  const input = document.getElementById("filtroFecha");

  let fecha = new Date(input.value);
  fecha.setDate(fecha.getDate() + 1);

  const nuevaFecha = fecha.toISOString().split("T")[0];

  window.location.href = "agenda.php?fecha=" + nuevaFecha;
});

cerrar.addEventListener("click", () => {
  panel.classList.add("d-none");

  listado.classList.remove("col-6");
  listado.classList.add("col-12");
  toggleAcciones(false);
  modoFormulario(false);
});
document.getElementById("dni").addEventListener("keypress", function (e) {
  if (e.key === "Enter") {
    e.preventDefault();

    let dni = this.value;

    fetch("../Controler/GetDNI.php?dni=" + dni)
      .then((response) => response.json())

      .then((data) => {
        if (data) {
          document.getElementById("nombre_paciente").value = data.nombre;

          document.getElementById("id_paciente").value = data.id_paciente;
        } else {
          alert("Paciente no encontrado");
        }
      });
  }
});
document.getElementById("formCita").addEventListener("submit", function (e) {
  e.preventDefault();

  let formData = new FormData(this);

  fetch("../Controler/Add_cita.php", {
    method: "POST",
    body: formData,
  })
    .then((res) => res.text())

    .then((data) => {
      let partes = data.split("|");

      let status = partes[0];
      let redirect = partes[2];
      let message = partes[1];

      if (status === "ocupado") {
        Swal.fire({
          icon: "warning",
          title: "Horario ocupado",
          text: message,
        });
        return;
      } else if (status === "ok") {
        Swal.fire({
          icon: "success",
          title: "Cita registrada",
          text: message,
        }).then(() => {
          window.location.href = redirect;
        });
      } else {
        Swal.fire({
          icon: "error",
          title: "Ocurrio un problema",
          text: message,
        });
      }
    });
});

btnHistorial.addEventListener("click", () => {
  contenedorListado.classList.add("d-none");
  panelFormulario.classList.add("d-none");

  contenedorHistorial.classList.remove("d-none");
  filtrosAgenda.classList.add("d-none");

  btnHistorial.classList.add("d-none");
  btnNuevaCita.innerHTML = `
        <i class="bi bi-arrow-left me-2"></i>
        Volver a Agenda
    `;
});

btnNuevaCita.addEventListener("click", () => {
  // SI ESTÁ EN HISTORIAL → VOLVER
  if (!contenedorHistorial.classList.contains("d-none")) {
    contenedorHistorial.classList.add("d-none");

    contenedorListado.classList.remove("d-none");

    filtrosAgenda.classList.remove("d-none");

    btnHistorial.classList.remove("d-none");

    panelFormulario.classList.add("d-none");

    btnNuevaCita.innerHTML = `
            <i class="bi bi-plus-circle me-2"></i>
            Nueva Cita
        `;

    return;
  }

  // ABRIR FORMULARIO
  panel.classList.remove("d-none");

  listado.classList.remove("col-12");
  listado.classList.add("col-6");

  toggleAcciones(true);

  modoFormulario(true);
});
