<?php
require 'config.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Verificar si el token es válido y no ha expirado
    $stmt = $conn->prepare("SELECT id FROM usuarios WHERE token_recuperacion = ? AND expiracion_token > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $nueva_contraseña = $_POST['nueva_contraseña'];
            $confirmar_contraseña = $_POST['confirmar_contraseña'];

            if ($nueva_contraseña !== $confirmar_contraseña) {
                die("⚠️ Las contraseñas no coinciden.");
            }

            if (strlen($nueva_contraseña) < 8 || !preg_match("/[A-Z]/", $nueva_contraseña) || 
                !preg_match("/\d/", $nueva_contraseña) || !preg_match("/[\W]/", $nueva_contraseña)) {
                die("⚠️ La contraseña debe tener al menos 8 caracteres, una mayúscula, un número y un carácter especial.");
            }

            // Encriptar la nueva contraseña
            $nueva_contraseña_hash = password_hash($nueva_contraseña, PASSWORD_BCRYPT);

            // Actualizar la contraseña y limpiar el token
            $stmt = $conn->prepare("UPDATE usuarios SET contraseña = ?, token_recuperacion = NULL, expiracion_token = NULL WHERE token_recuperacion = ?");
            $stmt->bind_param("ss", $nueva_contraseña_hash, $token);
            $stmt->execute();

            echo "✅ Contraseña restablecida con éxito. Ahora puedes <a href='log.php'>iniciar sesión</a>.";
            exit();
        }
    } else {
        echo "⚠️ Token inválido o expirado.";
        exit();
    }
} else {
    echo "⚠️ Token no proporcionado.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer Contraseña</title>
</head>
<body>
    <h2>Restablecer Contraseña</h2>
    <form action="" method="POST">
        <label for="nueva_contraseña">Nueva Contraseña:</label>
        <input type="password" name="nueva_contraseña" required>
        <br>
        <label for="confirmar_contraseña">Confirmar Contraseña:</label>
        <input type="password" name="confirmar_contraseña" required>
        <br>
        <button type="submit">Cambiar Contraseña</button>
    </form>
</body>
</html>
