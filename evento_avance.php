<?php
require 'conexion.php';

session_start(); // Iniciar sesión al principio de la página

// Verificar si el usuario ya inició sesión
if (!isset($_SESSION['id_usuario'])) {
    // Si no hay una sesión activa, redirigir al formulario de inicio de sesión
    header('Location: login.php');
    exit; // Detener la ejecución del script
}

// Inicializar mensaje de error
$mensaje_error = '';

// Verificar si se proporcionó el ID del evento en la URL
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id_evento'])) {
    try {
        $id_evento = $_GET['id_evento'];

        // Consultar los detalles del evento y los datos del denunciante asociado
        $stmt = $conexion->prepare("SELECT eventos.*, denunciantes.*, usuarios.nombre AS nombre_agente, usuarios.agente FROM eventos INNER JOIN denunciantes ON eventos.id_evento = denunciantes.id_evento INNER JOIN usuarios ON eventos.id_usuario = usuarios.id_usuario WHERE eventos.id_evento = ?");
        $stmt->execute([$id_evento]);
        $detalle_evento = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($detalle_evento) {
            // Mostrar los detalles del evento y los datos del denunciante
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Evento y Denunciante</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        h1 {
            color: #333;
        }
        p {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Detalles del Evento y Denunciante</h1>
    <h2>Detalles del Evento</h2>
    <p><strong>ID de Evento:</strong> <?php echo $detalle_evento['id_evento']; ?></p>
    <p><strong>Tipo:</strong> <?php echo $detalle_evento['tipo']; ?></p>
    <p><strong>Subtipo:</strong> <?php echo $detalle_evento['subtipo']; ?></p>
    <p><strong>Descripción:</strong> <?php echo $detalle_evento['descripcion']; ?></p>
    <p><strong>Fecha y Hora:</strong> <?php echo $detalle_evento['fecha_hora']; ?></p>
    <p><strong>Nombre del Agente:</strong> <?php echo $detalle_evento['nombre_agente']; ?></p>
    <p><strong>Agente:</strong> <?php echo $detalle_evento['agente']; ?></p>
    <p><strong>Estado:</strong> <?php echo $detalle_evento['estado']; ?></p>
    <p><strong>Avance:</strong> <?php echo $detalle_evento['avance']; ?></p>
    <h2>Detalles del Denunciante</h2>
    <p><strong>Nombre:</strong> <?php echo $detalle_evento['nombre']; ?></p>
    <p><strong>Apellido:</strong> <?php echo $detalle_evento['apellido']; ?></p>
    <p><strong>Tipo de Documento:</strong> <?php echo $detalle_evento['tipo_documento']; ?></p>
    <p><strong>Documento:</strong> <?php echo $detalle_evento['documento']; ?></p>
    <p><strong>Teléfono:</strong> <?php echo $detalle_evento['tel']; ?></p>
</div>

</body>
</html>
<?php
        } else {
            echo "<p>El evento no existe o no se encontró.</p>";
        }
    } catch(PDOException $e) {
        echo "<p>Error al procesar la solicitud: " . $e->getMessage() . "</p>";
    }
}
?>
