<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>
    <h2>Registro de Usuario</h2>
    <form action="registro.php" method="POST">
        <label>Nombre Completo:</label>
        <input type="text" name="nombre" required><br>

        <label>Correo Electrónico:</label>
        <input type="email" name="correo" required><br>

        <label>Contraseña:</label>
        <input type="password" name="contraseña" required><br>

        <label>Confirmar Contraseña:</label>
        <input type="password" name="confirmar_contraseña" required><br>

        <label>País:</label>
        <input type="text" name="pais" required><br>

        <label>Ciudad:</label>
        <input type="text" name="ciudad" required><br>

        <label>Genero:</label>
        <input type="text" name="genero" required><br>


        <!-- reCAPTCHA -->
        <div class="g-recaptcha" data-sitekey="6LdqFe8qAAAAAD35z8zf6uNxFQjyuD6aoWyJ7AsS
      "></div><br>

        <button type="submit">Registrarse</button>
    </form>
</body>
</html>
