<?php

session_start();

if (!isset($_SESSION['id_usuario'])) {
    die("Acceso denegado. Inicia sesión primero.");
}

$id_usuario = $_SESSION['id_usuario']; // Obtener ID del usuario autenticado

// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "facturacion_db");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Configurar la codificación UTF-8
$conexion->set_charset("utf8");

// Procesar el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $cedula = $_POST["cedula"];
    $fecha = $_POST["fecha"];
    $fecha_entrega = $_POST["fecha_entrega"];
    $telefono = $_POST["telefono"];
    $empresa = $_POST["empresa"];
    $direccion = $_POST["direccion"];
    $barrio = $_POST["barrio"];
    $referencia = $_POST["referencia"];
    $valor = $_POST["valor"];
    $num_cuotas = $_POST["num_cuotas"];
    $observaciones = $_POST["observaciones"];

    $sql = "INSERT INTO facturacion (id_usuario,nombre,apellido, cedula, fecha,fecha_entrega, telefono, empresa, direccion, barrio, referencia, valor, num_cuotas, observaciones) 
            VALUES ('$id_usuario','$nombre','$apellido','$cedula', '$fecha','$fecha_entrega', '$telefono', '$empresa', '$direccion', '$barrio', '$referencia', '$valor', '$num_cuotas', '$observaciones')";

    if ($conexion->query($sql) === TRUE) {
        header("Location: dashboard.php"); 
        echo "<p style='color:green;'>Factura guardada correctamente.</p>";
        exit();
    } else {
        echo "<p style='color:red;'>Error: " . $conexion->error . "</p>";
    }
}

$conexion->close();


?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Facturación</title>
</head>
<body>
    <h2>Formulario de Facturación</h2>
    <form method="POST" action="">
        <label>Nombre:</label>
        <input type="text" name="nombre" required><br><br>

        <label>Apellido:</label>
        <input type="text" name="apellido" required><br><br>

        <label>Cedula:</label>
        <input type="number" name="cedula" required><br><br>

        <label>Fecha:</label>
        <input type="date" name="fecha" required><br><br>

        <label>Fecha de entrega:</label>
        <input type="date" name="fecha_entrega" required><br><br>

        <label>Telefono:</label>
        <input type="text" name="telefono" required><br><br>

        <label>Empresa:</label>
        <input type="text" name="empresa" required><br><br>

        <label>Direccion:</label>
        <input type="text" name="direccion" required><br><br>

        <label>Barrio:</label>
        <input type="text" name="barrio" required><br><br>


        <label>Referencia:</label>
        <input type="text" name="referencia" required><br><br>

        <label>Valor:</label>
        <input type="number" name="valor" required><br><br>

        <label>Numero de cuotas:</label>
        <input type="text" name="num_cuotas" required><br><br>

        <label>Observaciones:</label>
        <textarea name="observaciones"></textarea><br><br>

        <input type="submit" value="Guardar Factura">
    </form>
    <a href="dashboard.php">Volver</a>
</body>
</html>
