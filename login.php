<?php
session_start(); // Iniciar sesión al principio de la página

require 'conexion.php'; // Incluir el archivo de conexión a la base de datos

// Verificar si se enviaron datos por POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $contraseña = $_POST['contraseña'];

    // Consulta preparada para evitar inyección SQL
    $consulta = $conexion->prepare("SELECT * FROM usuarios WHERE nombre = :nombre");
    $consulta->bindParam(':nombre', $nombre);
    $consulta->execute();

    $usuario = $consulta->fetch(PDO::FETCH_ASSOC);

    // Verificar si se encontró el usuario y si la contraseña es correcta
    if ($usuario && password_verify($contraseña, $usuario['contraseña'])) {
        // Iniciar sesión
        $_SESSION['id_usuario'] = $usuario['id_usuario'];

        // Redirigir al dashboard
        header('Location: dashordb.php');
        exit; // Detener la ejecución del script
    } else {
        // Si el usuario o la contraseña son incorrectos, mostrar un mensaje de error
        $mensaje_error = 'Nombre de usuario o contraseña incorrectos';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="shortcut icon" href="css/img/logo-removebg-preview.png" type="image/x-icon">
    <style>
      body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-image: url('css/img/fondo.jpg');
    background-size: cover; /* La imagen de fondo cubrirá todo el área del cuerpo */
    background-position: center; /* La imagen de fondo se centrará */
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}


        .login-container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            max-width: 400px;
            width: 100%;
        }

        .login-container form {
            margin-bottom: 20px;
        }

        .login-container label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        .login-container input[type="text"],
        .login-container input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .login-container input[type="submit"] {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: #002D62;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .login-container input[type="submit"]:hover {
            background-color: #001F3F;
        }

        .login-container p {
            margin-top: 10px;
            text-align: center;
        }

        .login-container a {
            color: #002D62;
            text-decoration: none;
            font-weight: bold;
        }

        .login-container a:hover {
            text-decoration: underline;
        }

        .error-message {
            color: red;
            font-weight: bold;
            margin-bottom: 10px;
            text-align: center;
        }
        h1{
            text-align: center;

        }
    </style>
</head>
<body>
    <div class="login-container">
       <h1>Gladius</h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="nombre">Nombre de usuario:</label>
            <input type="text" id="nombre" name="nombre" required>
            <label for="contraseña">Contraseña:</label>
            <input type="password" id="contraseña" name="contraseña" required>
            <input type="submit" value="Iniciar sesión">
        </form>

        <!-- Mostrar mensaje de error si existe -->
        <?php if(isset($mensaje_error)) { ?>
            <p class="error-message"><?php echo $mensaje_error; ?></p>
        <?php } ?>

        <!-- Enlace para registrarse -->
        <p>¿Te olvidaste tu contraseña? <a href="registro.html">Regístrate aquí</a></p>
    </div>
</body>
</html>
