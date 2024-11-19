<?php
// Incluir la conexión a la base de datos si es necesario
include '../includes/db_connection.php'; // Ajusta la ruta si es necesario


$registro = $_GET['registro'] ?? '';
$mensaje = $_GET['mensaje'] ?? '';

if ($registro === 'exitoso') {
    echo "<p>Registro exitoso. ¡Bienvenido!</p>";
} elseif ($registro === 'error') {
    echo "<p>Hubo un problema con el registro.</p>";
    if (!empty($mensaje)) {
        echo "<p>Detalles del error: " . htmlspecialchars($mensaje) . "</p>";
    }
} else {
    echo "<p>Formulario inválido.</p>";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultado del Registro</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Resultado del Registro</h2>
        <div class="alert alert-info">
            <?php echo $mensaje; ?>
        </div>
        <a href="registro.php" class="btn btn-primary">Volver al formulario</a>
    </div>
</body>
</html>
