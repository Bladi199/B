<?php
require 'config.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';
require 'PHPMailer-master/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = trim($_POST["correo"]);

    // Verificar si el correo existe
    $stmt = $conn->prepare("SELECT id FROM usuarios WHERE correo = ?");
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Generar token de recuperación
        $token = bin2hex(random_bytes(16));
        $expiracion = date("Y-m-d H:i:s", strtotime("+15 minutes"));

        // Guardar el token en la BD
        $stmt = $conn->prepare("UPDATE usuarios SET token_recuperacion = ?, expiracion_token = ? WHERE correo = ?");
        $stmt->bind_param("sss", $token, $expiracion, $correo);
        $stmt->execute();

        // Enviar correo con PHPMailer
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = SMTP_HOST;
            $mail->SMTPAuth = true;
            $mail->Username = SMTP_USER;
            $mail->Password = SMTP_PASS;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = SMTP_PORT;

            $mail->SMTPAutoTLS = false;
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );

            $mail->setFrom(SMTP_USER, "Recuperación de Cuenta");
            $mail->addAddress($correo);
            $mail->isHTML(true);
            $mail->Subject = "Recuperación de Contraseña";
            $mail->Body = "Haz clic en el siguiente enlace para restablecer tu contraseña:<br>
                <a href='http://localhost:8081/A1/restablecer.php?token=$token'>
                Restablecer Contraseña</a><br>Este enlace expirará en 15 minutos.";

            if ($mail->send()) {
                echo "✅ Se ha enviado un correo con las instrucciones.";
            } else {
                echo "⚠️ Error al enviar el correo: " . $mail->ErrorInfo;
            }
        } catch (Exception $e) {
            echo "⚠️ No se pudo enviar el correo. Error: " . $mail->ErrorInfo;
        }
    } else {
        echo "⚠️ Este correo no está registrado.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña</title>
</head>
<body>
    <h2>Recuperar Contraseña</h2>
    <form action="" method="POST">
        <label for="correo">Correo Electrónico:</label>
        <input type="email" name="correo" required>
        <button type="submit">Enviar</button>
    </form>
</body>
</html>
