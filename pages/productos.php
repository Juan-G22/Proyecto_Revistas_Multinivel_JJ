<?php
// Incluir el archivo de conexión a la base de datos
include('../includes/db_connection.php');
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Productos</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container {
            margin-top: 50px;
        }
        .form-label {
            font-weight: bold;
        }
        .form-container {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 30px;
            border-radius: 10px;
            background-color: #f9f9f9;
        }
    </style>
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
                <li class="nav-item"><a class="nav-link" href="administrador.php">Inicio</a></li>
                <li class="nav-item active"><a class="nav-link" href="#">Gestionar Productos</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Ver Ventas</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Ver Colaboradores</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Cerrar sesión</a></li>
            </ul>
        </div>
    </nav>

    <!-- Contenido principal -->
    <div class="container">
        <div class="form-container col-md-8 mx-auto">
            <h3 class="text-center mb-4">Registrar Nuevo Producto</h3>
            <form action="../logic/registroProductoLogic.php" method="POST" enctype="multipart/form-data">
    <div class="form-group">
        <label for="nombre" class="form-label">Nombre del Producto</label>
        <input type="text" class="form-control" id="nombre" name="nombre" required>
    </div>

    <div class="form-group">
        <label for="descripcion" class="form-label">Descripción</label>
        <textarea class="form-control" id="descripcion" name="descripcion" rows="4"></textarea>
    </div>

    <div class="form-group">
        <label for="precio" class="form-label">Precio</label>
        <input type="number" class="form-control" id="precio" name="precio" step="0.01" required>
    </div>

    <div class="form-group">
        <label for="comisionable" class="form-label">¿Es Comisionable?</label>
        <select class="form-control" id="comisionable" name="comisionable">
            <option value="S">Sí</option>
            <option value="N">No</option>
        </select>
    </div>

    <div class="form-group">
        <label for="foto" class="form-label">Foto del Producto</label>
        <input type="file" class="form-control" id="foto" name="foto" required>
    </div>

    <div class="form-group">
        <label for="estado" class="form-label">Estado</label>
        <select class="form-control" id="estado" name="estado">
            <option value="A">Activo</option>
            <option value="I">Inactivo</option>
        </select>
    </div>

    <button type="submit" class="btn btn-success btn-block">Registrar Producto</button>
</form>

        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
