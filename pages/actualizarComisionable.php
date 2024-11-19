<?php
session_start();
if (!isset($_SESSION['id_persona']) || $_SESSION['rol'] != 3) {
    header("Location: ../index.php");
    exit();
}

include_once("../includes/db_connection.php");

if (isset($_GET['id'])) {
    $idRevista = intval($_GET['id']);

    // Llamar al procedimiento para actualizar el estado de productos comisionables
    $query = "BEGIN actualizar_productos_comisionable(:id_revista, :estado); END;";
    $stid = oci_parse($conexion, $query);

    // Cambiar a 'S' o 'N' según lo que desees manejar
    $nuevoEstado = 'S';
    oci_bind_by_name($stid, ":id_revista", $idRevista);
    oci_bind_by_name($stid, ":estado", $nuevoEstado);

    // Ejecutar el procedimiento
    if (oci_execute($stid)) {
        $_SESSION['mensaje'] = "Estado de productos actualizado correctamente.";
    } else {
        $_SESSION['error'] = "Error al actualizar el estado de productos.";
    }

    oci_free_statement($stid);
}

header("Location: gestionRevistas.php");
exit();
?>
