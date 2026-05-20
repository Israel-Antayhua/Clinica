<?php
session_start();

if (isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body class="m-0 overflow-hidden p-0">

    <div class="container-fluid bg-white shadow-sm p-0">

        <div class="row min-vh-100 g-0 p-0">

            <!-- Imagen -->
            <div class="col-6 vh-100 overflow-hidden px-1">

                <img
                    src="../Imagenes/portada4.jpg"
                    alt="Login"
                    class="w-100 h-100 object-fit-cover">

            </div>

            <!-- Login -->
            <div class="col-6 d-flex justify-content-center align-items-center bg-white p-2 overflow-auto">

                <div class="card shadow-lg border-0 rounded-4 w-100" style="max-width: 600px; width: 100%; max-height: 95vh;">

                    <!-- HEADER -->
                    <div class="card-header bg-success text-white text-center py-4 rounded-top-4 border-0">

                        <i class="bi bi-person-plus-fill fs-1"></i>

                        <h2 class="fw-bold mt-2 mb-1">
                            Registro de Paciente
                        </h2>

                        <p class="mb-0">
                            Crea tu cuenta para acceder al sistema
                        </p>

                    </div>

                    <!-- BODY -->
                    <div class="card-body p-4" style="max-height: 85vh; overflow-y: auto;">

                        <form action="../Controler/Add_Paciente.php" method="POST">

                            <div class="row g-2">

                                <!-- NOMBRES -->
                                <div class="col-7">
                                    <label class="form-label fw-semibold small">Nombres</label>
                                    <input type="text" name="nombres" class="form-control form-control-sm" required>
                                </div>

                                <div class="col-5">
                                    <label class="form-label fw-semibold small">DNI</label>
                                    <input type="text" name="dni" maxlength="8" class="form-control form-control-sm" required>
                                </div>

                                <!-- APELLIDOS -->
                                <div class="col-7">
                                    <label class="form-label fw-semibold small">Apellidos</label>
                                    <input type="text" name="apellidos" class="form-control form-control-sm" required>
                                </div>

                                <div class="col-5">
                                    <label class="form-label fw-semibold small">Nacimiento</label>
                                    <input type="date" name="fecha_nacimiento" class="form-control form-control-sm" required>
                                </div>

                                <!-- CELULAR + CORREO -->
                                <div class="col-4">
                                    <label class="form-label fw-semibold small">Celular</label>
                                    <input type="text" name="celular" class="form-control form-control-sm" required>
                                </div>

                                <div class="col-8">
                                    <label class="form-label fw-semibold small">Correo</label>
                                    <input type="email" name="correo" class="form-control form-control-sm" required>
                                </div>

                                <!-- DIRECCION -->
                                <div class="col-12">
                                    <label class="form-label fw-semibold small">Dirección</label>
                                    <input type="text" name="direccion" class="form-control form-control-sm">
                                </div>

                                <!-- USUARIO -->
                                <div class="col-12">
                                    <label class="form-label fw-semibold small">Usuario</label>
                                    <input type="text" name="usuario" class="form-control form-control-sm" required>
                                </div>

                                <!-- CONTRASEÑA + CONFIRMAR -->
                                <div class="col-12">
                                    <label class="form-label fw-semibold small">Contraseña</label>
                                    <input type="password" name="password" class="form-control form-control-sm" required>
                                </div>


                                <!-- BOTÓN -->
                                <div class="col-12 mt-2">
                                    <button type="submit" class="btn btn-success btn-sm w-100 rounded-3">
                                        <i class="bi bi-check-circle me-2"></i>
                                        Registrarse
                                    </button>
                                </div>

                                <div class="text-center mt-3">
                                    <a href="login.php" class="text-decoration-none text-secondary">
                                        ¿ya tienes cuenta? Iniciar Sesion
                                    </a>
                                </div>

                            </div>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

</body>

</html>