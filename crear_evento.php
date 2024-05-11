<?php
require 'conexion.php';

session_start(); // Iniciar la sesión si no se ha iniciado todavía

// Obtener el ID de usuario de la sesión si está activa
$id_usuario = isset($_SESSION['id_usuario']) ? $_SESSION['id_usuario'] : null;

try {
    $dsn = 'sqlite:' . __DIR__ . '/gladius.db';

    // Conexión con la base de datos
    $pdo = new PDO($dsn);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Verificar si se ha enviado el formulario y todos los campos requeridos están presentes
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['guardar_datos'], $_POST['tipo'], $_POST['subtipo'], $_POST['descripcion'], $_POST['nombre'], $_POST['apellido'], $_POST['tipo_documento'], $_POST['documento'], $_POST['avance'])) {
        // Insertar datos del evento
        $tipo = $_POST['tipo'];
        $subtipo = $_POST['subtipo'];
        $descripcion = $_POST['descripcion'];
        $avance = $_POST['avance']; // Agregado el campo "avance" al formulario
        
        // Establecer el estado como "Inicio"
        $estado = "Inicio";

        // Obtener la fecha y hora actual de Argentina
        date_default_timezone_set('America/Argentina/Buenos_Aires');
        $fecha_hora = date('Y-m-d H:i:s');

        // Verificar si hay una sesión activa
        if ($id_usuario) {
            // Insertar datos del evento
            $stmt_evento = $pdo->prepare("INSERT INTO eventos (id_usuario, tipo, subtipo, descripcion, fecha_hora, estado, avance) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt_evento->execute([$id_usuario, $tipo, $subtipo, $descripcion, $fecha_hora, $estado, $avance]);
            $id_evento = $pdo->lastInsertId();

            // Insertar datos del denunciante
            $nombre = $_POST['nombre'];
            $apellido = $_POST['apellido'];
            $tipo_documento = $_POST['tipo_documento'];
            $documento = $_POST['documento'];
            $tel = isset($_POST['tel']) ? $_POST['tel'] : null;

            $stmt_denunciante = $pdo->prepare("INSERT INTO denunciantes (id_usuario, id_evento, nombre, apellido, tipo_documento, documento, tel) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt_denunciante->execute([$id_usuario, $id_evento, $nombre, $apellido, $tipo_documento, $documento, $tel]);

            // Insertar datos del domicilio si están presentes
            if (isset($_POST['calle'], $_POST['altura'])) {
                $calle = $_POST['calle'];
                $entre_calle = isset($_POST['entre_calle']) ? $_POST['entre_calle'] : null;
                $altura = $_POST['altura'];
                $piso = isset($_POST['piso']) ? $_POST['piso'] : null;
                $dto = isset($_POST['dto']) ? $_POST['dto'] : null;

                $stmt_domicilio = $pdo->prepare("INSERT INTO domicilio (id_evento, calle, entre_calle, altura, piso, dto, id_usuario) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt_domicilio->execute([$id_evento, $calle, $entre_calle, $altura, $piso, $dto, $id_usuario]);
            }
            header("Location: evento.html");
            exit; // Asegúrate de detener la ejecución del script después de enviar la cabecera de redirección            
        } else {
            // Si no hay una sesión activa, mostrar un mensaje de error
            echo "Error: Debes iniciar sesión para guardar los datos.";
        }
    }
} catch(PDOException $e) {
    echo "Error al procesar el formulario: " . $e->getMessage();
}
?>
