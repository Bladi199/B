<?php
require 'config.php';

if (isset($_GET['correo']) && isset($_GET['codigo'])) {
    $correo = trim($_GET['correo']);
    $codigo = trim($_GET['codigo']);

    // Verificar si el usuario existe y aún no está verificado
    $stmt = $conn->prepare("SELECT id FROM usuarios WHERE correo = ? AND codigo_verificacion = ? AND verificado = 0");
    $stmt->bind_param("ss", $correo, $codigo);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->close(); // Cerrar el primer statement antes de ejecutar otro

        // Actualizar estado de verificación
        $stmt = $conn->prepare("UPDATE usuarios SET verificado = 1, codigo_verificacion = NULL WHERE correo = ?");
        $stmt->bind_param("s", $correo);
        
        if ($stmt->execute()) {
            echo "✅ Cuenta verificada correctamente. Ahora puedes iniciar sesión.";
        } else {
            echo "❌ Error al actualizar la verificación: " . $conn->error;
        }
    } else {
        echo "⚠️ Código de verificación inválido o la cuenta ya está verificada.";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "⚠️ Solicitud inválida.";
}
?>
