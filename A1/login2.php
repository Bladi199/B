<?php
require 'config.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = trim($_POST["correo"]);
    $contraseña = $_POST["contraseña"];

    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        die("Correo electrónico inválido.");
    }

    $stmt = $conn->prepare("SELECT id, contraseña, verificado FROM usuarios WHERE correo = ?");
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $hash, $verificado);
        $stmt->fetch();

        if (!$verificado) {
            die("Tu cuenta no está verificada. Revisa tu correo.");
        }

        if (password_verify($contraseña, $hash)) {
            $_SESSION["usuario_id"] = $id;
            $_SESSION["correo"] = $correo;

            // Redirigir al usuario a la página de MFA (si está habilitada)
            header("Location: mfa.php");
            exit();
        } else {
            echo "Contraseña incorrecta.";
        }
    } else {
        echo "Correo no registrado.";
    }

    $stmt->close();
}
?>
