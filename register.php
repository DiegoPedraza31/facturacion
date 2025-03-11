<?php
session_start();
$conexion = new mysqli("localhost", "root", "", "facturacion_db");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $apellido = $_POST["apellido"];
    $email = $_POST["email"];
    $contraseña = password_hash($_POST["contraseña"], PASSWORD_DEFAULT); // 🔒 Encriptar contraseña

    $sql = "INSERT INTO usuarios (nombre, apellido, email, contraseña) VALUES ('$nombre', '$apellido', '$email', '$contraseña')";

    if ($conexion->query($sql) === TRUE) {
        echo "<p style='color:green;'>Registro exitoso. <a href='login.php'>Inicia sesión aquí</a></p>";
    } else {
        echo "<p style='color:red;'>Error: " . $conexion->error . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
</head>
<body>
    <h2>Registro de Usuario</h2>
    <form method="POST" action="">
        <label>Nombre:</label>
        <input type="text" name="nombre" required><br><br>

        <label>Apellido:</label>
        <input type="text" name="apellido" required><br><br>

        <label>Email:</label>
        <input type="email" name="email" required><br><br>

        <label>Contraseña:</label>
        <input type="password" name="contraseña" required><br><br>

        <input type="submit" value="Registrarse">
    </form>
</body>
</html>
