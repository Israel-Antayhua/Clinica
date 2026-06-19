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
    Swal.fire({
      title: "Editar cita",
      text: "Se enviará un código OTP para confirmar",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Sí, continuar",
    }).then((result) => {
      if (result.isConfirmed) {
        // 🔐 enviar OTP
        document.querySelector('[name="tipo_accion"]').value = "reprogramar";
        fetch("../Controler/Get_Otp.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({
            titulo: "Codigo de confirmacion",
            asunto: "Cambio de horario de cita",
            cuerpo: "Codigo para cambiar el horario: ",
          }),
        })
          .then((res) => res.json())
          .then((data) => {
            if (data.debug_otp) {
              document.getElementById("codigo").value = data.debug_otp;
              Swal.fire({
                toast: true,
                position: "top-end",
                icon: "success",
                title: "Código listo",
                timer: 1500,
                showConfirmButton: false,
              });
            }
          })
          .catch((err) => {
            console.error(err);

            Swal.fire({
              icon: "error",
              title: "Error",
              text: "No se pudo generar el OTP",
            });
          });

        let modal = new bootstrap.Modal(
          document.getElementById("modalEditarHora"),
        );
        modal.show();
      }
    });
  });
});
document
  .getElementById("formCambiarHora")
  .addEventListener("submit", function (e) {
    e.preventDefault();
    let formData = new FormData(this);
    fetch("../Controler/Add_Cita.php", {
      method: "POST",
      body: formData,
    })
      .then((res) => res.text())
      .then((data) => {
        let partes = data.split("|");
        let status = partes[0];
        let message = partes[1];
        if (status === "error") {
          Swal.fire({
            icon: "error",
            title: "Error OTP",
            text: message,
          });

          return;
        }
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
            title: "Cita Actualizada",
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
          console.log(status);
        }
      });
  });
document.querySelectorAll(".btnCancelarCita").forEach((btn) => {
  btn.addEventListener("click", function (e) {
    e.preventDefault();

    const id = this.dataset.id;

    Swal.fire({
      title: "Cancelar cita",
      text: "Se enviará un código OTP para confirmar",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Sí, continuar",
    }).then((result) => {
      if (result.isConfirmed) {
        fetch("../Controler/Get_Otp.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({
            titulo: "Código de confirmación",
            asunto: "Cancelación de cita",
            cuerpo: "Código para cancelar la cita:",
          }),
        })
          .then((res) => res.json())
          .then((data) => {
            if (data.debug_otp) {
              document.getElementById("codigo").value = data.debug_otp;
              Swal.fire({
                toast: true,
                position: "top-end",
                icon: "success",
                title: "Código listo",
                timer: 1500,
                showConfirmButton: false,
              });
            }
            // guardar id de la cita
            document.getElementById("edit_id").value = id;

            // indicar acción
            document.querySelector('[name="tipo_accion"]').value = "cancelar";

            // abrir modal OTP
            let modal = new bootstrap.Modal(
              document.getElementById("modalEditarHora"),
            );

            modal.show();
          });
      }
    });
  });
});
