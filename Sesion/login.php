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
                    src="../Imagenes/portada5.jpg"
                    alt="Login"
                    class="w-100 h-100 object-fit-cover">

            </div>

            <!-- Login -->
            <div class="col-6 d-flex justify-content-center align-items-center bg-white p-2">

                <div class="card shadow-lg border-0 rounded-4 w-100" style="max-width: 420px;">

                    <div class="card-header bg-primary text-white text-center py-4 rounded-top-4 border-0">

                        <i class="bi bi-shield-lock-fill fs-1"></i>

                        <h2 class="fw-bold mt-2 mb-1">
                            Iniciar Sesión
                        </h2>

                        <p class="mb-0">
                            Accede al sistema con tus credenciales
                        </p>

                    </div>

                    <div class="card-body p-4">

                        <form id="loginForm" method="POST">

                            <div class="mb-4">

                                <label class="form-label fw-semibold">
                                    Correo
                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        <i class="bi bi-person-fill"></i>
                                    </span>

                                    <input
                                        type="text"
                                        name="usuario"
                                        class="form-control"
                                        placeholder=""
                                        required>

                                </div>

                            </div>

                            <div class="mb-4">

                                <label class="form-label fw-semibold">
                                    Contraseña
                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        <i class="bi bi-lock-fill"></i>
                                    </span>

                                    <input
                                        type="password"
                                        name="password"
                                        class="form-control"
                                        placeholder=""
                                        required>

                                </div>

                            </div>

                            <div id="otpBox" class="mb-4" style="display:none;">

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Código OTP</label>

                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text">
                                            <i class="bi bi-shield-lock-fill"></i>
                                        </span>

                                        <input
                                            type="text"
                                            id="codigo"
                                            class="form-control text-center fw-bold"
                                            placeholder="6 dígitos"
                                            maxlength="6">
                                    </div>
                                </div>

                                <div class="d-grid">
                                    <button
                                        type="button"
                                        class="btn btn-success btn-lg rounded-3 small"
                                        onclick="verificar()">
                                        <i class="bi bi-check-circle me-2"></i>
                                        Verificar código
                                    </button>
                                </div>

                                <div class="text-center mt-3 small">
                                    <small class="text-muted">
                                        ¿No recibiste el código? Revisa tu correo o intenta nuevamente
                                    </small>
                                </div>

                            </div>

                            <div class="d-grid">

                                <button type="submit" class="btn btn-primary btn-lg rounded-3">
                                    <i class="bi bi-box-arrow-in-right me-2"></i>
                                    Ingresar
                                </button>

                            </div>
                            <div class="text-center mt-3">
                                <a href="register.php" class="text-decoration-none text-secondary">
                                    ¿No tienes cuenta? Registrarme
                                </a>
                            </div>
                        </form>
                    </div>

                </div>

            </div>

        </div>

    </div>

    </div>

</body>
<script>
    document.getElementById("loginForm").addEventListener("submit", function(e) {
        e.preventDefault();

        let formData = new FormData(this);

        fetch("validar.php", {
                method: "POST",
                body: formData
            })
            .then(res => res.json())
            .then(data => {

                if (data.status === "otp_sent") {
                    alert("Código enviado al correo");

                    document.getElementById("otpBox").style.display = "block";
                }

                if (data.status === "error") {
                    alert(data.message);
                }
            });
    });
</script>
<script>
    function verificar() {

        let codigo = document.getElementById("codigo").value;
        console.log(codigo);
        fetch("verificar.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: "codigo=" + encodeURIComponent(codigo)
            })
            .then(res => res.json())
            .then(data => {
                console.log(codigo);
                if (data.status === "ok") {
                    window.location.href = "../index.php";
                } else {
                    alert(data.message);
                }

            });
    }
</script>

</html>