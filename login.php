<?php
session_start();
$conexion = new mysqli("localhost", "root", "", "facturacion_db");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}
// Configurar la codificación UTF-8
$conexion->set_charset("utf8");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $contraseña = $_POST["contraseña"];

    // Buscar el usuario en la base de datos
    $sql = "SELECT id, contraseña FROM usuarios WHERE email = '$email'";
    $resultado = $conexion->query($sql);

    if ($resultado->num_rows == 1) {
        $usuario = $resultado->fetch_assoc();
        
        if (password_verify($contraseña, $usuario['contraseña'])) { // 🔒 Comparar contraseñas
            $_SESSION['id_usuario'] = $usuario['id']; // Guardar ID del usuario
            header("Location: dashboard.php");
            exit();
        } else {
            echo "<p style='color:red;'>Contraseña incorrecta.</p>";
        }
    } else {
        echo "<p style='color:red;'>Usuario no encontrado.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
</head>
<body>
    <h2>Iniciar Sesión</h2>
    <form method="POST" action="">
        <label>Email:</label>
        <input type="email" name="email" required><br><br>

        <label>Contraseña:</label>
        <input type="password" name="contraseña" required><br><br>

        <input type="submit" value="Ingresar">
    </form>
</body>
</html>
