<?php
require 'conexion.php';

session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit;
}

// Obtener el ID de usuario de la sesión
$id_usuario = $_SESSION['id_usuario'];

// Verificar si se enviaron datos por el método POST para agregar una nueva nota
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['titulo'], $_POST['nota'])) {
    // Obtener los datos del formulario y limpiarlos
    $titulo = limpiarDatos($_POST['titulo']);
    $nota = limpiarDatos($_POST['nota']);

    try {
        // Preparar la consulta para insertar la nueva nota en la tabla "Notas"
        $stmt = $conexion->prepare("INSERT INTO Notas (id_usuario, titulo, nota) VALUES (?, ?, ?)");
        // Ejecutar la consulta, pasando los datos del usuario y la nota
        $stmt->execute([$id_usuario, $titulo, $nota]);

        // Redirigir a la misma página para actualizar la lista de notas
        header('Location: notas.php');
        exit;
    } catch (PDOException $e) {
        // Si ocurre un error, mostrar un mensaje de error
        echo "Error al guardar la nota: " . $e->getMessage();
    }
}

// Verificar si se envió una solicitud de eliminación de nota por GET
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['eliminar']) && is_numeric($_GET['eliminar'])) {
    $id_nota = $_GET['eliminar'];

    try {
        // Preparar la consulta para eliminar la nota de la tabla "Notas"
        $stmt = $conexion->prepare("DELETE FROM Notas WHERE id_nota = ? AND id_usuario = ?");
        // Ejecutar la consulta, pasando el ID de la nota y el ID de usuario como parámetros
        $stmt->execute([$id_nota, $id_usuario]);

        // Redirigir a la misma página después de eliminar la nota
        header('Location: notas.php');
        exit;
    } catch (PDOException $e) {
        // Si ocurre un error, mostrar un mensaje de error
        echo "Error al eliminar la nota: " . $e->getMessage();
    }
}

// Función para limpiar datos de entrada
function limpiarDatos($dato) {
    $dato = trim($dato);
    $dato = stripslashes($dato);
    $dato = htmlspecialchars($dato);
    return $dato;
}

// Obtener y mostrar las notas del usuario actual
try {
    // Preparar la consulta para seleccionar todas las notas del usuario actual
    $stmt = $conexion->prepare("SELECT * FROM Notas WHERE id_usuario = ?");
    // Ejecutar la consulta, pasando el ID de usuario como parámetro
    $stmt->execute([$id_usuario]);
    // Obtener todas las notas como un array asociativo
    $notas = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Si ocurre un error al ejecutar la consulta, mostrar un mensaje de error
    echo "Error al obtener las notas: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notas</title>
    <link rel="shortcut icon" href="css\img\logo-removebg-preview.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .cont {
            max-width: 800px;
            width: 100%;

            margin:0 auto;
                background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .title {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"],
        textarea {
            width: 100%;
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }

        textarea {
            resize: vertical;
        }

        button {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            color: #fff;
            background-color: #007bff;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #0056b3;
        }

        .notes-container {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #ccc;
        }

        .note-item {
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid #ccc;
        }

        .note-title {
            color: #333;
            margin-top: 0;
        }

        .note-content {
            color: #666;
        }

        .delete-btn {
            background-color: #dc3545;
            color: #fff;
        }

        .delete-btn:hover {
            background-color: #c82333;
        }
        .container {
    display: flex;
    min-height: 100vh; /* Cambiado de 'height' a 'min-height' */
    width: 100%;
}


.sidebar {
    width: 170px;
    position: sticky;
    background-color: #002D62;
    color: #fff;
    padding: 20px;
}

.content {
    flex: 1;
    padding: 20px;
}
    .sidebar h2 {
        margin-bottom: 20px;
    }

    .sidebar ul {
        list-style-type: none;
        padding: 0;
    }

    .sidebar ul li {
        margin-bottom:30px;
    }

    .sidebar ul li a {
        color: #fff;
        text-decoration: none;
    }

    .contenedores{
        display:flex;
        margin-left:60px;
      
    }



    @media screen and (max-width: 768px) {
        body, html {
    margin: 0;
    padding: 0;
  
}

.container {
    display: flex;
    flex-direction: column;
}

.sidebar {
    width: 100%; /* Ocupa todo el ancho en dispositivos móviles */
}

.content {
    flex: 1;
    padding: 20px;
}
.cont {
        width: 50%; /* Cambia el ancho a tu preferencia */
        margin: 0 auto; /* Centra el contenido horizontalmente */
    positon:fixed;

    }

    }
    </style>
</head>
<body>
<div class="container">
        <div class="sidebar">
            <h2>Dashboard</h2>
            <hr>
            <ul> <br>
            <li><a href="dashordb.php">Inicio</a></li>
                <li><a href="evento.html">Crear Evento</a></li>
                <li><a href="registro.php">Registrar Usuarios</a></li>
                <li><a href="buscar.php">Buscar Evento</a></li>
                <li><a href="eventos_avance2.php">Evento Avance</a></li>
                <li><a href="repositorio.php">Repositorio</a></li>
                
                <li><a href="admin.php">Administrar Recursos</a></li>
            </ul>
        </div>
        <div class="content">
<div class="cont">
    <h1 class="title">Notas</h1>

    <!-- Formulario para agregar una nueva nota -->
    <form action="notas.php" method="POST">
        <div class="form-group">
            <label for="titulo">Título:</label>
            <input type="text" id="titulo" name="titulo" required>
        </div>
        <div class="form-group">
            <label for="nota">Nota:</label>
            <textarea id="nota" name="nota" rows="4" required></textarea>
        </div>
        <button type="submit">Guardar Nota</button>
    </form>

    <!-- Mostrar las notas existentes -->
    <div class="notes-container">
        <?php if (isset($notas) && count($notas) > 0): ?>
            <?php foreach ($notas as $nota): ?>
                <div class="note-item">
                    <h3 class="note-title"><?php echo $nota['titulo']; ?></h3>
                    <p class="note-content"><?php echo $nota['nota']; ?></p>
                    <!-- Formulario para eliminar una nota -->
                    <form action="notas.php" method="GET">
                        <input type="hidden" name="eliminar" value="<?php echo $nota['id_nota']; ?>">
                        <button type="submit" class="delete-btn">Eliminar</button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No hay notas para mostrar.</p>
        <?php endif; ?>
    </div>
</div>
        </div>
        </div>
</body>
</html>
