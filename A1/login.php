<?php
// Habilitar la visualización de errores para depuración
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Verificar si config.php existe y cargar la configuración
if (!file_exists('config.php')) {
    die("Error: config.php no encontrado.");
}
require 'config.php';

session_start();

// Verificar la conexión a la base de datos
if (!$conn) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Comprobar si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener y limpiar los datos del formulario
    $correo = trim($_POST["correo"]);
    $contraseña = $_POST["contraseña"];

    // Validar formato de correo
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        die("Error: Correo electrónico inválido.");
    }

    // Verificar si la consulta se prepara correctamente
    if (!$stmt = $conn->prepare("SELECT id, contraseña, verificado FROM usuarios WHERE correo = ?")) {
        die("Error en la preparación de la consulta: " . $conn->error);
    }

    // Asignar parámetros y ejecutar consulta
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $stmt->store_result();

    // Si el usuario existe en la base de datos
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $hash, $verificado);
        $stmt->fetch();

        // Verificar si la cuenta está activada
        if (!$verificado) {
            die("Error: Tu cuenta no está verificada. Revisa tu correo.");
        }

        // Verificar la contraseña
        if (password_verify($contraseña, $hash)) {
            // Iniciar sesión
            $_SESSION["usuario_id"] = $id;
            $_SESSION["correo"] = $correo;

            // Redirigir al panel de usuario
            header("Location: dashboard.php");
            exit();
        } else {
            echo "Error: Contraseña incorrecta.";
        }
    } else {
        echo "Error: Correo no registrado.";
    }

    // Cerrar la consulta
    $stmt->close();
}

// Cerrar conexión a la base de datos
$conn->close();
?>
