const btnNueva = document.getElementById("btnNuevaCita");
const panel = document.getElementById("panelFormulario");
const cerrar = document.getElementById("cerrarFormulario");
const listado = document.getElementById("contenedorListado");
const listado1 = document.getElementById("columna1");
const listado2 = document.getElementById("columna2");

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

btnNueva.addEventListener("click", () => {
  panel.classList.remove("d-none");

  listado.classList.remove("col-12");
  listado.classList.add("col-6");
  toggleAcciones(true);
  modoFormulario(true);
});

cerrar.addEventListener("click", () => {
  panel.classList.add("d-none");

  listado.classList.remove("col-6");
  listado.classList.add("col-12");
  toggleAcciones(false);
  modoFormulario(false);
});
