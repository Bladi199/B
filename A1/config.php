<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "bicentenario";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Configuración de correo (SMTP)
define("SMTP_HOST", "smtp.gmail.com");  // Cambia esto por tu servidor SMTP
define("SMTP_USER", "bladimamanicasas@gmail.com"); // Tu correo
define("SMTP_PASS", 'xngv kdkw yhot dnos'); // Contraseña del correo
define("SMTP_PORT", 587); // Puede ser 465 (SSL) o 587 (TLS)
?>
