<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h2>Iniciar Sesión</h2>
    <form action="login.php" method="POST">
        <label for="correo">Correo Electrónico:</label>
        <input type="email" name="correo" id="correo" required>
        <br>
        <label for="contraseña">Contraseña:</label>
        <input type="password" name="contraseña" id="contraseña" required>
        <br>
        <button type="submit">Ingresar</button>
    </form>
    
    <p>¿Olvidaste tu contraseña? <a href="recuperar.php">Recupérala aquí</a></p>
</body>
</html>

