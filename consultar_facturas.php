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

// Obtener las facturas registradas de manera segura
$sql = "SELECT id, nombre, apellido, cedula, fecha, valor FROM facturacion WHERE id_usuario = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$resultado = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Facturas</title>
</head>
<body>
    <h2>Facturas Registradas</h2>
    <table border="1">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Nombre</th>
                <th scope="col">Apellido</th>
                <th scope="col">Cédula</th>
                <th scope="col">Fecha</th>
                <th scope="col">Valor</th>
                <th scope="col">Acción</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($resultado->num_rows > 0) {
                while ($fila = $resultado->fetch_assoc()) {
            ?>
                <tr>
                    <td><?php echo $fila['id']; ?></td>
                    <td><?php echo $fila['nombre']; ?></td>
                    <td><?php echo $fila['apellido']; ?></td>
                    <td><?php echo $fila['cedula']; ?></td>
                    <td><?php echo date('d-m-Y', strtotime($fila['fecha'])); ?></td>
                    <td><?php echo number_format($fila['valor'], 2, ',', '.'); ?></td>
                    <td>
                        <a href="editar_factura.php?id=<?php echo $fila['id']; ?>">Editar</a>
                        <a href="exportar_factura_excel.php?id=<?php echo $fila['id']; ?>">Exportar a Excel</a>
                    </td>
                </tr>
            <?php
                }
            } else {
                echo "<tr><td colspan='7'>No se encontraron facturas.</td></tr>";
            }
            ?>
        </tbody>
    </table>
    <a href="dashboard.php">Volver</a>
</body>
</html>

<?php
$conexion->close();
?>
