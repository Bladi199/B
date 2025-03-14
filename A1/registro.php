<?php
require 'config.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';
require 'PHPMailer-master/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = trim($_POST["nombre"]);
    $correo = trim($_POST["correo"]);
    $contraseña = $_POST["contraseña"];
    $confirmar_contraseña = $_POST["confirmar_contraseña"];
    $pais = trim($_POST["pais"]);
    $ciudad = trim($_POST["ciudad"]);

    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        die("Correo electrónico inválido.");
    }

    if (strlen($contraseña) < 8 || !preg_match("/[A-Z]/", $contraseña) || 
        !preg_match("/\d/", $contraseña) || !preg_match("/[\W]/", $contraseña)) {
        die("La contraseña debe tener al menos 8 caracteres, una mayúscula, un número y un carácter especial.");
    }

    if ($contraseña !== $confirmar_contraseña) {
        die("Las contraseñas no coinciden.");
    }

    // Verificar si el correo ya está registrado
    $stmt = $conn->prepare("SELECT id FROM usuarios WHERE correo = ?");
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        die("⚠️ Error: Este correo ya está registrado. Intenta con otro.");
    }
    $stmt->close();

    // Generar hash de contraseña y código de verificación
    $contraseña_hash = password_hash($contraseña, PASSWORD_BCRYPT);
    $codigo_verificacion = bin2hex(random_bytes(16));

    // Insertar nuevo usuario
    $stmt = $conn->prepare("INSERT INTO usuarios (nombre, correo, contraseña, pais, ciudad, codigo_verificacion) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $nombre, $correo, $contraseña_hash, $pais, $ciudad, $codigo_verificacion);

    if ($stmt->execute()) {
        // Enviar correo de verificación
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = SMTP_HOST;
            $mail->SMTPAuth = true;
            $mail->Username = SMTP_USER;
            $mail->Password = SMTP_PASS;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = SMTP_PORT;

            // Solución de errores SSL
            $mail->SMTPAutoTLS = false;
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );

            $mail->setFrom(SMTP_USER, "Bicentenario");
            $mail->addAddress($correo, $nombre);
            $mail->isHTML(true);
            $mail->Subject = "Verifica tu cuenta";
            $mail->Body = "Hola $nombre,<br>Por favor verifica tu cuenta haciendo clic en el siguiente enlace:<br>
                <a href='http://localhost:8081/A1/verificar.php?correo=$correo&codigo=$codigo_verificacion'>
                Verificar Cuenta</a>";
            
            if ($mail->send()) {
                echo "✅ Registro exitoso. Revisa tu correo para verificar tu cuenta.";
            } else {
                echo "⚠️ Error al enviar el correo: " . $mail->ErrorInfo;
            }
        } catch (Exception $e) {
            echo "⚠️ No se pudo enviar el correo de verificación. Error: " . $mail->ErrorInfo;
        }
    } else {
        echo "❌ Error en el registro: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
