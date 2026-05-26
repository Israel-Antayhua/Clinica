document.getElementById("formMedicos").style.display = "none";
document.getElementById("formEspecialidades").style.display = "none";

function abrirFormulario() {
  document.getElementById("tablaMedicos").style.display = "none";
  document.getElementById("formMedicos").style.display = "block";
}

function cerrarFormulario() {
  document.getElementById("formMedicos").style.display = "none";
  document.getElementById("tablaMedicos").style.display = "block";
}

function abrirFormulario2() {
  document.getElementById("tablaEspecialidades").style.display = "none";
  document.getElementById("formEspecialidades").style.display = "block";
}

function cerrarFormulario2() {
  document.getElementById("formEspecialidades").style.display = "none";
  document.getElementById("tablaEspecialidades").style.display = "block";
}
document.querySelectorAll(".toggleEstado").forEach((btn) => {

  btn.addEventListener("click", function () {

    let id = this.dataset.id;

    fetch("../Controler/Add_Especia.php", {

      method: "POST",

      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },

      body: "accion=estado&id=" + id,

    })

    .then((res) => res.json())

    .then((data) => {

      if (data.success) {

        // ICONO
        let icon = this.querySelector("i");

        // FILA
        let fila = this.closest("tr");

        // BADGE
        let badge = fila.querySelector(".estadoBadge");

        // TEXTO BADGE
        badge.innerText = data.estado;

        if (data.estado === "Activo") {

          // ICONO
          icon.classList.remove("bi-toggle-off", "text-danger");
          icon.classList.add("bi-toggle-on", "text-success");

          // BADGE
          badge.className =
            "badge estadoBadge rounded-pill px-3 py-2 bg-success-subtle text-success border border-success-subtle shadow-sm";

        } else {

          // ICONO
          icon.classList.remove("bi-toggle-on", "text-success");
          icon.classList.add("bi-toggle-off", "text-danger");

          // BADGE
          badge.className =
            "badge estadoBadge rounded-pill px-3 py-2 bg-danger-subtle text-danger border border-danger-subtle shadow-sm";
        }
      }
    });
  });
});
document.querySelectorAll(".btnEditar").forEach((btn) => {
  btn.addEventListener("click", function () {
    document.getElementById("edit_id").value = this.dataset.id;
    document.getElementById("edit_nombre").value = this.dataset.nombre;
    document.getElementById("edit_precio").value = this.dataset.precio;

    new bootstrap.Modal(document.getElementById("modalEditar")).show();
  });
});
document.querySelectorAll(".btnEditar2").forEach((btn) => {
  btn.addEventListener("click", function () {
    document.getElementById("med_id").value = this.dataset.id;
    document.getElementById("med_nombre").value = this.dataset.nombre;
    document.getElementById("med_telefono").value = this.dataset.telefono;
    document.getElementById("med_usuario").value = this.dataset.usuario;
    document.getElementById("med_especialidad").value =
      this.dataset.id_especialidad;

    new bootstrap.Modal(document.getElementById("modalMedico")).show();
  });
});
function cambiarVista(vista) {
  let medicos = document.getElementById("vistaMedicos");
  let especialidades = document.getElementById("vistaEspecialidades");

  let btnMedicos = document.getElementById("btnVistaMedicos");
  let btnEspecialidades = document.getElementById("btnVistaEspecialidades");

  if (vista === "medicos") {
    medicos.style.display = "block";
    especialidades.style.display = "none";

    btnMedicos.classList.remove("btn-outline-primary");
    btnMedicos.classList.add("btn-primary");

    btnEspecialidades.classList.remove("btn-primary");
    btnEspecialidades.classList.add("btn-outline-primary");
  } else {
    medicos.style.display = "none";
    especialidades.style.display = "block";

    btnEspecialidades.classList.remove("btn-outline-primary");
    btnEspecialidades.classList.add("btn-primary");

    btnMedicos.classList.remove("btn-primary");
    btnMedicos.classList.add("btn-outline-primary");
  }
}
