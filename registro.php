<?php
session_start(); // Iniciar sesión


// Mensaje de registro
$mensaje = '';

// Verificar si se enviaron datos
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require 'conexion.php'; // Incluir archivo de conexión

    // Obtener datos del formulario
    $nombre = $_POST['nombre'];
    $contraseña = password_hash($_POST['contraseña'], PASSWORD_DEFAULT);
    $agente = isset($_POST['agente']) ? $_POST['agente'] : null;

    // Verificar si el agente es "administrador"
    if ($agente == "administrador") {
        try {
            // Consulta para verificar si hay algún usuario con el rol de "administrador"
            $consulta_existencia = $conexion->prepare("SELECT COUNT(*) as count FROM usuarios WHERE agente = 'administrador'");
            $consulta_existencia->execute();
            $resultado = $consulta_existencia->fetch(PDO::FETCH_ASSOC);

            // Si existe al menos un usuario con el rol de "administrador", se permite el registro
            if ($resultado['count'] > 0) {
                // Consulta preparada para evitar inyección SQL
                $consulta_insertar = $conexion->prepare("INSERT INTO usuarios (nombre, contraseña, agente) VALUES (:nombre, :contraseña, :agente)");
                $consulta_insertar->bindParam(':nombre', $nombre);
                $consulta_insertar->bindParam(':contraseña', $contraseña);
                $consulta_insertar->bindParam(':agente', $agente, PDO::PARAM_STR);

                if ($consulta_insertar->execute()) {
                    $mensaje = 'Registro exitoso';
                } else {
                    $mensaje = 'Error al registrar usuario';
                }
            } else {
                $mensaje = 'No se puede registrar un usuario como "administrador" porque no hay ningún usuario con ese rol existente.';
            }
        } catch (PDOException $e) {
            $mensaje = "Error al ejecutar la consulta: " . $e->getMessage();
        }
    } else {
        $mensaje = 'Lo siento, solo los usuarios con el rol de "administrador" pueden registrarse.';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <link rel="shortcut icon" href="css/img/logo-removebg-preview.png" type="image/x-icon">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #002D62; /* Color de fondo */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .registro-container {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.3);
            text-align: center;
        }
        .registro-container img {
            max-width: 150px;
            margin-bottom: 20px;
        }
        .registro-container input[type="text"],
        .registro-container input[type="password"] {
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .registro-container input[type="submit"] {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: #002D62; /* Color de fondo del botón */
            color: white;
            cursor: pointer;
        }
        .registro-container input[type="submit"]:hover {
            background-color: #003D82; /* Color de fondo del botón al pasar el cursor */
        }
    </style>
</head>
<body>
    <div class="registro-container">
        <img src="css/img/logo.jpg" alt="Logo">
        <h2>Registro</h2>
        <?php if (!empty($mensaje)) : ?>
            <p><?php echo $mensaje; ?></p>
        <?php endif; ?>
        <form action="registro.php" method="post">
            <label for="nombre">Nombre de usuario:</label>
            <input type="text" id="nombre" name="nombre" required><br><br>
            <label for="contraseña">Contraseña:</label>
            <input type="password" id="contraseña" name="contraseña" required><br><br>
            <label for="agente">Agente:</label>
            <input type="text" id="agente" name="agente"><br><br> <!-- Nuevo campo para el agente -->
            <input type="submit" value="Registrarse">
        </form>
       
    </div>
</body>
</html>
