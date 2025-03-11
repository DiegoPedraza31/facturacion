<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    die("Acceso denegado. Inicia sesión primero.");
}

$id_usuario = $_SESSION['id_usuario']; // Obtener ID del usuario autenticado
?>

<h1>Bienvenido, <?php echo $_SESSION["id_usuario"]; ?>!</h1>

<a href="facturacion.php">Facturar</a></br>
<a href="consultar_facturas.php">Consultar Facturas</a></br>




<a href="logout.php">Cerrar Sesión</a>
