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
document.addEventListener("click", function (e) {

  let btn = e.target.closest(".toggleEstado");

  if (!btn) return;

  let id = btn.dataset.id;

  fetch("../Controler/Add_Especia.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded"
    },
    body: "accion=estado&id=" + id
  })
  .then(res => res.json())
  .then(data => {

    if (!data.success) return;

    let icon = btn.querySelector("i");
    let fila = btn.closest("tr");
    let badge = fila.querySelector(".estadoBadge");

    badge.innerText = data.estado;

    if (data.estado === "Activo") {
      icon.className = "bi bi-toggle-on text-success";
      badge.className = "badge estadoBadge bg-success-subtle text-success";
    } else {
      icon.className = "bi bi-toggle-off text-danger";
      badge.className = "badge estadoBadge bg-danger-subtle text-danger";
    }
  });
});
document.addEventListener("click", function (e) {
  let btn = e.target.closest(".btnEditar");

  if (!btn) return;

  let id = btn.dataset.id;

  document.getElementById("edit_id").value = id;

  document.getElementById("edit_nombre").value = document.getElementById(
    "nombre_" + id,
  ).innerText;

  document.getElementById("edit_precio").value = document
    .getElementById("precio_" + id)
    .innerText.replace("S/ ", "")
    .trim();

  new bootstrap.Modal(document.getElementById("modalEditar")).show();
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
document.addEventListener("DOMContentLoaded", function () {
  // =========================
  // EDITAR (ABRIR MODAL)
  // =========================
  document.querySelectorAll(".btnEditar").forEach((btn) => {
    btn.addEventListener("click", function () {
      let id = this.dataset.id;

      // 🔥 leer SIEMPRE desde la tabla (no data-*)
      document.getElementById("edit_id").value = id;

      document.getElementById("edit_nombre").value = document.getElementById(
        "nombre_" + id,
      ).innerText;

      document.getElementById("edit_precio").value = document
        .getElementById("precio_" + id)
        .innerText.replace("S/ ", "")
        .trim();

      new bootstrap.Modal(document.getElementById("modalEditar")).show();
    });
  });

  // =========================
  // EDITAR (GUARDAR AJAX)
  // =========================
  document
    .getElementById("formEspecialidadEditar")
    .addEventListener("submit", function (e) {
      e.preventDefault();

      let formData = new FormData(this);

      fetch("../Controler/Add_Especia.php", {
        method: "POST",
        body: formData,
      })
        .then((res) => res.json())
        .then((data) => {
          if (data.status === "ok") {
            // =========================
            // ACTUALIZAR TABLA
            // =========================
            document.getElementById("nombre_" + data.id).innerText =
              data.nuevo_nombre;

            document.getElementById("precio_" + data.id).innerText =
              "S/ " + parseFloat(data.nuevo_precio).toFixed(2);

            // =========================
            // CERRAR MODAL BIEN
            // =========================
            let modalEl = document.getElementById("modalEditar");
            let modal = bootstrap.Modal.getInstance(modalEl);

            if (modal) modal.hide();

            // limpiar backdrop (evita pantalla gris)
            setTimeout(() => {
              document
                .querySelectorAll(".modal-backdrop")
                .forEach((el) => el.remove());
              document.body.classList.remove("modal-open");
              document.body.style = "";
            }, 200);

            // =========================
            // FEEDBACK
            // =========================
            Swal.fire({
              toast: true,
              position: "top-end",
              icon: "success",
              title: "Especialidad actualizada",
              timer: 1500,
              showConfirmButton: false,
            });

            // highlight visual
            let el = document.getElementById("nombre_" + data.id);
            el.classList.add("text-warning");

            setTimeout(() => {
              el.classList.remove("text-warning");
            }, 800);

            this.reset();
          } else {
            Swal.fire({
              toast: true,
              position: "top-end",
              icon: "error",
              title: data.message || "Error",
              timer: 2000,
              showConfirmButton: false,
            });
          }
        })
        .catch((err) => {
          console.error(err);

          Swal.fire({
            icon: "error",
            title: "Error en servidor",
          });
        });
    });
});
document
  .getElementById("formEspecialidad")
  .addEventListener("submit", function (e) {
    e.preventDefault();

    let formData = new FormData(this);
    formData.append("accion", "insertar");

    fetch("../Controler/Add_Especia.php", {
      method: "POST",
      body: formData,
    })
      .then((res) => res.json())
      .then((data) => {
        if (data.status === "ok") {
          recargarTabla(); // 🔥 refresca todo desde BD

          let modal = bootstrap.Modal.getInstance(
            document.getElementById("modalEditar"),
          );
          if (modal) modal.hide();

          // limpiar form
          this.reset();

          Swal.fire({
            toast: true,
            position: "top-end",
            icon: "success",
            title: "Especialidad agregada",
            timer: 1500,
            showConfirmButton: false,
          });
        } else {
          Swal.fire({
            icon: "error",
            title: data.message || "Error",
          });
        }
      })
      .catch((err) => console.error(err));
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
function recargarTabla() {
  fetch("../Controler/Get_Especia.php")
    .then((res) => res.text())
    .then((html) => {
      document.getElementById("bodyEspecie").innerHTML = html;
    });
}
