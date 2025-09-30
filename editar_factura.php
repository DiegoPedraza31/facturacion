<?php
session_start();


if (!isset($_SESSION['id_usuario'])) {
    die("Acceso denegado. <a href='login.php'>Inicia sesión aquí</a>");
}

$conexion = new mysqli("localhost", "root", "", "facturacion_db");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Configurar la codificación UTF-8
$conexion->set_charset("utf8");

$id_usuario = $_SESSION['id_usuario'];
$id_factura = $_GET['id'] ?? null;

// Verificar que la factura pertenece al usuario autenticado
$sql = "SELECT * FROM facturacion WHERE id = ? AND id_usuario = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("ii", $id_factura, $id_usuario);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows == 0) {
    die("Factura no encontrada o no tienes permiso para editarla.");
}

$factura = $resultado->fetch_assoc();

// Procesar actualización
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

    $sql_update = "UPDATE facturacion 
                   SET nombre=?,apellido=?,cedula=?, fecha=?,fecha_entrega=?, telefono=?, empresa=?, direccion=?, barrio=?, referencia=?, valor=?, num_cuotas=?, observaciones=?
                   WHERE id=? AND id_usuario=?";
    $stmt_update = $conexion->prepare($sql_update);
    $stmt_update->bind_param("sssssssssisssii", $nombre, $apellido, $cedula, $fecha,$fecha_entrega, $telefono, $empresa, $direccion, $barrio, $referencia, $valor, $num_cuotas, $observaciones, $id_factura, $id_usuario);

    
    if ($stmt_update->execute()) {
        echo "<p style='color:green;'>Factura actualizada correctamente.</p>";
        header("Location: dashboard.php"); // Redirigir tras editar
        exit();
    } else {
        echo "<p style='color:red;'>Error al actualizar: " . $conexion->error . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Factura</title>
</head>
<body>
    <h2>Editar Factura</h2>
    <form method="POST" action="">
        <label>Nombre:</label>
        <input type="text" name="nombre" value="<?php echo $factura['nombre']; ?>" required><br><br>

        <label>Apellido:</label>
        <input type="text" name="apellido" value="<?php echo $factura['apellido']; ?>" required><br><br>

        <label>Cédula:</label>
        <input type="number" name="cedula" value="<?php echo $factura['cedula']; ?>" required><br><br>

        <!-- Campo Fecha (solo visible pero NO editable) -->
        <label>Fecha:</label>
        <input type="date" value="<?php echo date('Y-m-d', strtotime($factura['fecha'])); ?>" disabled><br><br>

        <!-- Mantener valor en hidden para que se envíe en el POST -->
        <input type="hidden" name="fecha" value="<?php echo date('Y-m-d', strtotime($factura['fecha'])); ?>">


        <label>Fecha de entrega:</label>
        <input type="date" name="fecha_entrega" value="<?php echo date('Y-m-d', strtotime($factura['fecha_entrega'])); ?>" required><br><br>

        <label>Teléfono:</label>
        <input type="text" name="telefono" value="<?php echo $factura['telefono']; ?>" required><br><br>

        <label>Empresa:</label>
        <input type="text" name="empresa" value="<?php echo $factura['empresa']; ?>" required><br><br>

        <label>Dirección:</label>
        <input type="text" name="direccion" value="<?php echo $factura['direccion']; ?>" required><br><br>

        <label>Barrio:</label>
        <input type="text" name="barrio" value="<?php echo $factura['barrio']; ?>" required><br><br>

        <label>Referencia:</label>
        <input type="text" name="referencia" value="<?php echo $factura['referencia']; ?>" required><br><br>

        <label>Valor:</label>
        <input type="number" name="valor" value="<?php echo $factura['valor']; ?>" required><br><br>

        <label>Número de cuotas:</label>
        <input type="text" name="num_cuotas" value="<?php echo $factura['num_cuotas']; ?>" required><br><br>

        <label>Observaciones:</label>
        <textarea name="observaciones"><?php echo $factura['observaciones']; ?></textarea><br><br>

        <input type="submit" value="Actualizar Factura">
    </form>

    <br>

    <a href="dashboard.php">Volver a Mis Facturas</a>
</body>
</html>

<?php
$conexion->close();
?>
