<?php
session_start();

// Verificar si el usuario está autenticado
//if (!isset($_SESSION["usuario_id"])) {
    // Si no está autenticado, redirigir al login
 //   header("Location: login.php");
 //   exit();
//}

// Obtener información del usuario
//$usuario_id = $_SESSION["usuario_id"];
//$correo = $_SESSION["correo"];

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>
<body>
    <h1>Bienvenido al Dashboard</h1>
    <!--<p>Hola, <strong><?php echo $correo; ?></strong>!</p> -->
    <p>Este es tu panel de usuario donde puedes gestionar tus configuraciones.</p>

    <!-- Aquí puedes agregar más secciones de gestión, como editar perfil, cambiar contraseña, etc. -->
    <a href="editar_perfil.php">Editar perfil</a> | 
    <a href="cambiar_contraseña.php">Cambiar contraseña</a> | 
    <a href="logout.php">Cerrar sesión</a>
</body>
</html>
