<?php
// Incluir el archivo de conexión a la base de datos
require 'conexion.php';

// Verificar la autenticación del usuario (comprobar si está autenticado)
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit;
}

// Procesar el formulario de carga de archivos
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['form_submitted'])) {
    $nombre_archivo = $_FILES['archivo']['name'];
    $archivo = file_get_contents($_FILES['archivo']['tmp_name']); // Leer el archivo como datos binarios
    $tamaño_archivo = $_FILES['archivo']['size'];
    $tipo_archivo = $_FILES['archivo']['type'];
    $fecha_subida = date('Y-m-d H:i:s');
    
    try {
        // Insertar detalles del archivo en la base de datos
        $id_usuario = $_SESSION['id_usuario'];
        $stmt = $conexion->prepare("INSERT INTO repositorio (id_usuario, nombre_archivo, archivo, tamaño_archivo, tipo_archivo, fecha_subida) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bindParam(1, $id_usuario);
        $stmt->bindParam(2, $nombre_archivo);
        $stmt->bindParam(3, $archivo, PDO::PARAM_LOB); // Usar PDO::PARAM_LOB para datos binarios
        $stmt->bindParam(4, $tamaño_archivo);
        $stmt->bindParam(5, $tipo_archivo);
        $stmt->bindParam(6, $fecha_subida);
        $stmt->execute();
        
        echo "El archivo se ha subido correctamente.";
    } catch (PDOException $e) {
        echo "Error al subir el archivo: " . $e->getMessage();
    }
}

// Obtener la lista de archivos del repositorio
$stmt = $conexion->query("SELECT * FROM repositorio");
$archivos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Repositorio</title>
    <link rel="shortcut icon" href="css\img\logo-removebg-preview.png" type="image/x-icon">
    <style>
             body{
                font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        form {
            margin-bottom: 20px;
        }

        form input[type="file"] {
            margin-bottom: 10px;
        }

        ul {
            list-style: none;
            padding: 0;
        }

        ul li {
            margin-bottom: 10px;
        }

        ul li a {
            text-decoration: none;
            color: #333;
        }

        ul li a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Repositorio</h1>
        <form action="repositorio.php" method="post" enctype="multipart/form-data">
        <input type="file" name="archivo" required>
        <input type="submit" name="form_submitted" value="Subir Archivo">
    </form>
        <h2>Archivos en el Repositorio</h2>
    <ul>
        <?php foreach ($archivos as $archivo): ?>
            <li>
                <a href="descargar.php?id=<?= $archivo['id_repositorio'] ?>"><?= $archivo['nombre_archivo'] ?></a> - 
                <?= $archivo['tamaño_archivo'] ?> bytes - 
                <?= $archivo['tipo_archivo'] ?> - 
                <?= $archivo['fecha_subida'] ?>
            </li>
        <?php endforeach; ?>
    </ul>
        </div>
</body>
</html>
