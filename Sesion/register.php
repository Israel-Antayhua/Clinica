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
            <div class="col-6 d-flex justify-content-center align-items-center bg-white p-2">

                <div class="card shadow-lg border-0 rounded-4 w-100" style="max-width: 450px;">

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
                    <div class="card-body p-4">

                        <form action="guardar_paciente.php" method="POST">

                            <!-- NOMBRE -->
                            <div class="mb-3">

                                <label class="form-label fw-semibold">Nombre</label>

                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-person-fill"></i>
                                    </span>

                                    <input type="text"
                                        name="nombre"
                                        class="form-control"
                                        placeholder="Juan"
                                        required>
                                </div>

                            </div>

                            <!-- APELLIDO -->
                            <div class="mb-3">

                                <label class="form-label fw-semibold">Apellido</label>

                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-person-badge-fill"></i>
                                    </span>

                                    <input type="text"
                                        name="apellido"
                                        class="form-control"
                                        placeholder="Pérez"
                                        required>
                                </div>

                            </div>

                            <!-- USUARIO -->
                            <div class="mb-3">

                                <label class="form-label fw-semibold">Usuario</label>

                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-at"></i>
                                    </span>

                                    <input type="text"
                                        name="usuario"
                                        class="form-control"
                                        placeholder="juanperez"
                                        required>
                                </div>

                            </div>

                            <!-- CONTRASEÑA -->
                            <div class="mb-3">

                                <label class="form-label fw-semibold">Contraseña</label>

                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-lock-fill"></i>
                                    </span>

                                    <input type="password"
                                        name="password"
                                        class="form-control"
                                        placeholder="••••••••"
                                        required>
                                </div>

                            </div>

                            <!-- BOTÓN -->
                            <div class="d-grid mt-4">

                                <button type="submit" class="btn btn-success btn-lg rounded-3">
                                    <i class="bi bi-check-circle me-2"></i>
                                    Registrarse
                                </button>

                            </div>

                            <!-- LINK LOGIN -->
                            <div class="text-center mt-3">
                                <a href="login.php" class="text-decoration-none text-secondary">
                                    ¿Ya tienes cuenta? Inicia sesión
                                </a>
                            </div>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

</body>

</html>