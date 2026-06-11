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

                                <div class="mb-8">
                                    <label class="form-label fw-semibold">Código OTP</label>

                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="bi bi-shield-lock-fill"></i>
                                        </span>

                                        <input
                                            type="text"
                                            id="codigo"
                                            class="form-control text-center fw-bold"
                                            placeholder=""
                                            maxlength="6">
                                    </div>
                                </div>
                                <div class="text-center small">
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const Toast = Swal.mixin({
        toast: true,
        position: 'top',
        showConfirmButton: false,
        timer: 1500,
        timerProgressBar: true
    });
    document.getElementById("loginForm").addEventListener("submit", function(e) {
        e.preventDefault();

        if (document.getElementById("otpBox").style.display === "block") {
            verificar();
            return;
        }

        let formData = new FormData(this);

        Toast.fire({
            title: 'Enviando código...',
            text: 'Por favor espera',
            allowOutsideClick: false,
            didOpen: () => {
                Toast.showLoading();
            }
        });

        fetch("validar.php", {
                method: "POST",
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                Toast.close();

                if (data.status === "otp_sent") {
                    if (data.debug_otp) {
                        document.getElementById("codigo").value = data.debug_otp;
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: 'Código listo',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    } else {
                        Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 2500
                        }).fire({
                            icon: 'success',
                            title: 'Revisa tu correo para continuar'
                        });
                    }
                    document.getElementById("otpBox").style.display = "block";
                    document.querySelector('button[type="submit"]').innerHTML =
                        '<i class="bi bi-check-circle me-2"></i>Verificar código'
                    '<span class="spinner-border spinner-border-sm me-2"></span>Enviando...;';
                }

                if (data.status === "error") {
                    Toast.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message
                    });
                }
            }).catch(error => {

                Toast.close();

                Toast.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Ocurrió un problema al procesar la solicitud'
                });

                console.error(error);
            });
    });
    console.log("OTP DEBUG:", data.debug_otp);
    document.getElementById("codigo").addEventListener("keypress", function(e) {
        if (e.key === "Enter") {
            e.preventDefault();
            verificar();
        }
    });
</script>
<script>
    function verificar() {

        let codigo = document.getElementById("codigo").value;
        Toast.fire({
            title: 'Verificando código...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        fetch("verificar.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: "codigo=" + encodeURIComponent(codigo)
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === "ok") {
                    Toast.fire({
                        icon: 'success',
                        title: 'Codigo correcto',
                        timer: 1200,
                        showConfirmButton: false
                    }).then(() => {

                        window.location.href = "../index.php";

                    });
                } else {
                    Toast.fire({
                        icon: 'error',
                        title: 'Código incorrecto',
                        text: data.message
                    });
                }

            });
    }
</script>

</html>