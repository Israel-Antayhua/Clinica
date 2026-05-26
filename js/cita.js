const especialidad = document.getElementById("especialidad");
const montoVisible = document.getElementById("montoVisible");
const monto = document.getElementById("monto");
function actualizarMonto() {
  let opcion = especialidad.options[especialidad.selectedIndex];
  let precio = opcion.dataset.precio;
  montoVisible.value = "S/ " + precio;
  monto.value = precio;
}
actualizarMonto();
especialidad.addEventListener("change", actualizarMonto);
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
document.getElementById("especialidad").addEventListener("change", function () {
  let idEspecialidad = this.value;
  fetch("../Controler/Get_Medicos.php?id=" + idEspecialidad)
    .then((res) => res.text()) // 👈 cambia a text temporalmente
    .then((data) => {
      return JSON.parse(data);
    })
    .then((data) => {
      console.log(data.length);
      console.log(data);
      let selectMedico = document.getElementById("id_medico");
      selectMedico.innerHTML = "<option value=''>Seleccione médico</option>";
      if (data.length > 0) {
        data.forEach((medico) => {
          selectMedico.innerHTML += `
                            <option value="${medico.id}">
                                Dr. ${medico.nombre}
                            </option>
                        `;
        });
      } else {
        selectMedico.innerHTML = `
                        <option disabled selected>
                            No hay médicos disponibles.
                        </option>
                    `;
      }
    });
});
document.querySelectorAll(".btnEditarCita").forEach((btn) => {
  btn.addEventListener("click", function () {
    document.getElementById("edit_id").value = this.dataset.id;
    document.getElementById("edit_monto").value = this.dataset.monto;
    document.getElementById("edit_fecha").value = this.dataset.fecha;
    document.getElementById("edit_hora").value = this.dataset.hora;
    document.getElementById("edit_id_medico").value = this.dataset.medico;
    new bootstrap.Modal(document.getElementById("modalEditarHora")).show();
  });
});
document.getElementById("formCambiarHora").addEventListener("submit", function (e) {
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
            title: "Horario Actualizado",
            text: message,
          }).then(() => {
            // 🔥 CERRAR MODAL
            window.location.href = "../Vistas/citas.php";
          });
        } else {
          Swal.fire({
            icon: "error",
            title: "Error",
            text: message,
          });
        }
      });
});
