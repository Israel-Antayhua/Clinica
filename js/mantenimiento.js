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
          let icon = this.querySelector("i");

          if (data.estado === "Activo") {
            icon.classList.remove("bi-toggle-off", "text-danger");
            icon.classList.add("bi-toggle-on", "text-success");
            location.reload();
          } else {
            icon.classList.remove("bi-toggle-on", "text-success");
            icon.classList.add("bi-toggle-off", "text-danger");
            location.reload();
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
document.querySelectorAll(".btnEditar2").forEach(btn => {

    btn.addEventListener("click", function () {

        document.getElementById("med_id").value = this.dataset.id;
        document.getElementById("med_nombre").value = this.dataset.nombre;
        document.getElementById("med_telefono").value = this.dataset.telefono;
        document.getElementById("med_usuario").value = this.dataset.usuario;
        document.getElementById("med_especialidad").value = this.dataset.id_especialidad;

        new bootstrap.Modal(
            document.getElementById("modalMedico")
        ).show();

    });

});
