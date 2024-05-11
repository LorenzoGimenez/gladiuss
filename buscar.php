<?php

session_start(); // Iniciar la sesión si no se ha iniciado todavía

// Obtener el ID de usuario de la sesión si está activa
$id_usuario = isset($_SESSION['id_usuario']) ? $_SESSION['id_usuario'] : null;

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar</title>
    <link rel="shortcut icon" href="css\img\logo-removebg-preview.png" type="image/x-icon">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }
        
    body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            display: flex;
            min-height: 100vh;
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

     /* Estilos para el formulario */
     form {
            padding: 20px;
            background-color: #f2f2f2;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        input[type="submit"],
        input[type="reset"] {
            width: 48%;
            padding: 10px;
            background-color: #002D62;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover,
        input[type="reset"]:hover {
            background-color: #0056b3;
        }

        /* Estilos para la tabla */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #002D62;
            color:white;
        }
        
  

        @media screen and (max-width: 768px) {
    body, html {
        margin: 0;
        padding: 0;
        height: 100%;
        width: 100%;
    }

    .container {
        display: flex;
        flex-direction: column;
        min-height: 100vh; /* Ocupa al menos el 100% de la altura de la ventana gráfica */
    }

    .sidebar {
        width: 100%; /* Ocupa todo el ancho en dispositivos móviles */
    }

    .content {
        flex: 1;
        padding: 20px;
    }

    /* Estilos para el formulario */
    form {
        padding: 10px;
    }

    input[type="text"] {
        width: calc(100% - 20px); /* Se redujo el ancho para adaptarse mejor al ancho del dispositivo */
    }

    input[type="submit"],
    input[type="reset"] {
        margin-bottom:20px;
        width: 48%; /* Se ajustó el ancho para que quepan dos botones en una fila */
        margin-right: 4%; /* Se agregó un margen derecho para separar los botones */
    }

    table {
        font-size: 11px;
        text-align:center;
    }

    th, td {
        text-align:center;
        padding: 2px; /* Reducir el espaciado de las celdas para dispositivos móviles */
    }
}


    </style>
</head>
<body>
<div class="container">
        <div class="sidebar">
            <h2>Buscar</h2>
            <hr>
            <ul><br>
                <li><a href="dashordb.php">Inico</a></li>
                <li><a href="registro.html">Registrar Usuarios</a></li>
                <li><a href="evento.html">Cargar Evento</a></li>
                <li><a href="eventos_avance2.php">Evento Avance</a></li>
                <li><a href="notificaciones.php">Notificaciones</a></li>
                <li><a href="repositorio.php">Repositorio</a></li>
                <li><a href="notas.php">Notas</a></li>
                <li><a href="admin.php">Administrar Recursos</a></li>
            </ul>
        </div>
        <div class="content">
<form action="buscar.php" method="GET">
    <label for="busqueda">Buscar por cualquier dato o estado:</label>
    <input type="text" id="busqueda" name="busqueda" required>
    <br><br>
    <input type="submit" value="Buscar">
    <input type="reset" value="Limpiar">
</form>
<table id="eventosTable">
    <tr>
        <th>Tipo</th>
        <th>Subtipo</th>
        <th>Descripción</th>
        <th>Fecha y Hora</th>
        <th>Estado</th>
        <th>Detalles</th>
    </tr>
    <?php
 
    try {
        $dsn = 'sqlite:' . __DIR__ . '/gladius.db';

        // Conexión con la base de datos
        $pdo = new PDO($dsn);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Obtener el ID de usuario de la sesión si está activa
        $id_usuario = isset($_SESSION['id_usuario']) ? $_SESSION['id_usuario'] : null;

        // Verificar si se ha enviado el formulario y el campo de búsqueda está presente
        if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['busqueda'])) {
            $busqueda = $_GET['busqueda'];

            // Consultar eventos que coincidan con la búsqueda y el ID de usuario de la sesión
            $stmt = $pdo->prepare("SELECT tipo, subtipo, descripcion, fecha_hora, estado, id_evento FROM eventos WHERE id_usuario = ? AND (id_evento LIKE ? OR tipo LIKE ? OR subtipo LIKE ? OR descripcion LIKE ? OR fecha_hora LIKE ? OR estado LIKE ?)");
            
            // Ejecutar la consulta
            $stmt->execute([$id_usuario, "%$busqueda%", "%$busqueda%", "%$busqueda%", "%$busqueda%", "%$busqueda%", "%$busqueda%"]);
            $eventos = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Mostrar los resultados
            if (count($eventos) > 0) {
                foreach ($eventos as $evento) {
                    echo "<tr>";
                    echo "<td>" . $evento['tipo'] . "</td>";
                    echo "<td>" . $evento['subtipo'] . "</td>";
                    echo "<td>" . $evento['descripcion'] . "</td>";
                    echo "<td>" . $evento['fecha_hora'] . "</td>";
                    echo "<td>" . $evento['estado'] . "</td>";
                    // Enlace para ver los detalles del evento
                    echo "<td><a href=\"evento_avance.php?id_evento=" . $evento['id_evento'] . "\">Ver detalles</a></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No se encontraron eventos que coincidan con la búsqueda.</td></tr>";
            }
        }
        
    } catch(PDOException $e) {
        echo "<tr><td colspan='6'>Error al procesar la búsqueda: " . $e->getMessage() . "</td></tr>";
    }
    ?>


</table>
</div>
</div>
<script>
     function limpiarTabla() {
        var table = document.getElementById("eventosTable");
        // Eliminar todas las filas de la tabla excepto la primera (encabezado)
        while (table.rows.length > 1) {
            table.deleteRow(1);
        }
    }
</script>

</body>
</html>
