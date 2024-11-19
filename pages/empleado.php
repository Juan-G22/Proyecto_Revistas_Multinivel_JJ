<?php
session_start(); // Iniciar sesión

// Verificar si la sesión está iniciada y si el rol es de administrador
if (!isset($_SESSION['id_persona']) || $_SESSION['rol'] != 2) {
    header("Location: ../index.php"); // Redirigir al índice si no tiene acceso
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Empleado - Revistas Multinivel JJ</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Barra de navegación -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">Revistas JJ</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                
                <li class="nav-item"><a class="nav-link" href="#">Mis Ventas</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Mis Referidos</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Registrar Cliente</a></li>
                <!-- Enlace para cerrar sesión -->
                <li class="nav-item"><a class="nav-link" href="logout.php">Cerrar sesión</a></li>
            </ul>
        </div>
    </nav>

    <!-- Contenido principal -->
    <div class="container mt-5">
        <h2>Bienvenido, colaborador</h2>
        <p>Aquí puedes ver tu rendimiento de ventas, tus referidos y registrar nuevos clientes.</p>

        <!-- Estadísticas de ventas -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Estadísticas de ventas</h5>
                <p>Total vendido: $XXXX</p>
                <p>Ventas en esta semana: $XXXX</p>
            </div>
        </div>

        <!-- Referidos -->
        <h4>Mis Referidos</h4>
        <table class="table">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Fecha de registro</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Juan Pérez</td>
                    <td>juan@example.com</td>
                    <td>2024-11-01</td>
                </tr>
                <tr>
                    <td>Ana Gómez</td>
                    <td>ana@example.com</td>
                    <td>2024-11-05</td>
                </tr>
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
