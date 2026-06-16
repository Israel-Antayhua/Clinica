<?php
include '../includes/cabecera.php';
include '../ConexionDB/conexion.php';

$idPaciente = $_SESSION['id_usuario'];

$stmt = $conexion->prepare("
    SELECT p.nombres, p.apellidos, p.dni, p.celular, p.direccion,
           p.fecha_nacimiento, u.correo AS correo
    FROM pacientes p
    Inner Join usuarios u On u.id = p.id_usuario
    WHERE id_paciente = ?
");

$stmt->bind_param("i", $idPaciente);
$stmt->execute();

$paciente = $stmt->get_result()->fetch_assoc();
?>
<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <h3 class="fw-bold mb-1">
            Mi perfil
        </h3>

        <small class="text-secondary">
            Gestiona tus datos personales
        </small>

    </div>

</div>
<div class="row g-4">

    <!-- PERFIL -->
    <div class="col-lg-4">

        <div class="card border-0 shadow-sm h-100">

            <div class="card-body d-flex flex-column h-100 text-center p-4">

                <!-- PARTE SUPERIOR -->
                <div>

                    <i class="bi bi-person-circle text-primary display-1"></i>

                    <h4 class="fw-bold mt-3 mb-1">
                        <?= htmlspecialchars($paciente['nombres']) ?>
                        <?= htmlspecialchars($paciente['apellidos']) ?>
                    </h4>

                    <span class="badge bg-primary">
                        Paciente
                    </span>

                </div>

                <hr class="my-4">

                <!-- INFO (se expande) -->
                <div class="text-start flex-grow-1">

                    <p class="mb-3">
                        <i class="bi bi-envelope text-primary me-2"></i>
                        <?= htmlspecialchars($paciente['correo']) ?>
                    </p>

                    <p class="mb-3">
                        <i class="bi bi-telephone text-success me-2"></i>
                        <?= htmlspecialchars($paciente['celular']) ?>
                    </p>

                    <p class="mb-3">
                        <i class="bi bi-card-text text-warning me-2"></i>
                        DNI: <?= htmlspecialchars($paciente['dni']) ?>
                    </p>

                    <p class="mb-0">
                        <i class="bi bi-calendar-event text-danger me-2"></i>
                        <?= htmlspecialchars($paciente['fecha_nacimiento']) ?>
                    </p>

                </div>

                <!-- OPCIONAL: BOTÓN ABAJO -->
                <div class="mt-auto pt-3">

                    <button class="btn btn-outline-secondary w-100" disabled>
                        <i class="bi bi-person-badge me-2"></i>
                        Información del perfil
                    </button>

                </div>

            </div>

        </div>

    </div>

    <!-- FORMULARIO -->
    <div class="col-lg-8">

        <div class="card border-0 shadow-sm">

            <div class="card-header bg-primary text-white">

                <h5 class="mb-0">

                    <i class="bi bi-pencil-square me-2"></i>

                    Editar información personal

                </h5>

            </div>

            <div class="card-body p-4">

                <form id="perfilForm">

                    <div class="row g-3">

                        <div class="col-md-6">

                            <label class="form-label">
                                Nombres
                            </label>

                            <input
                                type="text"
                                name="nombres"
                                class="form-control"
                                value="<?= htmlspecialchars($paciente['nombres']) ?>"
                                required>

                        </div>

                        <div class="col-md-6">

                            <label class="form-label">
                                Apellidos
                            </label>

                            <input
                                type="text"
                                name="apellidos"
                                class="form-control"
                                value="<?= htmlspecialchars($paciente['apellidos']) ?>"
                                required>

                        </div>

                        <div class="col-md-6">

                            <label class="form-label">
                                Teléfono
                            </label>

                            <input
                                type="text"
                                name="telefono"
                                class="form-control"
                                value="<?= htmlspecialchars($paciente['celular']) ?>">

                        </div>

                        <div class="col-md-6">

                            <label class="form-label">
                                Correo
                            </label>

                            <input
                                type="email"
                                name="correo"
                                class="form-control"
                                value="<?= htmlspecialchars($paciente['correo']) ?>">

                        </div>

                        <div class="col-12">

                            <label class="form-label">
                                Dirección
                            </label>

                            <input
                                type="text"
                                name="direccion"
                                class="form-control"
                                value="<?= htmlspecialchars($paciente['direccion']) ?>">

                        </div>

                        <div class="col-md-6">

                            <label class="form-label">
                                DNI
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                value="<?= htmlspecialchars($paciente['dni']) ?>"
                                readonly, disabled>

                        </div>

                        <div class="col-md-6">

                            <label class="form-label">
                                Fecha de nacimiento
                            </label>

                            <input
                                type="date"
                                class="form-control"
                                value="<?= htmlspecialchars($paciente['fecha_nacimiento']) ?>"
                                readonly, disabled>

                        </div>

                    </div>

                    <div class="d-grid mt-4">

                        <button
                            type="submit"
                            class="btn btn-primary btn-lg">

                            <i class="bi bi-save me-2"></i>

                            Guardar cambios

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.getElementById("perfilForm")
        .addEventListener("submit", function(e) {

            e.preventDefault();

            let formData = new FormData(this);

            fetch("../Controler/Update_Perfil.php", {
                    method: "POST",
                    body: formData
                })
                .then(response => response.json())
                .then(data => {

                    if (data.status === "ok") {

                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: 'Perfil actualizado',
                            showConfirmButton: false,
                            timer: 2000
                        }).then(() => {
                            location.reload();
                        });

                    } else {

                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'error',
                            title: data.message,
                            showConfirmButton: false,
                            timer: 2000
                        });

                    }

                });

        });
</script>