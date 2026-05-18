<?php if (isset($_SESSION['usuario'])): ?>
        </div> <?php endif; ?>

    <script src="js/alertas.js"></script>
    <script>
        document.querySelectorAll('.btn-eliminar').forEach(function(boton) {
            boton.addEventListener('click', confirmarEliminacion);
        });
    </script>
</body>
</html>