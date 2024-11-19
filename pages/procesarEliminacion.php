<?php
session_start();
if (!isset($_SESSION['id_persona']) || $_SESSION['rol'] != 3) {
    header("Location: ../index.php");
    exit();
}
include_once("../includes/db_connection.php");

$idRevista = $_POST['idRevista'];
$idProducto = $_POST['idProducto'];

// Llamar al procedimiento para eliminar la asociación
$query = "BEGIN eliminar_producto_revista(:id_revista, :id_producto); END;";
$stid = oci_parse($conexion, $query);
oci_bind_by_name($stid, ":id_revista", $idRevista);
oci_bind_by_name($stid, ":id_producto", $idProducto);

// Ejecutar el procedimiento
if (oci_execute($stid)) {
    $_SESSION['mensaje'] = "Producto eliminado correctamente.";
} else {
    $_SESSION['mensaje'] = "Error al eliminar el producto.";
}
oci_free_statement($stid);

header("Location: asociarProductosRevista.php?id=" . $idRevista);
exit();
?>
