<?php
session_start();
if (!isset($_SESSION['id_persona']) || $_SESSION['rol'] != 3) {
    header("Location: ../index.php");
    exit();
}
include_once("../includes/db_connection.php");

$idRevista = $_POST['idRevista'];
$idProducto = $_POST['idProducto'];
$esComisionable = $_POST['esComisionable'];

// Insertar la asociación en la tabla DETALLES_REVISTA
$query = "BEGIN insertar_detalle_revista(:id_revista, :id_producto, :comisionable); END;";
$stid = oci_parse($conexion, $query);
oci_bind_by_name($stid, ":id_revista", $idRevista);
oci_bind_by_name($stid, ":id_producto", $idProducto);
oci_bind_by_name($stid, ":comisionable", $esComisionable);

if (oci_execute($stid)) {
    $_SESSION['mensaje'] = "Producto asociado correctamente.";
} else {
    $_SESSION['error'] = "Error al asociar el producto.";
}

oci_free_statement($stid);
header("Location: gestionRevistas.php");
?>
