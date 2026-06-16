<?php
include '../ConexionDB/conexion.php';
$espe = $conexion->query("SELECT * FROM especialidades");

while ($e = $espe->fetch_assoc()):

    $badge = ($e['estado'] == 'Activo')
        ? 'bg-success-subtle text-success border border-success-subtle'
        : 'bg-danger-subtle text-danger border border-danger-subtle';
?>

    <tr id="fila_<?php echo $e['id']; ?>">

        <!-- ID -->
        <td>

            <span class="fw-semibold text-dark">

                #<?php echo $e['id']; ?>

            </span>

        </td>

        <!-- Especialidad -->
        <td>

            <div class="d-flex align-items-center gap-3">

                <div class="bg-info bg-opacity-10 rounded-circle d-flex justify-content-center align-items-center shadow-sm"
                    style="width:50px; height:50px;">

                    <i class="bi bi-heart-pulse-fill text-info fs-5"></i>

                </div>

                <div>

                    <div class="fw-bold text-dark"
                        id="nombre_<?php echo $e['id']; ?>">

                        <?php echo $e['nombre']; ?>

                    </div>

                    <small class="text-secondary">
                        Especialidad médica
                    </small>

                </div>

            </div>

        </td>

        <!-- Precio Consulta -->
        <td>

            <div class="bg-success bg-opacity-10 rounded-4 px-3 py-2 d-inline-flex align-items-center gap-2">

                <i class="bi bi-cash-stack text-success"></i>

                <div>

                    <div class="fw-bold text-success"
                        id="precio_<?php echo $e['id']; ?>">

                        S/ <?php echo number_format($e['precio_consulta'], 2); ?>

                    </div>

                    <small class="text-secondary">
                        Consulta
                    </small>

                </div>

            </div>

        </td>

        <!-- Estado -->
        <td>

            <span class="badge estadoBadge rounded-pill px-3 py-2 <?php echo $badge; ?> shadow-sm">

                <?php echo $e['estado']; ?>

            </span>

        </td>

        <!-- Acciones -->
        <td class="text-center">

            <div class="d-flex justify-content-center gap-2">

                <button class="btn btn-sm btn-light border rounded-3 shadow-sm btnEditar"
                    data-id="<?php echo $e['id']; ?>"
                    data-nombre="<?php echo $e['nombre']; ?>"
                    data-precio="<?php echo $e['precio_consulta']; ?>">

                    <i class="bi bi-pencil text-primary"></i>

                </button>

                <button
                    class="btn btn-sm btn-light border toggleEstado"
                    data-id="<?php echo $e['id']; ?>">

                    <?php if ($e['estado'] == 'Activo') { ?>
                        <i class="bi bi-toggle-on text-success"></i>
                    <?php } else { ?>
                        <i class="bi bi-toggle-off text-danger"></i>
                    <?php } ?>

                </button>

            </div>

        </td>

    </tr>

<?php endwhile; ?>