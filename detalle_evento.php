<?php
require 'conexion.php';

// Función para limpiar datos de entrada
function limpiarDatos($dato) {
    $dato = trim($dato);
    $dato = stripslashes($dato);
    $dato = htmlspecialchars($dato);
    return $dato;
}

// Verificar si se envió el formulario y procesar los datos
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_evento'])) {
    $id_evento = limpiarDatos($_POST['id_evento']);
    $tipo = limpiarDatos($_POST['tipo']);
    $subtipo = limpiarDatos($_POST['subtipo']);
    $descripcion = limpiarDatos($_POST['descripcion']);
    $estado = limpiarDatos($_POST['estado']);
    $observaciones = limpiarDatos($_POST['observaciones']);
    $avance = limpiarDatos($_POST['avance']); // Nuevo campo de avance

    // Verificar si el usuario ha iniciado sesión
    session_start();
    if (isset($_SESSION['id_usuario'])) {
        $id_usuario = $_SESSION['id_usuario'];
    } else {
        // Redirigir al usuario al inicio de sesión si no ha iniciado sesión
        header("Location: login.php");
        exit;
    }

    try {
        // Establecer la zona horaria a Buenos Aires, Argentina
        date_default_timezone_set('America/Argentina/Buenos_Aires');
        // Obtener la fecha y hora actual
        $fecha_hora = date('Y-m-d H:i:s');
        
        // Insertar los datos en la tabla editar
        $stmt2 = $conexion->prepare("INSERT INTO editar (id_evento, id_usuario, tipo, subtipo, descripcion, estado, fecha_hora, observaciones, avance) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt2->execute([$id_evento, $id_usuario, $tipo, $subtipo, $descripcion, $estado, $fecha_hora, $observaciones, $avance]);

        echo "<p>Los cambios se han guardado exitosamente.</p>";
    } catch(PDOException $e) {
        echo "<p>Error al guardar los cambios: " . $e->getMessage() . "</p>";
    }
}

// Obtener y mostrar los detalles del evento si el ID del evento está presente en la URL
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id_evento'])) {
    try {
        $id_evento = $_GET['id_evento'];
        $stmt = $conexion->prepare("SELECT * FROM eventos WHERE id_evento = ?");
        $stmt->execute([$id_evento]);
        $detalle_evento = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($detalle_evento) {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Evento</title>
    <style>
    body {
    font-family: Arial, sans-serif;
    margin: 0;
    
    padding: 20px;
}

form {
    max-width: 600px;
    margin: 0 auto;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    padding: 20px;
}

h1 {
    color: #333;
    text-align: center;
}

label {
    display: block;
    margin-bottom: 5px;
}

input[type="text"],
textarea {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-sizing: border-box;
}

input[type="submit"] {
    width: 100%;
    padding: 10px;
    background-color: #002D62;
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
}

input[type="submit"]:hover {
    background-color: #0056b3;
}
/* Estilos para el enlace "Volver" */
a.back-link {
    display: inline-block;
    padding: 10px 20px;
    
    background-color: #002D62;
    color: #fff;
    text-decoration: none;
    border-radius: 5px;
    transition: background-color 0.3s;
}

a.back-link:hover {
    background-color: #0056b3;
}


</style>
<link rel="shortcut icon" href="css\img\logo-removebg-preview.png" type="image/x-icon">
</head>
<body>
<h1>Modificar Evento</h1>
<center>
            <a href="eventos_avance2.php" class="back-link">Volver</a>
            </center>
            <br>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <input type="hidden" name="id_evento" value="<?php echo $detalle_evento['id_evento']; ?>">
                <label for="tipo">Tipo:</label><br>
                <input type="text" id="tipo" name="tipo" value="<?php echo $detalle_evento['tipo']; ?>"><br>
                <label for="subtipo">Subtipo:</label><br>
                <input type="text" id="subtipo" name="subtipo" value="<?php echo $detalle_evento['subtipo']; ?>"><br>
                <label for="descripcion">Descripción:</label><br>
                <textarea id="descripcion" name="descripcion"><?php echo $detalle_evento['descripcion']; ?></textarea><br>
                <label for="estado">Estado:</label><br>
                <input type="text" id="estado" name="estado" value="<?php echo $detalle_evento['estado']; ?>"><br>
                <label for="avance">Avance:</label><br> <!-- Nuevo campo de avance -->
                <input type="text" id="avance" name="avance" value="<?php echo $detalle_evento['avance']; ?>"><br>
                <label for="observaciones">Observaciones:</label><br>
                <textarea id="observaciones" name="observaciones"></textarea><br>
                <input type="submit" value="Guardar cambios">
            </form>
</body>
</html>

            
<?php
        } else {
            echo "<p>El evento no existe o no se encontró.</p>";
        }
    } catch(PDOException $e) {
        echo "<p>Error al procesar la solicitud: " . $e->getMessage() . "</p>";
    }
} elseif ($_SERVER["REQUEST_METHOD"] == "GET" && !isset($_GET['id_evento'])) {
    echo "<p>No se proporcionó el ID del evento.</p>";
}
?>
